<?php if ( $docs ) { ?>

<div class="weidocs-shortcode-wrap">
    <ul class="weidocs-docs-list col-<?php echo $col; ?>">

        <?php foreach ( $docs as $main_doc ) { ?>
            <li class="weidocs-docs-single">
                <h3><a href="<?php echo get_permalink( $main_doc['doc']->ID ); ?>"><?php echo $main_doc['doc']->post_title; ?></a></h3>

                <?php if ( $main_doc['sections'] ) { ?>

                    <div class="inside">
                        <ul class="weidocs-doc-sections">
                            <?php foreach ( $main_doc['sections'] as $section ) { ?>
                                <li><a href="<?php echo get_permalink( $section->ID ); ?>"><?php echo $section->post_title; ?></a></li>
                            <?php } ?>
                        </ul>
                    </div>

                <?php } ?>

                <div class="weidocs-doc-link">
                    <a href="<?php echo get_permalink( $main_doc['doc']->ID ); ?>"><?php echo $more; ?></a>
                </div>
            </li>
        <?php } ?>
    </ul>
</div>

<?php }
