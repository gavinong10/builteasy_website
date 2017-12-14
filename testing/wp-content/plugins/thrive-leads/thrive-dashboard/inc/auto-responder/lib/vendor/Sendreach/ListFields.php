<?php
/**
 * This file contains the lists fields endpoint for MailWizzApi PHP-SDK.
 * 
 * @author Serban George Cristian <cristian.serban@mailwizz.com>
 * @link http://www.mailwizz.com/
 * @copyright 2013-2015 http://www.mailwizz.com/
 */
 
 
/**
 * MailWizzApi_Endpoint_ListFields handles all the API calls for handling the list custom fields.
 * 
 * @author Serban George Cristian <cristian.serban@mailwizz.com>
 * @package MailWizzApi
 * @subpackage Endpoint
 * @since 1.0
 */
class Thrive_Dash_Api_Sendreach_ListFields extends Thrive_Dash_Api_Sendreach
{
    /**
     * Get fields from a certain mail list
     * 
     * Note, the results returned by this endpoint can be cached.
     * 
     * @param string $listUid
     * @return Thrive_Dash_Api_Sendreach_Response
     */
    public function getFields($listUid)
    {
        $client = new Thrive_Dash_Api_Sendreach_Client(array(
            'method'        => Thrive_Dash_Api_Sendreach_Client::METHOD_GET,
            'url'           => $this->config->getApiUrl(sprintf('lists/%s/fields', $listUid)),
            'paramsGet'     => array(),
            'enableCache'   => true,
        ));
        
        return $response = $client->request();
    }
}