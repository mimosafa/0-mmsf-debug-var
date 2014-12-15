<?php

/*
Plugin Name: MMSF Degug Var
Plugin URI: 
Description: 
Author: mmsf
Version: 0.0
Author URI: http://mimosafa.me/
*/

/**
 *
 */
if ( !function_exists( '_var_dump' ) ) {
	function _var_dump( $var ) {
		mmsf_var_dump::vars( $var, $hook = '' );
	}
}

/**
 *
 */
class mmsf_var_dump {

	private $vars;
	private $file;
	private $line;

	private $hook = '';

	private function __construct( $var, $hook ) {
		$this -> vars = $var;
		$backtrace = debug_backtrace();
		foreach ( $backtrace as $arg ) {
			if ( $arg['file'] === __FILE__ ) {
				continue;
			}
			$this -> file = $arg['file'];
			$this -> line = $arg['line'];
			break;
		}

		if ( !$hook ) {
			if ( is_admin() ) {
				$this -> hook = 'admin_notices';
			} else {
				$this -> hook = 'wp_footer';
			}
		}

		if ( $this -> hook ) {
			add_action( $this -> hook, [ $this, 'var_dump' ] );
		}
	}

	public function var_dump() {
		?>
<div class="message updated">
  <dl>
    <dt>File</dt>
    <dd><?= $this -> file ?></dd>
    <dt>Line</dt>
    <dd><?= $this -> line ?></dd>
  <pre>
<?= var_dump( $this -> vars ) ?>
  </pre>
</div>
		<?php
	}

	public static function vars( $var, $hook ) {
		$cl = new self( $var, $hook );
	}

}
