<?php
// if the create video link was selected
if ($_GET['run']) {
	// if the video file exists, delete it.  Do not really need this
	if ( file_exists("images/security/timelapse.mp4")) {
	    unlink("images/security/timelapse.mp4");
	}
	exec('rm -f images/security/thumbs/* ');
	echo shell_exec("ffmpeg -y -r 10 -i images/security/image%04d.jpg -c:v libx264 -crf 15 -pix_fmt yuv420p images/security/timelapse.mp4 </dev/null >/dev/null 2>/var/log/ffmpeg.log &");
	}

/* function:  generates thumbnail */
function make_thumb($src,$dest,$desired_width) {
	/* read the source image */
	$source_image = imagecreatefromjpeg($src);
	$width = imagesx($source_image);
	$height = imagesy($source_image);
	/* find the "desired height" of this thumbnail, relative to the desired width  */
	$desired_height = floor($height*($desired_width/$width));

	/* create a new, "virtual" image */
	$virtual_image = imagecreatetruecolor($desired_width,$desired_height);
	/* copy source image at a resized size */
	imagecopyresized($virtual_image,$source_image,0,0,0,0,$desired_width,$desired_height,$width,$height);
	/* create the physical thumbnail image to its destination */
	imagejpeg($virtual_image,$dest);
}

/* function:  returns files from dir */
function get_files($images_dir,$exts = array('jpg')) {
	$files = array();
	if($handle = opendir($images_dir)) {
		while(false !== ($file = readdir($handle))) {
			$extension = strtolower(get_file_extension($file));
			if($extension && in_array($extension,$exts)) {
				$files[] = $file;
			}
		}
		closedir($handle);
	}
	return $files;
}

/* function:  returns a file's extension */
function get_file_extension($file_name) {
	return substr(strrchr($file_name,'.'),1);
}

/** settings **/

$dir_prefix = '/var/www/html/';
$local_images_dir = 'images/security/';
$images_dir = $local_dir.$local_images_dir;

$local_thumbs_dir = $local_images_dir.'thumbs/';
$images_dir_web = 'images/security/';
$thumbs_dir = $dir_prefix.$local_thumbs_dir;

$thumbs_width = 200;
$images_per_row = 6;

/** generate photo gallery **/

$image_files = get_files($images_dir);

if(count($image_files)) {
	$index = 0;
	foreach($image_files as $index=>$file) {
		$index++;
		$thumbnail_image = $thumbs_dir.$file;
		if(!file_exists($thumbnail_image)) {
			$extension = get_file_extension($thumbnail_image);
			if($extension) {
				make_thumb($images_dir.$file,$thumbnail_image,$thumbs_width);
			}
		}
		echo '<a href="',$local_images_dir.$file,'" class="photo-link smoothbox" rel="gallery"><img src="',$local_thumbs_dir.$file,'" /></a>';
		if($index % $images_per_row == 0) { echo '<div class="clear"></div>'; }
	}
	echo '<div class="clear"></div>';
}
else {

	echo '<p>There are no images in this gallery.</p>';
}


?>


<html>
<head>
<style>
.clear			{ clear:both; }
.photo-link		{ padding:5px; margin:5px; border:1px solid #ccc; display:block; width:200px; float:left; }
.photo-link:hover	{ border-color:#999; }
</style>
</head>
<title>
Welcome to the Kelly's Images and Video
</title>
<body>

<a href="?run=true">Create new Video!</a>
<video src="images/security/timelapse.mp4" controls>
/video>

</body>
</html>
