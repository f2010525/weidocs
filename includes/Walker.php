<?php

namespace WeDevs\WeiDocs;

use Walker_Page;

/**
 * weiDocs Docs Walker.
 */
class Walker extends Walker_Page {

    /**
     * Initialize the class
     */
    public function start_el( &$output, $page, $depth = 0, $args = [], $current_page = 0 ) {
        if ( isset( $args['pages_with_children'][ $page->ID ] ) ) {
            $args['link_after'] = '<span class="weidocs-caret"></span>';
        }

        parent::start_el( $output, $page, $depth, $args, $current_page );
    }
}
