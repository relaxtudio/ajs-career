<?php
defined('SAFELOAD') or define('SAFELOAD',true);
// ini_set('display_error', '0');
// error_reporting(E_WARNING);

require_once 'app/config.php';
require_once 'app/model.php';
require_once 'app/controller.php';

$url = explode("ws/",$_SERVER['REQUEST_URI']);
$urlparams = explode("/", $url[1]);

$result = [];

for ($i=0; $i < count($urlparams); $i++) {
	$check = new stdClass();
	$break = explode(";",$urlparams[$i]);
	$function = "null";
	if ($break[0]) {		
		$function = $break[0];
	}
	array_shift($break);
	$check->$function = null;
	if (function_exists($function)) {
		$check->$function = call_user_func_array($function, $break);
	}
	array_push($result, $check);
}

echo json_encode($result);