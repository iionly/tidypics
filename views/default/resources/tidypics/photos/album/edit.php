<?php
/**
 * Edit an album
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', TidypicsAlbum::SUBTYPE);

$album = get_entity($guid);

if (!$album->canEdit()) {
	throw new \Elgg\EntityPermissionsException();
}

elgg_push_entity_breadcrumbs($album);
elgg_push_breadcrumb(elgg_echo('edit'));

$title = elgg_echo('edit:object:album');

$vars = tidypics_prepare_form_vars($album);
$content = elgg_view_form('photos/album/save', ['method' => 'post'], $vars);

$body = elgg_view_layout('default', [
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('photos/sidebar_al', ['page' => 'upload']),
]);

echo elgg_view_page($title, $body);
