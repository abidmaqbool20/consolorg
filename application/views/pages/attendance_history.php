 
<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Attendance History","Deleted"=>0,"Status"=>1));
  if($module_data->num_rows() > 0)
  {
    $module_info = $module_data->result_array();
    $module_id = $module_info[0]['Id'];
  }
 

  $first_employee_id = 0;

 ?>
    <div class="row"> 
	    <form method="post" action="<?= base_url("admin/get_attendance_history"); ?>" onsubmit="return get_attendance_history(this)"> 
	      <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
	         <input type="text" class="form-control datepicker required" name="From_Date" id="From_Date"  value="<?= date('Y-m')."-01";  ?>" placeholder="From Date">
	      </div>
	      <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
	         <input type="text" class="form-control datepicker" name="To_Date" id="To_Date" placeholder="To Date" value="<?= date('Y-m-t');  ?>">
	      </div>
	      <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
	          <select class="form-control select2 required" name="Employee_Id" id="Employee_Id" > 
	              <?php 
                     $employees = $this->CI->get_employees_list();
                     $dep_id = 0;
                     $total_records = sizeof($employees);
                     foreach ($employees as $key => $value) {

                     	if($key == 0){ $first_employee_id = $value->Id; }

                        if($dep_id == 0  ){
                          $dep_id = $value->Dep_Id;
                          echo '<optgroup label="'.$value->Department_Name.'">';
                        }

                        if($dep_id != $value->Dep_Id){
                          $dep_id = $value->Dep_Id; 
                          echo '</optgroup><optgroup label="'.$value->Department_Name.'">';
                        }

                        $employee_unique_id =  $value->Joining_Date != "0000-00-00" ? employee_unique_id($value->Joining_Date,$value->Id) : 'Not Joined';
                        
                        $selected = ""; 
                        echo '<option '.$selected.' value="'.$value->Id.'">'.$value->First_Name.' '.$value->Last_Name.' ( '.$employee_unique_id.' )</option>'; 
                        if($total_records == $key+1){ echo '</optgroup>'; }
                     }
                   ?>
  
	          </select>
	      </div>  
	      <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
	      	<button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Get Attendance History</button>
	      </div>
	      <div class="col-md-offset-2 col-lg-offset-2 col-sm-offset-3 col-xs-12"></div>
	      <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><hr></div>
	    </form>
    </div>  
  	<div class="row" id="attendance_history">
  		<?php 
 
  			$this->db->select("employees.First_Name,employees.Last_Name,employees.Id,employees.Photo,shifts.Name as Shift_Name,shifts.Start_Time as Shift_Start_Time,departments.Name as Department_Name,locations.Name as Location_Name");
  			$employees = $this->db->where( array("employees.Deleted"=>0,"employees.Status"=>1,"employees.Org_Id"=>$this->org_id,"employees.Employee_Status"=>"Active","employees.Id"=>$first_employee_id ));
  			$this->db->from("employees"); 
			$this->db->join("employee_work_record","employee_work_record.Employee_Id = employees.Id","left");
			$this->db->join("departments","departments.Id = employee_work_record.Department_Id","left");
			$this->db->join("shift_employees","shift_employees.Employee_Id = employees.Id","left");
			$this->db->join("shifts","shifts.Id = shift_employees.Shift_Id","left");
			$this->db->join("locations","locations.Id = employees.Location_Id","left");	 
  			$this->db->group_by("employees.Id");
  			$employees = $this->db->get();
  			//echo $this->db->last_query();

  			if($employees->num_rows() > 0)
  			{
  				foreach ($employees->result() as $key => $value) 
  				{
  					$department_name = "Not Saved!";
  					if($value->Department_Name != ""){ $department_name = $value->Department_Name; }
  					
  					
  					$month_end = date('Y-m-t'); 
  					$loop_end = date("d",strtotime($month_end));

  					$employee_attendance_date = date("Y-m")."-01";

  					for ($i = 1; $i <= $loop_end; $i++) 
  					{ 
  						$signin_time = $signout_time = "0000-00-00 00:00:00";
	  					$Late_Reason = "";
	  					$Attendance_Id = 0;
	  					$date = date("Y-m-d"); 

  						$att_date = date('Y-m-d', strtotime($employee_attendance_date . ' +'.$i.' day'));
			  			$attendance = $this->db->get_where("attendance",array("Deleted"=>0,"Status"=>1,"Employee_Id"=>$first_employee_id,"Date"=>$att_date));
			  			 
			  			$attendance_date = date("D - d M, Y",strtotime($att_date));

			  			$class = "panel-absent";
			  			$time_sepnt = "00:00:00";

			  			if($attendance->num_rows() > 0)
			  			{
			  				foreach ($attendance->result() as $index => $attendance_data) 
			  				{ 
				  				$signin_time = $attendance_data->Signin;
				  				$signout_time = $attendance_data->Signout;
				  				$Late_Reason = $attendance_data->Late_Reason;
				  				$Attendance_Id = $attendance_data->Id;

				  				if($signout_time == "0000-00-00 00:00:00"){ $signout_time == date("Y-m-d H:i:s"); }
				  				
				  				$emp_start_time = date("Y-m-d H:i:s", strtotime($signin_time));
			  					$emp_end_time = date("Y-m-d H:i:s", strtotime($signout_time));

			  					$time_difference = date_difference($emp_start_time,$emp_end_time);
			  					if( $time_difference['Hour'] > 0){ $time_sepnt =  $time_difference['Hour']." Hour "; }
			  					if( $time_difference['Minuts'] > 0 && $time_sepnt != "00:00:00"){ $time_sepnt .=  $time_difference['Minuts']." Minutes "; }else{ $time_sepnt =  $time_difference['Minuts']." Minutes ";}
			  					//$time_sepnt = $time_difference['Hour'].":".$time_difference['Minuts'].":".$time_difference['Seconds'];


				  				if(strtotime($value->Shift_Start_Time) >= strtotime($signin_time))
				  				{
				  					$class = "panel-present";
				  					$attendance_status = "present"; 
				  				}
				  				else
				  				{
				  					$class = "panel-late";
				  					$attendance_status = "late"; 
				  				}
			  			 	}
			  			}
			  			else
			  			{
			  				$class = "panel-absent";
			  				$attendance_status = "absent";
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
	              <p class="media-usermeta" style="color: #fff;"> <?= $department_name; ?> | <strong><?= $attendance_date; ?></strong></p>
	            </div>
	            <ul class="panel-options">
	              <li>
	              	<div class="btn-group">
                      <button type="button" class="dropdown-toggle action-dropdown" style="font-size: 8px;" data-toggle="dropdown"><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i></button>
                      <ul class="dropdown-menu" role="menu">
                        <?php if(in_array($module_id."_view", $this->role_permissions)){ ?>
                        <li><a href="javascript:;" style="color: green;" onclick="open_modal_window(this,'employee_view','<?= $value->Id; ?>')"> <i class="fa fa-eye"></i> Employee Profile </a></li>
                        <?php }if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                        <li><a href="javascript:;" style="color: blue;" onclick="load_tab(this,'update_attendance',<?= $value->Id; ?>,'attendance_tabs_body','<?= $Attendance_Id; ?>')"> <i class="fa fa-pencil"></i> Edit </a></li>
                        <?php } ?>
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
	              		<th>Time Spent:</th>
	              		<td>
	              			<?php echo $time_sepnt; ?> 
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

      	 
      <?php }}}else{ echo no_record_found(); } ?>	 

      	 
 
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