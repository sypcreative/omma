<?php

/**
 * Handlers de formularios via admin-post.php
 *
 * @package omma
 */

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
