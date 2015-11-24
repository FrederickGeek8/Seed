<?php

include("../core/init.inc.php");
include("init.inc.php");

$uploads = array();
$errors = array();
$allowed = array("gif", "png", "jpg", "jpeg", "pdf", "doc", "docx", "html", "txt", "rtf", "tiff", "xlsx", "xls");
if(isset($_FILES['files'])){
	foreach($_FILES['files']['tmp_name'] as $key => $tmp_name){
		$newname = pathinfo($_FILES["files"]["name"][$key]);
		if(in_array($newname["extension"], $allowed) === false){
			$errors[] = "Extension type {$newname['extension']} is not allowed";
		}
		if($_FILES["file"]["size"][$key] > 15000000){
			$errors[] = "File too large";
		}
		if(empty($errors)){
			$newname = $newname["filename"]."_".microtime().".".$newname["extension"];
			move_uploaded_file($tmp_name, "/var/www/assets/uploads/{$newname}");
			$uploads[] = html_escape($newname);	
		}
	}
}

?>
<!DOCTYPE html>
<html lang="en">
	<head><title>Upload File</title></head>
	<body>
		<center>
		<?php if(empty($uploads) === false && empty($errors)){ ?>
		<h2>Success</h2><p>We uploaded your files! Below are the links.</p><?php foreach($uploads AS $upload){ ?><p><input type="text" onClick="this.select();" value="//marketdream.org/assets/uploads/<?=$upload?>" /></p><?php } ?>
		<?php } if(empty($errors) === false){ ?>
		<h2>Failed</h2><?php foreach($errors AS $error){ echo "<p>{$error}</p>"; } ?>
		<?php } ?>
		<form action="#" method="post" enctype="multipart/form-data">
			<input type="file" name="files[]" multiple="multiple" min="1" max="9999" />
			<input type="submit" value="Upload" />
		</form>
		</center>
	</body>
</html>