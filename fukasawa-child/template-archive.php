<?php
/*
Template Name: Archive Template
*/
?>

<?php get_header(); ?>

<div class="content">

	<div class="page-title">
			
		<div class="section-inner">
			
		  	<h4><?php if ( is_day() ) : ?>
				<?php echo get_the_date( get_option('date_format') ); ?>
			<?php elseif ( is_month() ) : ?>
				<?php echo get_the_date('F Y'); ?>
			<?php elseif ( is_year() ) : ?>
				<?php echo get_the_date('Y'); ?>
			<?php elseif ( is_category() ) : ?>
				<?php printf( __( 'Category: %s', 'fukasawa' ), '' . single_cat_title( '', false ) . '' ); ?>
			<?php elseif ( is_tag() ) : ?>
				<?php printf( __( 'Tag: %s', 'fukasawa' ), '' . single_tag_title( '', false ) . '' ); ?>
			<?php elseif ( is_author() ) : ?>
				<?php $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author)); ?>
				<?php printf( __( 'Author: %s', 'fukasawa' ), $curauth->display_name ); ?>
			<?php else : ?>
			  <?php /*_e( 'Archive', 'fukasawa' );*/ echo "Ročník "; ?>
			<?php endif; ?>
			
			<?php
			$paged = (get_query_var('paged')) ? get_query_var('paged') : intval(date("Y"));
			$wp_query = new WP_Query(array(
			  		'category_name' => 'cislo',
			  		//'paged' => $paged,
			  		'tag' => ''.$paged,	
			        'posts_per_page'=> '11',
	                'order' => 'ASC',
	                'orderby' => 'date',
					));	// select only cislo 								 
			//echo $paged;
			echo $wp_query->query_vars["tag"];   
		
			
            /*if ( "1" < $wp_query->max_num_pages ) :*/ ?>
			
			  <span><?php
				/*printf( __('Page %s of %s', 'fukasawa'), $paged, $wp_query->max_num_pages );*/
				for ($i = 2005; $i <= date('Y'); $i++){
				  $link = '<a href="'. home_url("archiv/page/") . $i .'">';
				  if ( $i == $wp_query->query_vars["tag"]){
					$link .= "<strong>".$i."</strong>";
				  } else {
					$link .= $i;
				  }				  
				  $link .= '</a>&nbsp;';
				  echo $link;
				}
				
				?></span>
				
				<div class="clear"></div>
			
			  <?php /*endif;*/ ?></h4>
					
		</div> <!-- /section-inner -->
		
	</div> <!-- /page-title -->
	
	<?php if ( have_posts() ) : ?>
	
		<?php rewind_posts(); ?>
			
		<div class="posts" id="posts">
		  
		  	<!--<div class="post-inner">
												
				<div class="post-header">
																										
					<h2 class="post-title"><?php the_title(); ?></h2>
															
				</div> <!-- /post-header section ->
				    
			    <div class="post-content">
			    
			    	<?php the_content(); ?>
					
			    	<?php wp_link_pages('before=<div class="clear"></div><p class="page-links">' . __('Pages:','fukasawa') . ' &after=</p>&seperator= <span class="sep">/</span> '); ?>
			    
			    </div>
	
			</div> <!-- /post-inner -->
			
			<?php
				$counter = 1;
				while ( have_posts() ) : the_post(); ?>
		  	<?php 			
			if (($counter % 4) == 1 ){
			  echo '<div class="section group" style="width:100%">';
			} ?>
			<?php get_template_part( 'content-archiv', get_post_format() ); ?>
		  	<?php foreach(get_the_tags() as $tag){
			  if ($tag->slug == "dvojcislo") {
			  	$counter++;
			  }
			}?>
			<?php if (($counter % 4) == 0 || $wp_query->found_posts == $counter){
			  echo '</div> <!-- section group -->';
			}
			
			$counter++;?>
			<?php endwhile; ?>			
							
		</div> <!-- /posts -->
  
  		
  		<?php if ( $wp_query->query_vars["tag"] > 2004 ) : ?>
			
			<div class="archive-nav">
			
				<div class="section-inner">
			
					<!-- <?php echo get_previous_posts_link( __('Následující ročník', 'fukasawa') . ' &raquo;'); ?> -->
				  
				  	<!-- <?php echo get_next_posts_link( '&laquo; ' . __('Předchozí ročník', 'fukasawa') ); ?> -->
					
				  
					<?php $output = esc_url( get_next_posts_page_link( date('Y') ) );
						if ( $wp_query->query_vars["tag"] < date('Y') ) {
				  			echo '<a href="' . $output .'" class="archive-nav-older fight">Následující ročník &raquo;</a>';
						}
					?>
				  
  				  	<?php $output = esc_url( get_previous_posts_page_link( 2004 ) );
						if ( $wp_query->query_vars["tag"] > 2005 ) {
				  			echo '<a href="' . $output .'" class="archive-nav-newer fleft">&laquo; Předchozí ročník</a>';
						}
					?>				  
				  	
					<div class="clear"></div>
				
				</div>
				
			</div> <!-- /post-nav archive-nav -->
							
		<?php endif; ?>
				
	<?php endif; ?>

</div> <!-- /content -->

<?php get_footer(); ?>