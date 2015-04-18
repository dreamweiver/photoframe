<?php
	/**
	* @file
	* POST-HIT APP`s Facebook Message posting Page
	*/
	//session_start();
	//Read App Id & Secret Key from config file
	include_once("config.php");
	//session_start();
	header('P3P: CP="NOI ADM DEV COM NAV OUR STP"');
	?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>PhotoFrame</title>
		<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
		<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0-rc2/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0-rc2/css/bootstrap.min.css" />
		<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" />
		<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.5.4/bootstrap-select.min.js"></script>
		<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.5.4/bootstrap-select.min.css" />
		<style >
			.rowCell {
				background: white;
				padding: 4px;
			}
			img+.panel {
				margin-top: 10px;
			}
			button:hover{
				border-color:66ccff;
			}
			p{
				font-weight:300;
				font-size:1.5em;
				font-family: 'Lato',Arial,sans-serif;
			}
		</style>
		<script  type="text/javascript" >
			$(document).ready(function() {
			
				//hide the fields which hold album ID and Album count
				$('.hide').hide();		
				//smooth scroll on page load				
				$('html, body').animate({
					scrollTop: $( '.panel').offset().top
				}, 2000);
				
				//initialise bootstrap-select plugin
				$('.selectpicker').selectpicker();
				
				//click event of the login button
				$(document).on('click','#loading-example-btn',function(){
					alert($(this).attr('data-text'));
					window.location=$(this).attr('data-text');
				});
				
				
				//Click event handler for Extract function
				$(document).on('click','#load-data',function(){
					val=($('.selectpicker').val()).split(":");
					
					alert(val[0]+"<>"+val[1]);
					$('#aId').val(val[0]);
					$('#aCount').val(val[1]);
					$('form').submit();
				});
				
			});	
		</script>
	</head>
	<body style='background-image:url("images/bg.jpg");'>
		<nav class="navbar navbar-default navbar-static-top navbar-fixed-top  " role="navigation">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#"> PhotoFrame 2.0  <span class="glyphicon glyphicon-leaf" style="color:green"></span></a>
				</div>
			</div>
			<!-- /.container-fluid -->
		</nav>
		<div class="container-fluid" style="margin-top:5%">
			<div class="row">
				<div class="col-md-1 rowCell"></div>
				<div class="col-md-10 well rowCell">
					<div class="row">
						<div class="col-md-1 "></div>
						<div class="col-md-10 well rowCell">
							<!--PhotoFrame App Image -->
							<div class="page-header">
								<h1>Photo Frame</h1>
							</div>
							
							<!--Facebook login panel -->
							<form action="photoframe.php" method="post">
								<img class="img-responsive" src="http://lansonlad.files.wordpress.com/2010/05/jupiterpluto.jpg" />
								<div class="panel panel-default">
									<?php
										if ($fbuser) 
										{
											 $val="";
											echo'<div class="panel-heading">
													<h3 class="panel-title"><i class="fa  fa-camera "></i>&nbsp;Choose a Album </h3>
												</div>
												<div class="panel-body">';
											try {
												$albums = $facebook->api('/me/albums');
																						
												/* new drop down from twitter bootstrap */
												echo '<select class="selectpicker show-tick show-menu-arrow" data-live-search="true" data-size="5" data-width="auto">';
																										
												foreach($albums['data'] as $album)
													{
														if(isset($album['count']))
														{
															$alCount=$album['count'];
														}
														$val=$album['id'].':'.$alCount;
														echo '<option value='.$val.'>'.$album['name'].'</option>';
													}
												echo '</select>';
												echo '<button type="button" id="load-data" class="btn btn-primary" style="float:right" data-toggle="button">Extract</button>';
												echo '<input type="text"  class="hide" name="albumId" id="aId" value=""  /> ';
												echo '<input type="text"  class="hide" name="albumCount" id="aCount" value="" /> ';
												echo '</div>';
											} 
											catch (FacebookApiException $e) {
												header( 'Location: error.php' ) ;
											}
										}
										else
										{
											echo'<div class="panel-heading">
													<h3 class="panel-title"><i class="fa  fa-facebook-square "></i> Login to Facebook</h3>
												</div>
												<div class="panel-body">';
												
											//Show login button for guest users
											$loginUrl = $facebook->getLoginUrl(array('redirect_uri'=>$return_url,'scope'=>$fbPermissions));
											echo '<button style="float:center" type="button" id="loading-example-btn" data-loading-text="Loading..." class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="Login to Facebook" data-text="'.$loginUrl.'">LOGIN</button>';
											$fbuser = null;
											echo "</div>";
					
										}	
										
									?>					
								</div>
							</form>
							<!--PhotoFrame App Desc -->
							<p > Photo Frame 1.0 showcases images in new style using jQuery. The main intention is to have a set of rotated thumbnails that, once clicked, animate to form the selected image. You can navigate through the images with previous and next buttons and when the big image gets clicked it will scatter across the screen to form a Grid layout of images.basically Photo Frame reads the images from users Facebook profile via Albums.</p>
						</div>
						<div class="col-md-1 "></div>
							
					</div>
				</div>
				<div class="col-md-1 rowCell"></div>
			</div>
		</div>
		<div id="footer">
			  <div class="container">
				<p class="text-muted"><a  class="page-footer" href="index.php">Back to the home page</a>
				<a  class="page-footer" href="https://www.facebook.com/dreamweiver.manoj">Go to developers profile</a> Copyright (c) 2014</p>
			  </div>
		 </div>
	
	</body>
</html>