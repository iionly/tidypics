<?php
/**
 * Photo Gallery plugin
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

require_once(dirname(__FILE__) . '/lib/tidypics.php');
require_once(dirname(__FILE__) . '/lib/hooks.php');
require_once(dirname(__FILE__) . '/lib/exif.php');
require_once(dirname(__FILE__) . '/lib/watermark.php');
require_once(dirname(__FILE__) . '/lib/resize.php');
require_once(dirname(__FILE__) . '/lib/upload.php');

elgg_register_event_handler('init', 'system', 'tidypics_init');

/**
 * Tidypics plugin initialization
 */
function tidypics_init() {
	// Register an ajax view that allows selection of album to upload images to
	elgg_register_ajax_view('photos/selectalbum');

	// Register an ajax view for the broken images cleanup routine
	elgg_register_ajax_view('photos/broken_images_delete_log');

	// Register an ajax view for the Galleria slideshow
	elgg_register_ajax_view('photos/galleria');

	// Set up site menu
	$site_menu_links_to = elgg_get_plugin_setting('site_menu_link', 'tidypics');
	if ($site_menu_links_to == 'albums') {
		elgg_register_menu_item('site', [
			'name' => 'photos',
			'href' => 'photos/all',
			'text' => elgg_echo('photos'),
		]);
	} else {
		elgg_register_menu_item('site', [
			'name' => 'photos',
			'href' => 'photos/siteimagesall',
			'text' => elgg_echo('photos'),
		]);
	}

	// Register a page handler so we can have nice URLs
	elgg_register_page_handler('photos', 'tidypics_page_handler');

	// Extend CSS
	elgg_extend_view('css/elgg', 'photos/css');
	elgg_extend_view('css/admin', 'photos/css');

	// Register the JavaScript libs
	elgg_register_js('jquery.plupload-tp', elgg_get_simplecache_url('tidypics/js/plupload/plupload.full.min.js'), 'footer');
	elgg_register_js('jquery.plupload.ui-tp', elgg_get_simplecache_url('tidypics/js/plupload/jquery.ui.plupload/jquery.ui.plupload.min.js'), 'footer');
	$plupload_language = tidypics_get_plugload_language();
	elgg_register_js('jquery.plupload.ui.lang-tp', elgg_get_simplecache_url('tidypics/js/plupload/i18n/' . $plupload_language . '.js'), 'footer');
	elgg_register_css('jquery.plupload.jqueryui-theme', elgg_get_simplecache_url('tidypics/css/jqueryui-theme.css'));
	elgg_register_css('jquery.plupload.ui', elgg_get_simplecache_url('tidypics/css/plupload/css/jquery.ui.plupload.css'));

	// Register for search
	elgg_register_entity_type('object', TidypicsImage::SUBTYPE);
	elgg_register_entity_type('object', TidypicsAlbum::SUBTYPE);
	elgg_register_entity_type('object', TidypicsBatch::SUBTYPE);

	// RSS extensions for embedded media
	elgg_extend_view('extensions/xmlns', 'extensions/photos/xmlns');

	// Register group options
	add_group_tool_option('photos', elgg_echo('tidypics:enablephotos'), true);
	elgg_extend_view('groups/tool_latest', 'photos/group_module');
	add_group_tool_option('tp_images', elgg_echo('tidypics:enable_group_images'), true);
	elgg_extend_view('groups/tool_latest', 'photos/group_tp_images_module');

	// Register widgets
	elgg_register_widget_type('album_view', elgg_echo("tidypics:widget:albums"), elgg_echo("tidypics:widget:album_descr"), ['profile']);
	elgg_register_widget_type('latest_photos', elgg_echo("tidypics:widget:latest"), elgg_echo("tidypics:widget:latest_descr"), ['profile']);
	
	// Add index widgets for Widget Manager plugin
	elgg_register_widget_type('index_latest_photos', elgg_echo("tidypics:mostrecent"), elgg_echo('tidypics:mostrecent:description'), ['index']);
	elgg_register_widget_type('index_latest_albums', elgg_echo("tidypics:albums_mostrecent"), elgg_echo('tidypics:albums_mostrecent:description'), ['index']);

	// Add groups widgets for Widget Manager plugin
	elgg_register_widget_type('groups_latest_photos', elgg_echo("tidypics:mostrecent"), elgg_echo('tidypics:mostrecent:description'), ['groups']);
	elgg_register_widget_type('groups_latest_albums', elgg_echo("tidypics:albums_mostrecent"), elgg_echo('tidypics:albums_mostrecent:description'), ['groups']);
	
	// Add photos link to owner block/hover menus
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'tidypics_owner_block_menu');

	// Override search for tidypics_batch subtype to not return any results
	elgg_register_plugin_hook_handler('search', 'object:tidypics_batch', 'tidypics_batch_no_search_results');

	// Register for the entity menu
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'tidypics_entity_menu_setup');
	
	// Register title urls for widgets (Widget Manager plugin)
	elgg_register_plugin_hook_handler("entity:url", "object", "tidypics_widget_urls");

	// Handle the availability of the Tidypics group widgets (Widget Manager plugin)
	elgg_register_plugin_hook_handler("group_tool_widgets", "widget_manager", "tidypics_tool_widgets_handler");

	// Allow group members add photos to group albums
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'tidypics_group_permission_override');
	elgg_register_plugin_hook_handler('permissions_check:metadata', 'object', 'tidypics_group_permission_override');

	// Notifications
	elgg_register_notification_event('object', TidypicsAlbum::SUBTYPE, ['album_first', 'album_more']);
	elgg_register_plugin_hook_handler('prepare', 'notification:album_first:object:album', 'tidypics_notify_message');
	elgg_register_plugin_hook_handler('prepare', 'notification:album_more:object:album', 'tidypics_notify_message');

	// Allow people in a walled garden to use flash uploader
	elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'tidypics_walled_garden_override');

	// Override the default url to view a tidypics_batch object
	elgg_register_plugin_hook_handler('entity:url', 'object', 'tidypics_batch_url_handler');

	// Custom layout for comments on tidypics river entries
	elgg_register_plugin_hook_handler('creating', 'river', 'tidypics_comments_handler');

	// Allow for liking of albums and images
	elgg_register_plugin_hook_handler('likes:is_likable', 'object:album', 'Elgg\Values::getTrue');
	elgg_register_plugin_hook_handler('likes:is_likable', 'object:image', 'Elgg\Values::getTrue');

	// Register actions
	elgg_register_action("tidypics/settings/save", dirname(__FILE__) . '/actions/tidypics/settings/save.php');
	elgg_register_action("photos/delete", dirname(__FILE__) . '/actions/photos/delete.php');
	elgg_register_action("photos/album/save", dirname(__FILE__) . '/actions/photos/album/save.php');
	elgg_register_action("photos/album/sort", dirname(__FILE__) . '/actions/photos/album/sort.php');
	elgg_register_action("photos/album/set_cover", dirname(__FILE__) . '/actions/photos/album/set_cover.php');
	elgg_register_action("photos/image/upload", dirname(__FILE__) . '/actions/photos/image/upload.php');
	elgg_register_action("photos/image/save", dirname(__FILE__) . '/actions/photos/image/save.php');
	elgg_register_action("photos/image/ajax_upload", dirname(__FILE__) . '/actions/photos/image/ajax_upload.php', 'logged_in');
	elgg_register_action("photos/image/ajax_upload_complete", dirname(__FILE__) . '/actions/photos/image/ajax_upload_complete.php', 'logged_in');
	elgg_register_action("photos/image/tag", dirname(__FILE__) . '/actions/photos/image/tag.php');
	elgg_register_action("photos/image/untag", dirname(__FILE__) . '/actions/photos/image/untag.php');
	elgg_register_action('photos/image/selectalbum', dirname(__FILE__) . '/actions/photos/image/selectalbum.php');
	elgg_register_action("photos/batch/edit", dirname(__FILE__) . '/actions/photos/batch/edit.php');
	elgg_register_action("photos/admin/create_thumbnail", dirname(__FILE__) . '/actions/photos/admin/create_thumbnail.php', 'admin');
	elgg_register_action("photos/admin/resize_thumbnails", dirname(__FILE__) . '/actions/photos/admin/resize_thumbnails.php', 'admin');
	elgg_register_action("photos/admin/delete_image", dirname(__FILE__) . '/actions/photos/admin/delete_image.php', 'admin');
	elgg_register_action("photos/admin/upgrade", dirname(__FILE__) . '/actions/hotos/admin/upgrade.php', 'admin');
	elgg_register_action("photos/admin/broken_images", dirname(__FILE__) . '/actions/photos/admin/broken_images.php', 'admin');
	elgg_register_action("photos/admin/imtest", dirname(__FILE__) . '/actions/photos/admin/imtest.php', 'admin');
}

/**
 * Tidypics page handler
 *
 * @param array $page Array of url segments
 * @return bool
 */
function tidypics_page_handler($page) {

	if (!isset($page[0])) {
		return false;
	}

	elgg_require_js('tidypics/tidypics');

	$resource_vars = [];
	switch ($page[0]) {
		case "siteimagesall":
			echo elgg_view_resource('tidypics/lists/siteimagesall');
			break;

		case "siteimagesowner":
			if (isset($page[1])) {
				$resource_vars['guid'] = (int) $page[1];
			}
			echo elgg_view_resource('tidypics/lists/siteimagesowner', $resource_vars);
			break;

		case "siteimagesfriends":
			echo elgg_view_resource('tidypics/lists/siteimagesfriends');
			break;

		case "siteimagesgroup":
			if (isset($page[1])) {
				$resource_vars['guid'] = (int) $page[1];
			}
			echo elgg_view_resource('tidypics/lists/siteimagesgroup', $resource_vars);
			break;

		case "all": // all site albums
		case "world":
			echo elgg_view_resource('tidypics/photos/all');
			break;

		case "owned":  // albums owned by container entity
		case "owner":
			echo elgg_view_resource('tidypics/photos/owner');
			break;

		case "friends": // albums of friends
			echo elgg_view_resource('tidypics/photos/friends');
			break;

		case "group": // albums of a group
			echo elgg_view_resource('tidypics/photos/owner');
			break;

		case "album": // view an album individually
			$resource_vars['guid'] = (int) $page[1];
			echo elgg_view_resource('tidypics/photos/album/view', $resource_vars);
			break;

		case "new":  // create new album
		case "add":
			$resource_vars['guid'] = (int) $page[1];
			echo elgg_view_resource('tidypics/photos/album/add', $resource_vars);
			break;
			
		case "edit": //edit image or album
			$resource_vars['guid'] = (int) $page[1];
			$entity = get_entity($resource_vars['guid']);
			if (!$entity) {
				return false;
			}
			switch ($entity->getSubtype()) {
				case TidypicsAlbum::SUBTYPE:
					echo elgg_view_resource('tidypics/photos/album/edit', $resource_vars);
					break;
				case TidypicsImage::SUBTYPE:
					echo elgg_view_resource('tidypics/photos/image/edit', $resource_vars);
					break;
				case TidypicsBatch::SUBTYPE:
					echo elgg_view_resource('tidypics/photos/batch/edit', $resource_vars);
					break;
				default:
					return false;
			}
			break;

		case "sort": // sort a photo album
			$resource_vars['guid'] = (int) $page[1];
			echo elgg_view_resource('tidypics/photos/album/sort', $resource_vars);
			break;

		case "image": //view an image
		case "view":
			$resource_vars['guid'] = (int) $page[1];
			echo elgg_view_resource('tidypics/photos/image/view', $resource_vars);
			break;

		case "thumbnail": // tidypics thumbnail
			$resource_vars['guid'] = (int) $page[1];
			$resource_vars['size'] = elgg_extract(2, $page, 'small');
			echo elgg_view_resource('tidypics/photos/image/thumbnail', $resource_vars);
			break;

		case "upload": // upload images to album
			if (elgg_get_plugin_setting('uploader', 'tidypics')) {
				$default_uploader = 'ajax';
			} else {
				$default_uploader = 'basic';
			}
			$resource_vars['guid'] = (int) $page[1];
			$resource_vars['uploader'] = elgg_extract(2, $page, $default_uploader);
			echo elgg_view_resource('tidypics/photos/image/upload', $resource_vars);
			break;

		case "download": // download an image
			$resource_vars['guid'] = (int) $page[1];
			$resource_vars['disposition'] = elgg_extract(2, $page, 'attachment');
			echo elgg_view_resource('tidypics/photos/image/download', $resource_vars);
			break;

		case "tagged": // all photos tagged with logged in user
			echo elgg_view_resource('tidypics/photos/tagged');
			break;

		case "riverpopup": // show image in lightbox on activity page
			if (isset($page[1])) {
				$resource_vars['guid'] = (int) $page[1];
			}
			if (elgg_is_xhr()) {
				echo elgg_view_resource('tidypics/photos/riverpopup', $resource_vars);
			} else {
				return elgg_redirect_response('photos/image/' . $resource_vars['guid']);
			}
			break;

		case "mostviewed": // images with the most views
			if (isset($page[1])) {
				$resource_vars['username'] = $page[1];
			}
			echo elgg_view_resource('tidypics/lists/mostviewedimages', $resource_vars);
			break;

		case "mostviewedtoday":
			if (isset($page[1])) {
				$resource_vars['username'] = $page[1];
			}
			echo elgg_view_resource('tidypics/lists/mostviewedimagestoday', $resource_vars);
			break;

		case "mostviewedthismonth":
			if (isset($page[1])) {
				$resource_vars['username'] = $page[1];
			}
			echo elgg_view_resource('tidypics/lists/mostviewedimagesthismonth', $resource_vars);
			break;

		case "mostviewedlastmonth":
			if (isset($page[1])) {
				$resource_vars['username'] = $page[1];
			}
			echo elgg_view_resource('tidypics/lists/mostviewedimageslastmonth', $resource_vars);
			break;

		case "mostviewedthisyear":
			if (isset($page[1])) {
				$resource_vars['username'] = $page[1];
			}
			echo elgg_view_resource('tidypics/lists/mostviewedimagesthisyear', $resource_vars);
			break;

		case "mostcommented":
			if (isset($page[1])) {
				$resource_vars['username'] = $page[1];
			}
			echo elgg_view_resource('tidypics/lists/mostcommentedimages', $resource_vars);
			break;

		case "mostcommentedtoday":
			if (isset($page[1])) {
				$resource_vars['username'] = $page[1];
			}
			echo elgg_view_resource('tidypics/lists/mostcommentedimagestoday', $resource_vars);
			break;

		case "mostcommentedthismonth":
			if (isset($page[1])) {
				$resource_vars['username'] = $page[1];
			}
			echo elgg_view_resource('tidypics/lists/mostcommentedimagesthismonth', $resource_vars);
			break;

		case "mostcommentedlastmonth":
			if (isset($page[1])) {
				$resource_vars['username'] = $page[1];
			}
			echo elgg_view_resource('tidypics/lists/mostcommentedimageslastmonth', $resource_vars);
			break;

		case "mostcommentedthisyear":
			if (isset($page[1])) {
				$resource_vars['username'] = $page[1];
			}
			echo elgg_view_resource('tidypics/lists/mostcommentedimagesthisyear', $resource_vars);
			break;

		case "recentlyviewed":
			if (isset($page[1])) {
				$resource_vars['username'] = $page[1];
			}
			echo elgg_view_resource('tidypics/lists/recentlyviewed', $resource_vars);
			break;

		case "recentlycommented":
			if (isset($page[1])) {
				$resource_vars['username'] = $page[1];
			}
			echo elgg_view_resource('tidypics/lists/recentlycommented', $resource_vars);
			break;

		case "recentvotes":
			if(elgg_is_active_plugin('elggx_fivestar')) {
				if (isset($page[1])) {
					$resource_vars['username'] = $page[1];
				}
				echo elgg_view_resource('tidypics/lists/recentvotes', $resource_vars);
				break;
			} else {
				return false;
			}

		case "highestrated":
			if(elgg_is_active_plugin('elggx_fivestar')) {
				if (isset($page[1])) {
					$resource_vars['username'] = $page[1];
				}
				echo elgg_view_resource('tidypics/lists/highestrated', $resource_vars);
				break;
			} else {
				return false;
			}

		case "highestvotecount":
			if(elgg_is_active_plugin('elggx_fivestar')) {
				if (isset($page[1])) {
					$resource_vars['username'] = $page[1];
				}
				echo elgg_view_resource('tidypics/lists/highestvotecount', $resource_vars);
				break;
			} else {
				return false;
			}

		default:
			return false;
	}

	return true;
}
