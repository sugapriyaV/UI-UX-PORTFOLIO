<?php
/**
 * superio functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Superio
 * @since Superio 1.3.35
 */

define( 'SUPERIO_THEME_VERSION', '1.3.35' );
define( 'SUPERIO_DEMO_MODE', false );

if ( ! isset( $content_width ) ) {
	$content_width = 660;
}

if ( ! function_exists( 'superio_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since Superio 1.0
 */
function superio_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on superio, use a find and replace
	 * to change 'superio' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'superio', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1200, 750, true );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'superio' ),
		'mobile-primary' => esc_html__( 'Mobile Primary Menu', 'superio' ),
		'employer-menu' => esc_html__( 'Employer Menu', 'superio' ),
		'candidate-menu' => esc_html__( 'Candidate Menu', 'superio' ),
		'employee-menu' => esc_html__( 'Employee Menu', 'superio' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	add_theme_support( "woocommerce", array('gallery_thumbnail_image_width' => 410) );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	
	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
	) );

	$color_scheme  = superio_get_color_scheme();
	$default_color = trim( $color_scheme[0], '#' );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'superio_custom_background_args', array(
		'default-color'      => $default_color,
		'default-attachment' => 'fixed',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Enqueue editor styles.
	add_editor_style( array( 'css/style-editor.css' ) );
}
endif; // superio_setup
add_action( 'after_setup_theme', 'superio_setup' );

/**
 * Load Google Front
 */
function superio_get_fonts_url() {
    $fonts_url = '';

    /* Translators: If there are characters in your language that are not
    * supported by Montserrat, translate this to 'off'. Do not translate
    * into your own language.
    */
    $jost = _x( 'on', 'Jost font: on or off', 'superio' );

    if ( 'off' !== $jost  ) {
        $font_families = array();
        
        if ( 'off' !== $jost ) {
            $font_families[] = 'Jost:400,500,600,700,800';
        }

        $query_args = array(
            'family' => ( implode( '|', $font_families ) ),
            'subset' => urlencode( 'latin,latin-ext' ),
        );
    
    $protocol = is_ssl() ? 'https:' : 'http:';
        $fonts_url = add_query_arg( $query_args, $protocol .'//fonts.googleapis.com/css' );
    }
 
    return esc_url( $fonts_url );
}

function superio_admin_enqueue_styles() {

	// load font
  	wp_enqueue_style( 'superio-theme-fonts', superio_get_fonts_url(), array(), null );

	//load font awesome
	wp_enqueue_style( 'all-awesome', get_template_directory_uri() . '/css/all-awesome.css', array(), '5.11.2' );

	//load font flaticon
	wp_enqueue_style( 'flaticon', get_template_directory_uri() . '/css/flaticon.css', array(), '1.0.0' );

	// load font themify icon
	wp_enqueue_style( 'themify-icons', get_template_directory_uri() . '/css/themify-icons.css', array(), '1.0.0' );
}
add_action( 'admin_enqueue_scripts', 'superio_admin_enqueue_styles' );

/**
 * Enqueue styles.
 *
 * @since Superio 1.0
 */
function superio_enqueue_styles() {
	
	// load font
  	wp_enqueue_style( 'superio-theme-fonts', superio_get_fonts_url(), array(), null );

	//load font awesome
	wp_enqueue_style( 'all-awesome', get_template_directory_uri() . '/css/all-awesome.css', array(), '5.11.2' );

	//load font flaticon
	wp_enqueue_style( 'superio-flaticon', get_template_directory_uri() . '/css/flaticon.css', array(), '1.0.0' );

	// load font themify icon
	wp_enqueue_style( 'themify-icons', get_template_directory_uri() . '/css/themify-icons.css', array(), '1.0.0' );
	
	// load animate version 3.6.0
	wp_enqueue_style( 'animate', get_template_directory_uri() . '/css/animate.css', array(), '3.6.0' );

	// load bootstrap style
	if( is_rtl() ){
		wp_enqueue_style( 'bootstrap-rtl', get_template_directory_uri() . '/css/bootstrap-rtl.css', array(), '3.2.0' );
	} else {
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.css', array(), '3.2.0' );
	}
	// slick
	wp_enqueue_style( 'slick', get_template_directory_uri() . '/css/slick.css', array(), '1.8.0' );
	// magnific-popup
	wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/css/magnific-popup.css', array(), '1.1.0' );
	// perfect scrollbar
	wp_enqueue_style( 'perfect-scrollbar', get_template_directory_uri() . '/css/perfect-scrollbar.css', array(), '0.6.12' );
	// mobile menu
	wp_enqueue_style( 'sliding-menu', get_template_directory_uri() . '/css/sliding-menu.min.css', array(), '0.3.0' );
	// main style
	wp_enqueue_style(
        'superio-template',
        get_template_directory_uri() . '/css/template.css',
        array(),
        filemtime(get_template_directory() . '/css/template.css')
    );

	wp_enqueue_style( 'educfind-popup', get_template_directory_uri() . '/css/educfind-login-popup.css', array(), filemtime(get_template_directory() . '/css/educfind-login-popup.css') );

	wp_enqueue_style( 'superio-custom', get_template_directory_uri() . '/css/custom.css', array(), filemtime(get_template_directory() . '/css/custom.css') );

	if( is_rtl() ){
		wp_enqueue_style( 'update-rtl', get_template_directory_uri() . '/css/update-rtl.css', array(), '1.0.0' );
	} else {
		wp_enqueue_style( 'update', get_template_directory_uri() . '/css/update.css', array(), filemtime(get_template_directory() . '/css/update.css') );
	}
	$custom_style = superio_custom_styles();
	if ( !empty($custom_style) ) {
		wp_add_inline_style( 'superio-template', $custom_style );
	}
	wp_enqueue_style( 'superio-style', get_template_directory_uri() . '/style.css', array(), '1.0' );
}
add_action( 'wp_enqueue_scripts', 'superio_enqueue_styles', 100 );


function superio_login_enqueue_styles() {
	wp_enqueue_style( 'all-awesome', get_template_directory_uri() . '/css/all-awesome.css', array(), '5.11.2' );
	wp_enqueue_style( 'superio-login-style', get_template_directory_uri() . '/css/login-style.css', array(), '1.0' );
}
add_action( 'login_enqueue_scripts', 'superio_login_enqueue_styles', 10 );
/**
 * Enqueue scripts.
 *
 * @since Superio 1.0
 */
function superio_enqueue_scripts() {
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	// bootstrap
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ), '20150330', true );
	// slick
	wp_enqueue_script( 'slick', get_template_directory_uri() . '/js/slick.min.js', array( 'jquery' ), '1.8.0', true );
	// countdown
	wp_register_script( 'countdown', get_template_directory_uri() . '/js/countdown.js', array( 'jquery' ), '20150315', true );
	wp_localize_script( 'countdown', 'superio_countdown_opts', array(
		'days' => esc_html__('Days', 'superio'),
		'hours' => esc_html__('Hrs', 'superio'),
		'mins' => esc_html__('Mins', 'superio'),
		'secs' => esc_html__('Secs', 'superio'),
	));

	wp_enqueue_script('efj-job-detail', get_template_directory_uri() . '/js/efj-job-detail.js', [ 'jquery' ],true );
	
	// popup
	wp_enqueue_script( 'jquery-magnific-popup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );
	// unviel
	wp_enqueue_script( 'jquery-unveil', get_template_directory_uri() . '/js/jquery.unveil.js', array( 'jquery' ), '1.1.0', true );
	// perfect scrollbar
	wp_enqueue_script( 'perfect-scrollbar', get_template_directory_uri() . '/js/perfect-scrollbar.jquery.min.js', array( 'jquery' ), '0.6.12', true );
	
	if ( superio_get_config('keep_header') ) {
		wp_enqueue_script( 'jquery-waypoint', get_template_directory_uri() . '/js/jquery.waypoints.min.js', array( 'jquery' ), '4.0.1', true );
	
		wp_enqueue_script( 'jquery-sticky', get_template_directory_uri() . '/js/sticky.min.js', array( 'jquery', 'jquery-waypoint' ), '4.0.1', true );
	}

	// mobile menu script
	wp_enqueue_script( 'sliding-menu', get_template_directory_uri() . '/js/sliding-menu.js', array( 'jquery' ), '0.3.0', true );
	
	
	// main script
	wp_register_script( 'superio-functions', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), filemtime( get_template_directory() . '/js/functions.js' ), true );
	wp_localize_script( 'superio-functions', 'superio_ajax', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'previous' => esc_html__('Previous', 'superio'),
		'next' => esc_html__('Next', 'superio'),
		'menu_back_text' => esc_html__('Back', 'superio')
	));
	wp_enqueue_script( 'superio-functions' );
	
	wp_add_inline_script( 'superio-functions', "(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);" );
}
add_action( 'wp_enqueue_scripts', 'superio_enqueue_scripts', 1 );

/**
 * Add a `screen-reader-text` class to the search form's submit button.
 *
 * @since Superio 1.0
 *
 * @param string $html Search form HTML.
 * @return string Modified search form HTML.
 */
function superio_search_form_modify( $html ) {
	return str_replace( 'class="search-submit"', 'class="search-submit screen-reader-text"', $html );
}
add_filter( 'get_search_form', 'superio_search_form_modify' );

/**
 * Function get opt_name
 *
 */
function superio_get_opt_name() {
	return 'superio_theme_options';
}
add_filter( 'apus_framework_get_opt_name', 'superio_get_opt_name' );


function superio_register_demo_mode() {
	if ( defined('SUPERIO_DEMO_MODE') && SUPERIO_DEMO_MODE ) {
		return true;
	}
	return false;
}
add_filter( 'apus_framework_register_demo_mode', 'superio_register_demo_mode' );

function superio_get_demo_preset() {
	$preset = '';
    if ( defined('SUPERIO_DEMO_MODE') && SUPERIO_DEMO_MODE ) {
        if ( isset($_REQUEST['_preset']) && $_REQUEST['_preset'] ) {
            $presets = get_option( 'apus_framework_presets' );
            if ( is_array($presets) && isset($presets[$_REQUEST['_preset']]) ) {
                $preset = $_REQUEST['_preset'];
            }
        } else {
            $preset = get_option( 'apus_framework_preset_default' );
        }
    }
    return $preset;
}

function superio_get_config($name, $default = '') {
	global $superio_options;
    if ( isset($superio_options[$name]) ) {
        return $superio_options[$name];
    }
    return $default;
}

function superio_set_exporter_settings_option_keys($option_keys) {
	return array(
		'elementor_disable_color_schemes',
		'elementor_disable_typography_schemes',
		'elementor_allow_tracking',
		'elementor_cpt_support',
		'wp_job_board_pro_settings',
		'wp_job_board_pro__job__fields_data',
		'wp_job_board_pro__employer__fields_data',
		'wp_job_board_pro__candidate__fields_data',
	);
}
add_filter( 'apus_exporter_ocdi_settings_option_keys', 'superio_set_exporter_settings_option_keys' );

function superio_disable_one_click_import() {
	return false;
}
add_filter('apus_frammework_enable_one_click_import', 'superio_disable_one_click_import');

function superio_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Default', 'superio' ),
		'id'            => 'sidebar-default',
		'description'   => esc_html__( 'Add widgets here to appear in your Sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	
	register_sidebar( array(
		'name'          => esc_html__( 'Missions filter sidebar', 'superio' ),
		'id'            => 'jobs-filter-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Missions filter 2 sidebar', 'superio' ),
		'id'            => 'jobs-filter2-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Missions filter 3 sidebar', 'superio' ),
		'id'            => 'jobs-filter3-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="p-0 bg-transparent widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Missions filter top sidebar', 'superio' ),
		'id'            => 'jobs-filter-top-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Missions filter top 2 sidebar', 'superio' ),
		'id'            => 'jobs-filter-top2-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Missions filter top 3 sidebar', 'superio' ),
		'id'            => 'jobs-filter-top3-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Missions filter top 4 sidebar', 'superio' ),
		'id'            => 'jobs-filter-top4-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Missions filter top maps sidebar', 'superio' ),
		'id'            => 'jobs-filter-top-maps-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Missions filter Half Job Detail  sidebar', 'superio' ),
		'id'            => 'jobs-filter-topbar-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Job single sidebar', 'superio' ),
		'id'            => 'job-single-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Job single 2 sidebar', 'superio' ),
		'id'            => 'job-single2-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Job single 3 sidebar', 'superio' ),
		'id'            => 'job-single3-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Job single 4 sidebar', 'superio' ),
		'id'            => 'job-single4-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="single-4 widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Family filter sidebar', 'superio' ),
		'id'            => 'employers-filter-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Family filter top sidebar', 'superio' ),
		'id'            => 'employers-filter-top-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Family filter top maps sidebar', 'superio' ),
		'id'            => 'employers-filter-top-maps-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Employer single sidebar', 'superio' ),
		'id'            => 'employer-single-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Employer single sidebar 2', 'superio' ),
		'id'            => 'employer-single-sidebar2',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Eductors filter sidebar', 'superio' ),
		'id'            => 'candidates-filter-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Eductors filter 2 sidebar', 'superio' ),
		'id'            => 'candidates-filter2-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="p-0 bg-transparent widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Eductors filter top sidebar', 'superio' ),
		'id'            => 'candidates-filter-top-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Eductors filter top 2 sidebar', 'superio' ),
		'id'            => 'candidates-filter-top2-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Eductors filter top 3 sidebar', 'superio' ),
		'id'            => 'candidates-filter-top3-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Candidate single sidebar', 'superio' ),
		'id'            => 'candidate-single-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Candidate single 2 sidebar', 'superio' ),
		'id'            => 'candidate-single2-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Candidate single 3 sidebar', 'superio' ),
		'id'            => 'candidate-single3-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'User Profile sidebar', 'superio' ),
		'id'            => 'user-profile-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Blog sidebar', 'superio' ),
		'id'            => 'blog-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'superio' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name' 				=> esc_html__( 'Header Mobile Bottom', 'superio' ),
		'id' 				=> 'header-mobile-bottom',
		'description'   => esc_html__( 'Add widgets here to appear in your header mobile.', 'superio' ),
		'before_widget' => '<aside class="%2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="title"><span>',
		'after_title'   => '</span></h2>',
	));

}
add_action( 'widgets_init', 'superio_widgets_init' );

function superio_get_load_plugins() {

	$plugins[] = array(
		'name'                     => esc_html__( 'Apus Framework For Themes', 'superio' ),
        'slug'                     => 'apus-framework',
        'required'                 => true ,
        'source'				   => get_template_directory() . '/inc/plugins/apus-framework.zip'
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'Elementor Page Builder', 'superio' ),
	    'slug'                     => 'elementor',
	    'required'                 => true,
	);
	
	$plugins[] = array(
		'name'                     => esc_html__( 'Revolution Slider', 'superio' ),
        'slug'                     => 'revslider',
        'required'                 => true ,
        'source'				   => get_template_directory() . '/inc/plugins/revslider.zip'
	);
	
	$plugins[] = array(
		'name'                     => esc_html__( 'Cmb2', 'superio' ),
	    'slug'                     => 'cmb2',
	    'required'                 => true,
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'MailChimp for WordPress', 'superio' ),
	    'slug'                     => 'mailchimp-for-wp',
	    'required'                 =>  true
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'Contact Form 7', 'superio' ),
	    'slug'                     => 'contact-form-7',
	    'required'                 => true,
	);

	// woocommerce plugins
	$plugins[] = array(
		'name'                     => esc_html__( 'Woocommerce', 'superio' ),
	    'slug'                     => 'woocommerce',
	    'required'                 => true,
	);
	
	// Job plugins
	$plugins[] = array(
		'name'                     => esc_html__( 'WP Job Board Pro', 'superio' ),
        'slug'                     => 'wp-job-board-pro',
        'required'                 => true ,
        'source'				   => get_template_directory() . '/inc/plugins/wp-job-board-pro.zip'
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'WP Job Board Pro - WooCommerce Paid Listings', 'superio' ),
        'slug'                     => 'wp-job-board-pro-wc-paid-listings',
        'required'                 => true ,
        'source'				   => get_template_directory() . '/inc/plugins/wp-job-board-pro-wc-paid-listings.zip'
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'WP Private Message', 'superio' ),
        'slug'                     => 'wp-private-message',
        'required'                 => true ,
        'source'				   => get_template_directory() . '/inc/plugins/wp-private-message.zip'
	);
	
	$plugins[] = array(
        'name'                  => esc_html__( 'One Click Demo Import', 'superio' ),
        'slug'                  => 'one-click-demo-import',
        'required'              => false,
        'force_activation'      => false,
        'force_deactivation'    => false,
    );

	$plugins[] = array(
        'name'                  => esc_html__( 'Easy SVG Support', 'superio' ),
        'slug'                  => 'easy-svg',
        'required'              => false,
        'force_activation'      => false,
        'force_deactivation'    => false,
    );

	$config = array(
        'id'           => 'superio', 
        'default_path' => '', 
        'menu'         => 'tgmpa-install-plugins', 
        'has_notices'  => true, 
        'dismissable'  => true, 
        'dismiss_msg'  => '',  
        'is_automatic' => false, 
        'message'      => '', 
        'strings'      => array(
            'bulk_install' => esc_html__( 'Install Selected Plugins', 'superio' ),
        ),
    );

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'superio_get_load_plugins' );

get_template_part( '/inc/plugins/class-tgm-plugin-activation' );
get_template_part( '/inc/functions-helper' );
get_template_part( '/inc/functions-frontend' );

/**
 * Implement the Custom Header feature.
 *
 */
get_template_part( '/inc/custom-header' );
get_template_part( '/inc/classes/megamenu' );
get_template_part( '/inc/classes/mobilemenu' );

/**
 * Custom template tags for this theme.
 *
 */
require get_template_directory() . '/inc/template-tags.php';

if ( defined( 'APUS_FRAMEWORK_REDUX_ACTIVED' ) ) {
	get_template_part( '/inc/vendors/redux-framework/redux-config' );
	define( 'SUPERIO_REDUX_FRAMEWORK_ACTIVED', true );
}
if( superio_is_cmb2_activated() ) {
	get_template_part( '/inc/vendors/cmb2/page' );
	define( 'SUPERIO_CMB2_ACTIVED', true );
}
if( superio_is_woocommerce_activated() ) {
	get_template_part( '/inc/vendors/woocommerce/functions' );
	get_template_part( '/inc/vendors/woocommerce/functions-redux-configs' );
	define( 'SUPERIO_WOOCOMMERCE_ACTIVED', true );
}

if( superio_is_wp_job_board_pro_activated() ) {
	get_template_part( '/inc/vendors/wp-job-board-pro/functions-redux-configs' );
	get_template_part( '/inc/vendors/wp-job-board-pro/functions' );
	get_template_part( '/inc/vendors/wp-job-board-pro/functions-employer' );
	get_template_part( '/inc/vendors/wp-job-board-pro/functions-candidate' );

	get_template_part( '/inc/vendors/wp-job-board-pro/functions-job-display' );
	get_template_part( '/inc/vendors/wp-job-board-pro/functions-employer-display' );
	get_template_part( '/inc/vendors/wp-job-board-pro/functions-candidate-display' );
}

if ( superio_is_wp_job_board_pro_wc_paid_listings_activated() ) {
	get_template_part( '/inc/vendors/wp-job-board-pro-wc-paid-listings/functions' );
}

function superio_register_load_widget() {
	get_template_part( '/inc/widgets/custom_menu' );
	get_template_part( '/inc/widgets/recent_post' );
	get_template_part( '/inc/widgets/search' );
	get_template_part( '/inc/widgets/socials' );
	
	if ( did_action( 'elementor/loaded' ) ) {
		get_template_part( '/inc/widgets/elementor-template' );
	}
	
	if ( superio_is_wp_job_board_pro_activated() ) {
		get_template_part( '/inc/widgets/candidate-contact-form' );
		get_template_part( '/inc/widgets/candidate-info' );
		get_template_part( '/inc/widgets/candidate-cv' );
		get_template_part( '/inc/widgets/candidate-buttons' );
		get_template_part( '/inc/widgets/candidate-socials' );
		get_template_part( '/inc/widgets/candidate-tags' );
		get_template_part( '/inc/widgets/candidate-maps' );

		get_template_part( '/inc/widgets/employer-contact-form' );
		get_template_part( '/inc/widgets/employer-info' );
		get_template_part( '/inc/widgets/employer-maps' );
		
		get_template_part( '/inc/widgets/job-info' );
		get_template_part( '/inc/widgets/job-maps' );
		get_template_part( '/inc/widgets/job-tags' );
		get_template_part( '/inc/widgets/job-statistics' );
		get_template_part( '/inc/widgets/job-also-viewed' );
		get_template_part( '/inc/widgets/job-buttons' );
		get_template_part( '/inc/widgets/job-social-share' );
		get_template_part( '/inc/widgets/job-employer-info' );
		
		get_template_part( '/inc/widgets/job-contact-form' );
		
		get_template_part( '/inc/widgets/user-short-profile' );
	}
}
add_action( 'widgets_init', 'superio_register_load_widget' );

add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
add_filter( 'use_widgets_block_editor', '__return_false' );


if ( superio_is_wp_private_message() ) {
	get_template_part( '/inc/vendors/wp-private-message/functions' );
}

get_template_part( '/inc/vendors/elementor/functions' );
get_template_part( '/inc/vendors/one-click-demo-import/functions' );

function superio_register_post_types($post_types) {
	foreach ($post_types as $key => $post_type) {
		if ( $post_type == 'brand' || $post_type == 'testimonial' ) {
			unset($post_types[$key]);
		}
	}
	if ( !in_array('header', $post_types) ) {
		$post_types[] = 'header';
	}
	return $post_types;
}
add_filter( 'apus_framework_register_post_types', 'superio_register_post_types' );


/**
 * Customizer additions.
 *
 */
get_template_part( '/inc/customizer' );

/**
 * Custom Styles
 *
 */
get_template_part( '/inc/custom-styles' );

// Disable Gutenberg on the back end.
add_filter( 'use_block_editor_for_post', '__return_false' );

// Disable Gutenberg for widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );

function educfind_menu_customizations($items, $args) {

    $mission_count = wp_count_posts('job_listing')->publish;

    $unread_count = 0;
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $unread_query = new WP_Query(array(
            'post_type'      => 'private_message',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => array(
                'relation' => 'AND',
                array(
                    'key'     => '_recipient',
                    'value'   => $user_id,
                    'compare' => '=',
                ),
                array(
                    'key'     => '_read_' . $user_id,
                    'compare' => 'NOT EXISTS',
                ),
            ),
        ));
        $unread_count = $unread_query->found_posts;
        wp_reset_postdata();
    }

    $icon_map = array(
        'Welcome'   => 'fa fa-home',
        'Missions'  => 'flaticon-briefcase-1',
        'Educators' => 'flaticon-mortarboard',
    );

    foreach ($items as $item) {
        $title = trim(strip_tags($item->title));

        if ( isset($icon_map[$title]) && !empty($icon_map[$title]) ) {
            $item->title = '<i class="' . $icon_map[$title] . '"></i>' . $title;
        }

        if ( $title === 'Missions' ) {
            $item->title .= ' <span class="mission-count">' . $mission_count . '</span>';
        }

        // Match by URL instead of title — works across all menus/roles
        if ( $unread_count > 0 ) {
            $item_url = trim( $item->url, '/' );
            $messages_url = trim( home_url('/messages/'), '/' ); // ← adjust slug if needed

            if ( $item_url === $messages_url || str_ends_with($item_url, '/messages') ) {
                $item->title .= ' <span class="mission-count">' . $unread_count . '</span>';
            }
        }
    }

    return $items;
}
add_filter('wp_nav_menu_objects', 'educfind_menu_customizations', 10, 2);

add_action('wp_footer', 'educfind_hide_header_button_for_candidate');

function educfind_hide_header_button_for_candidate() {
    if ( !is_user_logged_in() || !class_exists('WP_Job_Board_Pro_User') ) {
        return;
    }
    // Check if logged user is candidate
    if ( WP_Job_Board_Pro_User::is_candidate() ) {
        ?>	
        <style>
            .user_button_header{
                display: none !important;
            }
        </style>
        <?php
    }
}
/* ══════════════════════════════════════════════════════════════
   EFJ HELPER FUNCTIONS
   Must be defined before any hook that calls them.
══════════════════════════════════════════════════════════════ */

/**
 * Normalise a string for loose comparison
 */
function efj_norm( $s ) {
    $s = mb_strtolower( trim( $s ), 'UTF-8' );
    $map = [
        'à'=>'a','â'=>'a','ä'=>'a','é'=>'e','è'=>'e','ê'=>'e','ë'=>'e',
        'î'=>'i','ï'=>'i','ô'=>'o','ö'=>'o','ù'=>'u','û'=>'u','ü'=>'u','ç'=>'c',
    ];
    $s = strtr( $s, $map );
    $s = preg_replace( '/[^a-z0-9\s]/', ' ', $s );
    return trim( preg_replace( '/\s+/', ' ', $s ) );
}

/**
 * Does needle loosely appear in haystack?
 */
function efj_contains( $haystack, $needle ) {
    return strpos( efj_norm( $haystack ), efj_norm( $needle ) ) !== false;
}

/**
 * Extract the first numeric value from a string e.g. "40–48 €/h" → 40
 */
function efj_first_num( $s ) {
    preg_match( '/[\d]+/', str_replace( ',', '.', $s ), $m );
    return isset( $m[0] ) ? (float) $m[0] : null;
}

/**
 * Build job data array from a job ID.
 * Used by both the template and the enqueue hook.
 */
function efj_get_job_data_for_scoring( $jid ) {
    $employer_id     = get_post_meta( $jid, '_job_employer_posted_by', true );
    $skills_required = get_post_meta( $jid, 'mandatory', true );
    $skills_desired  = get_post_meta( $jid, 'desired',   true );
    $slots           = get_post_meta( $jid, '_job_slots', true );

    return [
        'skills_required' => ! empty( $skills_required )
            ? array_map( 'trim', explode( ',', $skills_required ) ) : [],
        'skills_desired'  => ! empty( $skills_desired )
            ? array_map( 'trim', explode( ',', $skills_desired ) )  : [],
        'salary'          => get_post_meta( $jid, '_job_salary',        true ),
        'max_salary'      => get_post_meta( $jid, '_job_max_salary',    true ),
        'budget'          => $employer_id
            ? get_post_meta( $employer_id, 'budget-mensuel', true ) : '',
        'slots'           => is_array( $slots ) ? $slots : [],
        'gender'          => get_post_meta( $jid, '_job_gender',        true ),
        'qualification'   => get_post_meta( $jid, '_job_qualification', true ),
    ];
}

/**
 * Score one candidate against a job.
 * Returns array [ score, checks, name, cid ]
 *
 * Scoring breakdown (total 100 pts):
 *   Diplômes / qualifications   20 pts
 *   Spécialités match           20 pts
 *   Expérience                  15 pts
 *   Tarif dans budget           20 pts
 *   Disponibilités créneaux     15 pts
 *   Documents vérifiés           5 pts
 *   Langues bonus                5 pts
 */
function efj_score_candidate( $cid, $job ) {
    $score  = 0;
    $checks = [];

    /* ── Pull candidate meta ──────────────────────────────── */
    $c_qualification = get_post_meta( $cid, '_candidate_qualification', true );
    $c_qualification = is_array( $c_qualification ) ? $c_qualification : (array) maybe_unserialize( $c_qualification );
    $c_qualification = array_filter( $c_qualification );

    $c_education = get_post_meta( $cid, '_candidate_education', true );
    $c_education = is_array( $c_education ) ? $c_education : [];

    $c_salary   = get_post_meta( $cid, '_candidate_salary', true );
    $c_exp_time = get_post_meta( $cid, '_candidate_experience_time', true );

    $c_languages = get_post_meta( $cid, '_candidate_languages', true );
    $c_languages = is_array( $c_languages ) ? $c_languages : (array) maybe_unserialize( $c_languages );
    $c_languages = array_filter( $c_languages );

    $c_experience = get_post_meta( $cid, '_candidate_experience', true );
    $c_experience = is_array( $c_experience ) ? $c_experience : [];

    $days_meta = [
        'lundi'    => get_post_meta( $cid, 'lundi-hebdomadaires',    true ),
        'mardi'    => get_post_meta( $cid, 'mardi-hebdomadaires',    true ),
        'mercredi' => get_post_meta( $cid, 'mercredi-hebdomadaires', true ),
        'jeudi'    => get_post_meta( $cid, 'jeudi-hebdomadaires',    true ),
        'vendredi' => get_post_meta( $cid, 'vendredi-hebdomadaires', true ),
        'samedi'   => get_post_meta( $cid, 'samedi-hebdomadaires',   true ),
    ];

    $has_docs = false;
    foreach ( $c_education as $edu ) {
        if ( ! empty( $edu['_candidate__documents'] ) ) {
            $has_docs = true;
            break;
        }
    }

    /* ── 1. DIPLÔMES / QUALIFICATIONS (20 pts) ───────────── */
    $req_skills = $job['skills_required'];
    $req_count  = count( $req_skills );
    $req_hits   = 0;

    $edu_text = '';
    foreach ( $c_education as $edu ) {
        $edu_text .= ' ' . ( $edu['title'] ?? '' ) . ' ' . ( $edu['academy'] ?? '' );
    }
    $all_text = $edu_text . ' ' . implode( ' ', $c_qualification );

    foreach ( $req_skills as $skill ) {
        if ( efj_contains( $all_text, $skill ) ) {
            $req_hits++;
        }
    }

    $dip_score = $req_count > 0 ? round( ( $req_hits / $req_count ) * 20 ) : 10;
    $score    += $dip_score;

    if ( $req_hits === $req_count && $req_count > 0 ) {
        $checks[] = [ 'ok', '✅ ' . __( 'All required skills matched', 'efj' ) ];
    } elseif ( $req_hits > 0 ) {
        $checks[] = [ 'ok', '✅ ' . sprintf( __( '%1$d/%2$d required skills matched', 'efj' ), $req_hits, $req_count ) ];
    } else {
        $checks[] = [ 'warn', '⚠️ ' . __( 'No required skills detected', 'efj' ) ];
    }

    /* ── 2. SPÉCIALITÉS (20 pts) ─────────────────────────── */
    $des_skills    = $job['skills_desired'];
    $all_skills    = array_merge( $req_skills, $des_skills );
    $spec_hits     = 0;
    $matched_specs = [];

    foreach ( $c_qualification as $q ) {
        foreach ( $all_skills as $s ) {
            if ( efj_contains( $q, $s ) || efj_contains( $s, $q ) ) {
                $spec_hits++;
                $matched_specs[] = esc_html( $q );
                break;
            }
        }
    }

    $job_qual_match = false;
    if ( $job['qualification'] && ! empty( $c_education ) ) {
        foreach ( $c_education as $edu ) {
            if ( efj_contains( $edu['title'] ?? '', $job['qualification'] ) ) {
                $job_qual_match = true;
            }
        }
    }
    if ( $job_qual_match ) $spec_hits += 2;

    $spec_score = min( 20, round( ( $spec_hits / max( 1, count( $all_skills ) ) ) * 20 ) );
    $score     += $spec_score;

    if ( ! empty( $matched_specs ) ) {
        $checks[] = [ 'ok', '✅ ' . sprintf( __( 'Specialities: %s', 'efj' ), implode( ', ', array_unique( array_slice( $matched_specs, 0, 3 ) ) ) ) ];
    }

    /* ── 3. EXPÉRIENCE (15 pts) ──────────────────────────── */
    $exp_years = 0;
    if ( $c_exp_time ) {
        preg_match( '/(\d+)/', $c_exp_time, $em );
        $exp_years = isset( $em[1] ) ? (int) $em[1] : 0;
    }
    $exp_score = min( 15, max( 0, $exp_years ) * 3 );
    $score    += $exp_score;

    if ( $exp_years >= 5 ) {
        $checks[] = [ 'ok',  '✅ ' . sprintf( __( '%d+ years of experience', 'efj' ), $exp_years ) ];
    } elseif ( $exp_years >= 2 ) {
        $checks[] = [ 'ok',  '✅ ' . sprintf( __( '%d years of experience', 'efj' ), $exp_years ) ];
    } elseif ( $exp_years > 0 ) {
        $checks[] = [ 'warn', '⚠️ ' . sprintf( __( '%d year(s) of experience', 'efj' ), $exp_years ) ];
    } else {
        $checks[] = [ 'warn', '⚠️ ' . __( 'Experience not provided', 'efj' ) ];
    }

    /* ── 4. TARIF / BUDGET (20 pts) ──────────────────────── */
    $c_rate   = efj_first_num( $c_salary ?? '' );
    $j_min    = efj_first_num( $job['salary'] ?? '' );
    $j_max    = efj_first_num( $job['max_salary'] ?? '' );
    $j_budget = efj_first_num( $job['budget'] ?? '' );

    $rate_score = 0;

    if ( $c_rate !== null ) {
        if ( $j_min !== null && $j_max !== null ) {
            if ( $c_rate >= $j_min && $c_rate <= $j_max ) {
                $rate_score = 20;
                $checks[]   = [ 'ok',  '✅ ' . sprintf( __( 'Rate within budget (%s)', 'efj' ), $c_salary ) ];
            } elseif ( $c_rate <= $j_max * 1.1 ) {
                $rate_score = 12;
                $checks[]   = [ 'warn', '⚠️ ' . sprintf( __( 'Rate slightly over budget (%s)', 'efj' ), $c_salary ) ];
            } else {
                $rate_score = 4;
                $checks[]   = [ 'warn', '⚠️ ' . sprintf( __( 'Rate over budget (%s)', 'efj' ), $c_salary ) ];
            }
        } elseif ( $j_budget !== null ) {
            $estimated_rate = $j_budget / 16;
            if ( $c_rate <= $estimated_rate * 1.05 ) {
                $rate_score = 20;
                $checks[]   = [ 'ok',  '✅ ' . __( 'Rate compatible with monthly budget', 'efj' ) ];
            } elseif ( $c_rate <= $estimated_rate * 1.20 ) {
                $rate_score = 10;
                $checks[]   = [ 'warn', '⚠️ ' . __( 'Rate slightly above budget', 'efj' ) ];
            } else {
                $rate_score = 4;
                $checks[]   = [ 'warn', '⚠️ ' . __( 'Rate exceeds monthly budget', 'efj' ) ];
            }
        } else {
            $rate_score = 10;
        }
    } else {
        $rate_score = 5;
        $checks[]   = [ 'warn', '⚠️ ' . __( 'Rate not provided', 'efj' ) ];
    }
    $score += $rate_score;

    /* ── 5. DISPONIBILITÉS vs CRÉNEAUX (15 pts) ──────────── */
    $slot_score = 0;
    $slot_hits  = 0;
    $slot_total = count( $job['slots'] );
    $avail_days = [];

    foreach ( $days_meta as $day_key => $day_val ) {
        if ( ! $day_val ) { $avail_days[ $day_key ] = false; continue; }
        $v = efj_norm( $day_val );
        $avail_days[ $day_key ] = ! (
            strpos( $v, 'non' )   !== false ||
            strpos( $v, 'pris' )  !== false ||
            strpos( $v, 'occup' ) !== false ||
            strpos( $v, 'bcba' )  !== false ||
            strpos( $v, 'admin' ) !== false
        );
    }

    if ( $slot_total > 0 ) {
        foreach ( $job['slots'] as $slot ) {
            $day_n = efj_norm( $slot['title'] ?? '' );
            foreach ( $avail_days as $dk => $dv ) {
                if ( efj_norm( $dk ) === $day_n && $dv ) {
                    $slot_hits++;
                    break;
                }
            }
        }
        $slot_score = round( ( $slot_hits / $slot_total ) * 15 );
    } else {
        $free_days  = count( array_filter( $avail_days ) );
        $slot_score = $free_days >= 3 ? 15 : ( $free_days >= 1 ? 8 : 0 );
    }
    $score += $slot_score;

    if ( $slot_hits === $slot_total && $slot_total > 0 ) {
        $checks[] = [ 'ok', '✅ ' . __( 'All time slots available', 'efj' ) ];
    } elseif ( $slot_hits > 0 ) {
        $checks[] = [ 'ok', '✅ ' . sprintf( __( '%1$d/%2$d slots OK', 'efj' ), $slot_hits, $slot_total ) ];
    } else {
        $free_list = [];
        foreach ( $avail_days as $dk => $dv ) { if ( $dv ) $free_list[] = ucfirst( $dk ); }
        if ( ! empty( $free_list ) ) {
            $checks[] = [ 'ok',  '✅ ' . sprintf( __( 'Available: %s', 'efj' ), implode( ' + ', array_slice( $free_list, 0, 3 ) ) ) ];
        } else {
            $checks[] = [ 'warn', '⚠️ ' . __( 'Availability to be confirmed', 'efj' ) ];
        }
    }

    /* ── 6. DOCUMENTS VÉRIFIÉS (5 pts) ───────────────────── */
    if ( $has_docs ) {
        $score   += 5;
        $checks[] = [ 'ok',  '✅ ' . __( 'Documents verified', 'efj' ) ];
    } else {
        $checks[] = [ 'warn', '⚠️ ' . __( 'Documents not yet verified', 'efj' ) ];
    }

    /* ── 7. LANGUES BONUS (5 pts) ────────────────────────── */
    if ( count( $c_languages ) >= 2 ) {
        $score   += 5;
        $checks[] = [ 'ok', '✅ ' . implode( ' + ', $c_languages ) ];
    } elseif ( count( $c_languages ) === 1 ) {
        $score   += 2;
        $checks[] = [ 'ok', '✅ ' . $c_languages[0] ];
    }

    /* ── Gender penalty ───────────────────────────────────── */
    $c_gender = get_post_meta( $cid, '_candidate_gender', true );
    if ( $job['gender'] && $job['gender'] !== 'Both' && $c_gender && $c_gender !== $job['gender'] ) {
        $score   -= 5;
        $checks[] = [ 'warn', '⚠️ ' . sprintf( __( 'Gender: %1$s (requested: %2$s)', 'efj' ), $c_gender, $job['gender'] ) ];
    }

    $score = max( 0, min( 100, $score ) );

    return [
        'score'  => $score,
        'checks' => $checks,
        'name'   => get_the_title( $cid ),
        'cid'    => $cid,
    ];
}

/**
 * Get best matching candidate with 12-hour transient cache.
 */
function efj_get_best_match( $job_data, $jid ) {
    $cache_key = 'efj_best_' . $jid;
    $cached    = get_transient( $cache_key );

    if ( $cached !== false ) {
        return $cached;
    }

    $candidates_query = new WP_Query( [
        'post_type'      => 'candidate',
        'post_status'    => 'publish',
        'posts_per_page' => 80,
        'fields'         => 'ids',
        'meta_query'     => [ [
            'key'     => '_candidate_show_profile',
            'value'   => 'hide',
            'compare' => '!=',
        ] ],
    ] );

    $all_scores = [];
    if ( $candidates_query->have_posts() ) {
        foreach ( $candidates_query->posts as $cid ) {
            $all_scores[] = efj_score_candidate( $cid, $job_data );
        }
    }
    wp_reset_postdata();

    usort( $all_scores, fn( $a, $b ) => $b['score'] - $a['score'] );
    $best = ! empty( $all_scores ) ? $all_scores[0] : null;

    set_transient( $cache_key, $best, 12 * HOUR_IN_SECONDS );

    return $best;
}

/* ══════════════════════════════════════════════════════════════
   CLEAR CACHE WHEN CANDIDATE IS SAVED (runs once, no duplicate)
══════════════════════════════════════════════════════════════ */
add_action( 'save_post_candidate', function( $post_id ) {
    global $wpdb;
    $wpdb->query(
        "DELETE FROM {$wpdb->options}
         WHERE option_name LIKE '_transient_efj_best_%'
         OR option_name LIKE '_transient_timeout_efj_best_%'"
    );
} );

/* ══════════════════════════════════════════════════════════════
   ENQUEUE JS + PASS DATA TO JS (runs once, no duplicate)
══════════════════════════════════════════════════════════════ */
add_action( 'wp_enqueue_scripts', function() {
 
    if ( ! is_singular( 'job_listing' ) ) return;
 
    wp_enqueue_script(
        'efj-job-detail',
        get_template_directory_uri() . '/js/efj-job-detail.js',
        [ 'jquery' ],
        '1.0.1',
        true
    );
 
    global $post;
    if ( ! $post ) return;
 
    $jid      = $post->ID;
    $job_data = efj_get_job_data_for_scoring( $jid );
    $best     = efj_get_best_match( $job_data, $jid );
 
    // Match score (already existed)
    wp_localize_script( 'efj-job-detail', 'EFJ_Match', [
        'pct' => $best ? (int) $best['score'] : 0,
    ] );
 
}, 20 );

/* ══════════════════════════════════════════════════════════════
   1. CANDIDATE APPLIES → NOTIFY THE FAMILY (EMPLOYER)
══════════════════════════════════════════════════════════════ */
add_action( 'wp_ajax_efj_apply_job', 'efj_notify_family_on_apply', 5 );
 
function efj_notify_family_on_apply() {
 
    $nonce  = isset( $_POST['nonce'] )  ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
    $job_id = isset( $_POST['job_id'] ) ? absint( $_POST['job_id'] ) : 0;
 
    if ( ! wp_verify_nonce( $nonce, 'efj_apply_nonce' ) || ! $job_id ) {
        return;
    }
 
    $current_uid = get_current_user_id();
    if ( ! $current_uid ) return;
 
    $candidate_id = WP_Job_Board_Pro_User::get_candidate_by_user_id( $current_uid );
    if ( ! $candidate_id ) return;
 
    $employer_post_id = get_post_meta( $job_id, '_job_employer_posted_by', true );
    if ( ! $employer_post_id ) return;
 
    // type 'internal_apply' renders:
    // "A new application is submitted on your job <Job> by <Candidate>."
    WP_Job_Board_Pro_User_Notification::add_notification( array(
        'post_type'    => 'employer',
        'user_post_id' => $employer_post_id,
        'unique_id'    => uniqid( 'efj_apply_', true ),
        'viewed'       => 0,
        'time'         => current_time( 'timestamp' ),
        'type'         => 'internal_apply',
        'job_id'       => $job_id,
        'candidate_id' => $candidate_id,
    ) );
}
 
 
/* ══════════════════════════════════════════════════════════════
   2. FAMILY SUBMITS MISSION → NOTIFY ALL CANDIDATES
══════════════════════════════════════════════════════════════ */
add_action( 'transition_post_status', 'efj_notify_candidates_on_mission_submit', 10, 3 );
 
function efj_notify_candidates_on_mission_submit( $new_status, $old_status, $post ) {
 
    if ( $post->post_type !== 'job_listing' ) return;
    if ( $new_status     !== 'publish' )      return;
    if ( $old_status     === 'publish' )      return; // skip re-saves
 
    $job_id      = $post->ID;
    $employer_id = get_post_meta( $job_id, '_job_employer_posted_by', true );
 
    $candidate_ids = get_posts( array(
        'post_type'      => 'candidate',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => array( array(
            'key'     => '_candidate_show_profile',
            'value'   => 'hide',
            'compare' => '!=',
        ) ),
    ) );
 
    foreach ( $candidate_ids as $cid ) {
        // type 'invite_candidate_apply' renders:
        // "You are invited to apply job <Job>."
        WP_Job_Board_Pro_User_Notification::add_notification( array(
            'post_type'    => 'candidate',
            'user_post_id' => $cid,
            'unique_id'    => uniqid( 'efj_mission_' . $cid . '_', true ),
            'viewed'       => 0,
            'time'         => current_time( 'timestamp' ),
            'type'         => 'invite_candidate_apply',
            'job_ids'      => array( $job_id ),
            'employer_id'  => $employer_id,
            'candidate_id' => $cid,
        ) );
    }
}

add_action( 'wp_enqueue_scripts', 'superio_address_autocomplete' );
function superio_address_autocomplete() {
    $load = is_page( 'post-a-job' )
         || is_page( 'submit-mission' )
         || is_page( 'profile' )        // candidate profile page slug
         || is_page( 'employer' );      // family/employer profile page slug

    if ( ! $load ) {
        return;
    }

    wp_enqueue_script(
        'address-autocomplete',
        get_template_directory_uri() . '/js/address-autocomplete.js',
        [ 'jquery' ],
        '1.0.1',
        true
    );
}

/**
 * ============================================================
 * ADD THESE SNIPPETS TO YOUR functions.php
 * File: wp-content/themes/superio/functions.php
 * ============================================================
 */
// ============================================================
// 1. Register custom 'completed' post status
// ============================================================
add_action( 'init', 'superio_register_completed_status' );
function superio_register_completed_status() {
    register_post_status( 'completed', array(
        'label'                     => _x( 'Completed', 'post status', 'superio' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop(
            'Completed <span class="count">(%s)</span>',
            'Completed <span class="count">(%s)</span>'
        ),
    ) );
}

// ============================================================
// 2. Prevent WP Job Board Pro from overriding 'completed' status
// ============================================================
function superio_protect_completed_status( $data, $postarr ) {
    if ( isset( $postarr['ID'] ) ) {
        $current_status = get_post_field( 'post_status', $postarr['ID'] );
        if ( $current_status === 'completed' ) {
            $doing_our_ajax = (
                defined('DOING_AJAX') && DOING_AJAX &&
                isset($_POST['action']) &&
                $_POST['action'] === 'superio_mark_job_completed'
            );
            if ( ! $doing_our_ajax ) {
                $data['post_status'] = 'completed';
            }
        }
    }
    return $data;
}
add_filter( 'wp_insert_post_data', 'superio_protect_completed_status', 99, 2 );

// ============================================================
// 3. AJAX — mark job as completed
//    Also saves the employer↔candidate relationship meta so
//    the review box shows on the candidate profile page.
// ============================================================
add_action( 'wp_ajax_superio_mark_job_completed', 'superio_ajax_mark_job_completed' );
function superio_ajax_mark_job_completed() {
    global $wpdb;

    check_ajax_referer( 'superio-mark-completed-nonce', 'nonce' );

    $job_id = isset( $_POST['job_id'] ) ? absint( $_POST['job_id'] ) : 0;

    if ( ! $job_id ) {
        wp_send_json_error( array( 'message' => 'Invalid job ID.' ) );
    }
    if ( (int) get_post_field( 'post_author', $job_id ) !== (int) get_current_user_id() ) {
        wp_send_json_error( array( 'message' => 'Permission denied.' ) );
    }

    // ── Direct DB update (bypasses plugin hooks) ──────────────
    $updated = $wpdb->update(
        $wpdb->posts,
        array( 'post_status' => 'completed' ),
        array( 'ID' => $job_id ),
        array( '%s' ),
        array( '%d' )
    );

    if ( $updated === false ) {
        wp_send_json_error( array( 'message' => 'DB update failed.' ) );
    }

    clean_post_cache( $job_id );
    wp_cache_flush();

    wp_send_json_success( array(
        'message' => __( 'Mission marked as Completed.', 'superio' ),
        'label'   => __( 'Completed', 'superio' ),
    ) );
}

// ============================================================
// 4. Enqueue mission-completed.js
// ============================================================
add_action( 'wp_enqueue_scripts', 'superio_enqueue_mission_completed_js' );
function superio_enqueue_mission_completed_js() {
    if ( ! is_user_logged_in() ) return;

    wp_enqueue_script(
        'superio-mission-completed',
        get_template_directory_uri() . '/js/mission-completed.js',
        array( 'jquery' ),
        '1.3.0',
        true
    );

    wp_localize_script( 'superio-mission-completed', 'superioCompleted', array(
        'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
        'confirmComplete' => __( 'Marquer cette mission comme Complétée ?', 'superio' ),
        'error'           => __( 'Une erreur est survenue.', 'superio' ),
        'ajaxError'       => __( 'Erreur AJAX. Veuillez réessayer.', 'superio' ),
    ) );
}
/**
 * EFJ-only gate: can this logged-in educator review this family?
 *
 * Called alongside the existing superio_check_employer_candidate_review().
 * Checks:
 *  1. ?efj_review_family=1 is present in the URL
 *  2. Viewer is a logged-in candidate
 *  3. They have at least one 'completed' job with this employer
 *  4. They have NOT already reviewed this employer
 *
 * Completely separate from the plugin's own function — touches nothing else.
 */
function efj_candidate_can_review_family( $employer_id ) {

    if ( ! isset( $_GET['efj_review_family'] ) ) return false;
    if ( ! is_user_logged_in() )                 return false;
    if ( ! class_exists( 'WP_Job_Board_Pro_User' ) ) return false;
    if ( ! WP_Job_Board_Pro_User::is_candidate() )   return false;

    $current_uid  = get_current_user_id();
    $employer_id  = (int) $employer_id;

    $candidate_id = WP_Job_Board_Pro_User::get_candidate_by_user_id( $current_uid );
    if ( ! $candidate_id ) return false;

    // Already reviewed? → block
    $already = get_comments( array(
        'post_id' => $employer_id,
        'user_id' => $current_uid,
        'count'   => true,
        'status'  => 'all',
    ) );
    if ( $already > 0 ) return false;

    // ── THE ONLY CHECK THAT MATTERS ──────────────────────────
    // superio_ajax_mark_job_completed() writes this meta when the
    // employer clicks "Mark as Completed". If it exists → allow.
    $store_key = '_efj_completed_employer_' . $employer_id;
    $meta      = get_post_meta( $candidate_id, $store_key );
    if ( ! empty( $meta ) ) return true;

    return false;
}
add_action( 'wp_footer', function() {
    ?>
    <script>
    (function($) {
        $(document).ready(function() {
            $('#hero-primary-btn').on('click', function(e) {
                e.preventDefault();
                var $popup = $('#apus_login_forgot_form');
                if (!$popup.length) return;

                // Detach from header and append to body if not already there
                if (!$popup.parent().is('body')) {
                    $popup.appendTo('body');
                }

                $.magnificPopup.open({
                    items: { src: '#apus_login_forgot_form' },
                    type: 'inline',
                    mainClass: 'apus-mfp-zoom-in login-popup',
                    removalDelay: 400
                });
            });
        });
    })(jQuery);
    </script>
    <?php
}, 99 );



