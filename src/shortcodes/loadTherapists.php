<?php

add_shortcode('daroon2_list_therapist', 'daroon2_list_therapist_func');
add_action('wp_enqueue_scripts', function () {
    wp_register_script('daroon2-therapist-search', plugins_url('../../assets/js/therapist-search.js', __FILE__), ['jquery'], '1.0', true);
    wp_register_style('daroon2-therapist-search', plugins_url('../../assets/css/therapist-search.css', __FILE__));
});

function daroon2_list_therapist_func($atts)
{
    $count = 0;
    wp_enqueue_script('daroon2-therapist-search');
    wp_enqueue_style('daroon2-therapist-search');
    $atts = shortcode_atts([
        'category_id' => '',
        'categories_filter' => '',
        'show_privates' => 'false',
        'only_search' => 'false',
    ], $atts);
    $category_id = intval($atts['category_id']);
    $categories_filter = explode(',', $atts['categories_filter']);
    $show_privates = $atts['show_privates'] == 'true';
    $only_search = $atts['only_search'] == 'true';
    // Get top-level categories (team-category)
    $categories = get_terms([
        'taxonomy' => 'team-category',
        'parent' => 0,
        'hide_empty' => false,
    ]);

    $categories = array_filter($categories, function ($cat) use ($categories_filter) {
        return in_array($cat->term_id, $categories_filter);
    });

    // Get therapists (team post type)
    $args = [
        'post_type' => 'team',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ];
    if ($category_id) {
        $args['tax_query'] = [[
            'taxonomy' => 'team-category',
            'field' => 'term_id',
            'terms' => $category_id,
        ]];
    }
    $therapists = get_posts($args);

    ob_start();
?>
    <div class="daroon2-therapist-filter-wrap w-100">
        <div class="therapist-list-search-wrap-wrap mb-5" style="<?php echo !$only_search ? 'display: none;' : ''; ?>">
            <div class="therapist-list-search-wrap d-flex gap-1 align-items-strech">
                <svg width="29" height="28" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14.3284 25.375C7.73678 25.375 2.37012 20.0083 2.37012 13.4167C2.37012 6.82501 7.73678 1.45834 14.3284 1.45834C20.9201 1.45834 26.2868 6.82501 26.2868 13.4167C26.2868 20.0083 20.9201 25.375 14.3284 25.375ZM14.3284 3.20834C8.69345 3.20834 4.12012 7.79334 4.12012 13.4167C4.12012 19.04 8.69345 23.625 14.3284 23.625C19.9634 23.625 24.5368 19.04 24.5368 13.4167C24.5368 7.79334 19.9634 3.20834 14.3284 3.20834Z" fill="black" />
                    <path d="M26.5784 26.5417C26.3567 26.5417 26.135 26.46 25.96 26.285L23.6267 23.9517C23.2884 23.6133 23.2884 23.0533 23.6267 22.715C23.965 22.3767 24.525 22.3767 24.8634 22.715L27.1967 25.0483C27.535 25.3867 27.535 25.9467 27.1967 26.285C27.0217 26.46 26.8 26.5417 26.5784 26.5417Z" fill="black" />
                </svg>
                <input type="text" id="daroon2-therapist-search" class="grow-1 input body2" placeholder="Search therapists..." style="height: 50px" />
                <?php if (!$only_search): ?>
                    <span class="search-close chip" style="--padding-x: 10px">
                        <svg width="29" height="28" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22.3495 19.7858L16.5637 14L22.3495 8.2142C22.8013 7.76236 22.8013 7.01296 22.3495 6.56111C21.8976 6.10927 21.1482 6.10927 20.6964 6.56111L14.9106 12.3469L9.12478 6.56111C8.67294 6.10927 7.92354 6.10927 7.4717 6.56111C7.01985 7.01296 7.01985 7.76236 7.4717 8.2142L13.2575 14L7.4717 19.7858C7.01985 20.2377 7.01985 20.9871 7.4717 21.4389C7.92354 21.8907 8.67294 21.8907 9.12478 21.4389L14.9106 15.6531L20.6964 21.4389C21.1482 21.8907 21.8976 21.8907 22.3495 21.4389C22.8013 20.9871 22.8013 20.2377 22.3495 19.7858Z" fill="black" />
                        </svg>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!$only_search): ?>
            <div class="daroon2-therapist-categories-wrap mb-5">
                <div class="daroon2-therapist-categories">
                    <span class="search-trigger chip" style="--padding-x: 10px">
                        <svg width="29" height="28" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14.3284 25.375C7.73678 25.375 2.37012 20.0083 2.37012 13.4167C2.37012 6.82501 7.73678 1.45834 14.3284 1.45834C20.9201 1.45834 26.2868 6.82501 26.2868 13.4167C26.2868 20.0083 20.9201 25.375 14.3284 25.375ZM14.3284 3.20834C8.69345 3.20834 4.12012 7.79334 4.12012 13.4167C4.12012 19.04 8.69345 23.625 14.3284 23.625C19.9634 23.625 24.5368 19.04 24.5368 13.4167C24.5368 7.79334 19.9634 3.20834 14.3284 3.20834Z" fill="black" />
                            <path d="M26.5784 26.5417C26.3567 26.5417 26.135 26.46 25.96 26.285L23.6267 23.9517C23.2884 23.6133 23.2884 23.0533 23.6267 22.715C23.965 22.3767 24.525 22.3767 24.8634 22.715L27.1967 25.0483C27.535 25.3867 27.535 25.9467 27.1967 26.285C27.0217 26.46 26.8 26.5417 26.5784 26.5417Z" fill="black" />
                        </svg>
                    </span>
                    <span data-slug="all" class="chip active">All</span>
                    <?php foreach ($categories as $cat): ?>
                        <span class="chip" data-slug="<?php echo esc_attr($cat->slug); ?>"><?php echo esc_html($cat->name); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div id="available-therapists" class="mb-2 title3">
            <span>-</span> therapists available
        </div>
        <div class="daroon2-therapist-list d-flex flex-wrap align-items-strech w-100 justify-content-start">
            <?php
            $moshavere_l = (is_rtl()) ? 'https://daroon.me' : get_home_url() . '/team/saeed-heydarian-2/';

            foreach ($therapists as $post):
                $name = get_the_title($post);
                $audio = get_post_meta($post->ID, 'team_audio', true);
                $permalink = get_permalink($post);
                $post_cats = get_the_terms($post, 'team-category');
                $job_title = get_the_terms($post, 'team-job-title');
                $reviews = get_post_meta($post->ID, 'team_reviews', true);
                $cat_slugs = $post_cats ? implode(' ', array_map(function ($c) {
                    return $c->slug;
                }, $post_cats)) : '';
                $image = get_the_post_thumbnail_url($post, 'thumb-64x64');
                $settings = get_post_meta($post->ID, 'team_settings', true);

                if (isset($settings) && isset($settings['is_private']) && $settings['is_private'] == '1' && !$show_privates) {
                    continue;
                }

                if (isset($settings['team_settings_staff_member_id'])) {
                    $staff_info = daroon2_get_bookly_staff_info($settings['team_settings_staff_member_id']);
                    $nearest_available_time = daroon2_get_staff_nearest_available_time($settings['team_settings_staff_member_id']);
                } else {
                    $staff_info = NULL;
                    $nearest_available_time = NULL;
                }
                $count++;
            ?>
                <article class="daroon2-therapist-item d-flex direction-column justify-content-between" data-cats="<?php echo esc_attr($cat_slugs); ?>">
                    <header class="d-flex direction-column gap-2 align-items-start mb-4 w-100">
                        <a href="<?php echo esc_url($permalink); ?>">
                            <img style="border-radius: 8px" width="64" height="64" src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($name); ?>" />
                        </a>
                        <a style="text-decoration: none;" href="<?php echo esc_url($permalink); ?>">
                            <h3 class="daroon2-therapist-name title1 color-content-primary"><?php echo esc_html($name); ?></h3>
                        </a>
                        <?php if ($job_title): ?><div class="title2 color-action-aqua"><?php echo esc_html($job_title[0]->name); ?></div><?php endif; ?>
                    </header>

                    <div class="daroon2-therapist-content mb-4 w-100">
                        <p class="title1 color-content-secondary">
                            <?php if (isset($staff_info)): ?>
                                <?= ($staff_info['total_appointments']); ?> Appointments (<?php echo sizeof($reviews); ?> reviews)
                            <?php endif; ?>
                        </p>

                        <?php if ($audio): ?>
                            <div class="audio mt-2">
                                <?php
                                if (isset($audio) && $audio != '') {
                                ?>
                                    <div class="audio-player">
                                        <button id="play-pause">►</button>
                                        <div class="progress-container" id="progress-container">
                                            <div class="bg-overlay"></div>
                                            <div class="progress" id="progress"></div>
                                        </div>
                                        <div class="time d-none" id="current-time">0:00</div>
                                        <div class="time d-none" id="duration">0:00</div>
                                        <input class="d-none" type="range" id="volume" min="0" max="1" step="0.01" value="1">
                                        <audio id="audio" src="<?php echo $audio; ?>"></audio>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <footer class="w-100">
                        <?php if (isset($nearest_available_time)): ?>
                            <p class="mb-3 d-flex">
                                <span class="color-content-secondary title1"><?= "Earliest available"; ?></span>
                                <span class="color-action-ember title2"><?= date('D, M j', strtotime($nearest_available_time['next_available_time'])); ?></span>
                            </p>
                        <?php endif; ?>
                        <a class="btn btn-primary btn-size-m btn-style-outline"
                            href="<?php echo esc_url($permalink); ?>">
                            <span>View Profile</span>
                        </a>

                    </footer>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        setTimeout(() => {
            window.daroon2_update_therapists_count(<?= $count; ?>);
        }, 1000);
    </script>
<?php
    $html = ob_get_clean();
    echo $html;
}
?>