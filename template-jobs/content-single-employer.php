<?php
/**
 * Employer Detail Template — Fully Translatable Version
 * Text Domain: efj
 *
 * All visible strings are wrapped with WordPress i18n functions.
 * No logic, security checks, or structural code has been altered.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
global $post;
$eid = $post->ID;

/* ── All meta ───────────────────────────────────────────── */
$phone        = get_post_meta($eid, '_employer_phone', true);
$email        = get_post_meta($eid, '_employer_email', true);
$address      = get_post_meta($eid, '_employer_address', true);
$logement     = get_post_meta($eid, 'logement', true);
$parent_representative     = get_post_meta($eid, 'parent-representative', true);
$second_parent     = get_post_meta($eid, 'second-parent', true);
$other_children     = get_post_meta($eid, 'other-children', true);
$accessibilite= get_post_meta($eid, 'accessibilite', true);
$espace       = get_post_meta($eid, 'espace-amenage', true);
$profil       = get_post_meta($eid, 'profil', true);
$frequence    = get_post_meta($eid, 'frequence', true);
$objectif1    = get_post_meta($eid, 'objectif-1', true);
$objectif2    = get_post_meta($eid, 'objectif-2', true);
$objectif3    = get_post_meta($eid, 'objectif-3', true);
$objectif4    = get_post_meta($eid, 'objectif-4', true);
$coordination = get_post_meta($eid, 'coordination', true);
$budget       = get_post_meta($eid, 'budget-mensuel', true);
$financement  = get_post_meta($eid, 'financement', true);
$paiement     = get_post_meta($eid, 'paiement', true);
$facturation  = get_post_meta($eid, 'facturation', true);
$children     = get_post_meta($eid, '_employer_team_members', true);
$children     = is_array($children) ? $children : [];

/* ── Child History (repeatable group) ──────────────────── */
$child_history = get_post_meta($eid, '_employer_child_history', true);
$child_history = is_array($child_history) ? $child_history : [];

/* Disability */
$has_disability   = get_post_meta($eid, 'has-disability', true);
$disability_types = get_post_meta($eid, 'disability-type', true);
$disability_types = is_array($disability_types) ? $disability_types : (array)maybe_unserialize($disability_types);
$disability_level = get_post_meta($eid, 'disability-level', true);
$autonomy_level   = get_post_meta($eid, 'autonomy-level', true);
$rqth             = get_post_meta($eid, 'rqth', true);

/* Initials for avatar */
$name_raw = get_the_title();
$parts    = explode(' ', trim($name_raw));
$initials = strtoupper(substr($parts[0] ?? '', 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));

/* Reviews — WP comments, last 3 */
$all_reviews = get_comments(['post_id'=>$eid,'status'=>'approve','number'=>-1,'order'=>'DESC']);
$review_count = count($all_reviews);
$reviews = array_slice($all_reviews, 0, 3); // only show last 3

/* Gender label for child */
function ef_child_gender($v){
    if($v==='1') return '👧 ' . __( 'Girl', 'efj' );
    if($v==='2') return '⚧ '  . __( 'Other', 'efj' );
    return '👦 ' . __( 'Boy', 'efj' );
}

/* Star helper */
function ef_emp_stars($r = 0) {
    $r = max(0, min(5, (int)$r));
    $s = '';
    for ($i = 1; $i <= 5; $i++) {
        $s .= ($i <= $r) ? '★' : '☆';
    }
    return $s;
}

/* Timeline dot color cycle — matches the HTML demo */
$tl_dot_colors = ['blue', 'green', 'orange', 'purple', 'blue', 'green', 'orange'];
?>

<?php do_action('wp_job_board_pro_before_job_detail', $eid); ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800&family=DM+Serif+Display&display=swap" rel="stylesheet">

<article id="post-<?php echo esc_attr($eid); ?>" <?php post_class('employer-single-v1'); ?>>
	<header class="ef-topbar">
		<div class="ef-logo">
			        <?php
                        $logo = superio_get_config('media-mobile-logo');
                        $logo_url = '';
                        if ( !empty($logo['id']) ) {
                            $img = wp_get_attachment_image_src($logo['id'], 'full');
                            if ( !empty($img[0]) ) {
                                $logo_url = $img[0];
                            }
                        }
                    ?>
                    <?php if( isset($logo['url']) && !empty($logo['url']) ): ?>
                        <div class="logo">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" >
                                <img src="<?php echo esc_url( $logo['url'] ); ?>" alt="<?php echo esc_attr(get_bloginfo( 'name' )); ?>">
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="logo logo-theme">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" >
                                <img src="<?php echo esc_url_raw( get_template_directory_uri().'/images/logo.svg'); ?>" alt="<?php echo esc_attr(get_bloginfo( 'name' )); ?>">
                            </a>
                        </div>
                    <?php endif; ?>
		</div>
		<div class="ef-spacer"></div>
		<a href="<?php echo site_url('/educfind_premium'); ?>" class="ef-back-btn">
			<?php esc_html_e( '← Back to dashboard', 'efj' ); ?>
		</a>
	</header>

  <div class="employer-content-area <?php echo apply_filters('superio_employer_content_class','container'); ?>">
    <div class="row content-single-candidate">
      <div class="col-xs-12 list-content-candidate col-md-8 ">

        <?php do_action('wp_job_board_pro_before_employer_content', $eid); ?>

        <div class="eff">

        <!-- ══ FICHE HEADER ══════════════════════════════════ -->
        <div class="eff-hdr">
          <div class="eff-av">
            <?php echo esc_html($initials); ?>
            <div class="eff-dot"></div>
          </div>
          <div class="eff-hi">
            <div class="eff-name"><?php the_title(); ?></div>
            <?php if($address): ?>
            <div class="eff-sub"><?php echo esc_html($address); ?></div>
            <?php endif; ?>
            <div class="eff-bdgs">
              <span class="eff-b blue">✔ <?php esc_html_e( 'Verified profile', 'efj' ); ?></span>
              <?php
             if($review_count > 0):
              $total_rating = 0;
              $rated_count  = 0;
              foreach($all_reviews as $r) {
                $rv = intval(get_comment_meta($r->comment_ID, 'REPLACE_WITH_CORRECT_KEY', true));
                if ($rv === 0) $rv = intval(get_comment_meta($r->comment_ID, 'rating', true));
                if ($rv === 0) $rv = intval(get_comment_meta($r->comment_ID, '_rating', true));
                if ($rv >= 1 && $rv <= 5) {
                    $total_rating += $rv;
                    $rated_count++;
                }
              }
              $avg = $rated_count > 0 ? round($total_rating / $rated_count, 1) : 0;
              ?>
              <span class="eff-b green">
                <?php
                /* translators: %s average rating out of 5 */
                printf( esc_html__( 'Rating: %s/5', 'efj' ), esc_html($avg) );
                ?>
              </span>
              <span class="eff-b purple">
                <?php
                /* translators: %d number of reviews */
                printf( esc_html( _n( '%d review received', '%d reviews received', $review_count, 'efj' ) ), $review_count );
                ?>
              </span>
              <?php endif; ?>
              <?php if($budget): ?>
              <span class="eff-b orange">💳 <?php echo esc_html($budget); ?></span>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- ══ STATS STRIP ════════════════════════════════════ -->
        <div class="eff-strip">
          <div class="eff-ss">
            <div class="eff-ssv blue"><?php echo esc_html($review_count ?: '—'); ?></div>
            <div class="eff-ssl">
              <?php
              /* translators: plural label for completed missions */
              echo esc_html( _n( 'Completed mission', 'Completed missions', $review_count, 'efj' ) );
              ?>
            </div>
          </div>
          <?php if($review_count > 0 && isset($avg)): ?>
          <div class="eff-ss">
            <div class="eff-ssv green"><?php echo esc_html($avg); ?>/5</div>
            <div class="eff-ssl"><?php esc_html_e( 'Family rating', 'efj' ); ?></div>
          </div>
          <?php endif; ?>
          <?php if($budget): ?>
          <div class="eff-ss">
            <div class="eff-ssv orange"><?php echo esc_html($budget); ?></div>
            <div class="eff-ssl"><?php esc_html_e( 'Monthly budget', 'efj' ); ?></div>
          </div>
          <?php endif; ?>
          <div class="eff-ss">
            <div class="eff-ssv"><?php echo get_the_date('M. Y'); ?></div>
            <div class="eff-ssl"><?php esc_html_e( 'Member since', 'efj' ); ?></div>
          </div>
        </div>

        <!-- ══ CONTENT GRID ═══════════════════════════════════ -->
        <div class="eff-grid">

          <!-- Child profile (repeatable group) -->
          <?php foreach($children as $i => $child):
            if(empty($child['first_name'])) continue;
            $cname     = trim($child['first_name'].' '.($child['last_name']??''));
            /* translators: %d child age in years */
            $cage      = !empty($child['age']) ? sprintf( __( '%d years old', 'efj' ), (int)$child['age'] ) : '';
            $cgender   = ef_child_gender($child['gender'] ?? '0');
            $cschool   = $child['school_name'] ?? '';
            $cdiag     = $child['diagnosis'] ?? '';
            $csensory  = $child['sensory_profile'] ?? '';
            $cbehavior = $child['behavior'] ?? '';
            $csummary  = $child['summary'] ?? '';
            $cmdph     = $child['MDPH'] ?? '';
            /* translators: %s school name */
            $cschool_label = $cschool ? sprintf( __( 'School: %s', 'efj' ), $cschool ) : '';
          ?>
          <div class="eff-card eff-full">
            <div class="eff-lbl">
              <span class="eff-ic purple">👦</span>
              <?php
              if ( count($children) > 1 ) {
                  /* translators: %d child number index */
                  printf( esc_html__( "Child's profile %d", 'efj' ), $i + 1 );
              } else {
                  esc_html_e( "Child's profile", 'efj' );
              }
              ?>
            </div>
            <div class="eff-child">
              <div class="eff-child-name"><?php echo esc_html($cname); ?></div>
              <div class="eff-child-age">
                <?php echo esc_html(implode(' · ', array_filter([$cage, $cgender, $cschool_label]))); ?>
              </div>

              <?php if($cdiag): ?>
              <div class="eff-cd">
                <span class="eff-cd-icon">🧩</span>
                <div>
                  <strong><?php esc_html_e( 'Diagnosis:', 'efj' ); ?></strong>
                  <?php echo nl2br(esc_html($cdiag)); ?>
                </div>
              </div>
              <?php endif; ?>

              <?php if($csensory): ?>
              <div class="eff-cd">
                <span class="eff-cd-icon">🎧</span>
                <div>
                  <strong><?php esc_html_e( 'Sensory profile:', 'efj' ); ?></strong>
                  <?php echo nl2br(esc_html($csensory)); ?>
                </div>
              </div>
              <?php endif; ?>

              <?php if($cbehavior): ?>
              <div class="eff-cd">
                <span class="eff-cd-icon">⚡</span>
                <div>
                  <strong><?php esc_html_e( 'Behaviour:', 'efj' ); ?></strong>
                  <?php echo nl2br(esc_html($cbehavior)); ?>
                </div>
              </div>
              <?php endif; ?>

              <?php /* Disability from Disability Information tab */ ?>
              <?php if($has_disability === 'on'): ?>
              <div class="eff-cd">
                <span class="eff-cd-icon">🏥</span>
                <div>
                  <strong><?php esc_html_e( 'Disability:', 'efj' ); ?></strong>
                  <?php if(!empty($disability_types)) echo esc_html(implode(', ', array_filter($disability_types))); ?>
                  <?php if($disability_level):
                    /* translators: %s disability level */
                    echo ' · ' . sprintf( esc_html__( 'Level: %s', 'efj' ), esc_html($disability_level) );
                  endif; ?>
                  <?php if($autonomy_level):
                    /* translators: %s autonomy level */
                    echo ' · ' . sprintf( esc_html__( 'Autonomy: %s', 'efj' ), esc_html($autonomy_level) );
                  endif; ?>
                  <?php if($rqth === 'on') echo ' · ' . esc_html__( 'RQTH', 'efj' ); ?>
                </div>
              </div>
              <?php endif; ?>

              <?php if($csummary): ?>
              <div class="eff-cd">
                <span class="eff-cd-icon">🏫</span>
                <div>
                  <strong><?php esc_html_e( 'Follow-up:', 'efj' ); ?></strong>
                  <?php echo nl2br(esc_html($csummary)); ?>
                </div>
              </div>
              <?php endif; ?>

              <?php if($cmdph): ?>
              <div class="eff-cd">
                <span class="eff-cd-icon">📋</span>
                <div>
                  <strong><?php esc_html_e( 'MDPH:', 'efj' ); ?></strong>
                  <?php echo nl2br(esc_html($cmdph)); ?>
                </div>
              </div>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>

          <!-- Family information -->
          <?php if($address || $logement || $accessibilite || $espace || $phone || $email || $parent_representative || $other_children || $second_parent ): ?>
          <div class="eff-card">
            <div class="eff-lbl"><span class="eff-ic blue">🏠</span> <?php esc_html_e( 'Family information', 'efj' ); ?></div>
            <?php if($parent_representative): ?>
            <div class="eff-dr">
              <span class="eff-dk"><?php esc_html_e( 'Primary parent', 'efj' ); ?></span>
              <span class="eff-dv"><?php echo esc_html($parent_representative); ?></span>
            </div>
            <?php endif; ?>
            <?php if($second_parent): ?>
            <div class="eff-dr">
              <span class="eff-dk"><?php esc_html_e( 'Second parent', 'efj' ); ?></span>
              <span class="eff-dv"><?php echo esc_html($second_parent); ?></span>
            </div>
            <?php endif; ?>
            <?php if($other_children): ?>
            <div class="eff-dr">
              <span class="eff-dk"><?php esc_html_e( 'Other children', 'efj' ); ?></span>
              <span class="eff-dv"><?php echo esc_html($other_children); ?></span>
            </div>
            <?php endif; ?>
            <?php if($address): ?>
            <div class="eff-dr">
              <span class="eff-dk"><?php esc_html_e( 'Address', 'efj' ); ?></span>
              <span class="eff-dv"><?php echo esc_html($address); ?></span>
            </div>
            <?php endif; ?>
            <?php if($logement): ?>
            <div class="eff-dr">
              <span class="eff-dk"><?php esc_html_e( 'Housing', 'efj' ); ?></span>
              <span class="eff-dv"><?php echo esc_html($logement); ?></span>
            </div>
            <?php endif; ?>
            <?php if($accessibilite): ?>
            <div class="eff-dr">
              <span class="eff-dk"><?php esc_html_e( 'Accessibility', 'efj' ); ?></span>
              <span class="eff-dv"><?php echo esc_html($accessibilite); ?></span>
            </div>
            <?php endif; ?>
            <?php if($espace): ?>
            <div class="eff-dr">
              <span class="eff-dk"><?php esc_html_e( 'Adapted space', 'efj' ); ?></span>
              <span class="eff-dv"><?php echo esc_html($espace); ?></span>
            </div>
            <?php endif; ?>
            <?php if($phone): ?>
            <div class="eff-dr">
              <span class="eff-dk"><?php esc_html_e( 'Phone', 'efj' ); ?></span>
              <span class="eff-dv"><?php echo esc_html($phone); ?></span>
            </div>
            <?php endif; ?>
          </div>
          <?php endif; ?>

          <!-- What they are looking for -->
          <?php if($profil || $frequence || $objectif1 || $objectif2 || $objectif3 || $objectif4 || $coordination): ?>
          <div class="eff-card">
            <div class="eff-lbl"><span class="eff-ic orange">🎯</span> <?php esc_html_e( 'What they are looking for', 'efj' ); ?></div>
            <?php if($profil): ?>
            <div class="eff-dr">
              <span class="eff-dk"><?php esc_html_e( 'Profile', 'efj' ); ?></span>
              <span class="eff-dv left"><?php echo esc_html($profil); ?></span>
            </div>
            <?php endif; ?>
            <?php if($frequence): ?>
            <div class="eff-dr">
              <span class="eff-dk"><?php esc_html_e( 'Frequency', 'efj' ); ?></span>
              <span class="eff-dv left"><?php echo esc_html($frequence); ?></span>
            </div>
            <?php endif; ?>
            <?php if($objectif1): ?>
            <div class="eff-dr">
              <span class="eff-dk"><?php esc_html_e( 'Objective 1', 'efj' ); ?></span>
              <span class="eff-dv left"><?php echo esc_html($objectif1); ?></span>
            </div>
            <?php endif; ?>
            <?php if($objectif2): ?>
            <div class="eff-dr">
              <span class="eff-dk"><?php esc_html_e( 'Objective 2', 'efj' ); ?></span>
              <span class="eff-dv left"><?php echo esc_html($objectif2); ?></span>
            </div>
            <?php endif; ?>
            <?php if($objectif3): ?>
            <div class="eff-dr">
              <span class="eff-dk"><?php esc_html_e( 'Objective 3', 'efj' ); ?></span>
              <span class="eff-dv left"><?php echo esc_html($objectif3); ?></span>
            </div>
            <?php endif; ?>
            <?php if($objectif4): ?>
            <div class="eff-dr">
              <span class="eff-dk"><?php esc_html_e( 'Objective 4', 'efj' ); ?></span>
              <span class="eff-dv left"><?php echo esc_html($objectif4); ?></span>
            </div>
            <?php endif; ?>
            <?php if($coordination): ?>
            <div class="eff-dr">
              <span class="eff-dk"><?php esc_html_e( 'Coordination', 'efj' ); ?></span>
              <span class="eff-dv left"><?php echo esc_html($coordination); ?></span>
            </div>
            <?php endif; ?>
          </div>
          <?php endif; ?>

          <!-- Budget & funding -->
          <?php if($budget || $financement || $paiement || $facturation): ?>
          <div class="eff-card">
            <div class="eff-lbl"><span class="eff-ic green">💳</span> <?php esc_html_e( 'Budget & funding', 'efj' ); ?></div>
            <?php if($budget): ?>
            <div class="eff-dr">
              <span class="eff-dk"><?php esc_html_e( 'Monthly budget', 'efj' ); ?></span>
              <span class="eff-dv"><?php echo esc_html($budget); ?></span>
            </div>
            <?php endif; ?>
            <?php if($financement): ?>
            <div class="eff-dr">
              <span class="eff-dk"><?php esc_html_e( 'Funding', 'efj' ); ?></span>
              <span class="eff-dv"><?php echo esc_html($financement); ?></span>
            </div>
            <?php endif; ?>
            <?php if($paiement): ?>
            <div class="eff-dr">
              <span class="eff-dk"><?php esc_html_e( 'Payment', 'efj' ); ?></span>
              <span class="eff-dv"><?php echo esc_html($paiement); ?></span>
            </div>
            <?php endif; ?>
            <?php if($facturation): ?>
            <div class="eff-dr">
              <span class="eff-dk"><?php esc_html_e( 'Billing', 'efj' ); ?></span>
              <span class="eff-dv"><?php echo esc_html($facturation); ?></span>
            </div>
            <?php endif; ?>
          </div>
          <?php endif; ?>

           <!-- ══ EducFind History — from _employer_child_history group ══ -->
          <?php
          /* Filter out any empty entries before checking */
          $valid_history = array_filter($child_history, function($item) {
              return !empty($item['titre']) || !empty($item['description']);
          });
          ?>
          <?php if(!empty($valid_history)): ?>
          <div class="eff-card">
            <div class="eff-lbl">
              <span class="eff-ic dark">📜</span>
              <?php esc_html_e( 'EducFind history', 'efj' ); ?>
            </div>

            <?php foreach($valid_history as $hi => $item):
              /* Rotate through dot colours: blue → green → orange → purple → … */
              $dot_color = $tl_dot_colors[ $hi % count($tl_dot_colors) ];

              $h_titre       = trim($item['titre']       ?? '');
              $h_academie    = trim($item['académie']    ?? '');
              $h_annee       = trim($item['année']       ?? '');
              $h_note        = trim($item['note_donnée '] ?? $item['note_donnee'] ?? ''); /* handle space in key */
              $h_description = trim($item['description'] ?? '');

              if(empty($h_titre) && empty($h_description)) continue;
            ?>
            <div class="eff-tl">
              <div class="eff-tld <?php echo esc_attr($dot_color); ?>"></div>
              <div class="eff-tl-body">

                <div class="eff-tlt">
                  <?php if($h_titre): ?>
                  <strong><?php echo esc_html($h_titre); ?></strong>
                  <?php endif; ?>
                  <?php if($h_description): ?>
                  <?php echo $h_titre ? ' — ' . esc_html($h_description) : esc_html($h_description); ?>
                  <?php endif; ?>
                </div>

                <?php if($h_annee || $h_academie || $h_note): ?>
                <div class="eff-tl-date">
                  <?php
                  $meta_parts = array_filter([$h_annee, $h_academie, $h_note]);
                  echo esc_html(implode(' · ', $meta_parts));
                  ?>
                </div>
                <?php endif; ?>

              </div>
            </div>
            <?php endforeach; ?>

          </div>
          <?php endif; ?>

          <!-- Family presentation (post content as bio) -->
          <?php $bio = apply_filters('the_content', get_the_content()); if($bio): ?>
          <div class="eff-card eff-full">
            <div class="eff-lbl"><span class="eff-ic blue">📝</span> <?php esc_html_e( 'Family presentation', 'efj' ); ?></div>
            <div class="eff-bio"><?php echo wp_kses_post($bio); ?></div>
          </div>
          <?php endif; ?>

          <!-- Reviews received by the family -->
          <?php if(!empty($reviews)): ?>
          <div class="eff-card">
            <div class="eff-lbl"><span class="eff-ic orange">⭐</span> <?php esc_html_e( 'Reviews received by the family', 'efj' ); ?></div>
            <?php foreach($reviews as $rev):
              $rating = intval(get_comment_meta($rev->comment_ID, 'rating', true));
              if ($rating === 0) {
                  $rating = intval(get_comment_meta($rev->comment_ID, '_rating', true));
              }
              if ($rating === 0) {
                  $rating = intval(get_comment_meta($rev->comment_ID, 'overall_rating', true));
              }
              $rating = max(0, min(5, $rating));
              $mission = get_comment_meta($rev->comment_ID,'mission',true);
            ?>
            <div class="eff-rev">
              <div class="eff-rev-top">
                <span class="eff-rev-author"><?php echo esc_html($rev->comment_author); ?></span>
                <span class="eff-rev-date"><?php echo esc_html(date_i18n('F Y',strtotime($rev->comment_date))); ?></span>
              </div>
              <div class="eff-rev-stars"><?php echo ef_emp_stars($rating); ?></div>
              <?php if($mission): ?>
              <div class="eff-rev-mission"><?php echo esc_html($mission); ?></div>
              <?php endif; ?>
              <div class="eff-rev-text"><?php echo nl2br(esc_html($rev->comment_content)); ?></div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

          <!-- Required Documents section (show submitted docs) -->
          <?php
          $doc_identity = get_post_meta($eid, 'identitydocument', true);
          $doc_address  = get_post_meta($eid, 'proofofaddress', true);
          $doc_mdph     = get_post_meta($eid, 'mdphnotification', true);
          $doc_pps      = get_post_meta($eid, 'pps-document', true);
          $has_docs     = $doc_identity || $doc_address || $doc_mdph || $doc_pps;
          if($has_docs): ?>
          <div class="eff-card">
            <div class="eff-lbl"><span class="eff-ic green">🔒</span> <?php esc_html_e( 'Submitted documents', 'efj' ); ?></div>
            <?php $docs = [
              ['📄', __( 'Identity card / Passport', 'efj' ),    $doc_identity],
              ['🏠', __( 'Proof of address', 'efj' ),            $doc_address],
              ['📋', __( 'MDPH notification', 'efj' ),           $doc_mdph],
              ['📋', __( 'PPS / PAI', 'efj' ),                   $doc_pps],
            ];
            foreach($docs as $d): if(!$d[2]) continue; ?>
            <div class="eff-dr">
              <span class="eff-dk"><?php echo $d[0] . ' ' . esc_html($d[1]); ?></span>
              <span class="eff-dv" style="color:var(--success);font-weight:600;">✓ <?php esc_html_e( 'Submitted', 'efj' ); ?></span>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

          <!-- Match banner (EducFind AI matching) -->
          <?php
          $match_pct    = get_post_meta($eid, '_match_percent', true);
          $match_name   = get_post_meta($eid, '_match_educator_name', true);
          $match_desc   = get_post_meta($eid, '_match_description', true);
          $match_checks = get_post_meta($eid, '_match_checks', true);
          $match_checks = is_array($match_checks) ? $match_checks : (array)maybe_unserialize($match_checks);
          if($match_pct || $match_name): ?>
          <div class="eff-match eff-full">
            <div class="eff-mscore">
              <div class="eff-mpct"><?php echo esc_html($match_pct ?: '—'); ?><?php if($match_pct) echo '%'; ?></div>
              <div class="eff-mlsm"><?php esc_html_e( 'Match', 'efj' ); ?></div>
            </div>
            <div class="eff-minfo">
              <div class="eff-mtitle">
                <?php echo esc_html( $match_name ?: __( 'Best match', 'efj' ) ); ?>
              </div>
              <?php if($match_desc): ?>
              <div class="eff-mdesc"><?php echo esc_html($match_desc); ?></div>
              <?php else: ?>
              <div class="eff-mdesc">
                <?php esc_html_e( 'Score calculated based on qualifications, specialities, experience, geographic area, availability, rates and verified documents.', 'efj' ); ?>
              </div>
              <?php endif; ?>
              <?php if(!empty($match_checks)): ?>
              <div class="eff-mchecks">
                <?php foreach($match_checks as $mc):
                  $is_warn = strpos($mc,'⚠') !== false;
                ?>
                <span class="eff-mchk <?php echo $is_warn?'warn':''; ?>"><?php echo esc_html($mc); ?></span>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
            </div>
          </div>
          <?php endif; ?>

        </div><!-- /.eff-grid -->
        </div><!-- /.eff -->
        <?php
        // ── EFJ: Gate the review form — only for educators with a completed mission ──
        if ( isset( $_GET['efj_review_family'] ) && is_user_logged_in() && class_exists( 'WP_Job_Board_Pro_User' ) && WP_Job_Board_Pro_User::is_candidate() ) :

            $current_uid = get_current_user_id();
            // Check if already reviewed
            $already_reviewed = get_comments( array(
                'post_id' => $eid,
                'user_id' => $current_uid,
                'count'   => true,
                'status'  => 'all',
            ) );
            if ( $already_reviewed > 0 ) :
        ?>
                <div class="ef-card ef-full" style="margin-top:24px">
                    <div class="eff-lbl">
                        <span class="eff-ic green">⭐</span>
                        <?php esc_html_e( 'Votre avis', 'efj' ); ?>
                    </div>
                    <p style="color:#16a34a; font-weight:500; padding:12px 0;">
                        ✅ <?php esc_html_e( 'Vous avez déjà soumis un avis pour cette famille. Merci !', 'efj' ); ?>
                    </p>
                </div>
            <?php
                elseif ( efj_candidate_can_review_family( $eid ) ) :
                    comments_template();
                else :
            ?>
                <div class="ef-card ef-full" style="margin-top:24px">
                    <div class="eff-lbl">
                        <span class="eff-ic orange">⭐</span>
                        <?php esc_html_e( 'Laisser un avis', 'efj' ); ?>
                    </div>
                    <p style="color:#b45309; padding:12px 0;">
                        <?php esc_html_e( 'Vous ne pouvez laisser un avis que si vous avez effectué une mission complétée avec cette famille.', 'efj' ); ?>
                    </p>
                </div>
        <?php
            endif;

        endif;
        ?>

        <?php do_action('wp_job_board_pro_after_employer_content', $eid); ?>

      </div><!-- /.list-content-candidate -->

      <?php if ( is_active_sidebar( 'employer-single-sidebar' ) ): ?>
				<div class="col-md-4 col-xs-12 sidebar-job">
			   		<?php dynamic_sidebar( 'employer-single-sidebar' ); ?>
			   	</div>
		   	<?php endif; ?>

    </div><!-- /.row -->
  </div><!-- /.employer-content-area -->

</article>

<?php do_action('wp_job_board_pro_after_job_detail', $eid); ?>