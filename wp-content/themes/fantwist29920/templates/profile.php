<?php /* Template Name: Profile */ ?>

<?php
if (is_user_logged_in()) {
	header("Location:" . get_author_posts_url(get_current_user_id()) . '"');
}
else {
	header("Location: ".rtrim(home_url(),"/\\"));
}

die();

?>

