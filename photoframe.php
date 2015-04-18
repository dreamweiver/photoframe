<?php

//session_start();
//include config file
include_once("config.php");

//check for valid user
if ($fbuser) 
	{
	
	$albumId=$_POST["albumId"];
	
	//echo "album Id:".$albumId;
	
	$photosCount=$_POST["albumCount"];
	//echo "albumcount:".$photosCount;
	/*if(!isset($albumId) || !isset($photosCount))
	{
		session_destroy();
		header( 'Location: index_page.php' ) ;
	}*/
	$_SESSION['albumId']=$albumId;
	//echo "count:".$photosCount;

	//echo "id inside session:".$_SESSION['albumId'];
?>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="Merging Image Boxes with jQuery,Image Slide show with Facebook integration" />
    <meta name="keywords" content="jquery, merge, effect, images, photos, animation, background-image,facebook,slide show,disperse"/>
	<link rel="icon" type="image/gif" href="images/favicon.ico" >
  <title>Photo Frame 1.0</title>
  <!--	CSS for Merging Image Begin  -->
  <link rel="stylesheet" href="css/img_merger_style.css" type="text/css" media="screen"/>
  <link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
  <!-- CSS for Merging Image End     -->
  <link rel="stylesheet" href="https://dwz7u9t8u8usb.cloudfront.net/m/309704b5e60d/compressed/css/8e3df21248cb.css" />
  <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <!--<link rel="stylesheet" href="css/style.css" type="text/css" />-->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  <script src="js/imagemerger/cufon-yui.js" type="text/javascript"></script>
  <script src="js/imagemerger/ChunkFive_400.font.js" type="text/javascript"></script>
  <link href='http://fonts.googleapis.com/css?family=Trade+Winds|Revalia|Bilbo+Swash+Caps|Finger+Paint' rel='stylesheet' type='text/css'>
  <!--	Nailthumb for Merging Image Begin  -->
  <script type="text/javascript" src="js/nailthumb/jquery.nailthumb.1.1.js"></script>
  <!--	Nailthumb for Merging Image  End   -->
  
  <!--<script src="MergingImageBoxes/js/cufon-yui.js" type="text/javascript"></script>
  <script src="MergingImageBoxes/js/ChunkFive_400.font.js" type="text/javascript"></script>-->
 <!--  used for transforming images, like rotaion -->
 <script src="js/imagemerger/jquery.transform-0.9.1.min.js"></script>
  <script type="text/javascript">
			Cufon.replace('h1.title',{ textShadow: '1px 1px #fff'});
			Cufon.replace('.description1',{ textShadow: '1px 1px #fff'});
			Cufon.replace('.description',{ textShadow: '1px 1px #fff'});
			Cufon.replace('a.tab',{ textShadow: '1px 1px #fff', hover : true});
  </script>
  <style>
	.dTabs
	{
		height:auto;
	}
	.im_loading{
	display:none;
	position:fixed;
	top:50%;
	left:50%;
	margin:-35px 0px 0px -35px;
	background:#fff url(images/imagemerger/loader.gif) no-repeat center center;
	width:70px;
	height:70px;
	z-index:9999;
	-moz-border-radius:10px;
	-webkit-border-radius:10px;
	border-radius:10px;
	-moz-box-shadow:1px 1px 3px #000;
	-webkit-box-shadow:1px 1px 3px #000;
	box-shadow:1px 1px 3px #000;
	opacity:0.7;
	filter:progid:DXImageTransform.Microsoft.Alpha(opacity=70);
	}
	
	body{
	background:#f0f0f0 url(MergingImageBoxes/bg.jpg) repeat top left;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	color: #555;
	}
	.description1
	{
		//position:fixed;
		//right:10px;
		//top:10px;
		font-family: 'Revalia', cursive;
		font-size:12px;
		color:#888;
	}
	.description
	{
		position:fixed;
		right:10px;
		top:10px;
		font-size:12px;
		color:#888;
	}
	
	<!-- css for increasing tab width -->
	.ui-tabs .ui-tabs-nav {
    margin: 0;
    padding: 0.4em 0.4em 0;	
   }
  
   
  </style>
 
  <script>
  var loaded_tabs = new Array();
  $(document).ready(function() {
  alert("inside load func");
    $("#tabs").tabs();
	
	//image loading status
	var $im_loading	= $('#im_loading');
	
	//creating tabs based on images
	/*var tabCount=0;
		
	if(photosCount<=24)
	{
		 tabCount=1;
	}
	else
	{
		 tabCount=Math.floor((photosCount/24));
	}
	*/
	
	
	//var tabRegExp='/^tab[0-9]*$/';
	
		$(".tab").click(
		    
			function(){
				//alert("tab click");
				//ajaxCall();
				var srcId=$(this).parent().attr('id') ;
				var targetCont=$(this).attr('href');
				
				//check the status of tab ,it loaded earlier then dont make ajax call again.
				//else make ajax call,to load the images.
				
				if(loaded_tabs.indexOf(srcId) > -1) {
					// if 'tabid' already loaded with images earlier, then do nothing
					//alert("already loaded");
				}
				else
				{
				    //alert("first load of images");
				    //load the 'tab id' to loaded_tabs array 
					loaded_tabs.push(srcId);
					// make ajax call for loading images on appropriate tab
					$.ajax({
						url: "imagemerger.php",
						data: {tabId:srcId },
						type: "post",
						dataType: "text",
						success:function(response){
										//Hide loading image
										$im_loading.hide();
										$(targetCont).empty();
										//alert(response);
										//$(targetCont).empty();	
										$(targetCont).append(response);
							},
						statusCode: {
							404: function() {
							alert("page not found");
							}
						},
						beforeSend: function() {
									//$('div#ajaxProcessingMessageDiv').show();
									//alert("Before sending");
									//$("#slide-show").remove();
									//$("#acall").empty();
									//ccs3 loading image begin
									//$("#acall").append('<br><br><br><br><br><div class="circle"></div><div class="circle1"></div><div align="center"><font  size="3"><strong>Loading,Pls Wait...<font></strong></div>');
									//ccs3 loading image end
									//alert("loading...");
									//$(targetCont).empty();
									//$(targetCont).append("Loading........");
									//show loading image
									$im_loading.show();
								}
					});
				}

			});
	
	$("#tabH1").click();
	
	}
	
	);
  
  </script>
</head>
<body style="font-size:62.5%;">
  
<div id="tabs">
    <ul style= "margin:0; padding:0.8em 0.8em 0;">
	<?php
		
		$tabCount=0;
		echo "photos count :".$photosCount;
		if($photosCount>0 && $photosCount<=24 )
		{
			$tabCount=1;
			//echo "tabCount:".$tabCount;
		}else
		{	
			//$tabCount=floor(($photosCount/24));
			$tabCount=1;
			$photo_count=$photosCount;
			while(($photo_count-24)>0)
			{
				$tabCount++;
				$photo_count=$photo_count-24;
			}
			
			//echo "photo count in else block".$photosCount."\n";
			//echo "mul tabs".$tabCount."actual count :".($photosCount/24);
		}
		if(!$tabCount==0)
		{
		
			for($index=0;$index<$tabCount;$index++)
			{
				if($index==0)
				{
					echo "<li  id=\"tab".($index+1)."\"><a class=\"tab\" href=\"#fragment-".($index+1)."\" id=\"tabH1\" ><span style=\"cursor: pointer\">Page".($index+1)."</span></a></li>";
				}
				else
					echo "<li  id=\"tab".($index+1)."\"><a class=\"tab\" href=\"#fragment-".($index+1)."\" ><span style=\"cursor: pointer\">Page".($index+1)."</span></a></li>";
				
			}
		}else
		{
			echo "<li  id=\"tab1\"><a class=\"tab\" href=\"#fragment-1\" ><span style=\"cursor: pointer\">Page1</span></a></li>
       			</ul>
				<div class=\"dTabs\" id=\"fragment-1\"><div class=\"description1\">Sorry no Images available from selected album.Go to<a class=\"footer\" href=\"index.php \"> home page</a> and select a different album</div> </div>";
		}
	?>
     <!--   <li id="tab1"><a class="tab" href="#fragment-1" id="tabH1"><span>Page1</span></a></li>
        <li id="tab2"><a class="tab" href="#fragment-2"><span>Page2</span></a></li>
        <li id="tab3"><a class="tab" href="#fragment-3"><span>Page3</span></a></li>
		<li id="tab4"><a class="tab" href="#fragment-4"><span>Page4</span></a></li>-->
    
	
	<?php
		echo "</ul>";
		for($index=0;$index<$tabCount;$index++)
		{
			echo "<div class=\"dTabs\" id=\"fragment-".($index+1)."\" ><h1 class=\"title\" >Photo Frame <span>1.0</span></h1></div>";
		}
	?>
   <!-- <div class="dTabs" id="fragment-1">
        
    </div>
    <div class="dTabs" id="fragment-2">
        
    </div>
    <div class="dTabs" id="fragment-3">
        
    </div>
	<div class="dTabs" id="fragment-4">
        
	</div> -->
</div>
<div class="description">Photo Frame 1.0</div>
<div id="im_loading" class="im_loading"></div>

<!-- footer -->
<div>
	<span class="reference"  >
		<a  class="page-footer" href="index.php" >back to the home page</a>
		<a  class="page-footer" href="https://www.facebook.com/dreamweiver.manoj" >Go to developers profile</a>
	</span>
</div>
</body>
</html>
<?php
	}else
	{
		//session_destroy();
		header( 'Location: index.php' ) ;
	}
?>