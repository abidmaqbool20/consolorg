 <?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Manage Leave Applications","Deleted"=>0,"Status"=>1));
  if($module_data->num_rows() > 0)
  {
    $module_info = $module_data->result_array();
    $module_id = $module_info[0]['Id'];
  } 

 ?>
 <style type="text/css">
 	.btn-success
 	{
 		background-color: #2cad5a !important;
 	}
 	.leave-request-table tr th
 	{
 		min-width: 200px;
 		width: 200px;
 	}
 	.img-circle
 	{
 		border:1px solid #fff;
 	}
 </style>
   
 	   <div class="row"> 
	    <form method="post" action="<?= base_url("admin/get_leaves_history"); ?>" onsubmit="return get_leaves_history(this)"> 
	      <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12">
	      	<label class="control-label">From Date</label>
	        <input type="text" class="form-control datepicker required" name="From_Date" id="From_Date" placeholder="From Date">
	      </div>
	      <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12">
	      	<label class="control-label">To Date</label>
	        <input type="text" class="form-control datepicker required" name="To_Date" id="To_Date" placeholder="To Date">
	      </div>  
	      <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
	      	<label class="control-label">Select Employee</label>
	        <select class="form-control select2 required" name="Employee_Id" id="Employee_Id" > 
	        	<option value="0">Select Employee</option>
	              <?php 

		              	$this->db->order_by("First_Name","ASC");
		                $this->db->select("First_Name,Last_Name,Id","asc");
		                $employees = $this->db->get_where('employees',array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Employee_Status"=>"Active"));
		                if($employees->num_rows() > 0)
		                {
		                  foreach ($employees->result() as $key => $value)
		                  {
		                  	
		                  	if($key == 0){ $first_employee_id = $value->Id; }
		                    echo '<option value="'.$value->Id.'">'.$value->First_Name.' '.$value->Last_Name.' (#) </option>';
		                  }
		                }

	              ?>  
	          </select>
	      </div>  
	      <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12">
	      	<label class="control-label" style="width: 100%">&nbsp;</label>
	      	<button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Get Leave Applications</button>
	      </div>
	      <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
	      	<label class="control-label" style="width: 100%">&nbsp;</label>
	      	<button type="button" onclick="load_tab(this,'add_leave_application',<?= 1; ?>,'leave_tabs_body')" class="btn btn-warning pull-right "><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add New </button> 
	      </div> 
	      <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><hr></div>
	    </form>
    </div>
  	<div class="row" style="max-height: 72vh; overflow: scroll;">
  		<?php 

  			$leave_applications = 0; 

  			$this->db->select("
  								leave_applications.*,
  								employees.First_Name,
  								employees.Last_Name, 
  								employees.Photo,
  								shifts.Name as Shift_Name,
  								shifts.Start_Time as Shift_Start_Time,
  								departments.Name as Department_Name,
  								locations.Name as Location_Name
  							");

  			$employees = $this->db->where( 
  											array(
  													"leave_applications.Deleted"=>0,
  													"leave_applications.Status"=>1,
  													"employees.Deleted"=>0,
  													"employees.Status"=>1,
  													"employees.Org_Id"=>$this->org_id,
  													"employees.Employee_Status"=>"Active"
  												)
  										 );

  			$this->db->from("leave_applications"); 
			$this->db->join("employees","employees.Id = leave_applications.Employee_Id","left");
			$this->db->join("employee_work_record","employee_work_record.Employee_Id = employees.Id","left");
			$this->db->join("departments","departments.Id = employee_work_record.Department_Id","left");
			$this->db->join("shift_employees","shift_employees.Employee_Id = employees.Id","left");
			$this->db->join("shifts","shifts.Id = shift_employees.Shift_Id","left");
			$this->db->join("locations","locations.Id = employees.Location_Id","left");	 
  			$this->db->group_by("leave_applications.Id" );
  			$applications = $this->db->get();


  			if($applications->num_rows() > 0)
  			{  
  			  foreach ($applications->result() as $key => $value)
  			  { 
			  	  $leave_applications = $leave_applications + 1;
				  $available_paid_leaves = 0;
				  $available_unpaid_leaves = 0;

				  $year = date("Y",strtotime($value->To_Date));
				  $get_leave_record = $this->db->get_where("employee_leaves",array("Year"=>$year,"Employee_Id"=>$value->Employee_Id,"Deleted"=>0,"Status"=>1));
				  if($get_leave_record->num_rows() > 0)
				  {
				  	$leave_record = $get_leave_record->result_array();
				  	$available_paid_leaves = $leave_record[0]['Paid_Leaves'] - $leave_record[0]['Consumed_Paid_Leaves'];
				  	$available_unpaid_leaves = $leave_record[0]['Unpaid_Leaves'] - $leave_record[0]['Consumed_Unpaid_Leaves'];
				  	 
				  }	
			   
			      $src =  "assets/images/default-user.png";
			      
                  if($value->Photo && $value->Photo != "")
                  {
	                $src =  "assets/panel/userassets/employees/".$value->Employee_Id."/".$value->Photo;
	                if(!file_exists($src))
	                {
	                   $src =  "assets/images/default-user.png";
	                } 
                  }

                  if($value->Application_Status == "Approved"){
                  	$style = 'background-color: #7cd6b7; opacity: 0.8;';
                  }
                  elseif($value->Application_Status == "Rejected"){
                  	$style = 'background-color: #ff8080; opacity: 0.8;';
                  }
                  elseif($value->Application_Status == "Pending"){
                  	$style = 'background-color: #e6e0ff; opacity: 0.7;';
                  }



  		?>
      	<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 attendance_rec" style="margin-bottom: 10px;" id="row_<?= $value->Id; ?>" > 
        	<div class="">
        		<div class="row">
			        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 0;">
			        	<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0;"> 
			        		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			        			<div class="table-responsive">
					                <table class="table table-bordered table-inverse table-striped nomargin leave-request-table"> 
					                    <tr>
					                      <th colspan="2" style="<?= $style; ?>">
						                      	<div style="width: 75px; float: left;">
									              <a href="#" onclick="open_modal_window(this,'employee_view','<?= $value->Employee_Id; ?>')" class="panel-profile-photo">
									                <img class="img-circle" src="<?= $src; ?>" style="width: 100%;" alt=""> 
									              </a>
									            </div>
									            <h3>&nbsp;&nbsp;<?= $value->First_Name." ".$value->Last_Name; ?></h3>
									            <h5 style="padding-left: 87px; font-size: 14px;">Department: <span style="font-size: 13px;"><?= $value->Department_Name; ?></span></h5>
									            <h6 style="padding-left: 87px; color: #029fbb;"><?= date("D d M, Y",strtotime($value->Date_Added)); ?></h6>
									             
									            <ul class="panel-options pull-left">
									              <li>
									              	<div class="btn-group ">
								                      <button type="button" class="dropdown-toggle action-dropdown" style="font-size: 8px;" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i></button>
								                      <ul class="dropdown-menu" role="menu">
								                      	<?php if($value->Application_Status == "Approved"){ ?>
		                                                <li><a href="javascript:;" style="color: black;" onclick="change_leave_application_status(this,'<?= $value->Id; ?>','<?= $value->Employee_Id; ?>','Pending')"> <i class="fa fa-adjust"></i> Pending </a></li>
		                                                <li><a href="javascript:;" style="color: red;" onclick="change_leave_application_status(this,'<?= $value->Id; ?>','<?= $value->Employee_Id; ?>','Rejected')"> <i class="fa fa-times"></i> Reject </a></li>
		                                                <?php }if($value->Application_Status == "Rejected"){ ?>
		                                                <li><a href="javascript:;" style="color: black;" onclick="change_leave_application_status(this,'<?= $value->Id; ?>','<?= $value->Employee_Id; ?>','Pending')"> <i class="fa fa-adjust"></i> Pending </a></li>
		                                                <li><a href="javascript:;" style="color: green;" onclick="change_leave_application_status(this,'<?= $value->Id; ?>','<?= $value->Employee_Id; ?>','Approved')"> <i class="fa fa-check"></i> Approved </a></li>
		                                                <?php }if($value->Application_Status == "Pending"){ ?> 
		                                               	<li><a href="javascript:;" style="color: green;" onclick="change_leave_application_status(this,'<?= $value->Id; ?>','<?= $value->Employee_Id; ?>','Approved')"> <i class="fa fa-check"></i> Approved </a></li>
		                                               	<li><a href="javascript:;" style="color: red;" onclick="change_leave_application_status(this,'<?= $value->Id; ?>','<?= $value->Employee_Id; ?>','Rejected')"> <i class="fa fa-times"></i> Reject </a></li>
		                                                <?php } ?>
		                                                <li><a href="javascript:;" style="color: blue;" onclick="load_tab(this,'add_leave_application',<?= $value->Employee_Id; ?>,'leave_tabs_body','<?= $value->Id; ?>')"> <i class="fa fa-pencil"></i> Edit </a></li>
		                                                <li><a href="javascript:;" style="red: blue;" onclick="delete_record('leave_applications','<?= $value->Id; ?>',this)"> <i class="fa fa-trash"></i> Delete </a></li>
		                                              </ul>
								                    </div>
									              </li>
									            </ul>
						                  </th> 
					                    </tr>
					                    <tr>
					                      <th >Employee Available Leaves</th>
					                  	  <td>Paid Leave: <?= $available_paid_leaves; ?> | Unpaid Leaves: <?= $available_unpaid_leaves; ?></td>
					                    </tr>
					                    <tr>
					                      <th>Leave Duration</th>
					                  	  <td><strong> From:</strong> <?= date("D d M, Y",strtotime($value->From_Date)); ?> <strong> &nbsp;&nbsp;&nbsp;TO:</strong><?= date("D d M, Y",strtotime($value->To_Date)); ?></td>
					                    </tr>
					                    <tr>
					                    	<th>Application</th>
					                    	<td><p><?= $value->Reason; ?></p></td>
					                    </tr>  
					                    <?php 
					                    	if($value->Application_Status == "Pending" && $value->From_Date > date("Y-m-d"))
					                    	{
					                    ?>
					                    <tr>
					                     	<th></th>
					                     	<td>
					                     		<button class="btn btn-success" onclick="change_leave_application_status(this,'<?= $value->Id; ?>','<?= $value->Employee_Id; ?>','Approved')" type="button"><i class="fa fa-check"></i> Approve</button>
				              					<button class="btn btn-danger"  onclick="change_leave_application_status(this,'<?= $value->Id; ?>','<?= $value->Employee_Id; ?>','Rejected')" type="button"><i class="fa fa-times"></i> Reject</button>
					                     	</td>
					                    </tr>
					                <?php } ?>
					                </table>
					              </div>
				            	 
				            </div>
				            <div class="mb15"></div>
				             
				        </div>
			        </div>
			    </div>
	        </div>
      	</div> 
      	 
      <?php 	} 
  				
  				if($applications->num_rows() > 10)
  				{
  					echo '<div class="row">
					 		<div class="col-md-12" style=" text-align: center; margin: 25px 0;">
					 			<button class="button btn-lg btn-primary"><i class="fa fa-refresh"></i> Load More (50 Remains)</button>
					 		</div>
					 	  </div>';
				}
  			}


      		if($leave_applications == 0)
      		{
      			echo no_record_found();
      		}
      ?>	 

      	 
 
    </div>
 	
 <script type="text/javascript">
  $('.select2').select2(); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' }); 
  
  
 	
</script>