<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Daily Progress Report","Deleted"=>0,"Status"=>1));
  if($module_data->num_rows() > 0)
  {
    $module_info = $module_data->result_array();
    $module_id = $module_info[0]['Id'];
  }

 ?>

<div class="mainpanel">
  <div class="contentpanel" >
    <div class="panel">
      <div class="panel-body">
        <div class="row">
          <div class="col-md-12">
            <div class="col-lg-7 col-md-6 col-sm-6">
              <h3><i class="fa fa-file-o"></i> Daily Progress Report</h3>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-3"> </div>
            <div class="col-lg-2 col-md-2 col-sm-2"> </div>
            <div class="col-lg-1 col-md-2 col-sm-2"> </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <form method="post" action="<?= base_url("admin/get_daily_reports"); ?>" onsubmit="return employee_daily_reports(this)">
            <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
              <input type="text" class="form-control datepicker required" name="From_Date" id="From_Date"  value="<?= date('Y-m')."-01";  ?>" placeholder="From Date">
            </div>
            <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
              <input type="text" class="form-control datepicker" name="To_Date" id="To_Date" placeholder="To Date" value="<?= date('Y-m-t');  ?>">
            </div>
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
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Get Employee Daily Reports</button>
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
                    <h3><?= date("l d F, Y"); ?></h3> 
                  </div>
              </div>
          </div>  
          <?php 
		 
		  			$employee_daily_reports = $this->db->select(" employee_daily_reports.*,employees.First_Name,employees.Last_Name, employees.Photo, departments.Name as Department_Name,designations.Name as Designation_Name  ");
		  			$this->db->where( array("employee_daily_reports.Deleted"=>0,"employee_daily_reports.Status"=>1,"employee_daily_reports.Org_Id"=>$this->org_id,"employee_daily_reports.Report_Status"=>"New"));
		  			$this->db->from("employee_daily_reports");  
		  			$this->db->join("employee_work_record","employee_work_record.Employee_Id = employee_daily_reports.Employee_Id","left");
            $this->db->join("departments","departments.Id = employee_work_record.Department_Id","left"); 
		  			$this->db->join("designations","designations.Id = employee_work_record.Designation_Id","left"); 
					  $this->db->join("employees","employees.Id = employee_daily_reports.Employee_Id","left"); 
					  $this->db->group_by("employee_daily_reports.Id");
		  			$employee_daily_reports = $this->db->get();
		  			//echo $this->db->last_query();

		  			if($employee_daily_reports->num_rows() > 0)
		  			{
		  				foreach ($employee_daily_reports->result() as $key => $value) 
		  				{ 
			  				$src =  "assets/images/default-user.png";
			                if($value->Photo && $value->Photo != "")
			                {
				                $src =  "assets/panel/userassets/employees/".$value->Employee_Id."/".$value->Photo;
				                if(!file_exists($src))
				                {
				                   $src  = "assets/images/default-user.png";
				                } 
			                }
  
		  		?>
            
            <div class="well primary mb10" id="row_<?= $value->Id; ?>">
              <div class="row">
                <div class="col-md-12">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                    <div style="width: 50px; float: left;">
                      <a href="javascript:;"><img src="<?= $src; ?>" class="pro-img"> </a>
                    </div>
                    <div style="width: 80%; float: left;">
                      <a href="javascript:;">
                        <h3 style="padding-left: 10px;"> 
                          <?php echo $value->First_Name." ".$value->Last_Name." ( ".$value->Designation_Name." )"; ?> 
                          <br>
                          <span style="font-size: 13px; color: #f8be0f"><?= $value->Department_Name ; ?> Department</span>
                        </h3>
                      </a>
                    </div> 
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
                    <p style="font-size: 17px;font-family: monospace;"><?= $value->Report; ?></p>
                  </div>
                  <form id="common_form" method="post" action="<?= base_url("admin/save_report_points") ?>" onsubmit="return save_report_points(this);" class="form-horizontal" >
                    <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php echo $value->Id; ?>">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <hr>
                      <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12" style="padding-left: 0px;">
                        <select class="form-control select2 required" name="Points" id="Points" >
                          <option value="0">Report Satisfaction Level</option>
                          <option value="0">0 ( Not Satisfied )</option>
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                          <option value="6">6</option>
                          <option value="7">7</option>
                          <option value="8">8</option>
                          <option value="9">9</option>
                          <option value="10">10 ( Fully Satisfied )</option>
                        </select>
                      </div>
                      <div class="col-md-10 col-lg-10 col-sm-10 col-xs-12" >
                        <button type="submit" class="btn btn-warning pull-left"><i class="fa fa-check"></i> Save</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div> 
            </div>

          <?php }}else{ echo no_record_found(); } ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('.select2').select2(); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' }); 
   
</script>