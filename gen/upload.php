<?php
include 'db.php';

$data = json_encode($_POST);
$file = $_FILES["file"]["name"];
$tmp = explode(".", $file);
$ext = end($tmp);
$nama = $_POST['name'];
$alamat = $_POST['address'];
$ktp = $_POST['ktp'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$job = $_POST['job'];
$path = '../adm/content/';
$tmp = $ktp . "." . $ext;
if ($_FILES['file']['size'] > 1024000) {
	echo "File melebihi kapasitas yang ditentukan";
} else {
	if ($ext != "pdf") {
		echo "File harus pdf";
	} else {
		if (move_uploaded_file($_FILES['file']['tmp_name'], $path.basename($tmp))) {
			// echo "<div>Uploaded $data</div>";
			$sql = "INSERT INTO career_schema (career_name, career_address, career_ktp, career_telp, career_email, career_file, job_id) VALUES ('$nama', '$alamat', '$ktp', '$phone', '$email', '$tmp', '$job')";
			$conn->query($sql) or die(mysqli_error($conn));
			mysqli_close($conn);
			echo "Success";
		}
	}
}