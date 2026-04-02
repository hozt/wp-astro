
jQuery(document).ready(function($) {
    $('#add_related_link').on('click', function() {
        var field = '<p><input type="text" name="related_link[]" class="meta-image regular-text"><button type="button" class="remove-link">Remove</button></p>';
        $('#related_links_container').append(field);
    });

    $('#related_links_container').on('click', '.remove-link', function() {
        $(this).parent().remove();
    });
});
