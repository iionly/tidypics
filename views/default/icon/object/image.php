<?php
/**
 * Image icon view
 *
 * @uses $vars['entity']     The entity the icon represents - uses getIconURL() method
 * @uses $vars['size']       tiny, small (default), large, master
 * @uses $vars['href']       Optional override for link
 * @uses $vars['img_class']  Optional CSS class added to img
 * @uses $vars['link_class'] Optional CSS class added to link
 * @uses $vars['title']      Optional title override
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$entity = $vars['entity'];

$sizes = ['master', 'large', 'small', 'tiny'];
// Get size
if (!in_array($vars['size'], $sizes)) {
	$vars['size'] = 'small';
}
$watermark_text = elgg_get_plugin_setting('watermark_text', 'tidypics', '');
if ($watermark_text && $vars['size'] == 'master') {
	$vars['size'] = 'large';
}

if (!isset($vars['title'])) {
	$title = $entity->getTitle();
} else {
	$title = $vars['title'];
	$title = htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
}

$url = $entity->getURL();
if (isset($vars['href'])) {
	$url = $vars['href'];
}

$class = '';
if (isset($vars['img_class'])) {
	$class = $vars['img_class'];
}
$class = "elgg-photo $class";

$img = elgg_view('output/img', [
	'src' => $entity->getIconURL($vars['size']),
	'class' => $class,
	'title' => $title,
	'alt' => $title,
]);

if ($url) {
	$params = [
		'href' => $url,
		'text' => $img,
		'is_trusted' => true,
	];
	if (isset($vars['link_class'])) {
		$params['class'] = $vars['link_class'];
	}
	echo elgg_view('output/url', $params);
} else {
	echo $img;
}
