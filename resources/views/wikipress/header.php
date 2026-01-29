<?php get_template_part( 'header', 'head' ); ?>

<!-- .wkp-sitecontainer -->
<div class="wkp-sitecontainer">

	<!-- .wkp-header -->
	<div class="wkp-header">

		<?php $wkp_theme_logo = get_theme_mod( 'wkp_setting__themelogo', get_template_directory_uri().'/img/wikipress-logo.png' ); ?>
    <?php if ( '' != $wkp_theme_logo ) : ?>
			<div class="wkp-logo">
				<a href="<?php echo esc_url( home_url() ); ?>">
					<img src="<?php echo esc_url( $wkp_theme_logo ); ?>" alt="<?php bloginfo( 'name' ); ?>" />
				</a>
			</div>
		<?php endif; ?>

		<?php get_template_part( 'header', 'nav' ); ?>

		<?php get_template_part( 'header', 'user' ); ?>    

	</div>
	<!-- /.wkp-header -->

	<!-- .wkp-main -->
	<main class="wkp-main">
