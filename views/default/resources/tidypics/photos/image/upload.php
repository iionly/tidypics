<?php
/**
 * Upload images
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$album_guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($album_guid, 'object', TidypicsAlbum::SUBTYPE);

$album = get_entity($album_guid);
if (!($album instanceof TidypicsAlbum)) {
	return;
}

if (!$album->getContainerEntity()->canWriteToContainer()) {
	return;
}

elgg_push_collection_breadcrumbs('object', TidypicsImage::SUBTYPE, $album);
elgg_push_breadcrumb(elgg_echo('add:object:image'));

$title = elgg_echo('add:object:image');

if (elgg_get_plugin_setting('uploader', 'tidypics')) {
	$uploader = 'ajax';
} else {
	$uploader = 'basic';
}
if ($uploader == 'basic') {
	$form_vars = [
		'action' => 'action/photos/image/upload',
		'enctype' => 'multipart/form-data',
		'class' => 'elgg-form-settings',
	];
	$body_vars = [
		'entity' => $album,
	];
	$content = elgg_view_form('photos/basic_upload', $form_vars, $body_vars);
} else {
	elgg_require_css('tidypics/css/jqueryui-theme');
	elgg_require_css('tidypics/css/plupload/css/jquery.ui.plupload');
	elgg_require_js('tidypics/uploading');
	$content = elgg_view('forms/photos/ajax_upload', ['entity' => $album]);
}

$body = elgg_view_layout('default', [
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'sidebar' => elgg_view('photos/sidebar_im', ['page' => 'upload']),
]);

echo elgg_view_page($title, $body);
