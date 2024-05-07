<?php
/**
 * Create new album page
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$guid = (int) elgg_extract('guid', $vars);
if (!$guid) {
	$guid = elgg_get_logged_in_user_guid();
}

elgg_entity_gatekeeper($guid);

$container = get_entity($guid);

if (!$container->canWriteToContainer(0, 'object', TidypicsAlbum::SUBTYPE)) {
	throw new \Elgg\Exceptions\Http\EntityPermissionsException();
}

elgg_push_collection_breadcrumbs('object', TidypicsAlbum::SUBTYPE, $container);
elgg_push_breadcrumb(elgg_echo('add:object:album'));

$title = elgg_echo('add:object:album');

$vars = TidypicsTidypics::tidypics_prepare_form_vars();
$content = elgg_view_form('photos/album/save', ['method' => 'post'], $vars);

$body = elgg_view_layout('default', [
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('photos/sidebar_al', ['page' => 'upload']),
]);

echo elgg_view_page($title, $body);
