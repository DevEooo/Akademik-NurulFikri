<?php get_header(); ?>

<div class="wkp-maincontent">

	<?php while ( have_posts() ) : the_post(); ?>

	<article class="wkp-article">

		<?php get_template_part( 'view', htwiki_get_current_view( 'page' ) ); ?>

	</article>

	<?php endwhile; ?>

</div>

<?php get_footer(); ?>
