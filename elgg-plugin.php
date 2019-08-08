<?php
return [
	'views' => [
		'default' => [
			'tidypics/' => __DIR__ . '/graphics',
			"tidypics/js/plupload/plupload.full.min.js" => __DIR__ . "/vendors/plupload/js/plupload.full.min.js",
			"tidypics/js/plupload/" => __DIR__ . "/vendors/plupload/js",
			"tidypics/js/plupload/jquery.ui.plupload/jquery.ui.plupload.min.js" => __DIR__ . "/vendors/plupload/js/jquery.ui.plupload/jquery.ui.plupload.min.js",
			"tidypics/js/plupload/i18n/" => __DIR__ . "/vendors/plupload/js/i18n",
			"tidypics/css/plupload/css/jquery.ui.plupload.css" => __DIR__ . "/vendors/plupload/js/jquery.ui.plupload/css/jquery.ui.plupload.css",
			"tidypics/css/plupload/img/" => __DIR__ . "/vendors/plupload/js/jquery.ui.plupload/img",

			"tidypics/css/jqueryui-theme.css" => "/vendor/bower-asset/jquery-ui/themes/smoothness/jquery-ui.min.css",
		],
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'image',
			'class' => 'TidypicsImage',
			'searchable' => true,
		],
		[
			'type' => 'object',
			'subtype' => 'album',
			'class' => 'TidypicsAlbum',
			'searchable' => true,
		],
		[
			'type' => 'object',
			'subtype' => 'tidypics_batch',
			'class' => 'TidypicsBatch',
			'searchable' => false,
		],
	],
	'actions' => [
		'tidypics/settings/save' => [],
		'photos/delete' => [],
		'photos/album/save' => [],
		'photos/album/sort' => [],
		'photos/album/set_cover' => [],
		'photos/image/upload' => [],
		'photos/image/save' => [],
		'photos/image/ajax_upload' => [],
		'photos/image/ajax_upload_complete' => [],
		'photos/image/tag' => [],
		'photos/image/untag' => [],
		'photos/image/selectalbum' => [],
		'photos/batch/edit' => [],
		'photos/admin/create_thumbnail' => ['access' => 'admin'],
		'photos/admin/resize_thumbnails' => ['access' => 'admin'],
		'photos/admin/delete_image' => ['access' => 'admin'],
		'photos/admin/upgrade' => ['access' => 'admin'],
		'photos/admin/broken_images' => ['access' => 'admin'],
		'photos/admin/imtest' => ['access' => 'admin'],
	],
	'routes' => [
		'collection:object:photos:siteimagesall_guid' => [
			'path' => '/photos/siteimagesall/{guid}',
			'resource' => 'tidypics/lists/siteimagesowner',
		],
		'collection:object:photos:siteimagesall' => [
			'path' => '/photos/siteimagesall',
			'resource' => 'tidypics/lists/siteimagesowner',
		],
		'collection:object:photos:siteimagesgroup' => [
			'path' => '/photos/siteimagesgroup/{guid}',
			'resource' => 'tidypics/lists/siteimagesgroup',
		],
		'collection:object:photos:siteimagesfriends' => [
			'path' => '/photos/siteimagesfriends',
			'resource' => 'tidypics/lists/siteimagesfriends',
		],
		'collection:object:photos:all' => [
			'path' => '/photos/all',
			'resource' => 'tidypics/photos/all',
		],
		'collection:object:photos:world' => [
			'path' => '/photos/world',
			'resource' => 'tidypics/photos/all',
		],
		'collection:object:photos:owned' => [
			'path' => '/photos/owned',
			'resource' => 'tidypics/photos/owner',
		],
		'collection:object:photos:group' => [
			'path' => '/photos/group/{guid}/all',
			'resource' => 'tidypics/photos/owner',
		],
		'collection:object:photos:owner' => [
			'path' => '/photos/owner',
			'resource' => 'tidypics/photos/owner',
		],
		'collection:object:photos:friends' => [
			'path' => '/photos/friends',
			'resource' => 'tidypics/photos/friends',
		],
		'collection:object:photos:album' => [
			'path' => '/photos/album/{guid}/{title}',
			'resource' => 'tidypics/photos/album/view',
		],
		'new:object:photos' => [
			'path' => '/photos/new/{guid}',
			'resource' => 'tidypics/photos/album/add',
		],
		'add:object:photos' => [
			'path' => '/photos/add/{guid}',
			'resource' => 'tidypics/photos/album/add',
		],
		'edit:object:photos' => [
			'path' => '/photos/edit/{guid}',
			'resource' => 'tidypics/photos/edit',
		],
		'collection:object:photos:sort' => [
			'path' => '/photos/sort/{guid}',
			'resource' => 'tidypics/photos/album/sort',
		],
		'image:object:photos' => [
			'path' => '/photos/image/{guid}/{title}',
			'resource' => 'tidypics/photos/image/view',
		],
		'view:object:photos' => [
			'path' => '/photos/view/{guid}',
			'resource' => 'tidypics/photos/image/view',
		],
		'collection:object:photos:thumbnail' => [
			'path' => '/photos/thumbnail/{guid}/{size}',
			'resource' => 'tidypics/photos/image/thumbnail',
		],
		'collection:object:photos:upload' => [
			'path' => '/photos/upload/{guid}',
			'resource' => 'tidypics/photos/image/upload',
		],
		'collection:object:photos:uploadadv' => [
			'path' => '/photos/upload/{guid}/{uploader}',
			'resource' => 'tidypics/photos/image/upload',
		],
		// 'collection:object:photos:upload_basic' => [
		// 	'path' => '/photos/upload/{guid}/basic',
		// 	'resource' => 'tidypics/photos/image/upload',
		// ],
		'collection:object:photos:download' => [
			'path' => '/photos/download/{guid}/{disposition}',
			'resource' => 'tidypics/photos/image/download',
		],
		'collection:object:photos:tagged' => [
			'path' => '/photos/tagged',
			'resource' => 'tidypics/photos/tagged',
		],
		'collection:object:photos:riverpopup' => [
			'path' => '/photos/riverpopup/{guid}',
			'resource' => 'tidypics/photos/riverpopup',
		],
		'collection:object:photos:mostviewed' => [
			'path' => '/photos/mostviewed',
			'resource' => 'tidypics/lists/mostviewedimages',
		],
		'collection:object:photos:mostviewedtoday' => [
			'path' => '/photos/mostviewedtoday',
			'resource' => 'tidypics/lists/mostviewedimagestoday',
		],
		'collection:object:photos:mostviewedthismonth' => [
			'path' => '/photos/mostviewedthismonth',
			'resource' => 'tidypics/lists/mostviewedimagesthismonth',
		],
		'collection:object:photos:mostviewedlastmonth' => [
			'path' => '/photos/mostviewedlastmonth',
			'resource' => 'tidypics/lists/mostviewedimageslastmonth',
		],
		'collection:object:photos:mostviewedthisyear' => [
			'path' => '/photos/mostviewedthisyear',
			'resource' => 'tidypics/lists/mostviewedimagesthisyear',
		],
		'collection:object:photos:mostcommented' => [
			'path' => '/photos/mostcommented',
			'resource' => 'tidypics/lists/mostcommentedimages',
		],
		'collection:object:photos:mostcommentedtoday' => [
			'path' => '/photos/mostcommentedtoday',
			'resource' => 'tidypics/lists/mostcommentedimagestoday',
		],
		'collection:object:photos:mostcommentedthismonth' => [
			'path' => '/photos/mostcommentedthismonth',
			'resource' => 'tidypics/lists/mostcommentedimagesthismonth',
		],
		'collection:object:photos:mostcommentedlastmonth' => [
			'path' => '/photos/mostcommentedlastmonth',
			'resource' => 'tidypics/lists/mostcommentedimageslastmonth',
		],
		'collection:object:photos:mostcommentedthisyear' => [
			'path' => '/photos/mostcommentedthisyear',
			'resource' => 'tidypics/lists/mostcommentedimagesthisyear',
		],
		'collection:object:photos:recentlyviewed' => [
			'path' => '/photos/recentlyviewed',
			'resource' => 'tidypics/lists/recentlyviewed',
		],
		'collection:object:photos:recentlycommented' => [
			'path' => '/photos/recentlycommented',
			'resource' => 'tidypics/lists/recentlycommented',
		],
		'collection:object:photos:recentvotes' => [
			'path' => '/photos/recentvotes',
			'resource' => 'tidypics/lists/recentvotes',
		],
		'collection:object:photos:highestrated' => [
			'path' => '/photos/highestrated',
			'resource' => 'tidypics/lists/highestrated',
		],
		'collection:object:photos:highestvotecount' => [
			'path' => '/photos/highestvotecount',
			'resource' => 'tidypics/lists/highestvotecount',
		],
	],
];
