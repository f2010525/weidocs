<?php
/*
Plugin Name: weiDocs
Plugin URI: https://wpwenku.com/
Description: A documentation plugin for WordPress
Version: 1.7.1
Author: wpwenku.com
Author URI: https://wpwenku.com/
License: GPL2
Text Domain: weidocs
Domain Path: /languages
*/

/*
 * Copyright (c) 2021 Tareq Hasan (email: tareq@wedevs.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * WeiDocs class.
 *
 * @class WeiDocs The class that holds the entire WeiDocs plugin
 */
final class WeiDocs {

    /**
     * Plugin version.
     *
     * @var string
     */
    const VERSION = '1.7.1';

    /**
     * The plugin url.
     *
     * @var string
     */
    public $plugin_url;

    /**
     * The plugin path.
     *
     * @var string
     */
    public $plugin_path;

    /**
     * The theme directory path.
     *
     * @var string
     */
    public $theme_dir_path;

    /**
     * The post type name.
     *
     * @var string
     */
    private $post_type = 'docs';

    /**
     * Holds various class instances
     *
     * @var array
     */
    private $container = [];

    /**
     * Class construcotr
     */
    private function __construct() {
        $this->define_constants();
        $this->init_actions();

        register_activation_hook( __FILE__, [ $this, 'activate' ] );

        add_action( 'after_setup_theme', [ $this, 'init_classes' ] );
    }

    /**
     * Initializes the WeiDocs() class.
     *
     * Checks for an existing WeiDocs() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new WeiDocs();
        }

        return $instance;
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'WEDOCS_VERSION', self::VERSION );
        define( 'WEDOCS_FILE', __FILE__ );
        define( 'WEDOCS_PATH', __DIR__ );
        define( 'WEDOCS_URL', plugins_url( '', WEDOCS_FILE ) );
        define( 'WEDOCS_ASSETS', WEDOCS_URL . '/assets' );
    }

    /**
     * Magic getter to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
        }

        return $this->{$prop};
    }

    /**
     * Initialize the plugin.
     *
     * @return void
     */
    public function init_actions() {
        $this->theme_dir_path = apply_filters( 'weidocs_theme_dir_path', 'weidocs/' );

        // Localize our plugin
        add_action( 'init', [ $this, 'localization_setup' ] );

        // registeer our widget
        add_action( 'widgets_init', [ $this, 'register_widget' ] );
    }

    /**
     * The plugin activation function.
     *
     * @since 1.3
     *
     * @return void
     */
    public function activate() {
        $installer = new WeDevs\WeiDocs\Installer();
        $installer->run();
    }

    /**
     * Initialize the classes.
     *
     * @since 1.4
     *
     * @return void
     */
    public function init_classes() {
        $this->container['post_type'] = new WeDevs\WeiDocs\Post_Types();

        if ( is_admin() ) {
            $this->container['admin'] =new WeDevs\WeiDocs\Admin();
        } else {
            $this->container['frontend'] =new WeDevs\WeiDocs\Frontend();
        }

        if ( wp_doing_ajax() ) {
            $this->container['ajax'] =new WeDevs\WeiDocs\Ajax();
        }

        $this->container['api'] =new WeDevs\WeiDocs\API();
    }

    /**
     * Initialize plugin for localization.
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( 'weidocs', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Register the search widget.
     *
     * @return void
     */
    public function register_widget() {
        register_widget( 'WeDevs\WeiDocs\Widget' );
    }

    /**
     * Get the plugin url.
     *
     * @return string
     */
    public function plugin_url() {
        if ( $this->plugin_url ) {
            return $this->plugin_url;
        }

        return $this->plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
    }

    /**
     * Get the plugin path.
     *
     * @return string
     */
    public function plugin_path() {
        if ( $this->plugin_path ) {
            return $this->plugin_path;
        }

        return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
    }

    /**
     * Get the template path.
     *
     * @return string
     */
    public function template_path() {
        return $this->plugin_path() . '/templates/';
    }

    /**
     * Get the theme directory path.
     *
     * @return string
     */
    public function theme_dir_path() {
        return $this->theme_dir_path;
    }
} // WeiDocs

/**
 * Initialize the plugin.
 *
 * @return \WeiDocs
 */
function weidocs() {
    return WeiDocs::init();
}

// kick it off
weidocs();
