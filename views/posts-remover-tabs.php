<div class="wrap php-run-once-tabs">
    <h1><?php echo esc_html(get_admin_page_title()) ?></h1>

    <nav class="nav-tab-wrapper">
        <a href="?page=bulk-posts-remover" class="nav-tab <?php if ($active_tab === null) : ?>nav-tab-active<?php endif; ?>">Remove</a>
        <a href="?page=bulk-posts-remover&tab=settings" class="nav-tab <?php if ($active_tab === 'settings') : ?>nav-tab-active<?php endif; ?>">Settings</a>
        <a href="?page=bulk-posts-remover&tab=about" class="nav-tab <?php if ($active_tab === 'about') : ?>nav-tab-active<?php endif; ?>">About</a>
    </nav>
</div>