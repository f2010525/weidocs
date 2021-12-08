<?php global $post; ?>

<div class="weidocs-feedback-wrap weidocs-hide-print">
    <?php
    $positive = (int) get_post_meta( $post->ID, 'positive', true );
    $negative = (int) get_post_meta( $post->ID, 'negative', true );

    $positive_title = $positive ? sprintf( _n( '%d person found this useful', '%d persons found this useful', $positive, 'weidocs' ), number_format_i18n( $positive ) ) : __( 'No votes yet', 'weidocs' );
    $negative_title = $negative ? sprintf( _n( '%d person found this not useful', '%d persons found this not useful', $negative, 'weidocs' ), number_format_i18n( $negative ) ) : __( 'No votes yet', 'weidocs' );
    ?>

    <?php _e( 'Was this article helpful to you?', 'weidocs' ); ?>

    <span class="vote-link-wrap">
        <a href="#" class="weidocs-tip positive" data-id="<?php the_ID(); ?>" data-type="positive" title="<?php echo esc_attr( $positive_title ); ?>">
            <?php _e( 'Yes', 'weidocs' ); ?>

            <?php if ( $positive ) { ?>
                <span class="count"><?php echo number_format_i18n( $positive ); ?></span>
            <?php } ?>
        </a>
        <a href="#" class="weidocs-tip negative" data-id="<?php the_ID(); ?>" data-type="negative" title="<?php echo esc_attr( $negative_title ); ?>">
            <?php _e( 'No', 'weidocs' ); ?>

            <?php if ( $negative ) { ?>
                <span class="count"><?php echo number_format_i18n( $negative ); ?></span>
            <?php } ?>
        </a>
    </span>
</div>