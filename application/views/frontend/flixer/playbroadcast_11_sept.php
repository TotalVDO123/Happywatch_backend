<?php include 'header_browse.php';


        //$this->db->where('broadcast_id', $broadcast_id);
        //$query = $this->db->get('broadcast_view');
        //$broadcast_view_details=$query->result_array();


$view_count=$this->db->get_where('broadcast_view', array('broadcast_id'=>$broadcast_id))->num_rows();


$comment_count=$this->db->get_where('broadcast_comments', array('broadcast_id'=>$broadcast_id))->num_rows();


$like_count=$this->db->get_where('broadcast_like_unlike', array('broadcast_id'=>$broadcast_id,'type'=>1))->num_rows();

 $user_id=$broadcast_data[0]['user_id'];

$user_name=$this->db->get_where('user', array('user_id'=>$user_id))->row()->name ;    

$bar_thumb	=	'thumb1.png';
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
$deep_link_url='https://happywatch.page.link/?link=https://happywatch99.com/?broad_id='.$broadcast_id.'&apn=com.happyWatch.happywatchmobile&isi=1489697591&ibi='.$ibi.'&ofl='.$oth_url;
	
		       ?>
		        <a href="#" style="background-color: #9d22af;width: 90%; margin: 20px 0px;" class="btn btn-default "> Open in  App</a>
		      

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
	<?php include 'footer.php';?>
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
const hrefUrl = window.location.href;
var clicked = +new Date, timeout = 500;
setTimeout(function () {
if (+new Date - clicked < timeout*2) {
console.log('clicked '+ (+new Date - clicked) +' ago- go to appstore');
window.location = "itms://itunes.apple.com/app/apple-store/id1489697591?mt=8";
} else {
console.log('too late open Appstore');
}
}, timeout);
var iosLink = hrefUrl.replace("https:", "com.happywatch99iOS.com:");
try {
window.location = iosLink;
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