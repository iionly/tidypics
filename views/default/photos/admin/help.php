<?php
/**
 * Tidypics Help
 *
 */

use \Michelf\MarkdownExtra;

$faq = elgg_get_plugins_path() . 'tidypics/FAQ.txt';
$text = MarkdownExtra::defaultTransform(file_get_contents($faq));

$content = elgg_format_element('div', ['class' => 'elgg-markdown'], $text);

echo elgg_view_module('inline', elgg_echo('tidypics:help'), $content);
