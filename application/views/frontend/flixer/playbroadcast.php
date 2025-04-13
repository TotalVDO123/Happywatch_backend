
 

<?php //include 'header_browse.php';


        //$this->db->where('broadcast_id', $broadcast_id);
        //$query = $this->db->get('broadcast_view');
        //$broadcast_view_details=$query->result_array();


$view_count=$this->db->get_where('broadcast_view', array('broadcast_id'=>$broadcast_id))->num_rows();


$comment_count=$this->db->get_where('broadcast_comments', array('broadcast_id'=>$broadcast_id))->num_rows();


$like_count=$this->db->get_where('broadcast_like_unlike', array('broadcast_id'=>$broadcast_id,'type'=>1))->num_rows();

 $user_id=$broadcast_data[0]['user_id'];

$user_name=$this->db->get_where('user', array('user_id'=>$user_id))->row()->name ;    

$bar_thumb	=	'thumb1.png';



$broadcast_img=$broadcast_data[0]['broadcast_img'];

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
    
    $broadcast_video_thumbnail=$broadcast_data[0]['broadcast_video_thumbnail'];
    //$broadcast_video_thumbnail=$broadcast_data['broadcast_video_thumbnail'];
    $array_video_thumb= explode(',', $broadcast_video_thumbnail);
    
    foreach($array_video_thumb as $thumb )
    {
       if(!empty($thumb))
       {
        $images[]='https://'.$thumb;
       }
    }


    
    
}








?>
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
<div style="height:93vh;width:100%;background-image: url(https://happywatch99.com/assets/frontend/flixer/images/home_top_banner.jpg)">
<div class="row" style="margin:20px 60px;">
	
	<div class="content">
		<div class="grid">
			<div class="row" style="min-height: 194px;" >
			</div>    
			<div class="row" style="min-height: 594px;" >
			
				<?php
        $share_images="";		
		 foreach( $images  as $img )
		 {
		
	   
		$share_images.='
				<img src="'.$img.' " style=" height: 52px;margin-right: 50px;margin-top: -16px;" />';
		
	    
		   
		   /*
		   
		   	$share_images.='<a href="https://happywatch99.com/index.php?browse/home" class="navbar-brand">
    				<img src="'.$img.' " style=" height: 52px;margin-right: 50px;margin-top: -16px;" />
    			</a>';
		   
		   */
		     
		    
		     
		 }
		echo $share_images;
		
	//	echo $share_with_chat;
		
		?>
		  
			
			<div class="col-lg-5 col-sm-5 .col-md-5">
			</div>    
			
		   
    
			
		<div class="col-lg-3 col-sm-4 .col-md-3 div_background">
		    
		    	<div class="myNewfirstDivHeight" >
			</div> 
		    
		    <div class="div_right myNewDivHeight" >
		 
		 <?php /* ?>
		  <img src="<?php echo base_url();?>assets/global/<?php echo $bar_thumb;?>" 
		
	
		
						style="height:30px; border-radius: 50%;" />
					<?php */ ?>		
						
						&nbsp; <span style="color:#a807a5;" ><strong><?php echo $user_name ?> </strong></span>        
		        
		    </div>
		    
		  
		    <div class="div_right myNewDivHeight" >
		        <strong><?php echo $broadcast_data[0]['content_description'] ?></strong>
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
	<?php // include 'footer.php';?>
</div>

<?php
$actual_link = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
?>

<script>

        function isIOS() {
        const iDevices = [
        'iPad Simulator',
        'iPhone Simulator',
        'iPod Simulator',
        'iPad',
        'iPhone',
        'iPod'
        ];
        
        if (navigator.platform) {
        while (iDevices.length) {
        if (navigator.platform === iDevices.pop()) {
        return true;
        }
        }
        }
        
        return false;
        }
        
        let openedApp = false;
        
        function openAppOrStore() {
        setTimeout(function () {
        if (!openedApp) {
        //window.location = "https://apps.apple.com/us/app/petleo/id1462882016";
        window.location = "<?php echo $actual_link ?>";
        
        }
        }, 25);
         //const parts = window.location.href.split('/');
        //const consultationId = parts[parts.length - 2];
         
        const parts = window.location.href.split('/');
         
        const consultationtype = parts[parts.length - 2];
        const consultationId = parts[parts.length - 1];
         
         //const consultationId = parts[parts.length - 1];
        
        
        //https://livekanvas.com/index.php?browse/playlive/fpwj47pf
        
        //const iosLink = "com.livekanvasstudios.livekanvas://livekanvas.com/" + consultationId;
        
        //const iosLink = "com.livekanvasstudios.livekanvas://happywatch99.com/" + consultationtype + "/" + consultationId;
        
        const iosLink = "com.happywatch99iOS.com://happywatch99.com/" + consultationtype + "/" + consultationId;
        //const iosLink = "com.livekanvasstudios.livekanvas://livekanvas.com/" + consultationtype +consultationId;
        
        try {
        window.location = iosLink;
        if (window.location.href.indexOf("https://") !== -1) {
        openedApp = true;
        }
        } catch (e) {
        }
        }
        
        setTimeout(function () {
        if (window.location.href.indexOf('happywatch99.com') !== -1) {
        if (isIOS()) {
        openAppOrStore();
        }
        }
        }, 25);
        
        

</script>


