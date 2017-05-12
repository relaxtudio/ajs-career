<?php
error_reporting(E_ALL);
if (!defined('SAFELOAD'))
    exit('ACCESS FORBIDDEN!');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-type: application/json');

function getBase64($usr = NULL, $pass = NULL) {
	$model = new Model;
	return $model->getBase64($usr, $pass);
}

function checkUser($usr = NULL) {
	$model = new Model;
	$model->connect();
	return $model->checkUser($usr);
	$model->close();
}

function login($user = NULL, $pass = NULL) {
	$model = new Model;
	$model->connect();
	return $model->login($user, $pass);
	$model->close();
}

function createUser($data = NULL, $token = NULL) {
	$model = new Model;
	$model->connect();
	return $model->createUser($data, $token);
	$model->close();
}

function getConfig() {
	$model = new Model;
	$model->connect();
	return $model->getConfig();
	$model->close();
}

function updateConfig() {
	$model = new Model;
	$model->connect();
	return $model->updateConfig();
	$model->close();
}

function getRegistree() {
	$model = new Model;
	$model->connect();
	return $model->getRegistree();
	$model->close();
}

function getJob() {
	$model = new Model;
	$model->connect();
	return $model->getJob();
	$model->close();
}

function addJob() {
	$model = new Model;
	$model->connect();
	return $model->addJob();
	$model->close();
}

function updateJob() {
	$model = new Model;
	$model->connect();
	return $model->updateJob();
	$model->close();
}

function deleteJob() {
	$model = new Model;
	$model->connect();
	return $model->deleteJob();
	$model->close();
}