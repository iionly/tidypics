<?php
/**
 * Edit the image information for a batch of images
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', TidypicsBatch::SUBTYPE);

$batch = get_entity($guid);

if (!$batch->canEdit()) {
	throw new \Elgg\EntityPermissionsException();
}

elgg_push_entity_breadcrumbs($batch);
elgg_push_breadcrumb(elgg_echo('edit'));

$title = elgg_echo('edit:object:tidypics_batch');

$content = elgg_view_form('photos/batch/edit', [], ['batch' => $batch]);

$body = elgg_view_layout('default', [
	'filter' => false,
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('photos/sidebar_al', ['page' => 'upload']),
]);

echo elgg_view_page($title, $body);
