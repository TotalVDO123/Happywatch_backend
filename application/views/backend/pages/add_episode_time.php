<div class="card">

    <div class="row">
        <div class="col-md-6">
            <?php 
                ?>
				<form method="post" action="<?php echo base_url();?>index.php?admin/save_episode_time/" enctype="multipart/form-data">
				
               <!-- <?php echo form_open(base_url() . 'admin/save_episode_time/', array('class' => 'form-horizontal group-border-dashed', 'enctype' => 'multipart/form-data'));?>-->

                <h4 class="text-center"><?php echo 'Ad Timing' ?></h4>
                <hr>
            <div class="card">

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
            </div>
        </div>
        
	</div>
    <div class="row">            
        <div class="col-sm-6 pull-right">
            <input type="hidden" name="vid" value="<?php echo $vid ?>">
			<button type="submit" class="btn btn-primary pull-right waves-effect"> <span class="btn-label"><i class="fa fa-floppy-o"></i></span>Save </button>
        </div>
      
    </div>
</form>


</div>
<!-- <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script> -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.form.min.js"></script>


<script>
    jQuery(document).ready(function() {
        $(".select2").select2();
        $('form').parsley();
        $('#release_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
        $(":file").filestyle({
            input: false
        });

    });

	
	
	</script>



<!--instant image dispaly-->
<script type="text/javascript">
    function showImg(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#thumb_image')
                    .attr('src', e.target.result)
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<script type="text/javascript">
    function showImg2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#poster_image')
                    .attr('src', e.target.result)
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<!--end instant image dispaly-->

<script src="<?php echo base_url(); ?>assets/backend/js/app.min.js"></script>
<!-- third party js -->
<script src="<?php echo base_url(); ?>assets/backend/js/vendor/Chart.bundle.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/backend/js/vendor/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo base_url(); ?>assets/backend/js/vendor/jquery-jvectormap-world-mill-en.js"></script>

<!-- third party js ends -->
<!-- demo app -->

<!-- end demo js-->
<!-- custom js -->
<script src="<?php echo base_url(); ?>assets/backend/js/custom.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<!---
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/parsleyjs/dist/parsley.min.js"></script>


<script src="<?php echo base_url() ?>assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/typeahead.js/bloodhound.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/typeahead.js/typeahead.bundle.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/typeahead.js/typeahead.jquery.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/moment.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/bootstrap-filestyle/src/bootstrap-filestyle.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/plugins/select2/select2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/plugins/summernote/dist/summernote.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/date.js"></script>
-->



<script>
$(document).ready(function(){
  var i=2;
  $(".addmore").on('click',function(){
    var data='<tr>';
       data+='<td><input type="text" name="etime[]" class="etime" placeholder="00:00:00" required onchange="validateTime(this)" ></td>';
	   data+='<td>';
	   data+='<select name="type[]">';
	   data+='<option value="Inline" >Inline</option>';
	   data+='</select>'
	   data+='</td>';
       data+='<td>';
	   data+='<select name="mode[]">';
	   data+='<option value="Offset" >Offset</option>';
	   data+='</select>';
	   data+='</td>';
	   data+='<td><a href="javascript:void(0);" onclick="SomeDeleteRowFunction(this);" ><i class="fa fa-trash-o"></i></a></td>';
     data+='</tr>';
        $('table').append(data);
        i++;
});



 

});
function SomeDeleteRowFunction(btndel) {
	var result = confirm("Are you sure you want to delete this time ?");
	if (result) 
	{	
		$(btndel).closest("tr").remove();
		if (typeof(btndel) == "object") {
			$(btndel).closest("tr").remove();
		} else {
			return false;
		}
	}	
}

	function validateTime(strTime) 
	{
		var regex = new RegExp("([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])");
		if (regex.test(strTime.value)) {
		//alert("true");
		} else {
		alert("Invalid Time");
		strTime.value="";
		}

	}

</script>

<script>
jQuery(document).ready(function() {
 
        $('.etime').datepicker({
			
            format: 'hh-mm-ss',
           
        });
    });
</script>










<script>
//$('#date1').datepicker({
//	stepHour: 2,
//	stepMinute: 10,
//	stepSecond: 10
//});
</script>


<script>
    jQuery(document).ready(function() {        
        $('#focus_keyword').tagsinput();
        $('#tags').tagsinput({
            typeahead: {
                source: ['Amsterdam', 'Washington', 'Sydney', 'Beijing', 'Cairo']
            }
        });
        $('#thumb_link').click(function() {
            $('#thumbnail_content').html('<input type="text" name="thumb_link" class="form-control">');
        });
        $('#thumb_file').click(function() {
            $('#thumbnail_content').html('<input type="file" id="thumbnail_file" onchange="showImg(this);" name="thumbnail" class="filestyle" data-input="false" accept="image/*"></div>');
            $(":file").filestyle({
                input: false
            });
        });

        $('#poster_link').click(function() {
            $('#poster_content').html('<input type="text" name="poster_link" class="form-control">');
        });
        $('#poster_file_btn').click(function() {
            $('#poster_content').html('<input type="file" id="poster_file" onchange="showImg2(this);" name="poster_file" class="filestyle" data-input="false" accept="image/*"></div>');
            $(":file").filestyle({
                input: false
            });
        });

        $('#description').summernote({
            height: 200, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: false // set focus to editable area after initializing summernote
        });

    });
</script>

<script>
    $("#title").keyup(function() {
        var Text = $(this).val();
        Text = Text.toLowerCase();
        Text = Text.replace(/[^\w ]+/g, '');
        Text = Text.replace(/ +/g, '-');
        $("#slug").val(Text);
    });
</script>



