




<!-- TOP LANDING SECTION -->
<div style="width:100%; height:90px; background-color:#fafafa; border-bottom:solid 1px #dcdde0;">
	<!-- logo -->
	<div style="float: left;">
		<a href="<?php echo base_url();?>index.php?home">
		<img src="<?php echo base_url();?>/assets/global/logo.png" style="margin: 18px 40px; height: 50px;" />
		</a>
	</div>
</div>
<div style="width:100%; height:100%; background-color:#f5f5f5; border-bottom:solid 1px #dcdde0;">
<div class="container"  >
	<div class="row">
		<div class="col-lg-12">
		    <?php 
    			foreach ($broadcast_data as $row){
    			    echo $row['content_description'];
    			}
    		?>
		</div>
	</div>
	<?php include 'footer.php';?>
</div>
</div>