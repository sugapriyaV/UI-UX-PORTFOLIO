<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

extract( $args );

if ( !is_user_logged_in() || !class_exists('WP_Job_Board_Pro_User') ) {
    return;
}

extract( $args );
extract( $instance );

echo trim($before_widget);

$title = apply_filters('widget_title', $instance['title']);

if ( $title ) {
    echo trim($before_title) . trim($title) . $after_title;
}

$user_id = get_current_user_id();

/*
|--------------------------------------------------------------------------
| USER DATA
|--------------------------------------------------------------------------
*/
if ( WP_Job_Board_Pro_User::is_employer($user_id) ) {
    $employer_id = WP_Job_Board_Pro_User::get_employer_by_user_id($user_id);
    $title = get_the_title($employer_id);
    $post  = get_post($employer_id);

    ob_start();
    superio_employer_display_logo($post);
    $logo = ob_get_clean();

    $location = superio_employer_display_short_location($post, 'no-title', false);

    if ($nav_menu_employer) {

        $term = get_term_by('slug', $nav_menu_employer, 'nav_menu');

        if ( !empty($term) ) {
            $nav_menu_id = $term->term_id;
        }
    }

} elseif ( method_exists('WP_Job_Board_Pro_User', 'is_employee') && WP_Job_Board_Pro_User::is_employee($user_id) ) {

    $user_id = WP_Job_Board_Pro_User::get_user_id();

    if ( empty($user_id) ) {
        return;
    }

    $employer_id = WP_Job_Board_Pro_User::get_employer_by_user_id($user_id);

    $title = get_the_title($employer_id);
    $post  = get_post($employer_id);

    ob_start();
    superio_employer_display_logo($post);
    $logo = ob_get_clean();

    $location = superio_employer_display_short_location($post, 'no-title', false);

    if ($nav_menu_employee) {
        $term = get_term_by('slug', $nav_menu_employee, 'nav_menu');
        if ( !empty($term) ) {
            $nav_menu_id = $term->term_id;
        }
    }

} elseif ( WP_Job_Board_Pro_User::is_candidate() ) {

    $candidate_id = WP_Job_Board_Pro_User::get_candidate_by_user_id($user_id);

    $title = get_the_title($candidate_id);
    $post  = get_post($candidate_id);

    ob_start();
    superio_candidate_display_logo($post);
    $logo = ob_get_clean();

    $location = superio_candidate_display_short_location($post, 'no-title', false);

    if ($nav_menu_candidate) {

        $term = get_term_by('slug', $nav_menu_candidate, 'nav_menu');

        if ( !empty($term) ) {
            $nav_menu_id = $term->term_id;
        }
    }

    $profile_percents = WP_Job_Board_Pro_User::compute_profile_percent($candidate_id);

} else {
    return;
}

/*
|--------------------------------------------------------------------------
| LOGO URL
|--------------------------------------------------------------------------
*/
$educfind_logo = get_site_url() . '/wp-content/uploads/2026/02/Logo-bleu-clair-educfind-300x94.png';
?>

<div class="educfind-sidebar">
    <button class="educfind-mobile-close">
        ✕
    </button>
    <!-- TOP CONTENT -->
    <div class="educfind-sidebar-top">

        <!-- LOGO -->
        <div class="educfind-logo">
            <a href="<?php echo esc_url(home_url('/')); ?>">
                <img src="<?php echo esc_url($educfind_logo); ?>" alt="Educfind">
            </a>
        </div>

        <!-- PLATFORM -->
        <div class="educfind-platform">
            • PLATEFORME • FRANCE
        </div>

        <!-- MENU -->
        <?php if ( !empty($nav_menu_id) ) { ?>

            <div class="educfind-menu-wrapper">

                <?php
                    $args = array(
                        'menu'              => $nav_menu_id,
                        'container_class'   => 'navbar-collapse no-padding',
                        'menu_class'        => 'menu_short_profile educfind-menu',
                        'fallback_cb'       => '',
                        'walker'            => new Superio_Nav_Menu()
                    );

                    wp_nav_menu($args);
                ?>

            </div>

        <?php } ?>

        <!-- SKILLS -->

    </div>

    <!-- BOTTOM USER CARD -->
    <div class="educfind-user-card">

        <div class="educfind-user-avatar">

            <?php if ( !empty($logo) ) { ?>
                <?php echo trim($logo); ?>
            <?php } ?>

            <span class="educfind-online-dot"></span>

        </div>

        <div class="educfind-user-info">

            <?php if ( $title ) { ?>
                <h4>
                    <a href="<?php echo esc_url(get_permalink($post)); ?>">
                        <?php echo trim($title); ?>
                    </a>
                </h4>
            <?php } ?>

            <?php if ( !empty($location) ) { ?>
                <p><?php echo wp_strip_all_tags($location); ?></p>
            <?php } ?>

        </div>

        <div class="educfind-user-arrow">
            ›
        </div>

    </div>

</div>

<?php echo trim($after_widget); ?>