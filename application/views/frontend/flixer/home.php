<?php include 'header_browse.php';
error_reporting(0);

?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/frontend/' . $selected_theme;?>/hovercss/demo.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/frontend/' . $selected_theme;?>/hovercss/set1.css" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/frontend/image_slider/lightslider.min.css" />

<script src="<?php echo base_url()?>assets/frontend/image_slider/lightslider.min.js" ></script>




<style>
	.movie_thumb{}
	.btn_opaque{font-size:20px; border: 1px solid #939393;text-decoration: none;margin: 10px;background-color: rgba(0, 0, 0, 0.74); color: #fff;}
	.btn_opaque:hover{border: 1px solid #939393;text-decoration: none;background-color: rgba(57, 57, 57, 0.74);color:#fff;}
</style>
<!-- TOP FEATURED SECTION -->
<?php
	$featured_movie		=	$this->db->get_where('movie', array('featured'=>1))->row();
	
	?>
<div style="background: url(<?php echo $this->crud_model->get_poster_url('movie' , $featured_movie->movie_id);?>); background-size:cover; background-size: 100%;background-repeat: no-repeat;">
	<div style="font-size:5vw;font-weight: bold;clear: both;padding: 8% 0% 0% 2%;color: #fff;">
		<?php echo $featured_movie->title;?>
		<div style="font-size: 40%; letter-spacing: .2px; color: #ccc; font-weight: 100; width:50%;">
			<?php echo $featured_movie->description_short;?>
		</div>

		<a href="<?php echo base_url();?>index.php?browse/playmovie/<?php echo $featured_movie->movie_id;?>" 
			class="btn btn-danger btn-lg" style="font-size: 40%; margin-bottom:130px;"> 
		<b><i class="fa fa-play"></i> <?php echo get_phrase('PLAY');?></b>
		</a>
		
		</div>
</div>
<script>
	// submit the add/delete request for mylist
	// type = movie/series, task = add/delete, id = movie_id/series_id
	function process_list(type, task, id)
	{
		$.ajax({
			url: "<?php echo base_url();?>index.php?browse/process_list/" + type + "/" + task + "/" + id, 
			success: function(result){
			//alert(result);
			if (task == 'add')
			{
				$("#mylist_button_holder").html( $("#mylist_delete_button").html() );
			}
			else if (task == 'delete')
			{
				$("#mylist_button_holder").html( $("#mylist_add_button").html() );
			}
		}});
	}
	
	// Show the add/delete wishlist button on page load
	$( document ).ready(function() {
	
		// Checking if this movie_id exist in the active user's wishlist
		mylist_exist_status = "<?php echo $this->crud_model->get_mylist_exist_status('movie' , $featured_movie->movie_id);?>";
	
		if (mylist_exist_status == 'true')
		{
			$("#mylist_button_holder").html( $("#mylist_delete_button").html() );
		}
		else if (mylist_exist_status == 'false')
		{
			$("#mylist_button_holder").html( $("#mylist_add_button").html() );
		}
	});
</script>
<!-- MY LIST, GENRE WISE LISTING & SLIDER -->
<?php 
	$genres		=	$this->crud_model->get_genres();
	foreach ($genres as $row):
		// count movie number of this genre, if no found then return.
		$genres_name= str_replace(' ','_',  $row['name']);
		$this->db->where('genre_id' , $row['genre_id']);
        $total_result = $this->db->count_all_results('movie');
        if ($total_result == 0)
        	continue;
	?>
<div class="row" style="margin:20px 60px;">
	<h4><?php echo $row['name'];?></h4>
	<div class="content">
		<div class="grid">
		<ul id="<?php echo $genres_name ?>" class="content-slider">    
			<?php 
				//$movies	= $this->crud_model->get_movies($row['genre_id'] , 5, 0);
				
				$movies	= $this->crud_model->get_movies($row['genre_id'] );
				foreach ($movies as $row)
				{
					$title	=	$row['title'];
					$link	=	base_url().'index.php?browse/playmovie/'.$row['movie_id'];
					$thumb	=	$this->crud_model->get_thumb_url('movie' , $row['movie_id']);
					echo '<li>';
					include 'thumb.php';
					echo '</li>';
				}
				?>
		</ul>
		</div>
	</div>
</div>
<?php 

echo '<script type="text/javascript">
  $(document).ready(function() {
    $("#'.$genres_name.'").lightSlider({
            keyPress:false,
            item:4,
            loop:true
        }) 
  });
</script>';




endforeach;?>
<div class="container" style="margin-top: 90px;">
	<hr style="border-top:1px solid #333;">
	<?php include 'footer.php';?>
</div>