<?php
/**
 * Hooks for Tidypics
 */

/**
 * Add a menu item to an ownerblock
 */
function tidypics_owner_block_menu($hook, $type, $return, $params) {
	if ($params['entity'] instanceof ElggUser) {
		$url = "photos/siteimagesowner/{$params['entity']->guid}";
		$item = new ElggMenuItem('photos', elgg_echo('photos'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->tp_images_enable != "no") {
			$url = "photos/siteimagesgroup/{$params['entity']->guid}";
			$item = new ElggMenuItem('photos', elgg_echo('photos:group'), $url);
			$return[] = $item;
		}
	}

	if ($params['entity'] instanceof ElggUser) {
		$url = "photos/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('photo_albums', elgg_echo('albums'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->photos_enable != "no") {
			$url = "photos/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('photo_albums', elgg_echo('photos:group_albums'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 * Subtype tidypics_batch is registered only to be included in activity page filter
 * but we don't want any results for this subtype returned in a search
 */
function tidypics_batch_no_search_results($hook, $handler, $return, $params) {
	return false;
}

/**
 * Add Tidypics links/info to entity menu
 */
function tidypics_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'photos') {
		return $return;
	}

	if ($entity instanceof TidypicsImage) {
		$album = $entity->getContainerEntity();
		$cover_guid = $album->getCoverImageGuid();
		if ($cover_guid != $entity->getGUID() && $album->canEdit()) {
			$url = 'action/photos/album/set_cover'
				. '?image_guid=' . $entity->getGUID()
				. '&album_guid=' . $album->getGUID();

			$params = [
				'href' => $url,
				'text' => elgg_echo('album:cover_link'),
				'is_action' => true,
				'is_trusted' => true,
				'confirm' => elgg_echo('album:cover'),
			];
			$text = elgg_view('output/url', $params);

			$options = [
				'name' => 'set_cover',
				'text' => $text,
				'priority' => 80,
			];
			$return[] = ElggMenuItem::factory($options);
		}

		if (elgg_get_plugin_setting('view_count', 'tidypics')) {
			$view_info = $entity->getViewInfo();
			$text = elgg_echo('tidypics:views', [(int) $view_info['total']]);
			$options = [
				'name' => 'views',
				'text' => "<span>$text</span>",
				'href' => false,
				'priority' => 90,
			];
			$return[] = ElggMenuItem::factory($options);
		}

		$restrict_tagging = elgg_get_plugin_setting('restrict_tagging', 'tidypics');
		if (elgg_get_plugin_setting('tagging', 'tidypics') && elgg_is_logged_in() && (!$restrict_tagging || ($restrict_tagging && $entity->canEdit()))) {
			$options = [
				'name' => 'tagging',
				'text' => elgg_echo('tidypics:actiontag'),
				'href' => '#',
				'title' => elgg_echo('tidypics:tagthisphoto'),
				'rel' => 'photo-tagging',
				'priority' => 80,
			];
			$return[] = ElggMenuItem::factory($options);
		}
	}

	return $return;
}

/**
 * Register title urls for widgets (Widget Manager plugin)
 */
function tidypics_widget_urls($hook_name, $entity_type, $return_value, $params){
	$result = $return_value;
	$widget = $params["entity"];

	if (empty($result) && ($widget instanceof ElggWidget)) {
		$owner = $widget->getOwnerEntity();
		switch($widget->handler) {
			case "latest_photos":
				$result = "/photos/siteimagesowner/" . $owner->guid;
				break;
			case "album_view":
				$result = "/photos/owner/" . $owner->username;
				break;
			case "index_latest_photos":
				$result = "/photos/siteimagesall";
				break;
			case "index_latest_albums":
				$result = "/photos/all";
				break;
			case "groups_latest_photos":
				if($owner instanceof ElggGroup){
					$result = "photos/siteimagesgroup/{$owner->guid}";
				} else {
					$result = "/photos/siteimagesowner/" . $owner->guid;
				}
				break;
			case "groups_latest_albums":
				if($owner instanceof ElggGroup){
					$result = "photos/group/{$owner->guid}/all";
				} else {
					$result = "/photos/owner/" . $owner->username;
				}
				break;
		}
	}
	return $result;
}

/**
 * Add or remove a group's Tidypics widgets based on the corresponding group tools option
 */
function tidypics_tool_widgets_handler($hook, $type, $return_value, $params) {
	if (!empty($params) && is_array($params)) {
		$entity = elgg_extract("entity", $params);

		if ($entity instanceof ElggGroup) {
			if (!is_array($return_value)) {
				$return_value = [];
			}

			if (!isset($return_value["enable"])) {
				$return_value["enable"] = [];
			}
			if (!isset($return_value["disable"])) {
				$return_value["disable"] = [];
			}

			if ($entity->tp_images_enable == "yes") {
				$return_value["enable"][] = "groups_latest_photos";
			} else {
				$return_value["disable"][] = "groups_latest_photos";
			}
			if ($entity->photos_enable == "yes") {
				$return_value["enable"][] = "groups_latest_albums";
			} else {
				$return_value["disable"][] = "groups_latest_albums";
			}
		}
	}

	return $return_value;
}

/**
 * Override permissions for group albums
 *
 * 1. To write to a container (album) you must be able to write to the owner of the container (odd)
 * 2. We also need to change metadata on the album
 *
 * @param string $hook
 * @param string $type
 * @param bool   $result
 * @param array  $params
 * @return mixed
 */
function tidypics_group_permission_override($hook, $type, $result, $params) {
	$action_name_input = get_input('tidypics_action_name');
	if ($action_name_input == 'tidypics_photo_upload') {
		if (isset($params['container'])) {
			$album = $params['container'];
		} else {
			$album = $params['entity'];
		}

		if ($album instanceof TidypicsAlbum) {
			return $album->getContainerEntity()->canWriteToContainer();
		}
	}
}


/**
 *
 * Prepare a notification message about a new images added to an album
 *
 * Does not run if a new album without photos
 *
 * @param string                          $hook         Hook name
 * @param string                          $type         Hook type
 * @param Elgg_Notifications_Notification $notification The notification to prepare
 * @param array                           $params       Hook parameters
 * @return Elgg_Notifications_Notification (on Elgg 1.9); mixed (on Elgg 1.8)
 */
function tidypics_notify_message($hook, $type, $notification, $params) {

	$entity = $params['event']->getObject();

	if ($entity instanceof TidypicsAlbum) {

		$owner = $params['event']->getActor();
		$recipient = $params['recipient'];
		$language = $params['language'];
		$method = $params['method'];

		$descr = $entity->description;
		$title = $entity->getTitle();

		if ($type == 'notification:album_first:object:album') {
			$notification->subject = elgg_echo('tidypics:notify:subject_newalbum', [$entity->title], $language);
			$notification->body = elgg_echo('tidypics:notify:body_newalbum', [$owner->name, $title, $entity->getURL()], $language);
			$notification->summary = elgg_echo('tidypics:notify:summary_newalbum', [$entity->title], $language);

			return $notification;
		} else {
			$notification->subject = elgg_echo('tidypics:notify:subject_updatealbum', [$entity->title], $language);
			$notification->body = elgg_echo('tidypics:notify:body_updatealbum', [$owner->name, $title, $entity->getURL()], $language);
			$notification->summary = elgg_echo('tidypics:notify:summary_updatealbum', [$entity->title], $language);

			return $notification;
		}
	}
}

/**
 * Allows the flash uploader actions through walled garden since
 * they come without the session cookie
 */
function tidypics_walled_garden_override($hook, $type, $pages) {
	$pages[] = 'action/photos/image/ajax_upload';
	$pages[] = 'action/photos/image/ajax_upload_complete';
	return $pages;
}

/**
 * Return the album url of the album the tidypics_batch entitities belongs to
 */
function tidypics_batch_url_handler($hook, $type, $url, $params) {
	$batch = $params['entity'];
	if ($batch instanceof TidypicsBatch) {
		if (!$batch->getOwnerEntity()) {
			// default to a standard view if no owner.
			return false;
		}

		$album = get_entity($batch->container_guid);

		return $album->getURL();
	}
}

/**
 * custom layout for comments on tidypics river entries
 * Overriding generic_comment view
 */
function tidypics_comments_handler($hook, $type, $value, $params) {

	$result = $value;

	$action_type = $value['action_type'];
	if ($action_type != 'comment') {
		return;
	}

	$target_guid =  $value['target_guid'];
	if (!$target_guid) {
		return;
	}
	$target_entity = get_entity($target_guid);

	if ($target_entity instanceof Tidypics) {
		// update river entry attributes
		$result['view'] = 'river/object/comment/image';
	} else if ($target_entity instanceof TidypicsAlbum) {
		// update river entry attributes
		$result['view'] = 'river/object/comment/album';
	} else {
		return;
	}

	return $result;
}
