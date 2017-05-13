<?php
error_reporting(E_ALL);
// if (!define('SAFELOAD')) {
// 	exit('ACCESS FORBIDDEN');
// }

class Model {
	var $server;
	var $username;
	var $password;
	var $database;
	var $conn;

	function __construct() {
		$this->server = Config::$SERVER;
		$this->username = Config::$USER;
		$this->password = Config::$PASS;
		$this->database = Config::$DB;
	}

	function connect(){
        $this->conn = mysqli_connect($this->server, $this->username, $this->password, $this->database);
        if (!$this->conn) {
            echo json_encode(array('code'=>500,'message'=>'DB connection failed.'));
            die();
        }
    }

    function close(){
        mysqli_close($this->conn);
    }

    function getBase64($usr, $pass) {
    	$data = base64_encode($usr . ";;" . $pass);
    	return $data;
    }

    function checkUser($usr) {
    	$sql = "SELECT usr_name FROM usr_cred WHERE usr_name = '$usr'";
    	$q = mysqli_query($this->conn, $sql);
        $data = mysqli_fetch_object($q);

        if (count($data) > 0) {
            return true;
        } else {
            return false;
        }
    }

    function login($user, $pass) {
    	$sql = "SELECT usr_name, usr_pass, usr_salt FROM usr_cred WHERE usr_name = '$user'";
    	$q = mysqli_query($this->conn, $sql) or die('false');
    	$result = mysqli_fetch_array($q);
    	mysqli_free_result($q);
    	$salt = $result['usr_salt'];
    	$check = hash('sha256', $pass . $salt);
    	for ($i =0; $i < 65536; $i++) { 
    		$check = hash('sha256', $check . $salt);
    	}
    	$status = false;
    	if ($check == $result['usr_pass']) {
            $token = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
    		$sqlAdd = "UPDATE usr_cred SET usr_token = '$token' WHERE usr_name = '$user'";
            $qAdd = mysqli_query($this->conn, $sqlAdd) or die('false');
            if ($qAdd) {
                $status = $token;
            }
    	}
    	return $status;
    }

    function createUser($data, $token) {
    	$data = explode(",", base64_decode($data));
    	$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
        $password = hash('sha256', $data[1] . $salt);
        for($i = 0; $i < 65536; $i++) {
            $password = hash('sha256', $password . $salt);
        }
        $sql = "INSERT INTO usr_cred (usr_name, usr_pass, usr_salt) VALUES ('$data[0]', '$password', '$salt')";
        $q = mysqli_query($this->conn, $sql) or die('false');
        $status = false;
        if ($q) {
        	$status = true;
        }
        return $status;
    }

    function getConfig() {
        $sql = "SELECT * FROM career_config";
        $q = mysqli_query($this->conn, $sql);
        $result = mysqli_fetch_all($q,MYSQLI_ASSOC);

        return $result;
    }

    function updateConfig() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $text = $request->text;
        $min_date = $request->min_date;
        $max_date = $request->max_date;
        $toggle = $request->toggle;
        $sql = "UPDATE career_config 
                SET text='$text', min_date='$min_date',
                    max_date='$max_date', toggle='$toggle'
                WHERE idx=0";
        $q = mysqli_query($this->conn, $sql);
        $status = false;
        if ($q) {
            $status = true;
        }

        return $status;
    }

    function getRegistree() {
        $sql = "SELECT career_name AS name,
                        career_address AS address,
                        career_ktp AS ktp,
                        career_telp AS phone,
                        career_email AS email,
                        job_name AS job,
                        career_file AS file
                FROM career_schema cs
                JOIN career_job cj ON cj.job_id = cs.job_id
                ORDER BY cj.job_name,cs.career_id";
        $q = mysqli_query($this->conn, $sql);
        $result = mysqli_fetch_all($q,MYSQLI_ASSOC);

        return $result;
    }

    function getJob() {
        $sql = "SELECT * FROM career_job";
        $q = mysqli_query($this->conn, $sql);
        $result = mysqli_fetch_all($q,MYSQLI_ASSOC);

        return $result;
    }

    function addJob() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $name = $request->job_name;
        $req = $request->job_req;
        $sql = "INSERT INTO career_job (job_name, job_req) VALUES ('$name', '$req')";
        $q = mysqli_query($this->conn, $sql);
        $status = false;
        if ($q) {
            $status = true;
        }

        return $status;
    }

    function updateJob() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $id = $request->job_id;
        $name = $request->job_name;
        $req = $request->job_req;
        $sql = "UPDATE career_job SET job_name='$name', job_req='$req'
                WHERE job_id='$id'";
        $q = mysqli_query($this->conn, $sql);
        $status = false;
        if ($q) {
            $status = true;
        }

        return $status;
    }

    function deleteJob() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $id = $request->job_id;
        $sql = "DELETE FROM career_job WHERE job_id='$id'";
        $q = mysqli_query($this->conn, $sql);
        $status = false;
        if ($q) {
            $status = true;
        }

        return $status;
    }
}