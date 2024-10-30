<?php
/*
Plugin Name: Custom Inspect Elements
Description: If users who can't edit posts inspect elements Custom Inspect Elements will show a custom specific content.
Text Domain: eos-ins-off
Domain Path: /languages/
Version: 0.0.4
*/
/*  This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
add_action( 'wp_head','eos_ins_off_script_function' );
add_action( 'wp_footer','eos_ins_off_script_execution' );
add_action( 'wp_body_open','eos_ins_off_script_execution' );
//Add JavaScript function to hide the page if Inspect Elements is open
function eos_ins_off_script_function(){
	?>
	<script>
	function eos_ins_inpsect_elements_open(){
		if(navigator.userAgent.toLowerCase().indexOf('firefox') > -1){
			var threshold = 160,widthThreshold = window.outerWidth - window.innerWidth > threshold,
				heightThreshold = window.outerHeight - window.innerHeight > threshold,
				orientation = widthThreshold ? 'vertical' : 'horizontal';

			if(!(heightThreshold && widthThreshold) &&
			  ((window.Firebug && window.Firebug.chrome && window.Firebug.chrome.isInitialized) || widthThreshold || heightThreshold)
			){
				eos_ins_update_html();
				window.clearInterval(window.eos_ins_interval);
				return false;
			}
		}
		else{
			var element = new Image,devtoolsOpen = false;
			element.__defineGetter__("id", function() {
				devtoolsOpen = true;
			});
			devtoolsOpen = false;
			if('undefined' === typeof(window.eos_ins_interval)){
				window.eos_ins_interval = setInterval(function(){
					console.log(element);
					if(devtoolsOpen){
						eos_ins_update_html();
						window.clearInterval(window.eos_ins_interval);
						return false;
					}
				},200);
			}
		}
	}
	function eos_ins_update_html(){
		document.getElementsByTagName('body')[0].innerHTML = '<div style="text-align:center;margin-top:32px">Just a moment...</div>';
		var eosinsRequest = new XMLHttpRequest();
		window.clearInterval(window.eos_ins_interval);
		console.clear();
		eosinsRequest.onload = function(e) {
			if(this.readyState === 4) {
				if('' !== e.target.responseText){
					document.getElementsByTagName('body')[0].innerHTML = e.target.responseText;
				}

			}
			return false;
		};		
		eosinsRequest.open("POST","<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>" + '?action=eos_ins_custom_element',true);
		eosinsRequest.send();
		throw new Error("Your are not allowed to inspect elements!");
		return false;
	}
	</script>
	<?php
}
//Execut JavaScript function to hide the page if Inspect Elements is open
function eos_ins_off_script_execution(){
	if( current_user_can( 'edit_posts' ) ) return;
	if( defined( 'DOING_AJAX' ) && DOING_AJAX || isset( $_REQUEST['action'] ) ) return;
	?>
	<script>
	eos_ins_inpsect_elements_open();
	window.onresize = eos_ins_inpsect_elements_open;
	</script>
	<?php
}
if ( ! function_exists( 'wp_body_open' ) ) {
    /**
     * Fire the wp_body_open action.
     *
     * Added for backwards compatibility to support WordPress versions prior to 5.2.0.
     */
    function wp_body_open() {
        /**
         * Triggered after the opening <body> tag.
         */
        do_action( 'wp_body_open' );
    }
}
add_action( 'after_setup_theme','eos_ins_off_after_setup_theme' );
//Register custom post typeof
function eos_ins_off_after_setup_theme(){
	/*add costum post inspect content*/
	register_post_type( 'eos_ins_el_content', array(
		'label' => __( 'Inspection Contents','eos-ins-off' ),
		'labels' => array(
			'singular_name' => __( 'Inspection Content','eos-ins-off' ),
			'add_new_item' => __( 'Add a new inspection content element','eos-ins-off' ),
			'edit_item' => __( 'Edit inspection content element','eos-ins-off' ),
			'new_item' => __( 'New inspection content element','eos-ins-off' ),
			'view_item' => __( 'Show','eos-ins-off' ),
			'search_items' => __( 'Search for inspection content element','eos-ins-off' ),
			'not_found' => __( 'No inspection content elements were found','eos-ins-off' ),
			'not_found_in_trash' => __( 'No inspection content elements were found in Trash','eos-ins-off' ),
		),
		'public' => true,
		'show_ui' => true,
		'show_in_admin_bar' => false,
		'menu_icon' => 'dashicons-tagcloud',
		'show_in_nav_menus' => false,
		'capability_type' => 'post',
		'capabilities' => array(
			'publish_posts' => 'delete_others_pages',
			'edit_posts' => 'delete_others_pages',
			'edit_others_posts' => 'delete_others_pages',
			'delete_posts' => 'delete_others_pages',
			'delete_others_posts' => 'delete_others_pages',
			'read_private_posts' => 'delete_others_pages',
			'edit_post' => 'delete_others_pages',
			'delete_post' => 'delete_others_pages',
			'read_post' => 'delete_others_pages',
		),			
		'has_archive' => false,
		'exclude_from_search' => true,
		'rewrite' => array(
			'slug' => 'eos_ins_el_content'
		),
		'query_var' => false,
		'publicly_queryable'  => false,
		'supports' => array(
			'title',
			'editor',
			'revisions',
		) 
	) );
}	
add_action( 'wp_ajax_nopriv_eos_ins_custom_element','eos_ins_custom_element' );
//Return custom element for inspecting elements
function eos_ins_custom_element(){
	$posts = get_posts( array( 'post_status' => 'public','posts_per_page' => 1,'post_type' => 'eos_ins_el_content' ) );
	$content = '<div id="eos-ins-wrp">';
	if( $posts && is_array( $posts ) && !empty( $posts ) && is_object( $posts[0] ) ){
		$post = $posts[0];
		if( class_exists( 'EOSBMap' ) ){
			EOSBMap::addAllMappedShortcodes();
		}
		if( function_exists( 'eosb_include_not_mapped_shortcodes' ) ){
			eosb_include_not_mapped_shortcodes();
		}
		$content .= do_shortcode( apply_filters( 'the_content',$post->post_content ) );
	}
	$content .= '</div>';
	echo $content;
	die();
	exit;
}
if( defined( 'CIE_NOJS_REDIRECT' ) && CIE_NOJS_REDIRECT ){
	add_action( 'wp_head','eos_ins_redirect_on_nojs',1 );
}
//Redirect if JS is not enabled
function eos_ins_redirect_on_nojs(){
	if( !isset( $_GET['no-js'] ) ){
	?>
	<noscript>
		<style>html{display:none}</style>
		<meta http-equiv="refresh" content="0;url=?no-js=true" />
	</noscript>
	<?php
	}	
}
if( isset( $_GET['no-js'] ) ){
	add_action( 'init','eos_ins_content_if_no_js' );
}
//Display Ispect Content in case of JS disabled
function eos_ins_content_if_no_js(){
	?>
	<div style="padding:10px;margin-top:0;margin-left:0;margin-right:0;background:#000;color:#fff;text-align:center;font-size:20px;font-family:Arial">
		<?php esc_html_e( 'Website content not available if JavaScript is disabled. Please enable JavaScript in your browser if you want to see this website.','eos-ins-off' ); ?></div>
	<?php
	exit;
}