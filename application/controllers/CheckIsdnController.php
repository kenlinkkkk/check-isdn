<?php

class CheckIsdnController extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
	}

	public function index()
	{
		$cpurl = urldecode($this->input->get('cpurl'));
		$headers = $this->getRequestHeaders();
		$msisdn = '';


		if (SERVER_ZONE == 'mbf') {
			$ipCheck = $_SERVER['REMOTE_ADDR'];
			if ($this->checkIpInRange($ipCheck)) {
				$msisdn = $headers['Msisdn'];
			}
		} elseif (SERVER_ZONE == 'vnp') {
			$msisdn = $headers['Msisdn'];
		}
		$cpUrl = $cpurl .'?isdn=' . $msisdn;
		return redirect($cpUrl);
	}

	public function getRequestHeaders(): array
	{
		$headers = array();
		foreach ($_SERVER as $key => $value) {
			if (substr($key, 0, 5) <> 'HTTP_') {
				continue;
			}
			$header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
			$headers[$header] = $value;
		};

		return $headers;
	}

	public function checkIpInRange($ip = null): bool
	{
		$myFile = fopen('public/iprange/iprange.txt', 'r');
		while (!feof($myFile)) {
			$line = fgets($myFile);

			list ($net, $mask) = explode("/", $line);
			$ipnet = ip2long($net);
			$ipmask = ~((1 << (32 - $mask)) - 1);

			$ip_ip = ip2long($ip);
			$ip_ip_net = $ip_ip & $ipmask;

			if ($ip_ip_net == $ipnet) {
				return true;
			}
		}
		return false;
	}
}
