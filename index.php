<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <title>Goibibo</title>

  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
 <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"
    rel="stylesheet">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
<script type="text/javascript">
  $('select').select2();

  
  $(document).ready(function() {
  $(".js-example-basic-single").select2();
});
</script>

<script type="text/javascript">
$(document).ready(function(){
    $('tr').mouseover(function(){
        var valueOfTd = $(this).closest('tr').index();
        var id = parseInt(parseInt(valueOfTd)/2) ;
        //console.log(parseInt(parseInt(valueOfTd)/2)); // Do here what you want with the value.
        xoom(id);
        infowindow[id].open(map, markers[id]);
    });
    $('tr').mouseout(function(){
        var valueOfTd = $(this).closest('tr').index();
        var id = parseInt(parseInt(valueOfTd)/2) ;
        //console.log(parseInt(parseInt(valueOfTd)/2)); // Do here what you want with the value.
        map.setZoom(12);
        infowindow[id].close(map, markers[id]);
    });
});
</script>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>
<script>
var map ;
var bounds = new google.maps.LatLngBounds();
var infowindow = Array();
var markers  = Array();
function initialize() {

    console.log('init');
 
   var mapProp = {
 
     center:new google.maps.LatLng(28.6139,77.2090),
 
     zoom:4,
 
     mapTypeId:google.maps.MapTypeId.ROADMAP
 
   };
 
   map = new google.maps.Map(document.getElementById("map-canvas"),mapProp);

   for(var i = 0; i < loc_lat.length ; i++ )
   {

        var center = new google.maps.LatLng(loc_lat[i],loc_lon[i]);
     
       var marker = new google.maps.Marker({
       
       position:center,
       
       });
     
         marker.setMap(map);
         map.panTo(center);
         bounds.extend(center);
         map.fitBounds(bounds);

         var contentS = contentString[i];
         var info = new google.maps.InfoWindow({
                              content: contentS
                            });

         marker.addListener('click', function() {
                  console.log(i);
                  info.open(map, marker);
                });

        //infowindow
        

        
        infowindow.push(info);
        markers.push(marker);
   }
 
 }

 google.maps.event.addDomListener(window, 'load', initialize);

var loc_lat = Array();
var loc_lon = Array();
var contentString = Array();
 function plot(lat,lon,content)
 {
  loc_lat.push(lat);
  loc_lon.push(lon);
  contentString.push(content);
  }

  function xoom(i)
  {
    var center = new google.maps.LatLng(loc_lat[i],loc_lon[i]);
    map.setZoom(14);
    map.panTo(center);

  }
  
</script>

</head>

<body>
<?php 
    set_time_limit(100);
    include 'connect.php';
    

    if(isset($_GET['submit'])){
      $button_pressed = true ;
      $city = $_GET["city_code"];
      $check_in = date('Y-m-d',strtotime($_GET["check_in"]));
      $check_out = date('Y-m-d',strtotime($_GET["check_out"]));

      $check_in = str_replace("-", "", $check_in);
      $check_out = str_replace("-", "", $check_out);
      //$city = $_GET["city_code"];
      $url = "http://developer.goibibo.com/api/cyclone/?app_id=db80f519&app_key=bee575e376d1d851b3476eff19689194&city_id=".$city."&check_in=".$check_in."&check_out=".$check_out;
      //echo $url."<br/>";

      $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
             curl_setopt($ch ,CURLOPT_PROXY, '10.3.100.207');
            curl_setopt($ch, CURLOPT_PROXYPORT,'8080');
            $page = curl_exec($ch);
            $page = json_decode($page, true);
            $page = $page["data"];
            
            $hotels_id = array();
            $count = 0;
            foreach ($page as $key => $value) {
              $hotels_id[] = $key;
              $price_list[] = array($value["op"],$value["op_wt"]);
              $count +=1;

              if($count >= 20){
                break;
              }
            }

            //print_r(urlencode(json_encode($hotels_id)));
            $ids = urlencode(json_encode($hotels_id));

            $url = "http://developer.goibibo.com/api/voyager/?app_id=db80f519&app_key=bee575e376d1d851b3476eff19689194&method=hotels.get_hotels_data&id_list=".$ids."&id_type=_id";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
             curl_setopt($ch ,CURLOPT_PROXY, '10.3.100.207');
            curl_setopt($ch, CURLOPT_PROXYPORT,'8080');
            $page = curl_exec($ch);
             $page = json_decode($page, true);
            $page = $page["data"];


    }
  ?>

  <nav class="light-blue lighten-1" role="navigation">
    <div class="nav-wrapper container"><a id="logo-container" href="#" class="brand-logo">GoIbibo</a>
      <ul class="right hide-on-med-and-down">
        <li><a href="#">Flights</a></li>
      </ul>

      <ul id="nav-mobile" class="side-nav">
        <li><a href="#">Flights</a></li>
      </ul>
      <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
    </div>
  </nav>
  <div class="section no-pad-bot" id="index-banner">
    <div class="container">
      <br><br>
      <h1 class="header center orange-text">Search Hotels</h1>
      <div class="row center">
        <h5 class="header col s12 light"></h5>
      </div>

    </div>
  </div>


<div class="container">
  <div class="row">
    <form action="" method="get" class="col s12">
      <div class="row">
        <div class="input-field col s3 form-group">
          <input id="check-in" type="date" class="datepicker" name="check_in" required>
          <label for="check-in">Check-In</label>
        </div>
        <div class="input-field col s3 form-group">
          <input id="check-in" type="date" class="datepicker" name="check_out" required>
          <label for="check-out">Check-In</label>
        </div>
        <div class="input-field col s3 form-group">
          <label for="exampleInputEmail1">City Code</label>
          
          <select class="js-example-basic-single form-control" name="city_code" required>
          <?php $sql = "SELECT * FROM city";
           $result = mysqli_query($conn, $sql);
              
              if (mysqli_num_rows($result) > 0) {
                  // output data of each row
                  while($row = mysqli_fetch_assoc($result)) {
                    ?>
                     <option value="<?php echo $row["city_id"] ?>"><?php echo $row["city"] ?></option>
                    <?php   
                  }
                }
               ?>
        </select>
        </div>
         <button class="btn waves-effect waves-purple col s3" type="submit" name="submit">Search
         <i class="material-icons right">send</i>
      </button>
      </div>

      <br> </br> 

    </form>
  
        <div  class="col s7" id="map-canvas" style="height:500px">
        </div> 
        <div  class="pre-scrollable col s5" style="height:500px"> 

        <table class="" >
        <thead>
          <tr>
            <td>
              Name
            </td>
            <td>
              Location</td>
          </tr>
         </thead>
         <tbody>

          <?php 
          $count = 0;
          if (isset($page) ){  

            foreach ($page as $key => $value) { 
              $count +=1;
              if($count >= 20){
                break;
              }
              echo "<tr>";

              $url = $value["hotel_data_node"]["img_selected"]["r"]["l"];
              echo "<td>";
              echo "<img src='$url' />";
              echo "</td>";
              
              
              $content = "'<img src=".$url." /><h3><blockquote>".$value["hotel_geo_node"]["name"]."</h3></blockquote>'";
              $lat =  $value["hotel_geo_node"]["location"]["lat"]; 
              $long = $value["hotel_geo_node"]["location"]["long"]; 
              echo '<script>plot('.$lat.','.$long.','.$content.')</script>';

            echo "<td><blockquote>";
              echo $value["hotel_geo_node"]["name"];
              echo "</blockquote></td>";

              

              $loc = $value["hotel_data_node"]["loc"]["location"];
              $pin = $value["hotel_data_node"]["loc"]["pin"];
              $city = $value["hotel_data_node"]["loc"]["city"];
              $state = $value["hotel_data_node"]["loc"]["state"];
              $country = $value["hotel_data_node"]["loc"]["country"];
              echo "<td><p>";
              echo $loc." ".$city." ".$state." ".$country." ".$pin;
              echo "</p></td>";

              

              
              // echo "<td>";
              // echo $pin;
              // echo "</td>";
              
              // echo "<td>";
              // echo $city;
              // echo "</td>";

              // echo "<td>";
              // echo $state;
              // echo "</td>";

              // echo "<td>";
              // echo $country;
              // echo "</td>";

              
              echo "</tr>";

              echo "<tr>";
              echo "<td><div class='chip'><i class='material-icons' style='font-size:30px'>payment</i>";
              echo "Rs <span style='text-decoration: line-through;' >".$price_list[$count-1][1]."</span>";
              echo "</div></td>";

              echo "<td><div class='chip'><i class='material-icons' style='font-size:30px'>payment</i><strong>";
              echo "Rs ".$price_list[$count-1][0];
              echo "</strong></div></td>";

              echo "<td>";
              $price = $price_list[$count-1][1] ;
              $discounted = $price_list[$count-1][0];
              //echo "<a class='btn btn-success'
               //href='details.php?id=".$key."&&price=".$price."&&discount=".$discounted."'> Book Now </a>";
              

              echo '<a class="waves-effect waves-light btn" href="details.php?id='.$key.'&&price='.$price.'&&discount='.$discounted.'">Book Now</a>';

              echo "</td>";
              echo "</tr>";

              //break;
            }


             
          } ?>
        
        </tbody>
        </table>
        
      </div>
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


  <!--  Scripts-->
  <!--<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>-->
  <script src="js/init.js"></script>
  <!---<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>-->
  <script type="text/javascript" src="js/materialize.min.js"></script>
  <script type="text/javascript">
   $('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 15 // Creates a dropdown of 15 years to control year
  });</script>

  </body>
</html>