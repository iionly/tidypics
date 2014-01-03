<?php

$ia = elgg_set_ignore_access(true);

// don't want any event or plugin hook handlers from plugins to run
$original_events = _elgg_services()->events;
$original_hooks = _elgg_services()->hooks;
_elgg_services()->events = new Elgg_EventsService();
_elgg_services()->hooks = new Elgg_PluginHooksService();
elgg_register_plugin_hook_handler('permissions_check', 'all', 'elgg_override_permissions');
elgg_register_plugin_hook_handler('container_permissions_check', 'all', 'elgg_override_permissions');

$db_prefix = elgg_get_config('dbprefix');

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
