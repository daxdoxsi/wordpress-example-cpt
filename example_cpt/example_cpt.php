<?php

/**
 * Plugin Name: Example CPT
 * Plugin URI: https://www.qdavid.com/
 * Description: This is a testing plugin for evaluation purposes.
 * Version: 1.0
 * Author: David M. Miller
 * Author URI: https://www.qdavid.com
 */

function example_custom_post_type()
{
    register_post_type('example_cpt',
        array(
            'labels' => array(
                'name' => __('Example CPT', 'textdomain'),
                'singular_name' => __('Example CPT', 'textdomain'),
                'all_items' => __('All Example CPT'),
                'search_items' => __('Search Example CPT'),
            ),
            'rewrite' => ['slug' => 'example_cpt'],
            'supports' => array(
                'title',
                'editor',
                'excerpt',
                'author',
                'thumbnail',
                'comments',
                'revisions',
                'custom-fields',
            ),
            'capability_type' => 'post',
            'show_in_rest' => true,
            'public' => true,
            'has_archive' => true,
        )
    );
}

add_action('init', 'example_custom_post_type');

function example_meta_fields()
{

    register_meta('post', 'example_box_id', array(
        'type' => 'string',
        'description' => 'Example field',
        'single' => true,
        'show_in_rest' => true
    ));

}

add_action('rest_api_init', 'example_meta_fields');


function example_add_custom_box()
{
    add_meta_box(
        'example_cpt',
        'Example Meta',
        'example_custom_box_html',
        ['example_cpt']
    );
}

function example_custom_box_html($post)
{
    $example_cpt_value = get_post_meta($post->ID, 'example_box_id', true);
    wp_nonce_field('example_save_meta_field', 'example_cpt_nonce');
    ?>
    <label for="example_box_id">Example Meta</label>
    <input
            type="text"
            name="example_box_id"
            id="example_box_id"
            size="20"
            value="<?= sanitize_text_field($example_cpt_value) ?>"
    >
    <?php
}

add_action('add_meta_boxes', 'example_add_custom_box');

function example_save_meta_fields($post_id)
{

    // Check if nonce is set
    if (!isset($_POST['example_cpt_nonce'])) {
        return $post_id;
    }

    if (!wp_verify_nonce($_POST['example_cpt_nonce'], 'example_save_meta_field')) {
        return $post_id;
    }

    // Check that the logged in user has permission to edit this post
    if (!current_user_can('edit_posts')) {
        return $post_id;
    }

    $example_cpt_field_value = sanitize_text_field($_POST['example_box_id']);

    $result = update_post_meta($post_id, 'example_box_id', $example_cpt_field_value);

}

add_action('save_post', 'example_save_meta_fields');


function example_register_category_cpt()
{
    $labels = array(
        'name' => _x('Example CPT Category', 'taxonomy general name'),
        'singular_name' => _x('Example CPT Category', 'taxonomy singular name'),
        'search_items' => __('Search Example CPT Category'),
        'all_items' => __('All Example CPT Category'),
        'parent_item' => __('Parent Example CPT Category'),
        'parent_item_colon' => __('Parent Example CPT Category:'),
        'edit_item' => __('Edit Example CPT Category'),
        'update_item' => __('Update Example CPT Category'),
        'add_new_item' => __('Add New Example CPT Category'),
        'new_item_name' => __('New Example CPT Category Name'),
        'menu_name' => __('Categories'),
    );
    $args = array(
        'hierarchical' => true, // make it hierarchical (like categories)
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'example_cpt_category'],
    );
    register_taxonomy('example_cpt_category', ['example_cpt'], $args);
}

add_action('init', 'example_register_category_cpt');

function example_register_taxonomy_cpt()
{
    $labels = array(
        'name' => _x('Example CPT Tags', 'taxonomy general name'),
        'singular_name' => _x('Example CPT Tag', 'taxonomy singular name'),
        'search_items' => __('Search Example CPT Tag'),
        'all_items' => __('All Example CPT Tag'),
        'parent_item' => __('Parent Example CPT Tag'),
        'parent_item_colon' => __('Parent Example CPT Tag:'),
        'edit_item' => __('Edit Example CPT Tag'),
        'update_item' => __('Update Example CPT Tag'),
        'add_new_item' => __('Add New Example CPT Tag'),
        'new_item_name' => __('New Example CPT Tag Name'),
        'menu_name' => __('Tags'),
    );
    $args = array(
        'hierarchical' => true, // make it hierarchical (like categories)
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'example_cpt_taxonomy'],
    );
    register_taxonomy('example_cpt_taxonomy', ['example_cpt'], $args);
}

add_action('init', 'example_register_taxonomy_cpt');

