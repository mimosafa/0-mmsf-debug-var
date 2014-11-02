<?php

/*
Plugin Name: MMSF Degug Var
Plugin URI: 
Description: 
Author: mmsf
Version: 0.0
Author URI: http://mimosafa.me/
*/

class mmsf_var_dump {

	private $vars;

	private $hook = '';

	private function __construct( $var, $hook = '' ) {
		$this -> vars = $var;

		if ( !$hook ) {
			if ( is_admin() ) {
				$this -> hook = 'admin_notices';
			}
		}

		if ( $this -> hook ) {
			add_action( $this -> hook, [ $this, 'var_dump' ] );
		}
	}

	public function var_dump() {
		echo '<pre>';
		var_dump( $this -> vars );
		echo '</pre>';
	}

	public static function vars( $var, $hook = '' ) {
		$cl = new self( $var );
	}

}

/**
 *
 */
if ( !function_exists( '_var_dump' ) ) {
	function _var_dump( $var ) {
		mmsf_var_dump::vars( $var );
	}
}
