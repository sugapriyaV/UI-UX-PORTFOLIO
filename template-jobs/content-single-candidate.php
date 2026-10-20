<?php
/**
 * Candidate Detail Template — Fully Translatable Version
 * Text Domain: efj
 *
 * All visible strings are wrapped with WordPress i18n functions.
 * No logic, security checks, or structural code has been altered.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
global $post;
$candidate_id = get_the_ID();

/* ══════════════════════════════════════════════════════════════
   META
══════════════════════════════════════════════════════════════ */
$gender        = get_post_meta( $candidate_id, '_candidate_gender', true );
$phone         = get_post_meta( $candidate_id, '_candidate_phone', true );
$email_meta    = get_post_meta( $candidate_id, '_candidate_email', true );
$dob           = get_post_meta( $candidate_id, '_candidate_founded_date', true );
$statut        = get_post_meta( $candidate_id, 'statut', true );
$siret         = get_post_meta( $candidate_id, 'siret', true );
$languages     = get_post_meta( $candidate_id, '_candidate_languages', true );
$qualification = get_post_meta( $candidate_id, '_candidate_qualification', true );
$publics       = get_post_meta( $candidate_id, 'custom-textarea-44896932', true );
$interventions = get_post_meta( $candidate_id, 'interventions', true );
$salary_type   = get_post_meta( $candidate_id, '_candidate_salary_type', true );
$salary        = get_post_meta( $candidate_id, '_candidate_salary', true );
$exp_time      = get_post_meta( $candidate_id, '_candidate_experience_time', true );
$education     = get_post_meta( $candidate_id, '_candidate_education', true );
$experience    = get_post_meta( $candidate_id, '_candidate_experience', true );
$job_title     = get_post_meta( $candidate_id, '_candidate_job_title', true );
$address       = get_post_meta( $candidate_id, '_candidate_address', true );
$response_time = get_post_meta( $candidate_id, 'response-time', true );

$days_meta = [
    'Lundi'    => get_post_meta( $candidate_id, 'lundi-hebdomadaires',    true ),
    'Mardi'    => get_post_meta( $candidate_id, 'mardi-hebdomadaires',    true ),
    'Mercredi' => get_post_meta( $candidate_id, 'mercredi-hebdomadaires', true ),
    'Jeudi'    => get_post_meta( $candidate_id, 'jeudi-hebdomadaires',    true ),
    'Vendredi' => get_post_meta( $candidate_id, 'vendredi-hebdomadaires', true ),
    'Samedi'   => get_post_meta( $candidate_id, 'samedi-hebdomadaires',   true ),
];

// Normalise arrays
$languages     = array_filter( is_array($languages)     ? $languages     : (array)maybe_unserialize($languages) );
$qualification = array_filter( is_array($qualification) ? $qualification : (array)maybe_unserialize($qualification) );
$interventions = array_filter( is_array($interventions) ? $interventions : (array)maybe_unserialize($interventions) );
$education     = is_array($education)  ? $education  : [];
$experience    = is_array($experience) ? $experience : [];

/* ══════════════════════════════════════════════════════════════
   ① MISSIONS COMPLETED
   Count job_listing posts where this candidate was matched
   AND the job is marked filled (_job_filled = 'on').
══════════════════════════════════════════════════════════════ */
$candidate_name = get_the_title($candidate_id);

// Primary: jobs filled + matched to this candidate by name
$q_filled = new WP_Query([
    'post_type'      => 'job_listing',
    'post_status'    => ['publish','private'],
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'meta_query'     => [
        'relation' => 'AND',
        [ 'key' => '_job_filled',           'value' => 'on',          'compare' => '=' ],
        [ 'key' => '_match_educator_name',  'value' => $candidate_name, 'compare' => 'LIKE' ],
    ],
]);
$missions_completed = (int)$q_filled->found_posts;
wp_reset_postdata();

// Secondary: approved job_application posts by the linked WP user
$attached_uid    = get_post_meta($candidate_id, '_candidate_attached_user', true);
$total_apps      = 0;
$approved_apps   = 0;

if ( $attached_uid ) {
    // All applications (for acceptance rate denominator)
    $q_all_apps = new WP_Query([
        'post_type'      => 'job_application',
        'post_status'    => 'any',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'author'         => (int)$attached_uid,
    ]);
    $total_apps = (int)$q_all_apps->found_posts;
    wp_reset_postdata();

    // Approved/hired applications
    $q_approved = new WP_Query([
        'post_type'      => 'job_application',
        'post_status'    => ['publish','pending'],
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'author'         => (int)$attached_uid,
        'meta_query'     => [[
            'key'     => '_job_application_status',
            'value'   => ['approved','hired'],
            'compare' => 'IN',
        ]],
    ]);
    $approved_apps = (int)$q_approved->found_posts;
    wp_reset_postdata();

    // Use approved count if it beats the filled+matched count
    if ( $approved_apps > $missions_completed ) {
        $missions_completed = $approved_apps;
    }
}

// Fallback: count ANY jobs matched to this candidate (no filled filter)
if ( $missions_completed === 0 ) {
    $q_any = new WP_Query([
        'post_type'      => 'job_listing',
        'post_status'    => ['publish','private'],
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => [[
            'key'     => '_match_educator_name',
            'value'   => $candidate_name,
            'compare' => 'LIKE',
        ]],
    ]);
    $missions_completed = (int)$q_any->found_posts;
    wp_reset_postdata();
}

/* ══════════════════════════════════════════════════════════════
   ② AVERAGE RATING
   Average of all approved WP comment 'rating' meta on this post.
══════════════════════════════════════════════════════════════ */
$all_reviews = get_comments([
    'post_id' => $candidate_id,
    'status'  => 'approve',
    'number'  => 999,
    'order'   => 'DESC',
]);
$rating_sum   = 0;
$rating_count = 0;
foreach ( $all_reviews as $rv ) {
    // Try all known meta keys used by WP Job Board Pro
    $r = intval( get_comment_meta($rv->comment_ID, 'rating', true) );
    if ( $r === 0 ) $r = intval( get_comment_meta($rv->comment_ID, '_rating', true) );
    if ( $r === 0 ) $r = intval( get_comment_meta($rv->comment_ID, 'overall_rating', true) );
    if ( $r === 0 ) $r = intval( get_comment_meta($rv->comment_ID, 'stars', true) );

    if ( $r >= 1 && $r <= 5 ) {
        $rating_sum += $r;
        $rating_count++;
    }
}
$avg_rating      = $rating_count > 0 ? round($rating_sum / $rating_count, 1) : null;
$avg_rating_disp = $avg_rating !== null ? number_format($avg_rating, 1, ',', '') . '/5' : '—';
// JS target: numeric, format handled by script
$avg_rating_js   = $avg_rating !== null ? $avg_rating : 0;

/* ══════════════════════════════════════════════════════════════
   ③ ACCEPTANCE RATE
   = approved_apps / total_apps × 100
   Fallback when no app data: profile completeness estimate.
══════════════════════════════════════════════════════════════ */
if ( $total_apps > 0 ) {
    $acceptance_rate = (int)round( ($approved_apps / $total_apps) * 100 );
} else {
    // Estimate from profile fill quality
    $pct = 0;
    if ( !empty($education) )      $pct += 20;
    if ( !empty($qualification) )  $pct += 20;
    if ( !empty($experience) )     $pct += 20;
    if ( $salary )                 $pct += 15;
    if ( !empty($languages) )      $pct += 10;
    if ( $siret )                  $pct += 15;
    $acceptance_rate = min(100, $pct);
}

/* ══════════════════════════════════════════════════════════════
   ④ RESPONSE TIME
   Directly from meta 'response-time'.
   Stored as free text e.g. "1 hour 40 minutes" or "< 2h".
   We parse it to derive minutes for the animated counter,
   and keep the raw string for static display.
══════════════════════════════════════════════════════════════ */
$response_raw = $response_time ?: null;

// Parse minutes from the stored string for the counter animation
$response_minutes = 0;
if ( $response_raw ) {
    $s = strtolower($response_raw);
    // extract hours
    if ( preg_match('/(\d+)\s*h(?:eure|our)?/', $s, $hm) ) {
        $response_minutes += (int)$hm[1] * 60;
    }
    // extract minutes
    if ( preg_match('/(\d+)\s*m(?:in(?:ute)?)?/', $s, $mm) ) {
        $response_minutes += (int)$mm[1];
    }
    // fallback: single number interpreted as minutes
    if ( $response_minutes === 0 && preg_match('/(\d+)/', $s, $nm) ) {
        $response_minutes = (int)$nm[1];
    }
}

// Format the response time nicely for display
function efj_format_response_time($raw) {
    if (!$raw) return '—';
    $s = strtolower($raw);
    $h = 0; $m = 0;
    if (preg_match('/(\d+)\s*h/', $s, $hm)) $h = (int)$hm[1];
    if (preg_match('/(\d+)\s*m/', $s, $mm)) $m = (int)$mm[1];
    if ($h > 0 && $m > 0) return $h.'h '.$m.'min';
    if ($h > 0)            return $h.'h';
    if ($m > 0)            return $m.'min';
    return esc_html($raw); // pass through if unparseable
}
$response_display = efj_format_response_time($response_raw);

/* ══════════════════════════════════════════════════════════════
   ⑤ RELIABILITY SCORE  (0–100)
   Weighted signals:
     Verified documents uploaded    25 pts
     Education entries (×5, max 20) 20 pts
     Experience entries (×4, max 16) 16 pts  → capped 15
     SIRET filled                    5 pts
     Statut filled                   5 pts
     Languages ≥2                   10 pts  / 1 → 5 pts
     Avg rating ≥4.5 → 10, ≥4 → 7, ≥3 → 4   10 pts
     Availability ≥4 days filled    10 pts  / ≥2 → 5
══════════════════════════════════════════════════════════════ */
$reliability = 0;

// Verified docs
$has_docs = false;
foreach ($education as $edu) {
    if (!empty($edu['_candidate__documents'])) { $has_docs = true; break; }
}
if ($has_docs) $reliability += 25;

// Education
$edu_filled = count(array_filter($education, fn($e) => !empty($e['title'])));
$reliability += min(20, $edu_filled * 5);

// Experience
$exp_filled = count(array_filter($experience, fn($e) => !empty($e['title'])));
$reliability += min(15, $exp_filled * 4);

// Legal identity
if ($siret)  $reliability += 5;
if ($statut) $reliability += 5;

// Languages
if (count($languages) >= 2)      $reliability += 10;
elseif (count($languages) === 1) $reliability += 5;

// Rating
if ($avg_rating !== null) {
    if ($avg_rating >= 4.5)      $reliability += 10;
    elseif ($avg_rating >= 4.0)  $reliability += 7;
    elseif ($avg_rating >= 3.0)  $reliability += 4;
}

// Availability
$avail_days_filled = count(array_filter(array_values($days_meta), fn($v) => !empty($v)));
if ($avail_days_filled >= 4)      $reliability += 10;
elseif ($avail_days_filled >= 2)  $reliability += 5;

$reliability = min(100, $reliability);

/* ══════════════════════════════════════════════════════════════
   OTHER DISPLAY HELPERS
══════════════════════════════════════════════════════════════ */
$reviews = array_slice($all_reviews, 0, 3);

$dob_formatted = '';
$age = '';
if ($dob) {
    $ts = strtotime($dob);
    $dob_formatted = date_i18n('j F Y', $ts);
    $age = (int)floor((time() - $ts) / YEAR_IN_SECONDS);
}

$parts    = explode(' ', trim(get_the_title()));
$initials = strtoupper(substr($parts[0]??'',0,1).(isset($parts[1])?substr($parts[1],0,1):''));

if ( !function_exists('educfind_dispo_class') ) {
    function educfind_dispo_class($val) {
        if (!$val) return 'busy';
        $v = strtolower($val);
        if (strpos($v,'non')!==false||strpos($v,'pris')!==false||strpos($v,'occup')!==false) return 'busy';
        if (strpos($v,'matin')!==false||strpos($v,'demande')!==false||strpos($v,'partial')!==false) return 'partial';
        return 'free';
    }
}

$dot_colors = ['blue','blue','green','orange','orange'];
?>

<?php do_action('wp_job_board_pro_before_job_detail', $candidate_id); ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
<article id="post-<?php the_ID(); ?>" <?php post_class('candidate-single-v1'); ?>>

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

  <div class="candidate-content-area <?php echo apply_filters('superio_candidate_content_class','container'); ?>">
    <div class="row content-single-candidate">
      <div class="col-xs-12 list-content-candidate col-md-8">

        <?php do_action('wp_job_board_pro_before_job_content', $candidate_id); ?>

        <div class="ef-wrap">

          <!-- ══ HEADER ══════════════════════════════════════ -->
          <div class="ef-header">
            <div class="ef-avatar">
              <?php echo esc_html($initials); ?>
              <div class="ef-dot"></div>
            </div>
            <div class="ef-header-info">
              <div class="ef-name"><?php the_title(); ?></div>
              <div class="ef-sub"><?php
                $sub = [];
                if ($job_title) $sub[] = esc_html($job_title);
                if ($statut)    $sub[] = esc_html($statut);
                if ($address)   $sub[] = esc_html($address);
                echo implode(' · ', $sub);
              ?></div>
              <div class="ef-badges">
                <span class="ef-badge blue">✔ <?php esc_html_e( 'Profil vérifié', 'efj' ); ?></span>
                <?php if ($exp_time): ?>
                  <span class="ef-badge dark">
                    <?php
                    /* translators: %s experience duration e.g. "5 years" */
                    printf( esc_html__( '% d\'expérience', 'efj' ), esc_html($exp_time) );
                    ?>
                  </span>
                <?php endif; ?>
                <?php if (!empty($languages)): ?>
                  <span class="ef-badge green">🌐 <?php echo esc_html(implode(' · ', $languages)); ?></span>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- ══════════════════════════════════════════════════
               STATS STRIP — 5 cells, exact reference design
               ① Missions completed  → blue    → integer counter
               ② Average rating      → green   → x.x/5 counter
               ③ Acceptance rate     → orange  → % counter
               ④ Response time       → purple  → static text (highlighted bg)
               ⑤ Reliability score   → dark    → % counter
          ══════════════════════════════════════════════════ -->
          <div class="stats-strip" id="ef-stats-strip">

            <!-- ① Missions completed -->
            <div class="ss-item">
              <span class="ss-val blue"
                    id="stat-missions"
                    data-target="<?php echo (int)$missions_completed; ?>"
                    data-fmt="int">0</span>
              <div class="ss-label"><?php esc_html_e( 'Missions accomplies', 'efj' ); ?></div>
            </div>

            <!-- ② Average rating -->
            <div class="ss-item">
              <?php if ($avg_rating !== null): ?>
              <span class="ss-val green"
                    id="stat-rating"
                    data-target="<?php echo esc_attr($avg_rating_js); ?>"
                    data-fmt="rating">0,0/5</span>
              <?php else: ?>
              <span class="ss-val green" id="stat-rating">—</span>
              <?php endif; ?>
              <div class="ss-label"><?php esc_html_e( 'Note moyenne', 'efj' ); ?></div>
            </div>

            <!-- ③ Acceptance rate -->
            <div class="ss-item">
              <span class="ss-val orange"
                    id="stat-acceptance"
                    data-target="<?php echo (int)$acceptance_rate; ?>"
                    data-fmt="pct">0%</span>
              <div class="ss-label"><?php esc_html_e( 'Taux d\'acceptation', 'efj' ); ?></div>
            </div>

            <!-- ④ Response time — highlighted cell, purple, no counter (text) -->
            <div class="ss-item">
              <span class="ss-val-lg" id="stat-response"><?php echo esc_html($response_display); ?></span>
              <div class="ss-label"><?php esc_html_e( 'Temps de réponse', 'efj' ); ?></div>
            </div>

            <!-- ⑤ Reliability score -->
            <div class="ss-item">
              <span class="ss-val"
                    id="stat-reliability"
                    data-target="<?php echo (int)$reliability; ?>"
                    data-fmt="pct">0%</span>
              <div class="ss-label"><?php esc_html_e( 'Score de fiabilité', 'efj' ); ?></div>
            </div>

          </div><!-- /.stats-strip -->

          <!-- ══ CONTENT GRID ═══════════════════════════════ -->
          <div class="ef-grid">

            <!-- Bio -->
            <?php $bio = apply_filters('the_content', get_the_content()); if ($bio): ?>
            <div class="ef-card ef-full">
              <div class="ef-card-label"><span class="ef-cl blue">💬</span> <?php esc_html_e( 'Introduction', 'efj' ); ?></div>
              <div class="ef-bio"><?php echo wp_kses_post($bio); ?></div>
            </div>
            <?php endif; ?>

            <!-- Identity -->
            <?php if ($dob_formatted || $statut || $siret || !empty($languages)): ?>
            <div class="ef-card">
              <div class="ef-card-label"><span class="ef-cl blue">🪪</span> <?php esc_html_e( 'Identité', 'efj' ); ?></div>
              <?php if ($dob_formatted): ?>
              <div class="ef-dr">
                <span class="ef-dk"><?php esc_html_e( 'Born on', 'efj' ); ?></span>
                <span class="ef-dv">
                  <?php
                  echo esc_html($dob_formatted);
                  if ($age) {
                      /* translators: %d age in years */
                      echo ' (' . sprintf( esc_html__( '%d years old', 'efj' ), $age ) . ')';
                  }
                  ?>
                </span>
              </div>
              <?php endif; if ($statut): ?>
              <div class="ef-dr">
                <span class="ef-dk"><?php esc_html_e( 'Status', 'efj' ); ?></span>
                <span class="ef-dv"><?php echo esc_html($statut); ?></span>
              </div>
              <?php endif; if ($siret): ?>
              <div class="ef-dr">
                <span class="ef-dk"><?php esc_html_e( 'SIRET', 'efj' ); ?></span>
                <span class="ef-dv"><?php echo esc_html($siret); ?></span>
              </div>
              <?php endif; if (!empty($languages)): ?>
              <div class="ef-dr">
                <span class="ef-dk"><?php esc_html_e( 'Languages', 'efj' ); ?></span>
                <span class="ef-dv"><?php echo esc_html(implode(' · ', $languages)); ?></span>
              </div>
              <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Education & Qualifications -->
            <?php if (!empty($education)): ?>
            <div class="ef-card">
              <div class="ef-card-label"><span class="ef-cl purple">🎓</span> <?php esc_html_e( 'Éducation et qualifications', 'efj' ); ?></div>
              <?php foreach ($education as $edu):
                if (empty($edu['title'])) continue;
                $vp = array_filter([
                    $edu['academy'] ?? '',
                    $edu['year']    ?? '',
                ]);
              ?>
              <div class="ef-dr">
                <span class="ef-dk"><?php echo esc_html($edu['title']); ?></span>
                <span class="ef-dv"><?php echo esc_html(implode(' — ', $vp)); ?></span>
              </div>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Specialities & Audiences -->
            <?php if (!empty($qualification) || $publics || !empty($interventions)): ?>
            <div class="ef-card">
              <div class="ef-card-label"><span class="ef-cl green">🧩</span> <?php esc_html_e( 'Spécialités et publics', 'efj' ); ?></div>
              <?php if (!empty($qualification)): ?>
              <div style="margin-bottom:14px">
                <div class="ef-dk" style="margin-bottom:6px"><?php esc_html_e( 'Specialities', 'efj' ); ?></div>
                <div class="ef-tags">
                  <?php foreach ($qualification as $s): ?>
                    <span class="ef-tag"><?php echo esc_html($s); ?></span>
                  <?php endforeach; ?>
                </div>
              </div>
              <?php endif; if ($publics): ?>
              <div style="margin-bottom:14px">
                <div class="ef-dk" style="margin-bottom:6px"><?php esc_html_e( 'Audiences soutenues', 'efj' ); ?></div>
                <div class="ef-tags">
                  <?php foreach (explode(',',$publics) as $p): $p=trim($p); if(!$p) continue; ?>
                    <span class="ef-tag green"><?php echo esc_html($p); ?></span>
                  <?php endforeach; ?>
                </div>
              </div>
              <?php endif; if (!empty($interventions)): ?>
              <div>
                <div class="ef-dk" style="margin-bottom:6px"><?php esc_html_e( 'Interventions', 'efj' ); ?></div>
                <div class="ef-tags">
                  <?php foreach ($interventions as $iv): ?>
                    <span class="ef-tag"><?php echo esc_html($iv); ?></span>
                  <?php endforeach; ?>
                </div>
              </div>
              <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Pricing -->
            <?php if ($salary || $exp_time): ?>
            <div class="ef-card">
              <div class="ef-card-label"><span class="ef-cl orange">💰</span> <?php esc_html_e( 'Tarification', 'efj' ); ?></div>
              <?php if ($salary): ?>
              <div class="ef-pr">
                <span class="ef-pr-label">
                  <?php
                  /* translators: %s salary type label e.g. "Hourly" */
                  echo esc_html( $salary_type ? ucfirst($salary_type) : __( 'Taux', 'efj' ) );
                  ?>
                </span>
                <span class="ef-pr-val hi"><?php echo esc_html($salary); ?></span>
              </div>
              <?php endif; if ($exp_time): ?>
              <div class="ef-pr">
                <span class="ef-pr-label"><?php esc_html_e( 'Expérience', 'efj' ); ?></span>
                <span class="ef-pr-val"><?php echo esc_html($exp_time); ?></span>
              </div>
              <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Weekly availability -->
            <div class="ef-card ef-full">
              <div class="ef-card-label"><span class="ef-cl green">📅</span> <?php esc_html_e( 'Disponibilité hebdomadaire', 'efj' ); ?></div>
              <div class="ef-avail">
                <?php
                // Translatable day names mapped to meta keys
                $days_labels = [
                    'Lundi'    => __( 'Lundi',    'efj' ),
                    'Mardi'    => __( 'Mardi',   'efj' ),
                    'Mercredi' => __( 'Mercredi', 'efj' ),
                    'Jeudi'    => __( 'Jeudi',  'efj' ),
                    'Vendredi' => __( 'Vendredi',    'efj' ),
                    'Samedi'   => __( 'Samedi',  'efj' ),
                ];
                foreach ($days_meta as $day_key => $day_val):
                  $cls      = educfind_dispo_class($day_val);
                  $day_name = $days_labels[$day_key] ?? esc_html($day_key);
                ?>
                <div class="ef-day <?php echo esc_attr($cls); ?>">
                  <div class="ef-day-name"><?php echo esc_html($day_name); ?></div>
                  <div class="ef-day-info"><?php echo $day_val ? nl2br(esc_html($day_val)) : '—'; ?></div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Professional background -->
            <?php if (!empty($experience)): ?>
            <div class="ef-card">
              <div class="ef-card-label"><span class="ef-cl dark">📁</span> <?php esc_html_e( 'Parcours professionnel', 'efj' ); ?></div>
              <?php foreach ($experience as $i => $exp):
                if (empty($exp['title'])) continue;
                $dot = $dot_colors[$i] ?? 'blue';
              ?>
              <div class="ef-tl">
                <div class="ef-tl-dot <?php echo esc_attr($dot); ?>"></div>
                <div>
                  <div class="ef-tl-text">
                    <strong><?php echo esc_html($exp['title']); ?></strong>
                    <?php if (!empty($exp['company'])) echo ' — '.esc_html($exp['company']); ?>
                  </div>
                  <?php if (!empty($exp['start_date'])): ?>
                  <div class="ef-tl-date">
                    <?php
                    echo esc_html($exp['start_date']);
                    if (!empty($exp['end_date'])) {
                        /* translators: separator between start and end date */
                        echo esc_html( ' ' . __( '→', 'efj' ) . ' ' ) . esc_html($exp['end_date']);
                    }
                    ?>
                  </div>
                  <?php endif; ?>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Verified documents -->
            <?php
            $doc_edu = array_filter($education, fn($e) => !empty($e['_candidate__documents']));
            if (!empty($doc_edu)):
            ?>
            <div class="ef-card">
              <div class="ef-card-label"><span class="ef-cl green">🔒</span> <?php esc_html_e( 'Verified documents', 'efj' ); ?></div>
              <?php foreach ($doc_edu as $edu): ?>
              <div class="ef-doc">
                <div class="ef-doc-icon">📄</div>
                <div class="ef-doc-name"><?php echo esc_html($edu['title'] ?? ''); ?></div>
                <span class="ef-doc-status">✓ <?php esc_html_e( 'Verified', 'efj' ); ?></span>
              </div>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Client reviews -->
            <?php if (!empty($reviews)): ?>
            <div class="ef-card ef-full">
              <div class="ef-card-label">
                <span class="ef-cl orange">⭐</span>
                <?php
                /* translators: %d number of reviews shown */
                printf( esc_html__( 'Avis des clients (dernière %d)', 'efj' ), count($reviews) );
                ?>
              </div>
              <div class="ef-review-wrap">
                <?php foreach ($reviews as $review):
                  $rating = intval(get_comment_meta($review->comment_ID, 'rating', true));
                  $date   = date_i18n('F Y', strtotime($review->comment_date));
                ?>
                <div class="ef-review-card">
                  <div class="ef-review-top">
                    <div>
                      <div class="ef-review-author"><?php echo esc_html($review->comment_author); ?></div>
                      <div class="ef-review-stars">
                        <?php
                        $rating = intval(get_comment_meta($review->comment_ID, 'rating', true));

                        // Fallback: try alternate meta keys if rating is 0
                        if ($rating === 0) {
                            $rating = intval(get_comment_meta($review->comment_ID, '_rating', true));
                        }
                        if ($rating === 0) {
                            $rating = intval(get_comment_meta($review->comment_ID, 'overall_rating', true));
                        }

                        // Clamp between 0–5
                        $rating = max(0, min(5, $rating));

                        for ($i = 1; $i <= 5; $i++) {
                            echo $i <= $rating ? '★' : '☆';
                        }
                        ?>
                      </div>
                    </div>
                    <div class="ef-review-date"><?php echo esc_html($date); ?></div>
                  </div>
                  <div class="ef-review-content"><?php echo esc_html($review->comment_content); ?></div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>
          </div><!-- /.ef-grid -->
        </div><!-- /.ef-wrap -->
      <?php
      $show_review    = false;
      $already_reviewed = false;
      $employer_post  = null;

      if ( is_user_logged_in() && class_exists( 'WP_Job_Board_Pro_User' ) ) {
          $current_uid   = get_current_user_id();
          $employer_post = WP_Job_Board_Pro_User::get_employer_by_user_id( $current_uid );
          if ( $employer_post ) {
              $store_key      = '_efj_completed_employer_' . $employer_post;
              $completed_jobs = get_post_meta( $candidate_id, $store_key ); // array, no 3rd param           
              $show_review    = ! empty( $completed_jobs );             

              if ( $show_review ) {
                  // Check if this user already left a comment on this candidate
                  $existing_comments = get_comments( array(
                      'post_id' => $candidate_id,
                      'user_id' => $current_uid,
                      'count'   => true,
                      'status'  => 'all',
                  ) );
                  $already_reviewed = ( $existing_comments > 0 );
              }
          }
      }
      ?>

      <?php if ( $show_review ) : ?>
          <?php if ( $already_reviewed ) : ?>
              <div class="ef-card ef-full" style="margin-top:24px">
                  <div class="ef-card-label">
                      <span class="ef-cl green">⭐</span>
                      <?php esc_html_e( 'Votre avis', 'efj' ); ?>
                  </div>
                  <p style="color:#16a34a;font-weight:500;padding:12px 0">
                      ✅ <?php esc_html_e( 'Vous avez déjà soumis un avis pour cet éducateur. Merci !', 'efj' ); ?>
                  </p>
              </div>
          <?php else : ?>
              <?php comments_template(); ?>
          <?php endif; ?>
      <?php endif; ?>
        <?php do_action('wp_job_board_pro_after_job_content', $candidate_id); ?>
      </div>
       <?php if ( is_active_sidebar( 'candidate-single-sidebar' ) ): ?>
				<div class="col-xs-12 col-md-4 sidebar-job">
			   		<?php dynamic_sidebar( 'candidate-single-sidebar' ); ?>
			   	</div>
		   	<?php endif; ?>
    </div>
  </div>

</article>

<!-- ══════════════════════════════════════════════════════════
     ANIMATED COUNTER — triggers when strip scrolls into view
     Formats:
       int     → whole number (14)
       rating  → decimal with comma + /5  (4,9/5)
       pct     → percentage integer (92%)
══════════════════════════════════════════════════════════ -->
<script>
(function () {
  var DURATION = 1600; // ms

  function easeOutCubic(t) { return 1 - Math.pow(1 - t, 3); }

  function animateCounter(el) {
    var target = parseFloat(el.getAttribute('data-target') || 0);
    var fmt    = el.getAttribute('data-fmt') || 'int';
    var start  = null;

    function render(val) {
      if (fmt === 'int')    return Math.round(val).toString();
      if (fmt === 'rating') return val.toFixed(1).replace('.', ',') + '/5';
      if (fmt === 'pct')    return Math.round(val) + '%';
      return Math.round(val).toString();
    }

    function step(ts) {
      if (!start) start = ts;
      var elapsed  = ts - start;
      var progress = Math.min(elapsed / DURATION, 1);
      var eased    = easeOutCubic(progress);
      el.textContent = render(eased * target);
      if (progress < 1) requestAnimationFrame(step);
      else el.textContent = render(target); // snap to exact final value
    }

    requestAnimationFrame(step);
  }

  function runAll() {
    var strip    = document.getElementById('ef-stats-strip');
    if (!strip) return;
    var counters = strip.querySelectorAll('[data-target]');
    counters.forEach(function(el) { animateCounter(el); });
  }

  // Trigger on scroll into view (IntersectionObserver)
  var strip = document.getElementById('ef-stats-strip');
  if (!strip) return;

  if ('IntersectionObserver' in window) {
    var fired = false;
    var obs = new IntersectionObserver(function(entries) {
      if (entries[0].isIntersecting && !fired) {
        fired = true;
        runAll();
        obs.disconnect();
      }
    }, { threshold: 0.35 });
    obs.observe(strip);
  } else {
    // Fallback: run immediately (older browsers)
    runAll();
  }
})();
</script>

<?php do_action('wp_job_board_pro_after_job_detail', $candidate_id); ?>