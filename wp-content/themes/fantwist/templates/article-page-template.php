<?php /* Template Name: Article Page */ ?>

<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<div class="how-to-play-box page-box wrap">
	
	<div class="inner-wrap">

		<div class="how-to-play-wrap">
			
			<div class="how-to-play how-to-play-house-rules active">
				<h2><?= $post->post_title; ?></h2>
				<p>
					<?= $post->post_content; ?>
				</p>
			</div>
			
		</div>
	
	</div>

</div>
<style>
	.about-points 
	{
		display: flex;
		justify-content: space-between;
	}
</style>
<?php endwhile; endif; ?>

<?php get_footer(); ?>