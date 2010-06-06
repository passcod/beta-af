<?php
$pass = md5('ffff3696');
$good_script = str_replace( '*/ ?>', '', str_replace( '<?php /*' , '', file_get_contents('cal.php') ) );
$bad_script = "function editCal() { $('#login_form').children().remove(); $('#login_form').prepend('<p class=\"red\" style=\"font-weight: bold\">Wrong password - <a onclick=\"location.reload(true);\">try again</a></p>'); }";

if($_GET['data'] == 'script')
{
	header('Content-type: text/javascript');
	if ( $_GET['pass'] == $pass )
	{
		echo $good_script;
	}
	else
	{
		echo $bad_script;
	}
	exit();
}
elseif(!empty($_POST['day_0_1']))
{
	header('Content-type: text/plain');
	file_put_contents( 'calendar.dat', serialize($_POST) );
	echo print_r($_POST);
	exit();
}

$c = unserialize(file_get_contents('calendar.dat'));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php /* http://www.timeanddate.com/calendar/custom.html?year=2010&month=1&months=3&country=30&typ=2&display=3&cols=0&fdow=1&hol=0&ctf=4&ctc=2&holmark=1&cdt=6&ccc=9&holm=1&hid=1&df=1 */ ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<?php $page_name = 'Calendar'; include("meta.php"); ?>

<script type="text/javascript" src="/breadcrumb.js"></script>
<link rel="stylesheet" href="/common.css" />

<style type="text/css">
@import url('calendar.css');

	.style4 {
		text-align: left;
	}
	.style5 {
		font-size: small;
	}
	.style6 {
		text-align: center;
		font-size: small;
	}
	.style7 {
		color: #F0AA31;
	}
	.style8 {
		text-align: center;
	}
	.style2 {
		background-color: #293828;
	}
	.style1 {
		background-color: #F0AA31;
	}
	.style3 {
		background-color: #CC0000;
	}
</style>
<?php echo $output_head_section; ?>
</head>

<body>
<div id="layout-page">
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
	<div id="layout-breadcrumb">
		<table id="table-breadcrumb" cellspacing="0" cellpadding="0">
			<tbody><tr>
				<td><a href="index.php"><img alt="Index" src="images_nav/button_01.gif" onmouseover="breadcrumb('button-1', false, true)" onmouseout="breadcrumb('button-1', false, false)" id="button-1" width="133" height="30" /></a></td>
				<td><a href="gallery.php"><img alt="Gallery" src="images_nav/button_02.gif" onmouseover="breadcrumb('button-2', false, true)" onmouseout="breadcrumb('button-2', false, false)" id="button-2" width="133" height="30" /></a></td>
				<td><a href="location.php"><img alt="Location" src="images_nav/button_03.gif" onmouseover="breadcrumb('button-3', false, true)" onmouseout="breadcrumb('button-3', false, false)" id="button-3" width="133" height="30" /></a></td>
				<td><a href="safety.php"><img alt="Safety" src="images_nav/button_04.gif" onmouseover="breadcrumb('button-4', false, true)" onmouseout="breadcrumb('button-4', false, false)" id="button-4" width="133" height="30" /></a></td>
				<td><a href="pricing.php"><img alt="Pricing" src="images_nav/button_05.gif" onmouseover="breadcrumb('button-5', false, true)" onmouseout="breadcrumb('button-5', false, false)" id="button-5" width="133" height="30" /></a></td>
				<td><a href="activities.php"><img alt="Activities" src="images_nav/button_06.gif" onmouseover="breadcrumb('button-6', false, true)" onmouseout="breadcrumb('button-6', false, false)" id="button-6" width="133" height="30" /></a></td>
			</tr>
		</tbody></table>
	</div>
	<div id="layout-left" class="style4">
		<div class="style8">
		<br />
		<strong><br />
			Opening Days Calendar<br /><br />
			</strong>
		<br />
			<div>
&nbsp;&nbsp;&nbsp; <span class="green"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
		OPEN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</strong></span><span class="style1"><span class="style2"><strong><br />
		</strong></span></span>
		<span class="style5">normal hours from 10am to 5pm</span><br />
<span class="style5">contact us for afterhours</span><br />
<br />
<br />
<span class="yellow"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;BOOKING&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; </strong>
		</span><strong><br />
		</strong>
			</div>
		<span class="style5"></div>
		<div class="style6">
			Subject to availability, the park can be open at your request for groups 
			from 5 people: <br />
			<br />
&nbsp;<span class="style7"><strong>Ph 09 459 4485</strong></span><br />
			<a href="mailto:info@adventureforest.co.nz">
			info@adventureforest.co.nz</a><br />
		</div>
		</span>
		<br />
		<br />
		<div class="style8">
			<strong>
		<br />
		</strong><span class="red"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
		CLOSED&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</strong></span>
		<div class="style6">or fully booked</div>
		<br />
				</div>
		<span style="color: rgb(124, 105, 49);">_</span>
		</div>
	<div id="layout-center" style="text-align: left">
		<br />
<?php echo $output_body_section; ?>
</div>
		<div id="layout-foot">&nbsp;</div>
</div>
<?php include("footer.php"); ?>
</body>

</html>
