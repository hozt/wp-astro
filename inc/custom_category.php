<?php

// Add field to Edit Category screen
add_action( 'category_edit_form_fields', function( $term ) {
    $value = get_term_meta( $term->term_id, 'hide_listing_page', true );
    ?>
    <tr class="form-field">
        <th scope="row"><label for="hide_listing_page">Hide listing page</label></th>
        <td>
            <input type="checkbox" name="hide_listing_page" id="hide_listing_page" value="1" <?php checked( $value, '1' ); ?> />
            <p class="description">If checked, this category’s listing page should be hidden.</p>
        </td>
    </tr>
    <?php
} );

add_action( 'edited_category', function( $term_id ) {
    $value = isset( $_POST['hide_listing_page'] ) ? '1' : '0';
    update_term_meta( $term_id, 'hide_listing_page', $value );
} );

add_action( 'graphql_register_types', function() {
    register_graphql_field( 'Category', 'hideListingPage', [
        'type'        => 'Boolean',
        'description' => __( 'Whether to hide this category listing page', 'wp-astro' ),
        'resolve'     => function( $term ) {
            $value = get_term_meta( $term->term_id, 'hide_listing_page', true );
            return $value === '1';
        },
    ] );
} );
