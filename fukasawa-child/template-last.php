<?php
/*
Template Name: Last Template
*/
?>

<?php get_header(); ?>

<div class="content thin">
	<?php 
	/*$args = array(
    	'numberposts' => 1,
    	'offset' => 0,
    	'category' => 0,
	  	'category_name' => 'cislo',
	    'orderby' => 'post_date',//'post_modified',
    	'order' => 'DESC',
    	'post_type' => 'post',
    	'post_status' => 'publish', 
    	'suppress_filters' => true );

	$recent_posts = wp_get_recent_posts( $args, ARRAY_A );
	*/

	$wp_query = new WP_Query(array(
		'category_name' => 'cislo',
		"numberposts" => 1,
		"offset" => 0,
		"category" => 0,
		"post_type" => "post",
		'order' => 'DESC',
		'orderby' => 'post_date',
		//"supress_filters" => true,
		'posts_per_page' => 1
	));	// select only cislo 								 
	//echo $paged;
	
	?>
											        
	<?php if ($wp_query->have_posts()) : while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
		
		<div id="post-<?php the_ID(); ?>" <?php post_class('single'); ?>>
			
			<div class="post-inner">
				
				<div class="post-header">
													
					<h1 class="post-title"><?php the_title(); ?></h1>
															
				</div> <!-- /post-header -->
				    
			    <div class="post-content">
			    
					<?php 
						global $more; 
						$more = -1;
						$content = get_the_content();              
						$content = apply_filters( 'the_content', $content );                                                         
						$content = str_replace( ']]>', ']]&gt;', $content );                             
						$insert_index = strpos($content, 'Úvodník');
						$product = wc_get_product( wc_get_product_id_by_sku(get_the_date('Y').'-'.get_the_date('m')) );
						$price = $product->get_regular_price();
						$prod_id = $product->get_id();
						$insert_text = '<div class="woocommerce columns-1" style="margin-bottom: -200px;float: right;">';
						$insert_text .= '<ul class="products columns-1">';
						$insert_text .= '<li class="product type-product status-publish has-post-thumbnail product-type-simple">';
						$insert_text .= "<a href='". (($product->is_in_stock()) ? "/obchod/?add-to-cart={$prod_id}'" : "#' style='pointer-events: none;'") . "data-quantity='1' class='button product_type_simple add_to_cart_button ajax_add_to_cart' data-product_id='{$prod_id}' data-product_sku='' aria-label='Přidat do košíku' rel='nofollow'>";
						$insert_text .= ($product->is_in_stock()) ? "Koupit za {$price} Kč</a>" : "Vyprodano</a>";
						$insert_text .= '</li></ul></div><div class="clear_column"></div>';                             
						echo substr($content, 0, $insert_index - 83) . $insert_text . substr($content, $insert_index - 83);						
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
								
			<?php comments_template( '', true ); ?>
		
		</div> <!-- /post -->
									                        
   	<?php endwhile; else: ?>

		<p><?php _e("We couldn't find any posts that matched your query. Please try again.", "fukasawa"); ?></p>
	
	<?php endif; ?>    

</div> <!-- /content -->
		
<?php get_footer(); ?>