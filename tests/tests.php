<?php
require_once 'bootstrap.php';

class MediaHandlerTest extends WP_UnitTestCase {
    public function test_attachment_fields_to_edit_safe_media() {
        // Create a test attachment
        $attachment_id = 59;

        // Call the attachment_fields_to_edit_safe_media function
        $fields = attachment_fields_to_edit_safe_media( $attachment_id, 'image' );

        // Check that the quantity field was added
        $this->assertArrayHasKey( 'quantity', $fields );
        
        // Check that the quantity field has the expected HTML
        $this->assertStringContainsString( 'name="attachments[' . $attachment_id . '][quantity]"', $fields['quantity'] );
    }
}
