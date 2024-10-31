<?php
/*
Plugin Name: Protected Post Personalizer
Plugin URI: http://glot.homepie.org/plugins/protected-post-personalizer/
Description: Customize the display of private and password-protected posts: titles, previews, and password forms.
Version: 0.6
Author: Orin Zebest
Author URI: http://glot.homepie.org/about/me/
*/

/*

Boilerplate for Apache 2.0 included, so as to play nice with the GPL. My feelings: credit where due, build on it as you like, and don't litigate anyone if it doesn't do what you think it will.

 Copyright 2008 Orin Zebest
 
 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at 
 
   http://www.apache.org/licenses/LICENSE-2.0 
   
 Unless required by applicable law or agreed to in writing, software 
 distributed under the License is distributed on an "AS IS" BASIS, 
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. 
 See the License for the specific language governing permissions and 
 limitations under the License. 

*/


////////// Load Wordpress defaults, first time
add_option( 'password-protected-prefix', 'Protected:&nbsp;', 'Text to put before password-protected posts', TRUE );

add_option( 'private-prefix', 'Private:&nbsp;', 'Text to put before private posts', TRUE );

add_option( 'custom-preview-text', '<p>This post is password protected. To view it please enter your password below:</p>', 'Text which appears in password-protected posts along with password form', TRUE);

add_option( 'password-form-prefix', __('Password:&nbsp;'), 'Text which appears before password input box', TRUE);

add_option( 'password-form-submit', __('Submit'), 'Text which appears before password input box', TRUE);



////////// Take default post prefixes, change to user-defined prefixes
function replace_existing_prefix( $_title ) {
 global $post;
	
	$password_text = get_option('password-protected-prefix');
	$private_text = get_option('private-prefix');

	if ( !is_admin() ) {
		if( !empty( $post->post_password ) ) {
			$local_prefix = strlen(__('Protected: ')); // get length in local language
			if (strstr($_title, __('Protected: '))) {
				$_title = substr_replace($_title, $password_text, 0, $local_prefix);
			}
			} 
		else if( isset( $post->post_status ) && 'private' == $post->post_status ) {
			$local_prefix = strlen(__('Private: ')); // get length in local language
			if (strstr($_title, __('Private: '))) {
				$_title = substr_replace($_title, $private_text, 0, $local_prefix);
			}
		}
	} return $_title;
}


add_filter('the_title', 'replace_existing_prefix');



////////// Apply Content Previews 
add_option( 'passworded-preview-type', '0', 'Mode of user-defined passworded post preview', TRUE );


function show_content_for_passworded( $input ){
 global $post;

	if ( empty($post->post_password) ) { // if there's no password
			return $input; } else {
		if ( !isset($_COOKIE['wp-postpass_'.COOKIEHASH]) || $_COOKIE['wp-postpass_'.COOKIEHASH] != $post->post_password ) {  // and it doesn't match the cookie

		switch ( get_option( 'passworded-preview-type' ) )
			{
		case 1: // Show the excerpt, otherwise show default
			if ( !empty($post->post_excerpt) ) {$output = $post->post_excerpt . custom_password_form(); }
			else { $output = $input; }
			break;  
		case 2: // Show the excerpt, otherwise show custom message
			if ( !empty($post->post_excerpt) ) {$output = $post->post_excerpt . custom_password_form(); }
			else { $output = get_option('custom-preview-text') . custom_password_form(); }
			break;
		case 3: // show the custom message
			$output = get_option('custom-preview-text') . custom_password_form();
			break;
		default: // No content preview
			$output = $input;
			}

		return $output;

		} else return $input;
	} 
}

// default is to show get_the_password_form()
add_filter('the_content', 'show_content_for_passworded');

// default returns 'There is no excerpt because this is a protected post.'
add_filter('get_the_excerpt', 'show_content_for_passworded');



////////// Duplicate get_password_form() but with User-Supplied Text
function custom_password_form() {
	global $post;
	$label = 'pwbox-'.(empty($post->ID) ? rand() : $post->ID);
	$output = '<form action="' . get_option('siteurl') . '/wp-pass.php" method="post">
	<p '. get_option('password-form-css') .'><label for="' . $label . '">' . get_option('password-form-prefix') . '<input name="post_password" id="' . $label . '" type="password" size="20" /></label> <input type="submit" name="Submit" value="' . get_option('password-form-submit') . '" /></p>
	</form>
	';
	return $output;
}



////////// Options Page in WP-Admin Panel
function show_prpope_admin(){

 echo ('
<div class="wrap">
<h2>Protected Post Personalizer</h2>

<form method="post" action="options.php">');
 wp_nonce_field('update-options');

 echo('
 
 
<h3>Prefix Fixer</h3>

<p>'. __('By default, Wordpress indicates something is a protected post by including a word before the post\'s  title. You can change those words or even eliminate them. Use') .' <code>&amp;nbsp;</code> '. __('to insert a leading or trailing space.') .'</p>

<table class="form-table">
<tr valign="top">
<th scope="row">'. __('for Password-Protected') .'</th>
<td><input type="text" name="password-protected-prefix" value="'. get_option('password-protected-prefix') .'" /></td>
</tr>
 
<tr valign="top">
<th scope="row">'. __('for Private Posts') .'</th>
<td><input type="text" name="private-prefix" value="'. get_option('private-prefix') .'" /></td>
</tr></table>



<h3>Content Preview</h3>
<p>'. __('Choose what will be publicly displayed when a password hasn\'t been given. This can be especially useful if you want to give a hint using the post\'s <strong>excerpt</strong>. <em>Note, please</em>: always use the <strong>custom</strong> option if you wish to customize the password form, below.') .'</p>

<table class="form-table">
<tr valign="top">

<td><input type="radio" name="passworded-preview-type" value="0"');
	if ( '0' == get_option( 'passworded-preview-type' )) {
		echo ' checked="checked"'; }
	echo (' />'.
	__('Just show <strong>default</strong> (non-custom password form, or "There is no excerpt because...")')); echo("<br />"); echo('
	
	<input type="radio" name="passworded-preview-type" value="1"');
	if ( '1' == get_option( 'passworded-preview-type' )) {
		echo ' checked="checked"'; }
	echo (' />'.
	__('Use post <strong>excerpt</strong> if one exists, otherwise <strong>default</strong><br/>').'
	
	<input type="radio" name="passworded-preview-type" value="2"');
	if ( '2' == get_option( 'passworded-preview-type' )) {
		echo ' checked="checked"'; }
	echo (' />'.
	__('Use post <strong>excerpt</strong> if one exists, otherwise <strong>custom</strong><br/>').'
	
	<input type="radio" name="passworded-preview-type" value="3"');
	if ( '3' == get_option( 'passworded-preview-type' )) {
		echo ' checked="checked"'; }
	echo (' />'.
	__('Show <strong>custom</strong> message for all').'</td>
	
<td><h3>Custom Text</h3><textarea name="custom-preview-text" rows="10" cols="70" style="width:100%">'. get_option('custom-preview-text') .' </textarea></td>

</tr>

</table>


<h3>Customize Password Form</h3>

<p>'. __('You can also change the appearance of the box used to type in the password. Use') .' <code>&amp;nbsp;</code> '. __('to insert a leading or trailing space.') .'</p>

<table class="form-table">
<tr valign="top">
<th scope="row">'. __('Before Input Box') .'</th>
<td><input type="text" name="password-form-prefix" value="'. get_option('password-form-prefix') .'" /></td>
</tr>


<tr valign="top">
<th scope="row">'. __('Text of Submit Button') .'</th>
<td><input type="text" name="password-form-submit" value="'. get_option('password-form-submit') .'" /></td>
</tr>

<tr valign="top">
<th scope="row">'. __('Assign ID, Class, or Inline Styles to Password Form') .'</th>
<td><textarea name="password-form-css" style="width:100%" rows="1">'. get_option('password-form-css') .'</textarea></td>
</tr>
</table>


<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="password-protected-prefix, private-prefix, passworded-preview-type, custom-preview-text, password-form-prefix, password-form-submit, password-form-css" />

<p class="submit">
<input type="submit" name="Submit" value="'. __('Save Changes') .'" />
</p>

</form>
</div>
');

}

// Add page to Settings Menu in WP-Admin
function prpope_add_pages() {
	add_options_page('Protected Post Personalizer', 'Protected Post Personalizer', 7, __FILE__, 'show_prpope_admin');
}

add_action('admin_menu', 'prpope_add_pages');


?>