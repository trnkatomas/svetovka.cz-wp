<div class="post-container">

	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
		<?php 
			$post_title = get_the_title();
			if ( !empty( $post_title ) ) : 
		?>
					
			<div class="post-header">
				
			    <h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
			    	    
			</div> <!-- /post-header -->
		
		<?php endif; ?>
		<div class="post-excerpt">
			<ul><li class="post-tags">
			<?php
				$categories = get_the_category();
				$show = true;
				if ($categories) {
                  foreach($categories as $category) {
                      if ($category->slug == 'cislo') {
                      	$show = false;
                        break;
                      }
                      echo '<a>'.$category->name . '</a> ';                       
                  }  	
				}

				$posttags = get_the_tags();
				if ($posttags && $show) {
  					foreach($posttags as $tag) {
    					echo '<a>#'.$tag->name . '</a> '; 
  					}
				}
			?>
			</li>
			</ul>
		</div>
		<div class="post-excerpt">
			
			<?php the_excerpt(); ?>
		
		</div>
		<?php if ( has_post_thumbnail() ) : ?>
		
			<a class="featured-media" title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>">	
				
				<?php the_post_thumbnail('post-thumb'); ?>
				
			</a> <!-- /featured-media -->
				
		<?php endif; ?>
		
		<?php if ( empty( $post_title ) ) : ?>
			    
		    <div class="posts-meta">
		    
		    	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_time(get_option('date_format')); ?></a>
		    	
	    	</div>
	    
	    <?php endif; ?>
	
	</div> <!-- /post -->

</div> <!-- /post-container -->