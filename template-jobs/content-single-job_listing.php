<?php
/**
 * Job Detail Template — Fully Translatable Version
 * Text Domain: efj
 *
 * All visible strings are wrapped with WordPress i18n functions.
 * No logic, security checks, or structural code has been altered.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
global $post, $job_preview;

// In preview context, $job_id is passed by the preview template
if ( ! empty( $job_preview ) && isset( $job_id ) ) {
    $jid = (int) $job_id;
} else {
    $jid = $post->ID;
}

/* ══════════════════════════════════════════════════════════════
   JOB META
══════════════════════════════════════════════════════════════ */
$job_address      = get_post_meta($jid, '_job_address', true);
$map_address      = get_post_meta($jid, '_job_map_location', true);
$map_addr_str     = is_array($map_address) ? ($map_address['address'] ?? '') : $map_address;
$frequence        = get_post_meta($jid, 'frequence', true);
$duree_seance     = get_post_meta($jid, 'duree-seance', true);
$slots            = get_post_meta($jid, '_job_slots', true);
$slots            = is_array($slots) ? $slots : [];
$alternative      = get_post_meta($jid, 'alternative', true);
$duree_mission    = get_post_meta($jid, 'duree-mission', true);
$disorders        = get_post_meta($jid, 'disorders', true);
$their_passions   = get_post_meta($jid, 'their-passions', true);
$their_passions   = is_array($their_passions) ? $their_passions : (array)maybe_unserialize($their_passions);
$what_they_enjoy  = get_post_meta($jid, 'what-they-enjoy', true);
$what_they_enjoy  = is_array($what_they_enjoy) ? $what_they_enjoy : (array)maybe_unserialize($what_they_enjoy);
$job_salary       = get_post_meta($jid, '_job_salary', true);
$job_max_salary   = get_post_meta($jid, '_job_max_salary', true);
$salary_type      = get_post_meta($jid, '_job_salary_type', true);
$job_qualification= get_post_meta($jid, '_job_qualification', true);
$career_level     = get_post_meta($jid, '_job_career_level', true);
$expiry           = get_post_meta($jid, '_job_expiry_date', true);
$deadline         = get_post_meta($jid, '_job_application_deadline_date', true);
$gender           = get_post_meta($jid, '_job_gender', true);
$featured         = get_post_meta($jid, '_job_featured', true);
$urgent           = get_post_meta($jid, '_job_urgent', true);
$filled           = get_post_meta($jid, '_job_filled', true);

$employer_id      = get_post_meta($jid, '_job_employer_posted_by', true);
$budget           = $employer_id ? get_post_meta($employer_id, 'budget-mensuel', true) : '';
$paiement         = $employer_id ? get_post_meta($employer_id, 'paiement', true) : '';
$facturation      = $employer_id ? get_post_meta($employer_id, 'facturation', true) : '';

$skills_required  = get_post_meta($jid, 'mandatory', true);
$skills_desired   = get_post_meta($jid, 'desired', true);
$ideal_profile    = get_post_meta($jid, 'idealprofile', true);

$skills_required_array = !empty($skills_required)
    ? array_map('trim', explode(',', $skills_required)) : [];
$skills_desired_array  = !empty($skills_desired)
    ? array_map('trim', explode(',', $skills_desired))  : [];

/* ── Build job data array for scoring ────────────────────── */
$job_data = [
    'skills_required' => $skills_required_array,
    'skills_desired'  => $skills_desired_array,
    'salary'          => $job_salary,
    'max_salary'      => $job_max_salary,
    'budget'          => $budget,
    'slots'           => $slots,
    'gender'          => $gender,
    'qualification'   => $job_qualification,
];

$best = efj_get_best_match( $job_data, $jid );

/* ── Build display values ────────────────────────────────── */
$match_pct    = $best ? $best['score'] : null;
/* translators: %s best-matched candidate name */
$match_name   = $best ? $best['name'] . ' — ' . __( 'Best match', 'efj' ) : null;
$match_desc   = __( 'Score automatically calculated based on skills, specialities, experience, availability, rates and verified documents.', 'efj' );
$match_checks = $best ? array_map(fn($c) => $c[0]==='warn' ? '⚠️ '.ltrim($c[1],'⚠️ ') : $c[1], $best['checks']) : [];

/* ══════════════════════════════════════════════════════════════
   OTHER DISPLAY VARS
══════════════════════════════════════════════════════════════ */
$status_label = '🔵 ' . __( 'Matching in progress', 'efj' );
$status_class = 'efj-status-matching';
if ($filled === 'on') {
    $status_label = '✅ ' . __( 'Position filled', 'efj' );
    $status_class = 'efj-status-filled';
} elseif ($urgent === 'on') {
    $status_label = '🔴 ' . __( 'Urgent', 'efj' );
    $status_class = 'efj-status-urgent';
}

$slots_str = '';
if (!empty($slots)) {
    $parts = [];
    foreach ($slots as $s) {
        if (!empty($s['title'])) {
            $parts[] = esc_html($s['title']) . (!empty($s['timing']) ? ' '.esc_html($s['timing']) : '');
        }
    }
    $slots_str = implode(' · ', $parts);
}

$salary_display = '';
if ($job_salary && $job_max_salary) $salary_display = $job_salary.'–'.$job_max_salary.' €/h TTC';
elseif ($job_salary)                $salary_display = $job_salary.' €';

$parts_t  = explode(' ', trim(get_the_title()));
$initials = strtoupper(substr($parts_t[0]??'',0,1).(isset($parts_t[1])?substr($parts_t[1],0,1):''));
$employer_name = $employer_id ? get_the_title($employer_id) : '';
global $job_preview;
$is_preview = !empty($job_preview);
?>

<?php do_action('wp_job_board_pro_before_job_detail', $jid); ?>

<?php if ( ! $is_preview ) : ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800&family=DM+Serif+Display&display=swap" rel="stylesheet">
<?php endif; ?>

<article id="post-<?php the_ID(); ?>" <?php post_class('job-single-v1'); ?>>
<?php if ( ! $is_preview ) : ?>
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
 <?php endif; ?>

  <div class="job-content-area <?php echo apply_filters('superio_job_content_class','container'); ?>">
    <div class="row content-job-detail">
      <div class="list-content-job col-xs-12 col-md-12">
        <?php do_action('wp_job_board_pro_before_job_content', $jid); ?>

        <div class="efj">

          <!-- HEADER -->
          <div class="efj-hdr">
            <div class="efj-av">📋</div>
            <div class="efj-hi">
              <div class="efj-name"><?php the_title(); ?></div>
              <div class="efj-sub"><?php
                $sub = [];
                /* translators: %s employer/company name */
                if ($employer_name) $sub[] = sprintf( esc_html__( 'Posted by %s', 'efj' ), esc_html($employer_name) );
                if ($job_address)   $sub[] = esc_html($job_address);
                elseif($map_addr_str) $sub[] = esc_html($map_addr_str);
                echo implode(' · ', $sub);
              ?></div>
              <div class="efj-bdgs">
                <span class="efj-pill <?php echo esc_attr($status_class); ?>"><?php echo esc_html( $status_label ); ?></span>
                <?php if($urgent==='on'): ?><span class="efj-b red">🔴 <?php esc_html_e( 'Urgent', 'efj' ); ?></span><?php endif; ?>
                <?php if($featured==='on'): ?><span class="efj-b purple">★ <?php esc_html_e( 'Featured mission', 'efj' ); ?></span><?php endif; ?>
                <?php $app_count=get_post_meta($jid,'_job_applications_count',true);
                if($app_count): ?>
                  <span class="efj-b purple">
                    <?php
                    /* translators: %d number of applications */
                    printf( esc_html( _n( '%d application', '%d applications', (int)$app_count, 'efj' ) ), (int)$app_count );
                    ?>
                  </span>
                <?php endif; ?>
                <span class="efj-b dark">
                  <?php
                  /* translators: %s publication date */
                  printf( esc_html__( 'Published on %s', 'efj' ), get_the_date('d/m/Y') );
                  ?>
                </span>
              </div>
            </div>
            <?php if ( ! $is_preview ) : ?>
            <div class="efj-hdr-actions">
                <?php WP_Job_Board_Pro_Job_Listing::display_apply_job_btn($post->ID); ?>
                <?php WP_Job_Board_Pro_Job_Listing::display_shortlist_btn($post->ID); ?>
            </div>
            <?php endif; ?>
          </div>

          <!-- STATS STRIP -->
          <div class="efj-strip">
            <?php if($frequence): ?>
            <div class="efj-ss">
              <div class="efj-ssv blue"><?php echo esc_html($frequence); ?></div>
              <div class="efj-ssl"><?php esc_html_e( 'Sessions / week', 'efj' ); ?></div>
            </div>
            <?php endif; ?>
            <?php if($duree_mission): ?>
            <div class="efj-ss">
              <div class="efj-ssv green"><?php preg_match('/\d+\s*\w+/',$duree_mission,$m); echo esc_html($m[0]??$duree_mission); ?></div>
              <div class="efj-ssl"><?php esc_html_e( 'Minimum duration', 'efj' ); ?></div>
            </div>
            <?php endif; ?>
            <?php if($budget): ?>
            <div class="efj-ss">
              <div class="efj-ssv orange"><?php echo esc_html($budget); ?></div>
              <div class="efj-ssl"><?php esc_html_e( 'Monthly budget', 'efj' ); ?></div>
            </div>
            <?php endif; ?>
            <?php if($salary_display): ?>
            <div class="efj-ss">
              <div class="efj-ssv purple"><?php echo esc_html($salary_display); ?></div>
              <div class="efj-ssl"><?php esc_html_e( 'Accepted rate', 'efj' ); ?></div>
            </div>
            <?php endif; ?>
          </div>

          <!-- GRID -->
          <div class="efj-grid">

           <?php
          global $job_preview;
          if ( empty( $job_preview ) ) {
              // Normal single job view — run full content filters
              $bio = apply_filters( 'the_content', get_the_content() );
          } else {
              // Preview mode — get description from job meta, not post_content
              // (post_content on the submission page is the shortcode, not the job description)
              $bio = get_post_meta( $jid, '_job_description', true );
              if ( empty( $bio ) ) {
                  // fallback: try the actual job post's post_content directly
                  $job_post = get_post( $jid );
                  $bio = $job_post ? wpautop( wp_kses_post( $job_post->post_content ) ) : '';
              } else {
                  $bio = wpautop( wp_kses_post( $bio ) );
              }
          }
          if ( $bio ) : ?>
          <div class="efj-card efj-full">
            <div class="efj-lbl"><span class="efj-ic blue">📝</span> <?php esc_html_e( 'Mission description', 'efj' ); ?></div>
            <div class="efj-bio"><?php echo wp_kses_post( $bio ); ?></div>
          </div>
          <?php endif; ?>

            <!-- Practical details -->
            <div class="efj-card">
              <div class="efj-lbl"><span class="efj-ic blue">📌</span> <?php esc_html_e( 'Practical details', 'efj' ); ?></div>
              <?php $location=$job_address?:$map_addr_str; if($location): ?>
              <div class="efj-dr">
                <span class="efj-dk"><?php esc_html_e( 'Location', 'efj' ); ?></span>
                <span class="efj-dv"><?php echo esc_html($location); ?></span>
              </div>
              <?php endif; if($frequence): ?>
              <div class="efj-dr">
                <span class="efj-dk"><?php esc_html_e( 'Frequency', 'efj' ); ?></span>
                <span class="efj-dv"><?php echo esc_html($frequence); ?></span>
              </div>
              <?php endif; if($duree_seance): ?>
              <div class="efj-dr">
                <span class="efj-dk"><?php esc_html_e( 'Session duration', 'efj' ); ?></span>
                <span class="efj-dv"><?php echo esc_html($duree_seance); ?></span>
              </div>
              <?php endif; if($slots_str): ?>
              <div class="efj-dr">
                <span class="efj-dk"><?php esc_html_e( 'Time slots', 'efj' ); ?></span>
                <span class="efj-dv"><?php echo $slots_str; ?></span>
              </div>
              <?php endif; if($alternative): ?>
              <div class="efj-dr">
                <span class="efj-dk"><?php esc_html_e( 'Alternative', 'efj' ); ?></span>
                <span class="efj-dv"><?php echo esc_html($alternative); ?></span>
              </div>
              <?php endif; if($duree_mission): ?>
              <div class="efj-dr">
                <span class="efj-dk"><?php esc_html_e( 'Mission duration', 'efj' ); ?></span>
                <span class="efj-dv"><?php echo esc_html($duree_mission); ?></span>
              </div>
              <?php endif; if($disorders): ?>
              <div class="efj-dr">
                <span class="efj-dk"><?php esc_html_e( 'Desired start', 'efj' ); ?></span>
                <span class="efj-dv"><?php echo esc_html($disorders); ?></span>
              </div>
              <?php endif; if($expiry): ?>
              <div class="efj-dr">
                <span class="efj-dk"><?php esc_html_e( 'Expiry', 'efj' ); ?></span>
                <span class="efj-dv"><?php echo esc_html(date_i18n('j F Y',strtotime($expiry))); ?></span>
              </div>
              <?php endif; if($gender && $gender!=='Both'): ?>
              <div class="efj-dr">
                <span class="efj-dk"><?php esc_html_e( 'Educator gender', 'efj' ); ?></span>
                <span class="efj-dv"><?php echo esc_html($gender); ?></span>
              </div>
              <?php endif; ?>
            </div>

            <!-- Budget & payment -->
            <div class="efj-card">
              <div class="efj-lbl"><span class="efj-ic green">💰</span> <?php esc_html_e( 'Budget & payment', 'efj' ); ?></div>
              <?php if($salary_display): ?>
              <div class="efj-dr">
                <span class="efj-dk"><?php esc_html_e( 'Hourly rate', 'efj' ); ?></span>
                <span class="efj-dv"><?php echo esc_html($salary_display); ?></span>
              </div>
              <?php endif; if($budget): ?>
              <div class="efj-dr">
                <span class="efj-dk"><?php esc_html_e( 'Monthly budget', 'efj' ); ?></span>
                <span class="efj-dv"><?php echo esc_html($budget); ?></span>
              </div>
              <?php endif; if($paiement): ?>
              <div class="efj-dr">
                <span class="efj-dk"><?php esc_html_e( 'Payment', 'efj' ); ?></span>
                <span class="efj-dv"><?php echo esc_html($paiement); ?></span>
              </div>
              <?php endif; if($facturation): ?>
              <div class="efj-dr">
                <span class="efj-dk"><?php esc_html_e( 'Billing', 'efj' ); ?></span>
                <span class="efj-dv"><?php echo esc_html($facturation); ?></span>
              </div>
              <?php endif; if($salary_type): ?>
              <div class="efj-dr">
                <span class="efj-dk"><?php esc_html_e( 'Type', 'efj' ); ?></span>
                <span class="efj-dv"><?php echo esc_html(ucfirst($salary_type)); ?></span>
              </div>
              <?php endif; if($deadline): ?>
              <div class="efj-dr">
                <span class="efj-dk"><?php esc_html_e( 'Deadline', 'efj' ); ?></span>
                <span class="efj-dv"><?php echo esc_html(date_i18n('j F Y',strtotime($deadline))); ?></span>
              </div>
              <?php endif; ?>
            </div>

            <!-- Required skills -->
            <?php if(!empty($skills_required_array)||!empty($skills_desired_array)||$job_qualification||$career_level): ?>
            <div class="efj-card">
              <div class="efj-lbl"><span class="efj-ic red">✅</span> <?php esc_html_e( 'Required skills', 'efj' ); ?></div>
              <?php if(!empty($skills_required_array)): ?>
              <div style="margin-bottom:16px">
                <div class="efj-dk" style="margin-bottom:8px"><?php esc_html_e( 'Mandatory', 'efj' ); ?></div>
                <div class="efj-tags">
                  <?php foreach($skills_required_array as $s): ?>
                    <span class="efj-tag"><?php echo esc_html($s); ?></span>
                  <?php endforeach; ?>
                </div>
              </div>
              <?php elseif($job_qualification||$career_level): ?>
              <div style="margin-bottom:16px">
                <div class="efj-dk" style="margin-bottom:8px"><?php esc_html_e( 'Required qualification', 'efj' ); ?></div>
                <div class="efj-tags">
                  <?php if($job_qualification): ?><span class="efj-tag"><?php echo esc_html($job_qualification); ?></span><?php endif; ?>
                  <?php if($career_level): ?><span class="efj-tag"><?php echo esc_html($career_level); ?></span><?php endif; ?>
                </div>
              </div>
              <?php endif; ?>
              <?php if(!empty($skills_desired_array)): ?>
              <div>
                <div class="efj-dk" style="margin-bottom:8px"><?php esc_html_e( 'Desired', 'efj' ); ?></div>
                <div class="efj-tags">
                  <?php foreach($skills_desired_array as $s): ?>
                    <span class="efj-tag orange"><?php echo esc_html($s); ?></span>
                  <?php endforeach; ?>
                </div>
              </div>
              <?php endif; ?>
              <?php $job_cats=get_the_terms($jid,'job_listing_category');
              if(!empty($job_cats)&&!is_wp_error($job_cats)): ?>
              <div style="margin-top:14px">
                <div class="efj-dk" style="margin-bottom:8px"><?php esc_html_e( 'Categories', 'efj' ); ?></div>
                <div class="efj-tags">
                  <?php foreach($job_cats as $cat): ?>
                    <span class="efj-tag purple"><?php echo esc_html($cat->name); ?></span>
                  <?php endforeach; ?>
                </div>
              </div>
              <?php endif; ?>
              <?php if(!empty(array_filter($their_passions))): ?>
              <div style="margin-top:14px">
                <div class="efj-dk" style="margin-bottom:8px"><?php esc_html_e( "Child's passions", 'efj' ); ?></div>
                <div class="efj-tags">
                  <?php foreach(array_filter($their_passions) as $p): ?>
                    <span class="efj-tag green"><?php echo esc_html($p); ?></span>
                  <?php endforeach; ?>
                </div>
              </div>
              <?php endif; ?>
              <?php if(!empty(array_filter($what_they_enjoy))): ?>
              <div style="margin-top:14px">
                <div class="efj-dk" style="margin-bottom:8px"><?php esc_html_e( 'What they enjoy', 'efj' ); ?></div>
                <div class="efj-tags">
                  <?php foreach(array_filter($what_they_enjoy) as $w): ?>
                    <span class="efj-tag"><?php echo esc_html($w); ?></span>
                  <?php endforeach; ?>
                </div>
              </div>
              <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Ideal profile -->
            <?php if($ideal_profile): ?>
            <div class="efj-card efj-full">
              <div class="efj-lbl"><span class="efj-ic purple">🎯</span> <?php esc_html_e( 'Ideal profile sought', 'efj' ); ?></div>
              <div class="efj-bio"><?php echo nl2br(esc_html($ideal_profile)); ?></div>
            </div>
            <?php endif; ?>

            <!-- ══ SCORE BREAKDOWN CARD ══════════════════════ -->
            <?php if($best): ?>
            <div class="efj-card efj-full efj-scorebar">
              <div class="efj-lbl">
                <span class="efj-ic blue">📊</span>
                <?php
                /* translators: %s best candidate name */
                printf( esc_html__( 'Compatibility score breakdown — %s', 'efj' ), esc_html($best['name']) );
                ?>
              </div>

              <?php
              // Rebuild per-dimension scores for display
              $bst = $best;
              $cid_b = $bst['cid'];

              $b_qual = get_post_meta($cid_b,'_candidate_qualification',true);
              $b_qual = array_filter(is_array($b_qual)?$b_qual:(array)maybe_unserialize($b_qual));
              $b_edu  = get_post_meta($cid_b,'_candidate_education',true);
              $b_edu  = is_array($b_edu)?$b_edu:[];
              $b_edu_text = '';
              foreach($b_edu as $e){ $b_edu_text .= ' '.($e['title']??'').' '.($e['academy']??''); }
              $b_all_text = $b_edu_text.' '.implode(' ',$b_qual);

              $rh = 0; foreach($skills_required_array as $sk){ if(efj_contains($b_all_text,$sk)) $rh++; }
              $rc = count($skills_required_array);
              $dim_dip = $rc>0 ? round(($rh/$rc)*20):10;

              $sh=0; $all_sk=array_merge($skills_required_array,$skills_desired_array);
              foreach($b_qual as $q){ foreach($all_sk as $s){ if(efj_contains($q,$s)||efj_contains($s,$q)){$sh++;break;} } }
              $dim_spec = min(20,round(($sh/max(1,count($all_sk)))*20));

              $b_exp_time = get_post_meta($cid_b,'_candidate_experience_time',true);
              preg_match('/(\d+)/',$b_exp_time??'',$em2); $ey=isset($em2[1])?(int)$em2[1]:0;
              $dim_exp = min(15, $ey*3);

              $b_sal = get_post_meta($cid_b,'_candidate_salary',true);
              $c_r   = efj_first_num($b_sal??'');
              $j_mn  = efj_first_num($job_salary??'');
              $j_mx  = efj_first_num($job_max_salary??'');
              if($c_r!==null&&$j_mn!==null&&$j_mx!==null){
                  $dim_rate=$c_r>=$j_mn&&$c_r<=$j_mx?20:($c_r<=$j_mx*1.1?12:4);
              } else { $dim_rate=10; }

              $b_days=['lundi'=>get_post_meta($cid_b,'lundi-hebdomadaires',true),'mardi'=>get_post_meta($cid_b,'mardi-hebdomadaires',true),'mercredi'=>get_post_meta($cid_b,'mercredi-hebdomadaires',true),'jeudi'=>get_post_meta($cid_b,'jeudi-hebdomadaires',true),'vendredi'=>get_post_meta($cid_b,'vendredi-hebdomadaires',true),'samedi'=>get_post_meta($cid_b,'samedi-hebdomadaires',true)];
              $b_avail=[];
              foreach($b_days as $dk=>$dv){ $v=efj_norm($dv??''); $b_avail[$dk]=$dv&&!(strpos($v,'non')!==false||strpos($v,'pris')!==false||strpos($v,'occup')!==false||strpos($v,'bcba')!==false||strpos($v,'admin')!==false); }
              $slt=$slots; $slhits=0; $sltot=count($slt);
              if($sltot>0){ foreach($slt as $sl){ $dn=efj_norm($sl['title']??''); foreach($b_avail as $dk=>$dv){ if(efj_norm($dk)===$dn&&$dv){$slhits++;break;} } } $dim_dispo=round(($slhits/max(1,$sltot))*15); }
              else{ $dim_dispo=count(array_filter($b_avail))>=3?15:(count(array_filter($b_avail))>=1?8:0); }

              $b_has_docs=false; foreach($b_edu as $e){ if(!empty($e['_candidate__documents'])){$b_has_docs=true;break;} }
              $dim_docs=$b_has_docs?5:0;

              $b_langs=get_post_meta($cid_b,'_candidate_languages',true);
              $b_langs=array_filter(is_array($b_langs)?$b_langs:(array)maybe_unserialize($b_langs));
              $dim_lang=count($b_langs)>=2?5:(count($b_langs)===1?2:0);

              $dims = [
                  [ __( 'Degrees & qualifications', 'efj' ), $dim_dip,  20, 'blue'   ],
                  [ __( 'Specialities',             'efj' ), $dim_spec, 20, 'purple' ],
                  [ __( 'Experience',               'efj' ), $dim_exp,  15, 'green'  ],
                  [ __( 'Rate / budget',            'efj' ), $dim_rate, 20, 'orange' ],
                  [ __( 'Availability',             'efj' ), $dim_dispo,15, 'blue'   ],
                  [ __( 'Verified documents',       'efj' ), $dim_docs,  5, 'green'  ],
                  [ __( 'Languages',                'efj' ), $dim_lang,  5, 'purple' ],
              ];
              ?>
              <?php foreach($dims as $d): ?>
              <div class="efj-sb-row">
                <div class="efj-sb-label"><?php echo esc_html($d[0]); ?></div>
                <div class="efj-sb-track">
                  <div class="efj-sb-fill <?php echo esc_attr($d[3]); ?>"
                       style="width:<?php echo round(($d[1]/max(1,$d[2]))*100); ?>%"></div>
                </div>
                <div class="efj-sb-pts"><?php echo $d[1]; ?>/<?php echo $d[2]; ?></div>
              </div>
              <?php endforeach; ?>
            </div>

            <!-- ══ MATCH BANNER ══════════════════════════════ -->
            <div class="efj-match efj-full" id="efj-match-banner">

              <!-- Animated ring score -->
              <div class="efj-mscore">
                <?php
                $r   = 45; // radius
                $cx  = 55; $cy = 55;
                $circ = 2 * M_PI * $r;
                $offset = $circ - ($match_pct / 100) * $circ;
                ?>
                <svg viewBox="0 0 110 110">
                  <circle class="efj-mscore-track" cx="<?php echo $cx; ?>" cy="<?php echo $cy; ?>" r="<?php echo $r; ?>"/>
                  <circle class="efj-mscore-fill"
                          cx="<?php echo $cx; ?>" cy="<?php echo $cy; ?>" r="<?php echo $r; ?>"
                          stroke-dasharray="<?php echo $circ; ?>"
                          stroke-dashoffset="<?php echo $circ; ?>"
                          data-target-offset="<?php echo $offset; ?>"
                          id="efj-ring"/>
                </svg>
                <div class="efj-mscore-inner">
                  <div class="efj-mpct" id="efj-pct-counter">0%</div>
                  <div class="efj-mlsm"><?php esc_html_e( 'Match', 'efj' ); ?></div>
                </div>
              </div>
              <div class="efj-minfo">
                <div class="efj-mtitle"><?php echo esc_html($match_name); ?></div>
                <div class="efj-mdesc"><?php echo esc_html($match_desc); ?></div>
                <div class="efj-mchecks">
                  <?php foreach($match_checks as $mc):
                    $is_warn = strpos($mc,'⚠') !== false;
                  ?>
                  <span class="efj-mchk <?php echo $is_warn?'warn':''; ?>"><?php echo esc_html($mc); ?></span>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
            <?php endif; ?>
            <?php
          /**
           * ── APPLY BUTTON ─────────────────────────────────────────────
           * Paste this block inside content-job-detail.php, just before
           *   </div><!-- /.efj-grid -->
           *
           * Logic:
           *   • Hidden if job is already filled (_job_filled = 'on')
           *   • Hidden if user is not logged in (shows a "login to apply" link)
           *   • Hidden if current user is not a candidate
           *   • Shows "✓ Applied" (disabled) if the candidate already applied
           *   • Otherwise shows the active Apply button
           */
          $show_apply   = false;
          $already_applied = false;
          $apply_label  = '';
          $apply_disabled = false;

          if ( $filled !== 'on' ) {
              if ( ! is_user_logged_in() ) {
                  // Not logged in – show a login nudge
                  $show_apply  = true;
                  $apply_label = __( 'Log in to apply', 'efj' );
                  $apply_disabled = true;
              } else {
                  $current_uid  = get_current_user_id();
                  $current_cid  = WP_Job_Board_Pro_User::get_candidate_by_user_id( $current_uid );

                  if ( $current_cid ) {
                      $show_apply = true;

                      // Check for existing application
                      $already = get_posts( [
                          'post_type'        => 'job_applicant',
                          'post_status'      => 'publish',
                          'posts_per_page'   => 1,
                          'fields'           => 'ids',
                          'suppress_filters' => true,
                          'meta_query'       => [
                              'relation' => 'AND',
                              [ 'key' => WP_JOB_BOARD_PRO_APPLICANT_PREFIX . 'candidate_id', 'value' => $current_cid ],
                              [ 'key' => WP_JOB_BOARD_PRO_APPLICANT_PREFIX . 'job_id',       'value' => $jid ],
                          ],
                      ] );

                      if ( ! empty( $already ) ) {
                          $already_applied = true;
                          $apply_disabled  = true;
                          $apply_label     = __( '✓ Already applied', 'efj' );
                      } else {
                          $apply_label = __( 'Apply for this mission', 'efj' );
                      }
                  }
                  // If logged in but not a candidate, the button stays hidden ($show_apply = false)
              }
          }
          ?>
          </div><!-- /.efj-grid -->
        </div><!-- /.efj -->

        <?php do_action('wp_job_board_pro_after_job_content', $jid); ?>
      </div>
    </div>
  </div>
</article>
<?php do_action('wp_job_board_pro_after_job_detail', $jid); ?>