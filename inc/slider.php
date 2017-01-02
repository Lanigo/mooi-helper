<?php
/*
*   The function to display sliders
*/

// Shortcode for sliders
add_shortcode('mooihelp_slider', 'mooihelp_display_slider');

// The html to display the slider and images
function mooihelp_display_slider() {
$plugins_url = plugins_url();
echo '<div class="slide-container">
    <div id="slides"> 
        <img src="' . plugins_url( 'assets/img/example-slide-1.jpg' , __FILE__ ) . '" /> 
        <img src="' . plugins_url( 'assets/img/example-slide-2.jpg' , __FILE__ ) . '" /> 
        <img src="' . plugins_url( 'assets/img/example-slide-3.jpg' , __FILE__ ) . '" /> 
        <img src="' . plugins_url( 'assets/img/example-slide-4.jpg' , __FILE__ ) . '" /> 
            <a href="#" class="slidesjs-previous slidesjs-navigation">
                <i class="fa fa-chevron-left icon-large"></i>
            </a>
            <a href="#" class="slidesjs-next slidesjs-navigation">
                <i class="fa fa-chevron-right icon-large"></i>
            </a>
    </div> 
</div>';
}

// Create the custom post type for the slider
add_action('init', 'mooihelp_register_slider');

function mooihelp_register_slider() {
    $labels = array(
        'menu_name' => _x('Sliders', 'slidesjs_slider'),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'description' => 'Slideshows',
        'supports' => array('title', 'editor'),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'menu_position' => 10,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type('slidesjs_slider', $args);
}

add_action('add_meta_boxes', 'mooihelp_slider_meta_box'); 
function mooihelp_slider_meta_box() { 
    add_meta_box( "mooihelp-slider-images", "Slider Images", 'mooihelp_view_slider_images_box', "slidesjs_slider", "normal" ); 
} 

function mooihelp_view_slider_images_box() { 
    global $post; 
    $gallery_images = get_post_meta($post->ID, "_mooihelp_gallery_images", true); 
     
    $gallery_images = count( $gallery_images < 1 ) ? json_decode( $gallery_images ) : array(); 

    // Use nonce for verification 
    $html = '<input type="hidden" name="mooihelp_slider_box_nonce" value="'. wp_create_nonce(basename(__FILE__)). '" />'; 

    $html .= ' '; 

    $html .= ' <table class="form-table">
        <tbody>
            <tr> 
                <th><label for="Upload Images">Image 1</label></th>     
                <td><input id="mooihelp_slider_upload" type="text" name="gallery_img[]" value="' . $gallery_images[0] . '" /></td> 
            </tr> 
            <tr> 
                <th><label for="Upload Images">Image 2</label></th> 
                <td><input id="mooihelp_slider_upload" type="text" name="gallery_img[]" value="' . $gallery_images[1] . '" /></td> 
            </tr> 
            <tr> 
                <th><label for="Upload Images">Image 3</label></th> 
                <td><input id="mooihelp_slider_upload" type="text" name="gallery_img[]" value="' . $gallery_images[2] . '" /></td>
            </tr> 
            <tr> 
                <th><label for="Upload Images">Image 4</label></th> 
                <td><input id="mooihelp_slider_upload" type="text" name="gallery_img[]" value="' . $gallery_images[3] . '" /></td> 
            </tr> 
            <tr> 
                <th><label for="Upload Images">Image 5</label></th> 
                <td><input id="mooihelp_slider_upload" type="text" name="gallery_img[]" value="' . $gallery_images[4] . '" /></td> 
            </tr> 
        </tbody> 
    </table> '; 
    
    echo $html; 
}

// Save slider images
add_action('save_post', 'mooihelp_save_slider_info');

function mooihelp_save_slider_info($post_id) {

    // verify nonce

    if ( isset($_POST['mooihelp_slider_box_nonce']) && !wp_verify_nonce($_POST['mooihelp_slider_box_nonce'], __FILE__) ) {

        return $post_id;

    }

    // check autosave

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {

        return $post_id;

    }

    // check permissions

    if ('slidesjs_slider' == (isset($_POST['post_type'])) && current_user_can('edit_post', $post_id)) {

        /* Save Slider Images */

        $gallery_images = (isset($_POST['gallery_images']) ? $_POST['gallery_images'] : ”);

        $gallery_images = strip_tags(json_encode($gallery_images));

        update_post_meta($post_id, "_mooihelp_gallery_images", $gallery_images);

    } else {

        return $post_id;

    }

}

/* Define shortcode column in the Slider List View */
add_filter('manage_edit-slidesjs_slider_columns', 'mooihelp_set_custom_edit_slidesjs_slider_columns');
add_action('manage_slidesjs_slider_posts_custom_column', 'mooihelp_custom_slidesjs_slider_column', 10, 2);

function mooihelp_set_custom_edit_slidesjs_slider_columns($columns) {
    return $columns
            + array('slider_shortcode' => __('Shortcode'));
}

function mooihelp_custom_slidesjs_slider_column($column, $post_id) {

    $slider_meta = get_post_meta($post_id, "_mooihelp_slider_meta", true);
    $slider_meta = ($slider_meta != '') ? json_decode($slider_meta) : array();

    switch ($column) {
        case 'slider_shortcode':
            echo "[mooihelp_slider id='$post_id' /]";
            break;

    }
}