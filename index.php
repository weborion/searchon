<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Dashboard - Search On</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600"
        rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/pages/dashboard.css" rel="stylesheet">
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <style>
	      #map-canvas {
	        width: 500px;
	        height: 400px;
	      }
	    </style>
	    <script src="https://code.angularjs.org/1.3.9/angular.min.js"> </script>
	    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
	    <script>
	    
	    function initialize(term) {
  
  var startPos;
  var geoOptions = {
    enableHighAccuracy: true
  }

  var geoSuccess = function(position) {
    startPos = position;
    //console.log(startPos.coords.latitude);
    //console.log(startPos.coords.longitude);
   var myLatlng = new google.maps.LatLng(startPos.coords.latitude,startPos.coords.longitude);
   var mapOptions = {
    zoom: 12,
    center: myLatlng
  }
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title: 'You !',
      icon:"http://www.stolencamerafinder.com/images/pin-lost.png"
      
  });
  
  //Load Yelp Search Results
  //console.log(map);
  //console.log(map.center.lat());
  var cll= map.center.lat()+','+map.center.lng();
  //console.log(cll);
  $.ajax({ 
        type: 'GET', 
        url: 'http://demo.weborion.in/searchon/yelp.php?term='+term+'&location=&cll='+cll,
        data: { get_param: 'value' }, 
        success: function (data) { 
       
            
		$('#searchResults').html(data);
		
        }
        //searches.innerHTML = json_arr;
        
    });
  /// Load Map Pins
  var markerUrl='http://demo.weborion.in/searchon/yelp.php?term='+term+'&type=map&location=&cll='+cll;
    $.ajax({
		dataType: "json",
		url: markerUrl,
		success: function (locations) { 
		       
		                
				//console.log(map);
				setMarkers(map,locations);
				
		        }
	});
  //////////
  
  
 };
  var geoError = function(error) {
    console.log('Error occurred. Error code: ' + error.code);
    // error.code can be:
    //   0: unknown error
    //   1: permission denied
    //   2: position unavailable (error response from location provider)
    //   3: timed out
  };

  navigator.geolocation.getCurrentPosition(geoSuccess, geoError, geoOptions);
  
  
  ///////////////////////////
  
  
  
  function setMarkers(map,locations){

      var marker, i

	for (i = 0; i < locations.length; i++)
	 {  
	
	 var loan = locations[i][0]
	 var lat = locations[i][1]
	 var long = locations[i][2]
	 var add =  locations[i][3]
	
	 latlngset = new google.maps.LatLng(lat, long);
	
	  var marker = new google.maps.Marker({  
	          map: map, title: loan , position: latlngset  
	        });
	        //map.setCenter(marker.getPosition())
	
	
	        /*var content = "Loan Number: " + loan +  '</h3>' + "Address: " + add     
	
	 	 var infowindow = new google.maps.InfoWindow()
	
		google.maps.AddListener(marker,'click', function(map,marker){
	          infowindow.setContent(content)
	          infowindow.open(map,marker)
	
		});*/
	
	  }
  }
}

google.maps.event.addDomListener(window, 'load', initialize('Restaurants'));
	    
	    </script>
	   
</head>
<body >
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span
                    class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span> </a><a class="brand" href="http://demo.weborion.in/searchon/">Search On </a>
      <div class="nav-collapse">
        <ul class="nav pull-right">
          <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                            class="icon-cog"></i> Account <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="javascript:;">Settings</a></li>
              <li><a href="javascript:;">Help</a></li>
            </ul>
          </li>
          <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                            class="icon-user"></i> Search On <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="javascript:;">Profile</a></li>
              <li><a href="javascript:window.location.href='http://demo.weborion.in/searchon/login';">Logout</a></li>
            </ul>
          </li>
        </ul>
        <div class="navbar-search pull-right">
          <input type="text" class="search-query" placeholder="Search" onkeydown="if(event.keyCode==13)initialize(this.value);">
        </div>
      </div>
      <!--/.nav-collapse -->
    </div>
    <!-- /container -->
  </div>
  <!-- /navbar-inner -->
</div>
<!-- /navbar -->
<div class="subnavbar">
  <div class="subnavbar-inner">
    <div class="container">
      <ul class="mainnav">
        <li class="active"><a href="index.php"><i class="icon-dashboard"></i><span>Yelp</span> </a> </li>
        <li><a href="#"><i class="icon-list-alt"></i><span>Foursquare</span> </a> </li>
        <li><a href="#"><i class="icon-facetime-video"></i><span>Google Place</span> </a></li>
       <!-- <li><a href="#"><i class="icon-bar-chart"></i><span>Charts</span> </a> </li> -->
        <li><a href="#"><i class="icon-code"></i><span>Your Account</span> </a> </li>
      <!--  <li class="dropdown"><a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-long-arrow-down"></i><span>Drops</span> <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="icons.html">Icons</a></li>
            <li><a href="faq.html">FAQ</a></li>
            <li><a href="pricing.html">Pricing Plans</a></li>
            <li><a href="login.html">Login</a></li>
            <li><a href="signup.html">Signup</a></li>
            <li><a href="error.html">404</a></li>
          </ul>
        </li> -->
      </ul>
    </div>
    <!-- /container -->
  </div>
  <!-- /subnavbar-inner -->
</div>
<!-- /subnavbar -->
<div class="main">
  <div class="main-inner">
    <div class="container">
      <div class="row">
        <div class="span6">
          <div class="widget widget-nopad">
            <div class="widget-header"> <i class="icon-list-alt"></i>
              <h3>Stats</h3>
            </div>
            <!-- widget-header -->
            <div class="widget-content">
              <div class="widget big-stats-container">
                <div class="widget-content">
                  <h6 class="bigstats">Search On all searches at one place </h6>
                  <div id="big_stats" class="cf">
                    <div class="stat"> <i class="icon-anchor"></i> <span class="value">851</span> </div>
                    <!-- .stat -->

                    <div class="stat"> <i class="icon-thumbs-up-alt"></i> <span class="value">423</span> </div>
                    <!-- .stat -->

                    <div class="stat"> <i class="icon-twitter-sign"></i> <span class="value">922</span> </div>
                    <!-- .stat -->

                    <div class="stat"> <i class="icon-bullhorn"></i> <span class="value">25%</span> </div>
                    <!-- .stat -->
                  </div>
                </div>
                <!-- /widget-content -->

              </div>
            </div>
          </div>
          <!-- /widget -->
          <div class="widget widget-nopad">
            <div class="widget-header"> <i class="icon-list-alt"></i>
              <h3>Map</h3>
            </div>
              <div id="map-canvas"></div>
            <!-- /widget-header
            <div class="widget-content">
              <div id='calendar'>
              </div>
            </div>
            <!-- /widget-content -->
          </div>
          <!-- /widget -->
          <div class="widget">
            <div class="widget-header"> <i class="icon-file"></i>
              <h3> Content</h3>
            </div>
            <!-- /widget-header -->
            
          </div>
          <!-- /widget -->
        </div>
        <!-- /span6 -->
        <div class="span6">
          <div class="widget">
            <div class="widget-header"> <i class="icon-bookmark"></i>
              <h3>Important Shortcuts</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
              <div class="shortcuts"> <a href="javascript:initialize('Food');" class="shortcut"><i class="shortcut-icon icon-list-alt"></i><span
                                        class="shortcut-label">Foods</span> </a><a href="javascript:initialize('NightLife');" class="shortcut"><i
                                            class="shortcut-icon icon-bookmark"></i><span class="shortcut-label">Nightlife</span> </a><a href="javascript:initialize('Coffee');" class="shortcut"><i class="shortcut-icon icon-signal"></i> <span class="shortcut-label">Coffee</span> </a><a href="jjavascript:initialize('Shopping');" class="shortcut"> <i class="shortcut-icon icon-comment"></i><span class="shortcut-label">Shopping</span> </a><a href="javascript:initialize('Sights');" class="shortcut"><i class="shortcut-icon icon-user"></i><span
                                                class="shortcut-label">Sights</span> </a><a href="javascript:initialize('Outdoor');" class="shortcut"><i
                                                    class="shortcut-icon icon-file"></i><span class="shortcut-label">Outdoor</span> </a><a href="javascript:initialize('Photos');" class="shortcut"><i class="shortcut-icon icon-picture"></i> <span class="shortcut-label">Photos</span> </a><a href="javascript:initialize('Services');" class="shortcut"> <i class="shortcut-icon icon-tag"></i><span class="shortcut-label">Local Services</span> </a> </div>
              <!-- /shortcuts -->
            </div>
            <!-- /widget-content -->
          </div>
          <!-- /widget -->
          <div class="widget">
           
          </div>
          <!-- /widget -->
          <div class="widget widget-nopad">
            <div class="widget-header"> <i class="icon-list-alt"></i>
              <h3> Search Results</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content" id="searchResults">
              
            </div>
            <!-- /widget-content -->
          </div>
          <!-- /widget -->
        </div>
        <!-- /span6 -->
      </div>
      <!-- /row -->
    </div>
    <!-- /container -->
  </div>
  <!-- /main-inner -->
</div>
<!-- /main -->
<!-- <div class="extra">
  <div class="extra-inner">
    <div class="container">
      <div class="row">
                    <div class="span3">
                        <h4>
                            About Free Admin Template</h4>
                        <ul>
                            <li><a href="javascript:;">EGrappler.com</a></li>
                            <li><a href="javascript:;">Web Development Resources</a></li>
                            <li><a href="javascript:;">Responsive HTML5 Portfolio Templates</a></li>
                            <li><a href="javascript:;">Free Resources and Scripts</a></li>
                        </ul>
                    </div>

                    <div class="span3">
                        <h4>
                            Support</h4>
                        <ul>
                            <li><a href="javascript:;">Frequently Asked Questions</a></li>
                            <li><a href="javascript:;">Ask a Question</a></li>
                            <li><a href="javascript:;">Video Tutorial</a></li>
                            <li><a href="javascript:;">Feedback</a></li>
                        </ul>
                    </div>

                    <div class="span3">
                        <h4>
                            Something Legal</h4>
                        <ul>
                            <li><a href="javascript:;">Read License</a></li>
                            <li><a href="javascript:;">Terms of Use</a></li>
                            <li><a href="javascript:;">Privacy Policy</a></li>
                        </ul>
                    </div>

                    <div class="span3">
                        <h4>
                            Open Source jQuery Plugins</h4>
                        <ul>
                            <li><a href="http://www.egrappler.com">Open Source jQuery Plugins</a></li>
                            <li><a href="http://www.egrappler.com;">HTML5 Responsive Tempaltes</a></li>
                            <li><a href="http://www.egrappler.com;">Free Contact Form Plugin</a></li>
                            <li><a href="http://www.egrappler.com;">Flat UI PSD</a></li>
                        </ul>
                    </div>

                </div>

    </div>

  </div>

</div> -->
<!-- /extra -->
<div class="footer">
  <div class="footer-inner">
    <div class="container">
      <div class="row">
        <div class="span12"> &copy;Search On team  <a href="http://www.egrappler.com/">Search On team </a>. </div>
        <!-- /span12 -->
      </div>
      <!-- /row -->
    </div>
    <!-- /container -->
  </div>
  <!-- /footer-inner -->
</div>
<!-- /footer -->
<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/excanvas.min.js"></script>
<script src="js/chart.min.js" type="text/javascript"></script>
<script src="js/bootstrap.js"></script>
<script language="javascript" type="text/javascript" src="js/full-calendar/fullcalendar.min.js"></script>

<script src="js/base.js"></script>

</body>
</html>
