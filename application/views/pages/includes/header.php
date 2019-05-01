<!DOCTYPE html>
<html lang="en">

 <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content=""> 

  <title>CONSOL-HR</title>

  <link rel="stylesheet" href="<?= ASSETSPATH; ?>lib/Hover/hover.css"> 
  <link rel="stylesheet" href="<?= ASSETSPATH; ?>lib/weather-icons/css/weather-icons.css">
  <link rel="stylesheet" href="<?= ASSETSPATH; ?>lib/ionicons/css/ionicons.css">
  <link rel="stylesheet" href="<?= ASSETSPATH; ?>lib/jquery-toggles/toggles-full.css">
  <link rel="stylesheet" href="<?= ASSETSPATH; ?>lib/morrisjs/morris.css">
  <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />

 
   <link rel="stylesheet" href="<?= ASSETSPATH; ?>/lib/timepicker/jquery.timepicker.css">
  <link rel="stylesheet" href="<?= ASSETSPATH; ?>/lib/bootstrapcolorpicker/css/bootstrap-colorpicker.css">
  
  <link rel="stylesheet" href="<?= ASSETSPATH; ?>/lib/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css">
  <link rel="stylesheet" href="<?= ASSETSPATH; ?>/lib/select2/select2.css">


  <link rel="stylesheet" href="<?= ASSETSPATH; ?>/css/jquery.orgchart.css">


  <link rel="stylesheet" href="<?= ASSETSPATH; ?>/lib/summernote/summernote.css">

  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/css/bootstrap-material-design.min.css"/> -->
  <link rel="stylesheet" href="<?= ASSETSPATH; ?>/css/date-timepicker.css">

  <link rel="stylesheet" href="<?= ASSETSPATH; ?>css/quirk.css">
    <link rel="stylesheet" href="<?= ASSETSPATH; ?>/css/fullcalendar.css">
  <link rel="stylesheet" href="<?= ASSETSPATH; ?>/css/fullcalendar.print.css"> 

  <script src="<?= ASSETSPATH; ?>lib/jquery/jquery.js"></script>
  <script src="<?= ASSETSPATH; ?>lib/modernizr/modernizr.js"></script>
  <script src="<?= ASSETSPATH; ?>panel/js/view-loader.js"></script>
  <script src="<?= ASSETSPATH; ?>panel/js/actions.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/js/ripples.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/js/material.min.js"></script>
  <script type="text/javascript" src="http://momentjs.com/downloads/moment-with-locales.min.js"></script>
  <script src="<?= ASSETSPATH; ?>/js/datetime-picker.js"></script>

  <script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>

  <script src="http://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.3.1/sweetalert2.all.js"></script> 
  <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.3.1/sweetalert2.css" /> 
  <script src="http://cdn.ckeditor.com/4.8.0/standard/ckeditor.js"></script> 

  <script src="<?= ASSETSPATH; ?>/js/yearpicker.js"></script> 
  <script src="<?= ASSETSPATH; ?>/js/fullcalendar.js"></script>   

  <script src="http://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/highcharts-3d.js"></script>
  <script src="http://code.highcharts.com/modules/exporting.js"></script>
  <script src="http://code.highcharts.com/modules/export-data.js"></script>
  <script src="https://code.highcharts.com/modules/funnel.js"></script>

  <script type="text/javascript">
    Highcharts.setOptions({
      colors: Highcharts.map(Highcharts.getOptions().colors, function (color) {
          return {
              radialGradient: {
                  cx: 0.5,
                  cy: 0.3,
                  r: 0.7
              },
              stops: [
                  [0, color],
                  [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
              ]
          };
      })
    });
  </script>

</head>

<body>
<?php 

    if($this->user_data['Photo'] != "")
    {
      $profile_picture =  ASSETSPATH."panel/userassets/employees/".$this->user_data['Id']."/".$this->user_data['Photo'];
    }
    else
    {
      $profile_picture =  ASSETSPATH."images/default-user.png";
    }

 ?>

<div class="my-overlay" id="loader" style="display: none">
  <div class="my-overlay-text"><i class="fa fa-refresh fa-spin"></i><br>
    <div id="loading_progress" style=" text-align: center;  margin-left: -20px; "></div>
  </div>
</div>

<header>
  <div class="headerpanel">

    <div class="logopanel">
      <h2 style="font-size: 18px;"><a href="javascript:;"><img src="<?= ASSETSPATH."images/logo-icon.png" ?>" class="logo-icon" style="width: 35px;" alt="logo icon"> CONSOL-HR</a></h2>
    </div><!-- logopanel -->

    <div class="headerbar">

      <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>

      <div class="searchpanel">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Search for...">
          <span class="input-group-btn">
            <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
          </span>
        </div><!-- input-group -->
      </div>

      <div class="header-right">
        <ul class="headermenu">
          <li>
            <div id="noticePanel" class="btn-group">
              <button class="btn btn-notice alert-notice" data-toggle="dropdown">
                <i class="fa fa-globe"></i>
              </button>
              <div id="noticeDropdown" class="dropdown-menu dm-notice pull-right">
                <div role="tabpanel">
                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs nav-justified" role="tablist">
                    <li class="active"><a data-target="#notification" data-toggle="tab">Notifications (2)</a></li>
                    <li><a data-target="#reminders" data-toggle="tab">Reminders (4)</a></li>
                  </ul>

                  <!-- Tab panes -->
                  <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="notification">
                      <ul class="list-group notice-list">
                        <li class="list-group-item unread">
                          <div class="row">
                            <div class="col-xs-2">
                              <i class="fa fa-envelope"></i>
                            </div>
                            <div class="col-xs-10">
                              <h5><a href="<?= ASSETSPATH; ?>#">New message from Weno Carasbong</a></h5>
                              <small>June 20, 2015</small>
                              <span>Soluta nobis est eligendi optio cumque...</span>
                            </div>
                          </div>
                        </li>
                        <li class="list-group-item unread">
                          <div class="row">
                            <div class="col-xs-2">
                              <i class="fa fa-user"></i>
                            </div>
                            <div class="col-xs-10">
                              <h5><a href="<?= ASSETSPATH; ?>#">Renov Leonga is now following you!</a></h5>
                              <small>June 18, 2015</small>
                            </div>
                          </div>
                        </li>
                        <li class="list-group-item">
                          <div class="row">
                            <div class="col-xs-2">
                              <i class="fa fa-user"></i>
                            </div>
                            <div class="col-xs-10">
                              <h5><a href="<?= ASSETSPATH; ?>#">Zaham Sindil is now following you!</a></h5>
                              <small>June 17, 2015</small>
                            </div>
                          </div>
                        </li>
                        <li class="list-group-item">
                          <div class="row">
                            <div class="col-xs-2">
                              <i class="fa fa-thumbs-up"></i>
                            </div>
                            <div class="col-xs-10">
                              <h5><a href="<?= ASSETSPATH; ?>#">Rey Reslaba likes your post!</a></h5>
                              <small>June 16, 2015</small>
                              <span>HTML5 For Beginners Chapter 1</span>
                            </div>
                          </div>
                        </li>
                        <li class="list-group-item">
                          <div class="row">
                            <div class="col-xs-2">
                              <i class="fa fa-comment"></i>
                            </div>
                            <div class="col-xs-10">
                              <h5><a href="<?= ASSETSPATH; ?>#">Socrates commented on your post!</a></h5>
                              <small>June 16, 2015</small>
                              <span>Temporibus autem et aut officiis debitis...</span>
                            </div>
                          </div>
                        </li>
                      </ul>
                      <a class="btn-more" href="<?= ASSETSPATH; ?>#">View More Notifications <i class="fa fa-long-arrow-right"></i></a>
                    </div><!-- tab-pane -->

                    <div role="tabpanel" class="tab-pane" id="reminders">
                      <h1 id="todayDay" class="today-day">...</h1>
                      <h3 id="todayDate" class="today-date">...</h3>

                      <h5 class="today-weather"><i class="wi wi-hail"></i> Cloudy 77 Degree</h5>
                      <p>Thunderstorm in the area this afternoon through this evening</p>

                      <h4 class="panel-title">Upcoming Events</h4>
                      <ul class="list-group">
                        <li class="list-group-item">
                          <div class="row">
                            <div class="col-xs-2">
                              <h4>20</h4>
                              <p>Aug</p>
                            </div>
                            <div class="col-xs-10">
                              <h5><a href="<?= ASSETSPATH; ?>#">HTML5/CSS3 Live! United States</a></h5>
                              <small>San Francisco, CA</small>
                            </div>
                          </div>
                        </li>
                        <li class="list-group-item">
                          <div class="row">
                            <div class="col-xs-2">
                              <h4>05</h4>
                              <p>Sep</p>
                            </div>
                            <div class="col-xs-10">
                              <h5><a href="<?= ASSETSPATH; ?>#">Web Technology Summit</a></h5>
                              <small>Sydney, Australia</small>
                            </div>
                          </div>
                        </li>
                        <li class="list-group-item">
                          <div class="row">
                            <div class="col-xs-2">
                              <h4>25</h4>
                              <p>Sep</p>
                            </div>
                            <div class="col-xs-10">
                              <h5><a href="<?= ASSETSPATH; ?>#">HTML5 Developer Conference 2015</a></h5>
                              <small>Los Angeles CA United States</small>
                            </div>
                          </div>
                        </li>
                        <li class="list-group-item">
                          <div class="row">
                            <div class="col-xs-2">
                              <h4>10</h4>
                              <p>Oct</p>
                            </div>
                            <div class="col-xs-10">
                              <h5><a href="<?= ASSETSPATH; ?>#">AngularJS Conference 2015</a></h5>
                              <small>Silicon Valley CA, United States</small>
                            </div>
                          </div>
                        </li>
                      </ul>
                      <a class="btn-more" href="<?= ASSETSPATH; ?>#">View More Events <i class="fa fa-long-arrow-right"></i></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </li>
          <li>
            <div class="btn-group">
              <button type="button" class="btn btn-logged" data-toggle="dropdown">
                <img src="<?php echo $profile_picture; ?>" alt="" />
                <?php if(isset($this->user_data['First_Name'])){ echo $this->user_data['First_Name']." ".$this->user_data['Last_Name']; } ?> 
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu pull-right"> 
                <li><a href="<?= ASSETSPATH; ?>#"><i class="glyphicon glyphicon-cog"></i> Account Settings</a></li> 
                <li><a href="<?= base_url("logout"); ?>"><i class="glyphicon glyphicon-log-out"></i> Log Out</a></li>
              </ul>
            </div>
          </li>
          <li>
            <button id="chatview" class="btn btn-chat alert-notice">
              <span class="badge-alert"></span>
              <i class="fa fa-comments-o"></i>
            </button>
          </li>
        </ul>
      </div><!-- header-right -->
    </div><!-- headerbar -->
  </div><!-- header-->
</header>


<div id="mymodal" class="overlay">
  <a href="javascript:void(0)" class="closebtn" onclick="closemodal()">&times;</a>
  <div class="overlay-content" id="modalbody">
     
  </div>
</div>

 


<section>

  <div class="leftpanel">
    <div class="leftpanelinner">

       
      <div class="media leftpanel-profile">
        <div class="media-left">
          <a href="javascript:;">
            <img src="<?php echo $profile_picture; ?>" alt="" class="media-object img-circle">
          </a>
        </div>
        <div class="media-body">
          <h4 class="media-heading">
            <?php if(isset($this->user_data['First_Name'])){ echo $this->user_data['First_Name']." ".$this->user_data['Last_Name']; } ?> 
            <a data-toggle="collapse" data-target="#loguserinfo" class="pull-right"><i class="fa fa-angle-down"></i></a>
          </h4>
          <span><?php if(isset($this->user_data['Phone_Number'])){ echo $this->user_data['Phone_Number']; }else{ echo $this->user_data['Mobile_Number_1'];  } ?> </span>
        </div>
      </div> 

      <div class="leftpanel-userinfo collapse" id="loguserinfo">
        <h5 class="sidebar-title">Address</h5>
        <address>
         <?php if(isset($this->user_data['Address'])){ echo $this->user_data['Address'] ; }else{ echo "Not saved!"; } ?> 
        </address>
        <h5 class="sidebar-title">Contact</h5>
        <ul class="list-group">
          <li class="list-group-item">
            <label class="pull-left">Email</label>
            <span class="pull-right"><?php if(isset($this->user_data['Email'])){ echo $this->user_data['Email'] ; }else{ echo "Not saved!"; } ?> </span>
          </li>
          <li class="list-group-item">
            <label class="pull-left">Home</label>
            <span class="pull-right"><?php if(isset($this->user_data['Phone_Number'])){ echo $this->user_data['Phone_Number'] ; }else{ echo "Not saved!"; } ?> </span>
          </li>
          <li class="list-group-item">
            <label class="pull-left">Mobile</label>
            <span class="pull-right"><?php if(isset($this->user_data['Mobile_Number_1'])){ echo $this->user_data['Mobile_Number_1'] ; }else{ echo "Not saved!"; } ?> </span>
          </li>
          <!-- <li class="list-group-item">
            <label class="pull-left">Social</label>
            <div class="social-icons pull-right">
              <a href="#"><i class="fa fa-facebook-official"></i></a>
              <a href="#"><i class="fa fa-twitter"></i></a>
              <a href="#"><i class="fa fa-pinterest"></i></a>
            </div>
          </li> -->
        </ul>
      </div> 

  
      <div class="tab-content">
 
       <?php $this->load->view("pages/includes/sidebar"); ?>

        

      </div> 

    </div> 
  </div> 
