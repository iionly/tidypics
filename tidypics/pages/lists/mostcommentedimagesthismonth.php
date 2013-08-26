<?php

/**
 * Most commented images of the current month
 *
 */

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('photos'), 'photos/siteimagesall');
elgg_push_breadcrumb(elgg_echo('tidypics:mostcommentedthismonth'));

$offset = (int)get_input('offset', 0);
$max = 16;

$start = mktime(0,0,0, date("m"), 1, date("Y"));
$end = time();

$db_prefix = elgg_get_config('dbprefix');
$options = array('type' => 'object',
                 'subtype' => 'image',
                 'limit' => $max,
                 'offset' => $offset,
                 'selects' => array("count( * ) AS views"),
                 'joins' => array("JOIN {$db_prefix}entities ce ON ce.container_guid = e.guid",
                                  "JOIN {$db_prefix}entity_subtypes cs ON ce.subtype = cs.id AND cs.subtype = 'comment'"),
                 'wheres' => array("ce.time_created BETWEEN {$start} AND {$end}"),
                 'group_by' => 'e.guid',
                 'order_by' => "views DESC",
                 'full_view' => false,
                 'list_type' => 'gallery',
                 'gallery_class' => 'tidypics-gallery'
                );

$result = elgg_list_entities($options);

$title = elgg_echo('tidypics:mostcommentedthismonth');

if (elgg_is_logged_in()) {
        elgg_load_js('lightbox');
        elgg_load_css('lightbox');
        $logged_in_guid = elgg_get_logged_in_user_guid();
        elgg_register_menu_item('title', array('name' => 'addphotos',
                                               'href' => "ajax/view/photos/selectalbum/?owner_guid=" . $logged_in_guid,
                                               'text' => elgg_echo("photos:addphotos"),
                                               'link_class' => 'elgg-button elgg-button-action elgg-lightbox'));
}

if (!empty($result)) {
        $area2 = $result;
} else {
        $area2 = elgg_echo('tidypics:mostcommentedthismonth:nosuccess');
}
$body = elgg_view_layout('content', array(
        'filter_override' => '',
        'content' => $area2,
        'title' => $title,
        'sidebar' => elgg_view('photos/sidebar', array('page' => 'all')),
));

// Draw it
echo elgg_view_page(elgg_echo('tidypics:mostcommentedthismonth'), $body);
