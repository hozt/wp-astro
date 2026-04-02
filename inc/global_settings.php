<?php

// function global_wp_smtp_options() {
//     // Define global SMTP settings
//     return array(
//         'from'        => 'cloud@hozt.com',     // Global "From" email address
//         'fromname'    => 'HoZt Cloud Sites',           // Global "From" name
//         'host'        => 'smtp.sendgrid.net',    // SMTP host
//         'smtpsecure'  => 'ssl',                 // Encryption: '', 'ssl', or 'tls'
//         'port'        => '465',                 // SMTP port
//         'smtpauth'    => 'yes',                 // Enable SMTP authentication
//         'deactivate'  => '',                    // Deactivate WP Mail SMTP if needed
//     );
// }
// add_filter('pre_option_wp_smtp_options', 'global_wp_smtp_options');

// function set_smtp_auth_details($phpmailer) {
//     $phpmailer->Username = 'smtp_user';     // Your SMTP username
//     $phpmailer->Password = 'smtp_password'; // Your SMTP password
//     $phpmailer->SMTPAuth = true;            // Ensure SMTPAuth is set to true
// }
// add_action('phpmailer_init', 'set_smtp_auth_details');

add_filter('pre_option_wp_mail_smtp', function($default) {
    // Define global SMTP settings
    return array(
        'mail'        => array(
            'from_email'        => 'you@example.com',
            'from_name'         => 'Your Name',
        ),
        'smtp'        => array(
            'host'              => 'smtp.example.com',
            'port'              => '587',
            'encryption'        => 'tls',
            'authentication'    => true,
            'username'          => 'smtp_user',
            'password'          => 'smtp_password',
        ),
        'general'     => array(
            'deactivate'        => false,
        ),
    );
});