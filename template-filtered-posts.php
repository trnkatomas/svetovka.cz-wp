<?php
/*
Template Name: Custom Filtered Posts
*/

get_header(); 

// 1. Retrieve the saved meta data (the selected IDs)
$selected_cats = get_post_meta( get_the_ID(), '_custom_selected_cats', true );
$selected_tags = get_post_meta( get_the_ID(), '_custom_selected_tags', true );

// 2. Build the query arguments
$args = array(
    'post_type'      => 'post',
    'posts_per_page' => 10,
    'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
    'tax_query'      => array(
        'relation' => 'AND', // Matches BOTH category AND tag requirements (change to OR if preferred)
    ),
);

// Add Category filter if any are selected
if ( ! empty( $selected_cats ) && is_array( $selected_cats ) ) {
    $args['tax_query'][] = array(
        'taxonomy' => 'category',
        'field'    => 'term_id',
        'terms'    => $selected_cats,
    );
}

// Add Tag filter if any are selected
if ( ! empty( $selected_tags ) && is_array( $selected_tags ) ) {
    $args['tax_query'][] = array(
        'taxonomy' => 'post_tag',
        'field'    => 'term_id',
        'terms'    => $selected_tags,
    );
}

// 3. Execute the Query
$custom_query = new WP_Query( $args );

// 4. The Loop
?>

<?php get_header(); ?>

<div class="content">
	
    <div class="filtered-posts-list">
	    <?php if ($custom_query->have_posts()) : ?>

        <?php
		    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		    $total_post_count = $custom_query->post_count;
		    $total_pages = ceil( $total_post_count / $posts_per_page );
		
		    if ( "0" < $paged ) : ?>
			    <div class="page-title">
			
				<h4><?php printf( __('Page %s of %s', 'fukasawa'), $paged, $custom_query->max_num_pages ); ?></h4>
				
			    </div> <!-- /page-title -->
			
			    <div class="clear"></div>
		
		    <?php endif; ?>
	
		    <div class="posts" id="posts">
				
	    	    <?php while ($custom_query->have_posts()) : $custom_query->the_post(); ?>
	    	
	    		    <?php get_template_part( 'content', get_post_format() ); ?>
	    			        		            
	            <?php endwhile; ?>
        	                    
	        </div> <!-- /posts -->
    
    	<?php if ( $custom_query->max_num_pages > 1 ) : ?>
		
            <div class="archive-nav">
                    
                <?php echo get_next_posts_link( __('Older posts', 'fukasawa') . ' &rarr;', $custom_query->max_num_pages); ?>
                    
                <?php echo get_previous_posts_link( '&larr; ' . __('Newer posts', 'fukasawa')); ?>
                
                <div class="clear"></div>
                            
            </div> <!-- /archive-nav -->
						
	    <?php endif; ?>

        <?php wp_reset_postdata(); // Important: reset global post data ?>
        <?php endif; ?>
    </div>

</div>

<?php get_footer(); ?>