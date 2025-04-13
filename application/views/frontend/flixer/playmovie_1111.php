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

<div class="video_cover">
	<div class="container" style="padding-top:100px; text-align: center;">
		<div class="row">
			<div class="col-lg-12">

				<!-- Video player generator starts -->
						
						<?php 
						
						//$row['url']="";
						?>
						
<link href="https://player.5centscdn.com/videojs/7.7.6/video-js.min.css" rel="stylesheet" />
  <style type="text/css">
    html, body {margin: 0;padding: 0;background-color: #000;}
    .video-js.vjs-hls-quality-selector{display:block}
    .vjs-menu-button-popup .vjs-menu .vjs-menu-content{max-height:20em;}
     @media screen and (orientation:portrait) {
      .video-js{width:100vw;height:40vh;}
     }
     @media screen and (orientation:landscape) {
      .video-js{width:60vw;height:50vh;}
     }
  </style>
  <script src="https://player.5centscdn.com/videojs/ie8/1.1.2/videojs-ie8.min.js"></script>

  <!-- Add following <video> tag in the page's <body> tag, where you need the player to be shown -->
  <video id="my-video" class="video-js vjs-default-skin vjs-big-play-centered" muted autoplay controls preload="auto">
    <p class="vjs-no-js">
      To view this video please enable JavaScript, and consider upgrading to a web browser that<a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
    </p>
  </video>

  <!-- Add following section before </body> tag -->
  <script src="https://player.5centscdn.com/videojs/7.7.6/video.min.js"></script>
  <script src="https://player.5centscdn.com/videojs/videojs-contrib-quality-levels.min.js"></script>
  <script src="https://player.5centscdn.com/videojs/videojs-hls-quality-selector.min.js"></script>
  <script src="https://player.5centscdn.com/videojs/videojs.hotkeys.min.js"></script>

  <script type="text/javascript">
    let player = {};
    document.addEventListener("DOMContentLoaded", function() {
      player = videojs('my-video', {
        fit: true,
        responsive: true,
        controls: true,
        liveui: true,
        techOrder: ["html5"],
        html5: {
          hls: {
            overrideNative: true,
            enableLowInitialPlaylist: true,
            useBandwidthFromLocalStorage: true,
            allowSeeksWithinUnsafeLiveWindow:true,
          },
          nativeAudioTracks: false,
          nativeVideoTracks: false,
          nativeControlsForTouch: false,
        },
        plugins: {
        },
        sources: [{
          type: "application/x-mpegurl",
          src: '<?php echo $row['url'] ?>' }],
      }, function () {
        this.reloadSourceOnError();

        this.hlsQualitySelector({
          displayCurrentQuality: true,
        });

        this.hotkeys({
          volumeStep: 0.1,
          seekStep: 5,
          enableModifiersForNumbers: false,
          alwaysCaptureHotkeys: true,
        });

        setTimeout(function () {
          player.play();
        }, 550);
      });
    });
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
