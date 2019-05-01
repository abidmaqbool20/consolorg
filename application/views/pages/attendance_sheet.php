<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Attendance Sheet","Deleted"=>0,"Status"=>1));
  if($module_data->num_rows() > 0)
  {
    $module_info = $module_data->result_array();
    $module_id = $module_info[0]['Id'];
  }
 
 ?>

<style type="text/css">
    .employee_name
    {
        min-width: 200px !important;
    }
    .emp-tabe-name
    {
       margin-left: 35px !important;
       float: unset !important;
    }
    .attendance_date
    {
        min-width: 20px;
        max-width: 40px;
        padding: 5px !important;
    }
    .attendance-present
    {
      background-color: green;
      color: #fff;
    }
    .attendance-absent
    {
      background-color: #d9534f;
      color: #fff !important;
    }
</style>
<div class="row" id="employee_daily_reports">
         
          <div class="col-md-12">
            <form method="post" action="<?= base_url("admin/get_attendance_reports"); ?>" onsubmit="return get_attendance_reports(this)">
              <?php  $month = date("m"); ?>  
              <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
                <select class="form-control select2  required" name="Month" id="Month" > 
                  <option value="01" <?php if($month == 01){ echo 'selected="selected"'; } ?>>January</option>
                  <option value="02" <?php if($month == 02){ echo 'selected="selected"'; } ?>>February</option>
                  <option value="03" <?php if($month == 03){ echo 'selected="selected"'; } ?>>March</option>
                  <option value="04" <?php if($month == 04){ echo 'selected="selected"'; } ?>>April</option>
                  <option value="05" <?php if($month == 05){ echo 'selected="selected"'; } ?>>May</option>
                  <option value="06" <?php if($month == 06){ echo 'selected="selected"'; } ?>>June</option>
                  <option value="07" <?php if($month == 07){ echo 'selected="selected"'; } ?>>July</option>
                  <option value="08" <?php if($month == 08){ echo 'selected="selected"'; } ?>>August</option>
                  <option value="09" <?php if($month == 09){ echo 'selected="selected"'; } ?>>September</option>
                  <option value="10" <?php if($month == 10){ echo 'selected="selected"'; } ?>>October</option>
                  <option value="11" <?php if($month == 11){ echo 'selected="selected"'; } ?>>November</option>
                  <option value="12" <?php if($month == 12){ echo 'selected="selected"'; } ?>>December</option> 
                </select>
              </div>
              <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
                <select class="form-control select2  required" name="Year" id="Year" > 
                  <?php 
                    $year = date("Y");
                    echo '<option value="'.$year.'">'.$year.'</option>';
                    for ($i=0; $i < 5; $i++) 
                    { 
                        $year = $year - 1;
                        echo '<option value="'.$year.'">'.$year.'</option>';
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
        <div class="row table-responsive" > 
            <div class="col-md-12">
              <table class="table table-bordered table-primary nomargin">
                <thead>
                  <tr>
                    <?php 

                        $date = date("Y-m-d");
                        $year = date("Y");
                        $month_name = date("F");
                        $month = date("m");
                        echo '<th class="text-center">'.$month_name.'</th>';
                        $month_end_day = date("t",strtotime($date));
                        
                        for ($i=1; $i <= $month_end_day; $i++) 
                        { 
                            $today = $year."-".$month."-".$i;
                            echo '<th class="text-center attendance_date">'.date('D d',strtotime($today)).'</th>'; 
                        }

                    ?> 
                    <th class="text-center">Total Abscnets</th>  
                    <th class="text-center">Total Presents</th>  
                  </tr>
                </thead>
                <tbody>
                    <?php 

                        $this->db->select("employees.First_Name,employees.Last_Name,employees.Id,employees.Photo,shifts.Name as Shift_Name,shifts.Start_Time as Shift_Start_Time,departments.Name as Department_Name,locations.Name as Location_Name");
                        $employees = $this->db->where( array("employees.Deleted"=>0,"employees.Status"=>1,"employees.Org_Id"=>$this->org_id,"employees.Employee_Status"=>"Active"));
                        $this->db->from("employees"); 
                        $this->db->join("employee_work_record","employee_work_record.Employee_Id = employees.Id","left");
                        $this->db->join("departments","departments.Id = employee_work_record.Department_Id","left");
                        $this->db->join("shift_employees","shift_employees.Employee_Id = employees.Id","left");
                        $this->db->join("shifts","shifts.Id = shift_employees.Shift_Id","left");
                        $this->db->join("locations","locations.Id = employees.Location_Id","left");  
                        $this->db->group_by("employees.Id");
                        $employees = $this->db->get();

                        if($employees->num_rows() > 0)
                        {
                            foreach ($employees->result() as $key => $value) 
                            {
                                $department_name = "Not Saved!";
                                if($value->Department_Name != ""){ $department_name = $value->Department_Name; }
                                $signin_time = $signout_time = "0000-00-00 00:00:00";
                               
                                $total_abscnets = 0;
                                $total_presents = 0; 

                                $src =  "assets/images/default-user.png";
                                if($value->Photo && $value->Photo != "")
                                {
                                    $src =  "assets/panel/userassets/employees/".$value->Id."/".$value->Photo;
                                    if(!file_exists($src))
                                    {
                                       $src =  "assets/images/default-user.png";
                                    } 
                                }

                                echo '<tr>
                                        <th class="employee_name" >
                                            <div class="table-profile-img">
                                                <a href="javascript:;"  onclick="open_modal_window(this,\'report_employee_attendance_view\',1)">
                                                  <img src="'.$src.'" class="pro-img">
                                                </a> 
                                            </div> 
                                            <div class="emp-tabe-name">
                                                  '.$value->First_Name.' '.$value->Last_Name.'<span class="emp-table-email">'.$department_name.'</span>  
                                            </div> 
                                        </th>
                                ';

                                for ($i=1; $i <= $month_end_day; $i++) 
                                { 
                                    $today = $year."-".$month."-".$i; 
                                    $like_date = $year."-".$month."-";
                                    $this->db->like("Signin",$like_date,"before");
                                    $attendance = $this->db->get_where("attendance",array("Deleted"=>0,"Status"=>1,"Employee_Id"=>$value->Id));
                                    
                                    $attendance_status = "";
                                    $class = "";

                                    if($attendance->num_rows() > 0)
                                    {
                                        $attendance_data = $attendance->result_array();
                                        $signin_time = $attendance_data[0]['Signin'];
                                        $signout_time = $attendance_data[0]['Signout'];
                                        $Late_Reason = $attendance_data[0]['Late_Reason']; 
                                        $attendance_status = "P";
                                        $total_presents = $total_presents + 1;

                                        if(strtotime($value->Shift_Start_Time) >= strtotime($signin_time))
                                        {
                                            $class = "attendance-present";  
                                        }
                                        else
                                        {
                                            $class = "attendance-late"; 
                                        } 

                                    }
                                    else
                                    {
                                        $total_abscnets = $total_abscnets + 1;
                                        $class = "attendance-absent";
                                        $attendance_status = "A";
                                        $early_time = $late_time = "";
                                        $signin_time = $signout_time = "00:00:00"; 
                                    }
                                   
                                    echo '<td class="text-center attendance_date '.$class.'">'.$attendance_status.'</td>';
                                }

                                echo '<td class="text-center total_abscnets">'.$total_abscnets.'</td>';
                                echo '<td class="text-center total_presents">'.$total_presents.'</td>';
                            }
                        }
                    ?>
                 
                     
                      
                    
                    
                <tbody>

              </table>
          </div>
        </div> 
             
<script type="text/javascript">

  $('.select2').select2();
  $('#prepend').prepend( "<script>$('.select2').select2()</"+"script>"); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' }); 
</script>