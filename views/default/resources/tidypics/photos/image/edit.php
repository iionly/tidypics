<?php
/**
 * Edit an image
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', TidypicsImage::SUBTYPE);

$image = get_entity($guid);

if (!$image->canEdit()) {
	throw new \Elgg\EntityPermissionsException();
}

elgg_push_entity_breadcrumbs($image);
elgg_push_breadcrumb(elgg_echo('edit'));

$title = elgg_echo('edit:object:image');

$vars = tidypics_prepare_form_vars($image);
$content = elgg_view_form('photos/image/save', ['method' => 'post'], $vars);

$body = elgg_view_layout('default', [
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('photos/sidebar_im', ['page' => 'upload']),
]);

echo elgg_view_page($title, $body);
