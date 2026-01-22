<?php

/**
 * Register Meta Box
 */
function my_custom_filter_meta_box() {
    global $post;

    // Only show this meta box if the specific template is selected
    $template = get_post_meta( $post->ID, '_wp_page_template', true );

    if ( 'template-filtered-posts.php' === $template ) {
        add_meta_box(
            'my_filter_settings',        // ID
            'Allowed Rubrics & Tags',    // Title
            'my_render_filter_meta_box', // Callback function
            'page',                      // Screen (Post Type)
            'normal',                    // Context
            'high'                       // Priority
        );
    }
}
add_action( 'add_meta_boxes', 'my_custom_filter_meta_box' );

/**
 * Render Meta Box Content
 */
function my_render_filter_meta_box( $post ) {
    // Security nonce
    wp_nonce_field( 'save_filter_settings', 'my_filter_nonce' );

    // Get currently saved values
    $saved_cats = get_post_meta( $post->ID, '_custom_selected_cats', true );
    $saved_tags = get_post_meta( $post->ID, '_custom_selected_tags', true );

    // Ensure they are arrays
    if ( ! is_array( $saved_cats ) ) $saved_cats = array();
    if ( ! is_array( $saved_tags ) ) $saved_tags = array();

    // Get all Categories (Rubrics)
    $categories = get_categories( array( 'hide_empty' => false ) );
    
    // Get all Tags
    $tags = get_tags( array( 'hide_empty' => false ) );

    echo '<div style="display: flex; gap: 20px;">';
    
    // Column 1: Categories
    echo '<div style="flex: 1;">';
    echo '<h4>Select Rubrics (Categories)</h4>';
    echo '<div style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">';
    foreach ( $categories as $cat ) {
        $checked = in_array( $cat->term_id, $saved_cats ) ? 'checked' : '';
        echo '<label style="display:block; margin-bottom: 5px;">';
        echo '<input type="checkbox" name="custom_cats[]" value="' . esc_attr( $cat->term_id ) . '" ' . $checked . '> ';
        echo esc_html( $cat->name );
        echo '</label>';
    }
    echo '</div></div>';

    // Column 2: Tags
    echo '<div style="flex: 1;">';
    echo '<h4>Select Tags</h4>';
    echo '<div style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">';
    foreach ( $tags as $tag ) {
        $checked = in_array( $tag->term_id, $saved_tags ) ? 'checked' : '';
        echo '<label style="display:block; margin-bottom: 5px;">';
        echo '<input type="checkbox" name="custom_tags[]" value="' . esc_attr( $tag->term_id ) . '" ' . $checked . '> ';
        echo esc_html( $tag->name );
        echo '</label>';
    }
    echo '</div></div>';
    
    echo '</div>';
    echo '<p class="description">Select the items you want to display on this page.</p>';
}

/**
 * Save Meta Box Data
 */
function my_save_filter_settings( $post_id ) {
    // 1. Verify Nonce
    if ( ! isset( $_POST['my_filter_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['my_filter_nonce'], 'save_filter_settings' ) ) return;

    // 2. Prevent Autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    // 3. Check Permissions
    if ( ! current_user_can( 'edit_page', $post_id ) ) return;

    // 4. Save Categories
    if ( isset( $_POST['custom_cats'] ) ) {
        // Sanitize array of IDs
        $cats = array_map( 'intval', $_POST['custom_cats'] );
        update_post_meta( $post_id, '_custom_selected_cats', $cats );
    } else {
        // If unchecked (empty), delete the meta key
        delete_post_meta( $post_id, '_custom_selected_cats' );
    }

    // 5. Save Tags
    if ( isset( $_POST['custom_tags'] ) ) {
        $tags = array_map( 'intval', $_POST['custom_tags'] );
        update_post_meta( $post_id, '_custom_selected_tags', $tags );
    } else {
        delete_post_meta( $post_id, '_custom_selected_tags' );
    }
}
add_action( 'save_post', 'my_save_filter_settings' );

?>