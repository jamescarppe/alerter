<?php
require_once('functions.php');

if (!file_exists('/config/config.php')) {
	_log("[!] Unable to find configuration at /config/config.php, aborting\n");
	exit(1);
}
require_once('/config/config.php');

// Get the latest environment info from the Portainer API
$environments = json_decode(apiget($PORTAINER_API_KEY, $PORTAINER_API_URL . "/api/endpoints", array("excludeSnapshots" => "true")));

foreach ($environments as $environment) {
	_log("[*] Checking for environment " . $environment->Id . " in alerter config");
	if (array_key_exists($environment->Id, $ENVIRONMENTS_CONFIG)) {
		// Exists in config
		_log("[*] Environment exists in config, proceeding");
		if ($ENVIRONMENTS_CONFIG[$environment->Id]["sendAlerts"] == true) {
			// Send alerts
			_log("[*] Alerting enabled, checking status");
			if ($environment->Type == 4) {
				// Edge
				if ($environment->LastCheckInDate != 0 && (time() - $environment->LastCheckInDate) > $ENVIRONMENTS_CONFIG[$environment->Id]["checkinThreshold"]) {
					// Edge environment hasn't checked in within the specified parameter
					_log("[!] Edge environment " . $environment->Id . " has not checked in within " . $ENVIRONMENTS_CONFIG[$environment->Id]["checkinThreshold"] . " seconds, sending email to " . $ENVIRONMENTS_CONFIG[$environment->Id]["alertRecipient"]);
				} else {
					_log("[*] Edge environment " . $environment->Id . " last checked in within " . $ENVIRONMENTS_CONFIG[$environment->Id]["checkinThreshold"] . " seconds, no action needed");
				}
			} else {
				if ($environment->Status == 2) {
					// Environment is down
					_log("[!] Environment " . $environment->Id . " reports as DOWN, sending email to " . $ENVIRONMENTS_CONFIG[$environment->Id]["alertRecipient"]);
				} else {
					_log("[*] Environment " . $environment->Id . " reports as up, no action needed");
				}
			}
		} else {
			_log("[*] Alerting not enabled, moving on");
		}
	} else {
		_log("[*] Environment not in config, moving on");
	}
}
?>
