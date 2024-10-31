<?php
/*
Plugin Name: Nirror
Plugin URI: http://wordpress.org/extend/plugins/nirror/
Description: Enables <a href="http://www.nirror.com/">Nirror</a> on all pages.
Version: 0.0.1
Author: Nirror
Author URI: http://www.nirror.com
*/

if (!defined('WP_CONTENT_URL'))
      define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
if (!defined('WP_CONTENT_DIR'))
      define('WP_CONTENT_DIR', ABSPATH.'wp-content');
if (!defined('WP_PLUGIN_URL'))
      define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
if (!defined('WP_PLUGIN_DIR'))
      define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');

function activate_nirror() {
  add_option('nirror_site_id', '');
}

function deactive_nirror() {
  delete_option('nirror_site_id');
}

function admin_init_nirror() {
  register_setting('nirror', 'nirror_site_id');
}

function admin_menu_nirror() {
  add_options_page('Nirror', 'Nirror', 'manage_options', 'nirror', 'options_page_nirror');
}

function options_page_nirror() {
  include(WP_PLUGIN_DIR.'/nirror/options.php');  
}

function nirror() {
  $nirror_site_id = get_option('nirror_site_id');
  global $current_user;
  $current_user = wp_get_current_user();
?>
<script type="text/javascript">
    (function(i,s,o,g,r,a,m){i['NirrorObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();i[r].scriptURL=g;a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://static.nirror.com/client/nirrorclient.js','Ni');
 
    Ni('site', '<?php echo $nirror_site_id; ?>');
<?php if(is_user_logged_in() ) { ?>
    Ni('user','username', "<?php echo $current_user->display_name; ?>");
    Ni('user','extra', {
      'ID': "<?php echo $current_user->ID; ?>",
      'login': "<?php echo $current_user->user_login; ?>",
      'email': "<?php echo $current_user->user_email; ?>",
      'first_name': "<?php echo $current_user->user_firstname; ?>",
      'last_name': "<?php echo $current_user->user_lastname; ?>",
      'display_name': "<?php echo $current_user->display_name; ?>"
    });
<?php } ?>
</script>
<?php
}

register_activation_hook(__FILE__, 'activate_nirror');
register_deactivation_hook(__FILE__, 'deactive_nirror');

if (is_admin()) {
  add_action('admin_init', 'admin_init_nirror');
  add_action('admin_menu', 'admin_menu_nirror');
}

if (!is_admin()) {
  add_action('wp_head', 'nirror');
}

?>
