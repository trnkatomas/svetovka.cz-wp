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

<div id="primary" class="content-area">
    <main id="main" class="site-main">

    <h1><?php the_title(); ?></h1>
    <div class="page-content">
        <?php 
        // Show the main page content first (optional)
        while ( have_posts() ) : the_post();
            the_content();
        endwhile; 
        ?>
    </div>

    <div class="filtered-posts-list">
        <?php if ( $custom_query->have_posts() ) : ?>
            <div class="posts-grid">
                <?php while ( $custom_query->have_posts() ) : $custom_query->the_post(); ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        </header>
                        <div class="entry-summary">
                            <?php the_excerpt(); ?>
                        </div>
                    </article>

                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <?php 
                echo paginate_links( array(
                    'total' => $custom_query->max_num_pages
                ) ); 
                ?>
            </div>

            <?php wp_reset_postdata(); // Important: reset global post data ?>
        
        <?php else : ?>
            <p>No posts found matching the selected criteria.</p>
        <?php endif; ?>
    </div>

    </main>
</div>

<?php get_footer(); ?>