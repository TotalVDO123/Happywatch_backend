<div class="row">
	<!-- TOTAL Live NUMBER -->
	
	 <?php
	  if($this->session->userdata('login_type') == 2 OR $this->session->userdata('login_type') == 1 OR $this->session->userdata('login_type') == 3)
	  {
	  ?>
	<div class="col-md-3 col-sm-12 ">
		<div class="card widget-flat">
			<div class="card-body">
				<div class="float-right">
					<i class="mdi mdi-movie widget-icon"></i>
				</div>
				<h5 class="text-muted font-weight-normal mt-0" title="Number of Customers"><?php echo 'Total Live TV'; ?></h5>
				<h3 class="mt-3 mb-3"><?php 
				if($this->session->userdata('login_type') == 2 OR $this->session->userdata('login_type') == 3 )
				{
				$this->db->where('user_id' , $this->session->userdata('user_id')); 
				}
				echo $this->db->from('live')->count_all_results();?>
				</h3>
			</div>
		</div>
	</div>
	<!-- TOTAL VIDEO NUMBER -->
	<div class="col-md-3 col-sm-12 ">
		<div class="card widget-flat">
			<div class="card-body">
				<div class="float-right">
					<i class="mdi mdi-movie widget-icon"></i>
				</div>
				<h5 class="text-muted font-weight-normal mt-0" title="Number of Customers"><?php echo get_phrase('total_movies'); ?></h5>
				<h3 class="mt-3 mb-3"><?php 
				if($this->session->userdata('login_type') == 2 OR $this->session->userdata('login_type') == 3 )
				{
				$this->db->where('user_id' , $this->session->userdata('user_id')); 
				}
				echo $this->db->from('movie')->count_all_results();?>
				</h3>
			</div>
		</div>
	</div>
	<!-- TOTAL TV SERIES NUMBER -->
	<div class="col-md-3 col-sm-12 ">
		<div class="card widget-flat">
			<div class="card-body">
				<div class="float-right">
					<i class="mdi mdi-movie-roll widget-icon"></i>
				</div>
				<h5 class="text-muted font-weight-normal mt-0" title="Number of Customers"><?php echo 'Total TV Series'; ?></h5>
				<h3 class="mt-3 mb-3"><?php 
				
				if($this->session->userdata('login_type') == 2 OR $this->session->userdata('login_type') == 3 )
				{
				$this->db->where('user_id' , $this->session->userdata('user_id')); 
				}
				
				echo $this->db->from('series')->count_all_results();?>
				</h3>
			</div>
		</div>
	</div>
	
	<?php
	  }
	
	if($this->session->userdata('login_type') == 1)
	{
	?>
	<!-- TOTAL EPISODE NUMBER -->
	<div class="col-md-3 col-sm-12 ">
		<div class="card widget-flat">
			<div class="card-body">
				<div class="float-right">
					<i class="mdi mdi-video-stabilization widget-icon"></i>
				</div>
				<h5 class="text-muted font-weight-normal mt-0" title="Number of Customers"><?php echo get_phrase('total_episodes'); ?></h5>
				<h3 class="mt-3 mb-3"><?php echo number_format( $this->db->from('episode')->count_all_results());?></h3>
			</div>
		</div>
	</div>
</div>
<div style="margin: 20px;"></div>
<div class="row">
	<!-- TOTAL USER NUMBER -->
	<div class="col-md-3 col-sm-12 ">
		<div class="card widget-flat">
			<div class="card-body">
				<div class="float-right">
					<i class="mdi mdi-account-multiple-check widget-icon"></i>
				</div>
				<h5 class="text-muted font-weight-normal mt-0" title="Number of Customers"><?php echo get_phrase('total_registered_user'); ?></h5>
				<h3 class="mt-3 mb-3"><?php echo number_format ( ($this->db->from('user')->count_all_results())+1508797+1000000-1510000) ?></h3>
			</div>
		</div>
	</div>
	<!-- TOTAL ACTIVE SUBSCRIPTION -->
	<?php /* ?>
	<div class="col-md-3 col-sm-12 ">
		<div class="card widget-flat">
			<div class="card-body">
				<div class="float-right">
					<i class="mdi mdi-wallet-membership widget-icon"></i>
				</div>
				<h5 class="text-muted font-weight-normal mt-0" title="Number of Customers"><?php echo get_phrase('total_active_subscription'); ?></h5>
				<h3 class="mt-3 mb-3">
					<?php
						$total_active_subscription	=	0;
						$users	=	$this->db->get('user')->result_array();
						foreach ($users as $row)
						{
							$plan_id	=	$this->crud_model->get_active_plan_of_user($row['user_id']);
							if ($plan_id != false)
								$total_active_subscription++;
						}
						echo $total_active_subscription;
					?>
				</h3>
			</div>
		</div>
	</div>
	<?php */ ?>
	<!-- REVENUE THIS MONTH -->
	<?php /* ?>
	<div class="col-md-3 col-sm-12 ">
		<div class="card widget-flat">
			<div class="card-body">
				<div class="float-right">
					<i class="mdi mdi-square-inc-cash widget-icon"></i>
				</div>
				<h5 class="text-muted font-weight-normal mt-0" title="Number of Customers"><?php echo get_phrase('sales_this_month'); ?></h5>
				<h3 class="mt-3 mb-3">
					<?php
						$total_sale	=	0;
						$month			=	date("F");
						$year 			=	date("Y");
						$subscriptions	=	$this->crud_model->get_subscription_report($month, $year);
						foreach ($subscriptions as $row)
							$total_sale	+=	$row['paid_amount'];
						echo '$'.$total_sale;
						?>
				</h3>
			</div>
		</div>
	</div>
	<?php */ ?>
	<?php
	}
	?>
</div>
