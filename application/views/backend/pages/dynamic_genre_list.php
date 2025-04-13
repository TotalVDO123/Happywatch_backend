    <meta name="viewport" content="width=device-width, initial-scale=1">
   <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<div class="container">
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-9 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Genre List</h4>
                <table id="basic-datatable11" class="table table-bordered" width="100%">
					<thead>
						<tr>
							<th>
								#
							</th>
							<th>Genre Name</th>
							
						</tr>
					</thead>
					
					<tbody class="row_position" >
						<?php
							//$genres = $this->crud_model->get_genres();
							$genres = $this->crud_model->get_sort_genres();
							
							$counter = 1;
							foreach ($genres as $row):
							  ?>
						
						<tr  id="<?php echo $row['genre_id'] ?>">
							<td><?php echo $counter++;?></td>
							<td style="text-transform: uppercase;"><?php echo $row['name'];?></td>
						</tr>
						<?php endforeach;?>
					</tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">

    $( ".row_position" ).sortable({

        delay: 150,

        stop: function() {

            var selectedData = new Array();

            $('.row_position>tr').each(function() {

                selectedData.push($(this).attr("id"));

            });

            updateOrder(selectedData);

        }

    });


    function updateOrder(data) {

        $.ajax({

            url:"<?php echo base_url() ?>/index.php?admin/ajax_genre_update_sno",

            type:'post',

            data:{position:data},

            success:function(){

                alert('your change successfully saved');

            }

        })

    }

</script>