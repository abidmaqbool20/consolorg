 
<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Leaves Record","Deleted"=>0,"Status"=>1));
  if($module_data->num_rows() > 0)
  {
    $module_info = $module_data->result_array();
    $module_id = $module_info[0]['Id'];
  }

 ?>
 <style type="text/css">
 	.available-leaves
 	{
 		color: cornsilk !important;
 	}
 	.consumed-leaves
 	{
 		color: #9a1919 !important;
 	}
 	.remaining-leaves
 	{
 		color:green !important;
 	}
 </style>
  
  	<div class="row" id="attendance_history">
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
  			//echo $this->db->last_query();

  			if($employees->num_rows() > 0)
  			{
  				foreach ($employees->result() as $key => $value) 
  				{
  					$department_name = "Not Saved!";
  					if($value->Department_Name != ""){ $department_name = $value->Department_Name; }
  					
  					$year = date("Y");

  					$employee_leaves = $this->db->get_where("employee_leaves",array("Deleted"=>0,"Status"=>1,"Employee_Id"=>$value->Id,"Year"=>$year,"Org_Id"=>$this->org_id));
 					
  					$leave_rec_id = 0; 
	                $paid_leaves = 0;
	                $unpaid_leaves = 0;
	                $consumed_paid_leaves = 0;
	                $consumed_unpaid_leaves = 0;	

  					if($employee_leaves->num_rows() > 0)
  					{
  						$employee_leave_data = $employee_leaves->result_array();

  						$leave_rec_id = $employee_leave_data[0]['Id'];
  						$paid_leaves = $employee_leave_data[0]['Paid_Leaves'];
		                $unpaid_leaves = $employee_leave_data[0]['Unpaid_Leaves'];
		                $consumed_paid_leaves = $employee_leave_data[0]['Consumed_Paid_Leaves'];
		                $consumed_unpaid_leaves = $employee_leave_data[0]['Consumed_Unpaid_Leaves'];
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
      	<div class="col-md-3 col-lg-2 col-sm-4 col-xs-12 attendance_rec" id="row_<?= $value->Id; ?>"   >
        	<div class="panel panel-profile grid-view " style="background-color:#75c9f1;">
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
                        <li><a href="javascript:;" style="color: blue;" onclick="load_tab(this,'update_leaves',<?= $value->Id; ?>,'leave_tabs_body','<?= $leave_rec_id; ?>')"> <i class="fa fa-pencil"></i> Edit </a></li>
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
	              		<th class="available-leaves">Total Paid Leaves:</th>
	              		<td><?= $paid_leaves; ?></td>
	              	</tr>
	              	<tr>
	              		<th class="available-leaves">Total Unpaid Leaves:</th>
	              		<td><?= $unpaid_leaves; ?></td>
	              	</tr> 
	              </table>
	            </div>
	            <div class="info-group">
	            	<table class="table table-bordered table-inverse nomargin att-table">
		              	<tr>
		              		<th class="consumed-leaves">Consumed Paid Leaves:</th>
		              		<td><?= $consumed_paid_leaves; ?></td>
		              	</tr>
		              	<tr>
		              		<th class="consumed-leaves">Consumed Unpaid Leaves:</th>
		              		<td><?=  $consumed_unpaid_leaves; ?></td>
		              	</tr>  
		            </table> 
	            </div>
	            <div class="info-group">
	            	<table class="table table-bordered table-inverse nomargin att-table">
		              	<tr>
		              		<th class="remaining-leaves">Available Paid Leaves:</th>
		              		<td><?= $paid_leaves - $consumed_paid_leaves; ?></td>
		              	</tr>
		              	<tr>
		              		<th class="remaining-leaves">Available Unpaid Leaves:</th>
		              		<td><?= $unpaid_leaves - $consumed_unpaid_leaves; ?></td>
		              	</tr>   
		            </table> 
	            </div>
	            
	          </div> 
       		</div> 
      	</div>

      	 
      <?php }}else{ echo no_record_found(); } ?>	 

      	 
 
    </div>
 
 <script type="text/javascript">
  $('.select2').select2(); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' }); 
   
</script>