
<div class="row" >
<div class="col-12">

<?php 
if($this->session->userdata('admin_restricted')!=1)
{
?>		
	
<a href="<?php echo base_url();?>index.php?admin/uwatch_create/" class="btn btn-primary" >
<i class="fa fa-plus"></i>
	Create U Watch
</a>

<?php
}
?>	
	
<?php /* ?>
<a href="<?php echo base_url();?>index.php?admin/add_videos_time/" class="btn btn-info btn-xs btn-mini">
								Ad Timing</a>
<?php */ ?>								

</div>
	
	
<div class="col-12">&nbsp;</div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">U Watch List</h4>
				<table id="basic-datatable" class="table dt-responsive nowrap" width="100%">
					<thead>
						<tr>
							<th>
								#
							</th>
							<th></th>
							<th>Title</th>
							<th>Genre</th>
							<th>Operation</th>
						</tr>
					</thead>
					<tbody>
						<?php
							
							  
								if($this->session->userdata('login_type')==1)
								{
								$uwatches = $this->db->get('u_watch')->result_array();
								}	
								else
								{		
							  $userid=$this->session->userdata('user_id');
							  $this->db->where('user_id', $userid);
		                        
							  
							  $uwatches = $this->db->get('u_watch')->result_array();
								}
						
							
							$counter = 1;
							foreach ($uwatches as $row):
							  ?>
						<tr>
							<td style="vertical-align: middle;"><?php echo $counter++;?></td>
							<td><img src="<?php echo $this->crud_model->get_uwatch_thumb_url( $row['u_watch_thumb']);?>" style="height: 60px;" /></td>
							<td style="vertical-align: middle;"><?php echo $row['title'];?></td>
							<td style="vertical-align: middle;">
								<?php echo $this->db->get_where('genre',array('genre_id'=>$row['genre_id']))->row()->name;?>
							</td>
							<td style="vertical-align: middle;">
								
								<a href="<?php echo base_url();?>index.php?admin/uwatch_edit/<?php echo $row['u_watch_id'];?>" class="btn btn-info btn-xs btn-mini">
								edit</a>	
								
								<a href="<?php echo base_url();?>index.php?admin/uwatch_delete/<?php echo $row['u_watch_id'];?>" class="btn btn-danger btn-xs btn-mini" onclick="return confirm('Want to delete?')">
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
