<?php if ( ! empty( $_GET['ajax'] ) ? $_GET['ajax'] : null ) : // Is Live Search ?>

  <ul id="wkp" class="wkp-searchresults" role="listbox">

    <?php $total_results = 0; ?>
    <!-- ht_kb -->
    <?php if ( have_posts() ) : ?>
      <?php $counter = 0; ?>
      <?php $total_results += (int) $wp_query->posts; ?>
      <?php
      while ( have_posts() && $counter < 10 ) :
        the_post(); ?>
        <li>
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </li>
        <?php $counter++; ?>
      <?php endwhile; ?>
    <?php endif; ?>

    <?php if ( $total_results > 0 ) : ?>
      <!-- Placeholder for link to search results page -->
    <?php else : ?>
      <li class="wkp-searchresults__noresults" role="option">
        <span><?php esc_html_e( 'No Results', 'wikipress' ); ?></span>
      </li>
    <?php endif; ?>
  </ul>

<?php else : // Is Normal Search ?>

  <?php get_header(); ?>

  <div class="wkp-maincontent">

        <div class="wkp-article">

          <div class="wkp-article__header">

          <?php get_template_part( 'article-header', 'search' ); ?>

        </div>            
            
            <div class="wkp-article__thearticle">

              <header class="wkp-article__header">
                <h1 class="wkp-article__title"><?php esc_html_e( 'Search Results', 'wikipress' ); ?></h1>
              </header>
            <?php if ( have_posts() ) { ?>

              <ul class="wkp-searchresultslist">

              <?php /* Start the Loop */ ?>
              <?php while ( have_posts() ) : the_post(); ?>

                <li>
                  <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </li>

             <?php endwhile; ?>

            </ul>

             <?php //get_template_part( 'page', 'navigation' ); //navigation goes here ?>

           <?php } else { ?>

            <?php esc_html_e( 'No Results', 'wikipress' ); ?>

          <?php } ?>

        </div>

    </div>
    <!-- /.wkp-article -->

  </div>

  <?php get_footer(); ?>

<?php endif; ?>