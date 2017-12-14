<?php

class Thrive_Dash_Api_MailerLite_Lists extends Thrive_Dash_Api_MailerLite_Rest {

	public function __construct($apiKey)
	{
		$this->name = 'lists';
		parent::__construct($apiKey);
	}

	public function getActive($data = null)
	{
		$this->path .= 'active/';
		return $this->execute('GET', $data);
	}

	public function getUnsubscribed($data = null)
	{
		$this->path .= 'unsubscribed/';
		return $this->execute('GET', $data);
	}

	public function getBounced($data = null)
	{
		$this->path .= 'bounced/';
		return $this->execute('GET', $data);
	}
	
}
