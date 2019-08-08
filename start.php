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
			'icon' => 'file-image-o',
		]);
	} else {
		elgg_register_menu_item('site', [
			'name' => 'photos',
			'href' => 'photos/siteimagesall',
			'text' => elgg_echo('photos'),
			'icon' => 'file-image-o',
		]);
	}

	// Extend CSS
	elgg_extend_view('css/elgg', 'photos/css');
	elgg_extend_view('css/admin', 'photos/css');

	elgg_require_js('tidypics/tidypics');

	// RSS extensions for embedded media
	elgg_extend_view('extensions/xmlns', 'extensions/photos/xmlns');

	// Register group options
	elgg()->group_tools->register('photos', [
		'default_on' => true,
		'label' => elgg_echo('tidypics:enablephotos'),
	]);
	elgg()->group_tools->register('tp_images', [
		'default_on' => true,
		'label' => elgg_echo('tidypics:enable_group_images'),
	]);

	// Register widgets
	elgg_register_widget_type([
		'id' => 'album_view',
		'context' => ['profile'],
	]);
	elgg_register_widget_type([
		'id' => 'latest_photos',
		'context' => ['profile'],
	]);

	// Add index widgets for Widget Manager plugin
	elgg_register_widget_type([
		'id' => 'index_latest_photos',
		'context' => ['index'],
	]);
	// elgg_register_widget_type('', elgg_echo("tidypics:mostrecent"), elgg_echo('tidypics:mostrecent:description'), ['']);
	elgg_register_widget_type([
		'id' => 'index_latest_albums',
		'context' => ['index'],
	]);
	// elgg_register_widget_type('', elgg_echo("tidypics:albums_mostrecent"), elgg_echo('tidypics:albums_mostrecent:description'), ['index']);

	// Add groups widgets for Widget Manager plugin
	elgg_register_widget_type([
		'id' => 'groups_latest_photos',
		'context' => ['groups'],
	]);
	// elgg_register_widget_type('', elgg_echo("tidypics:mostrecent"), elgg_echo('tidypics:mostrecent:description'), ['']);
	elgg_register_widget_type([
		'id' => 'groups_latest_albums',
		'context' => ['groups'],
	]);
	// elgg_register_widget_type('', elgg_echo("tidypics:albums_mostrecent"), elgg_echo('tidypics:albums_mostrecent:description'), ['groups']);
	
	// Add photos link to owner block/hover menus
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'tidypics_owner_block_menu');

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

}
