<?php

if (!elgg_is_admin_logged_in()) {
	return;
}

$logtime = elgg_extract('time', $vars, false);

if (!$logtime) {
	$logtime = elgg_get_plugin_setting('tidypics_current_log', 'tidypics');
}

$log = tidypics_get_log_location($logtime);

echo tidypics_get_last_log_line($log);