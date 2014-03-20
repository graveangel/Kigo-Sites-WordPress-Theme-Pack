<?php
/*
This is the default Template, currently we are handling these pages:

Single Blog Post Page
Author Page
Search Landing Page
Category Page
Tag Page
Archive Page
Blog Posts List Page

*/
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
?>
<?php get_header(); ?>

<article class="default-page">
  <div class="row-fluid">
    <article class="span9">
      <?php 
	$isBlogList = false;
	
	if(is_single($post))
	{
		/* Start Single Page Output */
	?>
      <div class="single">
        <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <header>
            <h1 class="post-title">
              <?php the_title(); ?>
            </h1>
            <div class="post-meta">
              <?php instaparent_post_meta_data(); ?>
              <?php if ( comments_open() ) : ?>
              <span class="comments-link"> <span class="mdash">&mdash;</span>
              <?php comments_popup_link(__('No Comments &darr;', 'instaparent'), __('1 Comment &darr;', 'instaparent'), __('% Comments &darr;', 'instaparent')); ?>
              </span>
              <?php endif; ?>
            </div>
            <!-- end of .post-meta --> 
          </header>
          <div class="post-entry">
            <?php the_content(__('Read more &#8250;', 'instaparent')); ?>
          </div>
          <!-- end of .post-entry -->
          <footer>
            <div class="navigation">
              <div class="previous">
                <?php previous_post_link( '&#8249; %link' ); ?>
              </div>
              <div class="next">
                <?php next_post_link( '%link &#8250;' ); ?>
              </div>
              <div class="clearfix"></div>
            </div>
            <!-- end of .navigation -->
            
            <div class="post-data">
              <?php the_tags(__('Tagged with:', 'instaparent') . ' ', ', ', '<br />'); ?>
              <?php printf(__('Posted in %s', 'instaparent'), get_the_category_list(', ')); ?> </div>
            <!-- end of .post-data -->
            
            <div class="post-edit">
              <?php edit_post_link(__('Edit', 'instaparent')); ?>
            </div>
            <hr class="grey" />
            <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'instaparent'), 'after' => '</div>')); ?>
            <?php if ( get_the_author_meta('description') != '' ) : ?>
            <div id="author-meta" class="row-fluid">
              <div class="author-avatar span2">
                <?php if (function_exists('get_avatar')) { echo get_avatar( get_the_author_meta('email'), '80' ); }?>
              </div>
              <div class="author-content span10">
                <div class="about-author">
                  <?php _e('About','instaparent'); ?>
                  <?php the_author_posts_link(); ?>
                </div>
                <p>
                  <?php the_author_meta('description') ?>
                </p>
              </div>
            </div>
            <!-- end of #author-meta -->
            <hr class="grey" />
            <?php endif; // no description, no author's meta ?>
          </footer>
        </article>
        <!-- end of #post-<?php the_ID(); ?> -->
        
        <?php comments_template( '', true ); ?>
        <?php endwhile; ?>
        <?php if (  $wp_query->max_num_pages > 1 ) : ?>
        <div class="navigation">
          <div class="previous">
            <?php next_posts_link( __( '&#8249; Older posts', 'instaparent' ) ); ?>
          </div>
          <div class="next">
            <?php previous_posts_link( __( 'Newer posts &#8250;', 'instaparent' ) ); ?>
          </div>
        </div>
        <!-- end of .navigation -->
        <?php endif; ?>
        <?php endif; ?>
      </div>
      <?php /* End Single Page Output */
	}
	if(is_search())
	{ ?>
      <?php
	/* Start Search Results Page Output */
    	echo '<h1>Search Results</h1>';	?>
      <?php if (have_posts()) : ?>
      <h6><?php printf(__('Search results for: %s', 'instaparent' ), '<span>' . get_search_query() . '</span>'); ?></h6>
      <?php while (have_posts()) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header>
          <h1 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'instaparent'), the_title_attribute('echo=0')); ?>">
            <?php the_title(); ?>
            </a></h1>
          <div class="post-meta">
            <?php instaparent_post_meta_data(); ?>
            <?php if ( comments_open() ) : ?>
            <span class="comments-link"> <span class="mdash">&mdash;</span>
            <?php comments_popup_link(__('No Comments &darr;', 'instaparent'), __('1 Comment &darr;', 'instaparent'), __('% Comments &darr;', 'instaparent')); ?>
            </span>
            <?php endif; ?>
          </div>
          <!-- end of .post-meta --> 
        </header>
        <div class="post-entry">
          <?php the_excerpt(); ?>
          <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'instaparent'), 'after' => '</div>')); ?>
        </div>
        <!-- end of .post-entry -->
        
        <footer class="post-edit">
          <?php edit_post_link(__('Edit', 'instaparent')); ?>
        </footer>
      </article>
      <!-- end of #post-<?php the_ID(); ?> -->
      
      <?php endwhile; ?>
      <?php if (  $wp_query->max_num_pages > 1 ) : ?>
      <div class="navigation">
        <div class="previous">
          <?php next_posts_link( __( '&#8249; Older posts', 'instaparent' ) ); ?>
        </div>
        <div class="next">
          <?php previous_posts_link( __( 'Newer posts &#8250;', 'instaparent' ) ); ?>
        </div>
      </div>
      <!-- end of .navigation -->
      <?php endif; ?>
      <?php endif; ?>
      <?php 
	}
	
	
		if(is_tag())
		{	/* Start Tag Page Output */
		?>
      <h1 class="archive-title"><?php printf( __( 'Tag Archives: %s', 'instaparent' ), '<span>' . single_tag_title( '', false ) . '</span>' ); ?></h1>
      <?php if ( tag_description() ) : // Show an optional tag description ?>
      <div class="archive-meta"><?php echo tag_description(); ?></div>
      <?php endif;
			echo '<hr class="grey" />';
			/* End Tag Page Output */
		}
		else
		{	
			if(is_category( $category ))
			{
			/* Start Category Page Output */	
			echo '<h1 class="pull-left">';
			global $wp_query;
			printf( __( 'Articles posted in <span>%s</span>', 'instaparent' ), single_cat_title( '', false ) );
			echo '</h1>';
			echo '<h1 class="results pull-right">';
			printf( __( '%s articles', 'instaparent' ), $wp_query->found_posts );
			echo '</h1>';
			echo '<div class="clearfix"></div><hr class="grey" />';
			/* End Category Page Output */
			
			}
			else
			{
				if(is_author($author))
				{ 
				/* Start Author Page Output */
				?>
      <?php global $wp_query; 
                
                $curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
                
                        ?>
      <?php if( $curauth->user_description ) : ?>
      <div class="section-about-the-author author-archive row-fluid">
        <div class="author-avatar span2"> <?php echo get_avatar($curauth->user_email, '77'); ?> </div>
        <!-- END .author-avatar -->
        
        <div class="author-content span10">
          <h2 class="author-name"><?php echo $curauth->display_name; ?></h2>
          <span class="results">&nbsp;&nbsp;-&nbsp;&nbsp;<?php printf( __( '%s posts', 'instaparent' ), $wp_query->found_posts ); ?></span>
          <p><?php echo $curauth->user_description; ?></p>
        </div>
        <!-- END .author-content -->
        <div class="clear"></div>
      </div>
      <!-- END .section-about-the-author -->
      
      <?php else : ?>
      <h2 class="archive-title"> <span class="results"><?php printf( __( '%s posts', 'instaparent' ), $wp_query->found_posts ); ?></span>
        <?php global $curauth; printf( __( 'Posts by <span>%s</span>', 'instaparent' ), $curauth->display_name ); ?>
      </h2>
      <?php endif; ?>
      <?php 
				echo '<hr class="grey" />'; 
				/* End Author Page Output */
				
				}
				else
				{					
					if(is_archive())
					{ /* Start Archive Page Output */?>
      <h1>
        <?php if ( is_day() ) : ?>
        <?php printf( __( 'Daily Archives: %s', 'instaparent' ), '<span>' . get_the_date() . '</span>' ); ?>
        <?php elseif ( is_month() ) : ?>
        <?php printf( __( 'Monthly Archives: %s', 'instaparent' ), '<span>' . get_the_date( 'F Y' ) . '</span>' ); ?>
        <?php elseif ( is_year() ) : ?>
        <?php printf( __( 'Yearly Archives: %s', 'instaparent' ), '<span>' . get_the_date( 'Y' ) . '</span>' ); ?>
        <?php else : ?>
        <?php _e( 'Blog Archives', 'instaparent' ); ?>
        <?php endif; ?>
      </h1>
      <?php
					  /* End Archive Page Output */
					}
					else
					{						
						$isBlogList = true;
					}
				}
			}
		}
	 
	if(is_tag() || is_category( $category ) || is_author($author) || is_archive() ||$isBlogList && !is_single($post) && !is_search() )
	{
		
						/* Start Standard List of Blog Posting Output */
						?>
      <?php if (have_posts()) : ?>
      <?php while (have_posts()) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php if ( has_post_thumbnail()) : ?>
        <a class="image-wrapper" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
        <?php the_post_thumbnail(); ?>
        </a>
        <?php endif; ?>
        <header>
          <h2 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'instaparent'), the_title_attribute('echo=0')); ?>">
            <?php the_title(); ?>
            </a></h2>
          <div class="post-meta">
            <?php instaparent_post_meta_data(); ?>
            <?php if ( comments_open() ) : ?>
            <span class="comments-link"> <span class="mdash">&mdash;</span>
            <?php comments_popup_link(__('No Comments &darr;', 'instaparent'), __('1 Comment &darr;', 'instaparent'), __('% Comments &darr;', 'instaparent')); ?>
            </span>
            <?php endif; ?>
          </div>
          <!-- end of .post-meta --> 
        </header>
        <div class="post-entry">
          <?php the_content(__('Read more &#8250;', 'instaparent')); ?>
          <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'instaparent'), 'after' => '</div>')); ?>
        </div>
        <!-- end of .post-entry -->
        
        <footer class="post-data">
          <?php the_tags(__('Tagged with:', 'instaparent') . ' ', ', ', '<br />'); ?>
          <?php printf(__('Posted in %s', 'instaparent'), get_the_category_list(', ')); ?> </footer>
        <!-- end of .post-data -->
        
        <div class="post-edit">
          <?php edit_post_link(__('Edit', 'instaparent')); ?>
        </div>
      </article>
      <!-- end of #post-<?php the_ID(); ?> -->
      <hr class="grey" />
      <?php endwhile; ?>
      <?php if (  $wp_query->max_num_pages > 1 ) : ?>
      <div class="navigation">
        <div class="previous">
          <?php next_posts_link( __( '&#8249; Older posts', 'instaparent' ) ); ?>
        </div>
        <div class="next">
          <?php previous_posts_link( __( 'Newer posts &#8250;', 'instaparent' ) ); ?>
        </div>
        <div class="clearfix"></div>
      </div>
      <!-- end of .navigation -->
      <?php endif; ?>
      
      <?php endif; ?>
      <?php 
						/* End Standard List of Blog Posting Output */		
						
	}
	 	
	?>
    </article>
    <aside class="span3 main-sidebar module shadow">
      <div class="pd2">
        <?php if ( is_active_sidebar( 'main-sidebar' ) ) : ?>
        <?php dynamic_sidebar( 'main-sidebar' ); ?>
        <?php endif; ?>
      </div>
    </aside>
  </div>
</article>
<?php get_footer(); ?>
