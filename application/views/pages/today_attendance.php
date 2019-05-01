 
<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Today Attendance","Deleted"=>0,"Status"=>1));
  if($module_data->num_rows() > 0)
  {
    $module_info = $module_data->result_array();
    $module_id = $module_info[0]['Id'];
  }
 
 ?>
    <div class="row">  
      <div class="col-md-4 col-lg-4 col-sm-3 col-xs-12">
         <input type="text" class="form-control" name="Text" id="Text" onkeyup="filter_employees(this)" placeholder="Write to search">
      </div>
      <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
           <select class="form-control select2" name="Location_Id" id="Location_Id" onchange="filter_employees()"  >
              <option selected="selected" value="">Select Location</option>
              <?php 

                $this->db->order_by("Name","asc");
                $locations = $this->db->get_where('locations',array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                if($locations->num_rows() > 0)
                {
                  foreach ($locations->result() as $key => $value) {
                    echo '<option value="'.$value->Name.'">'.$value->Name.'</option>';
                  }
                }

              ?>  
          </select>
      </div>
      <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
           <select class="form-control select2" name="Department_Id" id="Department_Id"  onchange="filter_employees()"   >
              <option selected="selected" value="">Select Department</option>
              <?php 

                $this->db->order_by("Name","asc");
                $departments = $this->db->get_where('departments',array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                if($departments->num_rows() > 0)
                {
                  foreach ($departments->result() as $key => $value) {
                    echo '<option value="'.$value->Name.'">'.$value->Name.'</option>';
                  }
                }

              ?>  
          </select>
      </div>

      <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
          <select class="form-control select2" name="Shift" id="Shift"  onchange="filter_employees()"  >
              <option selected="selected" value="">Select Shift</option>
              <?php 

                $this->db->order_by("Name","asc");
                $shifts = $this->db->get_where('shifts',array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                if($shifts->num_rows() > 0)
                {
                  foreach ($shifts->result() as $key => $value) {
                    echo '<option value="'.$value->Name.'">'.$value->Name.'</option>';
                  }
                }

              ?>  
          </select>
      </div> 
       
      <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
          <select class="form-control select2" name="Attendance_Status" id="Attendance_Status"  onchange="filter_employees()"  > 
              <option value="" selected="selected">All Employees</option> 
              <option value="Present">Present</option> 
              <option value="Late">Late Comers</option>   
              <option value="Abscent">Abscent</option>    
          </select>
        </div>   
    </div>
    <hr>
  	<div class="row">
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
  					$Late_Reason = "";
  					$Attendance_Id = 0;
  					$date = date("Y-m-d"); 
		  			$attendance = $this->db->get_where("attendance",array("Deleted"=>0,"Status"=>1,"Employee_Id"=>$value->Id,"Signout"=>'0000-00-00 00:00:00',"Signin !="=>'0000-00-00 00:00:00'));
		  			 
		  			$attendance_status = "";
	  				$early_time = $late_time = $class = "";

		  			if($attendance->num_rows() > 0)
		  			{
		  				$attendance_data = $attendance->result_array();
		  				$signin_time = $attendance_data[0]['Signin'];
		  				$signout_time = $attendance_data[0]['Signout'];
		  				$Late_Reason = $attendance_data[0]['Late_Reason'];
		  				$Attendance_Id = $attendance_data[0]['Id'];

		  				if(strtotime($value->Shift_Start_Time) >= strtotime($signin_time))
		  				{
		  					$class = "panel-present";
		  					$attendance_status = "present";
		  					$start_time = date("Y-m-d H:i:s", strtotime($signin_time));
		  					$end_time = date("Y-m-d H:i:s", strtotime($value->Shift_Start_Time));
		  					$difference = date_difference($start_time,$end_time);
		  					if( $difference['Hour'] > 0){ $early_time =  $difference['Hour']." Hour "; }
			  			    if( $difference['Minuts'] > 0 && $early_time != ""){ $early_time .=  $difference['Minuts']." Minutes "; }else{ $early_time =  $difference['Minuts']." Minutes ";} 
		  					//$early_time = $difference['Hour'].":".$difference['Minuts'].":".$difference['Seconds'];
		  				}
		  				else
		  				{
		  					$class = "panel-late";
		  					$attendance_status = "late";
		  					$start_time = date("Y-m-d H:i:s", strtotime($value->Shift_Start_Time));
		  					$end_time = date("Y-m-d H:i:s", strtotime($signin_time));
		  					$difference = date_difference($start_time,$end_time);
		  					if( $difference['Hour'] > 0){ $late_time =  $difference['Hour']." Hour "; }
			  			    if( $difference['Minuts'] > 0 && $late_time != ""){ $late_time .=  $difference['Minuts']." Minutes "; }else{ $late_time =  $difference['Minuts']." Minutes ";}  
		  					//$late_time = $difference['Hour'].":".$difference['Minuts'].":".$difference['Seconds'];
		  				}
		  			}
		  			else
		  			{
		  				$class = "panel-absent";
	  					$attendance_status = "absent";
	  					$early_time = $late_time = "";
	  					$signin_time = $signout_time = "00:00:00"; 
		  			}
  				   
	  				
	  				$src =  "assets/images/default-user.png";
	                if($value->Photo && $value->Photo != "")
	                {
		                $src =  "assets/panel/userassets/employees/".$value->Id."/".$value->Photo;
		                if(!file_exists($src))
		                {
		                   $src =  "assets/images/default-user.png";
		                } 
	                }
  					 

  		?>
      	<div class="col-md-3 col-lg-2 col-sm-4 col-xs-12 attendance_rec" id="row_<?= $value->Id; ?>" name="<?= $value->First_Name." ".$value->Last_Name; ?>" location="<?= $value->Location_Name; ?>" shift="<?= $value->Shift_Name; ?>" department="<?= $department_name; ?>" attendance="<?= $attendance_status; ?>">
        	<div class="panel panel-profile grid-view <?= $class; ?>">
	          <div class="panel-heading">
	            <div class="text-center">
	            	<div style="width: 80px; margin:0 auto;">
		              <a href="#" onclick="open_modal_window(this,'employee_view','<?= $value->Id; ?>')" class="panel-profile-photo">
		                <img class="img-circle" src="<?= $src; ?>" style="width: 100%;" alt="">
		              </a>
		            </div>
	              <a href="javascript:;"  onclick="open_modal_window(this,'employee_view','<?= $value->Id; ?>')">
	              	<h4 class="panel-profile-name" ><?= $value->First_Name." ".$value->Last_Name; ?></h4>
	              </a>
	              <p class="media-usermeta" style="color: #fff;"> <?= $department_name; ?></p>
	            </div>
	            <ul class="panel-options">
	              <li>
	              	<div class="btn-group">
                      <button type="button" class="dropdown-toggle action-dropdown" style="font-size: 8px;" data-toggle="dropdown"><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i></button>
                      <ul class="dropdown-menu" role="menu">
                        <?php if(in_array($module_id."_view", $this->role_permissions)){ ?>
                        <li><a href="javascript:;" style="color: green;" onclick="open_modal_window(this,'employee_view',<?= $value->Id; ?>)"> <i class="fa fa-eye"></i> Employee Profile </a></li>
                        <?php }if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                        <li><a href="javascript:;" style="color: blue;" onclick="load_tab(this,'update_attendance',<?= $value->Id; ?>,'attendance_tabs_body','<?= $Attendance_Id; ?>')"> <i class="fa fa-pencil"></i> Edit </a></li>
                        <?php }if(in_array($module_id."_delete", $this->role_permissions)){ ?>
                        <li><a href="javascript:;" onclick="delete_record('attendance','<?= $Attendance_Id; ?>',this)" style="color: red;"> <i class="fa fa-trash"></i> Delete</a></li>
                        <?php } ?> 
                        <li><a href="javascript:;" onclick="employee_signout(<?= $Attendance_Id; ?>,<?= $value->Id; ?>)" style="color: red;"> <i class="fa fa-power-off"></i> Signout</a></li>
                      </ul>
                    </div>
	              </li>
	            </ul>
	          </div><!-- panel-heading -->
	          <div class="panel-body people-info">

	            <div class="info-group">
	              <table class="table table-bordered table-inverse nomargin att-table">
	              	<tr>
	              		<th>Signin Time:</th>
	              		<td><?= $signin_time; ?></td>
	              	</tr>
	              	<tr>
	              		<th>Signout Time:</th>
	              		<td id="employee_signout_<?= $Attendance_Id; ?>"><?= $signout_time; ?></td>
	              	</tr>
	              	<tr>
	              		<th><?php if($early_time == ""){ echo "Late"; }else{ echo "Early"; } ?> Signin:</th>
	              		<td>
	              			<?php echo $early_time.$late_time; ?> 
	              		</td>
	              	</tr>
	              </table>
	            </div>
	            <div class="info-group">
	            	<table class="table table-bordered table-inverse nomargin att-table">
		              	<tr>
		              		<th>Shift: </th>
		              		<td><?= $value->Shift_Name; ?></td>
		              	</tr>
		              	<tr>
		              		<th>Location:</th>
		              		<td><?= $value->Location_Name; ?></td>
		              	</tr> 
		            </table> 
	            </div>
	            <div class="info-group late-reason">

	              <label>Late Reason</label>
	              <p class="late_reason_p" ><?php if($Late_Reason && $Late_Reason != ""){ echo $Late_Reason; }else{ echo "Not Saved!"; } ?></p>
	            </div> 
	          </div> 
       		</div> 
      	</div>

      	 
      <?php }}else{ echo no_record_found(); } ?>	 

      	 
 
    </div>
 
 <script type="text/javascript">
  $('.select2').select2(); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' }); 
  function filter_employees($this)
  {
  	 
  	$text = $("#Text").val().toLowerCase();
  	$location = $("#Location_Id").val().toLowerCase();
  	$department = $('#Department_Id').val().toLowerCase();
  	$shift = $('#Shift').val().toLowerCase();
  	$attendance = $('#Attendance_Status').val().toLowerCase();

  	if($text != "" || $location != "" || $department != "" || $shift != "" || $attendance != "")
  	{ 
	  	$('.attendance_rec').map(function() 
	 	{
	 		$(this).css("display","none");
	 	});
	}
	else
	{
		$('.attendance_rec').map(function() 
	 	{
	 		$(this).css("display","block");
	 	}); 
	}
  	

  	if($text != "")	
  	{  
	  	$('.attendance_rec').each(function() 
	 	{
	 		$emp_name = $(this).attr("name").toLowerCase();
	 		$emp_location = $(this).attr("location").toLowerCase();
	 		$emp_shift = $(this).attr("shift").toLowerCase();
	 		$emp_department = $(this).attr("department").toLowerCase();
	 		$emp_attendance = $(this).attr("attendance").toLowerCase();

	 		   
		 	   if(
		 	   		$emp_name.includes($text) || 
		 	   		$emp_location.includes($text) || 
		 	   		$emp_shift.includes($text) || 
		 	   		$emp_department.includes($text) || 
		 	   		$emp_attendance.includes($text)   
		 	   	)
		 	   {
		 	   	$(this).css("display","block");
		 	   } 
		});
	} 
  	 

    if($location != "")
  	{   
  		$('.attendance_rec').each(function() 
 		{ 
	 	   $emp_location = $(this).attr("location").toLowerCase(); 
	 	   if( $emp_location == $location )
	 	   { 
	 	   	$(this).css("display","block");
	 	   }  
	 	});
  	}
    
    if($department != "")
  	{ 
  		$('.attendance_rec').each(function() 
 		{ 
	 	   $emp_department = $(this).attr("department").toLowerCase();
	 	  
	 	   if(  $emp_department == $department )
	 	   {
	 	   	$(this).css("display","block");
	 	   } 
		});
  	}
    
    if($shift != "")
  	{ 

  		$('.attendance_rec').each(function() 
 		{ 
 		   $emp_shift = $(this).attr("shift").toLowerCase(); 

	 	   if( $shift ==  $emp_shift)
	 	   {
	 	   	$(this).css("display","block");
	 	   } 	
		});
  	}
   	
    

    if($attendance != "")
  	{ 
  		$('.attendance_rec').each(function() 
 		{ 
	 	   $emp_attendance = $(this).attr("attendance").toLowerCase();

	 	   if($emp_attendance == $attendance )
	 	   {
	 	   	$(this).css("display","block");
	 	   } 
		});
  	}
 
  	 
  }
</script>