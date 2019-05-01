
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
          <table class="table table-bordered table-primary nomargin">
            <thead>
              <th colspan="4" style="text-align: center;"><h3 style="color: #fff;">Late Coming Report</h3></th>
            </thead>
            <tbody>
              <tr>
                <th>Employee Name</th>
                <td> M Abid Maqbool</td>
                <th>Department</th>
                <td>IT</td>
              </tr>
              <tr>
                <th>Designation</th>
                <td> Web Developer</td>
                <th>Joining Date</th>
                <td>05 October, 2013</td>
              </tr>
            </tbody>
          </table>
        </div>
        <hr>
        
        <div class="row" >
          <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12" id="hours_report_2018"></div>
          <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12" id="hours_report_2017"></div>
          <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12" id="hours_report_2016"></div>
          <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12" id="hours_report_2015"></div> 
          <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12" id="hours_report_2014"></div> 
          <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12" id="hours_report_2013"></div> 
        </div> 
        <div class="row" > 
          <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12" id="days_report_2018"></div> 
          <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12" id="days_report_2017"></div> 
          <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12" id="days_report_2016"></div> 
          <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12" id="days_report_2015"></div> 
          <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12" id="days_report_2014"></div> 
          <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12" id="days_report_2013"></div> 
        </div>
        <hr> 
        <div class="row">
          <div class="col-md-12">
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
        <hr>
        <div class="row" id="employee_daily_reports">
          <div class="well warning mb10">
              <div class="row"> 
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3>Report: Employees Late Coming Time in 2018 </h3> 
                  </div>
              </div>
          </div>  
          <div class="row table-responsive" >
              <div class="col-md-12">
                <table class="table table-bordered table-primary nomargin">
                  <thead>
                    <tr>
                       <th class="text-center">Month</th>
                       <th class="text-center">Sun 1</th>
                       <th class="text-center">Mon 2</th>
                       <th class="text-center">Tue 3</th>
                       <th class="text-center">Wed 4</th>
                       <th class="text-center">Thu 5</th>
                       <th class="text-center">Fri 6</th>
                       <th class="text-center">Sat 7</th>
                       <th class="text-center">Sun 8</th>
                       <th class="text-center">Sun 9</th>
                       <th class="text-center">Mon 10</th>
                       <th class="text-center">Tue 11</th>
                       <th class="text-center">Wed 12</th>
                       <th class="text-center">Thu 13</th>
                       <th class="text-center">Fri 14</th>
                       <th class="text-center">Sat 15</th>
                       <th class="text-center">Sun 16</th>
                       <th class="text-center">Mon 17</th>
                       <th class="text-center">Tue 18</th>
                       <th class="text-center">Wed 19</th>
                       <th class="text-center">Thu 20</th>
                       <th class="text-center">Fri 21</th>
                       <th class="text-center">Sat 22</th>
                       <th class="text-center">Sun 23</th>
                       <th class="text-center">Mon 24</th>
                       <th class="text-center">Tue 25</th>
                       <th class="text-center">Wed 26</th>
                       <th class="text-center">Thu 27</th>
                       <th class="text-center">Fri 28</th>
                       <th class="text-center">Sat 29</th>
                       <th class="text-center">Sun 30</th>
                       <th class="text-center">Mon 31</th>  
                       <th class="text-center">Total Time</th>  
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                       <th class="employee_name">
                        <a href="javascript:; " onclick="open_modal_window(this,'report_employee_latetime_view','<?= 1; ?>')"> 
                          January 
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
                       <td class="text-center">120</td>
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
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td> 
                       <td class="time_total">1500</td> 
                     </tr>
                     <tr>
                       <th class="employee_name">February</th>
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
                       <td class="text-center">120</td>
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
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td> 
                       <td class="time_total">1500</td> 
                     </tr>
                     <tr>
                       <th class="employee_name">March</th>
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
                       <td class="text-center">120</td>
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
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="time_total">1500</td> 
                     </tr>
                     <tr>
                       <th class="employee_name">April</th>
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
                       <td class="text-center">120</td>
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
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="time_total">1500</td> 
                     </tr>
                     <tr>
                       <th class="employee_name">May</th>
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
                       <td class="text-center">120</td>
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
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="time_total">1500</td> 
                     </tr>
                     <tr>
                       <th class="employee_name">June</th>
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
                       <td class="text-center">120</td>
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
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="time_total">1500</td> 
                     </tr>
                     <tr>
                       <th class="employee_name">July</th>
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
                       <td class="text-center">120</td>
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
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="time_total">1500</td> 
                     </tr>
                     <tr>
                       <th class="employee_name">August</th>
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
                       <td class="text-center">120</td>
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
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="time_total">1500</td> 
                     </tr>
                     <tr>
                       <th class="employee_name">September</th>
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
                       <td class="text-center">120</td>
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
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="time_total">1500</td> 
                     </tr>
                     <tr>
                       <th class="employee_name">October</th>
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
                       <td class="text-center">120</td>
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
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="time_total">1500</td> 
                     </tr>
                     <tr>
                       <th class="employee_name">November</th>
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
                       <td class="text-center">120</td>
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
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="time_total">1500</td> 
                     </tr>
                     <tr>
                       <th class="employee_name">December</th>
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
                       <td class="text-center">120</td>
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
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="text-center">10</td>
                       <td class="time_total">1500</td> 
                     </tr>
                     
                     <tr>
                       <th class="time_total">Monthly Total Time</th>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td>
                       <td class="time_total">10</td> 
                       <td class="time_total">10</td> 
                       <td class="time_total">1500</td> 
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
  
 
days_report('days_report_2018');
days_report('days_report_2017');
days_report('days_report_2016');
days_report('days_report_2015');
days_report('days_report_2014');
days_report('days_report_2013');

hours_report('hours_report_2018'); 
hours_report('hours_report_2017'); 
hours_report('hours_report_2016'); 
hours_report('hours_report_2015'); 
hours_report('hours_report_2014'); 
hours_report('hours_report_2013'); 

function days_report($object)
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
        text: 'Total Late Coming Time in Every Month of 2018'
    },
    subtitle: {
        text: 'It represent the monthly late coming time'
    },
    plotOptions: {
        pie: {
            innerSize: 60,
            depth: 45
        }
    },
    series: [{
        name: 'Minutes',
        data: [
            ['January', 8],
            ['February', 3],
            ['March', 1],
            ['April', 6],
            ['May', 8],
            ['June', 4],
            ['July', 4],
            ['August', 1],
            ['September', 1],
            ['October', 1],
            ['November', 1],
            ['December', 1]
        ]
    }]
  });
}

function hours_report($object)
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
        text: 'Late Coming Time in 2018'
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
            ['Late Coming Time', 10],
            ['On Time', 50]
            
        ]
    }]
  });
}

 
</script>