<?php

function wpf_reminder_send_function() {
    print_r("Send mails start!");
    $args = array(
        'numberposts' => -1,
        'post_type'   => 'wpforms_reminder',
        'post_status' => 'any',
        'meta_key'    => 'is_active',
        'meta_value'    => '1',
    );
    $templates = get_posts($args);
    
    // Get current date
    $currentDateTime = new DateTimeImmutable();
    
    foreach ($templates as $template) {
        $form_data = wpforms()->form->get( get_post_meta($template->ID, 'form_id', true), [ 'content_only' => true ] );
        $form_data = apply_filters( 'wpforms_frontend_form_data', $form_data );
        // Date range format for check date_regex
        $range_pattern = get_post_meta($template->ID, 'date_regex', true);
        $regex_index = get_post_meta($template->ID, 'regex_index', true);
        $date_format = get_post_meta($template->ID, 'date_format', true);
        
        $entries = wpforms()->entry->get_entries(['form_id' => get_post_meta($template->ID, 'form_id', true)]);
        
        // Add days for compare date
        $DiffDateTime = $currentDateTime->add(new DateInterval('P' . get_post_meta($template->ID, 'days_before', true) .'D'));
        $DiffDateTime = $DiffDateTime->format($date_format);
        
        // Get date range field ID
        $date_field_id = get_post_meta($template->ID, 'range_field_id', true);
        
        foreach ($entries as $entry) {
            $entry->fields = json_decode($entry->fields, true);
            if(isset($entry->fields[$date_field_id])) {
                $dateRange = $entry->fields[$date_field_id]['value'];
                
                if (preg_match($range_pattern, $dateRange, $matches)) {
                    if ($DiffDateTime == $matches[$regex_index]) {
                        wpf_reminder_send_email($template, $entry, $form_data);
                    }
                }
            }
            
        }
    }
}



function wpf_reminder_send_email($template, $entry, $form_data) {
    
    $smart_tags = wpforms()->get('smart_tags');
    $to = get_post_meta($template->ID, 'to_email', true);
    $to = $smart_tags->process($to, $entry, $entry->fields);
    
    $sender_name = get_post_meta($template->ID, 'sender_name', true);
    $sender_name = $smart_tags->process($sender_name, $entry, $entry->fields);
    
    $sender_address = get_post_meta($template->ID, 'sender_address', true);
    $sender_address = $smart_tags->process($sender_address, $entry, $entry->fields);
    
    $replyto = get_post_meta($template->ID, 'replyto', true);
    $replyto = $smart_tags->process($replyto, $entry, $entry->fields);
    
    $subject = get_post_meta($template->ID, 'subject', true);
    $subject = $smart_tags->process($subject, $entry, $entry->fields);
    
    $post_content = $template->post_content;
    
    $emails = new WPForms_WP_Emails();
    $emails->__set( 'fields', $entry->fields );
    $emails->__set( 'entry_id', $entry->entry_id );
    $emails->__set( 'from_name', $sender_name );
    $emails->__set( 'from_address', $sender_address );
    $emails->__set( 'reply_to', $replyto );
    $emails->__set( 'html', true );
    $emails->__set( 'content_type', 'text/html' );
    $emails = apply_filters( 'wpforms_entry_email_before_send', $emails );
    $emails->send( $to, $subject, $post_content );
    

}
