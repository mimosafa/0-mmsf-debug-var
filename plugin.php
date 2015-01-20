<?php

/*
Plugin Name: MMSF Degug Var
Plugin URI: 
Description: 
Author: mmsf
Version: 1.0
Author URI: http://mimosafa.me/
*/

/**
 *
 */
if ( !function_exists( '_var_dump' ) ) {
	function _var_dump( $var ) {
		MMSF_DEBUG_VAR::var_dump( $var );
	}
}

if ( defined( 'WP_DEBUG' ) && true === \WP_DEBUG ) {
	MMSF_DEBUG_VAR::init();
}

/**
 *
 */
class MMSF_DEBUG_VAR {

	const ACTION_HOOK   = 'admin_notices';

	/**
	 * @var array
	 */
	private static $vars = [];

	public static function init() {
		add_action( MMSF_DEBUG_VAR::ACTION_HOOK, 'MMSF_DEBUG_VAR::display_vars' );
	}

	/**
	 *
	 */
	public static function var_dump( $var ) {
		if ( did_action( MMSF_DEBUG_VAR::ACTION_HOOK ) ) {
			//
		} else {
			$vars = array();
			$vars['var'] = $var;
			$backtrace = debug_backtrace( false );
			foreach ( $backtrace as $arg ) {
				if ( $arg['file'] === __FILE__ ) {
					continue;
				}
				$vars['file'] = $arg['file'];
				$vars['line'] = $arg['line'];
				break;
			}
			MMSF_DEBUG_VAR::$vars[] = $vars;
		}
	}

	/**
	 *
	 */
	public static function display_vars() {
		if ( is_super_admin() && MMSF_DEBUG_VAR::$vars ) {
			foreach ( MMSF_DEBUG_VAR::$vars as $vars ) {
				?>
    <div class="message updated">
      <dl>
        <dt>File</dt>
        <dd><?= $vars['file'] ?></dd>
        <dt>Line</dt>
        <dd><?= $vars['line'] ?></dd>
      <pre>
    <?= var_dump( $vars['var'] ) ?>
      </pre>
    </div>
				<?php
			}
		}
	}

}
