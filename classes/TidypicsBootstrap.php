<?php

use Elgg\DefaultPluginBootstrap;

class TidypicsBootstrap extends DefaultPluginBootstrap {

	public function init() {
		// Register an ajax view that allows selection of album to upload images to
		elgg_register_ajax_view('photos/selectalbum');

		// Register an ajax view for the broken images cleanup routine
		elgg_register_ajax_view('photos/broken_images_delete_log');

		// Register an ajax view for the Galleria slideshow
		elgg_register_ajax_view('photos/galleria');

		// Register an ajax view for the River image popups
		elgg_register_ajax_view('photos/riverpopup');

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

		// Register the JavaScript libs
		elgg_define_js('jquery.plupload-tp', [
			'deps' => ['jquery'],
			'src' => elgg_get_simplecache_url('tidypics/js/plupload/plupload.full.min.js'),
			'exports' => 'jQuery.plupload',
		]);
		elgg_define_js('jquery.plupload.ui-tp', [
			'deps' => ['jquery-ui', 'jquery.plupload-tp'],
			'src' => elgg_get_simplecache_url('tidypics/js/plupload/jquery.ui.plupload/jquery.ui.plupload.min.js'),
		]);
		$plupload_language = tidypics_get_plugload_language();
		elgg_define_js('jquery.plupload.ui.lang-tp', [
			'deps' => ['jquery.plupload-tp'],
			'src' => elgg_get_simplecache_url('tidypics/js/plupload/i18n/' . $plupload_language . '.js'),
		]);

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

		// Add photos link to owner block/hover menus
		elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'tidypics_owner_block_menu');

		// Override search for tidypics_batch subtype to not return any results
		elgg_register_plugin_hook_handler('search', 'object:tidypics_batch', 'tidypics_batch_no_search_results');

		// Register for the entity menu
		elgg_register_plugin_hook_handler('register', 'menu:entity', 'tidypics_entity_menu_setup');
		
		// Register for the social menu
		elgg_register_plugin_hook_handler('register', 'menu:social', 'tidypics_social_menu_setup');

		// Tabs for siteimages
		elgg_register_plugin_hook_handler('register', 'menu:filter:tidypics_siteimages_tabs', 'tidypics_setup_tabs');
		
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

	public function activate() {
		// sets $version based on code
		require_once elgg_get_plugins_path() . "tidypics/version.php";

		$local_version = elgg_get_plugin_setting('version', 'tidypics');
		if ($local_version === null) {
			// set initial version for new install
			elgg_set_plugin_setting('version', $version, 'tidypics');
		}
	}
}
