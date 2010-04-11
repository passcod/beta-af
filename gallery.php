<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php /*

READ ME:

To manage the photos:

All you need to do to add pictures in the gallery is to add the
images, optimally reduced to a 800 x 600 resolution, to the
following directory:

	/gallery

The pictures will be taken in ascendin order (alphabetically).

*/ ?>


<?php $page_name = 'Gallery'; include("meta.php"); $is_gallery = true; ?>

<script type="text/javascript" src="breadcrumb.js"></script>
<link rel="stylesheet" href="common.css" />

<?php


function getGallery() {
	function file_array($path, $exclude = ".|..", $recursive = true) {
		$path = rtrim($path, "/") . "/";
		$folder_handle = opendir($path);
		$exclude_array = explode("|", $exclude);
		$result = array();
		
		while(false !== ($filename = readdir($folder_handle)))
		{
			if(!in_array(strtolower($filename), $exclude_array))
			{
				if(is_dir($path . $filename . "/")) // Need to include full "path" or it's an infinite loop
				{
					if($recursive) $result[] = file_array($path . $filename . "/", $exclude, true);
				}
				else
				{
					$result[] = $filename;
				}
			}
		}

		return $result;
	}

	$files = file_array( './gallery', '.|..|index.php|readme.txt|up.gif|down.gif', false );
	sort($files);


	$i = 0;
	$j = 0;
	$groups_of_three = Array();
	$groups_of_three[0] = Array();
	foreach( $files as $file )
	{
		if ( $i > 2 ) { $i = 0; $j++; $groups_of_three[$j] = Array(); }
		$groups_of_three[$j][$i] = $file;
		$i++;
	}

	return $groups_of_three;
}


$full = getGallery();

// making sure the groups loop...
if (!empty($_POST['group'])) { $group_no = $_POST['group']; }
else { $group_no = 0; }
if ( $group_no < 0 ) { $group_no = count($full)-1; }
else if ( $group_no > count($full)-1 ) { $group_no = 0; }

// and that if nothing is specified, the default group is set
if (!empty($_POST['image'])) { $image = $_POST['image']; }
else { $image = 0; }

?>


</head>

<body id="gallery">

<div id="layout-page" style="background-color: rgb(40, 29, 25);">
	<div id="layout-head">
		<table id="table-head" cellspacing="0" cellpadding="0">
			<tbody><tr>
				<td id="cell-1-head" rowspan="2"><a href="gallery.php"><img alt="header_gallery" src="images_header/header_01.gif" width="364" height="200" /></a></td>
				<td id="cell-2-head"><a href="location.php"><img alt="header_location" src="images_header/header_02.gif" width="436" height="120" /></a></td>
			</tr>
			<tr>
				<td id="cell-3-head"><a href="index.php"><img alt="header_index" src="images_header/header_03.gif" width="436" height="80" /></a></td>
			</tr>
		</tbody></table>
	</div><a id="Top"></a>
	<div id="layout-breadcrumb" style="background-color: rgb(181, 156, 99);">
		<table id="table-breadcrumb" cellspacing="0" cellpadding="0">
			<tbody><tr>
				<td><a href="index.php"><img alt="Link to Index" src="images_nav/button_01.gif" onmouseover="breadcrumb('button-1', false, true)" onmouseout="breadcrumb('button-1', false, false)" id="button-1" width="133" height="30" /></a></td>
				<td><a href="gallery.php"><img alt="Link to Gallery" src="images_nav/button_active_02.gif" onmouseover="breadcrumb('button-2', true, true)" onmouseout="breadcrumb('button-2', true, false)" id="button-2" width="133" height="30" /></a></td>
				<td><a href="location.php"><img alt="Link to Location" src="images_nav/button_03.gif" onmouseover="breadcrumb('button-3', false, true)" onmouseout="breadcrumb('button-3', false, false)" id="button-3" width="133" height="30" /></a></td>
				<td><a href="safety.php"><img alt="Link to Safety" src="images_nav/button_04.gif" onmouseover="breadcrumb('button-4', false, true)" onmouseout="breadcrumb('button-4', false, false)" id="button-4" width="133" height="30" /></a></td>
				<td><a href="pricing.php"><img alt="Link to Pricing" src="images_nav/button_05.gif" onmouseover="breadcrumb('button-5', false, true)" onmouseout="breadcrumb('button-5', false, false)" id="button-5" width="133" height="30" /></a></td>
				<td><a href="activities.php"><img alt="Link to Activities" src="images_nav/button_06.gif" onmouseover="breadcrumb('button-6', false, true)" onmouseout="breadcrumb('button-6', false, false)" id="button-6" width="133" height="30" /></a></td>
			</tr>
		</tbody></table>
	</div>
	<div id="layout-left" style="text-align: center">
		<br />
		<br />
		<div id="gallery-group">
			<form method="post" action="gallery.php#Top">
				<input type="hidden" name="group" value="<?php print $group_no - 1; ?>" />
				<button type="submit" style="background-color: transparent; border: 0px;">
					<img class="ud_buttons" alt="" 	src="gallery/up.gif" />
				</button>
			</form>
	
			<br />
<?php for( $i = 0; $i < 3; $i++ ) { ?>
			<form method="post" action="gallery.php#Top">
				<input type="hidden" name="group" value="<?php print $group_no; ?>" />
				<input name="image" type="hidden" value="<?php echo $i; ?>" />
				<button type="submit" style="background-color: transparent; border: 0px;">
					<img class="ud_buttons thumbs-sidebar" alt="" 	src="gallery/<?php print $full[$group_no][$i]; ?>" />
				</button>
			</form>

			<br />
<?php } ?>
				
			<form method="post" action="gallery.php#Top">
				<input type="hidden" name="group" value="<?php print $group_no + 1; ?>" />
				<input name="image" type="hidden" value="<?php print $image; ?>" />
				<button type="submit" style="background-color: transparent; border: 0px;">
					<img class="ud_buttons" alt="" 	src="gallery/down.gif" />
				</button>
			</form>
		</div>
	</div>
	<div id="layout-center" style="text-align: center; background-color: rgb(40, 29, 25)">
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<div>
		<?php if (!empty($full[$group_no][$image])) { ?>
		<img alt="" src="gallery/<?php print $full[$group_no][$image]; ?>" style="width: 500px;" />
		<?php } ?>
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<script type='text/javascript' src='gallery/jw/swfobject.js'></script>
		<div id='mediaspace' style="margin-right: 35px">_</div>
		<script type='text/javascript'>
		  var so = new SWFObject('gallery/jw/player.swf','mpl','540','405','9');
		  so.addParam('allowfullscreen','true');
		  so.addParam('allowscriptaccess','always');
		  //so.addParam('wmode','opaque');
		  so.addVariable('file','http://beta.adventureforest.co.nz/gallery/jw/video.flv');
		  //so.addVariable('file','http://www.youtube.com/watch?v=aBJQ5085kSo');
		  so.addVariable('image','http://beta.adventureforest.co.nz/gallery/jw/preview.jpg');
		  so.addVariable('backcolor','#281D19');
		  so.addVariable('frontcolor','#F0AA31');
		  so.addVariable('screencolor','#281D19');
		  so.addVariable('autostart','true');
		  so.write('mediaspace');
		</script>
		</div>
	</div>
		<div id="layout-foot">&nbsp;</div>
</div>
<?php include("footer.php"); ?>
</body>

</html>
