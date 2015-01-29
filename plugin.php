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
	function _var_dump( $var, $back_to = 1 ) {
		mmsf_var_dump::vars( $var, $back_to );
	}
}

/**
 *
 */
class mmsf_var_dump {

	private $vars;
	private $backtrace = array();

	private function __construct( $var, $back_to ) {
		$this->vars = $var;
		$debug_backtrace = debug_backtrace();
		foreach ( $debug_backtrace as $arg ) {
			if ( $arg['file'] === __FILE__ ) {
				continue;
			}
			$this -> backtrace[] = array(
				'file' => $arg['file'],
				'line' => $arg['line']
			);
			$back_to--;
			if ( !$back_to ) {
				break;
			}
		}

		$hook = is_admin() ? 'admin_notices' : 'wp_footer';
		add_action( $hook, array( $this, 'var_dump' ) );
	}

	public function var_dump() {
		?>
<div class="message updated">
  <?php foreach ( $this->backtrace as $array ) { ?>
  <dl>
    <dt>File</dt>
    <dd><?php echo $array['file']; ?></dd>
    <dt>Line</dt>
    <dd><?php echo $array['line']; ?></dd>
  </dl>
  <?php } ?>
  <pre>
<?= var_dump( $this -> vars ) ?>
  </pre>
</div>
		<?php
	}

	public static function vars( $var, $back_to ) {
		if ( absint( $back_to ) && 0 < $back_to ) {
			$cl = new self( $var, $back_to );
		}
	}

}
