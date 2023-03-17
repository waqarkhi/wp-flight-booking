<?php 
	if( @$_FILES ) {
		header('content-type:application/json');
		$file = $_FILES['file'];
		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		$ext = ($ext == "") ? explode('/', $file['type'])[1] : $ext;
		$file['extension'] = $ext;
		$path = 'upload/'.$_POST['name'].'.'.$ext;
		move_uploaded_file($file['tmp_name'], $path);
		$up = ['file'=> $path];
		$up['raw_file'] = $file;
		echo json_encode($up);
		die();
	}
?>