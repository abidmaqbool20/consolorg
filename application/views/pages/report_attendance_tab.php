
<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Attendance Report","Deleted"=>0,"Status"=>1));
  if($module_data->num_rows() > 0)
  {
    $module_info = $module_data->result_array();
    $module_id = $module_info[0]['Id'];
  }

 ?>

<style type="text/css">
  .chart_area
  {
    margin-top: 10px !important;
    padding-right: 0px !important;
  }

  .employee_name
  {
    text-align: left;
    background-color: #efc898; 
  }
  .time_total
  {
    text-align: center;
    background-color: #ceef98;/*#b9bf0f*/
  }
</style>
 
    <div class="panel">
      <div class="panel-body">
        <div class="row">
          <form method="post" action="<?= base_url("admin/get_attendance_reports"); ?>" onsubmit="return get_attendance_reports(this)">
            
            <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
              <select class="form-control select2 required" name="Employee_Id" id="Employee_Id" >
                <option value="0">Select Year</option>
                <option value="2018">2018</option>
                <option value="2017">2017</option>
                <option value="2016">2016</option>
                <option value="2015">2015</option> 
                <option value="2014">2014</option> 
              </select>
            </div>
            <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Get Attendance Reports</button>
            </div>
            <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12"> </div>
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"></div>
          </form>
        </div>
        <hr>
        
        <div class="row" >
          <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12" id="hours_report"></div>
          <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12" id="days_report"></div> 
        </div> 
         <hr>
        <div class="row" id="employee_daily_reports">
          <div class="well warning mb10">
              <div class="row"> 
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3>Report: Employees Attendance in 2018 </h3> 
                  </div>
              </div>
          </div>  
          <div class="row table-responsive">
              <div class="col-md-12">
                <table class="table table-bordered table-primary nomargin">
                  <thead>
                    <tr>
                      <th class="text-center">Employee Name</th>
                      <th class="text-center">January</th>
                      <th class="text-center">February</th>
                      <th class="text-center">March</th>
                      <th class="text-center">April</th>
                      <th class="text-center">May</th>
                      <th class="text-center">Jun</th>
                      <th class="text-center">July</th>
                      <th class="text-center">August</th>
                      <th class="text-center">September</th>
                      <th class="text-center">October</th>
                      <th class="text-center">November</th>
                      <th class="text-center">December</th> 
                      <th class="text-center">Total Time</th> 
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th class="employee_name">
                        <a href="javascript:;" onclick="open_modal_window(this,'report_employee_attendance_view','<?= 1; ?>')"> 
                          Abid 
                        </a>
                      </th>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="time_total">120</td>
                     </tr>
                     <tr>
                       <th class="employee_name">Abid</th>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="time_total">120</td>
                     </tr>
                     <tr>
                       <th class="employee_name">Abid</th>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="time_total">120</td>
                     </tr>
                     <tr>
                       <th class="employee_name">Abid</th>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="time_total">120</td>
                     </tr>
                     <tr>
                       <th class="employee_name">Abid</th>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="time_total">120</td>
                     </tr>
                     <tr>
                       <th class="employee_name">Abid</th>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="time_total">120</td>
                     </tr>
                     <tr>
                       <th class="employee_name">Abid</th>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="time_total">120</td>
                     </tr>
                     <tr>
                       <th class="employee_name">Abid</th>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="time_total">120</td>
                     </tr>
                     <tr>
                       <th class="employee_name">Abid</th>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="time_total">120</td>
                     </tr>
                     <tr>
                       <th class="employee_name">Abid</th>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="time_total">120</td>
                     </tr>
                     <tr>
                       <th class="employee_name">Abid</th>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="time_total">120</td>
                     </tr>
                     
                     <tr>
                       <th class="time_total">Monthly Total Time</th>
                       <td class="time_total">800</td>
                       <td class="time_total">800</td>
                       <td class="time_total">800</td>
                       <td class="time_total">800</td>
                       <td class="time_total">800</td>
                       <td class="time_total">800</td>
                       <td class="time_total">800</td>
                       <td class="time_total">800</td>
                       <td class="time_total">800</td>
                       <td class="time_total">800</td>
                       <td class="time_total">800</td>
                       <td class="time_total">800</td>
                       <td class="time_total" style="background-color: aqua;">800</td>
                     </tr>
                      
                  <tbody>

                </table>
            </div>
          </div> 
             
        </div>
      </div>
    </div>
 
 

   

<script type="text/javascript">
  $('.select2').select2(); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' }); 
  
 
days_report();
hours_report(); 

 

function days_report()
{ 

      var chart = Highcharts.chart('days_report', {

          title: {
              text: 'Attendance For Every Month 2018'
          },

          subtitle: {
              text: 'Attendance'
          },

          xAxis: {
              categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
          },

          series: [{
              name: 'Leaves',
              type: 'column',
              colorByPoint: true,
              data: [1,0,0,2,0,1,1,3,0,3,0,0],
              showInLegend: false
          }]

      });

      chart.update({
            chart: {
                inverted: false,
                polar: false
            },
            subtitle: {
                text: 'Attendance'
            }
      });
}

function hours_report()
{ 
  Highcharts.chart('hours_report', {
    chart: {
        type: 'pie',
        options3d: {
            enabled: true,
            alpha: 45
        }
    },
    title: {
        text: 'Attendace in 2018'
    },
    subtitle: {
        text: ''
    },
    plotOptions: {
        pie: {
            innerSize: 100,
            depth: 45
        }
    },
    series: [{
        name: 'Minutes',
        data: [
            ['Leaves', 10],
            ['Presence', 50]
            
        ]
    }]
  });
}

 
</script>