<?php
 ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Connection: close");

/*
if (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') && !strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')) {
	//$browser = 'Safari';
	
	echo "**********************************";
	
}

exit;
*/











$connection=mysqli_connect('localhost','happywat_netflix','TIT3ZK#~A$BJ','happywat_netflix');

mysqli_set_charset($connection,"utf8mb4");

///mysqli_select_db($connection,'amcappdbs');


$broadcast_id=$_REQUEST['broadcast_id'];

$sql_main="SELECT * FROM broadcast_details where id='".$broadcast_id."'" ;
$broadcast_main_coll = mysqli_query($connection,$sql_main );
$broadcast_data=mysqli_fetch_array($broadcast_main_coll);

$user_id=$broadcast_data['user_id'];

$broadcast_img=$broadcast_data['broadcast_img'];
$broadcast_video_thumbnail=$broadcast_data['broadcast_video_thumbnail'];


$content_description=$broadcast_data['content_description'];

$full_video_thumb_url=$broadcast_data['full_video_thumb_url'];

if($content_description=='UI Live' and !empty($full_video_thumb_url) )
{

//$array_live_video_thumb=explode(',', $full_video_thumb_url);
    
  //  print_r($array_live_video_thumb);
    
     $images[]='https://' . $_SERVER['SERVER_NAME'].'/assets/global/channel_thumb/'.$full_video_thumb_url;
    
}
elseif(!empty($broadcast_img))
{
    $array_broadcast_img=explode(',', $broadcast_img);
    
     foreach($array_broadcast_img as $img)
    {
        
          
           if(!empty($img))
            {
                $images[]='https://' . $_SERVER['SERVER_NAME'].'/assets/global/broadcast/images/'.$img;
            }
                
    }            
        
}
elseif(!empty($broadcast_video_thumbnail))
{
    //$broadcast_video_thumbnail=$broadcast_data['broadcast_video_thumbnail'];
    $array_video_thumb= explode(',', $broadcast_video_thumbnail);
    
    foreach($array_video_thumb as $thumb )
    {
       if(!empty($thumb))
       {
        
       
        
        $images[]='https://' . $_SERVER['SERVER_NAME'].'/assets/global/broadcast/video_thumb/'.$thumb;
       }
    }


    
    
}





?>

<!doctype html>
<html>
<head>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf8mb4">
    <meta http-equiv="expires" content="Sun, 01 Jan 2014 00:00:00 GMT"/>
<meta http-equiv="pragma" content="no-cache" />
    
	<title>Play Broadcast | Happy Watch 99</title>
	
	
	<!--
	<meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <!--<meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    <!--
    <meta property="og:url" content="https://happywatch99.com/" />
	<meta property="og:description" content="Site description" />
	<meta property="og:image" content="https://happywatch99-cache.cdnvideo.ru/happywatch99/broadcast_image/img_2135_1.jpg" />
    
    -->
    
    
    <meta property="og:title" content="Seen this on Happy Watch 99 yet?" />
<meta property="og:description" content="<?php echo $broadcast_data['content_description']; ?>" />
<meta property="og:url" content=https://happywatch99.com/broadcast.php?broadcast_id=<?php echo $broadcast_id ?>" />
<meta property="og:image" content="<?php echo $images[0]; ?>" />
	
        
    
    
    
    <script data-ad-client="ca-pub-6732371909634956" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    
    <link rel="shortcut icon" href="https://happywatch99.com/assets/global/favicon.ico">
    <link rel="stylesheet" href="https://happywatch99.com/assets/frontend/flixer/bootstrap.css" media="screen">
    <link rel="stylesheet" href="https://happywatch99.com/assets/frontend/flixer/custom.min.css">
    <link rel="stylesheet" href="https://happywatch99.com/assets/frontend/flixer/fontawesome/css/font-awesome.min.css">
    <script src="https://happywatch99.com/assets/frontend/flixer/jquery-1.10.2.min.js" ></script>
    <script src="https://happywatch99.com/assets/frontend/flixer/bootstrap.min.js" ></script>
    <script src="https://happywatch99.com/assets/deeplink-to-native-app.js" ></script>
    <style>
		.black_text{color:#000; background-color: #f3f3f3;}
		.blue_text{color: #0080ff;}
	</style>
</head>
<body style="background-color:#000;">
	<!-- TOP HEADING SECTION -->
<style>
	.nav_transparent {
	padding: 10px 0px 10px; border: 1px;
	background: rgba(0,0,0,0.6); 
	}
	.nav_dark {
	background-color: #000;
	padding: 10px;
	
	}
</style>

    <link itemprop="thumbnailUrl" href="https://happywatch99-cache.cdnvideo.ru/happywatch99/broadcast_image/img_2135_1.jpg">

<span itemprop="thumbnail" itemscope itemtype="http://schema.org/ImageObject">
<link itemprop="url" href="https://happywatch99-cache.cdnvideo.ru/happywatch99/broadcast_image/img_2135_1.jpg">
</span>
    

<?php

$t=time();




$sql="SELECT * FROM broadcast_view where broadcast_id='".$broadcast_id."'" ;
$broadcast_view_coll = mysqli_query($connection,$sql );

$view_count=mysqli_num_rows($broadcast_view_coll);



///$view_count=$this->db->get_where('broadcast_view', array('broadcast_id'=>$broadcast_id))->num_rows();

$sql_1="SELECT * FROM broadcast_comments where broadcast_id='".$broadcast_id."'" ;
$comment_view_coll = mysqli_query($connection,$sql_1 );

$comment_count=mysqli_num_rows($comment_view_coll);



//$comment_count=$this->db->get_where('broadcast_comments', array('broadcast_id'=>$broadcast_id))->num_rows();


$sql_2="SELECT * FROM broadcast_like_unlike where broadcast_id='".$broadcast_id."'" ;
$like_unlike_coll = mysqli_query($connection,$sql_2 );

$like_count=mysqli_num_rows($like_unlike_coll);


//$like_count=$this->db->get_where('broadcast_like_unlike', array('broadcast_id'=>$broadcast_id,'type'=>1))->num_rows();



//////////////////broadcast)details//////////////////////////////////////

/*
$sql_main="SELECT * FROM broadcast_details where id='".$broadcast_id."'" ;
$broadcast_main_coll = mysqli_query($connection,$sql_main );
$broadcast_data=mysqli_fetch_array($broadcast_main_coll);

$user_id=$broadcast_data['user_id'];

$broadcast_img=$broadcast_data['broadcast_img'];


if(!empty($broadcast_img))
{
    $array_broadcast_img=explode(',', $broadcast_img);
    
     foreach($array_broadcast_img as $img)
    {
          
           if(!empty($img))
            {
                $images[]='https://'.$img;
            }
                
    }            
        
}
else
{
    $broadcast_video_thumbnail=$broadcast_data['broadcast_video_thumbnail'];
    $array_video_thumb= explode(',', $broadcast_video_thumbnail);
    
    foreach($array_video_thumb as $thumb )
    {
       if(!empty($thumb))
       {
        $images[]='https://'.$thumb;
       }
    }


    
    
}


*/



///////////////////////////////////////////////////////////////////////


$sql_3="SELECT * FROM user where user_id='".$user_id."'" ;
$user_coll = mysqli_query($connection,$sql_3);
$user_data=mysqli_fetch_array($user_coll);
//$user_name=$this->db->get_where('user', array('user_id'=>$user_id))->row()->name ;    



$user_name=$user_data['name'];
$bar_thumb	=	'thumb1.png';






?>



<div class="navbar navbar-default navbar-fixed-top nav_transparent" >
	
	<?php /* ?>
	<div class="container" style=" width: 100%;">
		<div class="navbar-header">
			
			<a href="https://happywatch99.com/index.php?browse/home" class="navbar-brand">
				<img src="https://happywatch99.com//assets/global/logo.png" style=" height: 52px;margin-right: 50px;margin-top: -16px;" />
			</a>
			<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
		<div class="navbar-collapse collapse" id="navbar-main">
			<ul class="nav navbar-nav">
				<!-- Live GENRE WISE-->
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="" style="color: #ffffff; font-weight: bold;">
						Live TV <span class="caret"></span>
					</a>
					<ul class="dropdown-menu" aria-labelledby="themes">
												<li><a href="https://happywatch99.com/index.php?browse/live/6">
							Live Khmer TV							</a>
						</li>
												<li><a href="https://happywatch99.com/index.php?browse/live/7">
							Live Thai TV							</a>
						</li>
											</ul>
				</li>
				<!-- MOVIES GENRE WISE-->
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="" style="color: #ffffff; font-weight: bold;">
						Movies <span class="caret"></span>
					</a>
					<ul class="dropdown-menu" aria-labelledby="themes">
												<li><a href="https://happywatch99.com/index.php?browse/movie/8">
							American Movies							</a>
						</li>
												<li><a href="https://happywatch99.com/index.php?browse/movie/9">
							Chinese Movies							</a>
						</li>
											</ul>
				</li>
				<!-- TV SERIES GENRE WISE-->
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="" style="color: #ffffff; font-weight: bold;">TV Series<span class="caret"></span>
					</a>
					<ul class="dropdown-menu" aria-labelledby="themes">
												<li><a href="https://happywatch99.com/index.php?browse/series/10">
							Thai TV Series							</a>
						</li>
											</ul>
				</li>
				
			</ul>
			<!-- PROFILE, ACCOUNT SECTION -->
						<ul class="nav navbar-nav navbar-right">
			
			</ul>
			<!-- SEARCH FORM -->
			<form class="navbar-form navbar-right" method="post" action="https://happywatch99.com/index.php?browse/search">
				<!--<div class="form-group">
					<input type="text" class="form-control" placeholder="Search Live TV, TV Series & Movies" 
						style="background-color: #000; border: 1px solid #ababab; height:35px;" name="search_key">
				</div>-->
				<div  >
				  <input class="form-control" style="background-color: #000; color:#ffffff; border: 1px solid #ababab; height:35px; width:300px;" type="text" placeholder="Search Live TV, TV Series & Movies" 
				  name="search_key">
				  <button type="submit" style="background-color: #9d22af;"class="btn btn-default "><i class="fa fa-search" aria-hidden="true"></i></button>
				</div>
				
			</form>
		</div>
	</div>
	
	<?php */ ?>
	
	
	
</div>
<div style="padding: 50px;"></div><!-- MOVIE LIST, GENRE WISE LISTING -->

<style>
    
    .myNewfirstDivHeight{
        height:15px;
    }
    .myNewDivHeight{
       min-height:40px;
    }
    
    .div_background {
   background: rgba(122, 130, 136, 0.2)!important;
  /*background: #000;*/
  overflow: hidden;
   height:350px;
}


.div_right{
    text-align: left;
}


.tmpmargin{
    
    margin-right: 22px;
}

</style>
<div style="height:93vh;width:100%;background-image: url(https://happywatch99.com/assets/frontend/flixer/images/home_top_banner.jpg)">
<div class="row" style="margin:20px 60px;">
	
	<div class="content">
		
		
		
		<div class="grid">
			<div class="row" style="min-height: 194px;" >
			  
			  
				<?php
        $share_images="";		
    	if(!empty($images))
    	{
    		 foreach( $images  as $img )
    		 {
    	
    		$share_images.='<a href="https://happywatch99.com/index.php?browse/home" class="navbar-brand">
    				<img src="'.$img.' " style=" height: 52px;margin-right: 50px;margin-top: -16px;" />
    			</a>';
    		
    		  
    		  
    		  
    	
    		     
    		     
    		 }
		
    	}
		echo $share_images;
		
		
		
		?>
		  
			    
			</div>    
			<div class="row" style="min-height: 594px;" >
			
			
	
			
			<div class="col-lg-5 col-sm-5 .col-md-5">
			</div>    
			
		   
   
			
		<div class="col-lg-3 col-sm-4 .col-md-3 div_background">
		    
		    
		    
		    	<div class="myNewfirstDivHeight" >
			</div> 
		 

		   <div class="div_right myNewDivHeight" >
		  <img src="https://happywatch99.com/assets/global/<?php echo $bar_thumb;?>" 
						style="height:30px; border-radius: 50%;" />  &nbsp; <span style="color:#a807a5;" ><strong><?php echo $user_name ?> </strong></span>        
		        
		    </div>
		    
		  
		   <div class="div_right myNewDivHeight" >
		        <strong><?php echo $broadcast_data['content_description'] ?></strong>
		    </div>   
		    
		    <div class="myNewfirstDivHeight" >&nbsp;</div>    
		    
		    
		    
		    <div class="div_right myNewDivHeight" >
		    <strong><?php echo $view_count; ?> Views </strong>      
		    </div>      
		    
		     <div class="div_right myNewDivHeight" >
		   <strong><?php echo $comment_count ?> Comments </strong>&nbsp;&nbsp;.&nbsp;&nbsp;     
		   <strong><?php echo $like_count ?> Likes </strong>
		   </div>      
		    
		    
		    <div class="myNewDivHeight" >
		    </div>
		    <div class="text-center" >
		        <strong>See full post on Happy Watch 99 </strong>
		        <br>
		       <!-- <button type="button" id="open-app-link" >Open in App</button>-->
		       <?php
		       $oth_url="https://happywatch99.com/index.php?home/playbroadcast/".$broadcast_id;
            $ibi="com.happywatch99iOS.com";
$deep_link_url='https://test.happywatch99.com/?link=https://happywatch99.com/broad_id='.$broadcast_id.'&apn=master.happywatch99&isi=1489697591&ibi='.$ibi.'&ofl='.$oth_url;

        $device_name=mobile_user_agent_switch();
        if($device_name=='mac' OR $device_name=='iphone' OR $device_name=='ipad' )
        {
          $deep_link_url='ftp://happywatch99.com?broadcast_id='+$broadcast_id;  
        }
    
		       ?>
		        
		        <?php
		        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') && !strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')) {}
		        else
		        {
		        
		        ?>
		        
		        <button style="background-color: #9d22af;width: 90%; margin: 20px 0px;" class="btn btn-default " onclick='window.open("<?php echo $deep_link_url ?>", "_blank");'>Open in  App</button>
		        
		        
		        
		        
		        <?php
		        }
		        ?>
		      
		    </div>      
	    </div>
		<div class="col-lg-4 col-sm-3 .col-md-4">
			</div>    
		
		</div>	
			
		</div>
	</div>
	<div style="clear: both;"></div>
	</div>
	
</div>	
	
<div class="container" style="margin-top: 90px;">
	<hr style="border-top:1px solid #333;">
	<footer>
	<div class="row" style=" margin-left: 1%;" >
		<div class="col-lg-12">
			<ul class="list-unstyled">
				<li width="5%" ><a href="https://happywatch99.com/index.php?general/privacypolicy">Privacy Policy</a></li>
				<li width="5%"><a href="https://happywatch99.com/index.php?home/signin/admin">Admin</a></li>
				<li width="5%"><a href="https://happywatch99.com/index.php?general/support">Support</a></li>
				<li width="5%"><a href="https://happywatch99.com/index.php?general/termanduse">Terms of Use</a></li>
			</ul>
		</div>	
		
	</div>
		
	<div class="col-lg-9">
		<span style=" margin-left: 1%;">Happy Watch 99 is also available on</span>
		<img src="https://happywatch99.com//assets/global/apple.png" style=" margin-left: 5%;"  height="5%" 
		width="5%">
		<img src="https://happywatch99.com//assets/global/roku-logo.png"  height="5%" width="5%">
		<img src="https://happywatch99.com//assets/global/Amazon-Fire-TV-Logo.png"   height="5%" width="5%">
		<img src="https://happywatch99.com//assets/global/android.png"  height="5%" width="5%">
	</div>
</footer>
</div>




</body>
</html>

<?php

function mobile_user_agent_switch()
{
		$device = '';
		
		if( stristr($_SERVER['HTTP_USER_AGENT'],'ipad') ) {
			$device = "ipad";
		} else if( stristr($_SERVER['HTTP_USER_AGENT'],'iphone') || strstr($_SERVER['HTTP_USER_AGENT'],'iphone') ) 
		{
			$device = "iphone";
		
		
		}
        else if( stristr($_SERVER['HTTP_USER_AGENT'],'mac') ) {
			$device = "mac";
		}

    return $device;
}


?>
