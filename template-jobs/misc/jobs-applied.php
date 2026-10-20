<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
superio_load_select2();
?>
<div class="box-dashboard-wrapper">
	<h3 class="title"><?php echo esc_html__('Mission appliquée','superio') ?></h3>
	<div class="inner-list">
		<div class="search-orderby-wrapper flex-middle-sm">
			<div class="search-jobs-applied-form search-applicants-form">
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
			<div class="sort-jobs-applied-form sortby-form">
				<?php
					$orderby_options = apply_filters( 'wp_job_board_pro_my_jobs_orderby', array(
						'menu_order'	=> esc_html__( 'Défaut', 'superio' ),
						'newest' 		=> esc_html__( 'Le plus récent', 'superio' ),
						'oldest'     	=> esc_html__( 'Le plus ancien', 'superio' ),
					) );
					$orderby = isset( $_GET['orderby'] ) ? wp_unslash( $_GET['orderby'] ) : 'newest';
				?>
				<div class="orderby-wrapper flex-middle">
					<span class="text-sort"><?php echo esc_html__('Sort by: ','superio'); ?></span>
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

		<?php if ( !empty($applicants) && !empty($applicants->posts) ) { ?>
			<div class="table-responsive">
			<table class="job-table">
				<thead>
					<tr>
						<th class="job-title"><?php esc_html_e('Titre de la mission', 'superio'); ?></th>
						<th class="job-employer"><?php esc_html_e('Famille', 'superio'); ?></th>
						<th class="job-date"><?php esc_html_e("Date d'application", 'superio'); ?></th>
						<th class="job-status"><?php esc_html_e('Statut', 'superio'); ?></th>
						<th class="job-actions"><?php esc_html_e('Actes', 'superio'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					// Resolve logged-in educator's candidate post ID once
					$current_uid     = get_current_user_id();
					$my_candidate_id = WP_Job_Board_Pro_User::get_candidate_by_user_id( $current_uid );

					foreach ($applicants->posts as $applicant_id) {
						$job_id = get_post_meta($applicant_id, WP_JOB_BOARD_PRO_APPLICANT_PREFIX.'job_id', true);
						$post   = get_post($job_id);
						if ( empty($post) ) continue;

						$author_id   = superio_get_post_author($post->ID);
						$employer_id = WP_Job_Board_Pro_User::get_employer_by_user_id($author_id);
						$category    = get_the_terms( $post->ID, 'job_listing_category' );
						$location    = get_the_terms( $post->ID, 'job_listing_location' );

						// Read the REAL post_status — catches 'completed' set by employer
						$job_status  = get_post_field( 'post_status', $job_id );
						$app_status  = WP_Job_Board_Pro_Applicant::get_post_meta( $applicant_id, 'app_status', true );

						// Only approved/hired candidates see "Complétée" — everyone else sees their real status
						if ( ! in_array( $app_status, array( 'approved', 'hired' ), true ) ) {
							$job_status = $app_status ?: 'pending';
						}

						// Has this educator already reviewed this family?
						$already_reviewed_family = false;
						if ( $employer_id && $job_status === 'completed' ) {
							$existing_count = get_comments( array(
								'post_id' => $employer_id,
								'user_id' => $current_uid,
								'count'   => true,
								'status'  => 'all',
							) );
							$already_reviewed_family = ( $existing_count > 0 );
						}
					?>
						<tr class="job-applied-wrapper">

							<!-- Col 1: Mission title -->
							<td>
								<div class="job-applied">
									<?php if ( has_post_thumbnail($employer_id) ) { ?>
								        <div class="employer-logo">
								            <?php echo get_the_post_thumbnail( $employer_id, 'thumbnail' ); ?>
								        </div>
								    <?php } ?>
								    <div class="job-content">
								    	<div class="title-wrapper">
									        <h2 class="job-title">
									        	<a href="<?php echo esc_url(get_permalink($job_id)); ?>" rel="bookmark"><?php echo get_the_title($job_id); ?></a>
									        </h2>
					                    </div>
					                    <div class="job-metas">
								        	<?php if ( $category ) { ?>
								        		<div class="category-job">
								        			<i class="flaticon-briefcase-1"></i>
										            <?php foreach ($category as $term) { echo '<a href="'.get_term_link($term).'">'.trim($term->name).'</a>'; break; } ?>
									            </div>
									        <?php } ?>
								            <?php if ( $location ) { ?>
								        		<div class="location-job">
								        			<i class="flaticon-location"></i>
										            <?php foreach ($location as $term) { echo '<a href="'.get_term_link($term).'">'.trim($term->name).'</a>'; break; } ?>
									            </div>
									        <?php } ?>
								        </div>
								    </div>
							    </div>
							</td>

							<!-- Col 2: Family -->
							<td class="job-employer-cell">
								<?php if ( $employer_id ) :
									$emp_title    = get_the_title( $employer_id );
									$emp_url      = get_permalink( $employer_id );
									$emp_parts    = explode( ' ', trim( $emp_title ) );
									$emp_initials = strtoupper( substr($emp_parts[0]??'',0,1) . (isset($emp_parts[1]) ? substr($emp_parts[1],0,1) : '') );
								?>
								<div class="job-employer-wrap">
									<div class="employer-avatar-mini"><?php echo esc_html( $emp_initials ); ?></div>
									<a href="<?php echo esc_url( $emp_url ); ?>" class="employer-name-link" title="<?php esc_attr_e( 'Voir le profil de la famille', 'superio' ); ?>">
										<?php echo esc_html( $emp_title ); ?>
									</a>
								</div>
								<?php else : ?>
									<span style="color:#aaa;">—</span>
								<?php endif; ?>
							</td>

							<!-- Col 3: Date -->
							<td><?php echo get_the_time(get_option('date_format'), $applicant_id); ?></td>

							<!-- Col 4: Status — mirrors real job status, adds review CTA when completed -->
							<td>
								<?php if ( $job_status === 'completed' ) : ?>

									<span class="application-status-label completed">
										<?php esc_html_e( 'Complétée', 'superio' ); ?>
									</span>

									<?php if ( $employer_id && ! $already_reviewed_family ) :
										// Link to family profile with query arg to auto-open review form
										$review_url = add_query_arg( 'efj_review_family', '1', get_permalink( $employer_id ) );
									?>
									<br>
									<a href="<?php echo esc_url( $review_url ); ?>" class="efj-rate-family-btn">
										⭐ <?php esc_html_e( 'Évaluer la famille', 'superio' ); ?>
									</a>
									<?php elseif ( $already_reviewed_family ) : ?>
									<br>
									<span class="efj-already-rated">✅ <?php esc_html_e( 'Avis soumis', 'superio' ); ?></span>
									<?php endif; ?>

								<?php else :
									$app_status = WP_Job_Board_Pro_Applicant::get_post_meta($applicant_id, 'app_status', true);
									if ( $app_status == 'rejected' ) {
										echo '<span class="application-status-label rejected">'.esc_html__('Rejetée', 'superio').'</span>';
									} elseif ( $app_status == 'approved' ) {
										echo '<span class="application-status-label approved">'.esc_html__('Approuvée', 'superio').'</span>';
									} else {
										echo '<span class="application-status-label pending">'.esc_html__('En attente', 'superio').'</span>';
									}
								endif; ?>
							</td>

							<!-- Col 5: Actions -->
							<td>
								<div class="action-button">
									<a href="javascript:void(0)" class="btn-remove-job-applied btn-action-icon deleted"
									   data-applicant_id="<?php echo esc_attr($applicant_id); ?>"
									   data-nonce="<?php echo esc_attr(wp_create_nonce( 'wp-job-board-pro-remove-applied-nonce' )); ?>"
									   data-toggle="tooltip" title="<?php esc_attr_e('Remove', 'superio'); ?>">
										<i class="ti-close"></i>
									</a>
									<a class="btn-action-icon" href="<?php echo esc_url(get_permalink($job_id)); ?>"
									   data-toggle="tooltip" title="<?php esc_attr_e('View Job', 'superio'); ?>">
										<i class="ti-eye"></i>
									</a>
								</div>
							</td>

						</tr>
					<?php } ?>
				</tbody>
			</table>
			</div>

			<?php WP_Job_Board_Pro_Mixes::custom_pagination( array(
				'wp_query'      => $applicants,
				'max_num_pages' => $applicants->max_num_pages,
				'prev_text'     => '<i class="ti-arrow-left"></i>',
				'next_text'     => '<i class="ti-arrow-right"></i>',
			)); ?>

		<?php } else { ?>
			<div class="not-found"><?php esc_html_e('No application found.', 'superio'); ?></div>
		<?php } ?>
	</div>
</div>
