<?php include 'header_browse.php';?>
<!-- MOVIE LIST, GENRE WISE LISTING -->
<?php
	$movies		=	$this->crud_model->get_search_result('movie' , $search_key);
	$series		=	$this->crud_model->get_search_result('series', $search_key);
	$live		=	$this->crud_model->get_search_result('live', $search_key);
	?>
<div class="row" style="margin:20px 60px;">
	<h4>
		<?php echo get_phrase('Search_result_for');?> : "<?php echo $search_key;?>"
	</h4>
	<div class="content">
		<div class="grid">
			<?php 
				foreach ($movies as $row)
				{
					$title	=	$row['title'];
					$link	=	base_url().'index.php?browse/playmovie/'.$row['movie_id'];
					$thumb	=	$this->crud_model->get_thumb_url('movie' , $row['movie_id']);
					include 'thumb.php';
				}
				
				foreach ($series as $row)
				{
					$title	=	$row['title'];
					$link	=	base_url().'index.php?browse/playseries/'.$row['series_id'];
					$thumb	=	$this->crud_model->get_thumb_url('series' , $row['series_id']);
					include 'thumb.php';
				}
				foreach ($live as $row)
				{
					$title	=	$row['title'];
					$link	=	base_url().'index.php?browse/playlive/'.$row['live_id'];
					$thumb	=	$this->crud_model->get_thumb_url('live' , $row['live_id']);
					include 'thumb.php';
				}
				?>
		</div>
	</div>
	<div style="clear: both;"></div>
	<ul class="pagination">
		<?php echo $this->pagination->create_links(); ?>
	</ul>
</div>
<div class="container" style="margin-top: 90px;">
	<hr style="border-top:1px solid #333;">
	<?php include 'footer.php';?>
</div>