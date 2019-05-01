<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Leaves History","Deleted"=>0,"Status"=>1));
  if($module_data->num_rows() > 0)
  {
    $module_info = $module_data->result_array();
    $module_id = $module_info[0]['Id'];
  }
 

  $first_employee_id = 0;

 ?>

 <style type="text/css">
 	.approved
 	{
 		background: #54c139 !important;
 	}
 	.rejected
 	{
 		background: #de3151 !important;
 	}
 	.pending
 	{
 		background: #65bcf9 !important;
 	}
 </style>
    <div class="row"> 
	    <form method="post" action="<?= base_url("admin/get_leaves_history"); ?>" onsubmit="return get_leaves_history(this)"> 
	      <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
	         <input type="text" class="form-control yearpicker required" name="Year" id="Year"  value="<?= date('Y');  ?>" placeholder="Year">
	      </div>
	      <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
	         <select class="form-control select2 required" name="Month" id="Month" > 
	              <option value="01">January</option> 
	              <option value="02">February</option> 
	              <option value="03">March</option> 
	              <option value="04">April</option> 
	              <option value="05">May</option> 
	              <option value="06">June</option> 
	              <option value="07">July</option> 
	              <option value="08">August</option> 
	              <option value="09">September</option> 
	              <option value="10">October</option> 
	              <option value="11">November</option> 
	              <option value="12">December</option> 
	          </select>
	      </div> 
	      <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
	          <select class="form-control select2 required" name="Employee_Id" id="Employee_Id" > 
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
	      	<button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Get Leaves History</button>
	      </div>
	      <div class="col-md-offset-2 col-lg-offset-2 col-sm-offset-3 col-xs-12"></div>
	      <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><hr></div>
	    </form>
    </div>  
  	<div class="row" id="leaves_history">
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
  					$class = "approved";
  					$department_name = "Not Saved!";
  					if($value->Department_Name != ""){ $department_name = $value->Department_Name; }
  					
  					$year = date("Y"); 

  					
  					$this->db->where(array("leave_applications.Deleted"=>0,"leave_applications.Status"=>1,"leave_applications.Employee_Id"=>$value->Id,"leave_applications.Org_Id"=>$this->org_id));
  					$this->db->like("leave_applications.From_Date",$year,"both");
  					$this->db->select("leave_applications.*,employees.First_Name as Checked_By_First_Name, employees.Last_Name as Checked_By_Last_Name");
  					$this->db->from("leave_applications");
  					$this->db->join("employees","employees.Id = leave_applications.Status_Changed_By","left");
  					$leave_applications = $this->db->get(); 

  				 	$leave_application_data = array();

  					if($leave_applications->num_rows() > 0)
  					{
  						foreach ($leave_applications->result() as $index => $application_data) 
  						{ 

			  				$src =  "assets/images/default-user.png";
			                if($value->Photo && $value->Photo != "")
			                {
				                $src =  "assets/panel/userassets/employees/".$value->Id."/".$value->Photo;
				                if(!file_exists($src))
				                {
				                   $src =  "assets/images/default-user.png";
				                } 
			                }
  					 		
  					 		if($application_data->Application_Status == "Approved"){ $class = "approved"; }
  					 		elseif($application_data->Application_Status == "Rejected"){ $class = "rejected";}
  					 		elseif($application_data->Application_Status == "Pending"){ $class = "pending";}


  					 		$date1=date_create($application_data->From_Date);
		                    $date2=date_create($application_data->To_Date);
		                    $diff=date_diff($date1,$date2);
		                    $leaves = $diff->format("%a") + 1;


  		?>
      	<div class="col-md-3 col-lg-2 col-sm-4 col-xs-12 attendance_rec" id="row_<?= $value->Id; ?>" >
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
	              <p class="media-usermeta" style="color: #fff;"> <?= $department_name; ?> </p> 
	            </div>
	            <ul class="panel-options">
	              <li>
	              	<div class="btn-group">
                      <button type="button" class="dropdown-toggle action-dropdown" style="font-size: 8px;" data-toggle="dropdown"><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i></button>
                      <ul class="dropdown-menu" role="menu">
                        <?php if(in_array($module_id."_view", $this->role_permissions)){ ?>
                        <li><a href="javascript:;" style="color: green;" onclick="open_modal_window(this,'employee_view','<?= $value->Id; ?>')"> <i class="fa fa-eye"></i> Employee Profile </a></li>
                        <?php }if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                        <li><a href="javascript:;" style="color: blue;" onclick="load_tab(this,'add_leave_application',<?= $value->Id; ?>,'leave_tabs_body','<?= $application_data->Id; ?>')"> <i class="fa fa-pencil"></i> Edit </a></li>
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
	              		<th>From Date:</th>
	              		<td><?= date("D M d, Y",strtotime($application_data->From_Date)); ?></td>
	              	</tr>
	              	<tr>
	              		<th>To Date:</th>
	              		<td><?= date("D M d, Y",strtotime($application_data->To_Date)); ?></td>
	              	</tr>
	              	<tr>
	              		<th>Total Leaves</th>
	              		<td> <?= $leaves; ?> </td>
	              	</tr>
	              </table>
	            </div> 
	            <div class="info-group">
	            	<table class="table table-bordered table-inverse nomargin att-table">
		              	<tr>
		              		<th>Approved By:</th>
		              		<td><?= $application_data->Checked_By_First_Name." ".$application_data->Checked_By_Last_Name; ?></td>
		              	</tr>
		              	<tr>
		              		<th>Last Modified on:</th>
		              		<td><?= date("D M d, Y",strtotime($application_data->Date_Modification)); ?></td>
		              	</tr>  
		            </table> 
	            </div>
	            <div class="info-group late-reason"> 
	              <label>Leave Reason</label>
	              <p class="late_reason_p" ><?php echo strip_tags( substr($application_data->Reason,0,100) ); ?></p>
	            </div> 
	          </div> 
       		</div> 
      	</div>

      	 
      <?php }}}}else{ echo no_record_found(); } ?>	 

      	 
 
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


$('.yearpicker').yearpicker();

 
</script>