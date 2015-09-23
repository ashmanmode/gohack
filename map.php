<!DOCTYPE html>
<html>
<head>
  <title>Place searches</title>
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <meta charset="utf-8">
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
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <style>
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
    }
    #map {
      height: 100%;
    }
  </style>
  <?php
  if(isset($_GET['lat'] ) && isset($_GET['lon']) )
  {
   $lat = $_GET['lat'] ;
   $lon = $_GET['lon'] ;
   $name = $_GET['name'];
 }
 ?>

 <script>
  var map;
  var infowindow;
  var service;
  var pyrmont ;
  var markers = [] ;
  var tp ;

  function initMap() {
    pyrmont = {lat:  <?php echo $lat; ?>, lng: <?php echo $lon; ?>};

    map = new google.maps.Map(document.getElementById('map'), {
      center: pyrmont,
      zoom: 15
    });

    var marker=new google.maps.Marker({
      position:pyrmont,
    });
    var contentS = "<h2>This is where you are going to live.</h2> <p> At <strong>"+ '<?php echo $name;?>' + "</strong></p>";
    var info = new google.maps.InfoWindow({
      content: contentS
    });

    marker.setMap(map);
    marker.addListener('click', function() {
      info.open(map, marker);
    });

    infowindow = new google.maps.InfoWindow();
  }

  function getplaces(type) {
    tp = type ;
    for(var key in markers)
    {
    //console.log(key);
    markers[key].setMap(null) ;
  }
  service = new google.maps.places.PlacesService(map);
  service.nearbySearch({
    location: pyrmont,
    radius: 500,
    types: [type]
  }, callback);
}

function callback(results, status) {
  if (status === google.maps.places.PlacesServiceStatus.OK) {
    for (var i = 0; i < results.length; i++) {
      createMarker(results[i],tp,i);
    }
  }
}

function createMarker(place,type,i) {
  var placeLoc = place.geometry.location;
  var image = {
    url: place.icon,
    size: new google.maps.Size(71, 71),
    origin: new google.maps.Point(0, 0),
    anchor: new google.maps.Point(17, 34),
    scaledSize: new google.maps.Size(25, 25)
  };
  var marker = new google.maps.Marker({
    icon: image,
    map: map,
    position: place.geometry.location
  });

  markers[type+i] = marker ;

  google.maps.event.addListener(marker, 'click', function() {
    infowindow.setContent(place.name);
    infowindow.open(map, this);
  });
}
</script>
</head>
<body>
 <nav class="z-depth-4 ibiboheader z-depth-4" role="navigation">
  <div class="nav-wrapper container "><a id="logo-container" href="index.php" class="brand-logo">
    <img src="http://goibibo.ibcdn.com/styleguide/images/goLogo.png" style="width:50%">
  </a>
  <strong class="brand-logo center"><?php echo $name;?></strong>
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
<div class="col-sm-10" id="map"></div>
<div class="col-sm-2">
  <h2>Nearby Places</h2>
  <div class="collection">
    <a href="#!" class="collection-item" onclick="getplaces('atm')">ATM's<span class="new badge">1</span></a>
    <a href="#!" class="collection-item" onclick="getplaces('bank')">Bank's<span class="new badge">1</span></a>
    <a href="#!" class="collection-item" onclick="getplaces('store')">Stores<span class="new badge">4</span></a>
    <a href="#!" class="collection-item" onclick="getplaces('hospital')">Hospital<span class="new badge">4</span></a>
    <a href="#!" class="collection-item" onclick="getplaces('bar')">Bar<span class="new badge">4</span></a>
    <a href="#!" class="collection-item" onclick="getplaces('beauty_salon')">Beauty Salon<span class="new badge">4</span></a>
    <a href="#!" class="collection-item" onclick="getplaces('book_store')">Book Store's<span class="new badge">4</span></a>
    <a href="#!" class="collection-item" onclick="getplaces('cafe')">Cafe<span class="new badge">4</span></a>
    <a href="#!" class="collection-item" onclick="getplaces('clothing_store')">Clothing store's<span class="new badge">4</span></a>
    <a href="#!" class="collection-item" onclick="getplaces('train_station')">Train Station<span class="new badge">4</span></a>
    <a href="#!" class="collection-item" onclick="getplaces('taxi_stand')">Taxi Stand<span class="new badge">4</span></a>
    <a href="#!" class="collection-item" onclick="getplaces('school')">School<span class="new badge">4</span></a>
    <a href="#!" class="collection-item" onclick="getplaces('restaurant')">Restaurent<span class="new badge">4</span></a>
    <a href="#!" class="collection-item" onclick="getplaces('museum')">Museum<span class="new badge">4</span></a>
    <a href="#!" class="collection-item" onclick="getplaces('library')">Library<span class="new badge">4</span></a>
    <a href="#!" class="collection-item" onclick="getplaces('laundry')">Laundry<span class="new badge">4</span></a>
    <a href="#!" class="collection-item" onclick="getplaces('liquor_store')">Liquor Stores<span class="new badge">4</span></a>
  </div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?keysigned_in=true&libraries=places&callback=initMap" async defer></script>
<script src="js/init.js"></script>
<!---<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>-->
<script type="text/javascript" src="js/materialize.min.js"></script>
</body>
</html>