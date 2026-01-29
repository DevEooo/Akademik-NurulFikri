<?php 
$wkp_userinfoclass = 'wkp-userinfo--guest';
if ( is_user_logged_in() ) :
	$wkp_userinfoclass = 'wkp-userinfo--loggedin';
endif; ?>

<div class="wkp-userinfo <?php echo esc_attr( $wkp_userinfoclass ); ?>">
	<div class="wkp-userinfo__contentwrap">
	<?php if ( is_user_logged_in() ) : ?>

		<div id="wkp-userinfo" class="wkp-userinfo__content" data-nav-state="inactive">

			<?php global $current_user;
			$current_user = wp_get_current_user();
			if ( $current_user->exists() ) {
				echo get_avatar( $current_user->ID, 32 );  ?>
			<span class="wkp-userinfo__name"><?php echo esc_html( $current_user->display_name ); ?></span>
			<span><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"><path d="M11.5 2H.5a.5.5 0 0 0-.412.783l5.5 8a.5.5 0 0 0 .824 0l5.5-8A.5.5 0 0 0 11.5 2z" fill="#fff"/></svg></span>
			<?php } ?>

			<ul class="wkp-userinfo__menu">
				<li><a href="<?php echo esc_url (wp_logout_url( home_url() ) ); ?>"><?php _e( 'Logout', 'wikipress'); ?></a></li>
			</ul>

		</div>

	<?php else :
		$wkp_loginpage = get_theme_mod( 'wkp_setting__loginpage' );
		?>

		<div class="wkp-userinfo__content">
			<a href="<?php echo esc_url( get_permalink( $wkp_loginpage ) ); ?>">
				<?php echo get_avatar( 0, 32 ); ?>
				<span class="wkp-userinfo__name"><?php _e( 'Login', 'wikipress' ); ?></span>
			</a>
		</div>

	<?php endif; ?>
	</div>
</div>