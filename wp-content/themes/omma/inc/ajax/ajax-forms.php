<?php

/**
 * Handlers de formularios via admin-post.php
 *
 * @package omma
 */

// ── Nonce endpoint (REST, never cached) ───────────────────────────────────────

add_action('rest_api_init', function () {
	register_rest_route('omma/v1', '/contact-nonce', [
		'methods'             => 'GET',
		'callback'            => fn() => ['nonce' => wp_create_nonce('omma_contact_submit')],
		'permission_callback' => '__return_true',
	]);
});

// ── AJAX contact form (block-contact) ─────────────────────────────────────────

add_action('wp_ajax_nopriv_omma_contact_submit', 'omma_ajax_contact_submit');
add_action('wp_ajax_omma_contact_submit',        'omma_ajax_contact_submit');

function omma_ajax_contact_submit(): void
{
	check_ajax_referer('omma_contact_submit', '_wpnonce');

	// Strip newlines to prevent email header injection
	$fname   = isset($_POST['fname'])   ? preg_replace('/[\r\n]/', '', sanitize_text_field($_POST['fname']))   : '';
	$lname   = isset($_POST['lname'])   ? preg_replace('/[\r\n]/', '', sanitize_text_field($_POST['lname']))   : '';
	$email   = isset($_POST['email'])   ? sanitize_email($_POST['email'])                                      : '';
	$message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message'])                           : '';

	if (empty($fname) || empty($email) || !is_email($email)) {
		wp_send_json_error(['message' => 'Please fill in all required fields.']);
	}

	$to = get_option('admin_email');

	if (empty($to) || !is_email($to)) {
		wp_send_json_error(['message' => 'Could not send message. Please try again later.']);
	}

	$subject = sprintf('[%s] New contact from %s %s', get_bloginfo('name'), $fname, $lname);
	$body    = implode("\n", [
		"Name: {$fname} {$lname}",
		"Email: {$email}",
		'',
		"Message:\n{$message}",
	]);
	$headers = [
		'Content-Type: text/plain; charset=UTF-8',
		"Reply-To: {$fname} {$lname} <{$email}>",
	];

	$sent = wp_mail($to, $subject, $body, $headers);

	if ($sent) {
		wp_send_json_success(['message' => "Message sent! We'll be in touch soon."]);
	} else {
		error_log('omma_contact_submit: wp_mail() failed for ' . $email);
		wp_send_json_error(['message' => 'Failed to send. Please try again.']);
	}
}

// ── Legacy admin-post handler ──────────────────────────────────────────────────

add_action('admin_post_nopriv_block_contact_submit', 'omma_handle_contact_submit');
add_action('admin_post_block_contact_submit', 'omma_handle_contact_submit');

function omma_handle_contact_submit(): void
{
	if (
		!isset($_POST['block_contact_nonce']) ||
		!wp_verify_nonce($_POST['block_contact_nonce'], 'block_contact_submit')
	) {
		wp_die('Security check failed', 'Error', ['response' => 403]);
	}

	$name         = isset($_POST['name'])         ? sanitize_text_field($_POST['name'])         : '';
	$email        = isset($_POST['email'])         ? sanitize_email($_POST['email'])             : '';
	$project_type = isset($_POST['project_type'])  ? sanitize_text_field($_POST['project_type']) : '';
	$budget       = isset($_POST['budget'])        ? sanitize_text_field($_POST['budget'])       : '';
	$deadline     = isset($_POST['deadline'])      ? sanitize_text_field($_POST['deadline'])     : '';
	$message      = isset($_POST['message'])       ? wp_kses_post($_POST['message'])             : '';
	$privacy      = isset($_POST['privacy'])       ? 'Accepted' : 'Not accepted';

	if (empty($name) || empty($email) || !is_email($email)) {
		wp_safe_redirect(add_query_arg('contact_status', 'error', wp_get_referer() ?: home_url('/')));
		exit;
	}

	$to      = get_option('admin_email');
	$subject = sprintf('New contact from %s', get_bloginfo('name'));
	$body    = implode("\n", [
		'You have received a new contact request:',
		'',
		"Name: {$name}",
		"Email: {$email}",
		"Project: {$project_type}",
		"Budget range: {$budget}",
		"Deadline / timing: {$deadline}",
		'',
		"Message:\n{$message}",
		'',
		"Privacy: {$privacy}",
	]);

	$headers = [
		'Content-Type: text/plain; charset=UTF-8',
		"Reply-To: {$name} <{$email}>",
	];

	$sent = wp_mail($to, $subject, $body, $headers);

	wp_safe_redirect(add_query_arg('contact_status', $sent ? 'ok' : 'error', wp_get_referer() ?: home_url('/')));
	exit;
}
