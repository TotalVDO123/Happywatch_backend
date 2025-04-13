<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
				<form method="post" action="<?php echo base_url();?>index.php?admin/artist_create" enctype="multipart/form-data">
	                <div class="form-group mb-3">
	                    <label for="title"> Title</label>
	                    <input type="text" class="form-control" id = "title" name="title" required>
	                </div>

                    <div class="form-group mb-3">
	                    <label for="simpleinput1">Subtitle</label>
	                    <input type="text" class="form-control" id = "sub_title" name="sub_title" required>
	                </div>
    


	                <div class="form-group mb-3">
	                    <label for="thumb">Thumbnail</label>
						<span class="help">- icon image of the artist</span>
	                    <input type="file" class="form-control" name="thumb" >
	                </div>

	                <div class="form-group mb-3">
	                    <label for="poster">Poster</label>
						<span class="help">- large banner image of the artist</span>
	                    <input type="file" class="form-control" name="poster" >
	                </div>

                    <div class="form-group mb-3">
	                    <label for="simpleinput1">Audio Track </label>
	                    <input type="text" class="form-control" id = "audio_track " name="audio_track" required>
	                </div>

					<div class="form-group mb-3">
						<label for="description_short">Short Description</label>
						<textarea class="form-control" id="description_short" name="description_short" rows="6"></textarea>
					</div>

					<div class="form-group mb-3">
						<label for="description_long">Long Description</label>
						<textarea class="form-control" id="description_long" name="description_long" rows="6"></textarea>
					</div>

					<div class="form-group mb-3">
						<label for="actors">Actors</label>
						<span class="help">- select multiple actors</span>
						<select class="form-control select2" id="actors" multiple name="actors[]">
							
							<?php
								$actors	=	$this->db->get('actor')->result_array();
								foreach ($actors as $row2):?>
							<option value="<?php echo $row2['actor_id'];?>">
								<?php echo $row2['name'];?>
							</option>
							<?php endforeach;?>
						</select>
					</div>

					<div class="form-group mb-3">
						<label for="genre_id">Genre</label>
						<span class="help">- genre must be selected</span>
						<select class="form-control select2" id="genre_id" name="genre_id" required>
						<option value="">Select Parent Genre</option>	
							<?php
							
								//$genres	=	$this->crud_model->get_genres();
								$this->db->where('parent',1);
	                          
								$query 		=	 $this->db->get('genre');
                                $genres= $query->result_array();
							
							
								foreach ($genres as $row2):?>
							
							<option value="<?php echo $row2['genre_id'];?>">
								<?php echo $row2['name'];?>
							</option>
							<?php endforeach;?>
						</select>
					</div>

	                <div class="form-group mb-3">
						<label for="genre_id">Sub Genre</label>
						<span class="help"></span>
						<select class="form-control select2" id="sub_genre_id" name="sub_genre_id">
							<?php
							
								//$genres	=	$this->crud_model->get_genres();
								//$this->db->where_in('parent_id',39);
	                          
								//$query 		=	 $this->db->get('genre');
                                //$genres= $query->result_array();
							?>
							
								
							
							
						</select>
					</div>







					<div class="form-group mb-3">
						<label for="year">Publishing Year</label>
						<span class="help">- year of publishing time</span>
						<select class="form-control select2" id="year" name="year">
							<?php for ($i = date("Y"); $i > 2000 ; $i--):?>
							<option value="<?php echo $i;?>">
								<?php echo $i;?>
							</option>
							<?php endfor;?>
						</select>
					</div>

					<div class="form-group mb-3">
						<label for="rating">Rating</label>
						<span class="help">- star rating of the movie</span>
						<select class="form-control select2" id="rating" name="rating">
							<?php for ($i = 0; $i <= 5 ; $i++):?>
							<option value="<?php echo $i;?>">
								<?php echo $i;?>
							</option>
							<?php endfor;?>
						</select>
					</div>
					<input type="hidden" name="type" value="1">
					<div class="form-group mb-3">
						<label for="featured">Featured</label>
						<span class="help">- featured movie will be shown in home page</span>
						<select class="form-control select2" id="featured" name="featured">
							<option value="1">Yes</option>
							<option value="0">No</option>
							
						</select>
					</div>
					
					
					<div class="row">
						<div class="form-group col-6">
							<input type="submit" class="btn btn-success col-12" value="Create">
						</div>
						<div class="col-6">
							<a href="<?php echo base_url();?>index.php?admin/music_list" class="btn btn-secondary col-12">Go back</a>
						</div>
					</div>
				</form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

<script>
$(document).ready(function() {
$("#genre_id").change(function(){
	
	$.ajax({
  type: "POST",
  url: '<?php echo base_url()?>index.php?admin/ajax_subgenre',
  data: {parent_id:$("#genre_id").val()},
  cache: false,
  success: function(data){
     //alert(data);
	  $("#sub_genre_id").html(data);
  }
});
});	
});	
</script>	