<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
superio_load_select2();

$my_jobs_page_id = wp_job_board_pro_get_option('my_jobs_page_id');
$my_jobs_url = get_permalink( $my_jobs_page_id );

?>

<div class="box-dashboard-wrapper my-job-employer">
	<h3 class="widget-title"><?php echo esc_html__('Gérer les missions','superio') ?></h3>
	<div class="inner-list">
		<div class="search-orderby-wrapper flex-middle-sm">
			<div class="search-my-jobs-form search-applicants-form">
				<form action="" method="get">
					<div class="flex-middle">
						<button class="search-submit btn btn-sm btn-search" name="submit">
							<i class="flaticon-magnifiying-glass"></i>
						</button>
						<input type="text" placeholder="<?php esc_attr_e( 'Recherche ...', 'superio' ); ?>" class="form-control" name="search" value="<?php echo esc_attr(isset($_GET['search']) ? $_GET['search'] : ''); ?>">
					</div>
					<input type="hidden" name="paged" value="1" />
				</form>
			</div>
			<div class="sort-my-jobs-form sortby-form">
				<?php
					$orderby_options = apply_filters( 'wp_job_board_pro_my_jobs_orderby', array(
						'menu_order'	=> esc_html__( 'Défaut', 'superio' ),
						'newest' 		=> esc_html__( 'Le plus récent', 'superio' ),
						'oldest'     	=> esc_html__( 'Le plus ancien', 'superio' ),
					) );
					$orderby = isset( $_GET['orderby'] ) ? wp_unslash( $_GET['orderby'] ) : 'newest'; 
				?>
				<div class="orderby-wrapper flex-middle">
					<span class="text-sort">
						<?php echo esc_html__('Sort by: ','superio'); ?>
					</span>
					<form class="my-jobs-ordering" method="get">
						<select name="orderby" class="orderby">
							<?php foreach ( $orderby_options as $id => $name ) : ?>
								<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
							<?php endforeach; ?>
						</select>
						<input type="hidden" name="paged" value="1" />
						<?php WP_Job_Board_Pro_Mixes::query_string_form_fields( null, array( 'orderby', 'submit', 'paged' ) ); ?>
					</form>
				</div>
			</div>
		</div>

		<?php
			$user_id = WP_Job_Board_Pro_User::get_user_id();
			$paged = (get_query_var( 'paged' )) ? get_query_var( 'paged' ) : 1;
			$query_vars = array(
				'post_type'     => 'job_listing',
				'post_status'   => array( 'publish', 'expired', 'pending', 'pending_payment', 'pending_approve', 'draft', 'preview', 'filled', 'closed', 'private', 'completed' ),
				'paged'         => $paged,
				'author'        => $user_id,
				'orderby'       => 'date',
				'order'         => 'DESC',
				'meta_query'    => array(
					'relation' => 'OR',
					array(
						'key'     => WP_JOB_BOARD_PRO_JOB_LISTING_PREFIX . 'filled',
						'compare' => 'NOT EXISTS',
					),
					array(
						'key'     => WP_JOB_BOARD_PRO_JOB_LISTING_PREFIX . 'filled',
						'value'   => 'on',
						'compare' => '=',
					),
					array(
						'key'     => WP_JOB_BOARD_PRO_JOB_LISTING_PREFIX . 'filled',
						'value'   => '',
						'compare' => '=',
					),
				),
			);
			if ( isset($_GET['search']) ) {
				$query_vars['s'] = $_GET['search'];
			}
			if ( isset($_GET['orderby']) ) {
				switch ($_GET['orderby']) {
					case 'menu_order':
						$query_vars['orderby'] = array(
							'menu_order' => 'ASC',
							'date'       => 'DESC',
							'ID'         => 'DESC',
						);
						break;
					case 'newest':
						$query_vars['orderby'] = 'date';
						$query_vars['order'] = 'DESC';
						break;
					case 'oldest':
						$query_vars['orderby'] = 'date';
						$query_vars['order'] = 'ASC';
						break;
				}
			}
			$jobs = new WP_Query($query_vars);
			
			if ( $jobs->have_posts() ) :
			?>
			<div class="table-responsive">
				<table class="job-table">
					<thead>
						<tr>
							<th class="job-title"><?php esc_html_e('Titre', 'superio'); ?></th>
							<th class="job-applicants"><?php esc_html_e('Éducateurs', 'superio'); ?></th>
							<th class="job-date"><?php esc_html_e('Créé et expiré', 'superio'); ?></th>
							<th class="job-status"><?php esc_html_e('Statut', 'superio'); ?></th>
							<th class="job-actions"><?php esc_html_e('Actes', 'superio'); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php while ( $jobs->have_posts() ) : $jobs->the_post(); global $post;
						$filled       = WP_Job_Board_Pro_Job_Listing::get_post_meta($post->ID, 'filled');
						$is_completed = ( $post->post_status === 'completed' );
					?>
						<tr>
							<!-- TITLE -->
							<td class="job-table-info">
								<div class="job-table-info-content">
									<div class="title-wrapper">
										<h3 class="job-table-info-content-title">
											<?php if ( $post->post_status == 'publish' ) { ?>
												<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
											<?php } else { ?>
												<?php the_title(); ?>
											<?php } ?>
										</h3>
										<?php if (!has_post_thumbnail( $post->ID ) ): ?>
											<?php superio_job_display_featured_icon($post); ?>
										<?php endif; ?>
										<?php superio_job_display_urgent_icon($post,'icon'); ?>
									</div>
									<div class="job-metas">
										<?php superio_job_display_short_location($post, 'icon'); ?>
									</div>
								</div>
							</td>

							<!-- APPLICANTS -->
							<td class="job-table-applicants text-theme nowrap">
								<?php
									$count_applicants = WP_Job_Board_Pro_Job_Listing::count_applicants($post->ID);
									echo '<span class="number">'.$count_applicants.'</span> ';
									esc_html_e('Demandeur', 'superio');
								?>
							</td>

							<!-- DATES -->
							<td>
								<div class="job-table-info-content-date-expiry">
									<div class="created">
										<strong><?php esc_html_e('créée: ', 'superio'); ?></strong><?php the_time( get_option('date_format') ); ?>
									</div>
									<div class="expiry-date">
										<strong><?php esc_html_e('Date\'d expiration', 'superio'); ?></strong>
										<span class="text-danger">
										<?php
											$expires = get_post_meta( $post->ID, WP_JOB_BOARD_PRO_JOB_LISTING_PREFIX.'expiry_date', true);
											if ( $expires ) {
												echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $expires ) ) );
											} else {
												echo '--';
											}
										?>
										</span>
									</div>
								</div>
							</td>

							<!-- WP POST STATUS (Published / Preview / Expired …) -->
							<td class="job-table-status nowrap">
								<div class="job-table-actions-inner <?php echo esc_attr($post->post_status); ?>">
									<?php
										$post_status = get_post_status_object( $post->post_status );
										if ( !empty($post_status->label) ) {
											echo esc_html($post_status->label);
										} else {
											echo esc_html($post_status->post_status);
										}
									?>
								</div>
							</td>

							<!-- ACTIONS -->
							<td class="job-table-actions nowrap">
								<div class="action-button">
									<?php
									$my_jobs_url = add_query_arg( 'job_id', $post->ID, remove_query_arg( 'job_id', $my_jobs_url ) );
									switch ( $post->post_status ) {
										case 'publish' :
											$edit_url = add_query_arg( 'action', 'edit', remove_query_arg( 'action', $my_jobs_url ) );

											if ( $filled ) {
												$classes    = 'mark_not_filled';
												$title      = esc_html__('Marque non remplie', 'superio');
												$nonce      = wp_create_nonce( 'wp-job-board-pro-mark-not-filled-nonce' );
												$icon_class = 'fa fa-lock';
											} else {
												$classes    = 'mark_filled';
												$title      = esc_html__('Marquer comme rempli', 'superio');
												$nonce      = wp_create_nonce( 'wp-job-board-pro-mark-filled-nonce' );
												$icon_class = 'fa fa-unlock';
											}
											?>
											<a data-toggle="tooltip"
											   class="fill-btn btn-action-icon <?php echo esc_attr($classes); ?>"
											   href="javascript:void(0);"
											   title="<?php echo esc_attr($title); ?>"
											   data-job_id="<?php echo esc_attr($post->ID); ?>"
											   data-nonce="<?php echo esc_attr($nonce); ?>">
												<i class="<?php echo esc_attr($icon_class); ?>"></i>
											</a>

											<?php if ( $filled ) : ?>
												<a data-toggle="tooltip"
												   class="complete-btn btn-action-icon mark_completed"
												   href="javascript:void(0);"
												   title="<?php esc_attr_e( 'Marquer comme complété', 'superio' ); ?>"
												   data-job_id="<?php echo esc_attr( $post->ID ); ?>"
												   data-nonce="<?php echo esc_attr( wp_create_nonce( 'superio-mark-completed-nonce' ) ); ?>">
													<i class="fa fa-check-circle"></i>
												</a>
											<?php endif; ?>

											<?php if ( wp_job_board_pro_get_option('user_edit_published_submission') !== 'no' ) { ?>
												<a data-toggle="tooltip"
												   class="edit-btn btn-action-icon edit job-table-action"
												   href="<?php echo esc_url($edit_url); ?>"
												   title="<?php esc_attr_e('Edit', 'superio'); ?>">
													<i class="ti-pencil-alt"></i>
												</a>
											<?php } ?>
											<?php break; ?>

										<?php case 'completed' : ?>
										<?php
										$edit_url = add_query_arg( 'action', 'edit', remove_query_arg( 'action', $my_jobs_url ) );

										// Find the approved candidate for this completed job
										$approved_app = get_posts( array(
											'post_type'      => 'job_applicant',
											'post_status'    => 'any',
											'posts_per_page' => 1,
											'fields'         => 'ids',
											'meta_query'     => array(
												array(
													'key'   => WP_JOB_BOARD_PRO_APPLICANT_PREFIX . 'job_id',
													'value' => $post->ID,
												),
												array(
													'key'     => WP_JOB_BOARD_PRO_APPLICANT_PREFIX . 'app_status',
													'value'   => array( 'approved', 'hired' ),
													'compare' => 'IN',
												),
											),
										) );

										if ( ! empty( $approved_app ) ) :
											$approved_candidate_id = get_post_meta( $approved_app[0], WP_JOB_BOARD_PRO_APPLICANT_PREFIX . 'candidate_id', true );

											if ( $approved_candidate_id ) :
												// Check if already reviewed
												$already_reviewed = get_comments( array(
													'post_id' => $approved_candidate_id,
													'user_id' => get_current_user_id(),
													'count'   => true,
													'status'  => 'all',
												) );

												if ( $already_reviewed > 0 ) : ?>
													<span class="btn-action-icon" style="color:#f59e0b; font-size:13px;" title="<?php esc_attr_e('Avis soumis', 'superio'); ?>">
														<i class="fa fa-star"></i>
													</span>
												<?php else :
													$review_url = get_permalink( $approved_candidate_id );
												?>
													<a data-toggle="tooltip"
													class="btn-action-icon"
													href="<?php echo esc_url( $review_url ); ?>"
													title="<?php esc_attr_e( 'Évaluer l\'éducateur', 'superio' ); ?>"
													style="color:#f59e0b;">
														<i class="fa fa-star"></i>
													</a>
												<?php endif; ?>
											<?php endif; ?>
										<?php endif; ?>

										<?php if ( wp_job_board_pro_get_option('user_edit_published_submission') !== 'no' ) : ?>
											<a data-toggle="tooltip"
											class="edit-btn btn-action-icon edit job-table-action"
											href="<?php echo esc_url($edit_url); ?>"
											title="<?php esc_attr_e('Edit', 'superio'); ?>">
												<i class="ti-pencil-alt"></i>
											</a>
										<?php endif; ?>
										<?php break; ?>

										<?php case 'expired' : ?>
											<?php $relist_url = add_query_arg( 'action', 'relist', remove_query_arg( 'action', $my_jobs_url ) ); ?>
											<a data-toggle="tooltip"
											   href="<?php echo esc_url($relist_url); ?>"
											   class="btn-action-icon edit job-table-action"
											   title="<?php esc_attr_e('Relist', 'superio'); ?>">
												<i class="fa fa-registered"></i>
											</a>
											<?php break; ?>

										<?php case 'pending_payment': ?>
											<?php $order_id = get_post_meta($post->ID, WP_JOB_BOARD_PRO_JOB_LISTING_PREFIX.'order_id', true); ?>
											<?php if ( $order_id ) : ?>
												<?php if ( wp_job_board_pro_get_option('user_edit_published_submission') !== 'no' ) : ?>
													<?php $edit_url = add_query_arg( 'action', 'edit', remove_query_arg( 'action', $my_jobs_url ) ); ?>
													<a data-toggle="tooltip"
													   class="edit-btn btn-action-icon edit job-table-action"
													   href="<?php echo esc_url($edit_url); ?>"
													   title="<?php esc_attr_e('Edit', 'superio'); ?>">
														<i class="ti-pencil-alt"></i>
													</a>
												<?php endif; ?>
											<?php else : ?>
												<?php $continue_url = add_query_arg( 'action', 'continue', remove_query_arg( 'action', $my_jobs_url ) ); ?>
												<a data-toggle="tooltip"
												   href="<?php echo esc_url($continue_url); ?>"
												   class="edit-btn btn-action-icon edit job-table-action"
												   title="<?php esc_attr_e('Continue', 'superio'); ?>">
													<i class="ti-arrow-right"></i>
												</a>
											<?php endif; ?>
											<?php break; ?>

										<?php case 'pending_approve': ?>
										<?php case 'pending' : ?>
											<?php if ( wp_job_board_pro_get_option('user_edit_published_submission') !== 'no' ) : ?>
												<?php $edit_url = add_query_arg( 'action', 'edit', remove_query_arg( 'action', $my_jobs_url ) ); ?>
												<a data-toggle="tooltip"
												   class="edit-btn btn-action-icon edit job-table-action"
												   href="<?php echo esc_url($edit_url); ?>"
												   title="<?php esc_attr_e('Edit', 'superio'); ?>">
													<i class="ti-pencil-alt"></i>
												</a>
											<?php endif; ?>
											<?php break; ?>

										<?php case 'draft' : ?>
										<?php case 'preview' : ?>
											<?php $continue_url = add_query_arg( 'action', 'continue', remove_query_arg( 'action', $my_jobs_url ) ); ?>
											<a data-toggle="tooltip"
											   href="<?php echo esc_url($continue_url); ?>"
											   class="edit-btn btn-action-icon edit job-table-action"
											   title="<?php esc_attr_e('Continue', 'superio'); ?>">
												<i class="ti-arrow-right"></i>
											</a>
											<?php break; ?>
									<?php } // end switch ?>

									<a data-toggle="tooltip"
									   class="remove-btn btn-action-icon deleted job-table-action job-button-delete"
									   href="javascript:void(0)"
									   data-job_id="<?php echo esc_attr($post->ID); ?>"
									   data-nonce="<?php echo esc_attr(wp_create_nonce( 'wp-job-board-pro-delete-job-nonce' )); ?>"
									   title="<?php esc_attr_e('Remove', 'superio'); ?>">
										<i class="ti-close"></i>
									</a>
								</div>
							</td>
						</tr>
					<?php endwhile; ?>
					</tbody>
				</table>
			</div>
			<?php
				WP_Job_Board_Pro_Mixes::custom_pagination( array(
					'wp_query'      => $jobs,
					'max_num_pages' => $jobs->max_num_pages,
					'prev_text'     => '<i class="ti-arrow-left"></i>',
					'next_text'     => '<i class="ti-arrow-right"></i>',
				));
				wp_reset_postdata();
			?>
		<?php else : ?>
			<div class="alert alert-warning">
				<p><?php esc_html_e( 'Vous n\'avez encore aucun emploi. Commencez par en créer un nouveau.', 'superio' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>