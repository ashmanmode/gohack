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

            //echo "</pre>";
         ?>


  <nav class="light-blue lighten-1" role="navigation">
    <div class="nav-wrapper container"><a id="logo-container" href="index.php" class="brand-logo">GoIbibo</a>
      <ul class="right hide-on-med-and-down">
        <li><a href="">Flights</a></li>
      </ul>

      <ul id="nav-mobile" class="side-nav">
        <li><a href="#">Flights</a></li>
      </ul>
      <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
    </div>
  </nav>
 
    
	<div class="container">
		<h2 style="text-align:center" class="bg-success"> Welcome to my Go ibibo hackthon </h2>
		
		<div class="container">

			<div class="row">
				<div class="col-sm-6">
					
					<img src="<?php echo $images[1]["l"]; ?>" style="max-width:500px" />
					
				</div>

				<div class="col-sm-6">
					<h3> <?php echo $name ?> </h3>
					<h4> <?php if($price == $discount){
							echo "<span style='font-size:3em' >Rs ".$price."</span>";
						} else{

						
			        		echo "Rs <span style='text-decoration: line-through;' >".
			        		$price."</span><br />";
			        		
			        	
			        		echo "<span style='font-size:3em' >Rs ".$discount."</span>";
							}?>
					</h4>

					<p> <?php //print_r($extra); ?> </p>
					<p> <?php echo $extra["service"]; ?> </p>
					<table class="table table-striped"> <?php 

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
			</div>
			<div class="row">
			<h2 class="bg-danger" style="text-align:center">See location in  Map  </h2>
				<script>
					var myCenter=new google.maps.LatLng(<?php echo $location["lat"]; ?>,<?php echo $location["long"]; ?>);

					function initialize()
					{
					var mapProp = {
					  center:myCenter,
					  zoom:15,
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

					<div id="googleMap" style="width:800px;height:400px; margin: 0 auto"></div>
			</div>
		
		</div>

		<div class="container">
		<h2 class="bg-info" style="text-align:center"> Room Details </h2>
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

		<div class="container">
		<h2 class="bg-success" style="text-align:center"> Reviews</h2>
			<table class="table table-striped"> <?php 
							//print_r($rooms);
						foreach ($reviews as $review) {
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
	<footer class="page-footer orange">
    <div class="container">
      <div class="row">
        <div class="col l6 s12">
          <h5 class="white-text">Company Bio</h5>
          <p class="grey-text text-lighten-4">We are a team of college students working on this project like it's our full time job. Any amount would help support and continue development on this project and is greatly appreciated.</p>


        </div>
        <div class="col l3 s12">
          <h5 class="white-text">Settings</h5>
          <ul>
            <li><a class="white-text" href="#!">Link 1</a></li>
            <li><a class="white-text" href="#!">Link 2</a></li>
            <li><a class="white-text" href="#!">Link 3</a></li>
            <li><a class="white-text" href="#!">Link 4</a></li>
          </ul>
        </div>
        <div class="col l3 s12">
          <h5 class="white-text">Connect</h5>
          <ul>
            <li><a class="white-text" href="#!">Link 1</a></li>
            <li><a class="white-text" href="#!">Link 2</a></li>
            <li><a class="white-text" href="#!">Link 3</a></li>
            <li><a class="white-text" href="#!">Link 4</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="footer-copyright">
      <div class="container">
      Made by <a class="orange-text text-lighten-3" href="http://webtutplus.com">US</a>
      </div>
    </div>
  </footer>

</body>
</html>