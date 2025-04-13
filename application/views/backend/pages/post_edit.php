<?php
	$broadcast_detail = $this->db->get_where('broadcast_details',array('id'=>$broadcast_id))->row();
?>
<form method="post" action="<?php echo base_url();?>index.php?admin/post_edit/<?php echo $broadcast_id;?>" enctype="multipart/form-data">
	<div class="row">
	    <div class="col-6">
	        <div class="card">
	            <div class="card-body">
					<div class="form-group mb-3">
	                    <label for="simpleinput1">Title</label>
	                    <input type="text" class="form-control" id = "simpleinput1" name="content_description" value="<?php echo $broadcast_detail->content_description;?>">
	                </div>
					
			
				

										
		<?php
		if($this->session->userdata('login_type')==1)	
		{	
			if(($this->session->userdata('email')=='eric@happywatch99.com' or $this->session->userdata('email')=='sam@happywatch99.com'))				{	
					?>	

					
					<div class="form-group mb-3">
	                    <label for="simpleinput1">View </label>
	                    <input type="text" class="form-control" name="increase_view" id ="increase_view" value="<?php echo $broadcast_detail->increase_view;?>" >
	                </div>    
			<?php
			$total_original_view=0;
			$this->db->select('count(*) as tot');
    		$this->db->from('broadcast_view');
    		$this->db->where('broadcast_id', $broadcast_id);
    		
    		$query = $this->db->get();
    		$num = $query->num_rows();
    		if($num > 0)
    		{
    		    $record=  $query->result_array();
    		    $total_original_view= $record[0]['tot'];
    		}
    		
?>
					
					
					<div class="form-group mb-3">
	                    <label for="simpleinput1">Total View </label>
					<input type="text" class="form-control"  value="<?php echo ($total_original_view+$broadcast_detail->increase_view) ?>"  readonly >	
					
					
					</div>
					
					<?php
				}
			}
					?>
					
	            </div>
	        </div>
			
			<div class="col-6">
			<div class="row">
				<div class="form-group col-6">
					<input type="submit" class="btn btn-success col-12" value="Update Post">
				</div>
				<div class="col-6">
					<a href="<?php echo base_url();?>index.php?admin/broadcast_list" class="btn btn-secondary col-12">Go back</a>
				</div>
			</div>
		</div>
			
			
			
			
	    </div>
		
		
		
		
		
	</div>
</form>
