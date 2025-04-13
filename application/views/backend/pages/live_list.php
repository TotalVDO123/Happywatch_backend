<div class="row" >

<?php 
if($this->session->userdata('admin_restricted')!=1)
{
?>	
	
<div class="col-12">
<a href="<?php echo base_url();?>index.php?admin/live_create/" class="btn btn-primary" >
<i class="fa fa-plus"></i>
	Create Live
</a>

<a href="<?php echo base_url();?>index.php?admin/artist_create/" class="btn btn-primary" >
<i class="fa fa-plus"></i>
	Create Series
</a>


<a href="<?php echo base_url();?>index.php?admin/add_live_time/<?php echo $row['live_id'];?>" class="btn btn-info btn-xs btn-mini">
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
                <h4 class="header-title">Live List</h4>
				<table id="basic-datatable" class="table dt-responsive nowrap" width="100%">
					<thead>
						<tr>
							<th>
								#
							</th>
							<th></th>
							<th>Live Title</th>
							
							<th>Genre</th>
							<th>Sub Genre</th>
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
							  
							  $live = $this->db->get('live')->result_array();  
							}
							elseif($this->session->userdata('login_type') == 1 )
							{
							$live = $this->db->get('live')->result_array();
							}
							$counter = 1;
							foreach ($live as $row):
							  ?>
						<tr>
							<td style="vertical-align: middle;"><?php echo $counter++;?></td>
							<td><img src="<?php echo $this->crud_model->get_thumb_url('live' , $row['live_id']);?>" style="height: 60px;" /></td>
							<td style="vertical-align: middle;width:20%;"><?php  echo substr($row['title'], 0, 180); ?></td>
							
							<td style="vertical-align: middle;">
								<?php echo $this->db->get_where('genre',array('genre_id'=>$row['genre_id']))->row()->name;?>
							</td>
							
							<td style="vertical-align: middle;">
							<?php 
							if(!empty($row['sub_genre_id'])) 
							{
							?>
								<?php echo $this->db->get_where('genre',array('genre_id'=>$row['sub_genre_id']))->row()->name;?>
							<?php
							}	
							?>
							</td>
							
							
							
							<?php
							if($row['type']==1)
							{
							?>
							
							<td style="vertical-align: middle;">
								<a href="<?php echo base_url();?>index.php?browse/playseries/<?php echo $row['live_id'];?>"
									target="_blank" class="btn btn-secondary btn-xs btn-mini">
								<i class="fa fa-external-link"></i>visit</a>
								
								<?php 
							if($this->session->userdata('admin_restricted')!=1)
							{
							?>	
								
								<a href="<?php echo base_url();?>index.php?admin/artist_edit/<?php echo $row['live_id'];?>" class="btn btn-info btn-xs btn-mini">
								manage</a>
								<a href="<?php echo base_url();?>index.php?admin/artist_series_delete/<?php echo $row['live_id'];?>" class="btn btn-danger btn-xs btn-mini" onclick="return confirm('Want to delete?')">
								delete</a>
							<?php
							}	
							?>	
								
							</td>
							
							
							<?php
							}
							elseif($row['type']==0)
							{
							?>
							<td style="vertical-align: middle;">
								<a href="<?php echo base_url();?>index.php?browse/playlive/<?php echo $row['live_id'];?>"
									target="_blank" class="btn btn-secondary btn-xs btn-mini">
								<i class="fa fa-external-link"></i>visit</a>
							
		<?php 
		if($this->session->userdata('admin_restricted')!=1)
		{
		?>		
								
								<a href="<?php echo base_url();?>index.php?admin/live_edit/<?php echo $row['live_id'];?>" class="btn btn-info btn-xs btn-mini">
								edit</a>
								
													
						
								
								<a href="<?php echo base_url();?>index.php?admin/live_delete/<?php echo $row['live_id'];?>" class="btn btn-danger btn-xs btn-mini" onclick="return confirm('Want to delete?')">
								delete</a>
								
								
						<?php
						}		
						?>			
							</td>
							
						<?php
							}
						?>	
							
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
            </div>
        </div>
    </div>
</div>
