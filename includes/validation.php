<?php
// Common validation utilities

// Email regex pattern for HTML5 pattern attribute
const EMAIL_PATTERN = '[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}';
// Phone regex pattern for HTML5 pattern attribute
const PHONE_PATTERN = '\(\d{3}\)\d{3}-\d{4}';

/**
 * Validate an email address (server-side)
 * @param string $email
 * @return bool
 */
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate a phone number (server-side)
 * @param string $phone
 * @return bool
 */
function is_valid_phone($phone) {
    return preg_match('/^\(\d{3}\)\d{3}-\d{4}$/', $phone);
}
