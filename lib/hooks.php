<?php
/**
 * Hooks for Tidypics
 */

/**
 * Add a menu item to an ownerblock
 */
function tidypics_owner_block_menu(\Elgg\Hook $hook) {
	$menu = $hook->getValue();
	$entity = $hook->getParam('entity');

	if ($entity instanceof ElggUser) {
		$url = "photos/siteimagesowner/{$entity->guid}";
		$item = new ElggMenuItem('photos', elgg_echo('photos'), $url);
		$menu[] = $item;
	} else {
		if ($entity->tp_images_enable != "no") {
			$url = "photos/siteimagesgroup/{$entity->guid}";
			$item = new ElggMenuItem('photos', elgg_echo('collection:object:image:group'), $url);
			$menu[] = $item;
		}
	}

	if ($entity instanceof ElggUser) {
		$url = "photos/owner/{$entity->username}";
		$item = new ElggMenuItem('photo_albums', elgg_echo('albums'), $url);
		$menu[] = $item;
	} else {
		if ($entity->photos_enable != "no") {
			$url = "photos/group/{$entity->guid}";
			$item = new ElggMenuItem('photo_albums', elgg_echo('collection:object:album:group'), $url);
			$menu[] = $item;
		}
	}

	return $menu;
}

/**
 * Subtype tidypics_batch is registered only to be included in activity page filter
 * but we don't want any results for this subtype returned in a search
 */
function tidypics_batch_no_search_results(\Elgg\Hook $hook) {
	return false;
}

/**
 * Add Tidypics links/info to entity menu
 */
function tidypics_entity_menu_setup(\Elgg\Hook $hook) {
	$menu = $hook->getValue();

	$entity = $hook->getParam('entity');

	if (!($entity instanceof TidypicsImage)) {
		return $menu;
	}

	$album = $entity->getContainerEntity();
	$cover_guid = $album->getCoverImageGuid();
	if ($cover_guid != $entity->getGUID() && $album->canEdit()) {
		$url = 'action/photos/album/set_cover'
			. '?image_guid=' . $entity->getGUID()
			. '&album_guid=' . $album->getGUID();

		$options = [
			'name' => 'set_cover',
			'href' => $url,
			'text' => elgg_echo('album:cover_link'),
			'is_action' => true,
			'is_trusted' => true,
			'confirm' => elgg_echo('album:cover'),
			'priority' => 80,
			'icon' => 'picture-o',
		];
		$menu[] = ElggMenuItem::factory($options);
	}

	return $menu;
}

/**
 * Add entries to social menu
 */
function tidypics_social_menu_setup(\Elgg\Hook $hook) {
	$menu = $hook->getValue();

	if (elgg_in_context('widgets')) {
		return $menu;
	}

	$entity = $hook->getParam('entity');

	if (!($entity instanceof TidypicsImage)) {
		return $menu;
	}
	
	if (elgg_get_plugin_setting('view_count', 'tidypics')) {
		$view_info = $entity->getViewInfo();
		$text = elgg_echo('tidypics:views', [(int) $view_info['total']]);
		$options = [
			'name' => 'views',
			'text' => elgg_format_element('span', [], $text),
			'href' => false,
			'priority' => 70,
		];
		$menu[] = ElggMenuItem::factory($options);
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
			'icon' => 'link',
		];
		$menu[] = ElggMenuItem::factory($options);
	}
	
	return $menu;
}

/**
 * Tabs for siteimages
 *
 * @param \Elgg\Hook $hook "register", "menu:filter:tidypics_siteimages_tabs"
 *
 * @return ElggMenuItem[]
 */
function tidypics_setup_tabs(\Elgg\Hook $hook) {
	$result = $hook->getValue();
	$filter_value = $hook->getParam('filter_value');

	$result['all'] = \ElggMenuItem::factory([
		'name' => 'tidypics_siteimages_all_tab',
		'text' => elgg_echo('all'),
		'href' => elgg_generate_url('collection:object:image:all'),
		'selected' => $filter_value === 'all',
		'priority' => 200,
	]);
	$result['mine'] =\ElggMenuItem::factory([
		'name' => 'tidypics_siteimages_mine_tab',
		'text' => elgg_echo('mine'),
		'href' => elgg_generate_url('collection:object:image:owner'),
		'selected' => $filter_value === 'mine',
		'priority' => 300,
	]);
	$result['friends'] = \ElggMenuItem::factory([
		'name' => 'tidypics_siteimages_friends_tab',
		'text' => elgg_echo('friends'),
		'href' => elgg_generate_url('collection:object:image:friends'),
		'selected' => $filter_value === 'friends',
		'priority' => 400,
	]);

	return $result;
}


/**
 * Register title urls for widgets (Widget Manager plugin)
 */
function tidypics_widget_urls(\Elgg\Hook $hook) {
	$result = $hook->getValue();
	$widget = $hook->getParam('entity');

	if (empty($result) && ($widget instanceof ElggWidget)) {
		$owner = $widget->getOwnerEntity();
		switch ($widget->handler) {
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
				if ($owner instanceof ElggGroup){
					$result = "photos/siteimagesgroup/{$owner->guid}";
				} else {
					$result = "/photos/siteimagesowner/" . $owner->guid;
				}
				break;
			case "groups_latest_albums":
				if ($owner instanceof ElggGroup){
					$result = "photos/group/{$owner->guid}";
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
function tidypics_tool_widgets_handler(\Elgg\Hook $hook) {
	$return_value = $hook->getValue();
	$entity = $hook->getParam('entity', false);

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

	return $return_value;
}

/**
 * Override permissions for group albums
 *
 * 1. To write to a container (album) you must be able to write to the owner of the container (odd)
 * 2. We also need to change metadata on the album
 *
 */
function tidypics_group_permission_override(\Elgg\Hook $hook) {
	$params = $hook->getParams();

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
 */
function tidypics_notify_message(\Elgg\Hook $hook) {
	$type = $hook->getType();
	$notification = $hook->getValue();
	$params = $hook->getParams();

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
function tidypics_walled_garden_override(\Elgg\Hook $hook) {
	$pages = $hook->getValue();

	$pages[] = 'action/photos/image/ajax_upload';
	$pages[] = 'action/photos/image/ajax_upload_complete';

	return $pages;
}

/**
 * Return the album url of the album the tidypics_batch entitities belongs to
 */
function tidypics_batch_url_handler(\Elgg\Hook $hook) {
	$batch = $hook->getParam('entity');

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
function tidypics_comments_handler(\Elgg\Hook $hook) {
	$result = $hook->getValue();

	$action_type = $result['action_type'];
	if ($action_type != 'comment') {
		return;
	}

	$target_guid =  $result['target_guid'];
	if (!$target_guid) {
		return;
	}
	$target_entity = get_entity($target_guid);

	if ($target_entity instanceof TidypicsImage) {
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
