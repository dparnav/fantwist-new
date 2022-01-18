<?php /* Template Name: How To Play */ ?>

<?php
get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="page-hero how-to-play-hero" style="background-image:url(<?php echo get_the_post_thumbnail_url($post->ID, 'full'); ?>)">

			<div class="hero-details centered-vertical noselect">

				<div class="inner-wrap">

					<h1>How To Play</h1>
					<h2>Rules and Scoring for each League</h2>

				</div>

			</div>

		</div>

		<div class="how-to-play-box page-box wrap">

			<div class="inner-wrap">

				<div class="how-to-play-tabs noselect">
					<ul>

						<li>
							<a class="transition active" href="javascript:void(0);" data-league="nfl">NFL</a>
						</li>

						<li>
							<a class="transition" href="javascript:void(0);" data-league="mlb">MLB</a>
						</li>

						<li>
							<a class="transition" href="javascript:void(0);" data-league="nba">NBA</a>
						</li>

						<li>
							<a class="transition" href="javascript:void(0);" data-league="nhl">NHL</a>
						</li>

						<li>
							<a class="transition" href="javascript:void(0);" data-league="ncaa-f">NCAA-F</a>
						</li>

						<li>
							<a class="transition" href="javascript:void(0);" data-league="ncaa-b">NCAA-B</a>
						</li>

						<li>
							<a class="transition" href="javascript:void(0);" data-league="pga">PGA</a>
						</li>

						<li>
							<a class="transition" href="javascript:void(0);" data-league="soccer">SOCCER</a>
						</li>

						<li>
							<a class="transition" href="javascript:void(0);" data-league="nascar">NASCAR</a>
						</li>

					</ul>
				</div>

				<div class="how-to-play-wrap">

					<div class="how-to-play how-to-play-nfl active">
						<?php echo get_field('how_to_play_nfl'); ?>
					</div>
					<div class="how-to-play how-to-play-mlb">
						<?php echo get_field('how_to_play_mlb'); ?>
					</div>
					<div class="how-to-play how-to-play-nba">
						<?php echo get_field('how_to_play_nba'); ?>
					</div>
					<div class="how-to-play how-to-play-nhl">
						<?php echo get_field('how_to_play_nhl'); ?>
					</div>
					<div class="how-to-play how-to-play-ncaa-f">
						<?php echo get_field('how_to_play_ncaa_f'); ?>
					</div>
					<div class="how-to-play how-to-play-ncaa-b">
						<?php echo get_field('how_to_play_ncaa_b'); ?>
					</div>
					<div class="how-to-play how-to-play-pga">
						<?php echo get_field('how_to_play_pga'); ?>
					</div>
					<div class="how-to-play how-to-play-soccer">
						<?php echo get_field('how_to_play_soccer'); ?>
					</div>
					<div class="how-to-play how-to-play-nascar">
						<?php echo get_field('how_to_play_nascar'); ?>
					</div>

				</div>

			</div>

		</div>

<?php endwhile;
endif; ?>

<?php get_footer(); ?>