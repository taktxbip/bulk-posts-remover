<?php
function pr_submenu_pages()
{
    add_submenu_page('tools.php', 'Posts Remover', 'Posts Remover', 'administrator', 'posts-remover-settings', 'posts_remover_callback', 8);
}
add_action('admin_menu', 'pr_submenu_pages');

function posts_remover_callback()
{
    if (!current_user_can('administrator')) return;
    require_once POSTS_REMOVER_DIR . '/views/posts-remover-tab.php';
}
