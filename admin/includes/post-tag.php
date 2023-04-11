<?php


class SafeMediaDeletePostTagClass extends SafeMediaDeleteAdminClass
{
    public function __construct()
    {
        add_action('add_tag_form_fields', array($this,'safe_media_uploads_tags_field'));
        add_action('post_tag_edit_form_fields', array($this,'safe_media_uploads_tags_field_edit'), 99, 2);
        add_action('created_post_tag', array($this,'safe_media_uploads_create_post_tag'), 10, 2);
        add_action('edited_post_tag', array($this,'safe_media_uploads_create_post_tag'), 10, 2);
    }
    // create new image upload field for post_tag
    public function safe_media_uploads_tags_field($taxonomy)
    {
        if ($taxonomy == 'post_tag') {
            ?>
			<div class="form-field term-my_custom_field-wrap">
				<div id="image_upload_div">
					<div class="button button-primary">Upload Image</div>
				</div>
				<input type="hidden" name="image_uploaded" id="image_uploaded" value="">
			</div>
			<?php
        }
    }
    // save and update attachment id field for post_tag
    public function safe_media_uploads_create_post_tag($term_id, $tt_id)
    {
        if(isset($_POST['image_uploaded'])) {
            update_term_meta($term_id, 'image_uploaded', $_POST['image_uploaded']);
        }
    }
    // handle edit post tag section
    public function safe_media_uploads_tags_field_edit($term, $taxonomy)
    {
        $attachment_id = get_term_meta($term->term_id, 'image_uploaded', true);
        $attachment_url = get_the_guid($attachment_id);
        ?>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="my_custom_field"><?php _e('Image Uploaded', 'my_custom_field'); ?></label></th>
			<td>
				<div id="image_upload_div" style="width:fit-content;">
					<?php
                    if (!empty($attachment_url)) {
                        echo '<img style="width:150px;" src="'.esc_url($attachment_url).'">';
                    } else {
                        echo '<div class="button button-primary">Upload Image</div>';
                    }
        ?>
				</div>
				<input type="hidden" name="image_uploaded" id="image_uploaded" value="<?php echo absint($attachment_id); ?>">
			</td>
		</tr>
		<?php
    }

}
new SafeMediaDeletePostTagClass();
