<?php
/**
 * Plugin Name:       WP Safe media delete
 * Description:       WP Safe media delete
 * Text Domain:       safe-media-delete
 * Domain Path:       /languages
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class SafeMediaDeleteMain
{
    public function __construct()
    {
        $this->safe_media_uploads_constants();
        add_action('init', array($this, 'safe_media_uploads_for_text_domain'));
        include(SMDL_PLUGIN_DIR . 'admin/main-admin.php');
    }

     public function safe_media_uploads_for_text_domain()
     {
         load_plugin_textdomain('safe-media-delete', false, dirname(plugin_basename(__FILE__)) . '/languages/');
     }

     public function safe_media_uploads_constants()
     {
         if (!defined('SMDL_URL')) {
             define('SMDL_URL', plugin_dir_url(__FILE__));
         }

         if (!defined('SMDL_BASENAME')) {
             define('SMDL_BASENAME', plugin_basename(__FILE__));
         }

         if (! defined('SMDL_PLUGIN_DIR')) {
             define('SMDL_PLUGIN_DIR', plugin_dir_path(__FILE__));
         }
     }
}

new SafeMediaDeleteMain();
