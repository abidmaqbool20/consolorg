
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
        <!-- <div class="row">
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
        <hr> -->
        
        <div class="row" >
          <!-- <div class="well warning mb10">
              <div class="row"> 
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3>Report: All Applicants </h3> 
                  </div>
              </div>
          </div> -->  
          <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12" id="report_applicants_yearly"></div>
          <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12" id="report_applicants_gender"></div> 
        </div> 
        <hr>
        <div class="row" >
          <!-- <div class="well warning mb10">
              <div class="row"> 
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3>Report: Applicants Hiring in Last Four Years </h3> 
                  </div>
              </div>
          </div>   -->
          <div class="col-md-3 col-lg-3 col-sm-3 col-xs-12" id="applicants_first_year"></div>
          <div class="col-md-3 col-lg-3 col-sm-3 col-xs-12" id="applicants_second_year"></div> 
          <div class="col-md-3 col-lg-3 col-sm-3 col-xs-12" id="applicants_third_year"></div> 
          <div class="col-md-3 col-lg-3 col-sm-3 col-xs-12" id="applicants_fourth_year"></div> 
        </div>
        <hr> 
        <div class="row" >
          <!-- <div class="well warning mb10">
              <div class="row"> 
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3>Report: City & Gender Wise Applicants </h3> 
                  </div>
              </div>
          </div>  --> 
          <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12" id="applicants_cities"></div> 
          <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12" id="applicants_cities_gender"></div> 
        </div> 
        <hr> 
      </div>
    </div>
 
 

   

<script type="text/javascript">
  $('.select2').select2(); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' }); 
  
 
report_applicants_yearly('report_applicants_yearly');
report_applicants_gender('report_applicants_gender'); 
applicants_first_year('applicants_first_year'); 
applicants_second_year('applicants_second_year'); 
applicants_third_year('applicants_third_year'); 
applicants_fourth_year('applicants_fourth_year'); 
applicants_cities('applicants_cities'); 
applicants_cities_gender('applicants_cities_gender'); 

 

function report_applicants_yearly($object)
{ 

    var chart = Highcharts.chart($object, {

      chart: {
          type: 'column'
      },

      title: {
          text: 'Jobposts and Applicants '
      },

      subtitle: {
          text: 'All Applicants Till Today '
      },

      legend: {
          align: 'right',
          verticalAlign: 'middle',
          layout: 'vertical'
      },

      xAxis: {
          categories: ['2014', '2015', '2016','2017','2018'],
          labels: {
              x: -10
          }
      },

      yAxis: {
          allowDecimals: false,
          title: {
              text: 'Applicants'
          }
      },

      series: [{
          name: 'Jobposts',
          data: [2, 3,2,1,4]
      }, {
          name: 'Applicants',
          data: [190,260,159,86,347]
      } ],

      responsive: {
          rules: [{
              condition: {
                  maxWidth: 500
              },
              chartOptions: {
                  legend: {
                      align: 'center',
                      verticalAlign: 'bottom',
                      layout: 'horizontal'
                  },
                  yAxis: {
                      labels: {
                          align: 'left',
                          x: 0,
                          y: -5
                      },
                      title: {
                          text: null
                      }
                  },
                  subtitle: {
                      text: null
                  },
                  credits: {
                      enabled: false
                  }
              }
          }]
      }
  }); 
}

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

function applicants_first_year($object)
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
        text: 'Applicnats 2015'
    },
    subtitle: {
        text: '2015'
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
            ['Applicants', 210],
            ['Shortlisted', 60],
            ['Interviewed', 40],
            ['Selected', 20],
            ['Hired', 10],
            ['Rejected', 10] 
        ]
    }]
  });
}

function applicants_second_year($object)
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
        text: 'Applicnats 2016'
    },
    subtitle: {
        text: '2016'
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
            ['Applicants', 210],
            ['Shortlisted', 60],
            ['Interviewed', 40],
            ['Selected', 20],
            ['Hired', 10],
            ['Rejected', 10] 
        ]
    }]
  });
}

function applicants_third_year($object)
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
        text: 'Applicnats 2017'
    },
    subtitle: {
        text: '2017'
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
            ['Applicants', 210],
            ['Shortlisted', 60],
            ['Interviewed', 40],
            ['Selected', 20],
            ['Hired', 10],
            ['Rejected', 10] 
        ]
    }]
  });
}
 
function applicants_fourth_year($object)
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
        text: 'Applicnats 2018'
    },
    subtitle: {
        text: '2018'
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
            ['Applicants', 210],
            ['Shortlisted', 60],
            ['Interviewed', 40],
            ['Selected', 20],
            ['Hired', 10],
            ['Rejected', 10] 
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

function applicants_cities_gender($object)
{  
  Highcharts.chart($object, {

      chart: {
          type: 'column'
      },

      title: {
          text: 'City Wise Applicants and Gender Dvision'
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
          name: 'Male',
          data: [75,120,59,44,63,20,26,19,14,43,22],
          stack: 'Male'
      },{
          name: 'Female',
          data: [60,105,40,30,55,5,15,35,25,20,13],
          stack: 'Female'
      }]
  });
     
}
 
</script>