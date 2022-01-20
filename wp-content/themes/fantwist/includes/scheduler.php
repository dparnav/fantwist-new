<?php /* Template Name: Scheduler */ ?>

<?php 
// $stats_key = '562f123e387a4c2bbb37395741d0a539';
$stats_key = '1f84d3da9fcb4f8fb276be1503989a33';
update_live_mlb_contests($stats_key, false);

$user_id = get_current_user_id();

$cron_log = array(
	'post_status' => 'draft',
	'post_title' => 'MLB Cron Log - Update Live - Manual - ' .  $user_id,
	'post_type' => 'cron_log',
	'post_content' => date('m-d-Y g:i a', time() - (60*60*7)) . ' PT',
	'tax_input' => array (
		'cron_type' => 4299,
	),
);

wp_set_current_user(1);
wp_insert_post( $cron_log );
?>