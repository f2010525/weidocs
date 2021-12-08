<?php
$name = $email = '';

if ( is_user_logged_in() ) {
    $user  = wp_get_current_user();
    $name  = $user->display_name;
    $email = $user->user_email;
}
?>

<div class="weidocs-modal-backdrop" id="weidocs-modal-backdrop"></div>
<div id="weidocs-contact-modal" class="weidocs-contact-modal weidocs-hide-print">
    <div class="weidocs-modal-header">
        <h1><?php _e( 'How can we help?', 'weidocs' ); ?></h1>
        <a href="#" id="weidocs-modal-close" class="weidocs-modal-close"><i class="weidocs-icon weidocs-icon-times"></i></a>
    </div>

    <div class="weidocs-modal-body">
        <div id="weidocs-modal-errors"></div>
        <form id="weidocs-contact-modal-form" action="" method="post">
            <div class="weidocs-form-row">
                <label for="name"><?php _e( 'Name', 'weidocs' ); ?></label>

                <div class="weidocs-form-field">
                    <input type="text" name="name" id="name" placeholder="" value="<?php echo $name; ?>" required />
                </div>
            </div>

            <div class="weidocs-form-row">
                <label for="email"><?php _e( 'Email', 'weidocs' ); ?></label>

                <div class="weidocs-form-field">
                    <input type="email" name="email" id="email" placeholder="you@example.com" value="<?php echo $email; ?>" <?php disabled( is_user_logged_in() ); ?> required />
                </div>
            </div>

            <div class="weidocs-form-row">
                <label for="subject"><?php _e( 'subject', 'weidocs' ); ?></label>

                <div class="weidocs-form-field">
                    <input type="text" name="subject" id="subject" placeholder="" value="" required />
                </div>
            </div>

            <div class="weidocs-form-row">
                <label for="message"><?php _e( 'message', 'weidocs' ); ?></label>

                <div class="weidocs-form-field">
                    <textarea type="message" name="message" id="message" required></textarea>
                </div>
            </div>

            <div class="weidocs-form-action">
                <input type="submit" name="submit" value="<?php echo esc_attr_e( 'Send', 'weidocs' ); ?>">
                <input type="hidden" name="doc_id" value="<?php the_ID(); ?>">
                <input type="hidden" name="action" value="weidocs_contact_feedback">
            </div>
        </form>
    </div>
</div>
