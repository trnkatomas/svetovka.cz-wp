<?php 
$dvojcislo = false;
foreach(get_the_tags() as $tag){
  if ($tag->slug == "dvojcislo"){
	//$dvojcislo = true;
  }
};?>

<?php if ($dvojcislo) : ?>
<div class="col span_2_of_4"> <!--"post-container-archiv">-->
<?php else : ?>
<div class="col span_1_of_4"> <!--"post-container-archiv">-->
<?php endif; ?>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?> ">
	
		<?php if ( has_post_thumbnail() ) : ?>
		
			<a class="featured-media" title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>">	
				
				<?php the_post_thumbnail('post-thumb'); ?>
				
			</a> <!-- /featured-media -->
				
		<?php endif; ?>
		
		<?php 
			$post_title = get_the_title();
			if ( !empty( $post_title ) ) : 
		?>
					
			<div class="post-header">
				
			    <h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
			    	    
			</div> <!-- /post-header -->
		
		<?php endif; ?>
		
		<div class="post-excerpt">
			<div class="woocommerce columns-1">
			<ul class="products columns-1">
			<?php
				$product = wc_get_product( wc_get_product_id_by_sku(get_the_date('Y').'-'.get_the_date('m')) );
				$cats = $product->get_category_ids();
				$ebook_id = get_term_by( 'slug', "ebook", 'product_cat' );
				$ebook = "";
				if ($ebook_id) {
                  $num = intval($ebook_id->term_id);
                  $ebook = (in_array($num, $cats)) ? "ebook" : "";
				}
			?> 
			<li class="product type-product status-publish has-post-thumbnail product-type-simple">	
			<a <?php echo (($product->is_in_stock()) ? "href='/obchod/?add-to-cart={$product->get_id()}'": "#' style='pointer-events: none;'") ?> data-quantity="1" 
				class="button product_type_simple add_to_cart_button ajax_add_to_cart"
				data-product_id="<?php echo wc_get_product_id_by_sku(get_the_date('Y').'-'.get_the_date('m')); ?>" data-product_sku=""
				aria-label="Přidat číslo do košíku" rel="nofollow">
               <?php echo ($product->is_in_stock()) ? "Koupit {$ebook} za {$product->get_regular_price()} Kč": "Vyprodano"; ?> </a>
			</li>
			</ul>
			</div>
		  <?php //the_excerpt(); ?>
		
		</div>
		
		<?php if ( empty( $post_title ) ) : ?>
			    
		    <div class="posts-meta">
		    
		    	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_time(get_option('date_format')); ?></a>
		    	
	    	</div>
	    
	    <?php endif; ?>
	
	</div> <!-- /post -->

</div> <!-- /post-container -->