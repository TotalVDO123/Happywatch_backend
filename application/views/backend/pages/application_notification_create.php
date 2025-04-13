<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-9 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-body">
				<form method="post" action="<?php echo base_url();?>index.php?admin/application_notification_create">
					<div class="row">
						<div class="col-12">
					   <div class="form-group mb-3">
						<label for="genre_id">Notification Send</label>
						<select class="form-control" id="notification_send" name="notification_send" required>
							<option value=""> Select </option>
							<option value="ALLDEVICE">All Device </option>
							<option value="IOS">IOS</option>
							<option value="ANDROID">ANDROID</option>
								
						</select>
					</div>
							
					<div class="form-group mb-3">
						<label for="notification">Notification</label>
						<textarea class="form-control" id="notification" name="notification" rows="6"></textarea>
					</div>
							
					<div class="form-group">
						<input type="submit" class="btn btn-success" value="Create">
						<a href="<?php echo base_url();?>index.php?admin/application_notification_list" class="btn btn-secondary">Go back</a>
					</div>
						</div>
					</div>
				</form>
            </div>
        </div>
    </div>
</div>
