<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_enqueue_style( 'dashicons' );
$popup = isset($popup) ? $popup : false;
$rand = rand(0000, 9999);
?>
<div class="login-form-wrapper">
	<div id="login-form-wrapper<?php echo esc_attr($rand); ?>" class="form-container">			
		<?php if ( defined('SUPERIO_DEMO_MODE') && SUPERIO_DEMO_MODE ) { ?>
			<div class="sign-in-demo-notice">
				Username: <strong>candidate</strong> or <strong>employer</strong><br>
				Password: <strong>demo</strong>
			</div>
		<?php } ?>
		
		<form class="login-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="post">

			<?php if ( isset($_SESSION['register_msg']) ) { ?>
				<div class="alert <?php echo esc_attr($_SESSION['register_msg']['error'] ? 'alert-warning' : 'alert-info'); ?>">
					<?php echo trim($_SESSION['register_msg']['msg']); ?>
				</div>
			<?php
				unset($_SESSION['register_msg']);
			}
			?>

			<!-- TOP: Logo + Title + Description -->
			<div class="educfind-popup-top">
				<div class="educfind-login-logo">✨</div>
				<h2 class="educfind-login-title">
					<?php esc_html_e('Welcome to', 'superio'); ?> <span>EducFind</span>
				</h2>
				<p class="educfind-login-description">
					<?php esc_html_e('Create your free account to access missions, educators, and our secure messaging.', 'superio'); ?>
				</p>
			</div>

			<!-- Email field -->
			<div class="form-group">
				<label><?php esc_attr_e('Email', 'superio'); ?></label>
				<input autocomplete="username" type="text" name="username" class="form-control" id="username_or_email" placeholder="<?php esc_attr_e('Email', 'superio'); ?>">
			</div>

			<!-- Password field -->
			<div class="form-group">
				<label><?php esc_attr_e('Password', 'superio'); ?></label>
				<span class="show_hide_password">
					<input autocomplete="current-password" name="password" type="password" class="password required form-control" id="login_password" placeholder="<?php esc_attr_e('Password', 'superio'); ?>">
					<a class="toggle-password" title="<?php esc_attr_e('Show', 'superio'); ?>">
						<span class="dashicons dashicons-hidden"></span>
					</a>
				</span>
			</div>

			<!-- Remember me + Forgotten password -->
			<div class="row form-group info">
				<div class="col-sm-6">
					<label for="user-remember-field<?php echo esc_attr($rand); ?>" class="remember">
						<input type="checkbox" name="remember" id="user-remember-field<?php echo esc_attr($rand); ?>" value="true">
						<?php echo esc_html__('Keep me signed in', 'superio'); ?>
					</label>
				</div>
				<div class="col-sm-6 link-right">
					<a class="back-link" href="#forgot-password-form-wrapper<?php echo esc_attr($rand); ?>" title="<?php esc_attr_e('Forgotten password', 'superio'); ?>">
						<?php echo esc_html__('Forgotten password?', 'superio'); ?>
					</a>
				</div>
			</div>

			<?php if ( version_compare(WP_JOB_BOARD_PRO_PLUGIN_VERSION, '1.2.32', '>=') && WP_Job_Board_Pro_Recaptcha::is_recaptcha_enabled() ) { ?>
				<div class="form-group info">
					<div id="recaptcha-login-form" class="ga-recaptcha" data-sitekey="<?php echo esc_attr(wp_job_board_pro_get_option('recaptcha_site_key')); ?>"></div>
				</div>
			<?php } ?>

			<!-- Login button -->
			<div class="form-group">
				<input type="submit" class="btn btn-theme btn-block" name="submit" value="<?php esc_attr_e('Login', 'superio'); ?>"/>
			</div>

			<?php
				do_action('login_form');
				wp_nonce_field('ajax-login-nonce', 'security_login');
			?>

			<?php if ( $popup ) { ?>
				<!-- "Don't you have an account?" label -->
				<span class="educfind-register-label">
					<?php esc_html_e("Don't you have an account?", 'superio'); ?>
				</span>
				<!-- Register outlined button -->
				<div class="register-info">
					<a class="apus-user-register" href="#apus_register_form">
						<?php esc_html_e('Register', 'superio'); ?>
					</a>
				</div>
			<?php } ?>

			<!-- BOTTOM: Divider + Plans + Footer -->
			<div class="educfind-popup-bottom">

				<div class="educfind-divider">
					<span><?php esc_html_e('OR CHOOSE A PREMIUM PLAN', 'superio'); ?></span>
				</div>

				<div class="educfind-plan-list">

					<div class="educfind-plan-card">
						<div>
							<h4><?php esc_html_e('Essential', 'superio'); ?></h4>
							<p><?php esc_html_e('3 applications per month', 'superio'); ?></p>
						</div>
						<div class="educfind-price">
							<?php esc_html_e('Free', 'superio'); ?>
						</div>
					</div>

					<div class="educfind-plan-card">
						<div>
							<h4><?php esc_html_e('Family · Popular', 'superio'); ?></h4>
							<p><?php esc_html_e('Unlimited applications + priority', 'superio'); ?></p>
						</div>
						<div class="educfind-price">
							<?php esc_html_e('€29/month', 'superio'); ?>
						</div>
					</div>

					<div class="educfind-plan-card">
						<div>
							<h4><?php esc_html_e('Institution', 'superio'); ?></h4>
							<p><?php esc_html_e('Multi-account + API', 'superio'); ?></p>
						</div>
						<div class="educfind-price">
							<?php esc_html_e('€149/month', 'superio'); ?>
						</div>
					</div>

				</div>

				<p class="educfind-login-footer">
					<?php esc_html_e('By registering, you agree to our', 'superio'); ?>
					<a href="#"><?php esc_html_e('Terms of Service', 'superio'); ?></a>
					<?php esc_html_e('and our', 'superio'); ?>
					<a href="#"><?php esc_html_e('Privacy Policy', 'superio'); ?></a>.
				</p>

			</div><!-- END .educfind-popup-bottom -->

		</form>
	</div>

	<!-- Forgot password form -->
	<div id="forgot-password-form-wrapper<?php echo esc_attr($rand); ?>" class="form-container forgotpassword-form-wrapper">
		<div class="top-info-user text-center1">
			<h3 class="title"><?php echo esc_html__('Reset Password', 'superio'); ?></h3>
			<div class="des"><?php echo esc_html__('Please Enter Username or Email', 'superio'); ?></div>
		</div>
		<form name="forgotpasswordform" class="forgotpassword-form" action="<?php echo esc_url(site_url('wp-login.php?action=lostpassword', 'login_post')); ?>" method="post">
			<div class="lostpassword-fields">
				<div class="form-group">
					<input type="text" name="user_login" class="user_login form-control" id="lostpassword_username" placeholder="<?php esc_attr_e('Username or E-mail', 'superio'); ?>">
				</div>
				<?php
					do_action('lostpassword_form');
					wp_nonce_field('ajax-lostpassword-nonce', 'security_lostpassword');
				?>
				<div id="recaptcha-forgotpasswordform" class="ga-recaptcha" data-sitekey="<?php echo esc_attr(wp_job_board_pro_get_option('recaptcha_site_key')); ?>"></div>
				<div class="form-group">
					<div class="row">
						<div class="col-xs-6">
							<input type="submit" class="btn btn-theme btn-sm btn-block" name="wp-submit" value="<?php esc_attr_e('Get New Password', 'superio'); ?>" tabindex="100" />
						</div>
					</div>
				</div>
			</div>
			<div class="lostpassword-link">
				<a href="#login-form-wrapper<?php echo esc_attr($rand); ?>" class="back-link">
					<?php echo esc_html__('Back To Login', 'superio'); ?>
				</a>
			</div>
		</form>
	</div>
</div>
