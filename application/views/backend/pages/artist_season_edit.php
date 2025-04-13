<div class="row">
	<div class="col-md-6 col-sm-6 col-xs-6">
		<a href="<?php echo base_url();?>index.php?admin/artist_edit/<?php echo $live_id;?>"
			class="btn btn-primary" style="clear:both;margin-bottom: 20px;" >
			<i class="mdi mdi-arrow-left-drop-circle-outline"></i>
			Back to series
		</a>
		<a href="<?php echo base_url();?>index.php?browse/playseries/<?php echo $live_id.'/'.$artist_season_id;?>"
			class="btn btn-primary" style="clear:both;margin-bottom: 20px;" target="_blank">
			<i class="mdi mdi-arrow-top-right"></i>
			Visit <?php echo $season_name;?>
		</a>
	</div>
	<div class="col-md-6 col-sm-6 col-xs-6">
		<a href="#" onClick="load_create_form()"
			class="btn btn-primary pull-right" style="clear:both;margin-bottom: 20px;">
		<i class="fa fa-plus"></i>
		Create episode
		</a>
	</div>
</div>
<div class="row">
	<!-- BASIC INFORMATION UPDATE -->
	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="grid simple ">
			<div class="grid-title">
				<h4>Songs List</h4>
			</div>
			<div class="grid-body">
				<?php
					$episodes	=	$this->crud_model->get_episodes_of_artist_season($artist_season_id);
					
					
				/////	print_r($episodes);
					
					?>
				<table class="table table-hover no-more-tables">
					<thead>
						<tr>
							<th>#</th>
							<th>Title</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$counter	=	1;
							foreach ($episodes as $row):
							   
							    
							$artist_episode_id	=	$row['artist_episode_id'];
						
							
							?>
						<tr>
							<td>
								<?php echo 'Songs '.$counter++;?>
							</td>
							<td>
								<?php echo $row['title'];?>
							</td>
							<td>
								<a href="#" onClick="load_edit_form(<?php echo $live_id.','.$artist_season_id.','.$artist_episode_id;?>)"
									class="btn btn-info btn-xs btn-mini">
								edit</a>
								<?php /* ?>
								<a href="<?php echo base_url();?>index.php?admin/add_episode_time/<?php echo $row['episode_id'];?>" class="btn btn-info btn-xs btn-mini">
								Ad Timing</a>
								<?php */ ?>
								
								<a href="<?php echo base_url();?>index.php?admin/songs_episode_delete/<?php echo $live_id.'/'.$artist_season_id.'/'.$artist_episode_id;?>" 
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
	<script>
		function load_edit_form(series_id,season_id,episode_id)
		{
		   
		    
			document.getElementById("form_holder").innerHTML = document.getElementById("edit_episode_form_"+episode_id).innerHTML;
		}
		
		// LOAD THE CREATE EPISODE FORM AT FIRST
		window.onload = function ()
		{
			load_create_form()
		}
		
		function load_create_form()
		{
			document.getElementById("form_holder").innerHTML = document.getElementById("create_episode_form").innerHTML;
		}
	</script>
	<!-- MANAGE SEASONS & EPISODES -->
	<div class="col-md-6 col-sm-12 col-xs-12" id="form_holder">
	</div>
</div>
<!-- CREATE EPISODE FORM -->
<div id="create_episode_form" style="display: none;">
	<div class="grid simple ">
		<div class="grid-title">
			<h4>Create a new song</h4>
		</div>
		<div class="grid-body">
			<form method="post" action="<?php echo base_url();?>index.php?admin/songs_episode_create/<?php echo $live_id.'/'.$artist_season_id;?>"
				enctype="multipart/form-data">
				<div class="form-group">
					<label class="form-label">Title</label>
					<span class="help"></span>
					<div class="controls">
						<input type="text" class="form-control" name="title" value="" required>
					</div>
				</div>
				<div class="form-group">
					<label class="form-label">Song URL</label>
					<span class="help">- any song url </span>
					<div class="controls">
						<input type="text" class="form-control" name="url" id="url" required>
					</div>
				</div>
				<div class="form-group">
					<label class="form-label">Thumbnail</label>
					<span class="help">- icon image of the movie</span>
					<div class="controls">
						<input type="file" class="form-control" name="thumb" required>
					</div>
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-success" value="Create song">
				</div>
			</form>
		</div>
	</div>
</div>
<!-- EDIT EPISODE FORM -->
<?php
	foreach ($episodes as $row):
	$artist_episode_id	=	$row['artist_episode_id'];
	?>
<div id="edit_episode_form_<?php echo $row['artist_episode_id'];?>" style="display: none;">
	<div class="grid simple ">
		<div class="grid-title">
			<h4>Edit Song</h4>
		</div>
		<div class="grid-body">
			
		
			
			
			<form method="post" action="<?php echo base_url();?>index.php?admin/songs_episode_edit/<?php echo $live_id.'/'.$artist_season_id.'/'.$artist_episode_id;?>"
				enctype="multipart/form-data">
				<div class="form-group">
					<label class="form-label">Title</label>
					<span class="help"></span>
					<div class="controls">
						<input type="text" class="form-control" name="title" value="<?php echo $row['title'];?>">
					</div>
				</div>
				<div class="form-group">
					<label class="form-label">Song Url</label>
					<span class="help">- any hosted song</span>
					<div class="controls">
						<input type="text" class="form-control" name="url" id="url" value="<?php echo $row['url'];?>">
					</div>
				</div>
				<div class="form-group">
					<label class="form-label">Thumbnail</label>
					<span class="help">- icon image of the movie</span>
					<div class="controls">
						<input type="file" class="form-control" name="thumb">
					</div>
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-success" value="Update song">
				</div>
			</form>
		</div>
	</div>
</div>
<?php endforeach;?>