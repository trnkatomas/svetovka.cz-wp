<?php
/*
Template Name: Search results Template
*/
?>
<?php get_header(); ?>

<div class="content thin">		
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>				
	
		<div <?php post_class('post single'); ?>>
		
			<?php if ( has_post_thumbnail() ) : ?>
			
				<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail_size' ); $thumb_url = $thumb['0']; ?>
		
				<div class="featured-media">
		
					<?php the_post_thumbnail('post-image'); ?>
					
				</div> <!-- /featured-media -->
					
			<?php endif; ?>
		    
			
			<div class="post-inner">
												
				<div class="post-header">
																										
					<h1 class="post-title"><?php the_title(); ?></h1>
															
				</div> <!-- /post-header section -->
				    
			    <div class="post-content">
			    
			    	<?php the_content(); ?>
					
			    	<?php wp_link_pages('before=<div class="clear"></div><p class="page-links">' . __('Pages:','fukasawa') . ' &after=</p>&seperator= <span class="sep">/</span> '); ?>
			    
			    </div>
	
			</div> <!-- /post-inner -->
		  	<div class="results"></div>
		  <script type="text/javascript">
			var $ = jQuery;
			$(".uwpqsf_class").each(function(e){
			  $(this).children("span:first").on("click", function(e){
				$(this).parent().children().not("span:first").toggle()
			  })
			})				
		  </script>
		</div> <!-- /post -->
	<?php endwhile; else: ?>
   	
		<p><?php _e("We couldn't find any posts that matched your query. Please try again.", "fukasawa"); ?></p>

	<?php endif; ?> 


	<div class="clear"></div>
	
</div> <!-- /content -->
								
<?php get_footer(); ?>