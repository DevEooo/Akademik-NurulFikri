<div class="wkp-article__search">     
  <form class="wkp-site-search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
      <label class="wkp-screen-reader-text" for="wkp-search"><?php esc_html_e( 'Search For', 'wikipress' ); ?></label>
      <input id="wkp-search" class="wkp-site-search__field" type="text" value="<?php echo get_search_query(); ?>" placeholder="<?php esc_html_e( 'Search the wiki...', 'wikipress' ); ?>" name="s" autocomplete="off">
      <img class="wkp-site-search__loader" src="<?php echo esc_url( get_template_directory_uri() ); ?>/svg/icon-loader.svg" alt="<?php esc_html_e( 'Searching...', 'wikipress' ); ?>" />
        <input type="hidden" name="lang" value="<?php if( defined( 'ICL_LANGUAGE_CODE' ) ) echo( ICL_LANGUAGE_CODE ); ?>"/>
      <button class="wkp-site-search__button" type="submit"><span><?php esc_html_e( 'Search', 'wikipress' ); ?></span></button>
  </form>
</div>