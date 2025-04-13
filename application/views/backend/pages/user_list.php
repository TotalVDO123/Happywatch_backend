<!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">-->
<?php 
if($this->session->userdata('admin_restricted')!=1)
{
?>	
<a href="<?php echo base_url();?>index.php?admin/user_create/" class="btn btn-primary" style="margin-bottom: 20px;">
<i class="fa fa-plus"></i>
Create User
</a>

<?php
}
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
				 <h4 class="header-title">User List</h4>
				<p class="text-muted font-14 mb-4">
               
				<form method="POST" action="<?php echo base_url() ?>index.php?admin/user_list">
				<div class="row" >
				<!--<div class="col">
				<h4 class="header-title">User List</h4>
				</div>-->	
				
				<div class="col"  >
				<input type="text" name="s_email" placeholder="Enter Email" value="<?php echo $s_email ?>" >
				</div>	
				
				<div class="col" >
				<input type="text" name="s_mobile" placeholder="Enter Mobile" value="<?php echo $s_mobile ?>">
				</div>		
					
				<div class="col" >
				<input type="text" name="name" placeholder="Enter Name" value="<?php echo $s_name ?>">
				</div>	
					
				<div class="col">
				<input type="submit" name="Search" value="  Search" >
				</div>	
				<div class="col">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</div>	
				
				</div>
				</form>	
				
                <p class="text-muted font-14 mb-4">
               
				
				<!--  <table id="basic-datatable11" class="table dt-responsive nowrap" width="100%">	-->
				<table id="basic-datatable11" class="table dt-responsive nowrap" width="100%">					
					
					<thead>
						<tr>
							<th>
								#
							</th>
							<th>Email</th>
							<th>Mobile</th>
							<th>Name</th>
							<th>Created Date</th>
							<th>Manage</th>
						</tr>
					</thead>
					<tbody>
						<?php
						
						/// $this->db->order_by('channel_id', 'desc');
        				//$this->db->where('type', 0);
        				//$query = $this->db->get('user', 15, 0);
						//$users =$query->result_array();
						
						
						$counter = 1;
							foreach ($users as $row):
							  ?>
						<tr>
							<td>
								<?php echo $counter;?>
							</td>
							
							<td style="text-transform: lowercase;"><?php echo $row['email'];?></td>
							
							
							<td>
							    <?php
							    if(!empty($row['mobile']))
							    {
							    ?>   
							    <?php echo '('. $row['country_code'].') '.  $row['mobile'];?>
							
							    <?php
							    }
							    ?>
							</td>
							<td ><?php echo $row['name'];?></td>
							<td style="text-transform: uppercase;"><?php echo $row['created_date'];?></td>
							
							<td>
							
								<?php 
						if($this->session->userdata('admin_restricted')!=1)
						{
						?>		
								<a href="<?php echo base_url();?>index.php?admin/user_edit/<?php echo $row['user_id'];?>" class="btn btn-info btn-xs btn-mini">
								edit</a>
						<?php
						}		
						?>			
								
								<a href="<?php echo base_url();?>index.php?admin/user_delete/<?php echo $row['user_id'];?>" class="btn btn-danger btn-xs btn-mini" onclick="return confirm('Want to delete?')">
								delete</a>
								
							
								
							</td>
						</tr>
						<?php 
						$counter++;
						endforeach;?>
					</tbody>
					
					
					
                </table>
				
            </div>
			
		
       <div class="align-self-center">
           <?php echo $links ?>
        </div>
  
			
			
        </div>
    </div>
</div>
