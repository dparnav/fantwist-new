<?php /* Template Name: Refresh NASCAR */ ?>

<?php 
$stats_key = '02af536ebdad4d51beb46013379f61e0';
	
update_live_nascar_contests($stats_key, false);

$user_id = get_current_user_id();

$cron_log = array(
	'post_status' => 'draft',
	'post_title' => 'NASCAR Cron Log - Update Live - Manual - ' .  $user_id,
	'post_type' => 'cron_log',
	'post_content' => date('m-d-Y g:i a', time() - (60*60*7)) . ' PT',
	'tax_input' => array (
		'cron_type' => 4303,
	),
);

wp_set_current_user(1);
wp_insert_post( $cron_log );

?>