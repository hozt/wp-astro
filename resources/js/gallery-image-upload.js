jQuery(document).ready(function($) {
    var frame;

    // Function to update the hidden input field with image IDs in the correct order
    function updateImageIds() {
        var ids = [];
        $('#gallery_images_list li').each(function() {
            ids.push($(this).data('id'));
        });
        $('#gallery_image_ids').val(ids.join(','));
    }

    // Initialize sortable functionality on the images list
    $('#gallery_images_list').sortable({
        placeholder: 'ui-state-highlight',
        update: function(event, ui) {
            updateImageIds();  // Update the hidden field whenever items are re-ordered
        }
    });
    $('#gallery_images_list').disableSelection();

    // Handling the add images button
    $('#add_gallery_image').on('click', function(e) {
        e.preventDefault();

        // If the media frame already exists, reopen it.
        if (frame) {
            frame.open();
            return;
        }

        // Create a new media frame
        frame = wp.media({
            title: 'Select or Upload Media',
            button: {
                text: 'Use this media'  // Button text
            },
            multiple: 'add'  // Allow multiple files to be selected
        });

        // When an image is selected in the media frame...
        frame.on('select', function() {
            var attachments = frame.state().get('selection').map(function(attachment) {
                attachment = attachment.toJSON();
                $('#gallery_images_list').append('<li class="ui-state-default" data-id="' + attachment.id + '"><img src="' + attachment.url + '" style="width:100px;"><span class="remove-image">Remove</span></li>');
                return attachment;
            });
            updateImageIds();  // Update the hidden field to include the new images
        });

        // Finally, open the modal on click
        frame.open();
    });

    // Handling the removal of images
    $('body').on('click', '.remove-image', function(e) {
        e.preventDefault();
        $(this).parent().remove();
        updateImageIds();  // Update the hidden field to reflect the removal
    });



    $('.image-upload').click(function(e) {
        e.preventDefault();
        var imageUploader = wp.media({
            'title': 'Upload Image',
            'button': {
                'text': 'Use this image'
            },
            'multiple': false
        }).on('select', function() {
            var attachment = imageUploader.state().get('selection').first().toJSON();
            $('#banner_image').val(attachment.url);
            $('#image-preview').attr('src', attachment.url).show();
            updateRemoveButton();
        }).open();
    });

    // Handle image removal
    $(document).on('click', '.image-remove', function(e) {
        e.preventDefault();
        $('#banner_image').val('');
        $('#image-preview').removeAttr('src').hide();
        $(this).hide(); // Hide the remove button
    });

    // Update or add the remove button based on the image input field value
    function updateRemoveButton() {
        if ($('#banner_image').val()) {
            if ($('.image-remove').length === 0) { // If the remove button does not exist, create it
                $('<input type="button" class="button image-remove" value="Remove">').insertAfter('.image-upload');
            }
            $('.image-remove').show();
        } else {
            $('.image-remove').hide();
        }
    }

    // Call this function on page load to handle cases where the image is already set (e.g., during page edits)
    updateRemoveButton();
});
