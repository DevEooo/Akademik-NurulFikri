<?php $current_term = ( get_queried_object() && isset( get_queried_object()->term_id ) ) ? get_queried_object()->term_id : 0; ?>
<?php $wkp_categories = htwiki_get_categories( $current_term ); ?>

<?php if ( $wkp_categories ) : ?>
	<ul class="wkp-catlist">
		<?php foreach( $wkp_categories as $wkp_category ) : ?>

			<li>
				<a href="<?php echo esc_url( get_category_link( $wkp_category->term_id ) ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"><path fill="#4071fc" d="M15 3H8.4L5.7.3C5.5.1 5.3 0 5 0H1C.4 0 0 .4 0 1v14c0 .6.4 1 1 1h14c.6 0 1-.4 1-1V4c0-.6-.4-1-1-1z"/></svg>
					<?php echo esc_html( $wkp_category->name ); ?>
				</a>
			</li>

		<?php endforeach; ?>
	</ul>
<?php endif; ?>