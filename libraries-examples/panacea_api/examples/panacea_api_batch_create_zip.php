<?php
require_once("panacea_api.php");
$api = new PanaceaApi();
$api->setUsername("demouser");
$api->setPassword("demouser");

$file = "../generic/mybatch.zip";

$result = $api->batch_create("My batch name", $file, 0, false, 'zip');

if($api->ok($result)) {
	/* Batch created ! */
	
	$batch_id = $result['details'];
	
	echo "Batch created with ID {$batch_id}\n";
	
}

?>