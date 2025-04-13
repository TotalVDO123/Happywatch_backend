
<?php
//broadcast_details

	$this->db->select('broadcast_details.* , user.name ');
    $this->db->from('broadcast_details');
    $this->db->join('user', 'broadcast_details.user_id = user.user_id ');
	$this->db->order_by("created_date", "desc");
    //$this->db_replica->where(array('favourite.user_id'=>$uid));
    $broadcast_list = $this->db->get()->result_array();



?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">POST LIST</h4>
				<table id="basic-datatable" class="table dt-responsive nowrap" width="100%">
					<thead>
						<tr>
							<th>
								#
							</th>
							<th>User Name</th>
							<th style="width: 40%";>Content Description</th>
							<th style="width: 15%";>Created Date </th>
							<th>Action</th>
							
						</tr>
					</thead>
					<tbody>
						<?php
							
							
							$counter = 1;
							foreach ($broadcast_list as $row):
							  ?>
						<tr>
							<td style="vertical-align: middle;"><?php echo $counter++;?></td>
							
							<?php /* ?>
							<td><img src="<?php echo $this->crud_model->get_thumb_url('movie' , $row['movie_id']);?>" style="height: 60px;" /></td>
							
							<?php */ ?>
							
							<td style="vertical-align: middle;"><?php echo $row['name'];?></td>
							
							<td style="vertical-align: middle;">
								<?php echo $row['content_description'];?>
							</td>
							
							<td style="vertical-align: middle;">
								<?php echo $row['created_date'];?>
							</td>
							
							<td style="vertical-align: middle;">
								
								
								<a href="<?php echo base_url();?>index.php?admin/post_edit/<?php echo $row['id'];?>" class="btn btn-info btn-xs btn-mini">
								edit</a>
								
								
								<a href="<?php echo base_url();?>index.php?admin/broadcast_deletee/<?php echo $row['id']."/".$row['user_id']?>" class="btn btn-danger btn-xs btn-mini" onclick="return confirm('Want to delete?')">
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
