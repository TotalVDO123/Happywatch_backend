<div class="row" >
<div class="col-12">

<a href="<?php echo base_url();?>index.php?admin/artist_create/" class="btn btn-primary" >
<i class="fa fa-plus"></i>
Create Artist
</a>

<!--
<a href="<?php //echo base_url();?>index.php?admin/add_series_time/" class="btn btn-info btn-xs btn-mini">
								Ad Timing</a>
-->								

<div>&nbsp;</div>								
</div>								
</div>																
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Artist List</h4>
                <table id="basic-datatable" class="table dt-responsive nowrap" width="100%">
					<thead>
						<tr>
							<th>
								#
							</th>
							<th></th>
							<th>Title</th>
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
    							  $this->db->where('type', 1);
								  $seriess = $this->db->get('live')->result_array();
							}
							elseif($this->session->userdata('login_type') == 1 )
							{
							
							    $this->db->where('type', 1);
								$seriess = $this->db->get('live')->result_array();
							
							}
							$counter = 1;
							foreach ($seriess as $row):
							  ?>
						<tr>
							<td style="vertical-align: middle;"><?php echo $counter++;?></td>
								<td><img src="<?php echo $this->crud_model->get_thumb_url('live' , $row['live_id']);?>" style="height: 60px;" /></td>
							<td style="vertical-align: middle;"><?php echo $row['title'];?></td>
							<td style="vertical-align: middle;">
								<?php echo $this->db->get_where('genre',array('genre_id'=>$row['genre_id']))->row()->name;?>
							</td>
							
							
								<td style="vertical-align: middle;">
								<?php echo $this->db->get_where('genre',array('genre_id'=>$row['sub_genre_id']))->row()->name;?>
							</td>
							
							
							
							<td style="vertical-align: middle;">
								<a href="<?php echo base_url();?>index.php?browse/playseries/<?php echo $row['live_id'];?>"
									target="_blank" class="btn btn-secondary btn-xs btn-mini">
								<i class="fa fa-external-link"></i>visit</a>
								<a href="<?php echo base_url();?>index.php?admin/artist_edit/<?php echo $row['live_id'];?>" class="btn btn-info btn-xs btn-mini">
								manage</a>
								<a href="<?php echo base_url();?>index.php?admin/artist_series_delete/<?php echo $row['live_id'];?>" class="btn btn-danger btn-xs btn-mini" onclick="return confirm('Want to delete?')">
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
