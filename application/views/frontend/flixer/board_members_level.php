  
 <style>
/* RESET STYLES & HELPER CLASSES
–––––––––––––––––––––––––––––––––––––––––––––––––– */
:root {
  --level-1: #9617DA;
  --level-2: #9617DA;
  --level-3: #7b9fe0;
  --level-4: #f27c8d;
  --black: black;
}

* {
  padding: 0;
  margin: 0;
  box-sizing: border-box;
}

ol {
  list-style: none;
 /* left: -8%;*/
}

body {
  margin: 50px 0 100px;
  text-align: center;
  font-family: "Inter", sans-serif;
}

.container {
  max-width: 1200px !important;
  padding: 0 10px;
  margin: 0 auto;
}

.rectangle {
  position: relative;
  padding: 20px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
}


/* LEVEL-1 STYLES
–––––––––––––––––––––––––––––––––––––––––––––––––– */
.level-1 {
  width: 57%;
  margin: -1% auto 40px;
  background: var(--level-1);
}
/* line */
.level-1::before {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  width: 2px;
  height: 20px;
  background: var(--black);
}


/* LEVEL-2 STYLES
–––––––––––––––––––––––––––––––––––––––––––––––––– */
.level-2-wrapper {
  position: relative;
  display: grid;
  grid-template-columns: repeat(4, 21fr);
}

.level-2-wrapper::before {
  content: "";
  position: absolute;
  top: -20px;
  left: 12%;
  width: 76%;
  height: 2px;
  background: var(--black);
}

.level-2-wrapper::after {
  display: none;
  content: "";
  position: absolute;
  left: -20px;
  bottom: -20px;
  width: calc(100% + 20px);
  height: 2px;
  background: var(--black);
}

.level-2-wrapper li {
  position: relative;
}

.level-2-wrapper > li::before {
  content: "";
  position: absolute;
  bottom: 100%;
  left: 50%;
  transform: translateX(-50%);
  width: 2px;
  height: 20px;
  background: var(--black);
}

.level-2 {
  width: 70%;
  margin: 0 auto 40px;
  height: 300px;
  background: var(--level-2);
}

.level111-2::before {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  width: 2px;
  height: 20px;
  background: var(--black);
}

.level_111-2::after {
  display: none;
  content: "";
  position: absolute;
  top: 50%;
  left: 0%;
  transform: translate(-100%, -50%);
  width: 20px;
  height: 2px;
  background: var(--black);
}


/* LEVEL-3 STYLES
–––––––––––––––––––––––––––––––––––––––––––––––––– */
.level-3-wrapper {
  position: relative;
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  grid-column-gap: 20px;
  width: 90%;
  margin: 0 auto;
}

.level-3-wrapper::before {
  content: "";
  position: absolute;
  top: -20px;
  left: calc(25% - 5px);
  width: calc(50% + 10px);
  height: 2px;
  background: var(--black);
}

.level-3-wrapper > li::before {
  content: "";
  position: absolute;
  top: 0;
  left: 50%;
  transform: translate(-50%, -100%);
  width: 2px;
  height: 20px;
  background: var(--black);
}

.level-3 {
  margin-bottom: 20px;
  background: var(--level-3);
}


/* LEVEL-4 STYLES
–––––––––––––––––––––––––––––––––––––––––––––––––– */
.level-4-wrapper {
  position: relative;
  width: 80%;
  margin-left: auto;
}

.level-4-wrapper::before {
  content: "";
  position: absolute;
  top: -20px;
  left: -20px;
  width: 2px;
  height: calc(100% + 20px);
  background: var(--black);
}

.level-4-wrapper li + li {
  margin-top: 20px;
}

.level-4 {
  font-weight: normal;
  background: var(--level-4);
}

.level-4::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 0%;
  transform: translate(-100%, -50%);
  width: 20px;
  height: 2px;
  background: var(--black);
}


/* MQ STYLES
–––––––––––––––––––––––––––––––––––––––––––––––––– */
@media screen and (max-width: 700px) {
  .rectangle {
    padding: 20px 10px;
  }

  .level-1,
  .level-2 {
    width: 100%;
  }

  .level-1 {
    margin-bottom: 20px;
  }

  .level-1::before,
  .level-2-wrapper > li::before {
    display: none;
  }
  
  .level-2-wrapper,
  .level-2-wrapper::after,
  .level-2::after {
    display: block;
  }

  .level-2-wrapper {
    width: 90%;
    margin-left: 10%;
  }

  .level-2-wrapper::before {
    left: -20px;
    width: 2px;
    height: calc(100% + 40px);
  }

  .level-2-wrapper > li:not(:first-child) {
    margin-top: 50px;
  }
}


/* FOOTER
–––––––––––––––––––––––––––––––––––––––––––––––––– */
.page-footer {
  position: fixed;
  right: 0;
  bottom: 20px;
  display: flex;
  align-items: center;
  padding: 5px;
}

.page-footer a {
  margin-left: 4px;
}

.text_font{
	font-size:20px;
}

.text_font2{
	font-size:18px;
	
}

.ol-width1111{
	width: 1250px !important;
	left:-10%"; 
}

	 .header-style{
		 width:100%;
		 height:90px; 
		 background-color:#000;
		 border-bottom:solid 1px #000;
	 }
	 .text-left{
	 float: left;
	 }
	 
	 .text-title{
		 font-size: 30px;
		 letter-spacing: .2px; 
		 color: #fff;
		 font-weight: 400;
	 }
	 
	 .logo-size{
		 margin: 18px 40px;
		 height: 50px;
	 }
	 
	
	  .div-small-height{
	 	height: 30px;
	 }
	 .div-height{
	 	height: 60px;
	 }
</style>



<!-- TOP LANDING SECTION -->
<link href="https://fonts.googleapis.com/css?family=Inter&display=swap" rel="stylesheet">

<?php
 $bg_color = "#f3f3f3";
?>

<div class="header-style" >
	<!-- logo -->
	<div class="text-left" >
		<a href="<?php echo base_url();?>index.php?home">
		<img src="<?php echo base_url();?>/assets/global/logo.png" class="logo-size" />
		</a>
	</div>
</div>


<div class="container"  >
<div>&nbsp;</div>
<div class="row">

<div class="col-sm-12 col-md-12 col-lg-12">
 <div class="text-title" > BOARD OF DIRECTORS </div> 
 </div>

</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
  <h1 class="level-1 rectangle text_font">
  <div class="row">
  <div class="col-sm-3 col-md-3 col-lg-3">
  <img src="<?php echo base_url() ?>assets/board_member/img/01 - Eric Or - CEO & Founder.jpg"  width="120" height="150" >
  </div>
  <div class="col-sm-9 col-md-9 col-lg-9">
  
	<div class="col-sm-12 col-md-12 col-lg-12 div-small-height">
	<div class="text-left" ><strong>Mr. Eric Or </strong> </div>
	</div>
	<div class="col-sm-12 col-md-12 col-lg-12 div-small-height" >
	<div class="text-left" > CEO and Founder </div>
	</div>	
	
	<div class="col-sm-12 col-md-12 col-lg-12 div-small-height" >
	<div class="text-left" >USA and Worldwide </div>
	</div>
  
  </div>
</div>
 </h1>
 

<h1 class="level-1 rectangle text_font">
  <div class="row">
  <div class="col-sm-3 col-md-3 col-lg-3">
  <img src="<?php echo base_url() ?>assets/board_member/img/02 - Mr. Tan Sovann - Director of Sales & Marketing.jpg"  width="120" height="150" >
  </div>
  
  <div class="col-sm-9 col-md-9 col-lg-9">
	<div class="col-sm-12 col-md-12 col-lg-12 div-small-height">
	<div class="text-left" ><strong>Mr. Tan Sovann (Sem) </strong> </div>
	</div>
	<div class="col-sm-12 col-md-12 col-lg-12 div-small-height" >
	<div class="text-left" > Director of Sales & Marketing  </div>
	</div>	
	
	<div class="col-sm-12 col-md-12 col-lg-12 div-small-height" >
	<div class="text-left" >USA and Worldwide </div>
  </div> 	
  
  </div>
</div>
 </h1>

<h1 class="level-1 rectangle text_font">
  <div class="row">
  <div class="col-sm-3 col-md-3 col-lg-3">
  <img src="<?php echo base_url() ?>assets/board_member/img/03 - Mam Rasmey - Board Member & Director of Programming.jpg"  width="120" height="150" >
  </div>
  <div class="col-sm-9 col-md-9 col-lg-9">
  
	<div class="col-sm-12 col-md-12 col-lg-12 div-small-height">
	<div class="text-left" ><strong>Mr. Mam Rasmey </strong> </div>
	</div>
	<div class="col-sm-12 col-md-12 col-lg-12 div-small-height" >
	<div class="text-left" > Board Member </div>
	</div>	
	
	<div class="col-sm-12 col-md-12 col-lg-12 div-small-height" >
	<div class="text-left" >Shareholder & Director of Programming</div>
	</div>
  
  </div>
</div>
 </h1>



 
  <ol class="level-2-wrapper ol-width" >
      
	
	
	<li>
      <h2 class="level-2 rectangle text_font2">
	  
	 <div class="row">
 	
	<div class="col-sm-12 col-md-12 col-lg-12 div-small-height">
	<div class="text-left" ><strong>Mr. Som Chhaya </strong> </div>
	</div>
	<!--<div class=" ">&nbsp;</div>-->
	<div class="col-sm-12 col-md-12 col-lg-12 div-height" >
	<div class="text-left" >News Director</div>
	</div>	
	
		 
	<div class="col-sm-12 col-md-12 col-lg-12">
  <img src="<?php echo base_url() ?>assets/board_member/img/04 - Som Chhaya - News Director.jpg"  width="120" height="150" >
  </div>

	</div>
	  
	  
	  
	  
	  
	  </h2>
    </li>
  <li>
      <h2 class="level-2 rectangle text_font2">
	  
	   <div class="row">
 	<div class="col-sm-12 col-md-12 col-lg-12 div-small-height">
	<div class="text-left" ><strong>Mr. Huot Sovann </strong> </div>
	</div>
	<!--<div class=" ">&nbsp;</div>-->
	<div class="col-sm-12 col-md-12 col-lg-12 div-height" >
	<div class="text-left" >Director of Sales & Marketing </div>
	</div>	
	
	<div class="col-sm-12 col-md-12 col-lg-12">
  <img src="<?php echo base_url() ?>assets/board_member/img/05 - Huot Sovann - Director of Sales and Marketing.jpg"  width="120" height="150" >
  </div>

	</div>
	  
	  
	  
	  </h2>
  </li>
  
  <li>
      <h2 class="level-2 rectangle text_font2">
	  
	   <div class="row">
 	<div class="col-sm-12 col-md-12 col-lg-12 div-small-height">
	<div class="text-left" ><strong>Ms. Sa Seyha </strong> </div>
	</div>
	<!--<div class=" ">&nbsp;</div>-->
	<div class="col-sm-12 col-md-12 col-lg-12 div-height" >
	<div class="text-left" >Director of Finance</div>
	</div>	
	
	<div class="col-sm-12 col-md-12 col-lg-12">
  <img src="<?php echo base_url() ?>assets/board_member/img/06 - Sa Seyha - Director of Finance.jpg"  width="120" height="150" >
  </div>

	</div>
	  
	  
	  </h2>
  </li>
  
  <li>
      <h2 class="level-2 rectangle text_font2">
	  
	     <div class="row">
 	<div class="col-sm-12 col-md-12 col-lg-12 div-small-height">
	<div class="text-left" ><strong>Mr. Em Vireak </strong> </div>
	</div>
	<!--<div class=" ">&nbsp;</div>-->
	<div class="col-sm-12 col-md-12 col-lg-12 div-height" >
	<div class="text-left" >Technical Director</div>
	</div>	
	
	<div class="col-sm-12 col-md-12 col-lg-12">
  <img src="<?php echo base_url() ?>assets/board_member/img/07 - Em Vireak - Technical Director.jpg"  width="120" height="150" >
  </div>

	</div>
	
	  
	  </h2>
  </li>
 
  
  </ol>
  
</div>
	
	

	
	
 <?php //include 'footer.php';?>  










    