<?php
/**
 * The template for displaying 404 pages (Not Found).
**/
get_header(); ?>
  
<div class="wkp-maincontent">
  <article class="wkp-article">
    <div class="wkp-article__thearticle">
      <!-- logic here to facilitate page creation by the appropriate capability -->
      <?php printf( __('This page does not exist, would you like to %screate it%s?', 'wikipress'), sprintf( '<a href="%s">', apply_filters('htwiki_page_creation_url', '') ), '</a>'); ?>
    </div>
  </article>
</div>

<?php get_footer(); ?>