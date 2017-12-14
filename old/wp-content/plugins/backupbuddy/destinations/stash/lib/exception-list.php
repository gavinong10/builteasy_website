<?php

    //-----------------------------------------------------------------------------
    // array('id'=>4000,   'ex'=>'',                     'msg'=>''),
    //-----------------------------------------------------------------------------
    
    $exceptions = array(
        
    'apikey'=> array(
        
        array('id'=>1000,   'ex'=>'ITXAPI_Key_Missing',                                'msg'=>'The requested resource requires an API key for access but none was provided in the request.'),
        array('id'=>1001,   'ex'=>'ITXAPI_Key_Invalid',                                'msg'=>'The request API key is not formatted correctly.'),
        array('id'=>1002,   'ex'=>'ITXAPI_Key_Unknown',                                'msg'=>'The request API key is not valid.'),
        array('id'=>1003,   'ex'=>'ITXAPI_Key_Update_Fail',                            'msg'=>'Could not update record for request API key.'),
        array('id'=>1004,   'ex'=>'ITXAPI_Key_Disabled',                               'msg'=>'The request API key has been disabled.'),
        array('id'=>1005,   'ex'=>'ITXAPI_Key_Expired',                                'msg'=>'The request API key has expired.'),
        array('id'=>1006,   'ex'=>'ITXAPI_Quota_Exceeded',                             'msg'=>'The request quota for this API key has been reached.'),
        array('id'=>1007,   'ex'=>'ITXAPI_Access_Denied',                              'msg'=>'The request API key does not have permission to access the requested resource.'),
        array('id'=>1008,   'ex'=>'ITXAPI_Unavailable',                                'msg'=>'API backed resources are currently unavailable.'),
        array('id'=>1009,   'ex'=>'ITXAPI_Unavailable_Temporary',                      'msg'=>'API backed resources are temporarily unavailable. Try again later.'),
        array('id'=>1010,   'ex'=>'ITXAPI_Maintenance',                                'msg'=>'API backed resources are currently unavailable due to system maintenance.'),
        array('id'=>1011,   'ex'=>'ITXAPI_Temporary_Limit',                            'msg'=>'The request API key has been temporarily rate-limited.'),
        
    ),
    
    'subscriber'=> array(
        
        array('id'=>2000,   'ex'=>'ITXAPI_Subscriber_Missing',                         'msg'=>'A valid subscriber was not provided in the request.'),
        array('id'=>2001,   'ex'=>'ITXAPI_Subscriber_Invalid',                         'msg'=>'The subscriber is not formatted correctly.'),
        array('id'=>2002,   'ex'=>'ITXAPI_Subscriber_Unknown',                         'msg'=>'Unknown subscriber account.'),
        array('id'=>2003,   'ex'=>'ITXAPI_Subscriber_Unverified',                      'msg'=>'This subscriber has not been verified.'),
        array('id'=>2004,   'ex'=>'ITXAPI_Subscriber_Disabled',                        'msg'=>'This subscriber account has been disabled.'),
        array('id'=>2005,   'ex'=>'ITXAPI_Subscriber_Expired',                         'msg'=>'A valid subscription is required to access this resources, but it has expired.'),
        
    ),
    
    'signed_request'=> array(
        
        array('id'=>3000,   'ex'=>'ITXAPI_Signed_Request_Expired',                     'msg'=>'The signed request has expired.'),
        array('id'=>3001,   'ex'=>'ITXAPI_Signed_Request_Signature_Missing',           'msg'=>'Signature missing from signed request.'),
        array('id'=>3002,   'ex'=>'ITXAPI_Signed_Request_Signature_Bad',               'msg'=>'The signed request signature did not match.'),
        
    ),
    
    'stash'=> array(
        
        array('id'=>4000,   'ex'=>'ITXAPI_Stash_Provision_Failure',                    'msg'=>'Stash could not provision a new subscriber account.'),
        array('id'=>4001,   'ex'=>'ITXAPI_Stash_Subscriber_Missing',                   'msg'=>'Stash could not find the requested subscriber.'),
        array('id'=>4002,   'ex'=>'ITXAPI_Stash_Subscriber_Expired',                   'msg'=>'You must have an active iThemes subscription to access Stash.'),
        array('id'=>4003,   'ex'=>'ITXAPI_Stash_Subscriber_Disabled',                  'msg'=>'This Stash subscriber account has been disabled.'),
        array('id'=>4004,   'ex'=>'ITXAPI_Stash_Subscriber_Locked',                    'msg'=>'This Stash subscriber account has been locked.'),
        array('id'=>4005,   'ex'=>'ITXAPI_Stash_Filename_Missing',                     'msg'=>'A valid filename was not passed with the request.'),
        array('id'=>4006,   'ex'=>'ITXAPI_Stash_Filename_Bad_Encoding',                'msg'=>'The filename passed with this request was malformed.'),
        array('id'=>4007,   'ex'=>'ITXAPI_Stash_Filename_Bad_Format',                  'msg'=>'The filename passed with this request contained invalid characters.'),
        array('id'=>4008,   'ex'=>'ITXAPI_Stash_Hash_Missing',                         'msg'=>'The upload request is missing a hash.'),
        array('id'=>4009,   'ex'=>'ITXAPI_Stash_Hash_Bad_Format',                      'msg'=>'The upload request has is not formatted correctly or contains invalid characters.'),
        array('id'=>4010,   'ex'=>'ITXAPI_Stash_Size_Missing',                         'msg'=>'The size passed with this request is missing.'),
        array('id'=>4011,   'ex'=>'ITXAPI_Stash_Size_Bad_Format',                      'msg'=>'The size passed with this request is not formatted correctly or contains invalid characters.'),
        array('id'=>4012,   'ex'=>'ITXAPI_Stash_Storage_Quota_Exceeded',               'msg'=>'The requested file could not be uploaded because there is not enough free space.'),
        array('id'=>4013,   'ex'=>'ITXAPI_Stash_File_Count_Exceeded',                  'msg'=>'The request file couldn not be uploaded because there are too many files.'),
        array('id'=>4014,   'ex'=>'ITXAPI_Stash_Too_Many_Uploads',                     'msg'=>'The requested file could not be uploaded because there are too many active uploads.'),
        array('id'=>4015,   'ex'=>'ITXAPI_Stash_Upload_Started',                       'msg'=>'The requested file could not be uploaded because an upload has already started.'),
        array('id'=>4016,   'ex'=>'ITXAPI_Stash_Upload_Disabled',                      'msg'=>'Uploading for this Stash subscriber account has been temporarily disabled.'),
        array('id'=>4017,   'ex'=>'ITXAPI_Stash_Quota_Update_Failed',                  'msg'=>'Could not update the quota for this Stash subscriber account.'),
        array('id'=>4018,   'ex'=>'ITXAPI_Stash_Upload_Allocate_Failed',               'msg'=>'An upload slot for the requested file could not be reserved.'),
        array('id'=>4019,   'ex'=>'ITXAPI_Stash_Upload_Unknown',                       'msg'=>'The requested upload could not be found.'),
        array('id'=>4020,   'ex'=>'ITXAPI_Stash_Upload_File_Mismatch',                 'msg'=>'The requested upload could not be verified.'),
        array('id'=>4021,   'ex'=>'ITXAPI_Stash_Upload_Deallocate_Failed',             'msg'=>'An upload slot for the requested file could not be released.'),
        array('id'=>4022,   'ex'=>'ITXAPI_Stash_Upload_Replicate_Failed',              'msg'=>'The requested upload could not be replicated.'),
        array('id'=>4023,   'ex'=>'ITXAPI_Stash_Upload_Delete_Failed',                 'msg'=>'The requested upload could not be deleted.'),
                
    ),
    
    'aws'=> array(
        
        array('id'=>5000,   'ex'=>'ITXAPI_AWS_Config_Missing',                         'msg'=>'Could not find configuration for AWS service.'),
        array('id'=>5001,   'ex'=>'ITXAPI_AWS_STS_Error',                              'msg'=>'The AWS STS request had an error. Try again later.'),
        array('id'=>5002,   'ex'=>'ITXAPI_AWS_List_Object_Failure',                    'msg'=>'Could not list requested objects.'),
        
    )
           
    
    );
