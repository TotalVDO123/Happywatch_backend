
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-9 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-body">
				<form method="post" action="<?php echo base_url();?>index.php?admin/notification_create">
					<div class="row">
						<div class="col-12">
					
						
								<div class="form-group mb-3">
						<label for="genre_id">User</label>
						<select class="form-control" id="user_id" name="user_id[]" multiple>
							<option value=""> </option>
							<option value="ALL">All User </option>
							<?php
								$query 		=	 $this->db->get('user');
								$users=$query->result_array();
								foreach ($users as $row2):?>
								
								<?php
								$tmpname="";
								if(!empty($row2['name']))
								{
								    $tmpname=$row2['name'];    
								}
								elseif(!empty($row2['mobile']))
								{
								    $tmpname=$row2['mobile'];
								}
								elseif(!empty($row2['email']))
								{
								    $tmpname=$row2['email'];
								}
								?>
								
							<option value="<?php echo $row2['user_id'];?>">
								<?php echo $tmpname;?>
							</option>
							<?php endforeach;?>
						</select>
					</div>
							
					<div class="form-group mb-3">
						<label for="notification">Notification</label>
						<textarea class="form-control" id="notification" name="notification" rows="6"  ></textarea>
					</div>
							
					<div class="form-group">
						<input type="submit" class="btn btn-success" value="Create">
						<a href="<?php echo base_url();?>index.php?admin/notification_list" class="btn btn-secondary">Go back</a>
					</div>
						</div>
					</div>
				</form>
            </div>
        </div>
    </div>
</div>

