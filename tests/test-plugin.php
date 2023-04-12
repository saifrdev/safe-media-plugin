<?php

require_once dirname(__FILE__) . './../admin/main-admin.php';
include_once(ABSPATH.'wp-includes/class-wp-error.php');

class Test_Plugin extends WP_UnitTestCase
{
    // test case for safe_media_manage_media_columns
    public function test_safe_media_manage_media_columns()
    {
        // Arrange
        $columns = array(
            'title' => 'Title',
            'date' => 'Date',
            'author' => 'Author'
        );

        // Act
        $columns = (new SafeMediaDeleteMediaHandlerClass())->safe_media_manage_media_columns($columns);

        // Assert
        $this->assertArrayHasKey('linked_articles', $columns);
        $this->assertEquals(__('Linked Object', 'safe-media-delete'), $columns['linked_articles']);
    }


    public function test_manage_media_custom_column_media()
    {
        $attachment_id = $this->factory->attachment->create_upload_object(__DIR__ . '/example.jpg');
        $column_name = 'linked_articles';


        ob_start();
        (new SafeMediaDeleteMediaHandlerClass())->manage_media_custom_column_media($column_name, $attachment_id);
        $output = ob_get_clean();

        $this->assertStringContainsString('<div class="linked_ids"><div>articles</div></div>', $output);
    }

    // test case for safe_media_uploads_tags_field
    public function test_safe_media_uploads_tags_field_output()
    {
        // Simulate post_tag taxonomy
        $taxonomy = 'post_tag';
        // Start output buffering
        ob_start();
        // Call the function
        (new SafeMediaDeletePostTagClass())->safe_media_uploads_tags_field($taxonomy);
        // Get the buffered output
        $output = ob_get_clean();
        // Check that the output contains the expected HTML
        $this->assertStringContainsString('<div class="form-field term-my_custom_field-wrap">', $output);
        $this->assertStringContainsString('<div id="image_upload_div">', $output);
        $this->assertStringContainsString('<div class="button button-primary">Upload Image</div>', $output);
        $this->assertStringContainsString('<input type="hidden" name="image_uploaded" id="image_uploaded" value="">', $output);
    }

    // test case for safe_media_uploads_create_post_tag

    public function test_update_term_meta_with_empty_image_uploaded()
    {
        $term_id = $this->factory->term->create(array('taxonomy' => 'post_tag'));
        $_POST['image_uploaded'] = '';
        (new SafeMediaDeletePostTagClass())->safe_media_uploads_create_post_tag($term_id, 0);
        $value = get_term_meta($term_id, 'image_uploaded', true);
        $this->assertEquals('', $value);
    }


    // test case for safe_media_uploads_tags_field_edit

    public function test_safe_media_uploads_tags_field_edit()
    {

        // Create a new term object
        $term = $this->factory->term->create_and_get(array('taxonomy' => 'post_tag'));

        // Set the term meta
        update_term_meta($term->term_id, 'image_uploaded', 123);

        // Render the field
        ob_start();
        (new SafeMediaDeletePostTagClass())->safe_media_uploads_tags_field_edit($term, 'post_tag');
        $output = ob_get_clean();

        // Check the output

        $this->assertStringContainsString('<input type="hidden" name="image_uploaded" id="image_uploaded" value="123">', $output);
    }

    // test case for safe_media_rest_api_init_image_details

    public function test_safe_media_rest_api_init_image_details()
    {
        // Create a test image and upload it as an attachment
        $length = 10; // The length of the random string
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; // The characters to use for the random string
        $random_string = substr(str_shuffle($chars), 0, $length); // Generate the random string

        $file = __DIR__ . '/example.jpg';
        $attachment_id = $this->factory->attachment->create_upload_object($file);
        $attachment_title = get_the_title($attachment_id);

        $attachment_slug = wp_unique_post_slug(sanitize_title($attachment_title), $attachment_id, 'attachment', 'save', null);

        // Call the function with the attachment ID
        $request = array('id' => $attachment_id);
        $result = (new SafeMediaDeleteMediaEndPointClass())->safe_media_rest_api_init_image_details($request);

        // Assert that the result is a WP_REST_Response object
        $this->assertInstanceOf('WP_REST_Response', $result);

        // Assert that the response contains the expected data
        wp_delete_attachment($attachment_id, true);
        $data = $result->get_data();

        $this->assertEquals((string)$attachment_id, $data['id']);
        $this->assertNotEmpty($data['date']);
        $this->assertEquals($attachment_slug, $data['slug'], 'The slug in the response does not match the expected value.');
        $this->assertEquals('jpg', $data['type']);
        $this->assertNotEmpty($data['link']);

        // Clean up the test attachment
        wp_delete_attachment($attachment_id, true);
    }

    public function test_get_linked_articles_links_b()
    {
        $attachment_id = $this->factory->attachment->create_upload_object(__DIR__ . '/example.jpg');
        $c = 'b';
        $links = (new SafeMediaDeleteMediaHandlerClass())->get_linked_articles_links($attachment_id, $c);
        $this->assertStringContainsString('<div>articles</div>', $links);
    }

    public function test_get_linked_articles_links_c()
    {
        $attachment_id = $this->factory->attachment->create_upload_object(__DIR__ . '/example.jpg');
        $c = 'c';
        $links = (new SafeMediaDeleteMediaHandlerClass())->get_linked_articles_links($attachment_id, $c);
        $this->assertStringContainsString('<div class="linked_ids">', $links);
    }



}
