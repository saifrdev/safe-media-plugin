jQuery(function($) {
    $('#image_upload_div').click(function(e) {
        e.preventDefault();
        var frame = wp.media({
            title: 'Select Images',
            multiple: false,
            library: {
                type: ['image'],
                mimeTypes: ['image/jpeg', 'image/png']
            }
        });
        frame.on('select', function() {
            var selection = frame.state().get('selection');
            selection.map(function(attachment) {
                var attachment_url = attachment.attributes.url;
                var attachment_id = attachment.id;
                if (attachment_url && attachment_id > 0) {
                	jQuery('#image_upload_div').html('<img style="width:150px;" src="'+attachment_url+'">');
                	jQuery('#image_uploaded').val(attachment_id);
                } else {
                	jQuery('#image_upload_div').html('some error occured');
                	jQuery('#image_uploaded').val('');

                }
            });
        });
        frame.open();
    });
});
