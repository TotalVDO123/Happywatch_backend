
<div class="row" >
	
<?php 
	if($this->session->userdata('admin_restricted')!=1)
	{
	?>		
	
<div class="col-12">
<a href="<?php echo base_url();?>index.php?admin/movie_create/" class="btn btn-primary" >
<i class="fa fa-plus"></i>
	Create Movie
</a>

<a href="<?php echo base_url();?>index.php?admin/add_videos_time/" class="btn btn-info btn-xs btn-mini">
								Ad Timing</a>

</div>
<?php
	}	
?>
	
<div class="col-12">&nbsp;</div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Movie List</h4>
				<table id="basic-datatable" class="table dt-responsive nowrap" width="100%">
					<thead>
						<tr>
							<th>
								#
							</th>
							<th></th>
							<th>Movie Title</th>
							<th>Genre</th>
							<th>Operation</th>
						</tr>
					</thead>
					<tbody>
						<?php
							
							if($this->session->userdata('login_type') == 2 OR $this->session->userdata('login_type') == 3)
							{
							  
							  $userid=$this->session->userdata('user_id');
							  $this->db->where('user_id', $userid);
		                        //$query 		=	 $this->db->get('plan');
							  
							  $movies = $this->db->get('movie')->result_array();
							}
							elseif($this->session->userdata('login_type') == 1 )
							{
							
							$movies = $this->db->get('movie')->result_array();
							}
							$counter = 1;
							foreach ($movies as $row):
							  ?>
						<tr>
							<td style="vertical-align: middle;"><?php echo $counter++;?></td>
							<td><img src="<?php echo $this->crud_model->get_thumb_url('movie' , $row['movie_id']);?>" style="height: 60px;" /></td>
							<td style="vertical-align: middle;"><?php echo $row['title'];?></td>
							<td style="vertical-align: middle;">
								<?php echo $this->db->get_where('genre',array('genre_id'=>$row['genre_id']))->row()->name;?>
							</td>
							<td style="vertical-align: middle;">
								<a href="<?php echo base_url();?>index.php?browse/playmovie/<?php echo $row['movie_id'];?>"
									target="_blank" class="btn btn-secondary btn-xs btn-mini">
								<i class="fa fa-external-link"></i>visit</a>
								
								<?php 
							if($this->session->userdata('admin_restricted')!=1)
							{
							?>	

								
								<a href="<?php echo base_url();?>index.php?admin/movie_edit/<?php echo $row['movie_id'];?>" class="btn btn-info btn-xs btn-mini">
								edit</a>
								
																
								<a href="<?php echo base_url();?>index.php?admin/movie_delete/<?php echo $row['movie_id'];?>" class="btn btn-danger btn-xs btn-mini" onclick="return confirm('Want to delete?')">
								delete</a>
								
							<?php
							}	
							?>	
								
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
            </div>
        </div>
    </div>
</div>
