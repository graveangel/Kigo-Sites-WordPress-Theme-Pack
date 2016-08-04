<?php get_header();?>
<div class="blog-listing page-width">
	<!-- Blog listing sidebar -->
	<div class="col-xs-12 page-blog-listing-sidebar">
		<?php if (is_active_sidebar('page_archive_listing')) : ?>
							<?php dynamic_sidebar('page_archive_listing'); ?>
		<?php endif; ?>
	</div>

	<!-- Blog listing -->
		<div class="col-xs-12 col-lg-9">
			<div class="results">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post(); ?>

				<!-- Listed blog -->
				<div class="listed-blog col-xs-12 col-md-12">

					<!-- thumbnail -->
					<div class="image col-lg-2 col-xs-12 paddingless">

						<?php $attachments = has_post_thumbnail(); ?>
						<?php if($attachments): ?>
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail('thumbnail'); ?>
							</a>
						<?php endif; ?>

					</div>

					<!-- text -->
					<div class="text col-lg-10 col-xs-12 paddingless">

						<!-- the title -->
						<h3>
							<?php the_title(); ?>
							<?php the_date('Y-m-d', '<p class="post-date">', '</p>'); ?>
						</h3>

						<!-- the summary -->
						<div class="post-summary">
							<?php the_excerpt(); ?>
						</div>
					</div>
				</div>


			<?php endwhile; // end while
		endif; // end if
		?>
		</div>


		<div class="results-info">
			<h4>
				<?php
						global $wp_query;

						$big = 999999999; // need an unlikely integer

						echo paginate_links( array(
							'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
							'format' => '?paged=%#%',
							'current' => max( 1, get_query_var('paged') ),
							'total' => $wp_query->max_num_pages
						) );
				?>
			</h4>
		</div>
	</div>
	<div class="col-xs-12 col-lg-3 sidebar-right">
		<?php if (is_active_sidebar('page_archive_listing_right')) : ?>
							<?php dynamic_sidebar('page_archive_listing_right'); ?>
		<?php endif; ?>
	</div>



</div>
<?php get_footer();
