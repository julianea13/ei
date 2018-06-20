<?php
namespace ThisApp\Aplication\System;

class Config{
/**
 * @type array
 */

// belong = proyectos :1 ; instituciones :2 , historial:3
// id_belong = id de la tabla 
	private static $globals = array(
			'mysql' => array(
				'host' =>  'localhost',
				'username'=> 'root',
				'password' => '',
				'db' => 'escuela'
				),
			'remember' => array(
				'cookie_name' => 'hash',
				'cookie_expiry' => 604800
				),
			'system' => array(
				'domain' => 'trebol.cid.edu.co',
				'root' => 'public',
				'home' => 'instituciones',
				'errors' => array('404','500','403'),
				'enc_method' => 'aes-128-cbc',
				'enc_pass' => 'tr3b0l2018c3ntr0d31nn0v4c10n',
				'metas'=> array(
						'title' => 'Trébol',
						'description' => 'Mesas de trabajo público-privadas, Secretaría de educacion de Envigado.',
						'image' => 'https://trebol.cid.edu.co/assets/demo/default/media/img/logo/logo-12.png',
						'url' => 'https://trebol.cid.edu.co',
						)
				),
			'mailgun' => array(
				// 'api_key' => 'key-a147bc0ccd76e2396162a2a4bd12d07a',
				// 'domain' => 'ecosistemasidi.com'
				'api_key' => 'key-b9646503ea05708d7cee16ea0290f7b7',
				'domain' => 'developers.cid.edu.co'
				),
			'session' => array(
				'session_name' => 'user',
				'token_name' => 'token',
				'user_name' => 'name',
				'user_rol' => 'rol',
				'user_mail' => 'mail',
				'flash_msg' => 'flash',
				'menu_sent' => 'menu'
				)
			);

	public static function get($path = null){
		if ($path) {
			$config = self::$globals;
			$path = explode('/', $path);
			foreach ($path as $bit) {
				if (isset($config[$bit])) {
					$config = $config[$bit];
				}
			}
			return $config;
		}

		return false;
	}
}
