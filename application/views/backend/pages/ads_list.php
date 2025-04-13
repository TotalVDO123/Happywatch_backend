<?php 
if($this->session->userdata('admin_restricted')!=1)
{
?>	
	<a href="<?php echo base_url();?>index.php?admin/ads_create/" class="btn btn-primary" style="margin-bottom: 20px;">
	<i class="fa fa-plus"></i>
		Create Ads
	</a>

<?php
}
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Ads List</h4>
				<table id="basic-datatable" class="table dt-responsive nowrap" width="100%">
					<thead>
						<tr>
							<th>
								#
							</th>
						
							<th  >First AdsURL</th>
							<th>1st AdsURL Time</th>
							<th>Second AdsURL</th>
							<th>2nd AdsURL Time</th>
							<th>Third AdsURL</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$ads = $this->db->get('ads')->result_array();
							$counter = 1;
							foreach ($ads as $row):
							  ?>
						<tr>
							<td style="vertical-align: middle;"><?php echo $counter++;?></td>
							
						
							<td style="vertical-align: middle;" title=<?php echo $row['adsURL1']; ?>  ><?php echo substr( $row['adsURL1'],0,25);?></td>
							
							
							<td style="vertical-align: middle;" title=<?php echo $row['ads_time_url1']; ?>  ><?php echo  $row['ads_time_url1'];?> Seconds</td>
							
							
							<td style="vertical-align: middle;" title=<?php echo $row['adsURL2']; ?> ><?php echo substr( $row['adsURL2'],0,25);?></td>
							
							
							<td style="vertical-align: middle;" title=<?php echo $row['ads_time']; ?> ><?php echo  $row['ads_time']?> Minuts
							</td>
							
							
							
							<td style="vertical-align: middle;" title=<?php echo $row['adsURL3']; ?> ><?php echo substr( $row['adsURL3'],0,25);?></td>
							
							<td style="vertical-align: middle;">
<?php 
if($this->session->userdata('admin_restricted')!=1)
{
?>	
								
								<a href="<?php echo base_url();?>index.php?admin/ads_edit/<?php echo $row['id'];?>" class="btn btn-info btn-xs btn-mini">
								edit</a>
								<a href="<?php echo base_url();?>index.php?admin/ads_delete/<?php echo $row['id'];?>" class="btn btn-danger btn-xs btn-mini" onclick="return confirm('Want to delete?')">
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
