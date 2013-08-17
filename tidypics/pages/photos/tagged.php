<?php
/**
 * Tidypics Tagged Listing
 *
 * List all photos tagged with a user
 */

// Get user guid
$guid = get_input('guid');

$user = get_entity($guid);

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('photos'), 'photos/siteimagesall');
elgg_push_breadcrumb(elgg_echo('tidypics:usertagged'));

$offset = (int)get_input('offset', 0);
$max = 16; //controls how many actually show on screen

if ($user && ($user instanceof ElggUser)) {
	$title = elgg_echo('tidypics:usertag', array($user->name));
	
	$options = array('relationship' => 'phototag',
                 'relationship_guid' => $guid,
                 'inverse_relationship' => false,
                 'type' => 'object',
                 'subtype' => 'image',
                 'limit' => $max,
                 'offset' => $offset,
                 'full_view' => false,
                 'list_type' => 'gallery',
                 'gallery_class' => 'tidypics-gallery'
                );
        $result = elgg_list_entities_from_relationship($options);
        if (!empty($result)) {
                $area2 = $result;
        } else {
                $area2 = elgg_echo('tidypics:usertags_photos:nosuccess');
        }
} else {
	$title = elgg_echo('tidypics:usertag:nosuccess');
	$area2 = '';
}

if (elgg_is_logged_in()) {
        elgg_load_js('lightbox');
        elgg_load_css('lightbox');
        $logged_in_guid = elgg_get_logged_in_user_guid();
        elgg_register_menu_item('title', array('name' => 'addphotos',
                                               'href' => "ajax/view/photos/selectalbum/?owner_guid=" . $logged_in_guid,
                                               'text' => elgg_echo("photos:addphotos"),
                                               'link_class' => 'elgg-button elgg-button-action elgg-lightbox'));
}

$body = elgg_view_layout('content', array(
        'filter_override' => '',
        'content' => $area2,
        'title' => $title,
        'sidebar' => elgg_view('photos/sidebar', array('page' => 'all')),
));

// Draw it
echo elgg_view_page($title, $body);
