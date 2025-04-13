<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $page_title; ?> | Happy Watch 99</title>
		<!-- all the meta tags -->
		<?php include 'metas.php'; ?>
		<!-- all the css files -->
		<?php include 'css.php'; ?>
	</head>
	<body class="">
		<div class="wrapper">
			<!-- BEGIN CONTENT -->
			<!-- SIDEBAR -->
			<?php include 'menu.php'; ?>
			<!-- PAGE CONTAINER-->
			<div class="content-page">
				<div class="content">
					<!-- HEADER -->
					<?php include 'header.php'; ?>
					<!--  PAGE TITLE -->
					<div class="page-title">
						<i class="icon-custom-right"></i>
						<h4 class="page-title"><?php echo $page_title;?></h4>
					</div>
					<!-- BEGIN PlACE PAGE CONTENT HERE -->
					<?php
					include 'pages/'.$page_name.'.php';?>
					<!-- END PLACE PAGE CONTENT HERE -->
				</div>
			</div>
			<!-- END CONTENT -->
		</div>
		<!-- all the js files -->
		<?php 
		
		$pagename=$this->uri->segment(2);
		
		if($pagename=='uwatch_create' || $pagename=='user_list1')
		{
		}
		else
		{
		
		include 'js.php';
		?>
		
			<script type="text/javascript">
    
    CKEDITOR.replace('notification');
    
</script>
	
		<?php
		}
		
		?>
	</body>
</html>
