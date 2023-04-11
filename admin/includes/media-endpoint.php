<?php


class SafeMediaDeleteMediaEndPointClass extends SafeMediaDeleteAdminClass
{
    public function __construct()
    {
        add_action('rest_api_init', array($this,'safe_media_rest_api_init'));
    }
    // creat endpoint for attachment details
    public function safe_media_rest_api_init()
    {
        register_rest_route('assignment/v1', '/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array( $this, 'safe_media_rest_api_init_image_details' ),
        ));
    }
    // callback for endpoint
    public function safe_media_rest_api_init_image_details($request)
    {
        $image_id = $request['id'];
        $image = get_post($image_id);

        if (empty($image) || $image->post_type != 'attachment') {
            return new WP_Error('not_found', 'Image not found', array( 'status' => 404 ));
        }
        $handler = new SafeMediaDeleteMediaHandlerClass();
        $linked_posts = $handler->get_linked_articles_links($image_id, 'api');
        $image_data = array(
            'id' => $image_id,
            'date' => $image->post_date,
            'slug' => $image->post_name,
            'type' => wp_check_filetype(get_attached_file($image_id))['ext'],
            'link' => wp_get_attachment_url($image_id),
            'alt_text' => get_post_meta($image_id, '_wp_attachment_image_alt', true),
            'attached_objects' => $linked_posts
        );
        $attached_objects = get_attached_media('', $image_id);
        foreach ($attached_objects as $object) {
            $image_data['attached_objects'][] = $object->post_parent;
        }

        return rest_ensure_response($image_data);
    }
}
new SafeMediaDeleteMediaEndPointClass();
