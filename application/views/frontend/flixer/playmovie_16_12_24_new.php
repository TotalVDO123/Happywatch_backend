<?php include 'header_browse.php';?>
<?php
	$movie_details	=	$this->db->get_where('movie' , array('movie_id' => $movie_id))->result_array();
	foreach ($movie_details as $row):
	?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/frontend/' . $selected_theme;?>/hovercss/demo.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/frontend/' . $selected_theme;?>/hovercss/set1.css" />
<style>
	.movie_thumb{}
	.btn_opaque{font-size:20px; border: 1px solid #939393;text-decoration: none;margin: 10px;background-color: rgba(0, 0, 0, 0.74); color: #fff;}
	.btn_opaque:hover{border: 1px solid #939393;text-decoration: none;background-color: rgba(57, 57, 57, 0.74);color:#fff;}
	.video_cover {
	position: relative;padding-bottom: 30px;
	}
	.video_cover:after {
	content : "";
	display: block;
	position: absolute;
	top: 0;
	left: 0;
	background-image: url(<?php echo $this->crud_model->get_poster_url('movie' , $row['movie_id']);?>);
	width: 100%;
	height: 100%;
	opacity : 0.2;
	z-index: -999;
	background-size:cover;
	}
	.select_black{background-color: #000;height: 45px;padding: 12px;font-weight: bold;color: #fff;}
	.profile_manage{font-size: 25px;border: 1px solid #ccc;padding: 5px 30px;text-decoration: none;}
	.profile_manage:hover{font-size: 25px;border: 1px solid #fff;padding: 5px 30px;text-decoration: none;color:#fff;}
</style>
<!-- VIDEO PLAYER -->
<link rel="stylesheet" href="<?php echo base_url() . 'assets/ads_google'?>/style.css"/>

    <script src="https://imasdk.googleapis.com/js/sdkloader/ima3.js"></script>
    <script src="<?php echo base_url() . 'assets/ads_google'?>/application.js"></script>
    <script src="<?php echo base_url() . 'assets/ads_google'?>/ads.js"></script>
    <script src="<?php echo base_url() . 'assets/ads_google'?>/video_player.js"></script>
<div class="video_cover">
	<div class="container" style="padding-top:150px; text-align: center;">
		<div class="row">
			<div class="col-lg-12">

				<!-- Video player generator starts -->
						
						<?php 
						
						//$row['url']="";
						?>
						
						
						

    <!-- GPT Companion Code -->
    <!-- Initialize the tagging library -->
     <script>
       var googletag = googletag || {};
       googletag.cmd = googletag.cmd || [];
       (function() {
         var gads = document.createElement('script');
         gads.async = true;
         gads.type = 'text/javascript';
         gads.src = 'https://www.googletagservices.com/tag/js/gpt.js';
         var node = document.getElementsByTagName('script')[0];
         node.parentNode.insertBefore(gads, node);
       })();
     </script>

     <!-- Register your companion slots -->
     <script>
       googletag.cmd.push(function() {
         // Supply YOUR_NETWORK/YOUR_UNIT_PATH in place of 6062/iab_vast_samples.
         googletag.defineSlot('/6062/iab_vast_samples', [728, 90], 'companionDiv')
             .addService(googletag.companionAds())
             .addService(googletag.pubads());
         googletag.companionAds().setRefreshUnfilledSlots(true);
         googletag.pubads().enableVideoAds();
         googletag.enableServices();
       });
     </script>

    
				<div>&nbsp;</div>
				<div>&nbsp;</div>
				<div>&nbsp;</div>
				<div>&nbsp;</div>
  
   

    <div id="videoplayer">
      <video id="content">
        <source src="https://storage.googleapis.com/gvabox/media/samples/stock.mp4"></source>
      </video>
      <div id="adcontainer">
      </div>
      <button id="playpause" title="Play/Pause">&#9654;</button>
      <button id="replay" title="Replay">&#8634;</button>
      <button id="fullscreen" title="Full screen">[&nbsp;&nbsp;&nbsp;]</button>
    </div>

    <div id="customClick">
      <div id="customClickTextWrapper">Click here for more info on your ad.</div>
    </div>

    <!-- Declare a div where you want the companion to appear. Use
          googletag.display() to make sure the ad is displayed. -->
    <div id="companionDiv">
      <script>
        // Using the command queue to enable asynchronous loading.
        // The unit will not actually display now - it will display when
        // the video player is displaying the ads.
        googletag.cmd.push(function() { googletag.display('companionDiv'); });
      </script>
    </div>

    <div id="playlistDiv" style="display:none;">
      <div class="playlistItem">
        <img id="0" class="playlistImg" src="img/android_preview.jpg" />
        Video 1
      </div>
      <div class="playlistItem">
        <img id="1" class="playlistImg" src="img/doubleclick_preview.jpg" />
        Video 2
      </div>
    </div>

    <div id="console" style="display:none;" >
    Welcome to IMA HTML5 SDK Demo!
    </div>

    
  
  <script>
  var application = null;

  window.onload = function() {
    application = new Application();
  };
  </script>
						
						
						
						
						
						
						
						
						
						
						<!-- Video player generator ends -->

				<?php
				$iframe_embed = $this->crud_model->is_iframe($row['url']);
				if ($iframe_embed == true):
				?>
				<!-- loads iframe embed option as video player -->
				<style>
				.intrinsic-container {
				  position: relative;
				  height: 0;
				  overflow: hidden;
				}

				/* 16x9 Aspect Ratio */
				.intrinsic-container-16x9 {
				  padding-bottom: 56.25%;
				}

				/* 4x3 Aspect Ratio */
				.intrinsic-container-4x3 {
				  padding-bottom: 75%;
				}

				.intrinsic-container iframe {
				  position: absolute;
				  top:0;
				  left: 0;
				  width: 100%;
				  height: 100%;
				}
				</style>
				<!-- <div class="intrinsic-container intrinsic-container-16x9">
  					<iframe src="<?php echo $row['url'];?>" allowfullscreen style="border:0px; width:100%; height:100%;"></iframe>
				</div> -->
				<?php
				endif;
				if ($iframe_embed == false):
				?>
				<!-- loads jwplayer as video player
				<script src="https://content.jwplatform.com/libraries/O7BMTay5.js"></script>
				<div id="video_player_div"><?php echo $row['title'];?></div>


				<script>
					jwplayer("video_player_div").setup({
						"file": "<?php echo $row['url'];?>",
						"image": "<?php echo $this->crud_model->get_poster_url('movie' , $row['movie_id']);?>",
						"width": "100%",
						aspectratio: "16:9",
						listbar: {
						  position: 'right',
						  size: 260
						},
					  });
				</script>-->
				<?php endif;?>
			</div>
		</div>
	</div>
</div>
<!-- VIDEO DETAILS HERE -->
<div class="container" style="margin-top: 30px;">
	<div class="row">
		<div class="col-lg-8">
			<div class="row">
				<div class="col-lg-3">
					<img src="<?php echo $this->crud_model->get_thumb_url('movie' , $row['movie_id']);?>" style="height: 60px; margin:20px;" />
				</div>
				<div class="col-lg-9">
					<!-- VIDEO TITLE -->
					<h3>
						<?php echo $row['title'];?>
					</h3>
					<!-- RATING CALCULATION -->
					<div>
						<?php
							for($i = 1 ; $i <= $row['rating'] ; $i++):
							?>
						<i class="fa fa-star" aria-hidden="true"></i>
						<?php endfor;?>
						<?php
							for($i = 5 ; $i > $row['rating'] ; $i--):
							?>
						<i class="fa fa-star-o" aria-hidden="true"></i>
						<?php endfor;?>
					</div>
				</div>
			</div>
		</div>
		<script>
			// submit the add/delete request for mylist
			// type = movie/series, task = add/delete, id = movie_id/series_id
			function process_list(type, task, id)
			{
				$.ajax({
					url: "<?php echo base_url();?>index.php?browse/process_list/" + type + "/" + task + "/" + id,
					success: function(result){
			        //alert(result);
			        if (task == 'add')
			        {
			        	$("#mylist_button_holder").html( $("#mylist_delete_button").html() );
			        }
			        else if (task == 'delete')
			        {
			        	$("#mylist_button_holder").html( $("#mylist_add_button").html() );
			        }
			    }});
			}

			// Show the add/delete wishlist button on page load
			   $( document ).ready(function() {

			   	// Checking if this movie_id exist in the active user's wishlist
			    mylist_exist_status = "<?php echo $this->crud_model->get_mylist_exist_status('movie' , $row['movie_id']);?>";

			    if (mylist_exist_status == 'true')
			    {
			    	$("#mylist_button_holder").html( $("#mylist_delete_button").html() );
			    }
			    else if (mylist_exist_status == 'false')
			    {
			    	$("#mylist_button_holder").html( $("#mylist_add_button").html() );
			    }
			});
		</script>
		<div class="col-lg-4">
			<!-- ADD OR DELETE FROM PLAYLIST 
			<span id="mylist_button_holder">
			</span>
			<span id="mylist_add_button" style="display:none;">
			<a href="#" class="btn btn-danger btn-md" style="font-size: 16px; margin-top: 20px;"
				onclick="process_list('movie' , 'add', <?php echo $row['movie_id'];?>)">
			<i class="fa fa-plus"></i> <?php echo get_phrase('Add_to_My_list');?>
			</a>
			</span>
			<span id="mylist_delete_button" style="display:none;">
			<a href="#" class="btn btn-default btn-md" style="font-size: 16px; margin-top: 20px;"
				onclick="process_list('movie' , 'delete', <?php echo $row['movie_id'];?>)">
			<i class="fa fa-check"></i> <?php echo get_phrase('Added_to_My_list');?>
			</a>
			</span>-->
			<!-- MOVIE GENRE -->
			<div style="margin-top: 10px;">
				<strong><?php echo get_phrase('Genre');?></strong> :
				<a href="<?php echo base_url();?>index.php?browse/movie/<?php echo $row['genre_id'];?>">
				<?php echo $this->db->get_where('genre',array('genre_id'=>$row['genre_id']))->row()->name;?>
				</a>
			</div>
			<!-- MOVIE YEAR -->
			<div>
				<strong><?php echo get_phrase('Year');?></strong> : <?php echo $row['year'];?>
			</div>
		</div>
	</div>
	<div class="row" style="margin-top:20px;">
		<div class="col-lg-12">
			<div class="bs-component">
				<ul class="nav nav-tabs">
					<li class="active" style="width:50%;">
						<a href="#about" data-toggle="tab">
							<?php echo get_phrase('About');?>
						</a>
					</li>
					<!--<li style="width:33%;">
						<a href="#cast" data-toggle="tab">
							<?php echo get_phrase('Cast');?>
						</a>
					</li>-->
					<li style="width:50%;">
						<a href="#more" data-toggle="tab">
							<?php echo get_phrase('More');?>
						</a>
					</li>
				</ul>
				<div id="myTabContent" class="tab-content">
					<!-- TAB FOR TITLE -->
					<div class="tab-pane active in" id="about">
						<p>
							<?php echo $row['description_long'];?>
						</p>
					</div>
					<!-- TAB FOR ACTORS 
					<div class="tab-pane " id="cast">
						<p>
							<?php
								$actors	=	json_decode($row['actors']);
								for($i = 0; $i< sizeof($actors) ; $i++)
								{
									?>
						<div style="float: left; text-align:center; color: #fff; font-weight: bold;">
							<img src="<?php echo $this->crud_model->get_actor_image_url($actors[$i]);?>"
								style="height: 160px; margin:5px;" />
							<br>
							<?php echo $this->db->get_where('actor', array('actor_id'=>$actors[$i]))->row()->name;?>
						</div>
						<?php
							}
							?>
						</p>
					</div>-->
					<!-- TAB FOR SAME CATEGORY MOVIES -->
					<div class="tab-pane  " id="more">
						<p>
						<div class="content">
							<div class="grid">
								<?php
									$movies = $this->crud_model->get_movies($row['genre_id'] , 10, 0);
									foreach ($movies as $row)
									{
										$title	=	$row['title'];
										$link	=	base_url().'index.php?browse/playmovie/'.$row['movie_id'];
										$thumb	=	$this->crud_model->get_thumb_url('movie' , $row['movie_id']);
										include 'thumb.php';
									}
								?>
							</div>
						</div>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<hr style="border-top:1px solid #333;">
	<?php include 'footer.php';?>
</div>
<?php endforeach;?>
