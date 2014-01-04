<?php

/**
 * Update view path of river entries for comments made on Tidypics images, albums and tidypics_batches (image uploads)
 *
 * This is a follow-up upgrade to be executed after the Elgg core upgrade from Elgg 1.8 to Elgg 1.9.
 * The Elgg core upgrade script changes comments from annotations to entities and updates the river entries accordingly.
 * This Tidypics-specific script then updates the views referred in river entries for comments made on Tidypics entities
 * to allow for using the Tidypics-specific river comment views (which add optionally a thumbnail image of the image/album
 * commented on and takes the specifics of commenting on tidypics_batches into account)
 */

// prevent timeout when script is running (thanks to Matt Beckett for suggesting)
set_time_limit(0);

$ia = elgg_set_ignore_access(true);

// don't want any event or plugin hook handlers from plugins to run
$original_events = _elgg_services()->events;
$original_hooks = _elgg_services()->hooks;
_elgg_services()->events = new Elgg_EventsService();
_elgg_services()->hooks = new Elgg_PluginHooksService();
elgg_register_plugin_hook_handler('permissions_check', 'all', 'elgg_override_permissions');
elgg_register_plugin_hook_handler('container_permissions_check', 'all', 'elgg_override_permissions');

$db_prefix = elgg_get_config('dbprefix');

// now updating river entries for comments on images
$batch = new ElggBatch('elgg_get_river', array(
                            'type' => 'object',
                            'subtype' => 'comment',
                            'action_type' => 'comment',
                            'joins' => array("JOIN {$db_prefix}entities te ON te.guid = rv.target_guid",
                                             "JOIN {$db_prefix}entity_subtypes ts ON te.subtype = ts.id AND ts.subtype = 'image'"),
                            'limit' => false));
foreach ($batch as $river_entry) {
    $query = "
                UPDATE {$db_prefix}river
                SET view = 'river/object/comment/image'
                WHERE id = {$river_entry->id}
        ";
    update_data($query);
}

// now updating river entries for comments on albums
$batch = new ElggBatch('elgg_get_river', array(
                            'type' => 'object',
                            'subtype' => 'comment',
                            'action_type' => 'comment',
                            'joins' => array("JOIN {$db_prefix}entities te ON te.guid = rv.target_guid",
                                             "JOIN {$db_prefix}entity_subtypes ts ON te.subtype = ts.id AND ts.subtype = 'album'"),
                            'limit' => false));
foreach ($batch as $river_entry) {
    $query = "
                UPDATE {$db_prefix}river
                SET view = 'river/object/comment/album'
                WHERE id = {$river_entry->id}
        ";
    update_data($query);
}

// now updating river entries for comments on tidypics_batches
// fix target_guid and access_id for river entries that do not yet point to the album
$batch = new ElggBatch('elgg_get_river', array(
                            'type' => 'object',
                            'subtype' => 'comment',
                            'action_type' => 'comment',
                            'joins' => array("JOIN {$db_prefix}entities te ON te.guid = rv.target_guid",
                                             "JOIN {$db_prefix}entity_subtypes ts ON te.subtype = ts.id AND ts.subtype = 'tidypics_batch'"),
                            'limit' => false));
foreach ($batch as $river_entry) {
    $target_entity = get_entity($river_entry->target_guid);
    $album = get_entity($target_entity->container_guid);
    $query = "
                UPDATE {$db_prefix}river
                SET view = 'river/object/comment/album',
                        access_id = {$album->access_id},
                        target_guid = {$album->guid}
                WHERE id = {$river_entry->id}
        ";
    update_data($query);
}

// replace events and hooks
_elgg_services()->events = $original_events;
_elgg_services()->hooks = $original_hooks;

elgg_set_ignore_access($ia);
