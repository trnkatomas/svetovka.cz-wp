<?php get_header(); ?>

<div class="content thin">
											        
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
		<div id="post-<?php the_ID(); ?>" <?php post_class('single'); ?>>
		
			<?php $post_format = get_post_format(); ?>
			
			<?php if ( $post_format == 'video' ) : ?>
			
				<?php if ($pos=strpos($post->post_content, '<!--more-->')): ?>
		
					<div class="featured-media">
					
						<?php
								
							// Fetch post content
							$content = get_post_field( 'post_content', get_the_ID() );
							
							// Get content parts
							$content_parts = get_extended( $content );
							
							// oEmbed part before <!--more--> tag
							$embed_code = wp_oembed_get($content_parts['main']); 
							
							echo $embed_code;
						
						?>
					
					</div> <!-- /featured-media -->
				
				<?php endif; ?>
				
			<?php elseif ( $post_format == 'gallery' ) : ?>
			
				<div class="featured-media">	
	
					<?php fukasawa_flexslider('post-image'); ?>
					
					<div class="clear"></div>
					
				</div> <!-- /featured-media -->
							
			<?php elseif ( has_post_thumbnail() ) : ?>
				<!--	#commenting out featured image in the single post
				<div class="featured-media">
		
					<?php the_post_thumbnail('post-image'); ?>
					
				</div>--> <!-- /featured-media -->
					
			<?php endif; ?>
			
			<div class="post-inner">
				
				<div class="post-header">
													
					<h1 class="post-title"><?php the_title(); ?></h1>
															
				</div> <!-- /post-header -->
				    
			    <div class="post-content">
			    
			    	<?php 
						if ($post_format == 'video') { 
							$content = $content_parts['extended'];
							$content = apply_filters('the_content', $content);
							echo $content;
						} else {
                          $categories = get_the_category();
                          $cislo = false;
				          if ($categories) {
                              foreach($categories as $category) {
                                  if ($category->slug == 'cislo') {
                      	              $cislo = true;
                        		      break;
                                  }
                              }                            
                          }  	
                          if ($cislo) {
                             $content = get_the_content();              
                             $content = apply_filters( 'the_content', $content );                                                         
    						 $content = str_replace( ']]>', ']]&gt;', $content );                             
                             $insert_index = strpos($content, 'Úvodník');
                             $for_sku = get_year_and_month_from_post_tag($post->ID);
                             $product = wc_get_product( wc_get_product_id_by_sku($for_sku) );
                             $cats = $product->get_category_ids();
				             $ebook_id = get_term_by( 'slug', "ebook", 'product_cat' );
				             $ebook = "";
				             if ($ebook_id) {
                                $num = intval($ebook_id->term_id);
                                $ebook = (in_array($num, $cats)) ? "e-book" : "";
				             }
					         $price = $product->get_price();
                             $prod_id = $product->get_id();
                             $insert_text = '<div class="woocommerce columns-1" style="margin-bottom: -200px;float: right;">';
			                 $insert_text .= '<ul class="products columns-1">';
			                 $insert_text .= '<li class="product type-product status-publish has-post-thumbnail product-type-simple">';
                             $insert_text .= "<a href='". (($product->is_in_stock()) ? "/obchod/?add-to-cart={$prod_id}'" : "#' style='pointer-events: none;'") . "data-quantity='1' class='button product_type_simple add_to_cart_button ajax_add_to_cart' data-product_id='{$prod_id}' data-product_sku='' aria-label='Přidat do košíku' rel='nofollow'>";
                            $insert_text .= ($product->is_in_stock()) ? "Koupit {$ebook} za {$price} Kč</a>" : "Vyprodano</a>";
							 $insert_text .= '</li></ul></div><div class="clear_column"></div>';                             
							 echo substr($content, 0, $insert_index - 83) . $insert_text . substr($content, $insert_index - 83);
                          } else {                             
						  	 $content = get_the_content();              
                             $tags = get_the_tags();
                             $tag_array = array();
                             foreach ($tags as $t) {
                               $tag_array[] = $t->name;                               
                             }   
                             $categories = get_the_category();
                             $skip = false;
                             if ($categories) {
                               foreach($category as $categories) { 
                             		$skip = $skip || $category->slug == "oznameni";  
                               }
                             }
                             $custom_query = new WP_Query(array(
			  						'category_name' => 'cislo',
                                    'tag_slug__and' => $tag_array,	
							        'posts_per_page'=> '1',
	                				'order' => 'ASC',
	                				'orderby' => 'date',
							));                            
                            if (!$skip && $custom_query->found_posts) {
                             
                             while($custom_query->have_posts()) {
                              $custom_query->the_post();
							 							  $content .= "<p><a style='float:right' href='".get_the_permalink(get_the_ID())."'>Zpět na číslo</a></p><br>";
                            }
                             $custom_query->reset_postdata();
                           }
                            $content = apply_filters( 'the_content', $content );                                                         
    												$content = str_replace( ']]>', ']]&gt;', $content );                             
                            echo $content;
                          }
						}
					?>
			    
			    </div> <!-- /post-content -->
			    
			    <div class="clear"></div>
				
				<div class="post-meta-bottom">
				
					<?php 
				    	$args = array(
							'before'           => '<div class="clear"></div><p class="page-links"><span class="title">' . __( 'Pages:','fukasawa' ) . '</span>',
							'after'            => '</p>',
							'link_before'      => '<span>',
							'link_after'       => '</span>',
							'separator'        => '',
							'pagelink'         => '%',
							'echo'             => 1
						);
			    	
			    		wp_link_pages($args); 
					?>
				
					<ul>
						<li class="post-date"><a href="<?php the_permalink(); ?>"><?php the_date(get_option('date_format')); ?></a></li>
						<?php if (has_category()) : ?>
							<li class="post-categories"><?php _e('In','fukasawa'); ?> <?php the_category(', '); ?></li>
						<?php endif; ?>
						<?php if (has_tag()) : ?>
							<li class="post-tags"><?php the_tags('', ' '); ?></li>
						<?php endif; ?>
						<?php edit_post_link('Edit post', '<li>', '</li>'); ?>
					</ul>
					
					<div class="clear"></div>
					
				</div> <!-- /post-meta-bottom -->
			
			</div> <!-- /post-inner -->
			
			<?php
				$prev_post = get_previous_post();
				$next_post = get_next_post();
			?>
			
			<div class="post-navigation">
			
				<?php
				if (!empty( $prev_post )): ?>
				
					<a class="post-nav-prev" title="<?php _e('Previous post', 'fukasawa'); echo ': ' . esc_attr( get_the_title($prev_post) ); ?>" href="<?php echo get_permalink( $prev_post->ID ); ?>">
						<p>&larr; <?php _e('Previous post', 'fukasawa'); ?></p>
					</a>
				<?php endif; ?>
				
				<?php
				if (!empty( $next_post )): ?>
					
					<a class="post-nav-next" title="<?php _e('Next post', 'fukasawa'); echo ': ' . esc_attr( get_the_title($next_post) ); ?>" href="<?php echo get_permalink( $next_post->ID ); ?>">					
						<p><?php _e('Next post', 'fukasawa'); ?> &rarr;</p>
					</a>
			
				<?php endif; ?>
				
				<div class="clear"></div>
			
			</div> <!-- /post-navigation -->
								
			<?php comments_template( '', true ); ?>
		
		</div> <!-- /post -->
									                        
   	<?php endwhile; else: ?>

		<p><?php _e("We couldn't find any posts that matched your query. Please try again.", "fukasawa"); ?></p>
	
	<?php endif; ?>    

</div> <!-- /content -->
		
<?php get_footer(); ?>