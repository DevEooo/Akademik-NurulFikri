<nav id="wkp-mainnav" class="wkp-mainnav" data-nav-state="inactive">

	<div id="wkp-mainnav__mobile" class="wkp-mainnav__mobile">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><g fill="#fff"><path d="M15 7H1c-.6 0-1 .4-1 1s.4 1 1 1h14c.6 0 1-.4 1-1s-.4-1-1-1z"/><path d="M15 1H1c-.6 0-1 .4-1 1s.4 1 1 1h14c.6 0 1-.4 1-1s-.4-1-1-1zM15 13H1c-.6 0-1 .4-1 1s.4 1 1 1h14c.6 0 1-.4 1-1s-.4-1-1-1z"/></g></svg>
	</div>
	
	<ul>
	<?php 
		// Base Category Query
		$wkp_hp_cat_args = array(
			'orderby' => 'name',
			'order' => 'ASC',
			'hierarchical' => true,
			'hide_empty' => 0,
			'exclude' => 0,
			'pad_counts'  => 1
		);

		$wkp_categories = get_categories( $wkp_hp_cat_args ); 
		$wkp_categories = wp_list_filter( $wkp_categories, array( 'parent' => 0 ) );

		// If there are catgegories
		if ( $wkp_categories ) {
			foreach( $wkp_categories as $wkp_category ) { ?>

				<li data-nav-state="active">
					<span>
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><polyline fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="3.5,6.5 8,11 12.5,6.5"/></svg>
						<a href="<?php echo esc_url( get_category_link( $wkp_category->term_id ) ); ?>"><?php echo esc_html( $wkp_category->name ); ?></a>
					</span>

				<?php // Sub category
				 $wkp_sub_category = get_category( $wkp_category );
				 $wkp_subcat_args = array(
					'orderby' => 'name',
					'order' => 'ASC',
					'exclude' => 0,
					'child_of' => $wkp_sub_category->cat_ID,
					'pad_counts'  => 1
				);
				 $wkp_sub_categories = get_categories( $wkp_subcat_args ); 
				 $wkp_sub_categories = wp_list_filter( $wkp_sub_categories,array( 'parent' => $wkp_sub_category->cat_ID ) );

				//List Posts
				global $post;

			 $wkp_cat_post_args = array(
				'numberposts' => -1,
				'orderby' => 'title',
				'order' => 'ASC',
				'category__in' => $wkp_category->term_id 
				);

			 $wkp_cat_posts = get_posts( $wkp_cat_post_args );
			 echo '<ul>';
			 foreach( $wkp_cat_posts as $post ) : setup_postdata( $post ); ?>

				 <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

			 <?php endforeach; ?>

			 </ul>

			 </li>
			 
			<?php }    

		} ?>

	</ul>
</nav>