<?php
	/**
	* @file
	* POST-HIT APP`s Facebook Message posting Page
	*/
	//Read App Id & Secret Key
	include_once("config.php");
	$albumId=$_SESSION['albumId'];
	//echo "session id in mergerpage:".$albumId;
	//echo "inside imagemerger";
	if($fbuser)
	{
?>
<html>
    <head>
        
      <!--  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta name="description" content="Merging Image Boxes with jQuery,Image Slide show with Facebook integration" />
		<meta name="keywords" content="jquery, merge, effect, images, photos, animation, background-image,facebook,slide show,disperse"/>
		
		<link rel="icon" type="image/gif" href="images/favicon.ico" >
		<link rel="stylesheet" href="css/style.css" type="text/css" />
		
		<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script type="text/javascript" src="nailthumb/jquery.nailthumb.1.1.js"></script>-->
		
		<style>


		#side_wrapper2{width:130px;position:absolute;right:350px;top:25px;font-size:71.4%;color:#545454;}
		#header_wrapper #header #header_content_wrapper #header_content #header_logo{background-image:url("images/social.jpeg") no-repeat;}
		#header_wrapper #header #header_content_wrapper #header_content #header_button_wrapper #header_button #header_button_menu_wrapper #header_button_menu:after{content:"-";font-size:1px;display:block;position:fixed;opacity:0;-ms-filter:progid:DXImageTransform.Microsoft.Alpha(Opacity=0);filter:alpha(opacity=0);}
		#header_wrapper #header{background:url("images/header_bg.v102.png") repeat-x;height:57px;min-width:560px;width:100%;}
		#header_logo{float:left;width:200px;height:65px;margin:8 0 -100px -80px;background:url("") no-repeat;background-size:80px 75px;}
		#image{height:600;display:table-cell; vertical-align:middle; text-align:center;}
		.text{
			color: #333333;
			font-family:  ‘Lucida Console’;
			font-size: 35;
		</style>
		<style type="text/css" media="screen">
		.square-thumb {
		width: 125px;
		height: 125px;
		background-image: url("nailthumb/images/image.png");
		background-position: center center;
		background-repeat: no-repeat;
		}
		</style>
       <!-- <link rel="stylesheet" href="MergingImageBoxes/css/style.css" type="text/css" media="screen"/>-->
		<script type="text/javascript">
		$(document).ready(
				function(){	
					//for thumbnail version of every image
					$('.square-thumb').nailthumb();
		
		});
		</script>
		<!--<script src="MergingImageBoxes/js/cufon-yui.js" type="text/javascript"></script>
		<script src="MergingImageBoxes/js/ChunkFive_400.font.js" type="text/javascript"></script>-->
		
		<script type="text/javascript">
			//Cufon.replace('h1',{ textShadow: '1px 1px #fff'});
			Cufon.replace('.description',{ textShadow: '1px 1px #fff'});
			//Cufon.replace('a',{ textShadow: '1px 1px #fff', hover : true});
		</script>
        <style type="text/css">
			.description{
				position:fixed;
				right:10px;
				top:10px;
				font-size:12px;
				color:#888;
			}
			<!--span.reference a{
				color:#888;
				text-transform:uppercase;
				text-decoration:none;
				padding-right:20px;
			}
			span.reference a:hover{
				color:#444;
			}-->
			
		</style>
    </head>
 
    <body>
		<div class="repo-summary" style="padding-left:5px;" ><h1><a class="neon" href="" >Photo-Frame 1.0</a></h1></div>
		<div class="description"><h1></h1>Click on the thumbs or the large image</div>
		<div id="im_wrapper" class="im_wrapper">
		<?php
			$srcTab = $_POST['tabId'];
			$tabNo=substr($srcTab,3);
//			echo "tab no:".$tabNo;
			$hLimit=$tabNo*24;
			$lLimit=$hLimit-24;
			//$session_bit=1;
			try {
				//user name
				$link = json_decode(file_get_contents('http://graph.facebook.com/'.$fbuser));
				$userMessage=$link->name."  has viewed his Album in a new style using Photo-Frame 1.0";
				$appLink="http://google.com/";
				$appName="PhotoFrame1.0";
				$postResult=0;
				// Post Comment with image on user`s wall 
				
				//condition for posting the app message on user wall only once 
				if(!isset($_SESSION[$link->name]))
				{
					$msg_body = array
					(
						'access_token'=>$facebook->getAccessToken(),
						'message' => $userMessage,
						'name' => $appName,
						'caption' => 'Jquery Image Merger app ',
						'link' => $appLink,
						'description' => ' To have a ultimate experience of viewing your album,access Photo-Frame (A Jquery Image Merger app with Facebook Integration)',
						'picture' => 'http://cdn1.iconfinder.com/data/icons/DarkGlass_Reworked/128x128/apps/quick_restart.png',
						'actions' => array(
										array(
											'name' => 'Photo-Frame 1.0',
											'link' => $appLink
										)
									)
					);
										
					//commented for unneccessary posting on app message
					$postResult = $facebook->api("/me/feed", 'post', $msg_body );
					if($postResult )
					{
						$session_bit="true";
						//setting "session_bit" for valid posting of app message.
						$_SESSION[$link->name]=$session_bit;
					}
				}
				if(!$postResult)
				{
					//echo "Photo-Frame Error :Failed to post the Comment ";
					// log messages to log4php logger
				}
				
				//$facebook->destroySession();
				// get albums
				$albums = $facebook->api('/me/albums');
				$x=0;
				$y=0;
				$i=0;
				$j=0;
				$imgCount=0;
				$imageName='';
				$pixel='';
				
					//foreach($albums['data'] as $album)
					//{
						// get all photos for album
						//$photos = $facebook->api("/{$album['id']}/photos");
						$photos = $facebook->api("/{$albumId}/photos?limit=200");
						//echo " album name :".$album['id'].":".$album['name']."<br />";
                       // echo "\n imagecount before for loop :".$imgCount;
						foreach($photos['data'] as $photo)
						{
						//	echo "inside valid for loop :".$i;
							if($i<$lLimit || $i>($hLimit -1))
							  {
								$i++;
								continue;
							  }
							//    echo "\n i value=> ".$i." valid range: ".$lLimit.":".$hLimit;
								$imgCount++; /*Counter to keep the no of images on Page to 24 */
								if($j==0)  /*  Setting initial background position as x=0px & y=0px   */
								{
									$x=0;
									$y=0;
									$j++;
								}
								else if($i%6==0 )
								{
								    //echo "from second row\n";
									$x=0;
									$y+=-125;
								}else
								{
									$x+=-125;
									//$y=0;
								}
								$imageName=$photo['source'];
								$pixel=$x."px  ".$y."px ;'";
								//echo "pixel info:".$pixel;
								echo " <div class='square-thumb' style='background-position:".$pixel." >  <img src=".$imageName." left=\"0px\" alt=\"\"  /></div>";
								$i++;
						}
						
					//}
					//echo "<br/>imgCount:".$imgCount;
					
				//	echo "\nimgcount after for loop:".$imgCount;
					
					//populating  empty grids when images are less than 24 
					for($index=$imgCount;$index<24;$index++)
					{
						//$fillerImage='MergingImageBoxes/images/thumbs/15.jpg';
						$fillerImage='http://www.garralab.com/img/image.png';
					//	echo"\ninside filler for:";
						
						if($j==0)  /*  Setting initial background position as x=0px & y=0px   */
						{
							$x=0;
							$y=0;
							$j++;
						}
						else if($i%6==0)
						{
							$x=0;
							$y+=-125;
						}else
						{
							$x+=-125;
							//$y=0;
						}
						//$imageName=$photo['source'];
						$pixel=$x."px  ".$y."px ;'";
						//echo "pixel info inside for:".$pixel;
						echo " <div class='square-thumb' style='background-position:".$pixel." >  <img src=".$fillerImage."  alt= \"\" /></div>";
						$i++;	
					}
					
				/*if($tabNo==2)
					{
					   //echo "<img src=\"MergingImageBoxes/images/thumbs/15.jpg\" width=\"300\" height=\"300\" ></img>";
					   echo " <div class='square-thumb' style='background-position:".$pixel." >  <img src=".$fillerImage."  alt= \"\" /></div>";
					}	
				*/	
			} 
			catch (FacebookApiException $e) {
			    //$error_msg= $e->getMessage();
				header( 'Location: error.php' ) ;
				
			}
			//echo "</div >";
	?>
		
		</div>
		<div id="im_loading" class="im_loading"></div>
		<div id="im_next" class="im_next"></div>
		<div id="im_prev" class="im_prev"></div>
        <!--<div>
            <span class="reference">
                <a  class ="page-footer" href="index_page.php">back to the home page</a>
				<a class ="page-footer" href="https://www.facebook.com/dreamweiver.manoj">Go to developers profile</a>
            </span>
		</div>
		-->
        <!-- The JavaScript -->
       <!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
		<script src="MergingImageBoxes/js/jquery.transform-0.9.1.min.js"></script>-->
		<script type="text/javascript">
			//Paul Irish smartresize : http://paulirish.com/2009/throttled-smartresize-jquery-event-handler/
			(function($,sr){
				// debouncing function from John Hann
				// http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
				var debounce = function (func, threshold, execAsap) {
					var timeout;
					return function debounced () {
						var obj = this, args = arguments;
						function delayed () {
							if (!execAsap)
								func.apply(obj, args);
							timeout = null;
						};
						if (timeout)
							clearTimeout(timeout);
						else if (execAsap)
							func.apply(obj, args);
						timeout = setTimeout(delayed, threshold || 100);
					};
				}
				//smartresize
				jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };
			})(jQuery,'smartresize');
		</script>
        <script type="text/javascript">	
            $(function() {
			
				//added by manoj begin
				var tabNo='<?php echo $tabNo;?>';
				//added by manoj end
					//alert("tab no in js:"+tabNo);
				//check if the user made the
				//mistake to open it with IE
				var ie 			= false;
				if ($.browser.msie)
					ie = true;
				//flag to control the click event
				var flg_click	= true;
				//tab id
				var $tab_id = '#fragment-'+tabNo;
				//the fragment
				var $fragment = $($tab_id);
				//the wrapper
                //var $im_wrapper	= $('#im_wrapper');
				var $im_wrapper	=$($fragment).find('div.im_wrapper');
				//var tabId='#fragment-'+tabNo;
				//alert(tabId);
				//var $div=$(tabId).find('div.square-thumb');
				//the thumbs
				var $thumbs		= $im_wrapper.children('div');
				//all the images
				var $thumb_imgs = $thumbs.find('img');
				//number of images
				var nmb_thumbs	= $thumbs.length;
				//image loading status
				var $im_loading	= $('#im_loading');
				//the next and previous buttons
				var $im_next	= $('#im_next');
				var $im_prev	= $('#im_prev');
				//number of thumbs per line
				var per_line	= 6;
				//number of thumbs per column
				var per_col		= Math.ceil(nmb_thumbs/per_line)
				//index of the current thumb
				var current		= -1;
				//mode = grid | single
				var mode		= 'grid';
				//an array with the positions of the thumbs
				//we will use it for the navigation in single mode
				var positionsArray = [];
				for(var i = 0; i < nmb_thumbs; ++i)
					positionsArray[i]=i;
				
				
				//preload all the images
				$im_loading.show();
				var loaded		= 0;
				$thumb_imgs.each(function(){
					var $this = $(this);
					$('<img/>').load(function(){
						++loaded;
						if(loaded == nmb_thumbs*2)
							start();
					}).attr('src',$this.attr('src'));
					$('<img/>').load(function(){
						++loaded;
						if(loaded == nmb_thumbs*2)
							start();
					}).attr('src',$this.attr('src').replace('/thumbs',''));
				});
				
				//starts the animation
				function start(){
					$im_loading.hide();
					//disperse the thumbs in a grid
					
					disperse();
				}
				
				//disperses the thumbs in a grid based on windows dimentions
				function disperse(){
					//initialise  the resources on each run
					var left = 0;
					var top = 0;
					var spaces_w = 0;
					var spaces_h = 0;
					var r = 0;
					var title="";
					
				
				//alert("disperse begin");
					if(!flg_click) return;
					setflag();
					mode			= 'grid';
					//center point for first thumb along the width of the window
					spaces_w 	= $(window).width()/(per_line + 1);
					//center point for first thumb along the height of the window
				    spaces_h 	= $(window).height()/(per_col + 1);
					//let's disperse the thumbs equally on the page
					$thumbs.each(function(i){
							var $thumb 	= $(this);
							//calculate left and top for each thumb,
							//considering how many we want per line
							//alert($thumb.closest('.dTabs').attr('id'));
							
							left	= spaces_w*((i%per_line)+1) - $thumb.width()/2;
							top		= spaces_h*(Math.ceil((i+1)/per_line)) - $thumb.height()/2;
							//alert( "left :"+left+",top:"+top);
							//alert("left:"+left+"top:"+top+"i="+i);
							//lets give a random degree to each thumb
							r 		= Math.floor(Math.random()*41)-20;
							/*
							now we animate the thumb to its final positions;
							we also fade in its image, animate it to 115x115,
							and remove any background image	of the thumb - this
							is not relevant for the first time we call disperse,
							but when changing from single to grid mode
							 */
							if(ie)
								var param = {
									'left'		: left + 'px',
									'top'		: top + 'px'
								};
							else
								var param = {
									'left'		: left + 'px',
									'top'		: top + 'px',
									'rotate'	: r + 'deg'
								};
								//alert("disperse in process:"+param+":"+$thumb);
								//added by manoj begin
								/*if(tabNo>1)
								{
									var l=left+"px;";
									var t=top+"px;";
									var rot=r+"px;";
									var tabId='#fragment-'+tabNo;
									alert(tabId);
									var $div=$(tabId).find('div.square-thumb');
									alert("div found:"+$div.attr('class'));
										
										//new begin
										$div.stop(true, true)
										.animate(param,700,function(){
										   alert("inside animate new");
											if(i==nmb_thumbs-1)
												setflag();
										})
										.find('img')
										.fadeIn(700,function(){
											$thumb.css({
												'background-image'	: 'none'
											});
											$(this).animate({
												'top'       : '0px',
												'left'      : '0px', 
												'width'		: '115px',
												'height'	: '115px',
												'marginTop'	: '5px',
												'marginLeft': '5px'
											},150);
										//new end
										//alert("end of div from new cond\n");
										});
								}*/
								//added by manoj end
								$thumb.stop()
								.animate(param,1000,function(){
								   //alert("inside animate");
									if(i==nmb_thumbs-1)
										setflag();
								})
								.find('img')
								.fadeIn(700,function(){
									$thumb.css({
										'background-image'	: 'none'
									});
									//added by manoj end
									$(this).animate({
										'top'       : '0px',
										'left'      : '0px', 
										'width'		: '115px',
										'height'	: '115px',
										'marginTop'	: '5px',
										'marginLeft': '5px'
									},150);
								});
								//added by manoj  begin
								/*if(tabNo>1)
								{
									$(this).attr('top','0px');
									$(this).attr('left','0px');
									$(this).attr('width','115px');
									$(this).attr('height','115px');
									$(this).attr('marginTop','5px');
									$(this).attr('marginLeft','5px');
									alert("end of img from new cond");
								}*/
								//added by manoj end
								/*$(this).animate({
									'top'       : '0px',
									'left'      : '0px', 
									'width'		: '115px',
									'height'	: '115px',
									'marginTop'	: '5px',
									'marginLeft': '5px'
								},150);*/
						});
						title = $(this).attr("class");
						//alert("disperse end,parameters:".title);
					}	
			
				
				
				//controls if we can click on the thumbs or not
				//if theres an animation in progress
				//we don't want the user to be able to click
				function setflag(){
					flg_click = !flg_click
				}
				
				/*
				when we click on a thumb, we want to merge them
				and show the full image that was clicked.
				we need to animate the thumbs positions in order
				to center the final image in the screen. The
				image itself is the background image that each thumb
				will have (different background positions)
				If we are currently seeing the single image,
				then we want to disperse the thumbs again,
				and with this, showing the thumbs images.
				 */
				$thumbs.bind('click',function(){
					if(!flg_click) return;
					setflag();
					
					var $this 		= $(this);
					current 		= $this.index();
					
					if(mode	== 'grid'){
						mode			= 'single';
						//the source of the full image
						var image_src	= $this.find('img').attr('src').replace('/thumbs','');
						
						$thumbs.each(function(i){
							var $thumb 	= $(this);
							var $image 	= $thumb.find('img');
							//first we animate the thumb image
							//to fill the thumbs dimentions
							$image.stop().animate({
								'width'		: '100%',
								'height'	: '100%',
								'marginTop'	: '0px',
								'marginLeft': '0px'
							},150,function(){
								//calculate the dimentions of the full image
								var f_w	= per_line * 125;
								var f_h	= per_col * 125;
								var f_l = $(window).width()/2 - f_w/2
								var f_t = $(window).height()/2 - f_h/2
								/*
								set the background image for the thumb
								and animate the thumbs postions and rotation
								 */
								if(ie)
									var param = {
										'left'	: f_l + (i%per_line)*125 + 'px',
										'top'	: f_t + Math.floor(i/per_line)*125 + 'px'
									};
								else
									var param = {
										'rotate': '0deg',
										'left'	: f_l + (i%per_line)*125 + 'px',
										'top'	: f_t + Math.floor(i/per_line)*125 + 'px'
									};
								$thumb.css({
									'background-image'	: 'url('+image_src+')',
									'background-size': '750px 500px'  /*  stretching the image to cover full canvas */
									
								
								}).stop()
								.animate(param,1200,function(){
									//insert navigation for the single mode
									if(i==nmb_thumbs-1){
										addNavigation();
										setflag();
									}
								});
								//fade out the thumb's image
								$image.fadeOut(700);
							});
						});
					}
					else{
						setflag();
						//remove navigation
						removeNavigation();
						//if we are on single mode then disperse the thumbs
						disperse();
					}
				});
				
				//removes the navigation buttons
				function removeNavigation(){
					$im_next.stop().animate({'right':'-50px'},300);
					$im_prev.stop().animate({'left':'-50px'},300);
				}
				
				//add the navigation buttons
				function addNavigation(){
					$im_next.stop().animate({'right':'0px'},300);
					$im_prev.stop().animate({'left':'0px'},300);
				}
				
				//User clicks next button (single mode)
				$im_next.bind('click',function(){
					if(!flg_click) return;
					setflag();
					
					++current;
					var $next_thumb	= $im_wrapper.children('div:nth-child('+(current+1)+')');
					if($next_thumb.length>0){
						var image_src	= $next_thumb.find('img').attr('src').replace('/thumbs','');
						var arr 		= Array.shuffle(positionsArray.slice(0));
						$thumbs.each(function(i){
							//we want to change each divs background image
							//on a different point of time
							var t = $(this);
							setTimeout(function(){
								t.css({
									'background-image'	: 'url('+image_src+')'
								});
								if(i == nmb_thumbs-1)
									setflag();
							},arr.shift()*20);
						});
					}
					else{
						setflag();
						--current;
						return;
					}
				});
				
				//User clicks prev button (single mode)
				$im_prev.bind('click',function(){
					if(!flg_click) return;
					setflag();
					--current;
					var $prev_thumb	= $im_wrapper.children('div:nth-child('+(current+1)+')');
					if($prev_thumb.length>0){
						var image_src	= $prev_thumb.find('img').attr('src').replace('/thumbs','');
						var arr 		= Array.shuffle(positionsArray.slice(0));
						$thumbs.each(function(i){
							var t = $(this);
							setTimeout(function(){
								t.css({
									'background-image'	: 'url('+image_src+')'
								});
								if(i == nmb_thumbs-1)
									setflag();
							},arr.shift()*20);
						});
					}
					else{
						setflag();
						++current;
						return;
					}
				});
				
				//on windows resize call the disperse function
				$(window).smartresize(function(){
					removeNavigation()
					disperse();
				});
				
				//function to shuffle an array
				Array.shuffle = function( array ){
					for(
					var j, x, i = array.length; i;
					j = parseInt(Math.random() * i),
					x = array[--i], array[i] = array[j], array[j] = x
				);
					return array;
				};
            });
        </script>
		
    </body>
</html>
<?php
}
else
{
	//session_destroy();
	header( 'Location: index.php' ) ;
}
?>