<div class="wrap">
    <h1>Posts Remover</h1>
    <p>Select post type</p>
    <div id="posts-remover-app">
        <form id="posts-remover-form">
            <div class="posts-remover-filters">
                <select name="post_type" id="post_type_filter">
                    <option selected disabled>Choose Post Type</option>
                    <?php
                    $post_types = get_post_types();
                    dbg($post_types);
                    foreach ($post_types as $type) :
                        if (!$name = pr_get_post_name($type)) continue;
                    ?>
                        <option value="<?php echo $type ?>"><?php echo $name ?></option>
                    <?php endforeach;   ?>
                </select>

            </div>
            <div role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" class="posts-remover-progressbar">
                <div class="progressbar glare">
                    <span><span class="num">0</span>% completed</span>
                </div>
            </div>
            <div id="posts-remover-summary">
            </div>
            <p>
                <button type="submit" class="button button-secondary button-large">Remove</button>
            </p>
        </form>

        <h2 class="title">Posts Remover Log</h2>
        <div id="posts-remover-log">
            <ol></ol>
        </div>
    </div>
</div>