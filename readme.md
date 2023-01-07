=== The Admin Theme Experience ===
Contributors: GregRoss
Plugin URI: http://toolstack.com/the-admin-theme-experience
Author URI: http://toolstack.com
Tags: admin color scheme theme
Requires at least: 3.8.0
Tested up to: 3.8.0
Stable tag: 0.1
License: GPLv2

POC for proper theme's for the admin area.

== Description ==

Proof of concept for proper theme's for the admin area.

**THIS PLUGIN SHOULD NOT BE USED AT THIS TIME**

It is only a proof of concept for submission to the "Features-As-Plugins" development model for WordPress.

It has two primary goals:

* Simplify the creation of admin color themes.
* Bring the Site Theme Experience to admin themes.

**Simplify**

The current process of creating an admin color theme requires you to create a WordPress plugin.  There is no reason a color theme author should have to also be proficient in PHP and WordPress development to be able to create and deploy an admin color theme.

To this end, The Admin Theme Experience (ATHX) removes all PHP dependences from the theme files.   It does this my creating a new directory for admin themes (currently located in the plugin directory, however if brought in to the core, it would live in the wp-admin/themes directory to mirror the wp-content/themes structure).

Inside the admin theme directory are at minimum 2 files:

1. style.css - The style sheet to use for the theme, it follows the standard format of header information for a site theme.
2. readme.txt - A standard WordPress readme file.

In addition, several optional files can be included in the theme:

1. style-rtl.css - A right to left version of the theme
2. style.scss and style-rtl.scss - SASS files to create the stylesheets.
3. screenshot.png - A screen shot to use in the new admin theme selector.
4. Any number of additional files that are useful to the theme.

Updated version of _admin.scss and _variables.scss have been included that support the creation of rtl sytlesheets as well (read on for more information on this later).

The goal would be to create a third (themes, plugins and admin themes) repository on WordPress.org for admin themes, similar to the theme repository that exists today.

**Site Theme Experience**

The current admin theme selector in the 'Your Profile' page is woefully underpowered.  Fortunately WordPress 3.8 introduced a brand spanking, super shiny, theme selector (THX) for the site themes, so why not use it for admin themes as well?

Using the new style.css header information as well as the screen shot, a new menu item is added to the users.php menu, 'Your Admin Theme'.  This currently loads a slimmed down version of THX (this will be fleshed out, but this is only version 0.1 ;).

The plugin also replaces the existing theme selector in the 'Your Profile' page with a link to the new page.

**Technical Notes**

This is version 0.1, so there are some things to note:

* Of note is that the new code does not use wp_admin_css_color() in any way, shape or form.
* Uploading of themes is currently not implemented.
* There is some fudge used in a few places, in a core implementation some functions/class would be altered to support multiple repositories and types of themes.  For the POC this has not been done as altering core functions/classes is difficult with a plugin.
* The plugin works by ALWAYS setting the current WordPress admin theme to default.  It stores the "real" admin theme in the "admin_xp_color" user option.  This ensure that the appropriate css files are loaded which all of the built in admin themes use (and all the third party ones I've seen so far).  After that, it loads the theme's style.css file.  In a core implementation the second user option would not be required and the code simplified.
* As a POC, many items, like multi-site support and proper capabilities checking, has not yet been implemented.
* As this changes the contents of the theme directories, the built in admin theme's have been moved over to the plugins new themes directory and updated to match the new requirements.  This has been done by hand and no new .scss files have been used to generate them at this time.  The "Modern Grey" theme however does use the new _admin.scss file to generate it's style.css.

**History**

Just after WordPress 3.8 b1 came out I found myself not liking the new default admin color scheme all that much.  It was a lot darker than the old one and just didn't appeal to me.

So I started looking at how to build an admin color theme.  This was before the officially sanctioned "Admin Color Schemes" plugin came out and I found the documentation to be slightly confusing overall.  To the point that I put the idea away, figuring someone else would probably make a plugin to do the work for me :)

Once "Admin Color Schemes" was released, it became much clearer what you had to do.  However it also made clear the fact that the admin color scheme code was fundamentally broken.  I say this because:

* The built in themes can do things add on themes can't (for example a simple @include '../colors.css').
* Because of the above, you have to hook the load of the admin page and hack in the colors.css file.
* A hook for a single admin color theme might be ok, but what about 10 or 20 or more for large sites?
* You have to register all the color theme's you have on EVERY page load.
* _admin.css cannot generate both rtl and ltr files.

On top of that, the theme picker in the profile page became much too large to be part of the profile page.  "Admin Color Schemes" adds 8 new themes, my own "Grey Admin Color Schemes" adds 4.  A large site could have countless themes all adding to the profile page.

Likewise, with only 4 "preview" colors, it makes it VERY hard to tell what the theme is actually going to look like.  My "Grey Admin Color Schemes" share much of the same color pallet, but are clearly different as soon as you see them in action.

The final nitpick I had with the existing code is that as soon as you click on a new admin theme, it gets saved as your theme.  This goes counter to the "Save" button at the bottom of the page and the traditional work flow of the profile page.

I tried several different things to "save" the old system:

* I tried including wp-admin/css/colors.css in my css file, but that doesn't work as you either have to use a relative path to it, which breaks if the user has split the location of the wp-content and wp-admin directories, or you have to make your colors.css a php file so you can use the WordPress functions to find the wp-admin folder.
* I rewrote colors.css to be a .scss file, however I found other missing components in it and LOTS of extra items that were not covered by the _admin.scss file.  This ended up being a real mess.
* I rewrote _admin.scss to include the missing items from colors.css and other locations.  This was a trip down the rabbit hole, no matter how far I went, it just kept going and going and going...
* I thought about extending wp_admin_css_color() to allow for an array to be passed instead of a single css file, but that just covered up many of the other issues listed above.

In the end, I decided the old method was just too limited and wrote this plugin to try and fix the problem.

Turns out it was easier than I thought... kind of ;)

== Installation ==

1. Extract the archive file into your plugins directory in the admin-themes folder.
2. Activate the plugin in the Plugin options.

== Frequently Asked Questions ==

= None at this time =

== Changelog ==
= 0.1 =
* First pre-beta, everything is new.

== Upgrade Notice == 
= 0.1 =
* None

== Screenshots ==

1. Screen shot
