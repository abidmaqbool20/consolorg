

<style type="text/css">
  .selfservice_dashboard_nav
  {
    padding-left: 15px  !important;
    padding-right: 15px !important;
  }
  .ml5
  {
    padding: 5px 10px;
    font-size: 16px;
  }
</style>

  <div class="mainpanel"> 
      <div class="row">
       <nav class="navbar" style="border-bottom: 2px solid #ccc;">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header"> 
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <i class="fa fa-bars"></i>
              </button> 
              <div id="Attendance_btn">  
                <?php 

                  $attendance_status = $this->session->userdata("attendance");
                  if($attendance_status == "present")
                  {
                    echo '<button onclick="stopTimer(this)" id="setTimeButton" class="btn btn-danger  ml5" style="margin: 6px 30px;">
                            <i class="fa fa-clock-o"></i> Check-Out
                          </button><span style="font-size: 15px;" id="timer">00:00:00</span>';
                  }
                  else
                  {
                    echo '<button onclick="employee_attendance(this)" id="setTimeButton" class="btn btn-success ml5" style="margin: 6px 30px;">
                            <i class="fa fa-clock-o"></i> Check-In
                          </button><span style="font-size: 15px;" id="timer">00:00:00</span>';
                  }

                ?> 
               
            </div>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right"> 
              <li><a href="#"><i class="fa fa-calendar"></i> Calendar</a></li>
              <li><a href="#"><i class="fa fa-calendar"></i> Apply Leave</a></li>
              <li><a href="#"><i class="fa fa-gear"></i> Settings</a></li>
            </ul>

          </div><!-- /.navbar-collapse -->
        </nav>
      </div>
      <div class="contentpanel">
        <div class="row">

          <div class="col-md-12 col-lg-12" style="padding: 0;"> 

            <div class="col-md-3">
              <div class="panel"> 
                <div class="panel-body" style="text-align: center;">
                  <h1 class="">PAID LEAVES</h1>
                  <h1 class="">6:6</h1>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="panel">
                <div class="panel-body" style="text-align: center;">
                  <h1 class="">UNPAID LEAVES</h1>
                  <h1 class="">6:6</h1>
                </div>
              </div>
            </div>
            
            <div class="col-md-3">
              <div class="panel">
                <div class="panel-body" style="text-align: center;">
                  <h1 class="">ATTENDANCE</h1>
                  <h1 class="">90%</h1>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="panel">
                <div class="panel-body" style="text-align: center;">
                  <h1 class="">PERFORMANCE</h1>
                  <h1 class="">50%</h1>
                </div>
              </div>
            </div>

          </div>  
        </div>  
      </div> 
  </div>



 <script type="text/javascript">
   
   function employee_attendance($this)
   {
      $($this).prop("disabled",true);
      $.post("<?= base_url("admin/employee_attendance") ?>",{},function(response){
        if(response == "signin")
        {
          start_timer();
          load_sidebar_view(this,'self_service_dashboard');
          $("#Attendance_btn").html('<button onclick="stopTimer(this)" id="setTimeButton" class="btn btn-danger ml5" style="margin: 6px 30px;">'+
                                      '<i class="fa fa-clock-o"></i> Check-Out'+
                                    '</button><span style="font-size: 15px;" id="timer">00:00:00</span>');
        }
        else
        {
          start_timer();
          load_sidebar_view(this,'self_service_dashboard');
          $("#Attendance_btn").html('<button onclick="employee_attendance(this)" id="setTimeButton" class="btn btn-success ml5" style="margin: 6px 30px;">'+
                                      '<i class="fa fa-clock-o"></i> Check-In'+
                                    '</button><span style="font-size: 15px;" id="">00:00:00</span>');
        }
      });
   }

  
  $start_time = "00:00:00";
 
  function set_timer()
  {
    $start_time = "00:00:00";
    jQuery.ajax({
                    type: "POST",
                    data: {},
                    url: "<?= base_url('admin/get_employee_logedintime') ?>",
                    success: function (data) 
                    {   
                        $start_time =   data; 
                    }, 
                    async: false,  
                });  

    return $start_time;
  }

  //clear_intervals();
  
  set_timer();
  start_timer($start_time);

  function start_timer($start_timer='')
  { 
      setInterval(function() 
      { 
        if($start_time != "00:00:00")
        {
          $start_time = new Date($start_time);
          $start_time = $start_time.getTime();
          newDate = new Date();
          newStamp = newDate.getTime();
          
          var diff = Math.round((newStamp-$start_time)/1000);
           
          var d = Math.floor(diff/(24*60*60));  //though I hope she won't be working for consecutive days :) 
          diff = diff-(d*24*60*60);
          var h = Math.floor(diff/(60*60));
          diff = diff-(h*60*60);
          var m = Math.floor(diff/(60));
          diff = diff-(m*60);
          var s = diff;
          
          hours = (h < 10 ? "0" : "") + h;
          minutes = (m < 10 ? "0" : "") + m;
          seconds = (s < 10 ? "0" : "") + s;

          $("#timer").html( hours + ":" + minutes+":" + seconds ); 
          
        }

      }, 1000);
  }


 
function stopTimer($this)
{
    var interval_id = window.setInterval("", 9999);   
    for (var i = 1; i < interval_id; i++)
    {     
      window.clearInterval(i); 
    } 

    clearInterval(timer);
    employee_attendance($this);
}

 
  // function clear_intervals()
  // {
  //   var interval_id = window.setInterval("", 9999); // Get a reference to the last 
  //   for (var i = 1; i < interval_id; i++)
  //       window.clearInterval(i);
  // }
 
 </script>