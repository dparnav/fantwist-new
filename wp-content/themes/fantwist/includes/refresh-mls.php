<?php /* Template Name: Refresh MLS */ ?>

<?php 
$stats_key = '4ee248e814c54f54a6a5c5d4f6f56772';
	
update_live_mls_contests($stats_key, false);

$user_id = get_current_user_id();

$cron_log = array(
	'post_status' => 'draft',
	'post_title' => 'MLS Cron Log - Update Live - Manual - ' .  $user_id,
	'post_type' => 'cron_log',
	'post_content' => date('m-d-Y g:i a', time() - (60*60*7)) . ' PT',
	'tax_input' => array (
		'cron_type' => 4304,
	),
);

wp_set_current_user(1);
wp_insert_post( $cron_log );

?>