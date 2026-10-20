<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Superio_Elementor_User_Info2 extends Elementor\Widget_Base {

    public function get_name()       { return 'apus_element_user_info2'; }
    public function get_title()      { return esc_html__( 'Apus Header User Info 2', 'superio' ); }
    public function get_categories() { return [ 'superio-header-elements' ]; }

    protected function register_controls() {

        /* ── Content ──────────────────────────────────────────────────── */
        $this->start_controls_section( 'content_section', [
            'label' => esc_html__( 'Content', 'superio' ),
            'tab'   => Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        $this->add_control( 'layout_type', [
            'label'   => esc_html__( 'Layout Type', 'superio' ),
            'type'    => Elementor\Controls_Manager::SELECT,
            'options' => [ 'popup' => esc_html__( 'Popup', 'superio' ), 'page' => esc_html__( 'Page', 'superio' ) ],
            'default' => 'popup',
        ]);
        $this->add_control( 'login_register_text', [
            'label'     => esc_html__( 'Login Register Text', 'superio' ),
            'type'      => Elementor\Controls_Manager::TEXT,
            'default'   => 'Login / Register',
            'condition' => [ 'layout_type' => 'page' ],
        ]);
        $this->add_control( 'login_text', [
            'label'     => esc_html__( 'Login Text', 'superio' ),
            'type'      => Elementor\Controls_Manager::TEXT,
            'default'   => 'Login',
            'condition' => [ 'layout_type' => 'popup' ],
        ]);
        $this->add_control( 'login_icon', [
            'label'     => esc_html__( 'Login Button Icon', 'superio' ),
            'type'      => Elementor\Controls_Manager::ICONS,
            'default'   => [ 'value' => '', 'library' => 'solid' ],
            'condition' => [ 'layout_type' => 'popup' ],
        ]);
        $this->add_control( 'login_icon_position', [
            'label'     => esc_html__( 'Login Icon Position', 'superio' ),
            'type'      => Elementor\Controls_Manager::SELECT,
            'options'   => [ 'before' => esc_html__( 'Before Text', 'superio' ), 'after' => esc_html__( 'After Text', 'superio' ) ],
            'default'   => 'before',
            'condition' => [ 'layout_type' => 'popup', 'login_icon[value]!' => '' ],
        ]);
        $this->add_control( 'register_text', [
            'label'     => esc_html__( 'Register Text', 'superio' ),
            'type'      => Elementor\Controls_Manager::TEXT,
            'default'   => 'Register',
            'condition' => [ 'layout_type' => 'popup' ],
            'separator' => 'before',
        ]);
        $this->add_control( 'register_icon', [
            'label'     => esc_html__( 'Register Button Icon', 'superio' ),
            'type'      => Elementor\Controls_Manager::ICONS,
            'default'   => [ 'value' => '', 'library' => 'solid' ],
            'condition' => [ 'layout_type' => 'popup' ],
        ]);
        $this->add_control( 'register_icon_position', [
            'label'     => esc_html__( 'Register Icon Position', 'superio' ),
            'type'      => Elementor\Controls_Manager::SELECT,
            'options'   => [ 'before' => esc_html__( 'Before Text', 'superio' ), 'after' => esc_html__( 'After Text', 'superio' ) ],
            'default'   => 'before',
            'condition' => [ 'layout_type' => 'popup', 'register_icon[value]!' => '' ],
        ]);
        $this->add_control( 'login_title', [
            'label'     => esc_html__( 'Login Popup Title', 'superio' ),
            'type'      => Elementor\Controls_Manager::TEXT,
            'default'   => 'Login to superio',
            'condition' => [ 'layout_type' => 'popup' ],
            'separator' => 'before',
        ]);
        $this->add_control( 'register_title', [
            'label'     => esc_html__( 'Register Popup Title', 'superio' ),
            'type'      => Elementor\Controls_Manager::TEXT,
            'default'   => 'Create a free superio account',
            'condition' => [ 'layout_type' => 'popup' ],
        ]);
        $this->add_control( 'el_class', [
            'label'     => esc_html__( 'Extra class name', 'superio' ),
            'type'      => Elementor\Controls_Manager::TEXT,
            'separator' => 'before',
        ]);
        $this->add_responsive_control( 'align', [
            'label'     => esc_html__( 'Alignment', 'superio' ),
            'type'      => Elementor\Controls_Manager::CHOOSE,
            'options'   => [
                'left'   => [ 'title' => esc_html__( 'Left',   'superio' ), 'icon' => 'fa fa-align-left' ],
                'center' => [ 'title' => esc_html__( 'Center', 'superio' ), 'icon' => 'fa fa-align-center' ],
                'right'  => [ 'title' => esc_html__( 'Right',  'superio' ), 'icon' => 'fa fa-align-right' ],
            ],
            'selectors' => [ '{{WRAPPER}}' => 'text-align: {{VALUE}};' ],
        ]);
        $this->end_controls_section();

        /* ── Pricing Plans (Login Popup) ──────────────────────────────── */
        $this->start_controls_section( 'section_pricing', [
            'label'     => esc_html__( 'Pricing Plans (Login Popup)', 'superio' ),
            'tab'       => Elementor\Controls_Manager::TAB_CONTENT,
            'condition' => [ 'layout_type' => 'popup' ],
        ]);
        $this->add_control( 'pricing_notice', [
            'type' => Elementor\Controls_Manager::RAW_HTML,
            'raw'  => '<div style="background:#f0f5ff;border-left:3px solid #2563EB;padding:10px 12px;border-radius:6px;font-size:12px;line-height:1.6;color:#1e3a6e;">
                Enter one Product ID per audience type.<br>
                Plans are pulled live from <code>WCSS_Product_Type</code>.<br>
                Leave all empty to show static fallback plans.
            </div>',
        ]);

        /* Audience 1 */
        $this->add_control( 'h1', [ 'label' => '— Audience 1', 'type' => Elementor\Controls_Manager::HEADING, 'separator' => 'before' ]);
        $this->add_control( 'popup_product_1_id',    [ 'label' => esc_html__( 'Product ID', 'superio' ),       'type' => Elementor\Controls_Manager::NUMBER, 'default' => '', 'min' => 0 ]);
        $this->add_control( 'popup_product_1_label', [ 'label' => esc_html__( 'Tab Label', 'superio' ),        'type' => Elementor\Controls_Manager::TEXT,   'default' => 'Familles' ]);

        /* Audience 2 */
        $this->add_control( 'h2', [ 'label' => '— Audience 2', 'type' => Elementor\Controls_Manager::HEADING, 'separator' => 'before' ]);
        $this->add_control( 'popup_product_2_id',    [ 'label' => esc_html__( 'Product ID', 'superio' ),       'type' => Elementor\Controls_Manager::NUMBER, 'default' => '', 'min' => 0 ]);
        $this->add_control( 'popup_product_2_label', [ 'label' => esc_html__( 'Tab Label', 'superio' ),        'type' => Elementor\Controls_Manager::TEXT,   'default' => 'Éducateurs' ]);

        /* Audience 3 */
        $this->add_control( 'h3', [ 'label' => '— Audience 3', 'type' => Elementor\Controls_Manager::HEADING, 'separator' => 'before' ]);
        $this->add_control( 'popup_product_3_id',    [ 'label' => esc_html__( 'Product ID', 'superio' ),       'type' => Elementor\Controls_Manager::NUMBER, 'default' => '', 'min' => 0 ]);
        $this->add_control( 'popup_product_3_label', [ 'label' => esc_html__( 'Tab Label', 'superio' ),        'type' => Elementor\Controls_Manager::TEXT,   'default' => 'Structures' ]);

        $this->end_controls_section();

        /* ── Style sections (unchanged) ───────────────────────────────── */
        $this->start_controls_section( 'section_title_style', [ 'label' => esc_html__( 'Name Account', 'superio' ), 'tab' => Elementor\Controls_Manager::TAB_STYLE ]);
        $this->add_control( 'icon_color', [ 'label' => esc_html__( 'Color', 'superio' ), 'type' => Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .drop-dow' => 'color: {{VALUE}};' ] ]);
        $this->end_controls_section();

        $this->start_controls_section( 'section_icon_style', [ 'label' => esc_html__( 'Button Icons', 'superio' ), 'tab' => Elementor\Controls_Manager::TAB_STYLE, 'condition' => [ 'layout_type' => 'popup' ] ]);
        $this->add_control( 'btn_icon_size', [ 'label' => esc_html__( 'Icon Size', 'superio' ), 'type' => Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px', 'em' ], 'range' => [ 'px' => [ 'min' => 10, 'max' => 40 ] ], 'default' => [ 'unit' => 'px', 'size' => 14 ], 'selectors' => [ '{{WRAPPER}} .btn-login .efj-btn-icon, {{WRAPPER}} .btn-register .efj-btn-icon' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ] ]);
        $this->add_control( 'btn_icon_gap', [ 'label' => esc_html__( 'Gap between icon and text', 'superio' ), 'type' => Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px' ], 'range' => [ 'px' => [ 'min' => 0, 'max' => 20 ] ], 'default' => [ 'unit' => 'px', 'size' => 6 ], 'selectors' => [ '{{WRAPPER}} .btn-login, {{WRAPPER}} .btn-register' => 'gap: {{SIZE}}{{UNIT}};' ] ]);
        $this->end_controls_section();

        $this->start_controls_section( 'section_button_style', [ 'label' => esc_html__( 'Button', 'superio' ), 'tab' => Elementor\Controls_Manager::TAB_STYLE ]);
        $this->add_responsive_control( 'button_padding', [ 'label' => esc_html__( 'Padding Button', 'superio' ), 'type' => Elementor\Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em', '%' ], 'selectors' => [ '{{WRAPPER}} .btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ]);
        $this->add_control( 'border_radius', [ 'label' => esc_html__( 'Border Radius', 'superio' ), 'type' => Elementor\Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%' ], 'selectors' => [ '{{WRAPPER}} .btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ]);
        $this->add_control( 'button_color',              [ 'label' => esc_html__( 'Color Button',                   'superio' ), 'type' => Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .btn-login' => 'color: {{VALUE}};', '{{WRAPPER}} .btn-login a' => 'color: {{VALUE}};' ] ]);
        $this->add_control( 'button_bg_color',           [ 'label' => esc_html__( 'Background Color Button',        'superio' ), 'type' => Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .btn-login' => 'background-color: {{VALUE}};' ] ]);
        $this->add_control( 'button_border_color',       [ 'label' => esc_html__( 'Border Color Button',            'superio' ), 'type' => Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .btn-login' => 'border-color: {{VALUE}};' ] ]);
        $this->add_control( 'button_hover_color',        [ 'label' => esc_html__( 'Hover Color Button',             'superio' ), 'type' => Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .btn-login:hover' => 'color: {{VALUE}};', '{{WRAPPER}} .btn-login:focus' => 'color: {{VALUE}};', '{{WRAPPER}} .btn-login:hover a' => 'color: {{VALUE}};' ] ]);
        $this->add_control( 'button_hover_bg_color',     [ 'label' => esc_html__( 'Hover Background Color Button',  'superio' ), 'type' => Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .btn-login:hover' => 'background-color: {{VALUE}};', '{{WRAPPER}} .btn-login:focus' => 'background-color: {{VALUE}};' ] ]);
        $this->add_control( 'button_hover_border_color', [ 'label' => esc_html__( 'Hover Border Color Button',      'superio' ), 'type' => Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .btn-login:hover' => 'border-color: {{VALUE}};', '{{WRAPPER}} .btn-login:focus' => 'border-color: {{VALUE}};' ] ]);
        $this->add_control( 'register_button_color',              [ 'label' => esc_html__( 'Register: Color',              'superio' ), 'type' => Elementor\Controls_Manager::COLOR, 'separator' => 'before', 'selectors' => [ '{{WRAPPER}} .btn-register' => 'color: {{VALUE}};', '{{WRAPPER}} .btn-register a' => 'color: {{VALUE}};' ] ]);
        $this->add_control( 'register_button_bg_color',           [ 'label' => esc_html__( 'Register: Background Color',   'superio' ), 'type' => Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .btn-register' => 'background-color: {{VALUE}};' ] ]);
        $this->add_control( 'register_button_border_color',       [ 'label' => esc_html__( 'Register: Border Color',       'superio' ), 'type' => Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .btn-register' => 'border-color: {{VALUE}};' ] ]);
        $this->add_control( 'register_button_hover_color',        [ 'label' => esc_html__( 'Register: Hover Color',        'superio' ), 'type' => Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .btn-register:hover' => 'color: {{VALUE}};', '{{WRAPPER}} .btn-register:focus' => 'color: {{VALUE}};', '{{WRAPPER}} .btn-register:hover a' => 'color: {{VALUE}};' ] ]);
        $this->add_control( 'register_button_hover_bg_color',     [ 'label' => esc_html__( 'Register: Hover Background',   'superio' ), 'type' => Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .btn-register:hover' => 'background-color: {{VALUE}};', '{{WRAPPER}} .btn-register:focus' => 'background-color: {{VALUE}};' ] ]);
        $this->add_control( 'register_button_hover_border_color', [ 'label' => esc_html__( 'Register: Hover Border Color', 'superio' ), 'type' => Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .btn-register:hover' => 'border-color: {{VALUE}};', '{{WRAPPER}} .btn-register:focus' => 'border-color: {{VALUE}};' ] ]);
        $this->end_controls_section();
    }

    /* ================================================================== */
    /*  RENDER                                                              */
    /* ================================================================== */

    protected function render() {
        $settings = $this->get_settings();
        extract( $settings );

        /* ── Build popup_products array from the 3 controls ── */
        $popup_products = [];
        foreach ( [ 1, 2, 3 ] as $n ) {
            $pid   = absint( $settings[ "popup_product_{$n}_id" ] ?? 0 );
            $label = sanitize_text_field( $settings[ "popup_product_{$n}_label" ] ?? '' );
            if ( $pid && $label ) {
                $popup_products[] = [ 'id' => $pid, 'label' => $label ];
            }
        }

        /* Inject via filter so login-form.php can read it */
        if ( ! empty( $popup_products ) ) {
            add_filter( 'educfind_popup_products', function() use ( $popup_products ) {
                return $popup_products;
            }, 20 );
        }

        /* ── Icon render helper ── */
        $render_icon = function( $icon_setting, $extra_class = '' ) {
            if ( empty( $icon_setting['value'] ) ) return '';
            ob_start();
            Elementor\Icons_Manager::render_icon( $icon_setting, [ 'aria-hidden' => 'true', 'class' => 'efj-btn-icon ' . esc_attr( $extra_class ) ]);
            return ob_get_clean();
        };

        $login_icon_html    = $render_icon( $settings['login_icon']    ?? [] );
        $register_icon_html = $render_icon( $settings['register_icon'] ?? [] );
        $login_icon_pos     = $settings['login_icon_position']    ?? 'before';
        $register_icon_pos  = $settings['register_icon_position'] ?? 'before';

        $login_btn_inner = '';
        if ( $login_icon_html && $login_icon_pos === 'before' ) $login_btn_inner .= $login_icon_html;
        $login_btn_inner .= '<span>' . esc_html( trim( $login_text ) ) . '</span>';
        if ( $login_icon_html && $login_icon_pos === 'after' )  $login_btn_inner .= $login_icon_html;

        $register_btn_inner = '';
        if ( $register_icon_html && $register_icon_pos === 'before' ) $register_btn_inner .= $register_icon_html;
        $register_btn_inner .= '<span>' . esc_html( trim( $register_text ) ) . '</span>';
        if ( $register_icon_html && $register_icon_pos === 'after' )  $register_btn_inner .= $register_icon_html;

        /* ====================================================== */

        if ( is_user_logged_in() ) {
            $user_id   = get_current_user_id();
            $userdata  = get_userdata( $user_id );
            $user_name = $userdata->display_name;
            $menu_nav  = '';
            $avatar    = '';

            if ( WP_Job_Board_Pro_User::is_employer( $user_id ) ) {
                $menu_nav    = 'employer-menu';
                $employer_id = WP_Job_Board_Pro_User::get_employer_by_user_id( $user_id );
                $user_name   = get_post_field( 'post_title', $employer_id );
                $avatar      = get_the_post_thumbnail( $employer_id, 'thumbnail' );
            } elseif ( method_exists( 'WP_Job_Board_Pro_User', 'is_employee' ) && WP_Job_Board_Pro_User::is_employee( $user_id ) ) {
                $menu_nav    = 'employee-menu';
                $employer_id = WP_Job_Board_Pro_User::get_employer_by_user_id( WP_Job_Board_Pro_User::get_user_id() );
                $user_name   = get_post_field( 'post_title', $employer_id );
                $avatar      = get_the_post_thumbnail( $employer_id, 'thumbnail' );
            } elseif ( WP_Job_Board_Pro_User::is_candidate( $user_id ) ) {
                $menu_nav     = 'candidate-menu';
                $candidate_id = WP_Job_Board_Pro_User::get_candidate_by_user_id( $user_id );
                $user_name    = get_post_field( 'post_title', $candidate_id );
                $avatar       = get_the_post_thumbnail( $candidate_id, 'thumbnail' );
            }
            ?>
            <div class="top-wrapper-menu <?php echo esc_attr( $el_class ); ?> pull-right">
                <a class="drop-dow" href="javascript:void(0);">
                    <div class="infor-account flex-middle">
                        <div class="avatar-wrapper">
                            <?php echo ! empty( $avatar ) ? trim( $avatar ) : get_avatar( $user_id, 54 ); ?>
                        </div>
                        <div class="name-acount"><?php echo esc_html( $user_name ); ?>
                            <?php if ( ! empty( $menu_nav ) && has_nav_menu( $menu_nav ) ) : ?>
                                <i class="ti-angle-down" aria-hidden="true"></i>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
                <?php if ( ! empty( $menu_nav ) && has_nav_menu( $menu_nav ) ) :
                    wp_nav_menu([ 'theme_location' => $menu_nav, 'container_class' => 'inner-top-menu', 'menu_class' => 'nav navbar-nav topmenu-menu', 'fallback_cb' => '', 'menu_id' => '', 'walker' => new Superio_Nav_Menu() ]);
                endif; ?>
            </div>

            <div id="apus_login_forgot_form" class="apus_login_register_form mfp-hide" data-effect="fadeIn">
                <div class="form-login-register-inner">
                    <div class="title-wrapper flex-middle">
                        <?php if ( ! empty( $login_title ) ) : ?><h3 class="title"><?php echo trim( $login_title ); ?></h3><?php endif; ?>
                        <a href="javascript:void(0);" class="close-magnific-popup ali-right"><i class="ti-close"></i></a>
                    </div>
                    <?php
                    $current_user_id = get_current_user_id();
                    ob_start();
                    wp_set_current_user( 0 );
                    echo do_shortcode( '[wp_job_board_pro_login popup="true"]' );
                    wp_set_current_user( $current_user_id );
                    echo preg_replace( '/^\s*true\s*$/mi', '', ob_get_clean() );
                    ?>
                </div>
            </div>

        <?php } else {
            $show_candidate         = superio_get_config( 'register_form_enable_candidate', true );
            $show_employer          = superio_get_config( 'register_form_enable_employer',  true );
            $login_register_page_id = wp_job_board_pro_get_option( 'login_register_page_id' );
            if ( $show_candidate || $show_employer ) : ?>

            <div class="top-wrapper-menu <?php echo esc_attr( $el_class ); ?>">
                <?php if ( $layout_type === 'page' ) : ?>
                    <a class="btn btn-login login" href="<?php echo esc_url( get_permalink( $login_register_page_id ) ); ?>"><?php echo trim( $login_register_text ); ?></a>
                <?php else : ?>
                    <div class="login-register-btn">
                        <a class="apus-user-login btn btn-login" href="#apus_login_forgot_form"><?php echo $login_btn_inner; ?></a>
                        <?php if ( $show_candidate || $show_employer ) : ?>
                        <a class="apus-user-register btn btn-register" href="#apus_register_form"><?php echo $register_btn_inner; ?></a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php endif; ?>

            <div id="apus_login_forgot_form" class="apus_login_register_form mfp-hide" data-effect="fadeIn">
                <div class="form-login-register-inner">
                    <div class="title-wrapper flex-middle">
                        <?php if ( ! empty( $login_title ) ) : ?><h3 class="title"><?php echo trim( $login_title ); ?></h3><?php endif; ?>
                        <a href="javascript:void(0);" class="close-magnific-popup ali-right"><i class="ti-close"></i></a>
                    </div>
                    <?php echo do_shortcode( '[wp_job_board_pro_login popup="true"]' ); ?>
                </div>
            </div>

            <div id="apus_register_form" class="apus_login_register_form mfp-hide" data-effect="fadeIn">
                <div class="form-login-register-inner">
                    <div class="title-wrapper flex-middle">
                        <?php if ( ! empty( $register_title ) ) : ?><h3 class="title"><?php echo trim( $register_title ); ?></h3><?php endif; ?>
                        <a href="javascript:void(0);" class="close-magnific-popup ali-right"><i class="ti-close"></i></a>
                    </div>
                    <?php echo WP_Job_Board_Pro_Template_Loader::get_template_part( 'template-jobs/misc/register2', [ 'popup' => true ] ); ?>
                </div>
            </div>

        <?php } ?>
        <?php
        remove_all_filters( 'educfind_popup_products', 20 );
        remove_all_filters( 'educfind_pricing_product_id', 20 );
        remove_all_filters( 'educfind_pricing_featured_index', 20 );
    }
}

if ( version_compare( ELEMENTOR_VERSION, '3.5.0', '<' ) ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Superio_Elementor_User_Info2 );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Superio_Elementor_User_Info2 );
}