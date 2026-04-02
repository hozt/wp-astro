jQuery(document).ready(function($) {
    var mediaUploader;

    $('#additional_image_button').on('click', function(e) {
        e.preventDefault();
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#additional_image').val(attachment.url);
            $('#additional_image_preview').attr('src', attachment.url).show();
            $('#remove_additional_image_button').show();
        });

        mediaUploader.open();
    });

    $('#remove_additional_image_button').on('click', function(e) {
        e.preventDefault();
        $('#additional_image').val('');
        $('#additional_image_preview').attr('src', '').hide();
        $(this).hide();
    });
});
