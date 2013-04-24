<?php
/*
Plugin Name: Post & Pages Admin Renamer
Plugin URI: http://www.thejakegroup.com/wordpress
Description: Changes the words "Post" and "Page" in the admin interface to anything you'd like. Plural inflection is currently not supported... sorry... no geese or boxes for you! Choose your new term in the Rename Posts/Pages panel. This plugin requires PHP5. 
Author: Lawson Kurtz
Version: 1.1
Author URI: http://thejakegroup.com/
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/



function my_plugin_activate()
{
	// whatever
}

//register_activation_hook(__FILE__, "my_plugin_activate");

function my_plugin_deactivate()
{
	// whatever
}

//register_deactivation_hook(__FILE__, "my_plugin_deactivate");


$new_terminology_post = (get_option('new_post_term')) ? get_option('new_post_term') : "Post";
$new_terminology_page = (get_option('new_page_term')) ? get_option('new_page_term') : "Page";


function tjg_ppr_change_post_terminology( $translated ) {
    global $new_terminology_post;
	global $new_terminology_page; 
	$translated_post = str_ireplace(  'Post',  $new_terminology_post,  $translated ); 
	$translated_page = str_ireplace(  'Page',  $new_terminology_page,  $translated_post ); 
    return $translated_page;
}

add_filter(  'gettext',  'tjg_ppr_change_post_terminology'  );
add_filter(  'ngettext',  'tjg_ppr_change_post_terminology'  );

?>
<?php
// create custom plugin settings menu
add_action('admin_menu', 'tjg_ppr_ptc_create_menu');

function tjg_ppr_ptc_create_menu() {

	//create new top-level menu
	add_menu_page('Rename Posts/Pages', 'Rename Posts/Pages', 'administrator', __FILE__, 'tjg_ppr_ptc_settings_page',plugins_url('/images/lego.png', __FILE__));
	
	//let's make it a submenu under Settings instead
	//add_submenu_page( 'options-general.php', 'PTC Settings', 'PTC Settings', 'administrator', __FILE__ );
	//call register settings function
	add_action( 'admin_init', 'tjg_ppr_register_mysettings' );
}


function tjg_ppr_register_mysettings() {
	//register our settings
	register_setting( 'ptc-settings-group', 'new_post_term' );
	register_setting( 'ptc-settings-group', 'new_page_term' );

}

function tjg_ppr_ptc_settings_page() {
?>
<div class="wrap">
<h2>Post & Pages Admin Renamer</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'ptc-settings-group' ); ?>
    <?php // do_settings( 'ptc-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">New Post Term (singular please):</th>
        <td><input type="text" name="new_post_term" value="<?php echo get_option('new_post_term'); ?>" /></td>
        </tr>
		<tr valign="top">
        <th scope="row">New Page Term (singular please):</th>
        <td><input type="text" name="new_page_term" value="<?php echo get_option('new_page_term'); ?>" /></td>
        </tr>
    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>
<?php } ?>