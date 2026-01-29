<?php get_header(); ?>

<div class="wkp-maincontent">

	<article class="wkp-article">

		<div class="wkp-article__header">

			<?php get_template_part( 'article-header', 'search' ); ?>

			<?php get_template_part( 'article-header', 'controls' ); ?>

		</div>

		<div class="wkp-article__thearticle">

			<h1 class="wkp-article__title"><?php single_cat_title(); ?></h1>

			<?php get_template_part( 'subcategories', 'category' ); ?>

		<?php	if ( have_posts() ) : ?>
			<ul class="wkp-postlist">
			<?php while ( have_posts() ) :
				the_post(); ?>

				<li>
					<a href="<?php the_permalink(); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"><g fill="#4071fc"><path d="M14 0H2c-.6 0-1 .4-1 1v14c0 .6.4 1 1 1h12c.6 0 1-.4 1-1V1c0-.6-.4-1-1-1zm-1 14H3V2h10v12z"/><path data-color="color-2" d="M4 3h4v4H4zM9 4h3v1H9zM9 6h3v1H9zM4 8h8v1H4zM4 10h8v1H4zM4 12h5v1H4z"/></g></svg>
						<?php the_title(); ?>			
					</a>
				</li>
				<?php endwhile; ?>
			</ul>
			<?php the_posts_navigation();
			else :
				get_template_part( 'content', 'none' );
			endif; ?>

		</div>

	</article>

</div>

<?php get_footer(); ?>
