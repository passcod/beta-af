<?php

/**
 * @author		passcod
 * @version		9.51.3
 * @license		CC-GPL
 *
 * @package		gift-voucher-order
 * @link		template.php
 */

/*******************************************
*************** CONFIG *********************
*******************************************/

// enter here YOUR address.
$local_email = 'info@adventureforest.co.nz';


// Paste here your details from reCaptcha.
$publickey = "6Le5FggAAAAAAGU_fJD_BBeyzsmq40N6APMB5me9";
$privatekey = "6Le5FggAAAAAAB7cfWi8jKWOeqqxqreWuJGp8IoI";

/*******************************************
*************** /CONFIG ********************
*******************************************/

$done = false; // so we know if the mail has been sent.

require_once('recaptchalib.php');

// the following arrays contain (nearly) all the information to fetch data and build the orders.
// unfortunately, it is not used by all the script, and some text is still hard-coded within.

$opts = array('full_a', 'full_c', 'full_f', 'adv_a', 'adv_c', 'adv_f', 'disc_a', 'disc_c', 'disc_f');
$cost = array(32, 22, 95, 25, 17, 75, 18, 12, 50);
$desc = array(
				array('Full', 'Adult'),
				array('Full', 'Child'),
				array('Full', 'Family'),
			
				array('Adventure', 'Adult'),
				array('Adventure', 'Child'),
				array('Adventure', 'Family'),
				
				array('Discovery', 'Adult'),
				array('Discovery', 'Child'),
				array('Discovery', 'Family')
			);

$sig = ''.
		"\n\n---\n".
		"Adventure Forest\n".
		"4 Huanui Rd\n".
		"RD3 Glenbervie\n".
		"Whangarei 0173\n".
		"Ph 09 459 4485\n".
		"www.adventureforest.co.nz\n";


if ( $_POST['mail'] == 'send' ) // if form was posted...
{
	// set some variables.
	
	$error = '';
	$sum = array();
	$msg = array();
	
	
	// for the $test vars:
	// true => test passed
	// false => test failed
	
	/* for local debug / dev, comment from there ... */
	
	if ( !empty($_POST["recaptcha_response_field"]) ) // test the recaptcha response
	{
		$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

		if ($resp->is_valid)
		{
			$test['captcha'] = true;
		}
		else
		{
			$test['captcha'] = false;
			$error .= "You failed the Human Verification.<br />\n";
		}
	}
	else
	{
		$error .= "You failed the Human Verification.<br />\n";
		$test['captcha'] = false;
		$test['human'] = false; // i'm not really sure if i do need that anymore. Have to check the code.
	}
	
	/* ... to here. */
	
	// name
	
	if ( !empty($_POST['name']) )
	{
		$test['name'] = true;
		$msg['name'] = $_POST['name'];
	}
	else
	{
		$test['name'] = false;
		$error .= "You haven't entered a name.<br />\n";
	}
	
	
	// email
	
	if ( !empty($_POST['email']) )
	{
		$test['email'] = true;
		$msg['email'] = $_POST['email'];
	}
	else
	{
		$test['email'] = false;
		$error .= "You haven't entered an email address.<br />\n";
	}
	
	
	// postal address
	
	if ( !empty($_POST['postal']) )
	{
		$test['postal'] = true;
		$msg['postal'] = $_POST['postal'];
	}
	else
	{
		$test['postal'] = false;
		$error .= "You haven't entered a postal address.<br />\n";
	}
	
	
	// payment
	
	if ( !empty($_POST['payment']) )
	{
		$test['payment'] = true;
		$msg['payment'] = $_POST['payment'];
	}
	else
	{
		$test['payment'] = false;
		$error .= "You haven't chosen a payment method.<br />\n";
	}
	
	
	// option
	
	/**
	 * Tests the POST variable given by its name for emptiness or zero-equality
	 * 
	 * @param string $name The name of the POST variable to be tested
	 * @return bool False if $name is empty or equal to zero, true otherwise
	 */
	function testOpt($name) { if ( empty($_POST[$name]) || $_POST[$name] == 0 ) { return false; } else { return true; } }
	
	if ( 	// if none of the order fields are set to anything else than 0...
			!testOpt('full_a') &&
			!testOpt('full_c') &&
			!testOpt('full_f') &&
			!testOpt('adv_a') &&
			!testOpt('adv_c') &&
			!testOpt('adv_f') &&
			!testOpt('disc_a') &&
			!testOpt('disc_c') &&
			!testOpt('disc_f')
		)
	{
		$test['opt'] = false;
		$error .= "You haven't ordered anything.";
	}
	else
	{
		$test['opt'] = true;
		foreach ( $opts as $k => $a ) { if ( testOpt($a) ) { $msg[$a] = $_POST[$a]; /* retrieve data ... */ } else { $msg[$a] = 0; /* ... or set the default. */ } $sum[$a] = $cost[$k] * $msg[$a]; /* now calculate the cost */ }
		unset($a); unset($k); // so as not to mess up my arrays
		$sum['total'] = 0; $msg['total'] = 0; // set up defaults...
		foreach ( $opts as $a ) { $sum['total'] += $sum[$a]; $msg['total'] += $msg[$a]; /* ... and start summing up. */ }
	}
	
	$valid = true;
	foreach ( $test as $key => $value ) // every $test[] must be true
	{
		if ( $value == true && $valid == true )
		{
			$valid = true;
		}
		else
		{
			$valid = false; // i COULD break here...
		}
	}
	
	if ($valid)
	{
		
		###############################
		######## MAIL TO LOCAL ########
		###############################

		/**
		 * Lists the orders in a nicely formatted (plain text) 'table',
		 * with dots between the two columns. Takes its data from $desc
		 * and $msg.
		 * 
		 * @param string $name Long description of the order
		 * @param array $descx This value is $desc[x] where x is the name's corresponding key (could probably get that straight from $opts)
		 * @param array $price This must be $cost[x] where x is the name's corresponding key (could probably get that straight from $opts)
		 * @param array $mess This must be $msg
		 * @return string Returns the formatted 'table' row if an order was made, or an empty string
		 */
		function listOrders($name, $descx, $price, $mess)
		{
			if ( $mess[$name] > 0 ) // only display if an order was made
			{
				$n = 40;
				$str = $descx[0].' Pass '.$descx[1] . ' at $'.$price.' each: ';
				$dots = $n - strlen($str); // calculate the no of dots required to fill up $n chars
				return $str . str_repeat('.', $dots) . ' ' . $mess[$name] . "\n";
			}
			else
			{
				return '';
			}
		}
		
		/**
		 * getIP
		 * Attempts to determine the real ip of the client machine
		 *
		 * @return string Client's IP
		 */
		function getIp()
		{
			if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
			{
				$ip=$_SERVER['HTTP_CLIENT_IP'];
			}
			elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
			{
				$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			else
			{
				$ip=$_SERVER['REMOTE_ADDR'];
			}
			return $ip;
		}
		
		// Unique ID for reference. Auto-increment from a file (no db).
		$file = file_get_contents("last-id");
		$file = (int) $file;
		$file++;
		$file = str_repeat('0', (6 - strlen($file))) . $file;
		file_put_contents("last-id", $file);
		$name_key = $file;
		unset($file);
		// Make a log file.
		$file = file_get_contents("ids.log");
		$file .= "\n".$name_key."  -  ".getIp()."  -  ".$msg['name']."   ".$msg['email'];
		file_put_contents("ids.log", $file);
		unset($file);
		
		// now build up body of message
		
		$message = 'Name: '.$msg['name']."\n\n\n";

		$message .= "Snail Mail: \n\n".$msg['postal']."\n\n";

		// use prev func to quickly build orders list.
		
		for ( $i = 0; $i < 9; $i++ ) { $message .= listOrders($opts[$i], $desc[$i], $cost[$i], $msg); }
		
		$message .= "\n\n\n";
		
		$message .= 'Total Items: '.$msg['total']."\n\n";

		$message .= 'Payment to be received: $'.$sum['total']."\n";
		
		$pay_met = $msg['payment'] == 'cheque' ? 'cheque' : 'direct banking';
		
		$message .= 'Payment method: '.$pay_met.".\n\n\n\nID:".$name_key;


		// Send mail -- BEGIN


		$to = $local_email; // that's our copy
		$from = $msg['email']; // we need that address, there is a good place to put it

		$subject = "Gift Voucher Order from " . $msg['name'];

		$headers = "From: ".$from."\r\nReply-To: ".$from;

		$headers .= "\r\nContent-Type: text/plain;";


		$mail_sent = mail( $to, $subject, $message, $headers );


		// Send mail -- END



		###############################
		######## MAIL TO OTHER ########
		###############################
		unset($message); // otherwise it might 'duplicate' the message (with the one above)
		
		// from there, as above.
		
		$message = "Hi ".$msg['name'].",\n\nThank you very much for your order:\n\n"; 

		for ( $i = 0; $i < 9; $i++ ) { $message .= listOrders($opts[$i], $desc[$i], $cost[$i], $msg); }

		$message .= 'Total Items: '.$msg['total']."\n\n";

		$message .= 'Payment to be made: $'.$sum['total']."\n\n";
		
		if ( $msg['payment'] == 'cheque' ) { $how = "Please send payment by cheque to:\n\nAdventure Forest\n4, Huanui Rd\nGlenbervie\n0173 Whangarei"; }
		else if ( $msg['payment'] == 'direct' ) { $how = "Please pay by direct banking to the following account:\n12 3115 0183090 00"; }
		
		$message .= $how."\n\n";
		
		$message .= "Your order will be sent as soon as we receive the payment, by post, with no extra fee.\n\n\nSee you soon at Adventure Forest!";

		// signature
		$message .= $sig . "\n" . $name_key;
		// Send mail -- BEGIN

		$to = $msg['email'];
		$from = $local_email;
		

		$subject = "Adventure Forest - Gift Voucher Order - Reference email";

		$headers = "From: ".$from."\r\nReply-To: ".$from;

		$headers .= "\r\nContent-Type: text/plain;";


		$mail_sent = mail( $to, $subject, $message, $headers );
		
		
		
		$done = true; // now the mail's been sent, say so.
		
		// display a copy of the client's message on the screen.
		
		
		$display = $mail_sent ? "<p><span style='font-family: sans-serif;'><b><i>This is a copy of the email we sent you:</i></b></span>\n\n\n\n" . $message : "Failed to send.\nHit the back button of your browser and try again.\nIf you still have problems, email to info@adventureforest.co.nz";
		include("template.php");

		// Send mail -- END
	}
	else
	{
		include('template.php'); // if some fields were not completed, redisplay the form page
	}
}
else // if no form data has been sent (first view).
{
	include('template.php'); // display the form page.
}
?>