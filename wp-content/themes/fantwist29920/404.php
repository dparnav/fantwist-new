<?php get_header(); ?>

<div id="content">

	<div id="inner-content" class="wrap cf">

		<main id="main" class="cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

			<article id="post-not-found" class="hentry cf">

				<header class="article-header">

					<h1><?php _e( '404 - Page Not Found', 'bonestheme' ); ?></h1>

				</header>

				<section class="entry-content">

					<p><?php _e( 'The page you were looking for does not exist. Please click <a style="text-decoration:underline;" href="/">here</a> to return to the homepage.', 'bonestheme' ); ?></p>

				</section>

				<section class="search">

						<p><?php //get_search_form(); ?></p>

				</section>

				<footer class="article-footer"></footer>

			</article>

		</main>

	</div>

</div>

<?php get_footer(); ?>