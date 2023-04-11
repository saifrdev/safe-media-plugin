<?php


class SafeMediaDeleteMediaHandlerClass extends SafeMediaDeleteAdminClass
{
    public function __construct()
    {
        add_action('attachment_fields_to_edit', array($this,'attachment_fields_to_edit_safe_media'), 10, 2);
        add_filter('manage_media_columns', array($this,'safe_media_manage_media_columns'), 10, 1);
        add_filter('manage_media_custom_column', array($this,'manage_media_custom_column_media'), 10, 2);
    }

    // add new html inside view attachment popup
    public function attachment_fields_to_edit_safe_media($form_fields, $post)
    {
        $html = $this->get_linked_articles_links($post->ID, 'a');
        $form_fields['linked_articles'] = array(
            'label' => __('Linked articles'),
            'input' => 'html',
            'html' => $html,
        );
        return $form_fields;
    }
    // add new column inside media for table view
    public function safe_media_manage_media_columns($columns)
    {
        $columns['linked_articles'] = __('Linked Object', 'safe-media-delete');
        return $columns;
    }
    // get linked articles with attachment
    public function manage_media_custom_column_media($column_name, $attachment_id)
    {
        if ($column_name == 'linked_articles') {
            $html = $this->get_linked_articles_links($attachment_id, 'b');
            echo $html;
        }
    }
    // check if attachment is linked with post or any term
    public function get_linked_articles_links($attachment_id, $c)
    {
        $linked_html = '<div class="linked_ids">';
        if ($c == 'b') {
            $linked_html.='<div>articles</div>';
        }
        $posts_with_featured_image = get_posts(array(
            'meta_key' => '_thumbnail_id',
            'meta_value' => $attachment_id,
            'post_type' => array('post', 'page','product'),
            'fields' => 'ids'
        ));
        $post_types = array('post');
        $attachment_meta = wp_get_attachment_metadata($attachment_id);
        $attachment_filename = basename($attachment_meta['file']);
        $attachment_url = wp_get_attachment_url($attachment_id);
        $posts_with_attachment_in_content = get_posts(array(
            'post_type' => $post_types,
            'fields' => 'ids',
            'meta_query' => array(
                array(
                    'key' => '_wp_attached_file',
                    'value' => $attachment_filename,
                    'compare' => 'LIKE'
                )
            )
        ));


        $posts_with_attachment_url = get_posts(array(
            'post_type' =>$post_types,
            's' => $attachment_url,
            'posts_per_page' => -1,
            'fields' => 'ids'
        ));

        $posts_with_attachment_in_gallery = get_posts(array(
            'post_type' => $post_types,
            'fields' => 'ids',
            'meta_query' => array(
                array(
                    'key' => '_gallery_images',
                    'value' => $attachment_id,
                    'compare' => 'LIKE'
                )
            )
        ));
        $posts_merged_array = array();
        foreach (array($posts_with_featured_image, $posts_with_attachment_in_content, $posts_with_attachment_url,$posts_with_attachment_in_gallery) as $array) {
            $posts_merged_array = array_merge($posts_merged_array, $array);
        }
        $posts_arrays = array_unique($posts_merged_array);
        foreach($posts_arrays as $pid) {
            $linked_html.='<a href="'.get_edit_post_link($pid).'">'.$pid.'</a>';
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'termmeta';
        $sql = $wpdb->prepare(
            "SELECT DISTINCT term_id FROM $table_name WHERE meta_value LIKE %s",
            '%' . $wpdb->esc_like($attachment_url) . '%'
        );
        $term_ids1 = $wpdb->get_col($sql);

        $sql = $wpdb->prepare(
            "SELECT DISTINCT term_id FROM $table_name WHERE meta_value = %d",
            $attachment_id
        );
        $term_ids2 = $wpdb->get_col($sql);
        $merged_array = array_merge($term_ids1, $term_ids2);
        $terms_array = array_unique($merged_array);

        if ($c == 'api') {
            $final_array = array(
                'posts_linked'=>$posts_arrays,
                'tags_linked'=> $terms_array
            );
            return $final_array;
        }

        foreach($terms_array as $term_id) {
            $term = get_term($term_id);
            if (! is_wp_error($term)) {
                $taxonomy = $term->taxonomy;
                $term_edit_link = get_edit_term_link($term_id, $taxonomy);
                $linked_html.='<a href="'.$term_edit_link.'">'.$term_id.'</a>';
            }
        }
        $linked_html.='</div>';
        return $linked_html;
    }


}

new SafeMediaDeleteMediaHandlerClass();
