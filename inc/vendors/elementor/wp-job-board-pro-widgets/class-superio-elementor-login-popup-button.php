<?php
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Elementor widget: a styled button that only opens the login popup
 * (#apus_login_forgot_form), reusing the same trigger markup/class
 * ("apus-user-login") that Superio_Elementor_User_Info2 uses.
 */
class Superio_Elementor_Login_Popup_Button extends Elementor\Widget_Base
{

    public function get_name()
    {
        return 'apus_element_login_popup_btn';
    }
    public function get_title()
    {
        return esc_html__('EducFind Login Popup Button', 'superio');
    }
    public function get_icon()
    {
        return 'eicon-button';
    }
    public function get_categories()
    {
        return ['superio-header-elements'];
    }

    protected function register_controls()
    {

        /* ── Content ──────────────────────────────────────────────────── */
        $this->start_controls_section('content_section', [
            'label' => esc_html__('Content', 'superio'),
            'tab'   => Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('button_text', [
            'label'   => esc_html__('Button Text', 'superio'),
            'type'    => Elementor\Controls_Manager::TEXT,
            'default' => esc_html__('Commencer gratuitement', 'superio'),
        ]);

        $this->add_control('button_icon', [
            'label'   => esc_html__('Icon', 'superio'),
            'type'    => Elementor\Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-arrow-right', 'library' => 'fa-solid'],
        ]);

        $this->add_control('icon_position', [
            'label'     => esc_html__('Icon Position', 'superio'),
            'type'      => Elementor\Controls_Manager::SELECT,
            'options'   => [
                'before' => esc_html__('Before Text', 'superio'),
                'after'  => esc_html__('After Text', 'superio'),
            ],
            'default'   => 'after',
            'condition' => ['button_icon[value]!' => ''],
        ]);

        $this->add_control('el_class', [
            'label'     => esc_html__('Extra class name', 'superio'),
            'type'      => Elementor\Controls_Manager::TEXT,
            'separator' => 'before',
        ]);

        $this->add_responsive_control('align', [
            'label'     => esc_html__('Alignment', 'superio'),
            'type'      => Elementor\Controls_Manager::CHOOSE,
            'options'   => [
                'left'   => ['title' => esc_html__('Left',   'superio'), 'icon' => 'fa fa-align-left'],
                'center' => ['title' => esc_html__('Center', 'superio'), 'icon' => 'fa fa-align-center'],
                'right'  => ['title' => esc_html__('Right',  'superio'), 'icon' => 'fa fa-align-right'],
            ],
            'selectors' => ['{{WRAPPER}}' => 'text-align: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        /* ── Style: Button ────────────────────────────────────────────── */
        $this->start_controls_section('section_button_style', [
            'label' => esc_html__('Button', 'superio'),
            'tab'   => Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'button_typography',
            'selector' => '{{WRAPPER}} .efj-popup-btn',
        ]);

        $this->add_responsive_control('button_padding', [
            'label'      => esc_html__('Padding', 'superio'),
            'type'       => Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors'  => [
                '{{WRAPPER}} .efj-popup-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('border_radius', [
            'label'      => esc_html__('Border Radius', 'superio'),
            'type'       => Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => [
                '{{WRAPPER}} .efj-popup-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('border_width', [
            'label'      => esc_html__('Border Width', 'superio'),
            'type'       => Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default'    => ['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1, 'unit' => 'px', 'isLinked' => true],
            'selectors'  => [
                '{{WRAPPER}} .efj-popup-btn' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('heading_normal', [
            'label'     => esc_html__('Normal', 'superio'),
            'type'      => Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('button_color', [
            'label'     => esc_html__('Text Color', 'superio'),
            'type'      => Elementor\Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => ['{{WRAPPER}} .efj-popup-btn' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('button_bg_color', [
            'label'     => esc_html__('Background Color', 'superio'),
            'type'      => Elementor\Controls_Manager::COLOR,
            'default'   => '#2563EB',
            'selectors' => ['{{WRAPPER}} .efj-popup-btn' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('button_border_color', [
            'label'     => esc_html__('Border Color', 'superio'),
            'type'      => Elementor\Controls_Manager::COLOR,
            'default'   => '#2563EB',
            'selectors' => ['{{WRAPPER}} .efj-popup-btn' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_control('heading_hover', [
            'label'     => esc_html__('Hover', 'superio'),
            'type'      => Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('button_hover_color', [
            'label'     => esc_html__('Hover Text Color', 'superio'),
            'type'      => Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .efj-popup-btn:hover' => 'color: {{VALUE}};',
                '{{WRAPPER}} .efj-popup-btn:focus' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('button_hover_bg_color', [
            'label'     => esc_html__('Hover Background Color', 'superio'),
            'type'      => Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .efj-popup-btn:hover' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .efj-popup-btn:focus' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('button_hover_border_color', [
            'label'     => esc_html__('Hover Border Color', 'superio'),
            'type'      => Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .efj-popup-btn:hover' => 'border-color: {{VALUE}};',
                '{{WRAPPER}} .efj-popup-btn:focus' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        /* ── Style: Icon ──────────────────────────────────────────────── */
        $this->start_controls_section('section_icon_style', [
            'label'     => esc_html__('Icon', 'superio'),
            'tab'       => Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['button_icon[value]!' => ''],
        ]);

        $this->add_control('icon_size', [
            'label'      => esc_html__('Icon Size', 'superio'),
            'type'       => Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range'      => ['px' => ['min' => 8, 'max' => 40]],
            'default'    => ['unit' => 'px', 'size' => 14],
            'selectors'  => [
                '{{WRAPPER}} .efj-popup-btn .efj-btn-icon' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('icon_gap', [
            'label'      => esc_html__('Gap Between Icon and Text', 'superio'),
            'type'       => Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 20]],
            'default'    => ['unit' => 'px', 'size' => 8],
            'selectors'  => [
                '{{WRAPPER}} .efj-popup-btn' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('icon_color', [
            'label'     => esc_html__('Icon Color', 'superio'),
            'type'      => Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .efj-popup-btn .efj-btn-icon' => 'color: {{VALUE}}; fill: {{VALUE}};'],
        ]);

        $this->add_control('icon_hover_color', [
            'label'     => esc_html__('Icon Hover Color', 'superio'),
            'type'      => Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .efj-popup-btn:hover .efj-btn-icon' => 'color: {{VALUE}}; fill: {{VALUE}};'],
        ]);

        $this->end_controls_section();
    }

    /* ================================================================== */
    /*  RENDER                                                              */
    /* ================================================================== */

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $button_text = ! empty($settings['button_text']) ? $settings['button_text'] : '';
        $icon_pos    = ! empty($settings['icon_position']) ? $settings['icon_position'] : 'after';

        $icon_html = '';
        if (! empty($settings['button_icon']['value'])) {
            ob_start();
            Elementor\Icons_Manager::render_icon($settings['button_icon'], [
                'aria-hidden' => 'true',
                'class'       => 'efj-btn-icon',
            ]);
            $icon_html = ob_get_clean();
        }

        $inner = '';
        if ($icon_html && $icon_pos === 'before') {
            $inner .= $icon_html;
        }
        $inner .= '<span>' . esc_html($button_text) . '</span>';
        if ($icon_html && $icon_pos === 'after') {
            $inner .= $icon_html;
        }

        $el_class = ! empty($settings['el_class']) ? ' ' . $settings['el_class'] : '';
?>
        <a class="apus-user-login efj-popup-btn<?php echo esc_attr($el_class); ?>" href="#apus_login_forgot_form">
            <?php echo $inner; ?>
        </a>
<?php
    }
}

if (version_compare(ELEMENTOR_VERSION, '3.5.0', '<')) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Superio_Elementor_Login_Popup_Button);
} else {
    Elementor\Plugin::instance()->widgets_manager->register(new Superio_Elementor_Login_Popup_Button);
}
