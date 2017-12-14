<?php

    //-----------------------------------------------------------------------------
    
    class ITXAPI_Helper
    {
    
        //-----------------------------------------------------------------------------
        
        protected $apikey;
        
        protected $apiurl;    
        
        protected $_username;
        protected $_password;
        
        //-----------------------------------------------------------------------------
        
        public function __construct($apikey, $apiurl, $username, $password)
        {
        	
        	$username = strtolower( $username ); // Normalize username to lower case.
        	
            $this->apikey = $apikey;
            $this->apiurl = $apiurl;
            
            $this->_username = $username;
            $this->_password = $password;
            
        }
        
        //-----------------------------------------------------------------------------                
        
        protected function format_api_url($resource, $params = array())
        {
            
            $params['apikey'] = $this->apikey;
            
            return sprintf('%s/%s?%s', $this->apiurl, $resource, http_build_query($params, null, '&'));
            
        }
        
        //-----------------------------------------------------------------------------
        
        protected function hmac_SHA1($key, $data, $blocksize = 64)
        {
            if (strlen($key) > $blocksize) $key = pack('H*', sha1($key));
            
            $key = str_pad($key, $blocksize, chr(0x00));
            
            $ipad = str_repeat(chr(0x36), $blocksize);
            
            $opad = str_repeat(chr(0x5c), $blocksize);
            
            $hmac = pack( 'H*', sha1( ($key ^ $opad) . pack( 'H*', sha1( ($key ^ $ipad) . $data )) ));
            
            return base64_encode($hmac);
        
        }
        
        //-----------------------------------------------------------------------------
        
        public static function get_password_hash($username, $password)
        {
            $username = strtolower( $username ); // Normalize username to lower case.
            return sha1('itxapi'.$username.$password);
            
        }
        
        //-----------------------------------------------------------------------------
        
        protected function format_signed_url($resource, $username, $password, $params = array(), $expires = 0, $type = 'GET')
        {
            $username = strtolower( $username ); // Normalize username to lower case.
            
            if($expires == 0)
                $expires = time() + 720; // 12min window for now to account for server times being off.
            
            $signed_parts = array('apikey'=>$this->apikey,
                                  'request'=>strtoupper($type),
                                  'expires'=>intval($expires),
                                  'resource'=>$resource,
                                  'subscriber'=>$username,
                                  'password'=>$password);
            
            
            if(count($params))
                foreach($params as $key=>$param)
                    $signed_parts[$key] = $param;                            
                
                
            $signature = self::hmac_SHA1($signed_parts['password'], implode("\n", $signed_parts));
            
            unset($signed_parts['request']);
            
            unset($signed_parts['resource']);
            
            unset($signed_parts['password']);
            
            $signed_parts['signature'] = $signature;
            
            return $this->format_api_url($resource, $signed_parts);
            
            
        }
        
        //-----------------------------------------------------------------------------

        public function get_upload_url($src_file, $type = 'request', $dest_file = null)
        {
            
            if($type!='abort-all')
                $size = filesize($src_file);
            else
                $size = 0;
                
            $hash = md5('itxapi::stash::upload');
            
            if(empty($dest_file))
                $dest_file = $src_file;
            
                        
            switch($type)
            {
                case 'request':     return $this->format_signed_url('stash/upload', $this->_username, $this->_password, array('filename'=>base64_encode($dest_file), 'hash'=>$hash, 'size'=>$size));
                    
                case 'done':        return $this->format_signed_url('stash/upload-done', $this->_username, $this->_password, array('filename'=>base64_encode($dest_file), 'hash'=>$hash, 'size'=>$size));
                    
                case 'abort':       return $this->format_signed_url('stash/upload-abort', $this->_username, $this->_password, array('filename'=>base64_encode($dest_file), 'hash'=>$hash, 'size'=>$size));
                    
                case 'abort-all':   return $this->format_signed_url('stash/upload-abort-all', $this->_username, $this->_password);
                    
                default:            throw new Exception('Unknown type of upload url');
            
            }                        
            
        }
        
        //----------------------------------------------------------------------------- 
        
        public function get_manage_url()
        {
            
            return $this->format_signed_url('stash/manage', $this->_username, $this->_password);
            
        }
        
        //-----------------------------------------------------------------------------
        
        public function get_quota_url()
        {
            
            return $this->format_signed_url('stash/quota', $this->_username, $this->_password);
            
        }
        
        //-----------------------------------------------------------------------------
        
         public function get_files_url()
        {
            
            return $this->format_signed_url('stash/files', $this->_username, $this->_password);
            
        }
        
        //-----------------------------------------------------------------------------
        
    }
    
    //-----------------------------------------------------------------------------