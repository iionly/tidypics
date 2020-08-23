<?php
/**
 * Elgg Tidypics library of common functions
 *
 * @package TidypicsCommon
 */

/**
 * Get images for display on front page
 *
 * @param int number of images
 * @param array (optional) array of owner guids
 * @param string (optional) context of view to display
 * @return string of html for display
 */
function tp_get_latest_photos($num_images, array $owner_guids = null, $context = 'front') {
	$prev_context = elgg_get_context();
	elgg_set_context($context);
	$image_html = elgg_list_entities([
		'type' => 'object',
		'subtype' => TidypicsImage::SUBTYPE,
		'owner_guids' => $owner_guids,
		'limit' => $num_images,
		'full_view' => false,
		'list_type_toggle' => false,
		'list_type' => 'gallery',
		'pagination' => false,
		'gallery_class' => 'tidypics-gallery-widget',
	]);
	elgg_set_context($prev_context);
	return $image_html;
}

/**
 * Get albums for display on front page
 *
 * @param int number of albums
 * @param array (optional) array of container_guids
 * @param string (optional) context of view to display
 * @return string of html for display
 */
function tp_get_latest_albums($num_albums, array $container_guids = null, $context = 'front') {
	$prev_context = elgg_get_context();
	elgg_set_context($context);
	$image_html = elgg_list_entities([
		'type' => 'object',
		'subtype' => TidypicsAlbum::SUBTYPE,
		'container_guids' => $container_guids,
		'limit' => $num_albums,
		'full_view' => false,
		'pagination' => false,
	]);
	elgg_set_context($prev_context);
	return $image_html;
}


/**
 * Get image directory path
 *
 * Each album gets a subdirectory based on its container id
 *
 * @return string	path to image directory
 */
function tp_get_img_dir($album_guid) {
	$file = new ElggFile();
	$file->setFilename("image/$album_guid");
	return $file->getFilenameOnFilestore($file);
}

/**
 * Prepare vars for a form, pulling from an entity or sticky forms.
 *
 * @param type $entity
 * @return type
 */
function tidypics_prepare_form_vars($entity = null) {
	// input names => defaults
	$values = [
		'title' => '',
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $entity,
	];

	if ($entity) {
		foreach (array_keys($values) as $field) {
			if (isset($entity->$field)) {
				$values[$field] = $entity->$field;
			}
		}
	}

	if (elgg_is_sticky_form('tidypics')) {
		$sticky_values = elgg_get_sticky_values('tidypics');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('tidypics');

	return $values;
}

/**
 * Returns available image libraries.
 *
 * @return string
 */
function tidypics_get_image_libraries() {
	$options = [];
	if (extension_loaded('gd')) {
		$options['GD'] = 'GD';
	}

	if (extension_loaded('imagick')) {
		$options['ImageMagickPHP'] = 'imagick PHP extension';
	}

	$disablefunc = explode(',', ini_get('disable_functions'));
	if (is_callable('exec') && !in_array('exec', $disablefunc)) {
		$options['ImageMagick'] = 'ImageMagick executable';
	}

	return $options;
}

/**
 * Are there upgrade scripts to be run?
 *
 * @return bool
 */
function tidypics_is_upgrade_available() {
	// sets $version based on code
	require_once elgg_get_plugins_path() . "tidypics/version.php";

	$local_version = elgg_get_plugin_setting('version', 'tidypics');

	if ($local_version === false) {
		elgg_set_plugin_setting('version', $version, 'tidypics');
		$local_version = $version;
	}

	if ($local_version == $version) {
		return false;
	} else {
		return true;
	}
}

/**
 * This lists the photos in an album as sorted by metadata
 *
 * @todo this only supports a single album. The only case for use a
 * procedural function like this instead of TidypicsAlbum::viewImgaes() is to
 * fetch images across albums as a helper to elgg_get_entities().
 * This should function be deprecated or fixed to work across albums.
 *
 * @param array $options
 * @return string
 */
function tidypics_list_photos(array $options = []) {
	elgg_register_rss_link();

	$defaults = [
		'offset' => (int) max(get_input('offset', 0), 0),
		'limit' => (int) max(get_input('limit', 10), 0),
		'full_view' => true,
		'list_type_toggle' => false,
		'pagination' => true,
	];

	$options = array_merge($defaults, $options);

	$options['count'] = true;
	$count = elgg_get_entities($options);

	$album = get_entity($options['container_guid']);
	if ($album) {
		$guids = $album->getImageList();
		// need to pass all the guids and handle the limit / offset in sql
		// to avoid problems with the navigation
		//$guids = array_slice($guids, $options['offset'], $options['limit']);
		$options['guids'] = $guids;
		unset($options['container_guid']);
	}
	$options['count'] = false;
	$entities = elgg_get_entities($options);

	$keys = [];
	foreach ($entities as $entity) {
		$keys[] = $entity->guid;
	}

	$entities = array_combine($keys, $entities);

	$sorted_entities = [];
	foreach ($guids as $guid) {
		if (isset($entities[$guid])) {
			$sorted_entities[] = $entities[$guid];
		}
	}

	// for this function count means the total number of entities
	// and is required for pagination
	$options['count'] = $count;

	return elgg_view_entity_list($sorted_entities, $options);
}

/**
 * Returns just a guid from a database $row. Used in elgg_get_entities()'s callback.
 *
 * @param stdClass $row
 * @return type
 */
function tp_guid_callback($row) {
	return ($row->guid) ? $row->guid : false;
}


/**
 * the functions below replace broken core functions or add functions
 * that could/should exist in the core
 */

/**
 * Is the request from a known browser
 *
 * @return true/false
 */
function tp_is_person() {
	$known = ['mozilla', 'chrome', 'firefox', 'webkit', 'gecko', 'edgehtml', 'msie', 'safari', 'opera', 'netscape', 'konqueror'];

	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);

	foreach ($known as $browser) {
		if (strpos($agent, $browser) !== false) {
			return true;
		}
	}

	return false;
}

/**
 * Check if there are any albums a user can add photos to
 * or if the user can create a new album
 *
 * @param \ElggUser   $user      User (defaults to logged in user)
 * @param \ElggEntity $container Album container (defaults to page owner)
 * @return bool
 */
function tidypics_can_add_new_photos(\ElggUser $user = null, \ElggEntity $container = null) {
	if (!isset($user)) {
		$user = elgg_get_logged_in_user_entity();
	}
	if (!($user instanceof ElggUser)) {
		return false;
	}

	if (!isset($container)) {
		$container = elgg_get_page_owner_entity();
	}
	if (!($container instanceof ElggEntity)) {
		return false;
	}

	if ($container->canWriteToContainer($user->guid, 'object', TidypicsAlbum::SUBTYPE)) {
		return true;
	}

	$albums = elgg_get_entities([
		'type' => 'object',
		'subtype' => TidypicsAlbum::SUBTYPE,
		'container_guid' => $container->guid,
		'limit' => false,
		'batch' => true,
	]);
	foreach ($albums as $album) {
		if ($album->canWriteToContainer(0, 'object', TidypicsImage::SUBTYPE)) {
			return true;
		}
	}
	return false;
}

function tidypics_get_plugload_language() {
	if ($current_language = get_current_language()) {
		$path = elgg_get_plugins_path() . "tidypics/vendors/plupload/js/i18n";
		if (file_exists("$path/$current_language.js")) {
			return $current_language;
		}
	}

	return 'en';
}

function tidypics_get_last_log_line($filename) {
	$line = false;
	$f = false;
	if (file_exists($filename)) {
		$f = @fopen($filename, 'r');
	}

	if ($f === false) {
		return false;
	} else {
		$cursor = -1;

		fseek($f, $cursor, SEEK_END);
		$char = fgetc($f);

		/**
		 * Trim trailing newline chars of the file
		 */
		while ($char === "\n" || $char === "\r") {
			fseek($f, $cursor--, SEEK_END);
			$char = fgetc($f);
		}

		/**
		 * Read until the start of file or first newline char
		 */
		while ($char !== false && $char !== "\n" && $char !== "\r") {
			/**
			 * Prepend the new char
			 */
			$line = $char . $line;
			fseek($f, $cursor--, SEEK_END);
			$char = fgetc($f);
		}
	}

	return $line;
}

function tidypics_get_log_location($time) {
	return elgg_get_config('dataroot') . 'tidypics_log' . '/' . $time . '.txt';
}

// Return data array for Galleria slideshow
function tidypics_slideshow_json_data($images) {
	$img_array = [];
	if (is_array($images) && sizeof($images) > 0) {
		foreach ($images as $image) {
			$image->addView();
			$img_array[] = [
				'thumb' => elgg_normalize_url('/photos/thumbnail/' . $image->guid . '/small'),
				'image' => elgg_normalize_url('/photos/thumbnail/' . $image->guid . '/large'),
// 				'big' => elgg_normalize_url('/photos/thumbnail/' . $image->guid . '/master'),
				'title' => $image->getTitle(),
				'description' => ($image->description) ? $image->description : '',
				'link' => elgg_normalize_url('/photos/image/' . $image->guid),
			];
		}
	}
	return json_encode($img_array);
}
