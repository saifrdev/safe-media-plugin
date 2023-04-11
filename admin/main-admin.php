<?php


class SafeMediaDeleteAdminClass
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this,'safe_media_uploads_script'));
        $autoloads = array('post-tag','media-handler');
        foreach($autoloads as $file) {
            include(SMDL_PLUGIN_DIR . 'admin/includes/'.$file.'.php');
        }
    }

    public function safe_media_uploads_script()
    {
        $screen = get_current_screen();
        if ($screen->taxonomy == 'post_tag') {
            wp_enqueue_media();
            wp_enqueue_script('media_upload_js', SMDL_URL.'assets/js/admin.js', array( 'jquery' ), '1.0', true);
        }
        wp_enqueue_style('media_upload_css', SMDL_URL.'assets/css/media-admin.css');
    }

}

new SafeMediaDeleteAdminClass();
