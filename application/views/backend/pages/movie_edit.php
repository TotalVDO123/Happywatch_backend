<?php
	$movie_detail = $this->db->get_where('movie',array('movie_id'=>$movie_id))->row();
?>
<form method="post" action="<?php echo base_url();?>index.php?admin/movie_edit/<?php echo $movie_id;?>" enctype="multipart/form-data">
	<div class="row">
	    <div class="col-6">
	        <div class="card">
	            <div class="card-body">
					<div class="form-group mb-3">
	                    <label for="simpleinput1">Movie Title</label>
	                    <input type="text" class="form-control" id = "simpleinput1" name="title" value="<?php echo $movie_detail->title;?>">
	                </div>
					<!--
					<div class="form-group mb-3">
	                    <label for="simpleinput1">Sub Title</label>
	                    <input type="text" class="form-control" id = "sub_title" name="sub_title" value="<?php echo $movie_detail->sub_title;?>" required>
	                </div>
					-->
					
					<div class="form-group mb-3">
						<label for="actors">Subtitle</label>
						<span class="help">- select multiple subtitle</span>
						<select class="form-control select2" id="sub_title" multiple name="sub_title[]">
							<?php
								$subtitle_id	=	$movie_detail->subtitle_id;
								$array_subtitle_id	=	explode(",",$subtitle_id);
								$subtitles	=	$this->db->get('subtitle')->result_array();
								foreach ($subtitles as $subtitle):?>
							<option 
							<?php
							if($subtitle_id!="")
							{
										if (in_array($subtitle['subtitle_id'], $array_subtitle_id))
											echo 'selected';

							}
							?>
							value="<?php echo $subtitle['subtitle_id'];?>">
								<?php echo $subtitle['name'];?>
							</option>
							<?php endforeach;?>
						</select>
					</div>

					
					
					
					<?php 
						if($this->session->userdata('admin_restricted')!=1)
						{
						?>	
					<div class="form-group mb-3">
	                    <label for="url">Video URL</label>
						<span class="help">- youtube or any hosted video</span>
	                    <input type="text" class="form-control" name="url" id="url" value="<?php echo $movie_detail->url;?>">
	                </div>
					<?php
					}
					?>
					<div class="form-group mb-3">
	                    <label for="">Thumbnail</label>
						<span class="help">- icon image of the movie</span>
	                    <input type="file" class="form-control" name="thumb">
	                </div>

					<div class="form-group mb-3">
	                    <label for="">Poster</label>
						<span class="help">- large banner image of the movie</span>
	                    <input type="file" class="form-control" name="poster">
	                </div>

                    <!--
                    <div class="form-group mb-3">
	                    <label for="simpleinput1">Audio track </label>
	                    <input type="text" class="form-control" id = "audio_track " name="audio_track" value="<?php echo $movie_detail->audio_track;?>" required>
	                </div>
                    -->

                    <div class="form-group mb-3">
						<label for="actors">Audio Track</label>
						<span class="help">- select multiple Audio track</span>
						<select class="form-control select2" id="audio_track" multiple name="audio_track[]">
							<?php
								$audio_tracks	=	$this->db->get('audio_track')->result_array();
								
								$audio_track_id 	=	$movie_detail->audio_track_id ;
								$array_audio_track_id	=	explode(",",$audio_track_id);

								foreach ($audio_tracks as $audio_track):?>
							    <option 
							    <?php
							    
							    if($audio_track_id!="")
							    {
							        	if (in_array($audio_track['audio_track_id'], $array_audio_track_id))
											echo 'selected';
							    }
							    
							    ?>
							    value="<?php echo $audio_track['audio_track_id'];?>">
								<?php echo $audio_track['name'];?>
							</option>
							<?php endforeach;?>
						</select>
					</div>




					<div class="form-group mb-3">
						<label for="description_long">Long Description</label>
						<textarea class="form-control" id="description_long" name="description_long" rows="6"><?php echo $movie_detail->description_short;?></textarea>
					</div>

					<div class="form-group mb-3">
						<label for="description_short">Short Description</label>
						<textarea class="form-control" id="description_short" name="description_short" rows="6"><?php echo $movie_detail->description_long;?></textarea>
					</div>

					<div class="form-group mb-3">
						<label for="actors">Actors</label>
						<span class="help">- select multiple actors</span>
						<select class="form-control select2" id="actors" multiple name="actors[]">
							<?php
								$actors	=	$this->db->get('actor')->result_array();
								foreach ($actors as $row2):?>
							<option
								<?php
									$actors	=	$movie_detail->actors;
									if ($actors != '')
									{
										$actor_array	=	json_decode($actors);
										if (in_array($row2['actor_id'], $actor_array))
											echo 'selected';
									}
									?>
								value="<?php echo $row2['actor_id'];?>">
								<?php echo $row2['name'];?>
							</option>
							<?php endforeach;?>
						</select>
					</div>

					<div class="form-group mb-3">
						<label for="genre_id">Genre</label>
						<span class="help">- genre must be selected</span>
						<select class="form-control select2" id="genre_id" name="genre_id">
							<?php
								$genres	=	$this->crud_model->get_genres();
								foreach ($genres as $row2):?>
							<option
								<?php if ( $movie_detail->genre_id == $row2['genre_id']) echo 'selected';?>
								value="<?php echo $row2['genre_id'];?>">
								<?php echo $row2['name'];?>
							</option>
							<?php endforeach;?>
						</select>
					</div>

					<div class="form-group mb-3">
						<label for="year">Publishing Year</label>
						<span class="help">- year of publishing time</span>
						<select class="form-control select2" id="year" name="year">
							<?php for ($i = date("Y"); $i > 2000 ; $i--):?>
							<option
								<?php if ( $movie_detail->year == $i) echo 'selected';?>
								value="<?php echo $i;?>">
								<?php echo $i;?>
							</option>
							<?php endfor;?>
						</select>
					</div>

					<div class="form-group mb-3">
						<label for="rating">Rating</label>
						<span class="help">- star rating of the movie</span>
						<select class="form-control select2" id="rating" name="rating">
							<?php for ($i = 0; $i <= 5 ; $i++):?>
							<option
								<?php if ( $movie_detail->rating == $i) echo 'selected';?>
								value="<?php echo $i;?>">
								<?php echo $i;?>
							</option>
							<?php endfor;?>
						</select>
					</div>

					<div class="form-group mb-3">
						<label for="featured">Featured</label>
						<span class="help">- featured movie will be shown in home page</span>
						<select class="form-control select2" id="featured" name="featured">
							<option value="1" <?php if ( $movie_detail->featured == 1) echo 'selected';?>>Yes</option>
							<option value="0" <?php if ( $movie_detail->featured == 0) echo 'selected';?>>No</option>
						</select>
					</div>
					
					<div class="form-group mb-3">
                    <label for="">U Watch</label>
					<span class="help"></span>
                    <input type="file" class="form-control" name="u_watch" >
	                </div>
					
		<?php
		if($this->session->userdata('login_type')==1)	
		{	
			if(($this->session->userdata('email')=='eric@happywatch99.com' or $this->session->userdata('email')=='sam@happywatch99.com'))				{	
					?>	

					
					<div class="form-group mb-3">
	                    <label for="simpleinput1">View </label>
	                    <input type="text" class="form-control" name="increase_view" id ="increase_view" value="<?php echo $movie_detail->increase_view;?>" name="audio_track" required>
	                </div>    
			<?php
			$total_original_view=0;
			$this->db->select('count(*) as tot');
    		$this->db->from('movie_view');
    		$this->db->where('movie_id', $movie_id);
    		
    		$query = $this->db->get();
    		$num = $query->num_rows();
    		if($num > 0)
    		{
    		    $record=  $query->result_array();
    		    $total_original_view= $record[0]['tot'];
    		}
    		
?>
					
					
					<div class="form-group mb-3">
	                    <label for="simpleinput1">Total View </label>
					<input type="text" class="form-control"  value="<?php echo ($total_original_view+$movie_detail->increase_view) ?>"  readonly >	
					
					
					</div>
					
					<?php
				}
			}
					?>
					
	            </div>
	        </div>
	    </div>
		<div class="col-6">
			<div class="card">
				<div class="card-body">
					<div class="form-group">
						<label class="form-label">Preview:</label>

						<!-- Video player generator starts -->
						
						<?php if (video_type($movie_detail->url) == 'youtube'): ?>
							<!------------- PLYR.IO ------------>
							<link rel="stylesheet" href="<?php echo base_url();?>assets/global/plyr/plyr.css">

							<div class="plyr__video-embed" id="player">
							    <iframe src="<?php echo $movie_detail->url;?>?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1" allowfullscreen allowtransparency allow="autoplay"></iframe>
							</div>

							<script src="<?php echo base_url();?>assets/global/plyr/plyr.js"></script>
							<script>const player = new Plyr('#player');</script>

							<!------------- PLYR.IO ------------>
						<?php elseif (video_type($movie_detail->url) == 'vimeo'):
							$vimeo_video_id = get_vimeo_video_id($movie_detail->url); ?>
							<link rel="stylesheet" href="<?php echo base_url();?>assets/global/plyr/plyr.css">

							<div class="plyr__video-embed" id="player">
							    <iframe src="https://player.vimeo.com/video/<?php echo $vimeo_video_id; ?>?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media" allowfullscreen allowtransparency allow="autoplay"></iframe>
							</div>

							<script src="<?php echo base_url();?>assets/global/plyr/plyr.js"></script>
							<script>const player = new Plyr('#player');</script>
					
						<!------------- PLYR.mp4 ------------>
						<?php elseif (get_video_extension($movie_detail->url) == 'mp4'):?>
					
							<link rel="stylesheet" href="<?php echo base_url();?>assets/global/plyr/plyr.css">
							<video poster="<?php echo $this->crud_model->get_thumb_url('movie' , $movie_detail->movie_id);?>" id="player" playsinline controls>
							<?php if (get_video_extension($movie_detail->url) == 'mp4'): ?>
								<source src="<?php echo $movie_detail->url; ?>" type="video/mp4">
							<?php endif; ?>
							</video>

							<script src="<?php echo base_url();?>assets/global/plyr/plyr.js"></script>
							<script>const player = new Plyr('#player');</script>
							
						<!------------- PLYR.webm ------------>
						<?php elseif (get_video_extension($movie_detail->url) == 'webm'):?>
					
							<link rel="stylesheet" href="<?php echo base_url();?>assets/global/plyr/plyr.css">
							<video poster="<?php echo $this->crud_model->get_thumb_url('movie' , $movie_detail->movie_id);?>" id="player" playsinline controls>
							<?php if (get_video_extension($movie_detail->url) == 'webm'): ?>
								<source src="<?php echo $movie_detail->url; ?>" type="video/webm">
							<?php endif; ?>
							</video>

							<script src="<?php echo base_url();?>assets/global/plyr/plyr.js"></script>
							<script>const player = new Plyr('#player');</script>
					
						<?php else :?>	
						<!------------- PLYR.IO ------------>	
						
							<link href="https://vjs.zencdn.net/7.2.3/video-js.css" rel="stylesheet">
							<!-- HTML -->
							<video id='hls-example' class="video-js vjs-default-skin" width="400" height="300" controls>
							<source type="application/x-mpegURL" src="<?php echo $movie_detail->url; ?>">
							</video>
							<!-- JS code -->
							<!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
							<script src="https://vjs.zencdn.net/ie8/ie8-version/videojs-ie8.min.js"></script>
							<script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.14.1/videojs-contrib-hls.js"></script>
							<script src="https://vjs.zencdn.net/7.2.3/video.js"></script>
							<script>
							var player = videojs('hls-example');
							player.play();
							</script>
  
  
						
					
						<?php endif; ?>
						<!-- Video player generator ends -->

					</div>
				</div>
			</div>
		</div>
		<hr>
		<div class="col-6">
			<div class="row">
				<div class="form-group col-6">
					<input type="submit" class="btn btn-success col-12" value="Update Movie">
				</div>
				<div class="col-6">
					<a href="<?php echo base_url();?>index.php?admin/movie_list" class="btn btn-secondary col-12">Go back</a>
				</div>
			</div>
		</div>
	</div>
</form>
