<?php
/**
 * Plugin Name: GameStore General
 * Description: Core Code for GameStore
 * Version: 1.0
 * Author: mistblessed.code
 * Author URI: https://mistblessed.code
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

function gamestore_remove_dashboard_widgets(){
    global $wp_meta_boxes;

    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
    unset($wp_meta_boxes['dashboard']['normal']['high']['rank_math_dashboard_widget']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_site_health']);
}
add_action('wp_dashboard_setup', 'gamestore_remove_dashboard_widgets');

function gamestore_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'gamestore_mime_types');

function gamestore_fix_svg() {
    echo '<style>
        .attachment-266x266, .thumbnail img {
            width: 100% !important;
            height: auto !important;
        }
    </style>';
}
add_action('admin_head', 'gamestore_fix_svg');

function gamestore_register_news() {
    register_post_type( 'news', array(
        'labels' => array(
            'name'          => 'News',
            'singular_name' => 'News Item',
            'add_new_item'  => 'Add News Item',
            'edit_item'     => 'Edit News Item',
            'all_items'     => 'All News',
        ),
        'public'       => true,
        'has_archive'  => true,
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-megaphone',
        'supports'     => array(
            'title',
            'editor',
            'thumbnail',
            'excerpt',
        ),
        'rewrite' => array(
            'slug' => 'news',
        ),
    ) );

    register_taxonomy( 'news_category', array( 'news' ), array(
        'labels' => array(
            'name'          => 'News Categories',
            'singular_name' => 'News Category',
            'add_new_item'  => 'Add News Category',
            'edit_item'     => 'Edit News Category',
            'search_items'  => 'Search News Categories',
            'all_items'     => 'All News Categories',
        ),
        'public'            => true,
        'hierarchical'      => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'rewrite'           => array(
            'slug' => 'news-category',
        ),
    ) );
}

add_action( 'init', 'gamestore_register_news' );