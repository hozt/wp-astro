jQuery(document).ready(function($) {
    $('ul[id^="sortable-"]').each(function() {
        var postType = $(this).data('post-type');
        var termId = $(this).attr('id').replace('sortable-', '');

        $(this).sortable({
            update: function(event, ui) {
                var ids = $(this).sortable('toArray', { attribute: 'data-id' });

                $.post(ajaxurl, {
                    action: 'save_sorting_order',
                    ids: ids,
                    term_id: termId,
                    post_type: postType
                }, function(response) {
                    if (response.success) {
                        console.log('Order saved');
                    } else {
                        console.log('Error saving order: ' + response.data);
                    }
                });
            }
        });
    });
    var $videoUrlMetaBox = $('#video_url_meta_box');
    var $videoUrlInput = $('#video_url');

    if ($videoUrlInput.val().trim() === '') {
        $videoUrlMetaBox.addClass('closed');
    }
});

