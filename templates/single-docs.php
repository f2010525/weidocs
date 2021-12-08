<?php
/**
 * The template for displaying a single doc
 *
 * To customize this template, create a folder in your current theme named "weidocs" and copy it there.
 */
$skip_sidebar = ( get_post_meta( $post->ID, 'skip_sidebar', true ) == 'yes' ) ? true : false;

get_header(); ?>

    <?php
        /**
         * @since 1.4
         *
         * @hooked weidocs_template_wrapper_start - 10
         */
        do_action( 'weidocs_before_main_content' );
    ?>

    <?php while ( have_posts() ) {
        the_post(); ?>

        <div class="weidocs-single-wrap">

            <?php if ( !$skip_sidebar ) { ?>

                <?php weidocs_get_template_part( 'docs', 'sidebar' ); ?>

            <?php } ?>

            <div class="weidocs-single-content">
                <?php weidocs_breadcrumbs(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
                    <header class="entry-header">
                        <?php the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' ); ?>

                        <?php if ( weidocs_get_option( 'print', 'weidocs_settings', 'on' ) == 'on' ) { ?>
                            <a href="#" class="weidocs-print-article weidocs-hide-print weidocs-hide-mobile" title="<?php echo esc_attr( __( 'Print this article', 'weidocs' ) ); ?>"><i class="weidocs-icon weidocs-icon-print"></i></a>
                        <?php } ?>
                    </header><!-- .entry-header -->

                    <div class="entry-content" itemprop="articleBody">
                        <?php
                            the_content( sprintf(
                                /* translators: %s: Name of current post. */
                                wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'weidocs' ), [ 'span' => [ 'class' => [] ] ] ),
                                the_title( '<span class="screen-reader-text">"', '"</span>', false )
                            ) );

                            wp_link_pages( [
                                'before' => '<div class="page-links">' . esc_html__( 'Docs:', 'weidocs' ),
                                'after'  => '</div>',
                            ] );

                            $children = wp_list_pages( 'title_li=&order=menu_order&child_of=' . $post->ID . '&echo=0&post_type=' . $post->post_type );

                            if ( $children ) {
                                echo '<div class="article-child well">';
                                echo '<h3>' . __( 'Articles', 'weidocs' ) . '</h3>';
                                echo '<ul>';
                                echo $children;
                                echo '</ul>';
                                echo '</div>';
                            }

                            $tags_list = weidocs_get_the_doc_tags( $post->ID, '', ', ' );

                            if ( $tags_list ) {
                                printf( '<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
                                    _x( 'Tags', 'Used before tag names.', 'weidocs' ),
                                    $tags_list
                                );
                            }
                        ?>
                    </div><!-- .entry-content -->

                    <footer class="entry-footer weidocs-entry-footer">
                        <?php if ( weidocs_get_option( 'email', 'weidocs_settings', 'on' ) == 'on' ) { ?>
                            <span class="weidocs-help-link weidocs-hide-print weidocs-hide-mobile">
                                <i class="weidocs-icon weidocs-icon-envelope"></i>
                                <?php printf( '%s <a id="weidocs-stuck-modal" href="%s">%s</a>', __( 'Still stuck?', 'weidocs' ), '#', __( 'How can we help?', 'weidocs' ) ); ?>
                            </span>
                        <?php } ?>

                        <div class="weidocs-article-author" itemprop="author" itemscope itemtype="https://schema.org/Person">
                            <meta itemprop="name" content="<?php echo get_the_author(); ?>" />
                            <meta itemprop="url" content="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" />
                        </div>

                        <meta itemprop="datePublished" content="<?php echo get_the_time( 'c' ); ?>"/>
                        <time itemprop="dateModified" datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>"><?php printf( __( 'Updated on %s', 'weidocs' ), get_the_modified_date() ); ?></time>
                    </footer>

                    <?php weidocs_doc_nav(); ?>

                    <?php if ( weidocs_get_option( 'helpful', 'weidocs_settings', 'on' ) == 'on' ) { ?>
                        <?php weidocs_get_template_part( 'content', 'feedback' ); ?>
                    <?php } ?>

                    <?php if ( weidocs_get_option( 'email', 'weidocs_settings', 'on' ) == 'on' ) { ?>
                        <?php weidocs_get_template_part( 'content', 'modal' ); ?>
                    <?php } ?>

                    <?php if ( weidocs_get_option( 'comments', 'weidocs_settings', 'off' ) == 'on' ) { ?>
                        <?php if ( comments_open() || get_comments_number() ) { ?>
                            <div class="weidocs-comments-wrap">
                                <?php comments_template(); ?>
                            </div>
                        <?php } ?>
                    <?php } ?>

                </article><!-- #post-## -->
            </div><!-- .weidocs-single-content -->
        </div><!-- .weidocs-single-wrap -->

    <?php } ?>

    <?php
        /**
         * @since 1.4
         *
         * @hooked weidocs_template_wrapper_end - 10
         */
        do_action( 'weidocs_after_main_content' );
    ?>

<?php get_footer(); ?>
