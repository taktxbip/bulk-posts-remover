<?php

// Enqueue admin 
function pr_enqueue_admin()
{
    wp_enqueue_style('pr-posts-remover-styles', plugins_url('posts-remover.css', '/wordpress-posts-remover/assets/posts-remover.css'), array(), false);
    wp_enqueue_script('pr-posts-remover-scripts', plugins_url('posts-remover.js', '/wordpress-posts-remover/assets/posts-remover.js'), array('jquery'), false, true);
    wp_localize_script('pr-posts-remover-scripts', 'myajax', array(
        'url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('myajax-nonce')
    ));
}
add_action('admin_enqueue_scripts', 'pr_enqueue_admin');


add_action('plugins_loaded', 'wpdocs_i_am_a_function');
function wpdocs_i_am_a_function()
{
    if (is_admin()) {
        require_once POSTS_REMOVER_DIR . '/inc/posts-remover-functions.php';
        require_once POSTS_REMOVER_DIR . '/inc/posts-remover-menu.php';
        require_once POSTS_REMOVER_DIR . '/inc/classes/class-posts-remover.php';
    }
}
