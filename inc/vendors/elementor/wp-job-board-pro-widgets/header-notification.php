<?php
/**
 * Elementor User Notification Widget
 * Clean top-to-bottom card layout per notification.
 * All notification hooks live in functions.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Superio_Elementor_User_Notification extends Elementor\Widget_Base {

    public function get_name() {
        return 'apus_element_user_notification';
    }

    public function get_title() {
        return esc_html__( 'Apus Notification', 'superio' );
    }

    public function get_categories() {
        return [ 'superio-header-elements' ];
    }

    protected function register_controls() {

        $this->start_controls_section( 'content_section', [
            'label' => esc_html__( 'Content', 'superio' ),
            'tab'   => Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'el_class', [
            'label'       => esc_html__( 'Extra class name', 'superio' ),
            'type'        => Elementor\Controls_Manager::TEXT,
            'placeholder' => esc_html__( 'Custom CSS class', 'superio' ),
        ] );

        $this->add_control( 'dropdown', [
            'label'   => esc_html__( 'Dropdown direction', 'superio' ),
            'type'    => Elementor\Controls_Manager::SELECT,
            'options' => [
                'right' => esc_html__( 'Right', 'superio' ),
                'left'  => esc_html__( 'Left', 'superio' ),
            ],
            'default' => 'right',
        ] );

        $this->end_controls_section();

        $this->start_controls_section( 'section_style', [
            'label' => esc_html__( 'Icon', 'superio' ),
            'tab'   => Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'icon_color', [
            'label'     => esc_html__( 'Icon Color', 'superio' ),
            'type'      => Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .efj-notif-icon svg' => 'fill: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'icon_hover_color', [
            'label'     => esc_html__( 'Icon Hover Color', 'superio' ),
            'type'      => Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .message-notification:hover .efj-notif-icon svg' => 'fill: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'badge_bg', [
            'label'     => esc_html__( 'Badge Background', 'superio' ),
            'type'      => Elementor\Controls_Manager::COLOR,
            'default'   => '#f59e0b',
            'selectors' => [
                '{{WRAPPER}} .efj-notif-count' => 'background: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'badge_color', [
            'label'     => esc_html__( 'Badge Text Color', 'superio' ),
            'type'      => Elementor\Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .efj-notif-count' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_responsive_control( 'icon_size', [
            'label'      => esc_html__( 'Icon Size (px)', 'superio' ),
            'type'       => Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 16, 'max' => 64 ] ],
            'default'    => [ 'size' => 28, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .efj-notif-icon'     => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .efj-notif-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings();
        extract( $settings );

        $notifications = [];
        $user_post_id  = null;
        $post_type     = null;

        if ( is_user_logged_in() ) {
            $user_id = WP_Job_Board_Pro_User::get_user_id();

            if ( WP_Job_Board_Pro_User::is_employer( $user_id ) ) {
                $user_post_id = WP_Job_Board_Pro_User::get_employer_by_user_id( $user_id );
                $post_type    = 'employer';
            } elseif (
                method_exists( 'WP_Job_Board_Pro_User', 'is_employee' ) &&
                WP_Job_Board_Pro_User::is_employee( $user_id )
            ) {
                $user_post_id = WP_Job_Board_Pro_User::get_employer_by_user_id( $user_id );
                $post_type    = 'employer';
            } elseif ( WP_Job_Board_Pro_User::is_candidate( $user_id ) ) {
                $user_post_id = WP_Job_Board_Pro_User::get_candidate_by_user_id( $user_id );
                $post_type    = 'candidate';
            }
        }

        if ( ! empty( $user_post_id ) && ! empty( $post_type ) ) {
            $notifications = WP_Job_Board_Pro_User_Notification::get_not_seen_notifications(
                $user_post_id,
                $post_type
            );
        }

        $count = ! empty( $notifications ) ? count( $notifications ) : 0;
        ?>
        <div class="message-top <?php echo esc_attr( $el_class ); ?>">

            <!-- ── Trigger ── -->
            <a class="message-notification"
               href="javascript:void(0);"
               onclick="this.closest('.message-top').classList.toggle('open')">
                <span class="efj-notif-wrap">
                    <span class="efj-notif-icon">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10
                                     10-4.477 10-10S17.523 2 12 2zm0 18a8 8 0 1 1
                                     0-16 8 8 0 0 1 0 16zm.5-13H11v6l5.25 3.15
                                     .75-1.23-4.5-2.67V7z"/>
                        </svg>
                    </span>
                    <?php if ( $count > 0 ) : ?>
                        <span class="efj-notif-count"><?php echo $count; ?></span>
                    <?php endif; ?>
                </span>
            </a>

            <!-- ── Dropdown panel ── -->
            <div class="efj-notif-panel <?php echo esc_attr( trim( $dropdown ) ); ?>">

                <!-- Header -->
                <div class="efj-np-header">
                    <span class="efj-np-title">
                        <?php esc_html_e( 'Notifications', 'superio' ); ?>
                    </span>
                    <?php if ( $count > 0 ) : ?>
                        <span class="efj-np-count-pill">
                            <?php echo $count; ?> <?php esc_html_e( 'new', 'superio' ); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <?php if ( ! empty( $notifications ) ) : ?>
                    <ul class="efj-np-list">
                        <?php foreach ( $notifications as $notify ) :
                            $type = ! empty( $notify['type'] ) ? $notify['type'] : '';
                            if ( ! $type ) continue;

                            $display_msg = trim( WP_Job_Board_Pro_User_Notification::display_notify( $notify ) );
                            if ( empty( $display_msg ) ) continue;

                            // Strip <a> links — keep the link text, remove the href.
                            // Candidates should not be redirected from the notification.
                            $display_msg = preg_replace(
                                '/<a\b[^>]*>(.*?)<\/a>/is',
                                '$1',
                                $display_msg
                            );
                        ?>
                            <li class="efj-np-item">

                                <!-- Timestamp -->
                                <div class="efj-np-time">
                                    <svg viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm.5 5H11v6l5.25 3.15.75-1.23-4.5-2.67V7z"/></svg>
                                    <?php
                                    echo human_time_diff( $notify['time'], current_time( 'timestamp' ) )
                                         . ' ' . esc_html__( 'ago', 'superio' );
                                    ?>
                                </div>

                                <!-- Message — full width, wraps naturally -->
                                <p class="efj-np-msg">
                                    <?php echo wp_kses_post( $display_msg ); ?>
                                </p>

                                <!-- Dismiss -->
                                <a href="javascript:void(0);"
                                   class="efj-np-dismiss remove-notify-btn"
                                   data-id="<?php echo esc_attr( $notify['unique_id'] ); ?>"
                                   data-nonce="<?php echo esc_attr( wp_create_nonce( 'wp-job-board-pro-remove-notify-nonce' ) ); ?>"
                                   title="<?php esc_attr_e( 'Dismiss', 'superio' ); ?>">✕</a>

                            </li>
                        <?php endforeach; ?>
                    </ul>

                <?php else : ?>

                    <!-- Empty state -->
                    <div class="efj-np-empty">
                        <svg viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm.5 5H11v6l5.25 3.15.75-1.23-4.5-2.67V7z"/></svg>
                        <?php esc_html_e( 'No new notifications', 'superio' ); ?>
                    </div>

                <?php endif; ?>

            </div><!-- /.efj-notif-panel -->

        </div><!-- /.message-top -->

        <script>
        /* Close panel when clicking outside */
        (function() {
            document.addEventListener('click', function(e) {
                document.querySelectorAll('.message-top.open').forEach(function(el) {
                    if ( ! el.contains(e.target) ) {
                        el.classList.remove('open');
                    }
                });
            });
        })();
        </script>

        <?php
    }
}

if ( version_compare( ELEMENTOR_VERSION, '3.5.0', '<' ) ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Superio_Elementor_User_Notification );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Superio_Elementor_User_Notification );
}