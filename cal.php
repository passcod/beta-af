<?php ob_start(); ?>


<?php ob_start(); ?>
function editCal() {
	function getColor(ele)
	{
		ele = $(ele);
		
		for ( c in colors )
		{
			if ( ele.hasClass(colors[c]) ) { return colors[c]; }
		}
		
		return false;
	}
	
	function getMonth(ele)
	{
		ele = $(ele);
		
		if ( ele.hasClass("month0") ) { return "0"; }
		else if ( ele.hasClass("month1") ) { return "1"; }
		else if ( ele.hasClass("month2") ) { return "2"; }
		else { return false; }
	}
	
	
	$(function() {
		$('#login_form').remove();
		$("#mct1 > tbody").append('<tr><td><form id="calendar_form" method="post" action="#"></form></td></tr>');
		$(".ca1").each(function(i) { $(this).find("td").addClass("month"+i); });
		$(".ca1 td").each(function() {
			if ( /[1-9]{1,2}/i.test( $(this).html() ) )
			{
				var val = getColor(this);
				var name = "day_" + getMonth(this) + "_" + $(this).text();
				$(this).attr("name", name);
				$("#calendar_form").append('<input type="hidden" name="'+name+'" value="'+val+'" id="calendar_input_'+name+'" />');
			}
		});
		$('.ca1 td').click(changeColour);
		
		$("#calendar_form").append('<button id="submit-calendar">Save</button><span id="cc-status" style="font-size: small">Loaded</span><br /><a href="javascript:location.reload(true);">exit</a>');
		$('#submit-calendar').click(submitCal);
	});
}

function changeColour()
{
	var t = $(this);
	var i = "calendar_input_"+t.attr("name");
	
	for ( c in colors )
	{
		c = parseInt(c);
		d = ( c+1 < colors.length ) ? c+1 : 0;
		if ( t.hasClass(colors[c]) )
		{
			$('#'+i).val(colors[d]);
			t.removeClass(colors[c]);
			t.addClass(colors[d]);
			return;
		}
	}
}

function submitCal()
{
	var params = $('#calendar_form').serializeArray();
	$.post(page_self, params, function(data) {
		$('#cc-status').text('written '+data+' bytes');
	}, 'text');
	return false;
}
<?php $good_script = ob_get_clean(); ?>
<?php ob_start();
?>
function editCal()
{
	$('#login_form').children().remove();
	$('#login_form').prepend('<p style=\"font-weight: bold; background-color: red;\">Wrong password - <a onclick=\"location.reload(true);\">try again</a></p>');
}
<?php
$bad_script = ob_get_clean();
?><?php
$pass = md5('ffff3696');
$c = 'notloaded';
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
elseif($_GET['data'] == 'md5')
{
	header('Content-type: text/javascript');
	echo $md5;
	exit();
}
elseif(!empty($_POST['day_0_1']))
{
	$written = file_put_contents( 'calendar.dat', base64_encode(serialize($_POST)) );
	echo $written;
	exit();
}
else
{
	$c = unserialize(base64_decode(file_get_contents('calendar.dat')));
}
?>
<?php if ( $c != 'notloaded' ) { ?>


<?php ob_start(); ?>
<link rel="stylesheet" type="text/css" href="calendar.css" />

<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
var page_self = "<?php echo $_SERVER['PHP_SELF']; ?>";

var colors = Array();
colors[0] = "red";
colors[1] = "yellow";
colors[2] = "green";

$(function() {
	$('#mct1').after("<a id='cc_init_link' style='color: #283939; font-size: x-small'>edit</a>");
	var ccil = $('#cc_init_link');
	ccil.click(function() {
		loadEdit();
		ccil.remove();
	});
});

function loadEdit()
{
	$('#mct1').after('<div id="login_form" style="font-family: sans-serif;"><span style="font-size: small;">Password: </span><input type="password" name="pass" class="pass" /><button id="butcont">Continue</button></div>');
	$('#butcont').click(loginCal);
	$('#login_form input.pass').focus().keypress(function(e) {
		if ( e.keyCode == 13 ) { loginCal(); }
	});
}

function loginCal()
{
	$.getScript('md5.jquery.js', function() {
		var param = $.md5( $('#login_form input.pass').val() );
		$("body").data('pass', param);
		$.getScript(page_self+'?data=script&pass='+param, function() { editCal(); });
	});
}
</script>
<?php $output_head_section = ob_get_clean(); ?>


<?php ob_start(); ?>
<!-- BEGIN CALENDAR -->

<table cellspacing="0" cellpadding="4" border="0" align="center" class="ct1 cl2 cp4 cc9 cd1 cf3 ci8 cu3 cj1" id="mct1">
	<tbody>
		<tr>
			<th>March</th>
			<td class="cz"></td>
			<th>April</th>
			<td class="cz"></td>
			<th>May</th>
		</tr>
		<tr>
			<td align="center" valign="top" class="cbm cba cbo">
				<table cellspacing="0" cellpadding="2" border="0" class="ca ca1">
					<tbody>
						<tr class="cl">
							<td class="brown">Mo</td>
							<td class="brown">Tu</td>
							<td class="brown">We</td>
							<td class="brown">Th</td>
							<td class="brown">Fr</td>
							<td class="brown">Sa</td>
							<td class="brown cr">Su</td>
						</tr>
						<tr>
							<td class="<?php echo $c['day_0_1']; ?>">1</td>
							<td class="<?php echo $c['day_0_2']; ?>">2</td>
							<td class="<?php echo $c['day_0_3']; ?>">3</td>
							<td class="<?php echo $c['day_0_4']; ?>">4</td>
							<td class="<?php echo $c['day_0_5']; ?>">5</td>
							<td class="<?php echo $c['day_0_6']; ?>">6</td>
							<td class="<?php echo $c['day_0_7']; ?> cr">7</td>
						</tr>
						<tr>
							<td class="<?php echo $c['day_0_8']; ?>">8</td>
							<td class="<?php echo $c['day_0_9']; ?>">9</td>
							<td class="<?php echo $c['day_0_10']; ?>">10</td>
							<td class="<?php echo $c['day_0_11']; ?>">11</td>
							<td class="<?php echo $c['day_0_12']; ?>">12</td>
							<td class="<?php echo $c['day_0_13']; ?>">13</td>
							<td class="<?php echo $c['day_0_14']; ?> cr">14</td>
						</tr>
						<tr>
							<td class="<?php echo $c['day_0_15']; ?>">15</td>
							<td class="<?php echo $c['day_0_16']; ?>">16</td>
							<td class="<?php echo $c['day_0_17']; ?>">17</td>
							<td class="<?php echo $c['day_0_18']; ?>">18</td>
							<td class="<?php echo $c['day_0_19']; ?>">19</td>
							<td class="<?php echo $c['day_0_20']; ?>">20</td>
							<td class="<?php echo $c['day_0_21']; ?> cr">21</td>
						</tr>
						<tr>
							<td class="<?php echo $c['day_0_22']; ?>">22</td>
							<td class="<?php echo $c['day_0_23']; ?>">23</td>
							<td class="<?php echo $c['day_0_24']; ?>">24</td>
							<td class="<?php echo $c['day_0_25']; ?>">25</td>
							<td class="<?php echo $c['day_0_26']; ?>">26</td>
							<td class="<?php echo $c['day_0_27']; ?>">27</td>
							<td class="<?php echo $c['day_0_28']; ?> cr">28</td>
						</tr>
						<tr>
							<td class="<?php echo $c['day_0_29']; ?>">29</td>
							<td class="<?php echo $c['day_0_30']; ?>">30</td>
							<td class="<?php echo $c['day_0_31']; ?>">31</td>
							<td class="brown"></td>
							<td class="brown"></td>
							<td class="brown"></td>
							<td class="brown cr"></td>
						</tr>
						<tr class="cb">
							<td class="brown"></td>
							<td class="brown"></td>
							<td class="brown"></td>
							<td class="brown"></td>
							<td class="brown"></td>
							<td class="brown"></td>
							<td class="brown cr"></td>
						</tr>
					</tbody>
				</table>
			</td>
			<td class="cz"></td>
			<td align="center" valign="top" class="cbm cba cbo">
				<table cellspacing="0" cellpadding="2" border="0" class="ca ca1">
					<tbody>
						<tr class="cl">
							<td class="brown">Mo</td>
							<td class="brown">Tu</td>
							<td class="brown">We</td>
							<td class="brown">Th</td>
							<td class="brown">Fr</td>
							<td class="brown">Sa</td>
							<td class="brown cr">Su</td>
						</tr>
						<tr>
							<td class="brown"></td>
							<td class="brown"></td>
							<td class="brown"></td>
							<td class="<?php echo $c['day_1_1']; ?>">1</td>
							<td class="<?php echo $c['day_1_2']; ?>">2</td>
							<td class="<?php echo $c['day_1_3']; ?>">3</td>
							<td class="<?php echo $c['day_1_4']; ?> cr">4</td>
						</tr>
						<tr>
							<td class="<?php echo $c['day_1_5']; ?>">5</td>
							<td class="<?php echo $c['day_1_6']; ?>">6</td>
							<td class="<?php echo $c['day_1_7']; ?>">7</td>
							<td class="<?php echo $c['day_1_8']; ?>">8</td>
							<td class="<?php echo $c['day_1_9']; ?>">9</td>
							<td class="<?php echo $c['day_1_10']; ?>">10</td>
							<td class="<?php echo $c['day_1_11']; ?> cr">11</td>
						</tr>
						<tr>
							<td class="<?php echo $c['day_1_12']; ?>">12</td>
							<td class="<?php echo $c['day_1_13']; ?>">13</td>
							<td class="<?php echo $c['day_1_14']; ?>">14</td>
							<td class="<?php echo $c['day_1_15']; ?>">15</td>
							<td class="<?php echo $c['day_1_16']; ?>">16</td>
							<td class="<?php echo $c['day_1_17']; ?>">17</td>
							<td class="<?php echo $c['day_1_18']; ?> cr">18</td>
						</tr>
						<tr>
							<td class="<?php echo $c['day_1_19']; ?>">19</td>
							<td class="<?php echo $c['day_1_20']; ?>">20</td>
							<td class="<?php echo $c['day_1_21']; ?>">21</td>
							<td class="<?php echo $c['day_1_22']; ?>">22</td>
							<td class="<?php echo $c['day_1_23']; ?>">23</td>
							<td class="<?php echo $c['day_1_24']; ?>">24</td>
							<td class="<?php echo $c['day_1_25']; ?> cr">25</td>
						</tr>
						<tr>
							<td class="<?php echo $c['day_1_26']; ?>">26</td>
							<td class="<?php echo $c['day_1_27']; ?>">27</td>
							<td class="<?php echo $c['day_1_28']; ?>">28</td>
							<td class="<?php echo $c['day_1_29']; ?>">29</td>
							<td class="<?php echo $c['day_1_30']; ?>">30</td>
							<td class="brown"></td>
							<td class="brown cr"></td>
						</tr>
						<tr class="cb">
							<td class="brown"></td>
							<td class="brown"></td>
							<td class="brown"></td>
							<td class="brown"></td>
							<td class="brown"></td>
							<td class="brown"></td>
							<td class="brown cr"></td>
						</tr>
					</tbody>
				</table>
			</td>
			<td class="cz"></td>
			<td align="center" valign="top" class="cbm cba cbo">
				<table cellspacing="0" cellpadding="2" border="0" class="ca ca1">
					<tbody>
						<tr class="cl">
							<td class="brown">Mo</td>
							<td class="brown">Tu</td>
							<td class="brown">We</td>
							<td class="brown">Th</td>
							<td class="brown">Fr</td>
							<td class="brown">Sa</td>
							<td class="brown cr">Su</td>
						</tr>
						<tr>
							<td class="brown"></td>
							<td class="brown"></td>
							<td class="brown"></td>
							<td class="brown"></td>
							<td class="brown"></td>
							<td class="<?php echo $c['day_2_1']; ?>">1</td>
							<td class="<?php echo $c['day_2_2']; ?> cr">2</td>
						</tr>
						<tr>
							<td class="<?php echo $c['day_2_3']; ?>">3</td>
							<td class="<?php echo $c['day_2_4']; ?>">4</td>
							<td class="<?php echo $c['day_2_5']; ?>">5</td>
							<td class="<?php echo $c['day_2_6']; ?>">6</td>
							<td class="<?php echo $c['day_2_7']; ?>">7</td>
							<td class="<?php echo $c['day_2_8']; ?>">8</td>
							<td class="<?php echo $c['day_2_9']; ?> cr">9</td>
						</tr>
						<tr>
							<td class="<?php echo $c['day_2_10']; ?>">10</td>
							<td class="<?php echo $c['day_2_11']; ?>">11</td>
							<td class="<?php echo $c['day_2_12']; ?>">12</td>
							<td class="<?php echo $c['day_2_13']; ?>">13</td>
							<td class="<?php echo $c['day_2_14']; ?>">14</td>
							<td class="<?php echo $c['day_2_15']; ?>">15</td>
							<td class="<?php echo $c['day_2_16']; ?> cr">16</td>
						</tr>
						<tr>
							<td class="<?php echo $c['day_2_17']; ?>">17</td>
							<td class="<?php echo $c['day_2_18']; ?>">18</td>
							<td class="<?php echo $c['day_2_19']; ?>">19</td>
							<td class="<?php echo $c['day_2_20']; ?>">20</td>
							<td class="<?php echo $c['day_2_21']; ?>">21</td>
							<td class="<?php echo $c['day_2_22']; ?>">22</td>
							<td class="<?php echo $c['day_2_23']; ?> cr">23</td>
						</tr>
						<tr>
							<td class="<?php echo $c['day_2_24']; ?>">24</td>
							<td class="<?php echo $c['day_2_25']; ?>">25</td>
							<td class="<?php echo $c['day_2_26']; ?>">26</td>
							<td class="<?php echo $c['day_2_27']; ?>">27</td>
							<td class="<?php echo $c['day_2_28']; ?>">28</td>
							<td class="<?php echo $c['day_2_29']; ?>">29</td>
							<td class="<?php echo $c['day_2_30']; ?> cr">30</td>
						</tr>
						<tr class="cb">
							<td class="<?php echo $c['day_2_31']; ?>">31</td>
							<td class="brown"></td>
							<td class="brown"></td>
							<td class="brown"></td>
							<td class="brown"></td>
							<td class="brown"></td>
							<td class="brown cr"></td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>

<!-- END CALENDAR -->
<?php $output_body_section = ob_get_clean();

include('calendar.php');

 } ?>


<?php ob_end_flush(); ?>
