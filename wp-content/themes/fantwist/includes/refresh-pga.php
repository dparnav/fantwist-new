<?php /* Template Name: Refresh PGA */ ?>

<?php 
$stats_key = '44691bd017be4bdabcd8af9da127ae38';
	
update_live_pga_contests($stats_key, false);

$user_id = get_current_user_id();

$cron_log = array(
	'post_status' => 'draft',
	'post_title' => 'PGA Cron Log - Update Live - Manual - ' .  $user_id,
	'post_type' => 'cron_log',
	'post_content' => date('m-d-Y g:i a', time() - (60*60*7)) . ' PT',
	'tax_input' => array (
		'cron_type' => 4300,
	),
);

wp_set_current_user(1);
wp_insert_post( $cron_log );

?>