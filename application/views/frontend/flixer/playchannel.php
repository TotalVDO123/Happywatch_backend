<?php include 'header_browse.php';?>
<?php
	$channel_details	=	$this->db->get_where('channel' , array('channel_id' => $channel_id))->result_array();
	foreach ($channel_details as $row):
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
	background-image: url(<?php echo $this->crud_model->get_poster_url('channel' , $row['channel_id']);?>);
	width: 100%;
	height: 100%;
	opacity : 0.2;
	z-index: -1;
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
				<?php if (video_type($row['url']) == 'youtube'): ?>
					<!------------- PLYR.IO ------------>
					<link rel="stylesheet" href="<?php echo base_url();?>assets/global/plyr/plyr.css">

					<div class="plyr__video-embed" id="player">
					    <iframe src="<?php echo $row['url'];?>?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1" allowfullscreen allowtransparency allow="autoplay"></iframe>
					</div>

					<script src="<?php echo base_url();?>assets/global/plyr/plyr.js"></script>
					<script>const player = new Plyr('#player');</script>
					<!------------- PLYR.IO ------------>
				<?php elseif (video_type($row['url']) == 'vimeo'):
					$vimeo_video_id = get_vimeo_video_id($row['url']); ?>
					<link rel="stylesheet" href="<?php echo base_url();?>assets/global/plyr/plyr.css">

					<div class="plyr__video-embed" id="player">
					    <iframe src="https://player.vimeo.com/video/<?php echo $vimeo_video_id; ?>?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media" allowfullscreen allowtransparency allow="autoplay"></iframe>
					</div>

					<script src="<?php echo base_url();?>assets/global/plyr/plyr.js"></script>
					<script>const player = new Plyr('#player');</script>
				<?php else :?>
					<link rel="stylesheet" href="<?php echo base_url();?>assets/global/plyr/plyr.css">
					<video poster="<?php echo $this->crud_model->get_thumb_url('channel' , $row['channel_id']);?>" id="player" playsinline controls>
					<?php if (get_video_extension($row['url']) == 'mp4'): ?>
						<source src="<?php echo $row['url']; ?>" type="video/mp4">
					<?php elseif (get_video_extension($row['url']) == 'webm'): ?>
						<source src="<?php echo $row['url']; ?>" type="video/webm">
					<?php else: ?>
						<h4><?php get_phrase('video_url_is_not_supported'); ?></h4>
					<?php endif; ?>
					</video>

					<script src="<?php echo base_url();?>assets/global/plyr/plyr.js"></script>
					<script>const player = new Plyr('#player');</script>
				<?php endif; ?>
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
						"image": "<?php echo $this->crud_model->get_poster_url('channel' , $row['channel_id']);?>",
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
					<img src="<?php echo $this->crud_model->get_thumb_url('channel' , $row['channel_id']);?>" style="height: 60px; margin:20px;" />
				</div>
				<!--<div class="col-lg-9">
					 VIDEO TITLE 
					<h3>
						<?php echo $row['title'];?>
					</h3>
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
					</div
				</div>>-->
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
			    mylist_exist_status = "<?php echo $this->crud_model->get_mylist_exist_status('channel' , $row['channel_id']);?>";

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
				onclick="process_list('live' , 'add', <?php echo $row['live_id'];?>)">
			<i class="fa fa-plus"></i> <?php echo get_phrase('Add_to_My_list');?>
			</a>
			</span>
			<span id="mylist_delete_button" style="display:none;">
			<a href="#" class="btn btn-default btn-md" style="font-size: 16px; margin-top: 20px;"
				onclick="process_list('live' , 'delete', <?php echo $row['live_id'];?>)">
			<i class="fa fa-check"></i> <?php echo get_phrase('Added_to_My_list');?>
			</a>
			</span> -->
			<!-- MOVIE GENRE -->
			<div style="margin-top: 10px;">
				<strong><?php echo get_phrase('Genre');?></strong> :
				<a href="<?php echo base_url();?>index.php?browse/channel/<?php echo $row['genre_id'];?>">
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
					<li class="active" style="width:33%;">
						<a href="#about" data-toggle="tab">
							<?php echo get_phrase('About');?>
						</a>
					</li>
					<!--<li style="width:33%;">
						<a href="#cast" data-toggle="tab">
							<?php echo get_phrase('Cast');?>
						</a>
					</li>-->
					<li style="width:34%;">
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
									$channel = $this->crud_model->get_channel($row['genre_id'] , 10, 0);
									foreach ($channel as $row)
									{
										$title	=	$row['title'];
										$link	=	base_url().'index.php?browse/playchannel/'.$row['channel_id'];
										$thumb	=	$this->crud_model->get_thumb_url('channel' , $row['channel_id']);
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
