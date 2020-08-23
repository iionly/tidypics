<?php

require_once(dirname(__FILE__) . '/lib/tidypics.php');
require_once(dirname(__FILE__) . '/lib/hooks.php');
require_once(dirname(__FILE__) . '/lib/exif.php');
require_once(dirname(__FILE__) . '/lib/watermark.php');
require_once(dirname(__FILE__) . '/lib/resize.php');
require_once(dirname(__FILE__) . '/lib/upload.php');

return [
	'bootstrap' => \TidypicsBootstrap::class,
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'album',
			'class' => 'TidypicsAlbum',
			'searchable' => true,
		],
		[
			'type' => 'object',
			'subtype' => 'image',
			'class' => 'TidypicsImage',
			'searchable' => true,
		],
		[
			'type' => 'object',
			'subtype' => 'tidypics_batch',
			'class' => 'TidypicsBatch',
			'searchable' => true,
		],
	],
	'actions' => [
		'tidypics/settings/save' => [],
		'photos/delete' => [
			'access' => 'logged_in',
		],
		'photos/album/save' => [
			'access' => 'logged_in',
		],
		'photos/album/sort' => [
			'access' => 'logged_in',
		],
		'photos/album/set_cover' => [
			'access' => 'logged_in',
		],
		'photos/image/upload' => [
			'access' => 'logged_in',
		],
		'photos/image/save' => [
			'access' => 'logged_in',
		],
		'photos/image/ajax_upload' => [
			'access' => 'logged_in',
		],
		'photos/image/ajax_upload_complete' => [
			'access' => 'logged_in',
		],
		'photos/image/tag' => [
			'access' => 'logged_in',
		],
		'photos/image/untag' => [
			'access' => 'logged_in',
		],
		'photos/image/selectalbum' => [
			'access' => 'logged_in',
		],
		'photos/batch/edit' => [
			'access' => 'logged_in',
		],
		'photos/admin/create_thumbnail' => [
			'access' => 'admin',
		],
		'photos/admin/resize_thumbnails' => [
			'access' => 'admin',
		],
		'photos/admin/delete_image' => [
			'access' => 'admin',
		],
		'photos/admin/upgrade' => [
			'access' => 'admin',
		],
		'photos/admin/broken_images' => [
			'access' => 'admin',
		],
		'photos/admin/imtest' => [
			'access' => 'admin',
		],
	],
	'settings' => [
		'tagging' => false,
		'restrict_tagging' => false,
		'view_count' => true,
		'uploader' => true,
		'exif' => false,
		'download_link' => true,
		'slideshow' => false,
		'extended_sidebar_menu' => true,

		'quota' => 0,
		'max_uploads' => 10,
		'maxfilesize' => 5,
		'image_lib' => 'GD',
		'im_path' => '/usr/bin/',

		'img_river_view' => 'batch',
		'album_river_view' => 'cover',
		'river_album_number' => 7,
		'river_comments_thumbnails' => 'none',
		'river_thumbnails_size' => 'tiny',
		'river_tags' => 'show',
		'site_menu_link' => 'photos',

		'notify_interval' => 60 * 60 * 24,

		'thumbnail_optimization' => 'simple',
		'client_resizing' => false,
		'remove_exif' => false,
		'client_image_width' => 2000,
		'client_image_height' => 2000,

		'image_sizes' => serialize([
			'tiny_image_width' => 60,
			'tiny_image_height' => 60,
			'tiny_image_square' => true,
			'small_image_width' => 153,
			'small_image_height' => 153,
			'small_image_square' => true,
			'large_image_width' => 600,
			'large_image_height' => 600,
			'large_image_square' => false,
		]),
	],
	'routes' => [
		'collection:object:album:owner' => [
			'path' => '/photos/owner/{username?}',
			'resource' => 'tidypics/photos/owner',
		],
		'collection:object:album:owned' => [
			'path' => '/photos/owned/{username?}',
			'resource' => 'tidypics/photos/owner',
		],
		'collection:object:album:friends' => [
			'path' => '/photos/friends/{username?}/{guid?}',
			'resource' => 'tidypics/photos/friends',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'collection:object:album:group' => [
			'path' => '/photos/group/{guid}',
			'resource' => 'tidypics/photos/group',
			'required_plugins' => [
				'groups',
			],
		],
		'view:object:album' => [
			'path' => '/photos/album/{guid}/{title?}',
			'resource' => 'tidypics/photos/album/view',
		],
		'sort:object:album' => [
			'path' => '/photos/sort/{guid}',
			'resource' => 'tidypics/photos/album/sort',
		],
		'add:object:album' => [
			'path' => '/photos/add/{guid?}',
			'resource' => 'tidypics/photos/album/add',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'new:object:album' => [
			'path' => '/photos/new/{guid?}',
			'resource' => 'tidypics/photos/album/add',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'collection:object:image:owner' => [
			'path' => '/photos/siteimagesowner/{guid?}',
			'resource' => 'tidypics/lists/siteimagesowner',
		],
		'collection:object:image:friends' => [
			'path' => '/photos/siteimagesfriends/{username?}/{guid?}',
			'resource' => 'tidypics/lists/siteimagesfriends',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'collection:object:image:group' => [
			'path' => '/photos/siteimagesgroup/{guid}',
			'resource' => 'tidypics/lists/siteimagesgroup',
			'required_plugins' => [
				'groups',
			],
		],
		'view:object:image' => [
			'path' => '/photos/view/{guid}/{title?}',
			'resource' => 'tidypics/photos/image/view',
		],
		'image:object:image' => [
			'path' => '/photos/image/{guid}/{title?}',
			'resource' => 'tidypics/photos/image/view',
		],
		'collection:object:image:tagged' => [
			'path' => '/photos/tagged',
			'resource' => 'tidypics/photos/tagged',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'add:object:image' => [
			'path' => '/photos/upload/{guid}',
			'resource' => 'tidypics/photos/image/upload',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'thumbnail:object:image' => [
			'path' => '/photos/thumbnail/{guid}/{size?}',
			'resource' => 'tidypics/photos/image/thumbnail',
		],
		'download:object:image' => [
			'path' => '/photos/download/{guid}/{disposition?}',
			'resource' => 'tidypics/photos/image/download',
		],
		'edit:object:album' => [
			'path' => '/photos/edit/{guid}',
			'resource' => 'tidypics/photos/edit',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'edit:object:image' => [
			'path' => '/photos/edit/{guid}',
			'resource' => 'tidypics/photos/edit',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'edit:object:tidypics_batch' => [
			'path' => '/photos/edit/{guid}',
			'resource' => 'tidypics/photos/edit',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'collection:object:image:mostviewed' => [
			'path' => '/photos/mostviewed',
			'resource' => 'tidypics/lists/mostviewedimages',
		],
		'collection:object:image:mostviewedtoday' => [
			'path' => '/photos/mostviewedtoday',
			'resource' => 'tidypics/lists/mostviewedimagestoday',
		],
		'collection:object:image:mostviewedthismonth' => [
			'path' => '/photos/mostviewedthismonth',
			'resource' => 'tidypics/lists/mostviewedimagesthismonth',
		],
		'collection:object:image:mostviewedlastmonth' => [
			'path' => '/photos/mostviewedlastmonth',
			'resource' => 'tidypics/lists/mostviewedimageslastmonth',
		],
		'collection:object:image:mostviewedthisyear' => [
			'path' => '/photos/mostviewedthisyear',
			'resource' => 'tidypics/lists/mostviewedimagesthisyear',
		],
		'collection:object:image:mostcommented' => [
			'path' => '/photos/mostcommented',
			'resource' => 'tidypics/lists/mostcommentedimages',
		],
		'collection:object:image:mostcommentedtoday' => [
			'path' => '/photos/mostcommentedtoday',
			'resource' => 'tidypics/lists/mostcommentedimagestoday',
		],
		'collection:object:image:mostcommentedthismonth' => [
			'path' => '/photos/mostcommentedthismonth',
			'resource' => 'tidypics/lists/mostcommentedimagesthismonth',
		],
		'collection:object:image:mostcommentedlastmonth' => [
			'path' => '/photos/mostcommentedlastmonth',
			'resource' => 'tidypics/lists/mostcommentedimageslastmonth',
		],
		'collection:object:image:mostcommentedthisyear' => [
			'path' => '/photos/mostcommentedthisyear',
			'resource' => 'tidypics/lists/mostcommentedimagesthisyear',
		],
		'collection:object:image:recentlyviewed' => [
			'path' => '/photos/recentlyviewed',
			'resource' => 'tidypics/lists/recentlyviewed',
		],
		'collection:object:image:recentlycommented' => [
			'path' => '/photos/recentlycommented',
			'resource' => 'tidypics/lists/recentlycommented',
		],
		'collection:object:image:recentvotes' => [
			'path' => '/photos/recentvotes',
			'resource' => 'tidypics/lists/recentvotes',
			'required_plugins' => [
				'elggx_fivestar',
			],
		],
		'collection:object:image:highestrated' => [
			'path' => '/photos/highestrated',
			'resource' => 'tidypics/lists/highestrated',
			'required_plugins' => [
				'elggx_fivestar',
			],
		],
		'collection:object:image:highestvotecount' => [
			'path' => '/photos/highestvotecount',
			'resource' => 'tidypics/lists/highestvotecount',
			'required_plugins' => [
				'elggx_fivestar',
			],
		],
		'collection:object:album:all' => [
			'path' => '/photos/all',
			'resource' => 'tidypics/photos/all',
		],
		'collection:object:album:world' => [
			'path' => '/photos/world',
			'resource' => 'tidypics/photos/all',
		],
		'collection:object:image:all' => [
			'path' => '/photos/siteimagesall',
			'resource' => 'tidypics/lists/siteimagesall',
		],
		'default:object:tidypics_default' => [
			'path' => '/photos',
			'resource' => 'tidypics/photos/default',
		],
	],
	'widgets' => [
		'album_view' => [
			'context' => ['profile'],
		],
		'latest_photos' => [
			'context' => ['profile'],
		],
		'index_latest_photos' => [
			'context' => ['index'],
		],
		'index_latest_albums' => [
			'context' => ['index'],
		],
		'groups_latest_photos' => [
			'context' => ['groups'],
		],
		'groups_latest_albums' => [
			'context' => ['groups'],
		],
	],
	'views' => [
		'default' => [
			'tidypics/' => __DIR__ . '/graphics',
			'tidypics/js/plupload/plupload.full.min.js' => __DIR__ . '/vendors/plupload/js/plupload.full.min.js',
			'tidypics/js/plupload/' => __DIR__ . '/vendors/plupload/js',
			'tidypics/js/plupload/jquery.ui.plupload/jquery.ui.plupload.min.js' => __DIR__ . '/vendors/plupload/js/jquery.ui.plupload/jquery.ui.plupload.min.js',
			'tidypics/js/plupload/i18n/' => __DIR__ . '/vendors/plupload/js/i18n/',
			'tidypics/css/jqueryui-theme.css' => 'vendor/bower-asset/jquery-ui/themes/smoothness/jquery-ui.min.css',
			'tidypics/css/images/' => 'vendor/bower-asset/jquery-ui/themes/smoothness/images',
			'tidypics/css/plupload/css/jquery.ui.plupload.css' => __DIR__ . '/vendors/plupload/js/jquery.ui.plupload/css/jquery.ui.plupload.css',
			'tidypics/css/plupload/img/' => __DIR__ . '/vendors/plupload/js/jquery.ui.plupload/img',
		],
	],
	'view_extensions' => [
		'css/elgg' => [
			'photos/css' => [],
		],
		'css/admin' => [
			'photos/css' => [],
		],
	],
];