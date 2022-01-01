<svg style="display:none">
    <symbol id="icon-close" viewBox="0 0 20 20">
        <path d="M10,11.6l-8.1,8.1C1.7,19.9,1.4,20,1.1,20c-0.3,0-0.6-0.1-0.8-0.3C0.1,19.5,0,19.2,0,18.9c0-0.3,0.1-0.6,0.3-0.8L8.4,10
		L0.3,1.9C0.1,1.7,0,1.4,0,1.1c0-0.3,0.1-0.6,0.3-0.8S0.8,0,1.1,0c0.3,0,0.6,0.1,0.8,0.3L10,8.4l8.1-8.1C18.3,0.1,18.6,0,18.9,0
		c0.3,0,0.6,0.1,0.8,0.3S20,0.8,20,1.1c0,0.3-0.1,0.6-0.3,0.8L11.6,10l8.1,8.1c0.3,0.3,0.4,0.7,0.3,1.1c-0.1,0.4-0.4,0.7-0.8,0.8
		c-0.4,0.1-0.8,0-1.1-0.3L10,11.6z" />
        <path d="M10,11.6l-8.1,8.1C1.7,19.9,1.4,20,1.1,20c-0.3,0-0.6-0.1-0.8-0.3C0.1,19.5,0,19.2,0,18.9c0-0.3,0.1-0.6,0.3-0.8L8.4,10
		L0.3,1.9C0.1,1.7,0,1.4,0,1.1c0-0.3,0.1-0.6,0.3-0.8S0.8,0,1.1,0c0.3,0,0.6,0.1,0.8,0.3L10,8.4l8.1-8.1C18.3,0.1,18.6,0,18.9,0
		c0.3,0,0.6,0.1,0.8,0.3S20,0.8,20,1.1c0,0.3-0.1,0.6-0.3,0.8L11.6,10l8.1,8.1c0.3,0.3,0.4,0.7,0.3,1.1c-0.1,0.4-0.4,0.7-0.8,0.8
		c-0.4,0.1-0.8,0-1.1-0.3L10,11.6z" />
    </symbol>
</svg>

<div class="wrap">
    <div id="posts-remover-app">
        <div class="posts-remover-main">
            <form id="posts-remover-form">
                <div class="posts-remover-filters">
                    <h4>Filters</h4>
                    <div class="filters-wrap">
                        <select name="post_type" id="post_type_filter">
                            <option selected disabled>Select Post Type</option>
                            <?php
                            $post_types = get_post_types([
                                'public'   => true,
                                '_builtin' => true,
                            ], 'objects', 'and');

                            $post_types = array_merge($post_types, get_post_types([
                                'public'   => true,
                                '_builtin' => false,
                            ], 'objects', 'and'));

                            foreach ($post_types as $name => $item) :
                            ?>
                                <option value="<?php echo $name ?>"><?php echo $item->label . ' (' . $name . ')' ?></option>
                            <?php endforeach;   ?>
                        </select>
                        <input type="text" class="datetimepicker" name="date_from" value="" placeholder="Date from">
                        <input type="text" class="datetimepicker" name="date_to" value="" placeholder="Date to">
                    </div>
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
                    <button type="submit" class="button button-primary button-large">Remove</button>
                </p>
            </form>

            <h4 class="title-log">Log</h4>
            <div id="posts-remover-log">
                <ol></ol>
            </div>
        </div>
        <aside class="posts-remover-sidebar">
            <div class="posts-remover-box">
                <h2>What is this?</h2>
                <p>This is </p>
            </div>
            <div class="posts-remover-box">
                <h2>How does this work?</h2>
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Corrupti quibusdam sit fugit numquam minus facere quia veritatis reiciendis, quam placeat temporibus aspernatur eligendi maiores blanditiis. Quas voluptates praesentium quod velit.</p>
            </div>
        </aside>
    </div>
</div>