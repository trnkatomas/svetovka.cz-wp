<?php

/** custom functions for PLAV.cz **/

// Fukasawa theme options
class fukasawa_Customize_Plav {
  
   public static function plav_header_output() {
	$args = array(
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

	/* month regex */	
	$month_re = "/^(?P<month>[1-9]|1[012])$/";

	/* year regex */	
	$year_re = "/^(?P<year>20[0-3]\d)$/";

	foreach ($recent_posts as $post){
	  $month = 0;
	  $year = 0;  
	  
	  //d($post);
	  foreach (wp_get_post_tags( $post['ID']) as $tag){
		$tag_name = $tag->slug;		
		if (!$month && preg_match($month_re, $tag_name, $match)){
		  $month = $match['month'];
		};
		if (!$year && preg_match($year_re, $tag_name, $match)){
		  $year = $match['year'];
		};
	  };
		
	  if ($month && $year){
		 break;
      }
	}

	 echo '<link rel="stylesheet" href="'.get_template_directory_uri().'/css/'.$month.'.css" type="text/css" media="all">';   
   }
}

add_action( 'wp_head' , array( 'fukasawa_Customize_Plav' , 'plav_header_output' ) );

remove_filter( 'the_content', 'wpautop' );
//remove_all_filters( 'the_content');

remove_filter( 'the_excerpt', 'wpautop' );

/**
 * Filter the except length to 20 words.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function wpdocs_custom_excerpt_length( $length ) {
    return 100;
}
add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );

// God bless this awsome WP hacking guy
// https://wordpress.stackexchange.com/questions/141125/allow-html-in-excerpt
function wpse_allowedtags() {
    // Add custom tags to this string
	return '<script>,<style>,<em>,<i>,<ul>,<ol>,<li>,<p>,<video>,<audio>'; 
}

if ( ! function_exists( 'wpse_custom_wp_trim_excerpt' ) ) : 

    function wpse_custom_wp_trim_excerpt($wpse_excerpt) {
    $raw_excerpt = $wpse_excerpt;
	if ( '' == $wpse_excerpt ) {
        $wpse_excerpt = get_the_content('');
		$wpse_excerpt = strip_shortcodes( $wpse_excerpt );
		$wpse_excerpt = apply_filters('the_content', $wpse_excerpt);
		$wpse_excerpt = str_replace(']]>', ']]&gt;', $wpse_excerpt);
		$wpse_excerpt = strip_tags($wpse_excerpt, wpse_allowedtags()); /*IF you need to allow just certain tags. Delete if all tags are allowed */
		// bruteforce remove <br> tag
		$wpse_excerpt = str_replace('<br>', '', $wpse_excerpt);
          
		//Set the excerpt word count and only break after sentence is complete.
		$excerpt_word_count = 75;
		$excerpt_length = apply_filters('excerpt_length', $excerpt_word_count); 
		$tokens = array();
		$excerptOutput = '';
		$count = 0;

		// Divide the string into tokens; HTML tags, or words, followed by any whitespace
		preg_match_all('/(<[^>]+>|[^<>\s]+)\s*/u', $wpse_excerpt, $tokens);

		foreach ($tokens[0] as $token) { 
			if ($count >= $excerpt_length && preg_match('/[\,\;\?\.\!]\s*$/uS', $token)) { 
				// Limit reached, continue until , ; ? . or ! occur at the end
				$excerptOutput .= trim($token);
				break;
			}

			// Add words to complete sentence
			$count++;

			// Append what's left of the token
			$excerptOutput .= $token;
		}

		$wpse_excerpt = trim(force_balance_tags($excerptOutput));

		$excerpt_end = ' <a href="'. esc_url( get_permalink() ) . '">' . '&nbsp;&raquo;&nbsp;' . sprintf(__( 'Read more about: %s &nbsp;&raquo;', 'wpse' ), get_the_title()) . '</a>'; 
		$excerpt_more = apply_filters('excerpt_more', ' ' . $excerpt_end); 

		// After the content
		$wpse_excerpt .= $excerpt_more; /*Add read more in new paragraph */

		return $wpse_excerpt;   

	}
	return apply_filters('wpse_custom_wp_trim_excerpt', $wpse_excerpt, $raw_excerpt);
}

endif; 

remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'wpse_custom_wp_trim_excerpt'); 

remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );

function print_filters_for( $hook = '' ) {
    global $wp_filter;
    if( empty( $hook ) || !isset( $wp_filter[$hook] ) )
        return;
}

?>
