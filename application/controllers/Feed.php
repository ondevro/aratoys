<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feed extends Public_Controller {

	public function xml()  {
		redirect('/v1/xml', 'location');
	}
}