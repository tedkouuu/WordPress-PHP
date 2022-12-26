<?php

function university_files(){
    wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    
}

add_action("wp_enqueue_scripts", "university_files");

function university_features(){
    add_theme_support('title-tag');
}

add_action("after_setup_theme", 'university_features');

// Before WP sends the query off to the DB, it will give my function the last order, it will give me a chance to adjust the query
// WP will pass me the query object so I can manipulate it 
// Currently I am editing the default query
function university_adjust_queries($query){

    if (!is_admin() AND is_post_type_archive('program') AND is_main_query()) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }

    if (!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) { // I don't want to manipulate the back-end, only the front-end
    $today = date('Ymd');
    $query->set('meta_key', 'event_date');
    $query->set('orderby', 'meta_value_num'); 
    $query->set('order', 'ASC'); 
    $query->set('meta_query', array(
            array(
         'key' => 'event_date', 
         'compare' => '>=',
         'value' => $today,
         'type' => 'numeric'
        )
    ));
    }
}

add_action('pre_get_posts', 'university_adjust_queries');

/*
I created a new file in the wp-content directory and named it unviversity-post-types.php
It is better the code below to not be in the functions.php, because if the user decides
to change the theme, his access to the Event Post Type will be denied

<?php
// Function for the creation of custom Post types
function university_post_types(){
    // Event Post Type
    register_post_type('event', array(
        'supports' => array('title', 'editor', 'excerpt'),
        'rewrite' => array('slug' => 'events'),
        'has_archive' => true,
        'public' => true,
        'show_in_rest' => true,
        'labels' => array(
            'name' => 'Events',
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event'
        ),
        'menu_icon' => 'dashicons-calendar'
    ));

    // Program Post Type
    register_post_type('program', array(
        'supports' => array('title', 'editor'),
        'rewrite' => array('slug' => 'programs'),
        'has_archive' => true,
        'public' => true,
        'show_in_rest' => true,
        'labels' => array(
            'name' => 'Programs',
            'add_new_item' => 'Add New Program',
            'edit_item' => 'Edit Program',
            'all_items' => 'All Programs',
            'singular_name' => 'Program'
        ),
        'menu_icon' => 'dashicons-awards'
    ));
}

add_action('init', 'university_post_types');
*/