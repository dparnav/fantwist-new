<?php get_header(); ?>

<div id="content">
	
	<div id="inner-content" class="single-wrapper wrap">
		
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
			<main id="main" class="article-content-wrap" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">
				
				<div class="article-date">
					<?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?>
				</div>
				
				<div class="breadcrumbs">
					<ol itemscope itemtype="http://schema.org/BreadcrumbList">
						<?php 
						$cats = get_the_category(); $count = 1;
						foreach ($cats as $cat) {
							?>
							<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
								<a itemprop="item" href="<?php echo get_term_link($cat->term_id); ?>">
							    <span itemprop="name"><?php echo $cat->name; ?></span></a>
							    <meta itemprop="position" content="<?php echo $count; ?>" />
							</li>
							<?php
						}
						?>
					</ol>
				</div>
											
				<div class="article-header entry-header">
													
					<h1><?php the_title(); ?></h1>
					
					<?php 
					$avatar = get_field('user_avatar', 'user_'.$post->post_author);
					if (!$avatar) {
						$avatar = 'https://0.gravatar.com/avatar/c8318487e2421f7ee3ae5b1aaacc433a?s=70&d=mm&f=y&r=g';
					}
					?>
					
					<div class="author-box">
					
						<a class="user-avatar" href="<?php echo get_author_posts_url($post->post_author); ?>">
							<img src="<?php echo $avatar; ?>" alt="<?php echo get_the_author_meta('display_name'); ?>" />
							<span><?php echo get_the_author_meta('display_name'); ?></span>
						</a>
					
					</div>
												
				</div>	
								
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article" itemscope itemprop="blogPost" itemtype="http://schema.org/BlogPosting">
	
					<section class="entry-content article-content cf" itemprop="articleBody">
						
						<div class="featured-image">
							<?php the_post_thumbnail('full'); ?>
						</div>
											
						<?php the_content(); ?>
						
					</section>
	
					<footer class="article-footer">
					
					</footer> 
					
				</article>
	
			</main>
		
		<?php endwhile; endif; ?>

		<?php get_sidebar(); ?>

	</div> <!-- end #inner-content -->
	
</div> <!-- end #content -->

<?php get_footer(); ?>