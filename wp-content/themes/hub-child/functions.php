<?php

add_action( 'wp_enqueue_scripts', 'liquid_child_theme_style', 99 );

function liquid_parent_theme_scripts() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
function liquid_child_theme_style(){
    wp_enqueue_style( 'child-hub-style', get_stylesheet_directory_uri() . '/style.css' );	
    wp_enqueue_script( 'my-scripts', get_stylesheet_directory_uri().'/assets/js/scripts.js', array('jquery'), null, true );
}

add_action('wp_enqueue_scripts', 'enqueue_confetti_js_script');
function enqueue_confetti_js_script()
{
	wp_register_script('confetti', 'https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js', array('jquery'), '1.0', true);
    wp_enqueue_script('confetti');
	wp_register_script('my-confetti-js', get_stylesheet_directory_uri().'/assets/js/script-confetti.js', array('jquery'), '1.0', true);
    wp_enqueue_script('my-confetti-js');
}

add_action('wp_enqueue_scripts', 'enqueue_owlcarousel2');
function enqueue_owlcarousel2()
{
    wp_enqueue_style( 'owl-carousel-css', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css', array('child-hub-style') );
    wp_enqueue_script( 'owl-carousel-js', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', array('jquery'), null, true );
}
function custom_polylang_langswitcher() {
	$output = '';
	if ( function_exists( 'pll_the_languages' ) ) {
		$args   = [
			'show_flags' => 0,
			'show_names' => 1,
			'echo'       => 0,
		];
		$output = '<ul class="polylang_langswitcher">'.pll_the_languages( $args ). '</ul>';
	}

	return $output;
}

add_shortcode( 'custom_lang_switcher', 'custom_polylang_langswitcher' );


function highlight_parent_menu_item_for_child_pages($classes, $item) {
    if (is_singular()) {
        global $post;
		
		if(is_singular('liquid-portfolio')){
			
			if ( in_array($item->object_id, array('10254', '12518')) && $item->object == 'page') {
				$classes[] = 'current-menu-ancestor'; // WordPress uses this class to mark active ancestors
			}
		}else{
			// Get the ID of the current page's parent
			$parent_id = wp_get_post_parent_id($post->ID);

			// If current page has a parent and this menu item is the parent
			if ($parent_id && $item->object_id == $parent_id && $item->object == 'page') {
				$classes[] = 'current-menu-ancestor'; // WordPress uses this class to mark active ancestors
			}	
		}
        
    }

    return $classes;
}
add_filter('nav_menu_css_class', 'highlight_parent_menu_item_for_child_pages', 10, 2);


/**
 * Protfolio shortcode v1
 */
function alternating_portfolio_grid_shortcode($atts) {
    ob_start();

    $query = new WP_Query([
        'post_type' => 'liquid-portfolio',
        'posts_per_page' => -1,
    ]);

    if ($query->have_posts()) {
        $posts = $query->get_posts();
        $count = count($posts);
        $i = 0;

        echo '<div class="portfolio-alternating">';

        while ($i < $count) {
            // Définir le groupe de 3
            $chunk = array_slice($posts, $i, 3);
            $reverse = (floor($i / 3) % 2) !== 0; // alterner tous les 3 posts

            echo '<div class="portfolio-row' . ($reverse ? ' reverse' : '') . '">';

            if (count($chunk) === 3) {
                if ($reverse) {
                    // 2 petits à gauche
                    echo '<div class="portfolio-small-group">';
                    echo get_portfolio_item_html($chunk[0], 'small');
                    echo get_portfolio_item_html($chunk[1], 'small');
                    echo '</div>';
                    // 1 grand à droite
                    echo get_portfolio_item_html($chunk[2], 'large');
                } else {
                    // 1 grand à gauche
                    echo get_portfolio_item_html($chunk[0], 'large');
                    // 2 petits à droite
                    echo '<div class="portfolio-small-group">';
                    echo get_portfolio_item_html($chunk[1], 'small');
                    echo get_portfolio_item_html($chunk[2], 'small');
                    echo '</div>';
                }
            } else {
                // Reste de 1 ou 2 éléments à afficher en bas
                foreach ($chunk as $item) {
                    echo get_portfolio_item_html($item, 'large');
                }
            }

            echo '</div>'; // .portfolio-row
            $i += 3;
        }

        echo '</div>'; // .portfolio-alternating
    }

    wp_reset_postdata();
    return ob_get_clean();
}

function get_portfolio_item_html($post, $size = 'large') {
    setup_postdata($post);
    $title = get_the_title($post);
    $image = get_the_post_thumbnail_url($post->ID, 'large');
    $link = get_permalink($post);

    $terms = get_the_terms( $post->ID, 'liquid-portfolio-category');
    $potfolio_categories = array();
    foreach($terms as $term) {
      $potfolio_categories[] =  $term->name;
    }

    $class = $size === 'large' ? 'portfolio-large' : 'portfolio-small';

    return '
    <div class="portfolio-item ' . $class . '">
        <a href="' . esc_url($link) . '">
            <div class="portfolio-thumb" style="background-image:url(' . esc_url($image) . ')"></div>
            <div class="portfolio-infos">
				<div class="portfolio-caption">
					<div class="portfolio-post-title">' . esc_html($title) . '</div>
					<div class="portfolio-post-taxonomies">' . esc_html(implode(' - ', $potfolio_categories)) . '</div>
				</div>
			</div>
        </a>
    </div>';
}
add_shortcode('portfolio_alternating', 'alternating_portfolio_grid_shortcode');

/**
 * Protfolio shortcode v2
 */
function alternating_portfolio_grid_shortcode_v2($atts) {
    ob_start();

    $query = new WP_Query([
        'post_type' => 'liquid-portfolio',
        'posts_per_page' => -1,
    ]);
    echo '<div class="portfolio-flex-grid">';
    if ( $query->have_posts() ) {
        
        while ( $query->have_posts() ) {
            $query->the_post();
            $title = get_the_title();
            $image = get_the_post_thumbnail_url(get_the_ID(), 'large');
            $image_mobile =  get_field( 'image_mobile', get_the_ID ());
            $link = get_permalink();

            $terms = get_the_terms( get_the_ID(), 'liquid-portfolio-category');
            $potfolio_categories = array();
            foreach($terms as $term) {
            $potfolio_categories[] =  $term->name;
            }

            $size = get_field( 'size', get_the_ID() );
            if ( $size !== 'large' && $size !== 'small' ) {
                $size = 'large';
            }

            echo '
            <div class="portfolio-item-gd">
                <a href="' . esc_url($link) . '"><img src="'.esc_url($image).'" class="desktop-img"><img src="'.esc_url($image_mobile).'" class="mobile-img">
                    <div class="portfolio-informations">
                        <div class="portfolio-caption">
							<div class="portfolio-caption-wrap">
								<div class="portfolio-post-title">' . esc_html($title) . '</div>
								<div class="portfolio-post-taxonomies">' . esc_html(implode(' - ', $potfolio_categories)) . '</div>
							</div>
						</div>
                    </div>
                </a>
            </div>';
        }
        
    }
    echo '</div>';
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('portfolio_alternating_v2', 'alternating_portfolio_grid_shortcode_v2');



/**
 * générer le lien postuler
 */

function lien_traduit_shortcode($atts) {
    // Attributs : on attend l'ID de la page en français (par exemple)
    $atts = shortcode_atts(array(
        'id' => 14313,
        'texte' => 'postule ici !'
    ), $atts);

    $original_id = intval($atts['id']);
    
    if (!$original_id) return '';

    // Obtenir l'ID traduit selon la langue actuelle
    if (function_exists('pll_get_post')) {
        $translated_id = pll_get_post($original_id);
    } else {
        $translated_id = $original_id;
    }

    $url = get_permalink($translated_id);
    $texte = esc_html($atts['texte']);
    $id_offre = get_the_ID();

    return '<a href="' . esc_url($url) . '?offre_id=tmt-'.$id_offre.'" class="lien-postuler">' . $texte . '</a>';
}
add_shortcode('lien_traduit', 'lien_traduit_shortcode');


/**
 * générer le titre de la page postuler
 */
function titre_postuler_shortcode($atts) {
    // Attributs : on attend l'ID de la page en français (par exemple)
    $atts = shortcode_atts(array(
        'title' => 'Candidature spontanée'
    ), $atts);

    $title = esc_html($atts['title']);

	if (isset($_GET['offre_id']) && !is_null($_GET['offre_id']) && strpos($_GET['offre_id'], 'tmt-') !== false) {
		$offre_id = substr($_GET['offre_id'], 4);
		$title = get_the_title($offre_id);
	}

    return '<h1 class="title-page-postuler">'.$title.'</h1>';
}
add_shortcode('titre_postuler', 'titre_postuler_shortcode');

/**
 * add cutom input of offre to contact form
 */
add_action('wpcf7_init', 'wpcf7_add_form_tag_offre');
function wpcf7_add_form_tag_offre()
{
	// Add shortcode for the form [offre]
	wpcf7_add_form_tag(
		'offre',
		'wpcf7_offre_form_tag_handler',
		array(
			'name-attr' => true
		)
	);
}
// Parse the shortcode in the frontend
function wpcf7_offre_form_tag_handler($tag)
{

	$title = "Candidature spontanée";
	if (isset($_GET['offre_id']) && !is_null($_GET['offre_id']) && strpos($_GET['offre_id'], 'tmt-') !== false) {
		$offre_id = substr($_GET['offre_id'], 4);
		$title = get_the_title($offre_id);
	}

	return '<input type="hidden" name="' . $tag['name'] . '" value="' . $title . '" />';
}