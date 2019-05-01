
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
                <option value="0">Select Job Post</option>
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
          <!-- <div class="well warning mb10">
              <div class="row"> 
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3>Report: All Applicants </h3> 
                  </div>
              </div>
          </div>   -->
          <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12" id="report_applicants_jobpost"></div>
          <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12" id="report_applicants_gender"></div> 
        </div> 
        <hr>
         
        <div class="row" >
         <!--  <div class="well warning mb10">
              <div class="row"> 
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3>Report: City & Gender Wise Applicants </h3> 
                  </div>
              </div>
          </div>   -->
          <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12" id="applicants_cities"></div> 
          <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12" id="applicants_universities"></div> 
        </div> 
        <hr> 
      </div>
    </div>
 
 

   

<script type="text/javascript">
  $('.select2').select2(); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' }); 
  
 
report_applicants_jobpost('report_applicants_jobpost');
report_applicants_gender('report_applicants_gender');  
applicants_cities('applicants_cities'); 
applicants_universities('applicants_universities'); 

function report_applicants_gender($object)
{ 
  Highcharts.chart($object, {
    chart: {
        type: 'pie',
        options3d: {
            enabled: true,
            alpha: 45
        }
    },
    title: {
        text: 'Gender Wise Applicants'
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
        name: 'Gender',
        data: [
            ['Female', 210],
            ['Male', 550]
            
        ]
    }]
  });
}
 
function report_applicants_jobpost($object)
{  
  Highcharts.chart($object, {
      chart: {
          type: 'funnel'
      },
      title: {
          text: 'Total Applicants For this Jobpost'
      },
      plotOptions: {
          series: {
              dataLabels: {
                  enabled: true,
                  format: '<b>{point.name}</b> ({point.y:,.0f})',
                  color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                  softConnector: true
              },
              center: ['40%', '50%'],
              neckWidth: '30%',
              neckHeight: '25%',
              width: '80%'
          }
      },
      legend: {
          enabled: false
      },
      series: [{
          name: 'All Applicants',
          data: [
              ['Total', 15654],
              ['Shortlisted', 4064],
              ['Interviewed', 1987],
              ['Selected', 976],
              ['Rejected', 152],
              ['Hired', 820]
              
          ]
      }]
  });
}

 

function applicants_cities($object)
{  
  Highcharts.chart($object, {

      chart: {
          type: 'column'
      },

      title: {
          text: 'City Wise Applicants'
      },

      xAxis: {
          categories: ['Lahore', 'Sahiwal', 'Islamabad','Rawalpindi','Gujranwala','Faisalabad','Vehari','Multan','Karachi','Peshawar','Sargodha']
      },

      yAxis: {
          allowDecimals: false,
          min: 0,
          title: {
              text: 'Number of Applicants'
          }
      },

      tooltip: {
          formatter: function () {
              return '<b>' + this.x + '</b><br/>' +
                  this.series.name + ': ' + this.y + '<br/>' +
                  'Total: ' + this.point.stackTotal;
          }
      },

      plotOptions: {
          column: {
              stacking: 'normal'
          }
      },

      series: [ {
          name: 'Applicants',
          data: [75,120,59,44,63,20,26,19,14,43,22],
          stack: 'Applicants'
      }]
  });
     
}

function applicants_universities($object)
{  
  
  Highcharts.chart($object, {

      chart: {
          type: 'column'
      },

      title: {
          text: 'University Wise Applicants'
      },

      xAxis: {
          categories: ['UET Lahore', 'UET Peshawar', 'UET Texila','NUST','FAST','UOL','Sir Syed University','UCP','BZU' ]
      },

      yAxis: {
          allowDecimals: false,
          min: 0,
          title: {
              text: 'Number of Applicants'
          }
      },

      tooltip: {
          formatter: function () {
              return '<b>' + this.x + '</b><br/>' +
                  this.series.name + ': ' + this.y + '<br/>' +
                  'Total: ' + this.point.stackTotal;
          }
      },

      plotOptions: {
          column: {
              stacking: 'normal'
          }
      },

      series: [ {
          name: 'Applicants',
          data: [75,120,59,44,63,20,26,19,14],
          stack: 'Applicants'
      }]
  });
     
}
 
</script>