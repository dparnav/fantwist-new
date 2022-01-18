<?php get_header(); ?>

<div id="content">

	<div id="inner-content" class="wrap">

		<main id="main" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php //post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

				<header class="article-header">

					<h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1>
				
				</header>

				<section class="entry-content" itemprop="articleBody">
					
					<?php the_content(); ?>
				
				</section>

				<footer class="article-footer "></footer>
				
			</article>

			<?php endwhile; endif; ?>

		</main>

		<?php //get_sidebar(); ?>

	</div> <!-- end #inner-content -->

</div> <!-- end #content -->

<?php get_footer(); ?>