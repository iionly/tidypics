<?php

$guid = (int) elgg_extract('guid', $vars);
$entity = get_entity($guid);
if (!$entity) {
	return;
}
switch ($entity->getSubtype()) {
	case TidypicsAlbum::SUBTYPE:
		echo elgg_view_resource('tidypics/photos/album/edit', $vars);
	break;
	case TidypicsImage::SUBTYPE:
		echo elgg_view_resource('tidypics/photos/image/edit', $vars);
	break;
	case TidypicsBatch::SUBTYPE:
		echo elgg_view_resource('tidypics/photos/batch/edit', $vars);
	break;
	default:
	return false;
}
