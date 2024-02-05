<?php
    if (isset($_POST['action']) && $_POST['action'] == 'save') {
        $template_id = isset($_POST['template_id']) ? intval($_POST['template_id']) : 0;
        
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'wpforms-reminder-edit-' . $template_id)) {
            die('Security check');
        }
        
        if($template_id==0) {
            $template_id = wp_insert_post(array('post_type' => 'wpforms_reminder', 'post_title' => 'New Template', 'post_content' => '', 'post_status'=>'private'));
        }


        // Save template content
        $template_content = isset($_POST['template_content']) ? sanitize_post_field('post_content', $_POST['template_content'], $template_id, 'db') : '';
        $post_title = isset($_POST['post_title']) ? sanitize_post_field('post_title', $_POST['post_title'], $template_id, 'db') : '';
        wp_update_post(array('ID' => $template_id, 'post_content' => $template_content, 'post_title' => $post_title));
        
        // Save 'is_active'
        update_post_meta($template_id, 'is_active', isset($_POST['is_active']) ? sanitize_text_field($_POST['is_active']) : '0');
        
        // Save 'days_before'
        update_post_meta($template_id, 'form_id', isset($_POST['form_id']) ? intval($_POST['form_id']) : 0);
        
        // Save 'to_email'
        update_post_meta($template_id, 'to_email', isset($_POST['to_email']) ? $_POST['to_email'] : '');
        
        // Save 'to_email'
        update_post_meta($template_id, 'date_regex', isset($_POST['date_regex']) ? $_POST['date_regex'] : '');
        
        // Save 'to_email'
        update_post_meta($template_id, 'date_format', isset($_POST['date_format']) ? $_POST['date_format'] : '');
        
        // Save 'subject'
        update_post_meta($template_id, 'subject', isset($_POST['subject']) ? $_POST['subject'] : '');
        
        // Save 'sender_name'
        update_post_meta($template_id, 'sender_name', isset($_POST['sender_name']) ? $_POST['sender_name'] : '');
        
        // Save 'sender_address'
        update_post_meta($template_id, 'sender_address', isset($_POST['sender_address']) ? $_POST['sender_address'] : '');
        
        // Save 'replyto'
        update_post_meta($template_id, 'replyto', isset($_POST['replyto']) ? $_POST['replyto'] : '');
        
        // Save 'days_before'
        update_post_meta($template_id, 'days_before', isset($_POST['days_before']) ? intval($_POST['days_before']) : 0);
        
        // Save 'regex_index'
        update_post_meta($template_id, 'regex_index', isset($_POST['regex_index']) ? intval($_POST['regex_index']) : 0);
        
        // Save 'range_field_id'
        update_post_meta($template_id, 'range_field_id', isset($_POST['range_field_id']) ? intval($_POST['range_field_id']) : 0);
        

        // Redirect back to the templates list
        wp_safe_redirect(admin_url('admin.php?page=wpforms-reminder-settings'));
        exit;
    }