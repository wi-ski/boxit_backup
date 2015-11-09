<?php 

	      date_default_timezone_set('America/Los_Angeles');
	      $date = date("Y-m-d\TH:i:s\Z");
	//	echo "-------------------------------";
	//      print_r($date);

 	$return_message = "Thank you for purchasing. We will be in touch with you very soon.";
 	$result_array = array();

//if(isset($_POST['payment']))
//{
 	// parsing Exp Date
 	$expirationYear = substr($_POST['expDate'],0,4);
	$expirationDay = substr($_POST['expDate'],-2,2);

	/************* Connect Dabatase ************/

	$username = "root";
	$password = "qwerASDF1";
	$hostname = "localhost"; 
	$database = "db_shipment";
	$tableName = "14sep";
	$con = mysqli_connect($hostname,$username,$password,$database) or die("Failed to connect to MySQL. Please contact the administrator.");

	// shipping info with SQL security for escaping protected
	$ship_firstname = mysqli_real_escape_string($con, $_POST['ship_firstname']);
	$ship_lastname = mysqli_real_escape_string($con, $_POST['ship_lastname']);
	$ship_email = mysqli_real_escape_string($con, $_POST['ship_email']);
	$ship_comp = mysqli_real_escape_string($con, $_POST['ship_comp']);
	$ship_phone= mysqli_real_escape_string($con, $_POST['ship_phone']);
	$ship_address = mysqli_real_escape_string($con, $_POST['ship_address']);

	/****************** Paypal Pro Direct Payment using cURL ********************/
	$api_version  = '117';
	$api_endpoint = 'https://api-3t.paypal.com/nvp'; //'https://api-3t.paypal.com/nvp';
	$api_username = 'dwight_api1.boxit.biz';
	$api_password = '7T67K5WPX9NGRCDH';
	$api_signature= 'AFcWxV21C7fd0v3bYYYRCpSSRl31A1aE41sKtBTsRmtGifiBg8IZqFhy';
	$nvp_string = '';

    // billing info
	$card_type = $_POST['cardType'];
	$card_number = $_POST['cardNum'];
	$exp_date = $expirationDay.$expirationYear; 
	$card_cvv = $_POST['CVV'];
	$firstname= $_POST['firstName'];
	$lastname = $_POST['lastName'];
	$stree = $_POST['address'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zip = $_POST['zip'];


	$country = 'US';//$_POST['source'];
	$currencycode = 'USD';//$_POST['source'];
	$amount = '1.00';//_POST['source'];

	$request_params = array
                    (
                    'METHOD' => 'CreateRecurringPaymentsProfile', 
                    'USER' => $api_username, 
                    'PWD' => $api_password, 
                    'SIGNATURE' => $api_signature, 
                    'VERSION' => $api_version, 
                    'PAYMENTACTION' => 'Sale',                   
                    'IPADDRESS' => $_SERVER['REMOTE_ADDR'],
                    'CREDITCARDTYPE' => $card_type, 
                    'ACCT' => $card_number,                        
                    'EXPDATE' => $exp_date,           
                    'CVV2' => $card_cvv, 
                    'FIRSTNAME' => $firstname, 
                    'LASTNAME' => $lastname, 
                    'STREET' => $street, 
                    'CITY' => $city, 
                    'STATE' => $state,                     
                    'COUNTRYCODE' => 'US', 
                    'ZIP' => $zip, 
                    'AMT' => '1.00', 
                    'CURRENCYCODE' => 'USD', 
                    'DESC' => 'Testing Payments Pro',
                    'BILLINGPERIOD' => 'Month',
                    'BILLINGFREQUENCY'=> 1,
                    'MAXFAILEDPAYMENTS'=> 2,
		    'PROFILESTARTDATE' => $date
                    );
	foreach($request_params as $var=>$val)
	{
	    $nvp_string .= '&'.$var.'='.urlencode($val);    
	}
	//echo $_SERVER['REMOTE_ADDR'];
	// Send NVP string to PayPal and store response
	$curl = curl_init();
	        curl_setopt($curl, CURLOPT_VERBOSE, 1);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	        curl_setopt($curl, CURLOPT_URL, $api_endpoint);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $nvp_string);
	 
	$result = curl_exec($curl);     
	curl_close($curl);
	//	echo $result;
	$result_array = NVPToArray($result);
	print_r($result_array);
	if ($result_array[ACK] == 'Success')
	{
		$return_message = 'Thank you for purchasing. We will be in touch with you very soon.';
		// success msg 7: Array ( [TIMESTAMP] => 2014-09-20T20:13:17Z [CORRELATIONID] => 724df3e985066 [ACK] => Success [VERSION] => 117 [BUILD] => 12932421 [AMT] => 1.00 [CURRENCYCODE] => USD [AVSCODE] => N [CVV2MATCH] => M [TRANSACTIONID] => 0YV835558D4154636 )
		
		$sql="INSERT INTO $tableName (timeStamp, firstName, lastName, company, phone, address, email, transactionID, correlationID, buildID, currency, amount)
	 		VALUES ('$result_array[TIMESTAMP]', '$ship_firstname', '$ship_lastname','ship_comp','ship_phone','ship_address','ship_email','$result_array[TRANSACTIONID]','$result_array[CORRELATIONID]','$result_array[BUILD]','$result_array[CURRENCYCODE]','$result_array[AMT]');"
			or die('something wrong');
		if (!mysqli_query($con,$sql)) {
		  die('Error: ' . mysqli_error($con).' Please Contact the administrator.');
		}
		//echo "successfully created";
	}
	
	else
	{
		$return_message = 'Please enter the purchasing information in the correct format or contact us for help.<br><br>Error Message: '.
	$result_array[L_SHORTMESSAGE0].'-'.str_replace("This transaction cannot be processed. ", "",$result_array[L_LONGMESSAGE0]);
	}

	// Helper Function to convert NTP string to an array
	// Parse the API response
	// optional: $nvp_response_array = parse_str($result);
	function NVPToArray($NVPString)
	{
	    $proArray = array();
	    while(strlen($NVPString))
	    {
	        // name
	        $keypos= strpos($NVPString,'=');
	        $keyval = substr($NVPString,0,$keypos);
	        // value
	        $valuepos = strpos($NVPString,'&') ? strpos($NVPString,'&'): strlen($NVPString);
	        $valval = substr($NVPString,$keypos+1,$valuepos-$keypos-1);
	        // decoding the respose
	        $proArray[$keyval] = urldecode($valval);
	        $NVPString = substr($NVPString,$valuepos+1,strlen($NVPString));
	    }
	    return $proArray;
	}

//} 
//else die("please enter correct info");   


?>





<!DOCTYPE HTML>
<html>
<head>
<title>Boxit - We can help!</title>

<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/social_mail.css" rel="stylesheet" type="text/css" media="all" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" type="text/css" href="css/magnific-popup1.css">
<link rel="stylesheet" type="text/css" href="css/prettyPhoto.css">
	<!--  jquery plguin -->
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<!--start slider -->
	    <link rel="stylesheet" href="css/fwslider.css" media="all">
		<script src="js/jquery-ui.min.js"></script>
		<script src="js/css3-mediaqueries.js"></script>
		<script src="js/fwslider.js"></script>
	<!--end slider -->
	 <script type="text/javascript">
			$(document).ready(function() {
			
				var defaults = {
		  			containerID: 'toTop', // fading element id
					containerHoverID: 'toTopHover', // fading element hover id
					scrollSpeed: 1200,
					easingType: 'linear' 
		 		};
				
				
				$().UItoTop({ easingType: 'easeOutQuart' });
				
			});                                   
		</script>
		<!-- Add fancyBox light-box -->
		<script src="js/jquery.magnific-popup.js" type="text/javascript"></script>
				<script>
					$(document).ready(function() {
						$('.popup-with-zoom-anim').magnificPopup({
							type: 'inline',
							fixedContentPos: false,
							fixedBgPos: true,
							overflowY: 'auto',
							closeBtnInside: true,
							preloader: false,
							midClick: true,
							removalDelay: 300,
							mainClass: 'my-mfp-zoom-in'
					});
				});
		</script>
		<!-- //End fancyBox light-box -->

</head>
<body>
<!-- start header -->
<div class="header_bg">
<div class="wrap">
	<div class="header">
		<div class="logo">
			<h1><a href="index.html"><img src="images/boxitsub_logo.png" alt=""/></a></h1>
		</div>
		<div class="h_right">
			<ul class="menu">		
				<li class="active"><a  href="#home">Home</a></li>
				<li><a href="index.html#services" class="scroll">Services</a></li>
		
				<li><a href="index.html#pricing" class="scroll">Pricing</a></li>
				<li><a href="index.html#about" class="scroll">About</a></li>
				<!-- <li><a href="#about" class="scroll">Partners</a></li> -->
				<li ><a href="index.html#contact"  class="scroll">Contact</a></li>
				<li ><a href="investors.html"  target="new">Investors</a></li>
	            <li class="last"> <a href="careers.html"  target="new">Careers</a></li>
			</ul>
			<div id="sb-search" class="sb-search">
				<form>
					<input class="sb-search-input" placeholder="Enter your search term..." type="text" value="" name="search" id="search">
					<input class="sb-search-submit" type="submit" value="">
					<span class="sb-icon-search"></span>
				</form>
			</div>
			<script src="js/classie.js"></script>
			<script src="js/uisearch.js"></script>
			<script>
				new UISearch( document.getElementById( 'sb-search' ) );
			</script>
			<!-- start smart_nav * -->
	        <nav class="nav">
	            <ul class="nav-list">
	                <li class="nav-item"><a  href="#home">Home</a></li>
	                <li class="nav-item"><a href="#services" class="scroll">Services</a></li>
	           
	                <li class="nav-item"><a href="#pricing" class="scroll">Pricing</a></li>
	                <li class="nav-item"><a href="#about" class="scroll">About</a></li>
	                <li class="nav-item"><a href="#contact"  class="scroll">Contact</a></li>
	                <li class="nav-item"><a href="#about" class="scroll">Investors</a></li>
	                <li class="nav-item"><a href="#contact"  class="scroll">Careers</a></li>
	                <div class="clear"></div>
	            </ul>
	        </nav>
	        <script type="text/javascript" src="js/responsive.menu.js"></script>
			<!-- end smart_nav * -->
		</div>
		<div class="clear"></div>
	</div>
</div>
</div>

<!----------- message3 ------------>
<div class="social_mail">
	<div class="wrap">
		<style>
			background: url("../images/smallshutter/yogabeach.jpg") no-repeat;
		</style>
		<p> <font size="6">
			<?php echo $return_message ?>
		</font></p>
		<br>
		<br>
		<p> <font size="4">Follow Us On Social Networks</font> </p>
	<!---start-social-icons---->
							<div class="social-icons-set">
								<ul>
									<li><a class="facebook" href="https://www.facebook.com/profile.php?id=100008197922713&fref=ts" target="_blank"> </a></li>
									<li><a class="twitter" href="https://twitter.com/BoxItBob" target="_blank"> </a></li>
									<!-- <li><a class="vimeo" href="#"> </a></li>
									<li><a class="rss" href="#"> </a></li>
									<li><a class="gplus" href="#"> </a></li> -->
									<li><a class="pin" href="#"> </a></li>
									<div class="clear"> </div>
								</ul>
								<div class="clear"> </div>
							</div>					
							<!---//End-social-icons----> 
							<div class="clear"> </div>
</div>
</div>
<div class="footer-bottom">
	<div class="wrap">
		<div class="image">
			<a href="index.html"><img src="images/boxitsub_logo.png" alt=""></a>
		</div>	
		<div class="copy-right">
			
		</div>	
		 <div class="clear"></div>
	</div>
</div>		
</body>
</html>
