
<?php 
if(isset($_POST['email'])) {	
    // EDIT THE 2 LINES BELOW AS REQUIRED
	$source = $_POST['source'];
	$email_to = "wdembiz@gmail.com, anastacia.brewer@gmail.com, dwightd@alumni.rice.edu, paul@boxit.biz";
   	$email_subject = "NEW LEED - SOURCE:".$source;
 	$return_message = "Thank you for contacting us. We will be in touch with you very soon.";

    function died($error) {
 
        // your error code can go here
        global $return_message;
 
        $return_message = "We are very sorry, but there were error(s) found with the form you submitted. "."\r\n"
 
        . "These errors appear below.<br /><br />"
 
        .$error."<br />"

        ."Please go back and fix these errors.<br /><br />";

		echo "testing"; 
        //die();
 
    }
 
     
 
    // validation expected data exists
 
    if(!isset($_POST['name']) ||
 
        !isset($_POST['email']) ||
 
        !isset($_POST['message'])) {
       
         $return_message = "We are very sorry, but there were error(s) found with the form you submitted. ".'\r\n' 
        . "Please go back and fix these errors.<br /><br />";
        die();
     
 
    }     
 
    $name = $_POST['name']; // required
 
    $email= $_POST['email']; // required
 
    $message = $_POST['message']; // required
 
     
 
    $error_message = "";
 
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
 
  if(!preg_match($email_exp,$email)) {
 
    $error_message .= 'The Email Address you entered does not appear to be valid.<br />';
 
  }
 
    $string_exp = "/^[A-Za-z .'-]+$/";
 
  if(!preg_match($string_exp,$name)) {
 
    $error_message .= 'The Name you entered does not appear to be valid.<br />';
 
  }
 
  if(strlen($message) < 1) {
 
    $error_message .= 'The Message you entered dont appear to be valid.<br />';
 
  }
 
  if(strlen($error_message) > 0) {
 
    died($error_message);
 
  }
 
    $email_message = "Form details below.\n\n";
 
     
 
    function clean_string($string) {
 
      $bad = array("content-type","bcc:","to:","cc:","href");
 
      return str_replace($bad,"",$string);
 
    }
 
    $email_message .= "Name: ".clean_string($name)."\n";
 
    $email_message .= "Email: ".clean_string($email)."\n";
 
    $email_message .= "Message:  ".clean_string($message)."\n";
     
 
// create email headers
 
$headers = 'From: '.$email."\r\n".
 
'Reply-To: '.$email."\r\n" .
 
'X-Mailer: PHP/' . phpversion();
 
@mail($email_to, $email_subject, $email_message, $headers);  

    // if((isset($_POST['name']) || 
    // isset($_POST['email']) ||
    // isset($_POST['message']) ||
    //  preg_match($email_exp,$email))
    // 	|| (preg_match($string_exp,$name))
    // 	 || !(strlen($message) < 1) || 
    // 	 !(strlen($error_message) > 0))
    // 	echo('Thank you for contacting us. We will be in touch with you very soon.');

}
/********************************************************/
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
				<li class="active"><a  href="index.html#home" target="new">Home</a></li>
				<li><a href="index.html" target="new" >Services</a></li>
		
				<li><a href="index.html#pricing"  target="new">Pricing</a></li>
				<li><a href="index.html#about">About</a></li>
				 <li><a href="#about">Partners</a></li> 
				<li ><a href="index.html#contact">Contact</a></li> 
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
 <!-- scroll_top_btn -->
		<script type="text/javascript" src="js/move-top.js"></script>
		<script type="text/javascript" src="js/easing.js"></script>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$(".scroll").click(function(event){		
				event.preventDefault();
				$('html,body').animate({scrollTop:$(this.hash).offset().top},1200);
			});
		});
	</script>

	<script type="text/javascript">

</script>

		 <a href="#" id="toTop" style="display: block;"><span id="toTopHover" style="opacity: 1;"></span></a>
</body>
</html>


