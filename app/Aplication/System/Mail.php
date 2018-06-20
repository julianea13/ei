<?php
namespace ThisApp\Aplication\System;

use \ThisApp\Aplication\System\Config;
use \Mailgun\Mailgun;
use \Http\Adapter\Guzzle6\Client;
use \Exception;

class Mail {
	public static function send($nameTo, $mailTo,$subject,$text,$html, array $replaceVars = [])
	{
		# Instantiate the client.
		$client = new Client();
		$mailgun = new Mailgun(Config::get('mailgun/api_key'), $client);

		# Make the variables
		$domain = Config::get('mailgun/domain');		
		$from = "Trébol <no-responder@".$domain.">";
		$to = $nameTo.' <'.$mailTo.'>';
		$html = file_get_contents($html);
		$replaceVars["link"] = 'http://'.$replaceVars["link"];

		foreach ($replaceVars as $search => $replace) {
			$html = str_replace("[".$search."]", $replace, $html);
		}		
		# Make the call to the client.		
		$vars = ['from'    => $from,
			    'to'      => $to,
			    'subject' => $subject,
			    'text'    => $text,
			    'html'    => $html
		];
		$result = $mailgun->sendMessage($domain, $vars);
		if ($result->http_response_code == 200)		
				return true;
			else				
       			return false;
	}
}
