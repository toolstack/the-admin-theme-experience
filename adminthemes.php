<?php
// Load the currently selected admin theme.
$currentAdminTheme = get_user_option( 'admin_xp_color' );

// This is the directory to our new 'themes' directory in the plugin directory.
$themesDir = plugin_dir_path( __FILE__ ) . "themes";

// Let's scan the theme's directory for all the files/dirs that are in it.
$themesDirList = scandir( $themesDir );

// Setup an empty array to use to store the themes info.  
$themes = array();

// Make sure the current active admin theme is listed first.
$themes[] = get_admin_theme_info( $themesDir, $currentAdminTheme );
$themes[0]['active'] = 1;

// Loop through the directory list we did above.
foreach( $themesDirList as $themeName )
	{
	// Since we've already added the current theme, make sure to only add theme's that are the current one.
	if( $themeName != $currentAdminTheme )
		{
		// Go get the theme details.
		$themeInfo = get_admin_theme_info( $themesDir, $themeName );
	
		// Assuming nothing went wrong, add it to the array.
		if( $themeInfo != NULL ) { $themes[] = $themeInfo; }
		}
	}

/*
	This function returns an array that is used during the theme display.
*/
function get_admin_theme_info( $themesDir, $themeName)
	{
	// First make sure that what we're passed is a directory and that it contains a style.css file.
	// Also make sure it's not the "." or ".." directories.
	if( file_exists( $themesDir  . "/" . $themeName . "/style.css" ) && $themeName != "." && $themeName != ".." )
		{
		// Go get the theme info.  This relies on the WP core code to read a style.css file and return the header info from it.
		// We override the default theme's path with the second parameter.
		$thisTheme = wp_get_theme( $themeName, $themesDir );
		
		// Check to see if a screen shot exists in the theme directory, if so, figure out the proper path to add later.
		if( file_exists( $themesDir . "/" . $themeName . "/screenshot.png" ) )
			{
			$screenshot = plugins_url( "themes/" . $themeName . "/screenshot.png", __FILE__ );
			}
		
		// Time to setup the array to return.
		$themeInfo = array(
							'name' => $thisTheme->Name,
							'active' => $activeTheme[$themeName],
							'screenshot' => array($screenshot),
							'author' => $thisTheme->Author,
							'hasUpdate' => 0,
							'activate' => admin_url( 'users.php', __FILE__ ) . "?page=adminthemes&activateadmintheme=" . $themeName
						);
						
		return $themeInfo;
		}
	else
		{
		// If we didn't have something to process, just return NULL.
		return NULL;
		}
	}
	
/*
	The following code is taken from the core site themes selection code.  Some items have been stripped out
	for simplicity and will probably be added back in later.
*/
?>
<div class="wrap themes-php">

	<h2><?php esc_html_e( 'Admin Themes' ); ?>
		<span class="themes-php theme-count"><?php echo count( $themes ); ?></span>
	<?php if ( ! is_multisite() && current_user_can( 'install_themes' ) ) : ?>
		<a href="<?php echo plugins_url( 'admin-theme-install.php', __FILE__ ); ?>" class="add-new-h2"><?php echo esc_html( _x( 'Add New', 'Add new theme' ) ); ?></a>
	<?php endif; ?>
	</h2>
	<br>
<div class="theme-browser">
	<div class="themes">

<?php
/*
 * This PHP is synchronized with the tmpl-theme template below!
 */

foreach ( $themes as $theme ) : ?>
<div class="theme<?php if ( $theme['active'] ) echo ' active'; ?>">
	<?php if ( ! empty( $theme['screenshot'][0] ) ) { ?>
		<div class="theme-screenshot">
			<img src="<?php echo $theme['screenshot'][0]; ?>" alt="" />
		</div>
	<?php } else { ?>
		<div class="theme-screenshot blank"></div>
	<?php } ?>
	<span class="more-details"><?php _e( 'Theme Details' ); ?></span>
	<div class="theme-author"><?php printf( __( 'By %s' ), $theme['author'] ); ?></div>

	<?php if ( $theme['active'] ) { ?>
		<h3 class="theme-name"><span><?php _ex( 'Active:', 'theme' ); ?></span> <?php echo $theme['name']; ?></h3>
	<?php } else { ?>
		<h3 class="theme-name"><?php echo $theme['name']; ?></h3>
	<?php } ?>

	<div class="theme-actions">

	<?php if ( $theme['active'] ) { ?>
		<span class="button button-disabled"><?php _e( 'Active' ); ?></span>
	<?php } else { ?>
		<a class="button button-primary activate" href="<?php echo $theme['activate']; ?>"><?php _e( 'Activate' ); ?></a>
	<?php } ?>

	</div>

</div>
<?php endforeach; ?>
	<br class="clear" />
	</div>
</div>
</div>