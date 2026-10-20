<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php
	echo WP_Job_Board_Pro_Template_Loader::get_template_part('loop/organization/archive-inner', array('organizations' => $organizations));

	echo WP_Job_Board_Pro_Template_Loader::get_template_part('loop/organization/pagination', array('organizations' => $organizations));
?>