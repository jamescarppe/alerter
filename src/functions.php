<?php
function apiget($key, $url, $data = false) {
	$curl = curl_init();
	if ($data) {
		$url = sprintf("%s?%s", $url, http_build_query($data));
	}

	curl_setopt($curl, CURLOPT_HTTPHEADER, [
		'X-API-Key: ' . $key
	]);

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

	if (!$result = curl_exec($curl)) {
		echo curl_error($curl);
	}
	curl_close($curl);
	return $result;
}

function _log($content) {
	echo "[" . date("Y-m-d H:i:s") . "] " . $content . "\n";
}
?>
