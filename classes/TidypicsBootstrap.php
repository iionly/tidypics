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

		// Register the JavaScript libs
		elgg_define_js('jquery.plupload-tp', [
			'deps' => ['elgg', 'jquery'],
			'src' => elgg_get_simplecache_url('tidypics/js/plupload/plupload.full.min.js'),
			'exports' => 'jQuery.plupload',
		]);
		elgg_define_js('jquery.plupload.ui-tp', [
			'deps' => ['tidypics-jquery-ui/core', 'tidypics-jquery-ui/widget', 'tidypics-jquery-ui/widgets/button', 'tidypics-jquery-ui/widgets/progressbar', 'tidypics-jquery-ui/widgets/sortable', 'jquery.plupload-tp'],
			'src' => elgg_get_simplecache_url('tidypics/js/plupload/jquery.ui.plupload/jquery.ui.plupload.min.js'),
		]);
		elgg_define_js('tidypics.imgareaselect', [
			'deps' => ['elgg', 'jquery'],
			'src' => elgg_get_simplecache_url('tidypics/js/jquery-imgareaselect.js'),
		]);
	}

	public function activate() {
		// sets $version based on code
		require_once elgg_get_plugins_path() . "tidypics/version.php";

		$local_version = elgg_get_plugin_setting('version', 'tidypics');
		if ($local_version === null) {
			// set initial version for new install
			$plugin = elgg_get_plugin_from_id('tidypics');
			$plugin->setSetting('version', $version);
		}
	}
}
