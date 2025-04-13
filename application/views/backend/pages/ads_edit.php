<?php
	$ads_detail = $this->db->get_where('ads',array('id'=>$id))->row();
?>
<form method="post" action="<?php echo base_url();?>index.php?admin/ads_edit/<?php echo $id;?>" enctype="multipart/form-data">
	<div class="row">
	    <div class="col-8">
	        <div class="card">
	            <div class="card-body">
				
					<div class="form-group mb-3">
	                    <label for="url">First Ads url </label>
						<span class="help">-Enter First Ads url</span>
	                    <input type="text" class="form-control" name="adsURL1" id="adsURL1" value="<?php echo $ads_detail->adsURL1;?>" required>
	                </div>

                    <div class="form-group mb-3">
	                    <label for="url">Ads Timing</label>
	                    <span class="help">Ads timing of first Ads url</span>
	                    <input type="text" class="form-control" name="ads_time_url1" id="ads_time_url1" value="<?php echo $ads_detail->ads_time_url1;?>">Seconds
	                </div>

					<div class="form-group mb-3">
	                    <label for="url">Second Ads url</label>
						<span class="help">- Enter Second Ads url</span>
	                    <input type="text" class="form-control" name="adsURL2" id="adsURL2" value="<?php echo $ads_detail->adsURL2;?>">
	                </div>
					
					
					<div class="form-group mb-3">
                    <label for="url">Ads Timing</label>
                    <span class="help">Ads timing of second Ads url</span>
                    <input type="text" class="form-control" name="ads_time" id="ads_time" value="<?php echo $ads_detail->ads_time;?>" >Minuts
	                </div>
					
					
					
					<div class="form-group mb-3">
	                    <label for="url">Third Ads url</label>
						<span class="help">- Enter Third Ads url</span>
	                    <input type="text" class="form-control" name="adsURL3" id="adsURL3" value="<?php echo $ads_detail->adsURL3;?>">
	                </div>
					<div class="col-6">
					<div class="row">
						<div class="form-group col-6">
							<input type="submit" class="btn btn-success col-12" value="Update Ads">
						</div>
						<div class="col-6">
							<a href="<?php echo base_url();?>index.php?admin/ads_list" class="btn btn-secondary col-12">Go back</a>
						</div>
					</div>
					</div>
	            </div>
	        </div>
	    </div>
		
		<hr>
		
	</div>
</form>
