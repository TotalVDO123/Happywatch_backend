<!-- TOP LANDING SECTION -->

<div style="width:100%; height:90px; background-color:#fafafa; border-bottom:solid 1px #dcdde0;">
	<!-- logo -->
	<div style="float: left;">
		<a href="<?php echo base_url();?>index.php?home">
		<img src="<?php echo base_url();?>/assets/global/logo.png" style="margin: 18px 40px; height: 50px;" />
		</a>
	</div>
</div>

<div class="container">
	<div class="row">
		 <form method="post" action="<?php echo base_url();?>index.php?home/support" 
		 enctype="multipart/form-data" onsubmit="return validateContactForm()">
		<div class="col-6">
	        <div class="card">
	            <div class="card-body">
					<center><h2> Customer Support</h2></center>

				
				
					<div class="form-group mb-6">
	                   <label style="padding-top: 20px;">Name</label> <span
                    id="userName-info" class="info"></span><br /> <input style="color:#000;"
                    type="text" class="form-control" name="userName"
                    id="userName" />
	                </div>
					<div class="form-group mb-6">
	                    <label>Email</label> <span id="userEmail-info"
                    class="info"></span><br /> <input  style="color:#000;" type="text"
                    class="form-control" name="userEmail" id="userEmail" />
	                </div>

					<div class="form-group mb-3">
	                  <label>Subject</label> <span id="subject-info"
                    class="info"></span><br /> <input style="color:#000;" type="text"
                    class="form-control"name="subject" id="subject" />
	                </div>

					<div class="form-group mb-3">
	                    <label>Message</label> <span id="userMessage-info"
                    class="info"></span>
	                    <textarea  style="color:#000;" class="form-control" name="content" id="content"></textarea>
	                </div>


			 <center><div>
              <!--  <input type="submit" name="send" class="btn btn-success col-12"
                    value="Send" />-->
				<button type="submit" name="send" style="background-color: #9d22af;"class="btn btn-default col-12" value="Send">Submit</i></button>
                <div id="statusMessage"> 
                        <?php
                        if (! empty($message)) {
                            ?>
                            <p class='<?php echo $type; ?>Message'><?php echo $message; ?></p>
                        <?php
                        }
                        ?>
                    </div>
            </div></center>
	            </div>
	        </div>
	    </div>
        
    

    <script src="https://code.jquery.com/jquery-2.1.1.min.js"
        type="text/javascript"></script>
    <script type="text/javascript">
        function validateContactForm() {
            var valid = true;

            $(".info").html("");
            $(".input-field").css('border', '#e0dfdf 1px solid');
            var userName = $("#userName").val();
            var userEmail = $("#userEmail").val();
            var subject = $("#subject").val();
            var content = $("#content").val();
            
            if (userName == "") {
                $("#userName-info").html("Required.");
                $("#userName").css('border', '#e66262 1px solid');
                valid = false;
            }
            if (userEmail == "") {
                $("#userEmail-info").html("Required.");
                $("#userEmail").css('border', '#e66262 1px solid');
                valid = false;
            }
            if (!userEmail.match(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/))
            {
                $("#userEmail-info").html("Invalid Email Address.");
                $("#userEmail").css('border', '#e66262 1px solid');
                valid = false;
            }

            if (subject == "") {
                $("#subject-info").html("Required.");
                $("#subject").css('border', '#e66262 1px solid');
                valid = false;
            }
            if (content == "") {
                $("#userMessage-info").html("Required.");
                $("#content").css('border', '#e66262 1px solid');
                valid = false;
            }
            return valid;
        }
</script>
		
	</div>
	<?php include 'footer.php';?>
</div>