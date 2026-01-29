<?php
/**
 * View > Read Template
 */
?>

<div class="wkp-article__header">

  <?php get_template_part( 'article-header', 'search' ); ?>

  <?php get_template_part( 'article-header', 'controls' ); ?>

</div>

<div class="wkp-article__thearticle">

  <header class="wkp-article__tharticleheader">
	 <?php the_title( '<h1 class="wkp-article__title">', '</h1>' ); ?>
  </header>

  <div class="wkp-thecontent">
	 <?php the_content(); ?>
  </div>

</div>

<div class="wkp-article__footer">
  <div class="wkp-article__lastmodified"><?php esc_html_e( 'Last Modified:', 'wikipress' ); ?> <?php the_modified_date(); ?></div>
</div>