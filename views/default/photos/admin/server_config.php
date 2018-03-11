<?php
/**
 * Tidypics server configuration
 *
 */

use \Michelf\MarkdownExtra;

$faq = elgg_get_plugins_path() . 'tidypics/CONFIG.txt';
$text = MarkdownExtra::defaultTransform(file_get_contents($faq));

$content = elgg_format_element('div', ['class' => 'elgg-markdown'], $text);

echo elgg_view_module('inline', elgg_echo('tidypics:server_config'), $content);
