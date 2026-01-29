<?php global $current_user, $post;
$current_user = wp_get_current_user(); 
$editing_user_id = wp_check_post_lock( $post->ID );
?>

<div class="wkp-article__controls">
  <?php if( ! empty( $editing_user_id ) ) : ?>
    <div class="wkp-article__status"><?php echo get_avatar( $editing_user_id, 32 ); ?> <span><?php printf( esc_html( '%s is currently editing', 'wikipress' ), get_userdata($editing_user_id)->display_name ); ?></span></div>
  <?php endif; ?>
  <ul>
    <?php if ( ! empty( $editing_user_id ) &&  ( is_singular( 'post' ) ) )  : ?>      
      <li>
        <span class="wkp-article__btn wkp-article__btn--disabled wkp-article__btn--edit">
          <?php get_template_part( '/svg/icon', 'edit' ); ?>
          <?php esc_html_e( 'Edit', 'wikipress' ); ?>            
        </span>
      </li>
      <!--<li>
        <span class="wkp-article__btn wkp-article__btn--add">
          <a href="<?php echo apply_filters( 'htwiki_page_new_url', '' ); ?>">
            <?php get_template_part( '/svg/icon', 'addarticle' ); ?><?php esc_html_e( 'Add Article', 'wikipress' ); ?>
          </a>
        </span>
      </li>-->
    <?php elseif ( is_singular( 'post' ) ) : ?>
      <li>
        <span class="wkp-article__btn wkp-article__btn--edit">
          <a href="?view=edit">
            <?php get_template_part( '/svg/icon', 'edit' ); ?><?php esc_html_e( 'Edit', 'wikipress' ); ?>              
          </a>
        </span>
      </li>
    <?php endif; ?>   

    <li>
      <span class="wkp-article__btn wkp-article__btn--add">
        <a href="<?php echo apply_filters( 'htwiki_page_new_url', '' ); ?>">
          <?php get_template_part( '/svg/icon', 'addarticle' ); ?><?php esc_html_e( 'Add Article', 'wikipress' ); ?>            
        </a>
      </span>
    </li>

  </ul> 
</div>
