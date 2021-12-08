<?php

namespace WeDevs\WeiDocs;

use WP_Query;

/**
 * Frontend Handler Class
 */
class Frontend {

    /**
     * Shortcode class
     *
     * @var \WeDevs\WeiDocs\Shortcode
     */
    public $shortcode;

    /**
     * Theme wrapper class
     *
     * @var \WeDevs\WeiDocs\Theme_Support
     */
    public $theme;

    /**
     * Class Constructor
     */
    public function __construct() {

        // filter the search result
        add_action( 'pre_get_posts', [ $this, 'docs_search_filter' ] );

        // Loads frontend scripts and styles
        add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ], 9 );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_single_scripts' ], 9 );

        // override the theme template
        add_filter( 'template_include', [ $this, 'template_loader' ], 20 );

        $this->init_classes();
    }

    /**
     * Initialize the classes
     *
     * @return void
     */
    public function init_classes() {
        $this->shortcode = new Shortcode();
        $this->theme     = new Theme_Support();
    }

    /**
     * Enqueue admin scripts.
     *
     * Allows plugin assets to be loaded.
     *
     * @uses wp_enqueue_script()
     * @uses wp_localize_script()
     * @uses wp_enqueue_style
     */
    public function register_scripts() {
        // All styles goes here
        wp_register_style( 'weidocs-styles', WEDOCS_ASSETS . '/css/frontend.css', [], filemtime( WEDOCS_PATH . '/assets/css/frontend.css' ) );

        // All scripts goes here
        wp_register_script( 'weidocs-anchorjs', WEDOCS_ASSETS . '/js/anchor.min.js', [ 'jquery' ], WEDOCS_VERSION, true );
        wp_register_script( 'weidocs-scripts', WEDOCS_ASSETS . '/js/frontend.js', [ 'jquery', 'weidocs-anchorjs' ], filemtime( WEDOCS_PATH . '/assets/js/frontend.js' ), true );
        wp_localize_script( 'weidocs-scripts', 'weiDocs_Vars', [
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'weidocs-ajax' ),
            'style'   => WEDOCS_ASSETS . '/css/print.css?v=10',
            'powered' => sprintf( '&copy; %s, %d. %s<br>%s', get_bloginfo( 'name' ), date( 'Y' ), __( 'Powered by weiDocs plugin for WordPress', 'weidocs' ), home_url() ),
        ] );
    }

    /**
     * Enqueue scripts only for singular docs
     *
     * @since 1.6.1
     *
     * @return void
     */
    public function enqueue_single_scripts() {
        if ( is_singular( 'docs' ) ) {
            self::enqueue_assets();
        }
    }

    /**
     * Enqueue the scripts and styles
     *
     * @since 1.6.1
     *
     * @return void
     */
    public static function enqueue_assets() {
        wp_enqueue_style( 'weidocs-styles' );

        wp_enqueue_script( 'weidocs-anchorjs' );
        wp_enqueue_script( 'weidocs-scripts' );
    }

    /**
     * Handle the search filtering in search page.
     *
     * @param WP_Query $query
     *
     * @return void
     */
    public function docs_search_filter( $query ) {
        if ( ! $query->is_main_query() ) {
            return;
        }

        if ( ! is_search() ) {
            return;
        }

        $param = isset( $_GET['search_in_doc'] ) ? sanitize_text_field( $_GET['search_in_doc'] ) : false;

        if ( $param && 'all' != $param ) {
            $parent_doc_id = intval( $param );
            $post__in      = [ $parent_doc_id => $parent_doc_id ];
            $children_docs = weidocs_get_posts_children( $parent_doc_id, 'docs' );

            if ( $children_docs ) {
                $post__in = array_merge( $post__in, wp_list_pluck( $children_docs, 'ID' ) );
            }

            $query->set( 'post__in', $post__in );
        }
    }

    /**
     * If the theme doesn't have any single doc handler, load that from
     * the plugin.
     *
     * @param string $template
     *
     * @return string
     */
    public function template_loader( $template ) {
        $find = [ 'docs.php' ];
        $file = '';

        if ( is_single() && get_post_type() == 'docs' ) {
            $file   = 'single-docs.php';
            $find[] = $file;
            $find[] = weidocs()->theme_dir_path() . $file;
        }

        if ( $file ) {
            $template = locate_template( $find );

            if ( !$template ) {
                $template = weidocs()->template_path() . $file;
            }
        }

        return $template;
    }
}
