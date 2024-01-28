    <div class="wrap" style="padding-left: 25px;">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <p><?php _e('Manage your reminder templates below', 'wpforms-reminder'); ?>:</p>
        <?php
            // Include the template editor page
            $action = isset($_GET['action']) ? $_GET['action'] : '';
            
            $template_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            
            if ($action === 'edit') {
                include(plugin_dir_path(__FILE__) . 'template-editor.php');
            } else {
                ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                    <tr>
                        <th scope="col" class="manage-column column-title column-primary"><?php _e('Template Title', 'wpforms-reminder'); ?></th>
                        <th scope="col" class="manage-column column-form-id"><?php _e('Form name', 'wpforms-reminder'); ?></th>
                        <th scope="col" class="manage-column column-is-active"><?php _e('Is active', 'wpforms-reminder'); ?></th>
                        <th scope="col" class="manage-column column-to-email"><?php _e('E-mail', 'wpforms-reminder'); ?></th>
                        <th scope="col" class="manage-column column-subject"><?php _e('Subject', 'wpforms-reminder'); ?></th>
                        <th scope="col" class="manage-column column-sender-name"><?php _e('Sender name', 'wpforms-reminder'); ?></th>
                        <th scope="col" class="manage-column column-sender-address"><?php _e('Sender address', 'wpforms-reminder'); ?></th>
                        <th scope="col" class="manage-column column-replyto"><?php _e('Reply to', 'wpforms-reminder'); ?></th>
                        <th scope="col" class="manage-column column-days-before"><?php _e('Reminde Before', 'wpforms-reminder'); ?></th>
                        <th scope="col" class="manage-column column-range-field-id"><?php _e('Field id of date range', 'wpforms-reminder'); ?></th>
                        <th scope="col" class="manage-column column-content"><?php _e('Template', 'wpforms-reminder'); ?></th>
                        <th scope="col" class="manage-column column-actions"><?php _e('Actions', 'wpforms-reminder'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                <?php
            // Output the list of email templates
            $args = array(
                'numberposts' => -1,
                'post_type'   => 'wpforms_reminder',
                'post_status' => 'any',
                'order'       => 'ASC',
                'orderby'     => 'title'
            );
            $templates = get_posts($args);
            
            foreach ($templates as $template) : ?>
                    <tr>
                    <td class="title column-title has-row-actions column-primary">
                    <strong>
                     <a href="<?= admin_url('admin.php?page=wpforms-reminder-settings&action=edit&id=' . $template->ID); ?>" class="edit"><?= esc_html($template->post_title);?></a>
                    </strong>
                    </td>
                    <td class="column-form-id"><?= esc_html(wpforms()->form->get(get_post_meta($template->ID, 'form_id', true))->post_title); ?></td>
                    <td class="column-is-active"><?= get_post_meta($template->ID, 'is_active', true)=='1'?__('Active', 'wpforms-reminder'):__('Not active', 'wpforms-reminder') ?></td>
                    <td class="column-to-email"><?= esc_html(get_post_meta($template->ID, 'to_email', true)) ?></td>
                    <td class="column-subject"><?= esc_html(get_post_meta($template->ID, 'subject', true)) ?></td>
                    <td class="column-sender-name"><?= esc_html(get_post_meta($template->ID, 'sender_name', true)) ?></td>
                    <td class="column-sender-address"><?= esc_html(get_post_meta($template->ID, 'sender_address', true)) ?></td>
                    <td class="column-replyto"><?= esc_html(get_post_meta($template->ID, 'replyto', true)) ?></td>
                    <td class="column-days-before"><?= esc_html(get_post_meta($template->ID, 'days_before', true)) ?></td>
                    <td class="column-range-field-id"><?= esc_html(get_post_meta($template->ID, 'range_field_id', true)) ?></td>
                    <td class="content column-content"><?= wp_trim_words(strip_tags($template->post_content), 10) ?></td>
                    <td class="actions column-actions"><a href="<?= wp_nonce_url(admin_url('admin.php?page=wpforms-reminder-settings&action=delete&id=' . $template->ID), 'wpforms-reminder-delete-template-' . $template->ID) ?>" class="submitdelete" onclick="return confirm('<?php _e("Are you sure you want to delete this template?", 'wpforms-reminder') ?>')"><?php _e('Delete', 'course-reminder') ?></a></td>
                    </tr>
            <?php endforeach; ?>
            <p><a href="<?= admin_url('admin.php?page=wpforms-reminder-settings&action=edit') ?>" class="button"><?php _e('Add New Reminder', 'wpforms-reminder'); ?></a></p>
        <?php } ?>

    </div>