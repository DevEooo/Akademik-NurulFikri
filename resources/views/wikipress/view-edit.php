<?php
/**
 * View > Edit Template
 */

?>

<div class="wkp-article__header">

  <div class="wkp-article__controls">
    <?php if ( is_user_logged_in() || apply_filters( 'ht_wiki_demo_mode', false ) ) : ?>
    <ul>
      <li>    
        <span class="wkp-article__btn wkp-article__btn--bta"><a href="?view=read"><?php get_template_part( '/svg/icon', 'backarrow' ); ?><?php esc_html_e( 'Back to article', 'wikipress' ); ?></a></span>      
      </li>
    </ul> 
    <?php endif; ?>
  </div>

</div>

<?php if ( apply_filters( 'htwiki_allow_anon_editing', false ) || is_user_logged_in() ) : ?>

	<!-- the block editor -->
	<div class="block-editor gutenberg">
		<div id="editor" class="block-editor__container gutenberg__editor"></div>
		<div id="metaboxes" class="hidden"></div>
	</div>

<?php else : ?>

    <?php $wkp_loginpage = get_theme_mod( 'wkp_setting__loginpage' ); ?>

    <a href="<?php echo esc_url( get_permalink( $wkp_loginpage ) ); ?>"><?php _e( 'Login', 'wikipress' ); ?></a><?php esc_html_e( ' to edit the page', 'wikipress' ); ?>

<?php endif; ?>
