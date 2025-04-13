<!-- ========== Left Sidebar Start ========== -->
           <div class="left-side-menu" style="background: linear-gradient(135deg,#0F2027 0,#2C5364 60%) !important;">

               <div class="slimscroll-menu">

                   <!-- LOGO -->
                   <a href="<?php echo base_url('index.php?admin'); ?>" class="logo text-center">
                       <span class="logo-lg">
                           <img src="<?php echo base_url();?>assets/global/logo.png" alt="" height="40">
                       </span>

                       <!-- We should use a small logo for this image tag -->
                       <span class="logo-sm">
                           <img src="<?php echo base_url();?>assets/global/logo.png" alt="" height="40">
                       </span>
                   </a>

                   <!--- Sidemenu -->
                   <ul class="metismenu side-nav">
                       <li class="side-nav-item <?php if ($page_name == 'dashboard')echo 'active';?>">
                           <a href="<?php echo base_url();?>index.php?admin" class="side-nav-link <?php if ($page_name == 'dashboard')echo 'active';?>">
                               <i class="dripicons-meter"></i>
                               <span> <?php echo get_phrase('dashboard'); ?> </span>
                           </a>
                       </li>
						
						<?php
					  if($this->session->userdata('login_type') == 2 OR $this->session->userdata('login_type') == 1)
					  {
					  ?>
						
						<li class="side-nav-item <?php if ($page_name == 'ads_list' || $page_name == 'ads_edit' || $page_name == 'ads_create')echo 'active';?>">
                           <a href="<?php echo base_url();?>index.php?admin/ads_list" class="side-nav-link <?php if ($page_name == 'ads_list' || $page_name == 'ads_edit' || $page_name == 'ads_create')echo 'active';?>">
                               <i class="dripicons-align-justify"></i>
                               <span> <?php echo get_phrase('advertisement'); ?> </span>
                           </a>
                       </li>
					   
					  <?php
					  }
					  
					   
					  
					 if( $this->session->userdata('login_type') == 1 )
					  {
					  ?>
					<li class="side-nav-item <?php if ($page_name == 'broadcast_list')echo 'active';?>">
                           <a href="<?php echo base_url();?>index.php?admin/broadcast_list" class="side-nav-link <?php if ($page_name == 'broadcast_list' )echo 'active';?>">
                               <i class="mdi mdi-wallet-membership"></i>
                               <span> <?php echo 'Post'; ?> </span>
                           </a>
                       </li>
					   
					   <?php
					   }
					   ?>
					   
					       <?php
                        if( $this->session->userdata('login_type') == 3 or $this->session->userdata('login_type') == 1)
                        {
                       ?>
                       
                       <li class="side-nav-item <?php if ($page_name == 'uwatch_list' || $page_name == 'uwatch_edit' || $page_name == 'uwatch_create')echo 'active';?>">
                           <a href="<?php echo base_url();?>index.php?admin/uwatch_list" class="side-nav-link  <?php if ($page_name == 'uwatch_list' || $page_name == 'uwatch_edit' || $page_name == 'uwatch_create')echo 'active';?>">
                               <i class="mdi mdi-movie"></i>
                               <span> <?php echo 'U Watch'; ?> </span>
                           </a>
                       </li>
                       
                       <?php
                        }
                   
					   
					   
					   
					   
					   
					   
					   
					   
					   
					  if($this->session->userdata('login_type') == 2 OR $this->session->userdata('login_type') == 1 OR $this->session->userdata('login_type') == 3 )
					  {
					  ?>
					   <li class="side-nav-item <?php if ($page_name == 'live_list' || $page_name == 'live_edit' || $page_name == 'live_create')echo 'active';?>">
                           <a href="<?php echo base_url();?>index.php?admin/live_list" class="side-nav-link  <?php if ($page_name == 'live_list' || $page_name == 'live_edit' || $page_name == 'live_create')echo 'active';?>">
                               <i class="mdi mdi-movie"></i>
                               <span> Live TV </span>
                           </a>
                       </li>
					   
                       <li class="side-nav-item <?php if ($page_name == 'movie_list' || $page_name == 'movie_edit' || $page_name == 'movie_create')echo 'active';?>">
                           <a href="<?php echo base_url();?>index.php?admin/movie_list" class="side-nav-link  <?php if ($page_name == 'movie_list' || $page_name == 'movie_edit' || $page_name == 'movie_create')echo 'active';?>">
                               <i class="mdi mdi-movie"></i>
                               <span> <?php echo get_phrase('movies'); ?> </span>
                           </a>
                       </li>
						
					   
                       <li class="side-nav-item <?php if ($page_name == 'series_list' || $page_name == 'series_create' || $page_name == 'series_edit' || $page_name == 'season_edit')echo 'active';?>">
                           <a href="<?php echo base_url();?>index.php?admin/series_list" class="side-nav-link <?php if ($page_name == 'series_list' || $page_name == 'series_create' || $page_name == 'series_edit' || $page_name == 'season_edit')echo 'active';?>">
                               <i class="mdi mdi-movie-roll"></i>
                               <span> <?php echo get_phrase('TV_series'); ?> </span>
                           </a>
                       </li>
                       
                       
                       <?php /* ?>
                       <li class="side-nav-item <?php if ($page_name == 'series_list' || $page_name == 'music_create' || $page_name == 'music_edit' || $page_name == 'music_edit')echo 'active';?>">
                           <a href="<?php echo base_url();?>index.php?admin/music_list" class="side-nav-link <?php if ($page_name == 'music_list' || $page_name == 'music_create' || $page_name == 'series_edit' || $page_name == 'music_edit')echo 'active';?>">
                               <i class="mdi mdi-movie-roll"></i>
                               <span> <?php echo 'Music'; ?> </span>
                           </a>
                       </li>
                       <?php */ ?>
                       
                       
                   <?php    
					  }
                       
                       
					  if( $this->session->userdata('login_type') == 1 OR $this->session->userdata('login_type') == 3)
					  {
					  ?>
                     
                       
                       
                    <?php
                    /*
                    ?>
					   <li class="side-nav-item <?php if ($page_name == 'channel_list' || $page_name == 'channel_edit' || $page_name == 'channel_create')echo 'active';?>">
                           <a href="<?php echo base_url();?>index.php?admin/channel_list" class="side-nav-link <?php if ($page_name == 'channel_list' || $page_name == 'channel_edit' || $page_name == 'channel_create')echo 'active';?>">
                               <i class="dripicons-align-justify"></i>
                               <span> <?php echo get_phrase('channels'); ?> </span>
                           </a>
                       </li>
					   <?php
                    */
                    ?>
					 
					   
					   <?php 
						if($this->session->userdata('admin_restricted')!=1)
						{
						?>	
					   
                       <li class="side-nav-item <?php if ($page_name == 'genre_list' || $page_name == 'genre_edit' || $page_name == 'genre_create')echo 'active';?>">
                           <a href="<?php echo base_url();?>index.php?admin/genre_list" class="side-nav-link <?php if ($page_name == 'genre_list' || $page_name == 'genre_edit' || $page_name == 'genre_create')echo 'active';?>">
                               <i class="dripicons-align-justify"></i>
                               <span> <?php echo get_phrase('genre'); ?> </span>
                           </a>
                       </li>
					  

                       <li class="side-nav-item <?php if ($page_name == 'actor_list' || $page_name == 'actor_edit' || $page_name == 'actor_create')echo 'active';?>">
                           <a href="<?php echo base_url();?>index.php?admin/actor_list" class="side-nav-link <?php if ($page_name == 'actor_list' || $page_name == 'actor_edit' || $page_name == 'actor_create')echo 'active';?>">
                               <i class="mdi mdi-account-settings"></i>
                               <span> <?php echo get_phrase('actors'); ?> </span>
                           </a>
                       </li>
					<?php
						}
					   ?>
					   
					   
                        <?php
					  }
                       
                    if( $this->session->userdata('login_type') == 1 )
					  {
					  ?>
                    
					   
					     <?php 
						if($this->session->userdata('admin_restricted')!=1)
						{
						?>	   
                        <li class="side-nav-item <?php if ($page_name == 'application_notification_list')echo 'active';?>">
                           <a href="<?php echo base_url();?>index.php?admin/application_notification_list" class="side-nav-link <?php if ($page_name == 'application_notification_list' )echo 'active';?>">
                               <i class="mdi mdi-wallet-membership"></i>
                               <span> <?php echo 'App Notification'; ?> </span>
                           </a>
                       </li>
                       
					   
					    <li class="side-nav-item <?php if ($page_name == 'notification_list')echo 'active';?>">
                           <a href="<?php echo base_url();?>index.php?admin/notification_list" class="side-nav-link <?php if ($page_name == 'notification_list' )echo 'active';?>">
                               <i class="mdi mdi-wallet-membership"></i>
                               <span> <?php echo 'Notification'; ?> </span>
                           </a>
                       </li>
                        
					   <?php
						}
					   ?>

                       <li class="side-nav-item <?php if ($page_name == 'user_list' || $page_name == 'user_edit' || $page_name == 'user_create')echo 'active';?>">
                           <a href="<?php echo base_url();?>index.php?admin/user_list" class="side-nav-link <?php if ($page_name == 'user_list' || $page_name == 'user_edit' || $page_name == 'user_create')echo 'active';?>">
                               <i class="mdi mdi-account-multiple"></i>
                               <span> <?php echo 'Manage User'; ?> </span>
                           </a>
                       </li>
                        
                        
                        
                       <?php 
						if($this->session->userdata('admin_restricted')!=1)
						{
						?>	     
					
                        
                       <li class="side-nav-item <?php if ($page_name == 'plan_list' || $page_name == 'plan_edit')echo 'active';?>">
                           <a href="<?php echo base_url();?>index.php?admin/plan_list" class="side-nav-link <?php if ($page_name == 'plan_list' || $page_name == 'plan_edit')echo 'active';?>">
                               <i class="mdi mdi-wallet-membership"></i>
                               <span> <?php echo get_phrase('membership_package'); ?> </span>
                           </a>
                       </li>
                       
                    

                       <li class="side-nav-item <?php if ($page_name == 'report')echo 'active';?>">
                           <a href="<?php echo base_url();?>index.php?admin/report" class="side-nav-link <?php if ($page_name == 'report')echo 'active';?>">
                               <i class="dripicons-archive"></i>
                               <span> <?php echo get_phrase('report'); ?> </span>
                           </a>
                       </li>

                       <li
                       <?php
                       $is_active = '';
                       if ( $page_name == 'faq_list' 		||
                    		$page_name == 'faq_edit' 		||
                    		$page_name == 'faq_create' 		||
                    		$page_name == 'manage_language' ||
                    		$page_name == 'settings' ) $is_active = 'active'; ?>
                        class="side-nav-item <?php echo $is_active; ?>">
                           <a href="javascript: void(0);" class="side-nav-link <?php echo $is_active; ?>">
                               <i class="dripicons-view-apps"></i>
                               <span> <?php echo get_phrase('configuration'); ?> </span>
                               <span class="menu-arrow"></span>
                           </a>
                           <ul class="side-nav-second-level" aria-expanded="false">
                               <li class = "<?php if($page_name == 'settings') echo 'active'; ?>">
                                   <a href="<?php echo base_url();?>index.php?admin/settings" class = ""><?php echo get_phrase('website_settings'); ?></a>
                               </li>

                               <li class = "<?php if($page_name == 'manage_language') echo 'active'; ?>">
                                   <a href="<?php echo base_url();?>index.php?admin/manage_language" class = ""><?php echo get_phrase('language_settings'); ?></a>
                               </li>

                               <li class = "<?php if($page_name == 'faq_list') echo 'active'; ?>">
                                   <a href="<?php echo base_url();?>index.php?admin/faq_list" class = ""><?php echo 'Customer FAQ'; ?></a>
                               </li>
                           </ul>
                       </li>

					   <?php
						}
					   ?>
					   
					   
                       <li class="side-nav-item <?php if($page_name == 'account')echo 'active';?>">
                           <a href="<?php echo base_url();?>index.php?admin/account" class="side-nav-link <?php if($page_name == 'account')echo 'active';?>">
                               <i class="dripicons-meter"></i>
                               <span> <?php echo get_phrase('account'); ?> </span>
                           </a>
                       </li>
                   <?php
					  }
                   ?>
                   
                   </ul>
                   <div class="clearfix"></div>
               </div>
               <!-- Sidebar -left -->
           </div>
           <!-- Left Sidebar End -->
