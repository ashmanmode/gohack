<!DOCTYPE html>
<html>
<head>
	<title>GO Ibibo hackathon</title>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<!-- Compiled and minified CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.1/css/materialize.min.css">

	<!-- Compiled and minified JavaScript -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.1/js/materialize.min.js"></script>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"
	rel="stylesheet">
	
	<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
	<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
	<link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
	<link href="css/circle.css" type="text/css" rel="stylesheet" media="screen,projection"/>
	<script type="text/javascript">
		$('select').select2();

		
		$(document).ready(function() {
			$(".js-example-basic-single").select2();
		});
	</script>

	<script
	src="http://maps.googleapis.com/maps/api/js">
</script>



</head>
<body>
	<?php 
	set_time_limit(100);
	include 'connect.php';
	
            //print_r($page);
		//echo "<pre>";
	
	if(!isset($_GET['id'])){
		echo "no id ";
		exit(1);
	}

	$hotel_id = $_GET['id'];
	$price = $_GET["price"];
	$discount = $_GET["discount"];

	$url = "http://developer.goibibo.com/api/voyager/?app_id=db80f519&app_key=bee575e376d1d851b3476eff19689194&method=hotels.get_hotels_data&id_list=%5B".$hotel_id."%5D&id_type=_id";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch ,CURLOPT_PROXY, '10.3.100.207');
	curl_setopt($ch, CURLOPT_PROXYPORT,'8080');
	$page = curl_exec($ch);
	$page = json_decode($page, true);
	$page = $page["data"];

	foreach ($page as $key => $value) {
		$hotel = $page[$key];
            	//print_r($key);
	}
            //print_r($hotel);


	$name = $hotel["hotel_geo_node"]["name"];

	$location = $hotel["hotel_geo_node"]["location"];
	$images = $hotel["hotel_data_node"]["img_processed"];

	$extra = $hotel["hotel_data_node"]["extra"];

	$rooms = $hotel["hotel_data_node"]["room_info"];

	$lat = $location["lat"];
	$long = $location["long"];
	

            //echo "</pre>";
	?>


	<nav class="z-depth-4 ibiboheader z-depth-4" role="navigation">
		<div class="nav-wrapper container "><a id="logo-container" href="index.php" class="brand-logo">
			<img src="http://goibibo.ibcdn.com/styleguide/images/goLogo.png" style="width:50%">
		</a>
		<ul class="right hide-on-med-and-down">
			<li><a href="http://www.goibibo.com/flights/">Flights</a></li>
			<li><a href="http://www.goibibo.com/bus/">Bus</a></li>
			<li><a href="http://www.goibibo.com/holidays/holiday-packages-india/">Holidays</a></li>
			<li><a href="http://www.goibibo.com/go/f/">Flight+Hotels</a></li>
		</ul>

		<ul id="nav-mobile" class="side-nav">
			<li><a href="http://www.goibibo.com/flights/">Flights</a></li>
			<li><a href="http://www.goibibo.com/bus/">Bus</a></li>
			<li><a href="http://www.goibibo.com/holidays/holiday-packages-india/">Holidays</a></li>
			<li><a href="http://www.goibibo.com/go/f/">Flight+Hotels</a></li>
		</ul>
		<a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
	</div>
</nav>


<div class="container">
	
	
	<div class="row">

		<div class="row">
			<div class="col-sm-6">
				
				<img src="<?php echo $images[1]["l"]; ?>" style="max-width:500px" />
				
			</div>

			<div class="col-sm-6">
				<h3 class="h2"> <?php echo $name ?> </h3>
				<h4> <?php if($price == $discount){
					echo "<span style='font-size:3em' >&#8377; ".$price."</span>";
				} else{

					
					echo "Rs <span style='text-decoration: line-through;' >".
					$price."</span><br />";
					
					
					echo "<span class='h3' >&#8377; ".$discount."</span>";
				}?>
			</h4>

			<p> <?php //print_r($extra); ?> </p>
			<p> <?php echo $extra["service"]; ?> </p>

			<?php echo '<a class="waves-effect waves-purple btn" target="_blank" href="map.php?lat='.$lat.'&&lon='.$long.'&&name='.$name.'">See Nearby Places in Map</a>'; ?>
			

		</div>
	</div>
	<div  class="row land_card">
		<div class="row">

			<div class="col-sm-4">
				<h3 class="h3"> Places Near by </h3>
				<table class="table table-striped"> 
					<thead>
						<tr>
							<th> Place </th>
							<th> Distance </th>
							
						</tr>
					</thead>

					<?php 

					foreach ($extra["attractions"] as $attraction) {
						?>
						<tr>
							<td> <?php echo $attraction["Description"]; ?>  </td>
							<td> <?php echo $attraction["Distance"]." ".$attraction["Unit"] ."<br />"; ?> </td> 
						</tr>


						<?php 
					}

					?> </table>
				</div>
				<div class="col-sm-8" style="margin-top:100px;">
					<div id="googleMap" style="width:700px;height:400px; margin: 0 auto"></div>
				</div>
			</div>
			
			<script>
				var myCenter=new google.maps.LatLng(<?php echo $location["lat"]; ?>,<?php echo $location["long"]; ?>);

				function initialize()
				{
					var mapProp = {
						center:myCenter,
						zoom:15,
						zoomControl: false,
						scaleControl: false,
						scrollwheel: false,
						disableDoubleClickZoom: true,
						mapTypeId:google.maps.MapTypeId.ROADMAP
					};

					var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);

					var marker=new google.maps.Marker({
						position:myCenter,
					});

					marker.setMap(map);
				}

				google.maps.event.addDomListener(window, 'load', initialize);
			</script>

			
		</div>
		
	</div>

	<div class="row" >
		<h2 class="h2 land-card" style="text-align:center"> Room Details </h2>
		<ul class="collapsible popout" data-collapsible="accordion"> <?php 
							//echo "<pre>";
							//print_r($rooms[0]);
							//echo "</pre>";
			foreach ($rooms as $room) {
				?>
				<li>
					<div class="collapsible-header"><?php echo $room["type_name"]; ?></div>
					<div class="collapsible-body" >
						<div class="row">
							<div class="col-sm-4"> <img src="<?php echo $room["img_processed"][0]["l"];  ?>" style="max-width:300px" />  </div>
							
							<div class="col-sm-4"> <?php echo $room["description"]; ?> </div> 
							<div class="col-sm-4"> <ul><?php foreach ($room["facilities"] as $value) {
								echo "<li>".$value."</li>";
							} ?> </ul>  
							
						</div>

					</div>
				</div>
			</li>


			<?php 
		}


		?> </ul>

	</div>
	<?php 
	$url = "http://ugc.goibibo.com/api/HotelReviews/forWeb?app_id=db80f519&app_key=bee575e376d1d851b3476eff19689194&vid=".$hotel_id."&limit=100&offset=0";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch ,CURLOPT_PROXY, '10.3.100.207');
	curl_setopt($ch, CURLOPT_PROXYPORT,'8080');
	$page = curl_exec($ch);
	$page = json_decode($page, true);
	$reviews = $page;
	
	?>

	<div class="row ">
		<?php 

		$rating = array();
		$rating[1] = 0;
		$rating[2] = 0;
		$rating[3] = 0;
		$rating[4] = 0;
		$rating[5] = 0;
		$sum = sizeof($reviews);
		foreach ($reviews as $review) {
			$rating[$review["totalRating"]] +=1;

		}
		$average = (1*$rating[1]+2*$rating[2]+3*$rating[3]+4*$rating[4]+5*$rating[5])/($sum);

					//print_r($rating);
		$average *= 100;

		$average = (int)$average;

		$percentage = (int)($average/5);


		?>
		<h2 class="h2 land-card" style="text-align:center"> Reviews</h2>
		<div class="row">
			<div class="col-sm-6">
				<h5>Based on <span itemprop="ratingCount"><?php echo $sum; ?></span> ratings</h5>
				<div class="reviews-true">
					<span id="ratings-wrapper" class="js-pdp-nav-sec" data-link-nav="#defRevPDP" onclick="Snapdeal.pdpReview.displayReviewsWithRatingFilter(this,5,'HELPFUL');" title="Read 1962 reviews for 5-star ratings">
						<span class="lfloat">5 Star</span>
						<span class="barover review-bar" style="width:<?php echo ($rating[5]/$sum)*100;  ?>%"></span>
						<span><?php echo $rating[5]; ?></span>
					</span>
				</div>
				<div class="reviews-true">
					<span id="ratings-wrapper" class="js-pdp-nav-sec" data-link-nav="#defRevPDP" onclick="Snapdeal.pdpReview.displayReviewsWithRatingFilter(this,4,'HELPFUL');" title="Read 597 reviews for 4-star ratings">
						<span class="lfloat">4 Star</span>
						<span class="barover review-bar" style="width:<?php echo  ($rating[4]/$sum)*100;  ?>%"></span>
						<span><?php echo $rating[4]; ?></span>
					</span>
				</div>
				<div class="reviews-true">
					<span id="ratings-wrapper" class="js-pdp-nav-sec" data-link-nav="#defRevPDP" onclick="Snapdeal.pdpReview.displayReviewsWithRatingFilter(this,3,'HELPFUL');" title="Read 86 reviews for 3-star ratings">
						<span class="lfloat">3 Star</span>
						<span class="barover review-bar" style="width:<?php  echo ($rating[3]/$sum)*100;  ?>%"></span>
						<span><?php echo $rating[3]; ?></span>
					</span>
				</div>
				<div class="reviews-true">
					<span id="ratings-wrapper" class="js-pdp-nav-sec" data-link-nav="#defRevPDP" onclick="Snapdeal.pdpReview.displayReviewsWithRatingFilter(this,2,'HELPFUL');" title="Read 11 reviews for 2-star ratings">
						<span class="lfloat">2 Star</span>
						<span class="barover review-bar" style="width:<?php echo ($rating[2]/$sum)*100;  ?>%"></span>
						<span><?php echo $rating[2]; ?></span>
					</span>
				</div>
				<div class="reviews-true">
					<span id="ratings-wrapper" class="js-pdp-nav-sec" data-link-nav="#defRevPDP" onclick="Snapdeal.pdpReview.displayReviewsWithRatingFilter(this,1,'HELPFUL');" title="Read 3 reviews for 1-star ratings">
						<span class="lfloat">1 Star</span>
						<span class="barover review-bar" style="width:<?php echo ($rating[1]/$sum)*100;  ?>%"></span>
						<span><?php echo $rating[1]; ?></span>
					</span>
				</div>
			</div>
			<div class="col-sm-6">
				<h3> Avarage Rating </h3>
				<div class="c100 p<?php echo $percentage; ?> medium">
					<span><?php echo $average/100; ?></span>
					<div class="slice">
						<div class="bar"></div>
						<div class="fill"></div>
					</div>
				</div>
			</div>
			<table class="table table-striped">
				<thead>
					<tr>
						<th> Reviewer name </th>
						<th> Rating </th>
						<th> Review title </th>
						<th> Review  </th>
					</tr>
				</thead>
				<?php 

				$rating = array();
				$rating[1] = 0;
				$rating[2] = 0;
				$rating[3] = 0;
				$rating[4] = 0;
				$rating[5] = 0;

				foreach ($reviews as $review) {
					$rating[$review["totalRating"]] +=1;
				}

					//print_r($rating);
				$count = 0;
							//print_r($rooms);
				foreach ($reviews as $review) {
					if($review["reviewTitle"] == '' &&  $review["reviewContent"] == ''){
						continue;
					}
					$count +=1;
					if($count >= 15){
						break;
					}
					?>

					<tr>
						<td> <?php echo $review["reviewer"]["firstName"]." ".$review["reviewer"]["lastName"];  ?>  </td>
						<td> <?php echo $review["totalRating"]; ?> </td> 
						<td> <?php echo $review["reviewTitle"]; ?> </td> 
						<td> <?php echo $review["reviewContent"]; ?> </td> 
						
					</tr>


					<?php 
				}


				?> </table>

				
			</div>


			
			
			
		</div>
	</div>


	<footer class="page-footer">
		<div class="container">
			<div class="row">
				<div class="col l6 s12">
					<h5 class="white-text">About Us</h5>
					<p class="grey-text text-lighten-4">We are people trying to make web development and programming easy for you. We write tutorials on wide range of programming areas.Thank you for your extended support.If have any queries or want to guide us on something, feel free to contact us at contact.webtutplus@gmail.com</p>


				</div>
				<div class="col l3 s12">
					
				</div>
				<div class="col l3 s12">
					
				</div>
			</div>
		</div>
		<div class="footer-copyright">
			<div class="container">
				Made by <a class="orange-text text-lighten-3" href="http://webtutplus.com">Webtut+</a>
			</div>
		</div>
	</footer>


</body>
</html>