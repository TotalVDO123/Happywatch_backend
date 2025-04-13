<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
				<form method="post" action="<?php echo base_url();?>index.php?admin/user_create" enctype="multipart/form-data">
					<div class="row">
						<div class="col-6">
							<div class="form-group mb-3">
			                    <label for="name">User's Name</label>
			                    <input type="text" class="form-control" id = "name" name="name" required>
			                </div>

							<div class="form-group mb-3">
			                    <label for="email">User's Email</label>
			                    <input type="email" class="form-control" id = "email" name="email" required>
			                </div>
							<div class="form-group mb-3">
			                    <label for="password">User's Password</label>
			                    <input type="password" class="form-control" id = "password" name="password" required>
			                </div>
							
							<div class="form-group mb-3">
        						<label for="featured">User type</label>
        						<select class="form-control select2" id="type" name="type">
        							<option value="0">User (Customer)</option>
        							<option value="2">Admin</option>
        							<option value="3">Distributor</option>
        						</select>
        					</div>
    							
							
							
							
							<div class="form-group">
								<input type="submit" class="btn btn-success" value="Create">
								<a href="<?php echo base_url();?>index.php?admin/user_list" class="btn btn-secondary">Go back</a>
							</div>
						</div>
					</div>
				</form>
            </div>
        </div>
    </div>
</div>
