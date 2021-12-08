<?php

namespace WeDevs\WeiDocs;

/**
 * Post type class
 */
class Post_Types {

    /**
     * The post type name.
     *
     * @var string
     */
    private $post_type = 'docs';

    /**
     * Initialize the class
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_post_type' ] );
        add_action( 'init', [ $this, 'register_taxonomy' ] );
    }

    /**
     * Register the post type.
     *
     * @return void
     */
    public function register_post_type() {
        $labels = [
            'name'               => _x( 'Docs', 'Post Type General Name', 'weidocs' ),
            'singular_name'      => _x( 'Doc', 'Post Type Singular Name', 'weidocs' ),
            'menu_name'          => __( 'Documentation', 'weidocs' ),
            'parent_item_colon'  => __( 'Parent Doc', 'weidocs' ),
            'all_items'          => __( 'All Documentations', 'weidocs' ),
            'view_item'          => __( 'View Documentation', 'weidocs' ),
            'add_new_item'       => __( 'Add Documentation', 'weidocs' ),
            'add_new'            => __( 'Add New', 'weidocs' ),
            'edit_item'          => __( 'Edit Documentation', 'weidocs' ),
            'update_item'        => __( 'Update Documentation', 'weidocs' ),
            'search_items'       => __( 'Search Documentation', 'weidocs' ),
            'not_found'          => __( 'Not documentation found', 'weidocs' ),
            'not_found_in_trash' => __( 'Not found in Trash', 'weidocs' ),
        ];
        $rewrite = [
            'slug'       => 'docs',
            'with_front' => true,
            'pages'      => true,
            'feeds'      => true,
        ];
        $args = [
            'labels'              => $labels,
            'supports'            => [ 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes', 'comments' ],
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-portfolio',
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'show_in_rest'        => true,
            'rewrite'             => $rewrite,
            'capability_type'     => 'post',
            'taxonomies'          => [ 'doc_tag' ],
        ];

        register_post_type( $this->post_type, apply_filters( 'weidocs_post_type', $args ) );
    }

    /**
     * Register doc tags taxonomy.
     *
     * @return void
     */
    public function register_taxonomy() {
        $labels = [
            'name'                       => _x( 'Tags', 'Taxonomy General Name', 'weidocs' ),
            'singular_name'              => _x( 'Tag', 'Taxonomy Singular Name', 'weidocs' ),
            'menu_name'                  => __( 'Tags', 'weidocs' ),
            'all_items'                  => __( 'All Tags', 'weidocs' ),
            'parent_item'                => __( 'Parent Tag', 'weidocs' ),
            'parent_item_colon'          => __( 'Parent Tag:', 'weidocs' ),
            'new_item_name'              => __( 'New Tag', 'weidocs' ),
            'add_new_item'               => __( 'Add New Item', 'weidocs' ),
            'edit_item'                  => __( 'Edit Tag', 'weidocs' ),
            'update_item'                => __( 'Update Tag', 'weidocs' ),
            'view_item'                  => __( 'View Tag', 'weidocs' ),
            'separate_items_with_commas' => __( 'Separate items with commas', 'weidocs' ),
            'add_or_remove_items'        => __( 'Add or remove items', 'weidocs' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'weidocs' ),
            'popular_items'              => __( 'Popular Tags', 'weidocs' ),
            'search_items'               => __( 'Search Tags', 'weidocs' ),
            'not_found'                  => __( 'Not Found', 'weidocs' ),
            'no_terms'                   => __( 'No items', 'weidocs' ),
            'items_list'                 => __( 'Tags list', 'weidocs' ),
            'items_list_navigation'      => __( 'Tags list navigation', 'weidocs' ),
        ];

        $rewrite = [
            'slug'         => 'doc-tag',
            'with_front'   => true,
            'hierarchical' => false,
        ];

        $args = [
            'labels'            => $labels,
            'hierarchical'      => false,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud'     => true,
            'show_in_rest'      => true,
            'rewrite'           => $rewrite,
        ];

        register_taxonomy( 'doc_tag', [ 'docs' ], $args );
    }
}
