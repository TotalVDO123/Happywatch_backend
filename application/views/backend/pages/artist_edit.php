<?php
	$series_detail = $this->db->get_where('live',array('live_id'=>$live_id,'type'=>1))->row();
?>
<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
				<form method="post" action="<?php echo base_url();?>index.php?admin/artist_edit/<?php echo $live_id;?>" enctype="multipart/form-data">
	                <div class="form-group mb-3">
	                    <label for="title">Title</label>
	                    <input type="text" class="form-control" id = "title" name="title" value="<?php echo $series_detail->title;?>">
	                </div>
	                
	                <div class="form-group mb-3">
	                    <label for="simpleinput1">Subtitle</label>
	                    <input type="text" class="form-control" id = "sub_title" name="sub_title" value="<?php echo $series_detail->sub_title;?>" required>
	                </div>

	                <div class="form-group mb-3">
	                    <label for="thumb">Thumbnail</label>
						<span class="help">- icon image of the artist</span>
	                    <input type="file" class="form-control" name="thumb">
	                </div>

	                <div class="form-group mb-3">
	                    <label for="poster">Poster</label>
						<span class="help">- large banner image of the artist</span>
	                    <input type="file" class="form-control" name="poster">
	                </div>

                    <div class="form-group mb-3">
	                    <label for="simpleinput1">Audio Track </label>
	                    <input type="text" class="form-control" id = "audio_track " name="audio_track" value="<?php echo $series_detail->audio_track;?>" required>
	                </div>


					<div class="form-group mb-3">
						<label for="description_short">Short Description</label>
						<textarea class="form-control" id="description_short" name="description_short" rows="6"><?php echo $series_detail->description_short;?></textarea>
					</div>

					<div class="form-group mb-3">
						<label for="description_long">Long Description</label>
						<textarea class="form-control" id="description_long" name="description_long" rows="6"><?php echo $series_detail->description_long;?></textarea>
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
									$actors	=	$series_detail->actors;
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
								//$genres	=	$this->crud_model->get_genres();
								
									$this->db->where_in('genre_id',$series_detail->genre_id);
	                          
								$query 		=	 $this->db->get('genre');
                                $genres= $query->result_array();
								
								
								foreach ($genres as $row2):?>
							<option
								<?php if ( $series_detail->genre_id == $row2['genre_id']) echo 'selected';?>
								value="<?php echo $row2['genre_id'];?>">
								<?php echo $row2['name'];?>
							</option>
							<?php endforeach;?>
						</select>
					</div>


                    <div class="form-group mb-3">
						<label for="genre_id">Sub Genre</label>
						<span class="help"></span>
						<select class="form-control select2" id="genre_id" name="sub_genre_id">
							<?php
							
								//$genres	=	$this->crud_model->get_genres();
								$this->db->where_in('parent_id',$series_detail->genre_id);
	                          
								$query 		=	 $this->db->get('genre');
                                $genres= $query->result_array();
							
							
								foreach ($genres as $row2):?>
							<option <?php if ( $series_detail->sub_genre_id == $row2['genre_id']) echo 'selected';?> value="<?php echo $row2['genre_id'];?>">
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
								<?php if ( $series_detail->year == $i) echo 'selected';?>
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
								<?php if ( $series_detail->rating == $i) echo 'selected';?>
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
							<option value="1" <?php if ( $series_detail->featured == 1) echo 'selected';?>>Yes</option>
							<option value="0" <?php if ( $series_detail->featured == 0) echo 'selected';?>>No</option>
							
						</select>
					</div>
					
					
					<div class="row">
						<div class="form-group col-6">
							<input type="submit" class="btn btn-success col-12" value="Update">
						</div>
						<div class="col-6">
							<a href="<?php echo base_url();?>index.php?admin/live_list" class="btn btn-secondary col-12">Go back</a>
						</div>
					</div>
				</form>
            </div>
        </div>
    </div>
	<div class="col-6">
		<div class="card">
            <div class="card-body">
                <h4 class="header-title">Artist & Songs</h4>
				<a href="<?php echo base_url();?>index.php?admin/artist_season_create/<?php echo $live_id;?>"
					class="btn btn-primary pull-right" style="margin-bottom: 20px;">
				<i class="fa fa-plus"></i>
				Create Artist Playlist
				</a>

				<table class="table table-hover table-centered mb-0" width="100%">
					<tbody>
						<?php
							$seasons	=	$this->crud_model->get_seasons_of_artist( $live_id);
							foreach ($seasons as $row):
							?>
						<tr>
							<td>
								<i class="fa fa-dot-circle-o"></i>
								<?php echo $row['name'];?>
							</td>
							<td>
								<?php
									$episodes	=	$this->crud_model->get_episodes_of_artist_season($row['artist_season_id']);
									echo count($episodes);
									?>
								Songs
							</td>
							<td>
								<a href="<?php echo base_url();?>index.php?admin/artist_season_edit/<?php echo $live_id.'/'.$row['artist_season_id'];?>"
									class="btn btn-info btn-xs btn-mini">
								manage songs</a>
								<a href="<?php echo base_url();?>index.php?admin/artist_season_delete/<?php echo $live_id.'/'.$row['artist_season_id'];?>"
									class="btn btn-danger btn-xs btn-mini" onclick="return confirm('Want to delete?')">
								delete</a>
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
                </table>
            </div>
        </div>
	</div>
</div>
