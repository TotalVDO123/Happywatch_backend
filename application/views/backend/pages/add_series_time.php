
<div class="card">

    <div class="row">
        <div class="col-md-6">
            <?php 
                ?>
				<form method="post" action="<?php echo base_url();?>index.php?admin/save_series_time/" enctype="multipart/form-data">
				
               

                <h4 class="text-center"><?php echo 'Ad Timing' ?></h4>
                <hr>
            <div class="card">
                
                <table class="table">
				  
					<tr>
					  <td scope="col" style="width:60%" ><strong>Preroll</strong></td>
					  <td scope="col"><strong>Enable or Disable</strong> <input type="checkbox" name="is_preroll" <?php if($add_time_data->is_preroll==1 ) { echo 'checked'; } ?>  ></td>
					</tr>
                    <tr>
					  <td scope="col" style="width:60%" ><strong>Midroll (Interval Minutes)</strong></td>
					  <td scope="col">
					     <select class="form-control select2" id="add_time" name="add_time">
						    <?php
						    for($ii=1;$ii<=60;$ii++)
						    {
						    ?>
						    <option value="<?php echo  $ii ?>" <?php if(abs($add_time_data->add_time)==$ii) { echo 'selected= selected'; } ?> >
						    <?php echo $ii ?>
						    </option>
					        <?php
						    }
					        ?>
					    </select> 
					      
					      
					  </td>
					</tr>
                <tr>
                <td>
		            <button type="button" onclick="window.location.href='<?php echo base_url() ?>index.php?admin/series_list/' "  class="btn btn-primary pull-right waves-effect"> <span class="btn-label"></span>Back </button>    
		            <button type="submit" class="btn btn-primary pull-right waves-effect"> <span class="btn-label"><i class="fa fa-floppy-o"></i></span>Save </button>            
		        </td>    
                <td align="left" >
                    
                </td>
                </tr>
                </table>
                
				<?php /* ?>
				<table class="table">
				  <thead>
					<tr>
					  <th scope="col">Time</th>
					  <th scope="col">Type</th>
					  <th scope="col">Mode</th>
					  <th scope="col">Delete</th>
					</tr>
				  </thead>
				  <tbody>
				   <?php
				   foreach($add_time_data as $row)
				   {
				   ?> 	   

				<tr>
				   <td><input type="text" name="etime[]" value="<?php echo $row['add_time'] ?>" placeholder="00:00:00" onchange="validateTime(this)" required ></td>
				   <td>
				   <select name="type[]">
				   <option value="Inline" >Inline</option>
				   </select>
				   </td>
				   <td>
				   <select name="mode[]">
				   <option value="Offset" >Offset</option>
				   </select>
				   </td>
				   <td><a href="javascript:void(0);" onclick="SomeDeleteRowFunction(this);" ><i class="fa fa-trash-o"></i></a></td>
				</tr>
				   
				<?php	   
				   }
				   ?>
				   
				  </tbody>
				</table>
				
				<button type="button" class='addmore'  >+ Add More</button>
            <?php */ ?>
                
            </div>
        </div>
        
	</div>
    <?php /* ?>
    <div class="row">            
        <div class="col-sm-6 pull-right">
            <input type="hidden" name="vid" value="<?php echo $vid ?>">
			<button type="submit" class="btn btn-primary pull-right waves-effect"> <span class="btn-label"><i class="fa fa-floppy-o"></i></span>Save </button>
        </div>
      
    </div>
    <?php */ ?>

</form>


</div>
<!-- <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script> -->




