<?php
/*
Plugin Name: The Admin Theme Experience
Plugin URI: http://wordpress.org/plugins/the-admin-theme-experience
Description: POC for proper theme's for the admin area.
Version: 0.1
Author: Greg Ross
Author URI: http://ToolStack.com/

Compatible with WordPress 3.8+.

Read the accompanying readme.txt file for instructions and documentation.

Copyright (c) 2013 by Greg Ross

This software is released under the GPL v2.0, see license.txt for details
*/

/*
	This function replaces the default theme chooser in the user profile page
	with a link to the new 'Your Admin Theme' page.
*/
function AdminThemesLoadProfile()
	{
	echo "<script type='text/javascript'>jQuery('#color-picker').replaceWith('Admin Theme\'s can now be found <a href=\'users.php?page=adminthemes\'>here</a>.')</script>";
	}
	
/*
	Load the Admin Themes display page.
*/
function AdminThemesDisplayPage()
	{
	include_once( "adminthemes.php" );
	}

/*
	This function is called after a new theme is selected from AdminThemesDisplayPage().  It saves
	the new theme selection on the next page load for the user.

	When we change a theme, we have to do it pretty early in the WordPress load cycle, so
	let's first set the WordPress theme to the default, so it loads all the required css files.
	
	These include admin.css and colors.css.
	
	Then we can save the new theme we've selected to the user options.
*/
function AdminThemesSetTheme()
	{
	if( isset( $_GET['activateadmintheme'] ) )
		{
		// First set WP's admin theme value to the default, this will ensure all the appropriate css files are loaded before we add our own color theme.
		update_user_option( get_current_user_id(), 'admin_color', 'default' );
		
		// Now save our new value for The Admin Theme Experience
		update_user_option( get_current_user_id(), 'admin_xp_color', $_GET['activateadmintheme'] );
		}
	}
	
/*
	On each page load we have to register and enqueue the admin theme style we've selected, so do
	it here.
*/
function AdminThemesLoadTheme()
	{
	$themesDir = plugin_dir_path( __FILE__ ) . "themes";
	
	$currentXPTheme = get_user_option( 'admin_xp_color' );
	
	wp_register_style( 'AdminThemeXPStylesheet', plugins_url( 'themes/' . $currentXPTheme . '/style.css', __FILE__) );
	wp_enqueue_style( 'AdminThemeXPStylesheet' );
	}

/*
	Add the 'Your Admin Theme' menu to the users menu.
*/	
function AdminThemesAddMenu()
	{
	if( current_user_can( 'install_plugins' ) )
		{
		add_submenu_page( 'users.php', __( 'Your Admin Theme' ), __( 'Your Admin Theme' ), 'manage_options', 'adminthemes', 'AdminThemesDisplayPage' );
		}
	}

// Add the action to add the 'Your Admin Theme' menu item.
add_action( 'admin_menu', 'AdminThemesAddMenu', 1 );

// Replace the admin theme selector in the user profile screen with a link to the new one.
add_action( 'show_user_profile', 'AdminThemesLoadProfile' );
add_action( 'edit_user_profile', 'AdminThemesLoadProfile' );

// Catch and save the theme setting when a new one is selected.
add_action( 'init', 'AdminThemesSetTheme' );

// Enqueue the admin theme css files.
add_action( 'admin_enqueue_scripts', 'AdminThemesLoadTheme' );
?>