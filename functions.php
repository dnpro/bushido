<?php
/**
 * bushido functions and definitions
 *
 * @package bushido
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */

global $bsdfw;

if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'bushido_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function bushido_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on bushido, use a find and replace
	 * to change 'bushido' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'bushido', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 960, 960, true ); // Normal post thumbnails


	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'bushido' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'bushido_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // bushido_setup
add_action( 'after_setup_theme', 'bushido_setup' );

/**
 * Add SVG upload support.
 */
function cc_mime_types( $mimes ){
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

/**
 * Custom walker.
 *
 */

class bsd_walker extends Walker_Nav_Menu
{
	function start_el(&$output, $object, $depth = 0, $args = Array() , $current_object_id = 0) {

		global $wp_query;

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $object->classes ) ? array() : (array) $object->classes;
		$classes = array_slice($classes,1);

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $object ) );
		$class_names = ' class="'. esc_attr( $class_names ) . '"';



		$attributes  = ! empty( $object->attr_title ) ? ' title="'  . esc_attr( $object->attr_title ) .'"' : '';
		$attributes .= ! empty( $object->target )     ? ' target="' . esc_attr( $object->target     ) .'"' : '';
		$attributes .= ! empty( $object->xfn )        ? ' rel="'    . esc_attr( $object->xfn        ) .'"' : '';
		$attributes .= ! empty( $object->url )        ? ' href="'   . esc_attr( $object->url        ) .'"' : '';


		if($object->object == 'page')
		{              
			$bsd_menu_icon = get_post_meta($object->object_id, "bsd_menu_icon", true);
			
			$output .= $indent . '<li id="menu-item-'. $object->ID . '"' . $value . $class_names .'>';
			
			$object_output = $args->before;
			$object_output .= '<a class="material"'. $attributes .' bubble-size="big">';
			$object_output .= $args->link_before .$bsd_menu_icon;
			$object_output .= apply_filters( 'the_title', $object->title, $object->ID ).$args->link_after;
			$object_output .= '</a>';
			$object_output .= $args->after;   

			$output .= apply_filters( 'walker_nav_menu_start_el', $object_output, $object, $depth, $args );         
		}

	}
}


/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function bushido_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'bushido' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'bushido_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function bushido_scripts() {
	wp_enqueue_style( 'bushido-style', get_stylesheet_uri() );
    
    wp_deregister_script('jquery');

	wp_register_script('jquery', ("http://code.jquery.com/jquery-latest.min.js"), false);

	wp_enqueue_script('jquery');

	wp_enqueue_script( 'bushido-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '', true );
    
	wp_enqueue_script( 'bushido-infinite-pagination', get_template_directory_uri() . '/js/infinite-pagination.js', array(), '', true );
    
	wp_enqueue_script( 'bushido-color-thief', get_template_directory_uri() . '/js/vendor/color-thief.js', array(), '', true );
    
	wp_enqueue_script( 'bushido-work-cards', get_template_directory_uri() . '/js/components/work-cards.js', array(), '', true );
    
	wp_enqueue_script( 'bushido-main', get_template_directory_uri() . '/js/main.js', array(), '', true );

	wp_enqueue_script( 'bushido-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'bushido_scripts' );

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
/**
* Include Meta Box Framework.
*/
define( 'RWMB_URL', trailingslashit( get_template_directory_uri() . '/inc/meta-box' ) );
define( 'RWMB_DIR', trailingslashit( get_template_directory() . '/inc/meta-box' ) );

require_once RWMB_DIR . 'meta-box.php';
include_once get_template_directory() . '/inc/meta-boxes.php';
/**
 * Include Redux Framework.
 */
include_once get_template_directory() . '/inc/options-init.php';
/**
 * Load Portfolio.
 */
require get_template_directory() . '/inc/bushido-portfolio.php';




 
  
/* Pagination Function*/   
function bushido_pagination($pages = '', $range = 4) {
	$showitems = ($range * 2)+1;
	
	global $paged;
	if(empty($paged)) $paged = 1;
	
	if($pages == '') {
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if(!$pages) {
			$pages = 1;
		}
	}
	

		echo "<span class='allpages'>" . __('Page', 'bushido') . " ".$paged." " . __('of', 'bushido') . " ".$pages."</span>";
		if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; " . __('First', 'bushido') . "</a>";
		if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; " . __('Previous', 'bushido') . "</a>";
		
		for ($i=1; $i <= $pages; $i++) {
			if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {
				echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"next-page\">".$i."</a>";
			}
		}
	
		if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">" . __('Next', 'bushido') . " &rsaquo;</a>";
		if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>" . __('Last', 'bushido') . " &raquo;</a>";
	
}