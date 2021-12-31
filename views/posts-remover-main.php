<div class="wrap">
    <div id="posts-remover-app">
        <div class="posts-remover-main">
            <form id="posts-remover-form">
                <div class="posts-remover-filters">
                    <select name="post_type" id="post_type_filter">
                        <option selected disabled>Select Post Type</option>
                        <?php
                        $post_types = get_post_types();
                        // dbg($post_types);
                        foreach ($post_types as $type) :
                            if (!$name = pr_get_post_name($type)) continue;
                        ?>
                            <option value="<?php echo $type ?>"><?php echo $name ?></option>
                        <?php endforeach;   ?>
                    </select>
                    <input type="text" id="date_from" name="date_from" placeholder="Date from">
                    <input type="text" id="date_to" name="date_to" placeholder="Date to">
                </div>
                <div role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" class="posts-remover-progressbar">
                    <div class="progressbar glare">
                        <span><span class="num">0</span>% completed</span>
                    </div>
                </div>
                <div id="posts-remover-estimate">
                    <div class="posts-remover-elapsed">
                        <span><strong>Elapsed</strong></span>
                        <div class="time">
                            <span class="h">00</span>:<span class="m">00</span>:<span class="s">00</span>
                        </div>
                    </div>
                    <div class="posts-remover-remaining">
                        <span><strong>Estimate</strong></span>
                        <div class="time">
                            <span class="h">00</span>:<span class="m">00</span>:<span class="s">00</span>
                        </div>
                    </div>
                </div>
                <div id="posts-remover-summary"></div>
                <p>
                    <button type="submit" class="button button-secondary button-large">Remove</button>
                </p>
            </form>

            <h4 class="title">Log</h4>
            <div id="posts-remover-log">
                <ol></ol>
            </div>
        </div>
        <aside class="posts-remover-sidebar">
            <div class="posts-remover-box">
                <h2>About</h2>
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Corrupti quibusdam sit fugit numquam minus facere quia veritatis reiciendis, quam placeat temporibus aspernatur eligendi maiores blanditiis. Quas voluptates praesentium quod velit.</p>
            </div>
            <div class="posts-remover-box">
                <h2>About</h2>
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Corrupti quibusdam sit fugit numquam minus facere quia veritatis reiciendis, quam placeat temporibus aspernatur eligendi maiores blanditiis. Quas voluptates praesentium quod velit.</p>
            </div>
        </aside>
    </div>
</div>