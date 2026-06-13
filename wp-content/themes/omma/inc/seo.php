<?php

/**
 * SEO: dynamic meta description, canonical, Open Graph, Twitter Cards.
 *
 * Description priority:
 *   1. Post/page excerpt (set in the WP editor sidebar)
 *   2. First 25 words of post content
 *   3. Site tagline (Settings → General)
 *
 * OG image priority:
 *   1. Featured image of the current post/page
 *   2. ACF global option field: seo_og_image (image)
 *   3. /assets/img/og-image.jpg (static fallback)
 *
 * @package omma
 */

define('OMMA_DEFAULT_DESCRIPTION', 'OM-MA IDi estructura soluciones Tax Lease que convierten incentivos fiscales de I+D+i en financiación para empresas innovadoras y oportunidades de eficiencia fiscal para inversores.');

function omma_seo_description(): string
{
	if (is_singular()) {
		$post = get_queried_object();
		if ($post instanceof WP_Post) {
			if ($post->post_excerpt) {
				return wp_strip_all_tags($post->post_excerpt);
			}
			if ($post->post_content) {
				$text = wp_trim_words(wp_strip_all_tags($post->post_content), 25, '…');
				if ($text) {
					return $text;
				}
			}
		}
	}
	$tagline = get_bloginfo('description');
	return $tagline ?: OMMA_DEFAULT_DESCRIPTION;
}

add_filter('pre_get_document_title', function (string $title): string {
	if (is_front_page()) {
		return 'OM-MA IDi | Tax Lease I+D+i — Financiación Inteligente para Innovación';
	}
	return $title;
});

function omma_seo_og_image(): string
{
	if (is_singular() && has_post_thumbnail()) {
		$src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
		if ($src) {
			return esc_url($src[0]);
		}
	}
	if (function_exists('get_field')) {
		$img = get_field('seo_og_image', 'option');
		if (!empty($img['url'])) {
			return esc_url($img['url']);
		}
	}
	return esc_url(get_template_directory_uri() . '/assets/img/og-image.jpg');
}

function omma_output_seo_meta(): void
{
	$title       = esc_attr(wp_get_document_title());
	$description = esc_attr(omma_seo_description());
	$url         = esc_url(get_permalink() ?: home_url('/'));
	$image       = omma_seo_og_image();
	$site_name   = esc_attr(get_bloginfo('name'));
	$locale      = esc_attr(get_locale());
	?>

<!-- SEO ──────────────────────────────────────────────────────────────────── -->
<meta name="description" content="<?php echo $description; ?>">
<link rel="canonical" href="<?php echo $url; ?>">

<!-- Open Graph ───────────────────────────────────────────────────────────── -->
<meta property="og:type"        content="website">
<meta property="og:site_name"   content="<?php echo $site_name; ?>">
<meta property="og:locale"      content="<?php echo $locale; ?>">
<meta property="og:title"       content="<?php echo $title; ?>">
<meta property="og:description" content="<?php echo $description; ?>">
<meta property="og:url"         content="<?php echo $url; ?>">
<meta property="og:image"       content="<?php echo $image; ?>">

<!-- Twitter Card ─────────────────────────────────────────────────────────── -->
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="<?php echo $title; ?>">
<meta name="twitter:description" content="<?php echo $description; ?>">
<meta name="twitter:image"       content="<?php echo $image; ?>">

<?php
}
add_action('wp_head', 'omma_output_seo_meta', 1);
