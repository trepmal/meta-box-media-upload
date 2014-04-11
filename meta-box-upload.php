<?php
/*
 * Plugin Name: Meta Box Media Upload
 * Plugin URI: trepmal.com
 * Description:
 * Version:
 * Author: Kailey Lampert
 * Author URI: kaileylampert.com
 * License: GPLv2 or later
 * TextDomain: some-meta-box
 * DomainPath:
 * Network: false
 */

$meta_box_media_upload = new Meta_Box_Media_Upload();

class Meta_Box_Media_Upload {

	function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'setup_box' ) );
		add_action( 'save_post', array( $this, 'save_box' ), 10, 2 );
	}

	function setup_box() {
		add_meta_box( 'meta_box_id', __( 'Meta Box Media Upload', 'some-meta-box' ), array( $this, 'meta_box_contents' ), 'post', 'normal' );
	}

	function meta_box_contents() {

		wp_enqueue_media();
		wp_enqueue_script( 'meta-box-media', plugins_url('media.js', __FILE__ ), array('jquery') );

		wp_nonce_field( 'nonce_action', 'nonce_name' );

		// one or more
		$field_names = array( 'meta-box-media-name', 'another-meta-box-media-name' );

		foreach ( $field_names as $name ) {

			$value = get_post_meta( get_the_id(), $name, true );

			echo "<input type='hidden' id='$name-value'  class='small-text'       name='meta-box-media[$name]'            value='$value' />";
			echo "<input type='button' id='$name'        class='button meta-box-upload-button'        value='Upload' />";
			echo "<input type='button' id='$name-remove' class='button meta-box-upload-button-remove' value='Remove' />";

			$value = ! $value ? '' : wp_get_attachment_image( $value, 'full', false, array('style' => 'max-width:100%;height:auto;') );

			echo "<div class='image-preview'>$value</div>";

			echo '<br />';

		}

	}

	function save_box( $post_id, $post ) {

		if ( ! isset( $_POST['nonce_name'] ) ) //make sure our custom value is being sent
			return;
		if ( ! wp_verify_nonce( $_POST['nonce_name'], 'nonce_action' ) ) //verify intent
			return;
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) //no auto saving
			return;
		if ( ! current_user_can( 'edit_post', $post_id ) ) //verify permissions
			return;

		$new_value = array_map( 'intval', $_POST['meta-box-media'] ); //sanitize
		foreach ( $new_value as $k => $v ) {
			update_post_meta( $post_id, $k, $v ); //save
		}

	}

}