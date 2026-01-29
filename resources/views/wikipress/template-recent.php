<?php
/*
Template Name: Recent Changes
*/

get_header(); ?>

	<div class="wkp-maincontent">

		<?php
			$args = array( 'numberposts' => '10', 'post_type' => 'post' );
			$recent_posts = wp_get_recent_posts( $args, OBJECT );
		?>

		<article class="wkp-article">

			<div class="wkp-article__header">

				<?php get_template_part( 'article-header', 'search' ); ?>

				<?php get_template_part( 'article-header', 'controls' ); ?>

			</div>

			<div class="wkp-article__thearticle">

				<h1 class="wkp-article__title"><?php _e('Recent Changes', 'wikipress') ?></h1>

				<?php if ( count($recent_posts) > 0 ) : ?>
					<ul class="wkp-postlist wkp-postlist--recentchanges">
					<?php foreach($recent_posts as $post) : ?>
						<?php global $post; setup_postdata($post); ?>
						<li>
							<a href="<?php the_permalink(); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"><g fill="#4071fc"><path d="M14 0H2c-.6 0-1 .4-1 1v14c0 .6.4 1 1 1h12c.6 0 1-.4 1-1V1c0-.6-.4-1-1-1zm-1 14H3V2h10v12z"/><path data-color="color-2" d="M4 3h4v4H4zM9 4h3v1H9zM9 6h3v1H9zM4 8h8v1H4zM4 10h8v1H4zM4 12h5v1H4z"/></g></svg>
								<?php the_title(); ?>     
							</a>
							<span class="wp-article__meta">
								<span class="wp-article__time"><?php esc_html_e( 'last updated' , 'wikipress'); 
							printf( _x( ' %s ago', '%s = human-readable time difference', 'wikipress' ), human_time_diff( get_the_modified_time('U') , current_time( 'timestamp' ) ) );	?>
								<span class="wp-article__author"><?php esc_html_e( 'by','wikipress' ); ?> <?php echo get_the_author(); ?></span>
						</span>
						</li>
					<?php endforeach; ?>
					</ul>
					<?php //the_posts_navigation(); ?>
				<?php else : ?>
					<?php get_template_part( 'content', 'none' ); ?>
				<?php endif; ?>

			</div>

		</article>

	</div>

	<?php wp_reset_postdata(); ?>

<?php get_footer(); ?>