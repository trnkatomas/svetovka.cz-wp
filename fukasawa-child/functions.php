<?php

// Theme setup
add_action( 'after_setup_theme', 'fukasawa_setup' );

function fukasawa_setup() {
	
	// Automatic feed
	add_theme_support( 'automatic-feed-links' );
	
	// Set content-width
	global $content_width;
	if ( ! isset( $content_width ) ) $content_width = 620;
	
	// Post thumbnails
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size ( 88, 88, true );
	
	add_image_size( 'post-image', 973, 9999 );
	add_image_size( 'post-thumb', 508, 9999 );
	
	// Post formats
	add_theme_support( 'post-formats', array( 'gallery', 'image', 'video' ) );
		
	// Jetpack infinite scroll
	add_theme_support( 'infinite-scroll', array(
		'type' 				=> 		'click',
	    'container'			=> 		'posts',
		'footer' 			=> 		false,
	) );
	
	// Title tag
	add_theme_support('title-tag');
	
	// Add nav menu
	register_nav_menu( 'primary', __('Primary Menu','fukasawa') );
	
	// Make the theme translation ready
	load_theme_textdomain('fukasawa', get_template_directory() . '/languages');
	
	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable($locale_file) )
	  require_once($locale_file);
	
}


// Register and enqueue Javascript files
function fukasawa_load_javascript_files() {

	if ( !is_admin() ) {		
		wp_enqueue_script( 'masonry' );
		wp_enqueue_script( 'fukasawa_flexslider', get_template_directory_uri().'/js/flexslider.min.js', array('jquery'), '', true );
		wp_enqueue_script( 'fukasawa_global', get_template_directory_uri().'/js/global.js', array('jquery'), '', true );
		if ( is_singular() ) wp_enqueue_script( "comment-reply" );		
	}
}

add_action( 'wp_enqueue_scripts', 'fukasawa_load_javascript_files' );


// Register and enqueue styles
function fukasawa_load_style() {
	if ( !is_admin() ) {
	    wp_enqueue_style( 'fukasawa_googleFonts', '//fonts.googleapis.com/css?family=Lato:400,400italic,700,700italic' );
	    wp_enqueue_style( 'fukasawa_genericons', get_stylesheet_directory_uri() . '/genericons/genericons.css' );
	    wp_enqueue_style( 'fukasawa_style', get_stylesheet_uri() );
	}
}

add_action('wp_print_styles', 'fukasawa_load_style');


// Add editor styles
function fukasawa_add_editor_styles() {
    add_editor_style( 'fukasawa-editor-styles.css' );
    $font_url = '//fonts.googleapis.com/css?family=Lato:400,400italic,700,700italic';
    add_editor_style( str_replace( ',', '%2C', $font_url ) );
}
add_action( 'init', 'fukasawa_add_editor_styles' );


// Add sidebar widget area
add_action( 'widgets_init', 'fukasawa_sidebar_reg' ); 

function fukasawa_sidebar_reg() {
	register_sidebar(array(
	  'name' => __( 'Sidebar', 'fukasawa' ),
	  'id' => 'sidebar',
	  'description' => __( 'Widgets in this area will be shown in the sidebar.', 'fukasawa' ),
	  'before_title' => '<h3 class="widget-title">',
	  'after_title' => '</h3>',
	  'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
	  'after_widget' => '</div><div class="clear"></div></div>'
	));
}


// Add theme widgets
require_once (get_template_directory() . "/widgets/dribbble-widget.php");  
require_once (get_template_directory() . "/widgets/flickr-widget.php");  
require_once (get_template_directory() . "/widgets/recent-comments.php");
require_once (get_template_directory() . "/widgets/recent-posts.php");
require_once (get_template_directory() . "/widgets/video-widget.php");


// Delist the WordPress widgets replaced by custom theme widgets
 function fukasawa_unregister_default_widgets() {
     unregister_widget('WP_Widget_Recent_Comments');
     unregister_widget('WP_Widget_Recent_Posts');
 }
 add_action('widgets_init', 'fukasawa_unregister_default_widgets', 11);


// Check whether the browser supports javascript
function html_js_class () {
    echo '<script>document.documentElement.className = document.documentElement.className.replace("no-js","js");</script>'. "\n";
}
add_action( 'wp_head', 'html_js_class', 1 );


// Add classes to next_posts_link and previous_posts_link
add_filter('next_posts_link_attributes', 'fukasawa_posts_link_attributes_1');
add_filter('previous_posts_link_attributes', 'fukasawa_posts_link_attributes_2');

function fukasawa_posts_link_attributes_1() {
    return 'class="archive-nav-older fleft"';
}
function fukasawa_posts_link_attributes_2() {
    return 'class="archive-nav-newer fright"';
}


// Change the length of excerpts
function fukasawa_custom_excerpt_length( $length ) {
	return 28;
}
add_filter( 'excerpt_length', 'fukasawa_custom_excerpt_length', 999 );


// Change the excerpt ellipsis
function fukasawa_new_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'fukasawa_new_excerpt_more' );


// Add body class if is_mobile
add_filter('body_class','fukasawa_is_mobile_body_class');
 
function fukasawa_is_mobile_body_class( $classes ){
 
    /* using mobile browser */
    if ( wp_is_mobile() ){
        $classes[] = 'wp-is-mobile';
    }
    else{
        $classes[] = 'wp-is-not-mobile';
    }
    return $classes;
}


// Get comment excerpt length
function fukasawa_get_comment_excerpt($comment_ID = 0, $num_words = 20) {
	$comment = get_comment( $comment_ID );
	$comment_text = strip_tags($comment->comment_content);
	$blah = explode(' ', $comment_text);
	if (count($blah) > $num_words) {
		$k = $num_words;
		$use_dotdotdot = 1;
	} else {
		$k = count($blah);
		$use_dotdotdot = 0;
	}
	$excerpt = '';
	for ($i=0; $i<$k; $i++) {
		$excerpt .= $blah[$i] . ' ';
	}
	$excerpt .= ($use_dotdotdot) ? '...' : '';
	return apply_filters('get_comment_excerpt', $excerpt);
}


// Style the admin area
function fukasawa_admin_area_style() { 
   echo '
<style type="text/css">

	#postimagediv #set-post-thumbnail img {
		max-width: 100%;
		height: auto;
	}

</style>';
}

add_action('admin_head', 'fukasawa_admin_area_style');


// Flexslider function for format-gallery
function fukasawa_flexslider($size) {

	if ( is_page()) :
		$attachment_parent = $post->ID;
	else : 
		$attachment_parent = get_the_ID();
	endif;

	if($images = get_posts(array(
		'post_parent'    => $attachment_parent,
		'post_type'      => 'attachment',
		'numberposts'    => -1, // show all
		'post_status'    => null,
		'post_mime_type' => 'image',
                'orderby'        => 'menu_order',
                'order'           => 'ASC',
	))) { ?>
	
		<div class="flexslider">
		
			<ul class="slides">
	
				<?php foreach($images as $image) { 
				
					$attimg = wp_get_attachment_image($image->ID, $size); ?>
					
					<li>
						<?php echo $attimg; ?>
					</li>
					
				<?php }; ?>
		
			</ul>
			
		</div><?php
		
	}
}


// Fukasawa comment function
if ( ! function_exists( 'fukasawa_comment' ) ) :
function fukasawa_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
	
		<?php __( 'Pingback:', 'fukasawa' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'fukasawa' ), '<span class="edit-link">', '</span>' ); ?>
		
	</li>
	<?php
			break;
		default :
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	
		<div id="comment-<?php comment_ID(); ?>" class="comment">
			
			<div class="comment-header">
			
				<?php echo get_avatar( $comment, 160 ); ?>
				
				<div class="comment-header-inner">
											
					<h4><?php echo get_comment_author_link(); ?></h4>
					
					<div class="comment-meta">
						<a class="comment-date-link" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>" title="<?php echo get_comment_date() . ' at ' . get_comment_time(); ?>"><?php echo get_comment_date(get_option('date_format')) ?></a>
					</div> <!-- /comment-meta -->
				
				</div> <!-- /comment-header-inner -->
			
			</div>

			<div class="comment-content post-content">
			
				<?php comment_text(); ?>
				
			</div><!-- /comment-content -->
			
			<div class="comment-actions">
			
				<?php if ( '0' == $comment->comment_approved ) : ?>
				
					<p class="comment-awaiting-moderation fright"><?php _e( 'Your comment is awaiting moderation.', 'fukasawa' ); ?></p>
					
				<?php endif; ?>
				
				<div class="fleft">
			
				<?php 
					comment_reply_link( array( 
						'reply_text' 	=>  	__('Reply','fukasawa'),
						'depth'			=> 		$depth, 
						'max_depth' 	=> 		$args['max_depth'],
						'before'		=>		'',
						'after'			=>		''
						) 
					); 
				?><?php edit_comment_link( __( 'Edit', 'fukasawa' ), '<span class="sep">/</span>', '' ); ?>
				
				</div>
				
				<div class="clear"></div>
			
			</div> <!-- /comment-actions -->
										
		</div><!-- /comment-## -->
				
	<?php
		break;
	endswitch;
}
endif;



// Fukasawa theme options
class fukasawa_Customize {

   public static function fukasawa_register ( $wp_customize ) {
   
      //1. Define a new section (if desired) to the Theme Customizer
      $wp_customize->add_section( 'fukasawa_options', 
         array(
            'title' => __( 'Options for Fukasawa', 'fukasawa' ), //Visible title of section
            'priority' => 35, //Determines what order this appears in
            'capability' => 'edit_theme_options', //Capability needed to tweak
            'description' => __('Allows you to customize theme settings for Fukasawa.', 'fukasawa'), //Descriptive tooltip
         ) 
      );
      
      $wp_customize->add_section( 'fukasawa_logo_section' , array(
		    'title'       => __( 'Logo', 'fukasawa' ),
		    'priority'    => 40,
		    'description' => __('Upload a logo to replace the default site title in the sidebar/header', 'fukasawa'),
	  ) );
      
      
      //2. Register new settings to the WP database...
      $wp_customize->add_setting( 'accent_color', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
         array(
            'default' => '#019EBD', //Default setting/value to save
            'type' => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
            'transport' => 'postMessage', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
            'sanitize_callback' => 'sanitize_hex_color'            
         ) 
      );
	  
	  $wp_customize->add_setting( 'fukasawa_logo', 
      	array( 
      		'sanitize_callback' => 'custom_url_escape' //'esc_url_raw'
      	) 
      );
      
      
      //3. Finally, we define the control itself (which links a setting to a section and renders the HTML controls)...
      $wp_customize->add_control( new WP_Customize_Color_Control( //Instantiate the color control class
         $wp_customize, //Pass the $wp_customize object (required)
         'fukasawa_accent_color', //Set a unique ID for the control
         array(
            'label' => __( 'Accent Color', 'fukasawa' ), //Admin-visible name of the control
            'section' => 'colors', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
            'settings' => 'accent_color', //Which setting to load and manipulate (serialized is okay)
            'priority' => 10, //Determines the order this control appears in for the specified section
         ) 
      ) );
      
      $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'fukasawa_logo', array(
		    'label'    => __( 'Logo', 'fukasawa' ),
		    'section'  => 'fukasawa_logo_section',
		    'settings' => 'fukasawa_logo',
	  ) ) );
      
      //4. We can also change built-in settings by modifying properties. For instance, let's make some stuff use live preview JS...
      $wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
      $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
   }

   public static function fukasawa_header_output() {
      ?>
      
	      <!-- Customizer CSS --> 
	      
	      <style type="text/css">
	           <?php self::fukasawa_generate_css('body a', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('body a:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.main-menu .current-menu-item:before', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.main-menu .current_page_item:before', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.widget-content .textwidget a:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.widget_fukasawa_recent_posts a:hover .title', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.widget_fukasawa_recent_comments a:hover .title', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.widget_archive li a:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.widget_categories li a:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.widget_meta li a:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.widget_nav_menu li a:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.widget_rss .widget-content ul a.rsswidget:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('#wp-calendar thead', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.widget_tag_cloud a:hover', 'background', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.search-button:hover .genericon', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.flex-direction-nav a:hover', 'background-color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('a.post-quote:hover', 'background', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.posts .post-title a:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.post-content a', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.post-content a:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.post-content a:hover', 'border-bottom-color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.post-content blockquote:before', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.post-content fieldset legend', 'background', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.post-content input[type="submit"]:hover', 'background', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.post-content input[type="button"]:hover', 'background', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.post-content input[type="reset"]:hover', 'background', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.page-links a:hover', 'background', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.comments .pingbacks li a:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.comment-header h4 a:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.bypostauthor.commet .comment-header:before', 'background', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.form-submit #submit:hover', 'background-color', 'accent_color'); ?>
	           
	           <?php self::fukasawa_generate_css('.nav-toggle.active', 'background-color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.mobile-menu .current-menu-item:before', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('.mobile-menu .current_page_item:before', 'color', 'accent_color'); ?>
	           
	           <?php self::fukasawa_generate_css('body#tinymce.wp-editor a', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('body#tinymce.wp-editor a:hover', 'color', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('body#tinymce.wp-editor fieldset legend', 'background', 'accent_color'); ?>
	           <?php self::fukasawa_generate_css('body#tinymce.wp-editor blockquote:before', 'color', 'accent_color'); ?>
	      </style> 
	      
	      <!--/Customizer CSS-->
	      
      <?php
   }
   
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
   
   public static function fukasawa_live_preview() {
      wp_enqueue_script( 
           'fukasawa-themecustomizer', // Give the script a unique ID
           get_template_directory_uri() . '/js/theme-customizer.js', // Define the path to the JS file
           array(  'jquery', 'customize-preview' ), // Define dependencies
           '', // Define a version (optional) 
           true // Specify whether to put in footer (leave this true)
      );
   }

   public static function fukasawa_generate_css( $selector, $style, $mod_name, $prefix='', $postfix='', $echo=true ) {
      $return = '';
      $mod = get_theme_mod($mod_name);
      if ( ! empty( $mod ) ) {
         $return = sprintf('%s { %s:%s; }',
            $selector,
            $style,
            $prefix.$mod.$postfix
         );
         if ( $echo ) {
            echo $return;
         }
      }
      return $return;
    }
}

// Setup the Theme Customizer settings and controls...
add_action( 'customize_register' , array( 'fukasawa_Customize' , 'fukasawa_register' ) );

// Output custom CSS to live site
add_action( 'wp_head' , array( 'fukasawa_Customize' , 'fukasawa_header_output' ) );

add_action( 'wp_head' , array( 'fukasawa_Customize' , 'plav_header_output' ) );

// Enqueue live preview javascript in Theme Customizer admin screen
add_action( 'customize_preview_init' , array( 'fukasawa_Customize' , 'fukasawa_live_preview' ) );


/** custom functions for PLAV.cz **/
function custom_url_escape( $url, $protocols=null ) {
    $parsed = wp_parse_url( $url);    
    return $parsed["path"];
}


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
        //return '<script>,<style>,<br>,<em>,<i>,<ul>,<ol>,<li>,<a>,<p>,<img>,<video>,<audio>'; 
  		return '<script>,<style>,<br>,<b>,<em>,<i>,<ul>,<ol>,<li>,<p>,<video>,<audio>'; 
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

                //$pos = strrpos($wpse_excerpt, '</');
                //if ($pos !== false)
                // Inside last HTML tag
                //$wpse_excerpt = substr_replace($wpse_excerpt, $excerpt_end, $pos, 0); /* Add read more next to last word */
                //else
                // After the content
                $wpse_excerpt .= $excerpt_more; /*Add read more in new paragraph */

            return $wpse_excerpt;   

        }
        return apply_filters('wpse_custom_wp_trim_excerpt', $wpse_excerpt, $raw_excerpt);
    }

endif; 

remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'wpse_custom_wp_trim_excerpt'); 

function print_filters_for( $hook = '' ) {
    global $wp_filter;
    if( empty( $hook ) || !isset( $wp_filter[$hook] ) )
        return;

  print '<pre>';
  d( $wp_filter[$hook] );
  print '</pre>';
}

// TODO fix this accoring to this function
// https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/

function intersect_map($a, $b){
  $result = array();
  foreach($a as $key => $value){
    if (array_key_exists($key, $b)){
      $result[$key] = true;
    }
  }
  return $result;
}

function union_map($a, $b){
  $result = array();
  foreach($a as $key => $value){
    $result[$key] = true;    
  }
  foreach($b as $key => $value){
    $result[$key] = true;    
  }
  return $result;
}

function search_ids($keyword, $term){
  $ids = [];
  $tags_in = array(
  	$keyword => $term,
  	"posts_per_page" => -1
  );    
  $posts_query = new WP_Query( );
  $query_results = $posts_query->query($tags_in);     
  foreach ($query_results as $post){
    $ids[$post->ID] = true;
  }    
  return $ids;
}

function my_awesome_func( $data ) {
  $fulltext = $data['text'];
  $years = $data['rok'];
  $issues = $data['cislo'];
  $languages = $data['tag'];
  $cat = $data['rubrika'];
    
  $post_ids = array();
  if (explode(",", $years)[0]){
  	$rok_ids = search_ids("tag__in", 
  						explode(",", $years));
  	$post_ids = ($post_ids) ? intersect_map($rok_ids, $post_ids) :  union_map($rok_ids,  $post_ids);
  }
  if (explode(",", $issues)[0]){
  	$cislo_ids = search_ids("tag__in",
  						  explode(",", $issues));
  	$post_ids = ($post_ids) ? intersect_map($cislo_ids, $post_ids) : union_map($post_ids, $cislo_ids);
  }
  if ($fulltext) {
  	$fulltext_ids = search_ids("s", $fulltext);
  	$post_ids = ($post_ids) ? intersect_map($fulltext_ids, $post_ids) : union_map($post_ids, $fulltext_ids);    
  }
  if (explode(",", $languages)[0]){    
  	$jazyk_ids = search_ids("tag__in", explode(",", $languages));
    $post_ids = ($post_ids) ? intersect_map($jazyk_ids, $post_ids) : union_map($post_ids, $jazyk_ids);
  }
  if (explode(",", $cat)[0]){
  	$cat_ids = search_ids("category__in", explode(",", $cat));
  	$post_ids = ($post_ids) ? intersect_map($cat_ids, $post_ids) : union_map($post_ids, $cat_ids);
  }  
  if ($post_ids){
  	$query_args = array("posts_per_page" => 10,
  					  "post__in" => array_keys($post_ids));
  	$posts_query = new WP_Query( );
  	$query_results = $posts_query->query($query_args);
  }else{
    $query_results = array();
  }
  
  $results = [];
  
  foreach($query_results as $post) {   
    setup_postdata( $post );
    $excerpt = apply_filters( 'the_excerpt', apply_filters( 'get_the_excerpt', $post->post_excerpt, $post ) );
    $tags = wp_get_post_tags( $post->ID, array('fields' => 'slugs'));
    $categories = wp_get_post_categories( $post->ID, array('fields' => 'names'));
    $img_url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
    $p = array("ID"=> $post->ID,
               "excerpt"=> $excerpt,
               "title" => get_the_title( $post->ID ),
               'tags' => $tags,
    		   'url_img' => $img_url,
    		   'categories' => $categories,
    		   "link" => get_permalink( $post->ID ));
    $results[] = $p;
  }
  $page = (int) ($query_args['page']) ? $query_args['page'] : 1;
  $total_posts = $posts_query->found_posts;
  $max_pages = ceil( $total_posts / (int) $posts_query->query_vars['posts_per_page'] );
  $response = rest_ensure_response( $results );
  $response->header( 'X-WP-Total', (int) $total_posts );
  $response->header( 'X-WP-TotalPages', (int) $max_pages ); 
  $request_params = "/text=".$fulltext."&rok=".$years."&cislo=".$issues."&tag=".$languages."&rubrika=".$cat;
  $base = rest_url( "plav/v1/search" ) . $request_params;
  if ( $page > 1 ) {
    $prev_page = $page - 1;
    if ( $prev_page > $max_pages ) {
      $prev_page = $max_pages;
    }
    $prev_link = add_query_arg( 'page', $prev_page, $base );
    $response->link_header( 'prev', $prev_link );
  }
  if ( $max_pages > $page ) {
    $next_page = $page + 1;
    $next_link = add_query_arg( 'page', $next_page, $base );
    $response->link_header( 'next', $next_link );
  }
  
  return $response;
}

add_action( 'rest_api_init', function () {
  $route = "/search/text=(?P<text>[a-zA-Z,0-9 ]*)&rok=(?P<rok>[,0-9]*)&cislo=(?P<cislo>[0-9,]*)&tag=(?P<tag>[0-9,]*)&rubrika=(?P<rubrika>[0-9,]*)";
  register_rest_route( 'plav/v1', $route, array(
    'methods' => 'GET',
    'callback' => 'my_awesome_func',
    'args' => array(
  	  'text' => array(
        'validate_callback' => function($param, $request, $key) {
          return is_string( $param );
        }
      ),
      'rok' => array(
        'validate_callback' => function($param, $request, $key) {
          return is_string( $param );
        }
      ),
  	  'cislo' => array(
        'validate_callback' => function($param, $request, $key) {
          return is_string( $param );
        }
      ),
  	  'rubrika' => array(
        'validate_callback' => function($param, $request, $key) {
          return is_string( $param );
        }
      ),
     'tag' => array(
        'validate_callback' => function($param, $request, $key) {
          return is_string( $param );
        }
      ),
    ),
  ) );
} );

?>