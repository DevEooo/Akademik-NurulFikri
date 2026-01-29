<?php 
/*
Template Name: Front Page
*/

get_header(); ?>

<div class="wkp-maincontent">

	<article class="wkp-article">

		<div class="wkp-article__header">

			<?php get_template_part( 'article-header', 'search' ); ?>

			<?php get_template_part( 'article-header', 'controls' ); ?>

		</div>

		<div class="wkp-article__thearticle">

			<h1 class="wkp-article__title"><?php esc_html_e( 'Wiki', 'wikipress' ) ?></h1>

			<?php get_template_part( 'subcategories', 'front' ); ?>

		</div>

	</article>

</div>

<?php get_footer(); ?>
