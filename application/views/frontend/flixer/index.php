
<!doctype html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<?php

	if($page_name=='playbroadcast')
	{
	?>
	<title>Seen this on Happy Watch 99 yet?</title>
	
	<!--
	<meta property="og:url" content="https://happywatch99.com/" />
	<meta property="og:description" content="Site description" />
	<meta property="og:image" itemprop="image" content="https://happywatch99-cache.cdnvideo.ru/happywatch99/broadcast_image/img_2135_1.jpg" />
	
	-->
	
<meta property="og:title" content="How to change the address bar color in Chrome, Firefox, Opera, Safari" />
<meta property="og:description" content="How to change the address bar color in Chrome, Firefox, Opera, Safari" />
<meta property="og:url" content="https://happywatch99.com/index.php?home/playbroadcast/2351" />
<meta property="og:image" content="http://webdevelopmentscripts.com/post-images/685b-change-browser-address-bar-color-chrome-android.jpeg" />
	
	
	
	<?php
	}
	elseif($page_name=='privacypolicy')
	{
	?>
<meta property="fb:app_id"      content="339607746645444" />
<meta property="og:type"        content="article" />	
<meta property="og:title" content="Privacy Policy | Happy Watch 99" />
<meta property="og:description" content="Thank you for choosing to be part of our community at Happy Watch 99" />
<meta property="og:url" content="https://happywatch99.com/index.php?privacy_policy" />
<meta property="og:image" content="https://happywatch99.com//assets/global/logo.png" />
<meta property="og:site_name" content="happywatch99"/>	
	

	
	<?php	
	}	
	else
	{
	?>
	
	<title><?php echo $page_title;?> | <?php echo $this->db->get_where('settings',array('type'=>'site_name'))->row()->description;?></title>
	
	<?php
	}
	?>
	
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script data-ad-client="ca-pub-6732371909634956" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    
    <link rel="shortcut icon" href="<?php echo base_url();?>assets/global/favicon.ico">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/frontend/' . $selected_theme;?>/bootstrap.css" media="screen">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/frontend/' . $selected_theme;?>/custom.min.css">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/frontend/' . $selected_theme;?>/fontawesome/css/font-awesome.min.css">
    <script src="<?php echo base_url() . 'assets/frontend/' . $selected_theme;?>/jquery-1.10.2.min.js" ></script>
    <script src="<?php echo base_url() . 'assets/frontend/' . $selected_theme;?>/bootstrap.min.js" ></script>
    <script src="<?php echo base_url() . 'assets/' ;?>deeplink-to-native-app.js" ></script>
    <script>
	
		//document.addEventListener("contextmenu", function (e){
   // e.preventDefault();
//}, false)
		
	</script>	
  
  <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-NQVBPJW5B9"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-NQVBPJW5B9');
</script>
  
  
    
    
    <style>
		.black_text{color:#000; background-color: #f3f3f3;}
		.blue_text{color: #0080ff;}
	</style>
</head>
<?php
$bg_color = "#000";
	

	
if ($page_name == 'signup' || $page_name == 'signin' || $page_name == 'faq' ||
		$page_name == 'termsofuse' || $page_name == 'privacypolicy' || $page_name == 'refundpolicy' ||
   			$page_name == 'youraccount' || $page_name== 'billinghistory'||
   				$page_name == 'emailchange' || $page_name== 'passwordchange'||
   					$page_name == 'cancelplan' || $page_name == 'purchaseplan'||
   						$page_name == 'purchasestripe')
    $bg_color = "#f3f3f3";
?>
<body style="background-color:<?php echo $bg_color;?>;">
    
    
  <!--  
    <link itemprop="thumbnailUrl" href="https://happywatch99-cache.cdnvideo.ru/happywatch99/broadcast_image/img_2135_1.jpg">

<span itemprop="thumbnail" itemscope itemtype="http://schema.org/ImageObject">
<link itemprop="url" href="https://happywatch99-cache.cdnvideo.ru/happywatch99/broadcast_image/img_2135_1.jpg">
</span>
    -->
    
   
    
	<?php include ($page_name . '.php');?>
</body>
</html>
