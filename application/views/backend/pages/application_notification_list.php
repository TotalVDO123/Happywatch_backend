<?php
if( $this->session->userdata('login_type') == 1 )
{
?>
<a href="<?php echo base_url();?>index.php?admin/application_notification_create/" class="btn btn-primary" style="margin-bottom: 20px;">
<i class="fa fa-plus"></i>
	Create Application Notification
</a>





<?php
}
?>
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-9 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Application Notification List</h4>
                <table id="basic-datatable" class="table dt-responsive nowrap" width="100%">
					<thead>
						<tr>
							<th>
								#
							</th>
							<th style="width: 344.8px;" >Notification</th>
							<th>Send Device</th>
							<th>Operation</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$query 		=	 $this->db->get('application_notification');
                            $notifications=$query->result_array();
							
							$counter = 1;
							foreach ($notifications as $notification):
							  ?>
						<tr>
							<td><?php echo $counter++;?></td>
							<td style="text-transform: uppercase;"><?php echo $notification['notification'];?></td>
							
							<td style="text-transform: uppercase;"><?php echo $notification['notification_send'] ;?></td>
							
							<td>
								<a href="<?php echo base_url();?>index.php?admin/application_notification_delete/<?php echo $notification['id'];?>" class="btn btn-danger btn-xs btn-mini" onclick="return confirm('Want to delete?')">
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
