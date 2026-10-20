<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

wp_enqueue_style( 'dashicons' );
$popup = isset($popup) ? $popup : false;
$rand  = rand(1000, 9999);

/* ── Pull multi-product config from widget filter ── */
$popup_product_configs = apply_filters( 'educfind_popup_products', [] );

/* Legacy single-product fallback */
if ( empty( $popup_product_configs ) ) {
    $single_id = (int) apply_filters( 'educfind_pricing_product_id', 0 );
    if ( $single_id > 0 ) {
        $popup_product_configs = [ [ 'id' => $single_id, 'label' => 'Plans' ] ];
    }
}

/* ── Build audience tabs ── */
$audience_tabs = [];

if ( ! empty( $popup_product_configs ) && class_exists( 'WCSS_Product_Type' ) && function_exists( 'wc_get_product' ) ) {
    foreach ( $popup_product_configs as $cfg ) {
        $pid   = absint( $cfg['id'] ?? 0 );
        $label = sanitize_text_field( $cfg['label'] ?? '' );
        if ( ! $pid || ! $label ) continue;

        $product = wc_get_product( $pid );
        if ( ! $product || 'subscription' !== $product->get_type() ) continue;

        $plans     = WCSS_Product_Type::get_plans( $pid );
        $rules     = WCSS_Product_Type::get_rules( $pid );
        $rules_idx = [];
        foreach ( $rules as $r ) {
            $rules_idx[ strtolower( $r['plan_label'] ) ] = $r;
        }

        $tab_plans = [];
        foreach ( $plans as $idx => $plan ) {
            $is_free = ! empty( $plan['is_free'] );
            $plabel  = $plan['label'] ?: ( $is_free ? 'Gratuit' : 'Plan ' . ( $idx + 1 ) );
            $final   = (float) ( $plan['final_price'] ?? $plan['price'] ?? 0 );
            $period  = $plan['period'] ?? 'month';
            $rule    = $rules_idx[ strtolower( $plabel ) ] ?? [];

            $period_map = [ 'week' => '/sem', 'month' => '/mois', 'year' => '/an' ];
            $period_str = $period_map[ $period ] ?? '/' . $period;

            $missions = (int) ( $rule['missions_per_period'] ?? ( $is_free ? 3 : -1 ) );
            if ( $is_free ) {
                $subtitle = $missions > 0
                    ? sprintf( _n( '%d candidature/mois', '%d candidatures/mois', $missions, 'superio' ), $missions )
                    : __( 'Accès gratuit', 'superio' );
            } elseif ( ! empty( $rule['multi_zone'] ) ) {
                $subtitle = __( 'Illimitées + multi-zone', 'superio' );
            } else {
                $subtitle = __( 'Candidatures illimitées', 'superio' );
            }

            if ( $is_free ) {
                $price_str     = __( 'Gratuit', 'superio' );
                $is_free_price = true;
            } else {
                $formatted     = ( $final == floor( $final ) )
                    ? number_format( $final, 0, '.', '' )
                    : number_format( $final, 2, '.', '' );
                $price_str     = get_woocommerce_currency_symbol() . $formatted . $period_str;
                $is_free_price = false;
            }

            $tab_plans[] = [
                'label'   => $plabel,
                'subtitle'=> $subtitle,
                'price'   => $price_str,
                'is_free' => $is_free_price,
                'url'     => esc_url( add_query_arg( 'wcss_plan', $idx, get_permalink( $pid ) ) ),
            ];
        }

        if ( ! empty( $tab_plans ) ) {
            $audience_tabs[] = [ 'label' => $label, 'plans' => $tab_plans ];
        }
    }
}

/* ── Static fallback ── */
if ( empty( $audience_tabs ) ) {
    $audience_tabs = [
        [ 'label' => 'Familles',   'plans' => [
            [ 'label' => 'Essentiel', 'subtitle' => '3 candidatures/mois',    'price' => 'Gratuit',     'is_free' => true,  'url' => '#' ],
            [ 'label' => 'Illimité',  'subtitle' => 'Candidatures illimitées', 'price' => '€9.99/mois',  'is_free' => false, 'url' => '#' ],
        ]],
        [ 'label' => 'Éducateurs', 'plans' => [
            [ 'label' => 'Gratuit',  'subtitle' => '3 candidatures/mois',     'price' => 'Gratuit',      'is_free' => true,  'url' => '#' ],
            [ 'label' => 'Standard', 'subtitle' => 'Candidatures illimitées', 'price' => '€14.99/mois',  'is_free' => false, 'url' => '#' ],
            [ 'label' => 'Premium',  'subtitle' => 'Illimitées + priorité',   'price' => '€29.99/mois',  'is_free' => false, 'url' => '#' ],
        ]],
        [ 'label' => 'Structures', 'plans' => [
            [ 'label' => 'Pro', 'subtitle' => 'Accès complet + support dédié', 'price' => '€99/mois', 'is_free' => false, 'url' => '#' ],
        ]],
    ];
}

$has_tabs  = count( $audience_tabs ) > 1;
$tab_wrap  = 'ef-tabs-' . $rand; // unique wrapper ID per popup instance
?>
<div class="login-form-wrapper">
    <div id="login-form-wrapper<?php echo esc_attr($rand); ?>" class="form-container">

        <?php if ( defined('SUPERIO_DEMO_MODE') && SUPERIO_DEMO_MODE ) { ?>
        <div class="sign-in-demo-notice">
            Username: <strong>candidate</strong> or <strong>employer</strong> | Password: <strong>demo</strong>
        </div>
        <?php } ?>

        <form class="login-form" action="<?php echo esc_url( home_url('/') ); ?>" method="post">

            <?php if ( isset($_SESSION['register_msg']) ) { ?>
            <div class="alert <?php echo esc_attr($_SESSION['register_msg']['error'] ? 'alert-warning' : 'alert-info'); ?>">
                <?php echo trim($_SESSION['register_msg']['msg']); ?>
            </div>
            <?php unset($_SESSION['register_msg']); } ?>

            <!-- TOP -->
            <div class="educfind-popup-top">
                <div class="educfind-login-logo">✨</div>
                <h2 class="educfind-login-title">
                    <?php esc_html_e('Bienvenue à', 'superio'); ?> <span>EducFind</span>
                </h2>
                <p class="educfind-login-description">
                    <?php esc_html_e('Créez votre compte gratuit pour accéder aux missions et à notre messagerie sécurisée.', 'superio'); ?>
                </p>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label><?php esc_attr_e('Email', 'superio'); ?></label>
                <input autocomplete="username" type="text" name="username" class="form-control"
                       id="username_or_email" placeholder="<?php esc_attr_e('Email', 'superio'); ?>">
            </div>

            <!-- Password -->
            <div class="form-group">
                <label><?php esc_attr_e('Mot de passe', 'superio'); ?></label>
                <span class="show_hide_password">
                    <input autocomplete="current-password" name="password" type="password"
                           class="password required form-control" id="login_password"
                           placeholder="<?php esc_attr_e('Mot de passe', 'superio'); ?>">
                    <a class="toggle-password" title="<?php esc_attr_e('Afficher', 'superio'); ?>">
                        <span class="dashicons dashicons-hidden"></span>
                    </a>
                </span>
            </div>

            <!-- Remember + Forgot -->
            <div class="row form-group info">
                <div class="col-sm-6">
                    <label for="user-remember-field<?php echo esc_attr($rand); ?>" class="remember">
                        <input type="checkbox" name="remember" id="user-remember-field<?php echo esc_attr($rand); ?>" value="true">
                        <?php esc_html_e('Gardez-moi connecté', 'superio'); ?>
                    </label>
                </div>
                <div class="col-sm-6 link-right">
                    <a class="back-link" href="#forgot-password-form-wrapper<?php echo esc_attr($rand); ?>">
                        <?php esc_html_e('Mot de passe oublié ?', 'superio'); ?>
                    </a>
                </div>
            </div>

            <?php if ( version_compare(WP_JOB_BOARD_PRO_PLUGIN_VERSION, '1.2.32', '>=') && WP_Job_Board_Pro_Recaptcha::is_recaptcha_enabled() ) { ?>
            <div class="form-group info">
                <div id="recaptcha-login-form" class="ga-recaptcha"
                     data-sitekey="<?php echo esc_attr(wp_job_board_pro_get_option('recaptcha_site_key')); ?>"></div>
            </div>
            <?php } ?>

            <!-- Submit -->
            <div class="form-group">
                <input type="submit" class="btn btn-theme btn-block" name="submit"
                       value="<?php esc_attr_e('Connexion', 'superio'); ?>"/>
            </div>

            <?php do_action('login_form'); wp_nonce_field('ajax-login-nonce', 'security_login'); ?>

            <?php if ( $popup ) { ?>
            <div class="register-info">
                <span><?php esc_html_e("Pas encore de compte ?", 'superio'); ?></span>
                <a class="apus-user-register" href="#apus_register_form">
                    <?php esc_html_e("S'inscrire", 'superio'); ?>
                </a>
            </div>
            <?php } ?>

            <!-- PLANS SECTION -->
            <div class="educfind-popup-bottom" data-tabwrap="<?php echo esc_attr($tab_wrap); ?>">

                <div class="educfind-divider">
                    <span><?php esc_html_e('CHOISISSEZ UN FORFAIT', 'superio'); ?></span>
                </div>

                <?php if ( $has_tabs ) : ?>
                <!-- Tab pill buttons — scoped to this popup instance via data-tabwrap -->
                <div class="ef-popup-tabs" id="<?php echo esc_attr($tab_wrap); ?>">
                    <?php foreach ( $audience_tabs as $ti => $tab ) : ?>
                    <button type="button"
                            class="ef-popup-tab-btn<?php echo $ti === 0 ? ' active' : ''; ?>"
                            data-tabwrap="<?php echo esc_attr($tab_wrap); ?>"
                            data-panel="<?php echo esc_attr( $tab_wrap . '-panel-' . $ti ); ?>">
                        <?php echo esc_html( $tab['label'] ); ?>
                    </button>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Plan panels — ONLY the first panel is visible; rest are hidden via inline style -->
                <?php foreach ( $audience_tabs as $ti => $tab ) :
                    $is_active = ( $ti === 0 );
                ?>
                <div class="educfind-plan-list ef-tab-panel"
                     id="<?php echo esc_attr( $tab_wrap . '-panel-' . $ti ); ?>"
                     data-tabwrap="<?php echo esc_attr($tab_wrap); ?>"
                     style="display:<?php echo $is_active ? 'flex' : 'none'; ?>;">
                    <?php foreach ( $tab['plans'] as $plan ) : ?>
                    <a href="<?php echo $plan['url']; ?>"
                       class="educfind-plan-card<?php echo $plan['is_free'] ? ' ef-plan-free' : ''; ?>">
                        <div class="educfind-plan-info">
                            <h4><?php echo esc_html( $plan['label'] ); ?></h4>
                            <p><?php echo esc_html( $plan['subtitle'] ); ?></p>
                        </div>
                        <span class="educfind-price<?php echo $plan['is_free'] ? ' ef-free' : ''; ?>">
                            <?php echo esc_html( $plan['price'] ); ?>
                        </span>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>

                <p class="educfind-login-footer">
                    <?php esc_html_e('En vous inscrivant, vous acceptez nos', 'superio'); ?>
                    <a href="<?php echo esc_url( home_url('/terms/') ); ?>"><?php esc_html_e("Conditions d'utilisation", 'superio'); ?></a>
                    <?php esc_html_e('et notre', 'superio'); ?>
                    <a href="<?php echo esc_url( home_url('/privacy-policy/') ); ?>"><?php esc_html_e('politique de confidentialité', 'superio'); ?></a>.
                </p>

            </div><!-- /educfind-popup-bottom -->

        </form>
    </div>

    <!-- Forgot password -->
    <div id="forgot-password-form-wrapper<?php echo esc_attr($rand); ?>" class="form-container forgotpassword-form-wrapper">
        <div class="top-info-user text-center1">
            <h3 class="title"><?php esc_html_e('Réinitialiser le mot de passe', 'superio'); ?></h3>
            <div class="des"><?php esc_html_e("Veuillez saisir votre nom d'utilisateur ou votre adresse e-mail.", 'superio'); ?></div>
        </div>
        <form name="forgotpasswordform" class="forgotpassword-form"
              action="<?php echo esc_url(site_url('wp-login.php?action=lostpassword', 'login_post')); ?>" method="post">
            <div class="lostpassword-fields">
                <div class="form-group">
                    <input type="text" name="user_login" class="user_login form-control"
                           id="lostpassword_username"
                           placeholder="<?php esc_attr_e("Nom d'utilisateur ou E-mail", 'superio'); ?>">
                </div>
                <?php do_action('lostpassword_form'); wp_nonce_field('ajax-lostpassword-nonce', 'security_lostpassword'); ?>
                <div id="recaptcha-forgotpasswordform" class="ga-recaptcha"
                     data-sitekey="<?php echo esc_attr(wp_job_board_pro_get_option('recaptcha_site_key')); ?>"></div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-6">
                            <input type="submit" class="btn btn-theme btn-sm btn-block" name="wp-submit"
                                   value="<?php esc_attr_e('Obtenir un nouveau mot de passe', 'superio'); ?>" tabindex="100"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lostpassword-link">
                <a href="#login-form-wrapper<?php echo esc_attr($rand); ?>" class="back-link">
                    <?php esc_html_e('Retour à la connexion', 'superio'); ?>
                </a>
            </div>
        </form>
    </div>
</div>

<?php if ( $has_tabs ) : ?>

<script>
(function () {
    /* Use event delegation — safe even if popup renders after DOMContentLoaded */
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.ef-popup-tab-btn');
        if (!btn) return;

        var tabWrapId = btn.getAttribute('data-tabwrap');
        var targetId  = btn.getAttribute('data-panel');
        if (!tabWrapId || !targetId) return;

        /* Deactivate all buttons in THIS popup's tab bar */
        var tabBar = document.getElementById(tabWrapId);
        if (tabBar) {
            tabBar.querySelectorAll('.ef-popup-tab-btn').forEach(function (b) {
                b.classList.remove('active');
            });
        }
        btn.classList.add('active');

        /* Hide all panels belonging to THIS popup instance */
        document.querySelectorAll('.ef-tab-panel[data-tabwrap="' + tabWrapId + '"]').forEach(function (p) {
            p.style.setProperty('display', 'none', 'important');
        });

        /* Show the target panel — use flex to match theme expectation */
        var target = document.getElementById(targetId);
        if (target) {
            if (window.innerWidth <= 767) {
                target.style.setProperty('display', 'grid', 'important');
            } else {
                target.style.setProperty('display', 'flex', 'important');
            }
        }
    });
})();
</script>
<?php endif; ?>