<?php /* Template Name: Refresh NFL */ ?>

<?php 
$stats_key = '4d0325105daa44a49321d4acb82718c6';
	
update_live_nfl_contests($stats_key, false);

$user_id = get_current_user_id();

$cron_log = array(
	'post_status' => 'draft',
	'post_title' => 'NFL Cron Log - Update Live - Manual - ' .  $user_id,
	'post_type' => 'cron_log',
	'post_content' => date('m-d-Y g:i a', time() - (60*60*7)) . ' PT',
	'tax_input' => array (
		'cron_type' => 4308,
	),
);

wp_set_current_user(1);
wp_insert_post( $cron_log );

?>