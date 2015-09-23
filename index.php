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
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <script type="text/javascript">
  $('select').select2();

  
  $(document).ready(function() {
    $(".js-example-basic-single").select2();
  });
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
  <div class="section no-pad-bot" id="index-banner">
    <div class="container">
      <h1 class="header center " style="text-transform: uppercase;    font-weight: 700;
      text-transform: uppercase;
      text-shadow: 1px 1px 6px rgba(0,0,0,.2);">Searching Dream Hotels Starts Here</h1>

    </div>
    <br>


    <div class="row">
      <div class="col s6 m6" style="margin-left: 35%;width: 30%;">
        <div class="card waves-block waves-light z-depth-4">
          <form action="front.php" method="get" class="col s12" style="">
           <div class="card-content white-text">
            <div class="row">
              <div class="input-field col s12 form-group">
                <input id="check-in" type="date" class="datepicker" name="check_in" required>
                <label for="check-in"><strong>Check-In</strong></label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12 form-group">
                <input id="check-in" type="date" class="datepicker" name="check_out" required>
                <label for="check-out">Check-Out</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12 form-group">

                <label for="exampleInputEmail1"><strong>Where do want to check</strong></label>

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
            </div>
          </div>

          <div class="card-action">

           <button class="btn waves-effect waves-purple col s12" type="submit" name="submit">Search
             <i class="material-icons right">send</i>
           </button>


         </div>




         <br> </br> 

       </form>

     </div>
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


<!--  Scripts-->
<!--<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>-->
<script src="js/init.js"></script>
<!---<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>-->
<script type="text/javascript" src="js/materialize.min.js"></script>
<script type="text/javascript">
$('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 15, // Creates a dropdown of 15 years to control year
  });</script>

</body>
</html>