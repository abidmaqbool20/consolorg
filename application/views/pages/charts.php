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
</style>

<div class="mainpanel">
  <div class="contentpanel" >
    <div class="panel">
      <div class="panel-body">
        <div class="row">
          <div class="col-md-12">
            <div class="col-lg-7 col-md-6 col-sm-6">
              <h3><i class="fa fa-file-o"></i> Attendance Report</h3>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-3"> </div>
            <div class="col-lg-2 col-md-2 col-sm-2"> </div>
            <div class="col-lg-1 col-md-2 col-sm-2"> </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <form method="post" action="<?= base_url("admin/get_attendance_reports"); ?>" onsubmit="return get_attendance_reports(this)">
            
            <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
              <select class="form-control select2 required" name="Employee_Id" id="Employee_Id" >
                <option value="0">All Employees</option>
                <?php 

		              	$this->db->order_by("First_Name","ASC");
		                $this->db->select("First_Name,Last_Name,Id","asc");
		                $employees = $this->db->get_where('employees',array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Employee_Status"=>"Active"));
		                if($employees->num_rows() > 0)
		                {
		                  foreach ($employees->result() as $key => $value)
		                  {
		                  	if($key == 0){ $first_employee_id = $value->Id; }
		                    echo '<option value="'.$value->Id.'">'.$value->First_Name.' '.$value->Last_Name.'</option>';
		                  }
		                }

	              ?>
              </select>
            </div>
            <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Get Attendance Reports</button>
            </div>
            <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12"> </div>
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"></div>
          </form>
        </div>
      </div>
    </div>
    <div class="panel">
      <div class="panel-body">
        <div class="row" id="employee_daily_reports">
          <div class="well warning mb10">
              <div class="row"> 
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3>Saturday 14 November, 2018</h3> 
                  </div>
              </div>
          </div>  
          <div class="row" style="background: aliceblue;padding:3px 20px 10px 0px;">
            <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12 chart_area" id="hours_report"></div>
            <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12 chart_area" id="days_report"></div>
            <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12 chart_area" id="late_days_report"></div> 
          </div> 
             
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
late_days_report();

function days_report()
{ 
  Highcharts.chart('days_report', {
    chart: {
        type: 'pie',
        options3d: {
            enabled: true,
            alpha: 45
        }
    },
    title: {
        text: 'Contents of Highsoft\'s weekly fruit delivery'
    },
    subtitle: {
        text: '3D donut in Highcharts'
    },
    plotOptions: {
        pie: {
            innerSize: 100,
            depth: 45
        }
    },
    series: [{
        name: 'Delivered amount',
        data: [
            ['Bananas', 8],
            ['Kiwi', 3],
            ['Mixed nuts', 1],
            ['Oranges', 6],
            ['Apples', 8],
            ['Pears', 4],
            ['Clementines', 4],
            ['Reddish (bag)', 1],
            ['Grapes (bunch)', 1]
        ]
    }]
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
        text: 'Contents of Highsoft\'s weekly fruit delivery'
    },
    subtitle: {
        text: '3D donut in Highcharts'
    },
    plotOptions: {
        pie: {
            innerSize: 100,
            depth: 45
        }
    },
    series: [{
        name: 'Delivered amount',
        data: [
            ['Bananas', 8],
            ['Kiwi', 3],
            ['Mixed nuts', 1],
            ['Oranges', 6],
            ['Apples', 8],
            ['Pears', 4],
            ['Clementines', 4],
            ['Reddish (bag)', 1],
            ['Grapes (bunch)', 1]
        ]
    }]
  });
}

function late_days_report()
{ 
  Highcharts.chart('late_days_report', {
    chart: {
        type: 'pie',
        options3d: {
            enabled: true,
            alpha: 45
        }
    },
    title: {
        text: 'Contents of Highsoft\'s weekly fruit delivery'
    },
    subtitle: {
        text: '3D donut in Highcharts'
    },
    plotOptions: {
        pie: {
            innerSize: 100,
            depth: 45
        }
    },
    series: [{
        name: 'Delivered amount',
        data: [
            ['Bananas', 8],
            ['Kiwi', 3],
            ['Mixed nuts', 1],
            ['Oranges', 6],
            ['Apples', 8],
            ['Pears', 4],
            ['Clementines', 4],
            ['Reddish (bag)', 1],
            ['Grapes (bunch)', 1]
        ]
    }]
  });
}
</script>