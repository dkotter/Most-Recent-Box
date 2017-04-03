<?php
/**
 * Template for our Most Recent Box.
 *
 * @since 0.1.0
 */
?>

<article class="m-content__embed">
	<div class="layout">

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="col w-sm-33 w-md-33 w-lg-33">
				<div class="gutter">
					<div class="m-content__embed--media">
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( 'mrb-thumb' ); ?>
						</a>
					</div><!--/.m-content__embed--media-->
				</div><!--/.gutter-->
			</div><!--/.col-->
		<?php endif; ?>

		<div class="col w-sm-66 w-md-66 w-lg-66">
			<div class="gutter">
				<div class="m-content__embed--content">
					<p class="m-content__label--small">
						<?php if ( has_category() ) {
							the_category( ', ' );
							printf(
								'<span> | <em>%1$s</em></span>',
								esc_html( MRB\Utilities\time_stamp() )
							);
						} else {
							printf(
								'<span><em>%1$s</em></span>',
								esc_html( MRB\Utilities\time_stamp() )
							);
						} ?>
					</p><!--/.m-content__label-->

					<h2 class="m-content__embed--heading">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h2>

					<div class="m-content__embed--copy">
						<p>
							<?php MRB\Utilities\posted_on(); ?>
						</p>
					</div><!--/.m-content__embed--copy-->
				</div><!--/.m-content__embed--media-->
			</div><!--/.gutter-->
		</div><!--/.col-->

	</div><!--/.layout-->
</article><!--/.m-content__embed -->
