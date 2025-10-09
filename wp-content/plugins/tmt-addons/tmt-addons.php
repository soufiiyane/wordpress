<?php
/**
 * Plugin Name: TMT Addons
 * Description: TMT widgets for Elementor.
 * Version:     1.0.0
 * Author:      Mahassine sami
 * Author URI:  https://developers.elementor.com/
 * Text Domain: tmt-addons
 *
 * Requires Plugins: elementor
 * Elementor tested up to: 3.25.0
 * Elementor Pro tested up to: 3.25.0
 */

/**
 * Enregistrer la catégorie personnalisée pour les widgets Elementor.
 */
function register_new_elementor_category( $elements_manager ) {
    $elements_manager->add_category(
        'tmt-widgets',
        [
            'title' => esc_html__( 'TMT Widgets', 'tmt-addons' ),
            'icon' => 'fa fa-plug',
        ]
    );
}
add_action( 'elementor/elements/categories_registered', 'register_new_elementor_category' );

/**
 * Enregistrer le widget Elementor personnalisé.
 */

function register_hello_world_widget( $widgets_manager ) {

	require_once( __DIR__ . '/widgets/offres-list.php' );

	$widgets_manager->register( new \Elementor_Offres_List() );

}
add_action( 'elementor/widgets/register', 'register_hello_world_widget' );
