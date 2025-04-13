<?php
ob_start();
?>

<!doctype html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Play Broadcast | Happy Watch 99</title>
	
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    
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
	<!-- MOVIE LIST, GENRE WISE LISTING -->

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



<?php



$connection=mysqli_connect('localhost','happywat_netflix','TIT3ZK#~A$BJ','happywat_netflix');

///mysqli_select_db($connection,'amcappdbs');


$broadcast_id=$_REQUEST['broadcast_id'];

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

$sql_main="SELECT * FROM broadcast_details where id='".$broadcast_id."'" ;
$broadcast_main_coll = mysqli_query($connection,$sql_main );
$broadcast_data=mysqli_fetch_array($broadcast_main_coll);


 $user_id=$broadcast_data['user_id'];

///////////////////////////////////////////////////////////////////////


$sql_3="SELECT * FROM user where user_id='".$user_id."'" ;
$user_coll = mysqli_query($connection,$sql_3);
$user_data=mysqli_fetch_array($user_coll);
//$user_name=$this->db->get_where('user', array('user_id'=>$user_id))->row()->name ;    



$user_name=$user_data['name'];
$bar_thumb	=	'thumb1.png';




?>



<div style="height:93vh;width:100%;background-image: url(https://happywatch99.com/assets/frontend/flixer/images/home_top_banner.jpg)">
<div class="row" style="margin:20px 60px;">
	
	<div class="content">
		<div class="grid">
			<div class="row" style="min-height: 194px;" >
			</div>    
			<div class="row" style="min-height: 594px;" >
			
			
			<div class="col-lg-5 col-sm-5 .col-md-5">
			</div>    
			
		   
    
			
		<div class="col-lg-3 col-sm-4 .col-md-3 div_background">
		    
		    	<div class="myNewfirstDivHeight" >
			</div> 
		    
		    <div class="div_right myNewDivHeight" >
		  <img src="<?php echo base_url();?>assets/global/<?php echo $bar_thumb;?>" 
						style="height:30px; border-radius: 50%;" />  &nbsp; <span style="color:#a807a5;" ><strong><?php echo $user_name ?> </strong></span>        
		        
		    </div>
		    
		  
		    <div class="div_right myNewDivHeight" >
		        <strong><?php echo $broadcast_data['content_description'] ?></strong>
		    </div>      
		    
		    <div class="myNewfirstDivHeight" >&nbsp;</div>    
		    
		    
		    
		    <div class="div_right myNewDivHeight" >
		    <strong><?php echo $view_count ?> Views </strong>      
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

		       ?>
		        <a href="<?php echo $deep_link_url; ?>" style="background-color: #9d22af;width: 90%; margin: 20px 0px;" class="btn btn-default "> Open in  App</a>
		      

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
	</div>

</body>
</html>
