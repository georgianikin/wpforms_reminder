<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form method="post" action="">
        <?= wp_nonce_field('wpforms-reminder-edit-' . $template_id); ?>
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="template_id" value="<?= $template_id ?>">
        
        <label for="post_title"><?php _e('Template Title', 'wpforms-reminder');?>:</label>
        <input type="text" name="post_title" value="<?= esc_attr(get_post_field('post_title', $template_id)) ?>"><br>
        
        <label for="is_active"><?php _e('Is active', 'wpforms-reminder');?>:</label>
        <input type="checkbox" name="is_active" <?= checked(get_post_meta($template_id, 'is_active', true), '1', false) ?> value="1"><br>
        
        <label for="form_id"><?php _e('Form name', 'wpforms-reminder');?>:</label>
        <select name="form_id">
            <?php $forms = wpforms()->form->get();?>
            <?php foreach ($forms as $form) : ?>
            <option value="<?= $form->ID;?>" <?= get_post_meta($template_id, 'form_id', true)==$form->ID?' selected':''?>><?= $form->post_title;?></option>
            <?php endforeach;?>
        </select><br>
        
        <label for="to_email"><?php _e('E-mail', 'wpforms-reminder');?>:</label>
        <input type="text" name="to_email" value="<?= esc_attr(get_post_meta($template_id, 'to_email', true)) ?>"><br>
        
        <label for="subject"><?php _e('Subject', 'wpforms-reminder');?>:</label>
        <input type="text" name="subject" value="<?= esc_attr(get_post_meta($template_id, 'subject', true)) ?>"><br>
        
        <label for="sender_name"><?php _e('Sender name', 'wpforms-reminder');?>:</label>
        <input type="text" name="sender_name" value="<?= esc_attr(get_post_meta($template_id, 'sender_name', true)) ?>"><br>
        
        <label for="sender_address"><?php _e('Sender address', 'wpforms-reminder');?>:</label>
        <input type="text" name="sender_address" value="<?= esc_attr(get_post_meta($template_id, 'sender_address', true)) ?>"><br>
        
        <label for="replyto"><?php _e('Reply to', 'wpforms-reminder');?>:</label>
        <input type="text" name="replyto" value="<?= esc_attr(get_post_meta($template_id, 'replyto', true)) ?>"><br>
        
        <label for="range_field_id"><?php _e('Field id of date range', 'wpforms-reminder');?>:</label>
        
        <input type="number" name="range_field_id" value="<?= esc_attr(get_post_meta($template_id, 'range_field_id', true)) ?>"><br>
        
        <label for="days_before"><?php _e('Reminde before', 'wpforms-reminder');?>:</label>
        <input type="number" name="days_before" value="<?= esc_attr(get_post_meta($template_id, 'days_before', true)) ?>"><br>
        
        <label for="template_content"><?php _e('Template Content', 'wpforms-reminder');?>:</label><br>
            <?= wp_editor(get_post_field('post_content', $template_id), 'template_content'); ?>
        <br>
        <br><input type="submit" value="<?php _e('Save Reminder', 'wpforms-reminder');?>">
    </form>
</div>
