<?php
 /**
 *
 * Example Child Theme AMP-WP
 * 
 * Alexander Graef
 * portalzine.de
 *
 */
	
  // Add Custom Template for AMP-WP
  add_filter( 'amp_post_template_file', 'xyz_amp_set_custom_template', 10, 3 );

  function xyz_amp_set_custom_template( $file, $type, $post ) {
  	if ( 'single' === $type ) {
	  $file = dirname( __FILE__ ) . '/amp/amp_template.php';
  	}
  	return $file;
  }
