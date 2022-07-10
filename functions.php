<?php
error_reporting(0);
/**
 * Register Custom Navigation Walker
 */
/*
function register_navwalker() {
	require_once get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php';
}

add_action( 'after_setup_theme', 'register_navwalker' );
*/
require_once get_template_directory() . '/inc/general.php';

// include js in the Base-Theme
function base_theme_scripts() {
	if ( ! is_admin() ) {
		global $wp_query;

		// load a JS file from Base-Theme

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script('lightbox2','https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js',[ 'jquery' ],'2.8.2',true);
		
		wp_enqueue_script( 'combining-js', get_bloginfo( 'template_url' ) . '/js/combining.js', [ 'jquery' ], '', true );
		wp_enqueue_script( 'jquery-lazy', get_template_directory_uri() . '/js/jquery.lazy.min.js', [ 'jquery' ], '', true );

		wp_enqueue_script('slick-js','https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js',[ 'jquery' ],'1.9.0',true);
		
			
		wp_enqueue_script( 'owl.carousel.min.js', get_template_directory_uri() . '/js/owl.carousel.min.js', [ 'jquery' ], '1.0.0', true );

		wp_enqueue_script( 'scripts-js', get_template_directory_uri() . '/assets/js/scripts.js', [ 'jquery' ], '1.0.0', true );

        wp_enqueue_style('style','https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css');
		
		wp_enqueue_style( 'owl-css', get_stylesheet_directory_uri() . '/css/owl.carousel.min.css','','1.0.1');

		wp_enqueue_style( 'plugins-css', get_stylesheet_directory_uri() . '/css/plugins.min.css');
		wp_enqueue_style('bootstrap.min.css','https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css','','4.3.1');

        wp_enqueue_style( 'line-awesome', get_stylesheet_directory_uri() . '/plugins/line-awesome/css/line-awesome.min.css');
        wp_enqueue_style( 'themify-icons', get_bloginfo( 'template_url' ) . '/plugins/themify/themify-icons.css' );
		wp_enqueue_style( 'fontawesome-css', get_bloginfo( 'template_url' ) . '/plugins/fontawesome/css/font-awesome.min.css' );

        wp_enqueue_style( 'style-min', get_stylesheet_directory_uri() . '/css/style.min.css','','1.0.1');
		
        wp_enqueue_style( 'templete-css', get_bloginfo( 'template_url' ) . '/css/templete.min.css' );

        wp_enqueue_style( 'google-css',  'https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&amp;family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap' );
        wp_enqueue_style( 'skin-css', get_bloginfo( 'template_url' ) . '/css/skin/skin-1.css' );
        wp_enqueue_style( 'responsive', get_stylesheet_directory_uri() . '/css/responsive.css','','1.0.1');

        wp_enqueue_style( 'style-new', get_bloginfo( 'template_url' ) . '/css/style-new.css' );

        wp_enqueue_style( 'responsive-new', get_bloginfo( 'template_url' ) . '/css/responsive-new.css' );
		
		wp_enqueue_style('lightbox2-css','https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css','','2.8.2');
		
	}
}

add_action( 'wp_enqueue_scripts', 'base_theme_scripts' );

// enable post thumbnail option start here
add_theme_support( 'post-thumbnails' );

// enable image cropping sizes start here
function base_theme_image_theme_setup() {
	add_image_size( 'post-image', 420, 300, true );
	add_image_size( 'post-thumb', 520, 460, true );
}

add_action( 'after_setup_theme', 'base_theme_image_theme_setup' );

// assign custom thumbnail size start here
if ( has_post_thumbnail() ) {
	the_post_thumbnail( 'post-image' );
}

// assign media library images start here
add_filter( 'image_size_names_choose', 'base_theme_image_custom_sizes' );

function base_theme_image_custom_sizes( $sizes ) {
	return array_merge( $sizes, [
		'post-image' => __( 'Post Image' ),
	] );
}

// wp nav menu option start here
function register_my_menus() {
	register_nav_menus(
		[
			'header_menu' => 'Header Menu',
			'footer_menu' => 'Footer Menu',
			'usefull_menu' => 'Usefull Links',
			'quick_menu' => 'Quick Menu',
		]
	);
}

add_action( 'init', 'register_my_menus' );
// wp nav menu option end here



// side bar option start here
if ( function_exists( 'register_sidebar' ) ) {

	register_sidebar( [
		'name'          => 'Sidebar Widgets',
		'id'            => 'sidebar_widgets',
		'description'   => 'This area for Sidebar Widgets',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="widget-title style-1">',
		'after_title'   => '</h5>',
	] );
}
// side bar option end here

// custom excerpt length
function custom_excerpt_length( $length ) {
	return 10;
}

add_filter( 'excerpt_length', 'custom_excerpt_length' );

// Define additional option pages
if ( function_exists( 'acf_add_options_page' ) ) {

	$options = acf_add_options_page( [
		'page_title' => 'Theme options',
		'menu_title' => 'Theme options',
		'menu_slug'  => 'options-theme',
		'capability' => 'edit_posts',
		'redirect'   => false
	] );
}

//Register theme custom post types
function register_custom_post_types() {

	register_post_type( 'training', [
		'labels'    => [
			'name'          => __( 'Courses' ),
			'singular_name' => __( 'Course' ),
			'menu_name'     => __( 'Courses' ),
		],
		'public'    => true,
        'publicly_queryable' => true,
		'has_archive' => true,
		'menu_icon' => 'dashicons-book',
		'rewrite'   => [ 'slug' => 'course' ],
		'supports'  => [ 'title', 'editor', 'thumbnail' ]
	] );
	
	/*
    register_post_type( 'event', [
        'labels'    => [
            'name'          => __( 'Events' ),
            'singular_name' => __( 'Event' ),
            'menu_name'     => __( 'Events' ),
        ],
        'public'    => true,
        'menu_icon' => 'dashicons-welcome-view-site',
        'rewrite'   => [ 'slug' => 'event' ],
        'supports'  => [ 'title', 'editor', 'thumbnail' ]
    ] );
	*/
}

add_action( 'init', 'register_custom_post_types' );

//register taxonomy
function register_taxonomies() {

	register_taxonomy( 'training_cat', [ 'training' ], [
		'labels'            => [
			'name'          => __( 'Categories' ),
			'singular_name' => __( 'Category' ),
			'menu_name'     => __( 'Categories' ),
		],
		'hierarchical'      => true,
		'show_admin_column' => true,
		'rewrite'           => [ 'slug' => 'cat' ]
	] );
}

add_action( 'init', 'register_taxonomies' );

function add_archive_placeholders( $page_templates, $instance, $post ) {

	if ( $post && $post->post_type != 'page' ) {
		return $page_templates;
	}

	$post_types = get_post_types( [ '_builtin' => false ] );

	foreach ( $post_types as $post_type ) {
		if ( ( $post_type_object = get_post_type_object( $post_type ) ) != null && $post_type_object->has_archive ) {
			$page_templates[ 'archive_' . $post_type ] = sprintf( __( 'Archive - %s', 'text_domain' ), $post_type_object->labels->singular_name );
		}
	}

	return $page_templates;
}

add_filter( 'theme_page_templates', 'add_archive_placeholders', 10, 3 );

function redirect_to_archive() {

	if ( is_singular( 'page' ) ) {
		$template = str_replace( 'archive_', '', get_page_template_slug( get_queried_object_id() ) );
		$types    = get_post_types( [ 'has_archive' => true ], 'names' );

		if ( in_array( $template, $types ) ) {
			wp_safe_redirect( get_post_type_archive_link( $template ) );
			exit();
		}
	}
}

add_action( 'template_redirect', 'redirect_to_archive' );

function load_posts() {

	$paged = $_POST['page'] + 1;

	if ( ! empty( $paged ) ) {

		$args = [
			'post_type' => 'post',
			'paged'     => $paged,
		];

		$my_post = new WP_Query( $args );

		while ( $my_post->have_posts() ):
			$my_post->the_post(); ?>
			<div class="col-md-4"><?php get_template_part( 'template-parts/loop', 'post' ); ?></div>
		<?php endwhile;
		wp_reset_postdata();
	}

	wp_die();
}

add_action( 'wp_ajax_load_posts', 'load_posts' );
add_action( 'wp_ajax_nopriv_load_posts', 'load_posts' );



function add_404_placeholders( $page_templates, $instance, $post ) {
	if ( $post && $post->post_type != 'page' ) {
		return $page_templates;
	}

	$page_templates['404'] = __( '404 - Page not Found' );

	return $page_templates;
}

add_filter( 'theme_page_templates', 'add_404_placeholders', 10, 3 );

function redirect_to_404() {
	if ( is_singular( 'page' ) && is_page_template( '404' ) ) {
		global $wp_query;

		$wp_query->set_404();

		status_header( 404 );
		get_template_part( 404 );

		exit();
	}
}

add_action( 'template_redirect', 'redirect_to_404' );

function redirect() {
	global $wp_rewrite;

	if ( ! isset( $wp_rewrite ) || ! is_object( $wp_rewrite ) || ! $wp_rewrite->get_search_permastruct() ) {
		return;
	}

	$search_base = $wp_rewrite->search_base;

	if ( is_search() && ! is_admin() && strpos( $_SERVER['REQUEST_URI'], "/{$search_base}/" ) === false && strpos( $_SERVER['REQUEST_URI'], '&' ) === false ) {
		wp_redirect( get_search_link() );
		exit();
	}
}

function rewrite( $url ) {
	return str_replace( '/?s=', '/search/', $url );
}

define( 'NICE_SEARCH', true );

if ( NICE_SEARCH ) {
	add_action( 'template_redirect', 'redirect' );
	add_filter( 'wpseo_json_ld_search_url', 'rewrite' );
}

function adjust_main_queries( $query ) {

	if ( ! is_admin() && $query->is_main_query() ) {

		if ( $query->is_search() ) {

			$query->set( 'posts_per_page', 12 );
		}

        if( $query->is_tax('training_cat') && $query->has_term('training_cat')){
            $query->set('posts_per_page', 5);
            $query->set( 'order', 'asc' );
        }


        if ( $query->is_tax() || is_post_type_archive( 'package' ) ) {

            $query->set( 'order', 'asc' );

            if ( isset( $_GET['pk'] ) && ! empty( $_GET['pk'] ) ) {
                $query->set( 's', esc_attr( $_GET['pk'] ) );
            }

            if ( isset( $_GET['qtr'] ) && ! empty( $_GET['qtr'] ) ) {
                $query->set( 'tax_query', '' );
                $items = esc_attr( $_GET['qtr'] );
                $qs    = explode( '|', $items );

                $tax_query[] = [
                    'taxonomy' => 'filter_cat',
                    'field'    => 'slug',
                    'terms'    => $qs,
                ];
                $query->set( 'tax_query', $tax_query );
            }

            if ( isset( $_GET['price'] ) && ! empty( $_GET['price'] ) ) {

                $price = esc_attr( $_GET['price'] );
                $p     = explode( ',', $price );

                if ( ! empty( $p ) && is_array( $p ) ) {
                    $query->set( 'meta_query', [
                            [
                                'key'     => 'price',
                                'value'   => [ $p[0], $p[1] ],
                                'compare' => 'between',
                                'type'    => 'numeric'
                            ]
                        ]
                    );
                }
            }

            if ( isset( $_GET['qr'] ) && ! empty( $_GET['qr'] ) ) {

                $ratings = esc_attr( $_GET['qr'] );
                $stars   = explode( '|', $ratings );

                $package_ids = get_post_by_ratings( $stars );

                if ( ! empty( $package_ids ) && is_array( $package_ids ) ) {
                    $query->set( 'post__in', $package_ids );
                } else {
                    $query->set( 'post__in', [ 0 ] );
                }
            }

            if ( isset( $_GET['ord'] ) && ! empty( $_GET['ord'] ) ) {

                if ( $_GET['ord'] == 'low' || $_GET['ord'] == 'high' || $_GET['ord'] == 'alphabet' ) {

                    $order = esc_attr( $_GET['ord'] );

                    if ( $order == 'high' ) {
                        $query->set( 'meta_key', 'price' );
                        $query->set( 'orderby', 'meta_value_num' );
                        $query->set( 'order', 'desc' );
                    } elseif ( $order == 'low' ) {
                        $query->set( 'meta_key', 'price' );
                        $query->set( 'orderby', 'meta_value_num' );
                        $query->set( 'order', 'asc' );
                    } elseif ( $order == 'alphabet' ) {
                        $query->set( 'orderby', 'title' );
                        $query->set( 'order', 'asc' );
                    }
                }
            }
        }

		if ( is_tax() || is_post_type_archive( 'movie' ) ) {

			$query->set( 'posts_per_page', 99 );

			if ( isset( $_REQUEST['type'] ) && ! empty( $_REQUEST['type'] ) ) {

				$query->set( 'tax_query', '' );

				$cats = esc_attr( $_REQUEST['type'] );

				$qs = explode( '|', $cats );

				$tax_query[] = [
					'taxonomy' => 'movie_type',
					'field'    => 'slug',
					'terms'    => $qs,
				];

				$query->set( 'tax_query', $tax_query );
			}

			if ( isset( $_REQUEST['rating'] ) && ! empty( $_REQUEST['rating'] ) ) {
				$query->set( 'meta_query', [
					[
						'key'     => 'rating',
						'compare' => '=',
						'value'   => $_REQUEST['rating'],
						//'type'    => 'numeric',
					]
				] );
			}

			if ( isset( $_REQUEST['lang'] ) && ! empty( $_REQUEST['lang'] ) ) {

				$query->set( 'tax_query', '' );

				$cats = esc_attr( $_REQUEST['lang'] );

				$qs = explode( '|', $cats );

				$tax_query[] = [
					'taxonomy' => 'movie_lang',
					'field'    => 'slug',
					'terms'    => $qs,
				];

				$query->set( 'tax_query', $tax_query );
			}
		}
	}

	return $query;
}

//add_filter( 'pre_get_posts', 'adjust_main_queries' );

add_action('pre_get_posts', 'filter_press_tax');

function filter_press_tax( $query ){
	
	if ( $query->is_tax() ) {
		//$query->set( 'meta_key', 'post_id' );
		//$query->set( 'orderby', 'meta_value_num' );
		//$query->set( 'order', 'asc' );
		$query->set( 'order', 'asc' );
		$query->set( 'posts_per_page', -1 );
		
		if(is_post_type_archive( 'training' )){
			$query->set( 'posts_per_page', -1 );
		}
		//return;
	}
	
	if ( ! is_admin()) {
		if ( $query->is_search() ) {

			$query->set( 'post_type', ['post'] );
		}
	}
	
    return $query;
}


function cleanup_nav_walker( $classes, $item ) {
	$slug = sanitize_title( $item->title );

	// Fix core `active` behavior for custom post types
	if ( in_array( get_post_type(), get_post_types( [ '_builtin' => false ] ) ) ) {
		$classes = str_replace( 'current_page_parent', '', $classes );
		if ( get_post_type_archive_link( get_post_type() ) == strtolower( trim( $item->url ) ) ) {
			if ( is_search() || is_404() || is_tax('training_cat') ) {
				$classes[] = '';
			} else {
				$classes[] = 'active';
			}
		}
	}

	// Remove most core classes
	//$classes = preg_replace( '/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'active', $classes );
	//$classes = preg_replace( '/^((menu|page)[-_\w+]+)+/', '', $classes );

	//Remove most core classes with condition
	if ( is_search() || is_404() || is_tax('training_cat') ) {

	} else {
		$classes = preg_replace( '/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'active', $classes );
		$classes = preg_replace( '/^((menu|page)[-_\w+]+)+/', '', $classes );
	}

	// Add parent class
	if ( $item->is_subitem ) {
		$classes[] = 'has-children';
	}

	// Add `menu-<slug>` class
	$classes[] = 'menu-' . $slug;
	$classes   = array_unique( $classes );
	$classes   = array_map( 'trim', $classes );

	return array_filter( $classes );
}

add_filter( 'nav_menu_css_class', 'cleanup_nav_walker', 10, 2 );
add_filter( 'nav_menu_item_id', '__return_null' );


function woocommerce_them_support_func()
{
    /*add_theme_support("woocommerce", array(
        "thumbnail_image_width" => 150,
        "single_image_width" => 200,
        "product_grid" => array(
            "default_columns" => 10,
            "min_columns" => 2,
            "max_columns" => 3,
        ),
    ));
    */

    add_theme_support( 'woocommerce', apply_filters( 'storefront_woocommerce_args', array(
        'single_image_width' => 416,
        'thumbnail_image_width' => 324,
        'product_grid' => array(
            'default_columns' => 3,
            'default_rows' => 4,
            'min_columns' => 1,
            'max_columns' => 6,
            'min_rows' => 1
        )
    ) ) );

    // product thumbnail effect support
    //add_theme_support("wc-product-gallery-zoom");
    //add_theme_support("wc-product-gallery-lightbox");
    //add_theme_support("wc-product-gallery-slider");
}

add_action("after_setup_theme", "woocommerce_them_support_func");


add_filter( 'woocommerce_product_tabs', '__return_empty_array', 98 );

add_action('template_redirect', 'remove_sidebar_shop');
function remove_sidebar_shop() {
    if ( is_singular('product') ) {
        remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar');
    }
}

add_filter('woocommerce_product_single_add_to_cart_text','single_product_cart_button_change');
function single_product_cart_button_change(){
    return __('Enroll', 'woocommerce');

}

add_filter( 'woocommerce_return_to_shop_redirect', 'bbloomer_change_return_shop_url' );

function bbloomer_change_return_shop_url() {
    return home_url('/subscription-plan/');
}

add_filter( 'gettext', 'change_woocommerce_return_to_shop_text', 20, 3 );

function change_woocommerce_return_to_shop_text( $translated_text, $text, $domain ) {

    switch ( $translated_text ) {

        case 'Return to shop' :

            $translated_text = __( 'Return to Subscription', 'woocommerce' );
            break;

    }

    return $translated_text;
}


remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );



function custom_product_attributes() {
    $free_cat = get_field('select_sector','option');
    foreach (get_terms('training_cat',['hide_empty' => false, 'parent'   => 0 ]) as $term){
        $exclude[] = $term->term_id;
    }
    $hide = [213,190,111,186,211,170,110];
    //print_r($exclude);
    if($cats= get_terms('training_cat',['hide_empty' => true, 'exclude' => $exclude])): ?>

    <table class="variations" cellspacing="0">
            <tbody>
            <tr class="js-hide" <?php echo ( get_the_ID() == 288 )?'style="display:none;"':''; ?>>
                <td class="label">
                    <label>Sector Options</label>
                </td>
            </tr>
             <tr class="js-hide">

                <td class="value<?php if ( get_the_ID() == 329 ):?>-competency<?php elseif(get_the_ID() == 288):?>-proficiency<?php elseif(get_the_ID() == 2208):?>-checkbox<?php endif;?>">
                    <?php if ( get_the_ID() == 2208 ): ?>
                        <?php $i =1; foreach ($cats as $cat): 
                            if(!in_array($cat->term_id,$hide)): ?>
                            <label><input class="single-checkbox" type="checkbox" id="sector" name="sector[]" value="<?php echo $cat->term_id; ?>" <?php echo($i==1)?'checked':''; ?>/><?php echo $cat->name; ?></label>
                        <?php $i++; endif; endforeach; ?>
                    <?php endif; ?>
                    <?php if ( get_the_ID() == 288 ): ?>
                        <?php $i =1; foreach ($cats as $cat): 
                            if(!in_array($cat->term_id,$hide)): ?>
                            <label><input class="single-checkbox" type="checkbox" id="sector" name="sector[]" value="<?php echo $cat->term_id; ?>" <?php echo($i==1)?'checked':''; ?>/><?php echo $cat->name; ?></label>
                        <?php $i++; endif; endforeach; ?>
                    <?php endif; ?>
                    <?php if ( get_the_ID() == 329 ): ?>
                        <?php $i =1;  foreach ($cats as $cat): 
                            if(!in_array($cat->term_id,$hide)):?>
                            <label><input class="single-checkbox value-three" type="checkbox" id="sector" name="sector[]" value="<?php echo $cat->term_id; ?>" <?php echo($i==1)?'checked':''; ?>/><?php echo $cat->name; ?></label>
                            <?php $i++; endif; endforeach; ?>
                    <?php endif; ?>
                    <?php if ( get_the_ID() == 334 ): ?>
                        <?php $i = 1; foreach ($cats as $cat):
                            if(!in_array($cat->term_id,$hide)):?>
                            <label><input type="radio" id="sector" name="sector[]" value="<?php echo $cat->term_id; ?>" <?php echo($i==1)?'checked':''; ?>/><?php echo $cat->name; ?></label>
                        <?php $i++; endif;  endforeach; ?>
                    <?php endif; ?>
                </td>
            </tr>

            </tbody>
        </table>

   <?php endif; ?>
    <script type="text/javascript">
        jQuery(function($) {
            
            $('.value-competency input[type=checkbox]').change(function(e){
                if ($('input[type=checkbox]:checked').length > 3) {
                    $(this).prop('checked', false)
                    alert('maximum 3');
                }
                var len = $("input[type='checkbox']:checked").length;
                
                if(len == 0){
                  $(".single_add_to_cart_button").prop("disabled", true);
                }else{
                  $(".single_add_to_cart_button").removeAttr("disabled");
                }
            });
            
            $('.value-proficiency input[type=checkbox]').change(function(e){
                if ($('input[type=checkbox]:checked').length > 5) {
                    $(this).prop('checked', false)
                    alert('maximum 5');
                }
                var len = $("input[type='checkbox']:checked").length;
                
                if(len == 0){
                  $(".single_add_to_cart_button").prop("disabled", true);
                }else{
                  $(".single_add_to_cart_button").removeAttr("disabled");
                }
            });

            $('.value-checkbox input[type=checkbox]').change(function(e){
                if ($('input[type=checkbox]:checked').length > 8) {
                    $(this).prop('checked', false)
                    alert('maximum 8');
                }
                
                var len = $("input[type='checkbox']:checked").length;
                
                if(len == 0){
                  $(".single_add_to_cart_button").prop("disabled", true);
                }else{
                  $(".single_add_to_cart_button").removeAttr("disabled");
                }
            });
            
            /*
            
            $("input[type='checkbox']").change(function(){
                var len = $("input[type='checkbox']:checked").length;
                alert('hi');
                if(len == 0)
                  alert(len);
                  $("input[type='submit']").prop("disabled", true);
                else
                  $("input[type='submit']").removeAttr("disabled");
            });
            $("input[type='checkbox']").trigger('change');
            */
        });
    </script>
    <?php
}
add_action( 'woocommerce_before_add_to_cart_button', 'custom_product_attributes' );

function save_gift_wrap_fee( $cart_item_data, $product_id ) {
    session_start();
    $_SESSION['sector'] = $_POST['sector'];

    if( isset( $_POST['sector'] ) && !empty($_POST['sector']) ) {
        $cart_item_data[ "sector" ] = $_POST['sector'];
    }

    return $cart_item_data;

}
add_filter( 'woocommerce_add_cart_item_data', 'save_gift_wrap_fee', 99, 2 );


add_action('woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta');
function my_custom_checkout_field_update_order_meta( $order_id ) {
    if ($_POST['sector']) update_post_meta( $order_id, 'sector', esc_attr($_POST['sector']));
}



add_action('woocommerce_checkout_update_order_meta',function( $order_id, $posted ) {
    $order = wc_get_order( $order_id );
    session_start();
    $sector = $_SESSION['sector'];
    //$order->update_meta_data( 'sector', esc_attr($sector) );
    //$order->save();
    //unset($_SESSION['sector']);
} , 10, 2);

add_action( 'woocommerce_thankyou', 'bbloomer_print_order_array', 5 );
function bbloomer_print_order_array( $orderid ) {
	session_start();
    $order = wc_get_order( $orderid );
    //echo "thannks";
    //echo "<pre>";
    //print_r( $_SESSION );
    //echo "</pre>";

    //$sector = $_SESSION['sector'];
    //print_r($sector);
    //echo "sectors".$sectors =  implode(",",$sector);
}

function array_flatten($array) { 
  if (!is_array($array)) { 
    return FALSE; 
  } 
  $result = array(); 
  foreach ($array as $key => $value) { 
    if (is_array($value)) { 
      $result = array_merge($result, array_flatten($value)); 
    } 
    else { 
      $result[$key] = $value; 
    } 
  } 
  return $result; 
}

add_filter( 'woocommerce_account_menu_items', 'QuadLayers_rename_acc_adress_tab', 9999 );
function QuadLayers_rename_acc_adress_tab( $items ) {
	//echo "<pre>";
	//print_r($items);
	//echo "<pre>";
$items['ihc'] = 'Member Area';
return $items;
}

function get_term_top_most_parent( $term_id, $taxonomy ) {
    $parent  = get_term_by( 'id', $term_id, $taxonomy );
    while ( $parent->parent != 0 ){
        $parent  = get_term_by( 'id', $parent->parent, $taxonomy );
    }
    return $parent;
}

function post_read_time() {

    global $post;
    // get the post content
    $content ='';
    $content .= get_post_field( 'post_content', $post->ID );

    while(have_rows('course_contents',$post->ID)): the_row();

        if(get_row_layout() == 'content_image_section'):

            $content .= get_sub_field('ci_content');

        elseif(get_row_layout() == 'content_list_section'):

            //get_template_part('template-course/content','list');

        elseif(get_row_layout() == 'case_study_section'):
        
             $content .= get_sub_field('case_content');

        elseif(get_row_layout() == 'content_section'):

           $content .= get_sub_field('c_content');

        endif;    

    endwhile;

    // count the words
    $word_count = str_word_count( strip_tags( $content ) );

    // reading time itself
    $readingtime = ceil($word_count / 100);

    if ($readingtime == 1) {
        $timer = " minute read";
    } else {
        $timer = " minute read"; // or your version :) I use the same wordings for 1 minute of reading or more
    }

    // I'm going to print 'X minute read' above my post
    $totalreadingtime = $readingtime . $timer;
    echo $totalreadingtime;
    return $totalreadingtime;

}

add_action('template_redirect','redirect_to_login_page');

function redirect_to_login_page(){
   
    if(is_post_type_archive('training')){
        wp_redirect(home_url('/our-sectors/'));
        die();
    }
	 if(is_post_type_archive('mep_events')){
        wp_redirect(home_url('/events-list/'));
        die();
    }
	

}
add_filter( 'use_widgets_block_editor', '__return_false' );

class author_widget extends WP_Widget {
  
	function __construct() {
		parent::__construct('author_widget', __('Author Widget', ''), array( 'description' => __( 'Author Widget', '' ), ) 
		);

	}
  
	// Creating widget front-end
	  
	public function widget( $args, $instance ) {
	$title = apply_filters( 'widget_title', $instance['title'] );
	  
	echo $args['before_widget'];
	if ( ! empty( $title ) )
	echo $args['before_title'] . $title . $args['after_title']; 

	if(is_singular('post')): ?>

		<div class="authorwidget">
			<span itemscope itemprop="image" alt="Photo of <?php the_author_meta( 'display_name' ); ?>">
			  <?php if(function_exists('get_avatar')) { echo get_avatar( get_the_author_meta('email'), '50' ); } ?>
			</span>

			<h5 class="vcard author" itemprop="url" rel="author">
		    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="fn" itemprop="name">
		      <span itemprop="author" itemscope itemtype="https://schema.org/Person">
		        <?php echo ucwords(get_the_author_meta( 'display_name' )); ?>
		      </span>
		    </a>
		  </h5>
		  
		 <p class="authr_p"> <?php the_author_meta('description') ?></p>
		</div>
	  
	<?php 
		endif;
		
	echo $args['after_widget'];
	}
          
	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}else {
			$title = __( 'Title', '' );
		}
		// Widget admin form ?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
	<?php 
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
} 
 
 
// Register and load the widget
function author_load_widget() {
    register_widget( 'author_widget' );
}
add_action( 'widgets_init', 'author_load_widget' );

add_filter("nav_menu_link_attributes", "owt_each_anchor_attr");

function owt_each_anchor_attr($attr) {
    $attr['class'] = "nav-link";
    return $attr;
}

add_filter("nav_menu_css_class", "owt_each_li_class", 10, 4);

function owt_each_li_class($classes, $item, $args, $dept) {
    $classes[] = "nav-item";
    return $classes;
}
//complete order
add_action( 'woocommerce_thankyou', 'custom_woocommerce_auto_complete_order' );
function custom_woocommerce_auto_complete_order( $order_id ) {
    if ( ! $order_id ) {
        return;
    }

    $order = wc_get_order( $order_id );
    $order->update_status( 'completed' );
}