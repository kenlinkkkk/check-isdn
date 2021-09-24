<?php
/**
 * Created by PhpStorm.
 * User: TungNT
 * Date: 08/28/2019
 * Time: 10:49 AM
 */

class Root extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
	}

	/**
	 * mapping web by modules
	 */
	public function _remap() {
		$html = '';

		$segment1 = $this->uri->segment(1);

		switch ($segment1) {
			case 'header':
				$html = modules::run('Welcome');
				break;
			case 'isdn':
				$html = modules::run('GetHeader');
				break;
			default:
				$html = modules::run('CheckIsdnController');
		}

		$this->output->set_output($html);
	}
}
