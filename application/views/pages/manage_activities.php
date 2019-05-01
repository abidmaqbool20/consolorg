<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Manage Activities","Deleted"=>0,"Status"=>1));
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
              <h3><i class="fa fa-users"></i> Manage Employee Activities</h3>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-3"> </div>
            <div class="col-lg-2 col-md-2 col-sm-2"> </div>
            <div class="col-lg-1 col-md-2 col-sm-2">
              <button onclick="load_view(this,'form_employee_activity')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add New</button>
            </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <form method="post" action="<?= base_url("admin/get_employee_activities"); ?>" onsubmit="return get_employee_activities(this)">
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
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Get Activities History</button>
            </div>
            <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12"> </div>
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"></div>
          </form>
        </div>
      </div>
    </div>
    <div class="panel">
      <div class="panel-body">
        <div class="row" id="employee_activities">
          <?php 
		 
		  			$employee_activities = $this->db->select(" employee_activities.*,employees.First_Name,employees.Last_Name, employees.Photo,activity_types.Name as  Activity_Type_Name,departments.Name as Department_Name  ");
		  			$this->db->where( array("employee_activities.Deleted"=>0,"employee_activities.Status"=>1,"employee_activities.Org_Id"=>$this->org_id));
		  			$this->db->from("employee_activities");  
		  			$this->db->join("employee_work_record","employee_work_record.Employee_Id = employee_activities.Employee_Id","left");
		  			$this->db->join("departments","departments.Id = employee_work_record.Department_Id","left");
					$this->db->join("activity_types","activity_types.Id = employee_activities.Activity_Type","left");
					$this->db->join("employees","employees.Id = employee_activities.Employee_Id","left"); 
					$this->db->group_by("employee_activities.Id");
		  			$employee_activities = $this->db->get();
		  			//echo $this->db->last_query();

		  			if($employee_activities->num_rows() > 0)
		  			{
		  				foreach ($employee_activities->result() as $key => $value) 
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

			                $time_spent = "";
			                $start_time = date("Y-m-d H:i:s", strtotime($value->Start_Time));
		  					$end_time = date("Y-m-d H:i:s", strtotime($value->End_Time));
		  					$difference = date_difference($start_time,$end_time);
		  					if( $difference['Hour'] > 0){ $time_spent =  $difference['Hour']." Hour "; }
			  			    if( $difference['Minuts'] > 0 && $time_spent != ""){ $time_spent .=  $difference['Minuts']." Minutes "; }else{ $time_spent =  $difference['Minuts']." Minutes ";}  
		  					 

		  		?>
          <div class="col-md-3 col-lg-2 col-sm-4 col-xs-12 activity_rec" id="row_<?= $value->Id; ?>"   >
            <div class="panel panel-profile grid-view " style="background-color:#75c9f1;">
              <div class="panel-heading">
                <div class="text-center">
                  <div style="width: 80px; margin:0 auto;"> <a href="#" onclick="open_modal_window(this,'employee_view','<?= $value->Employee_Id; ?>')" class="panel-profile-photo"> <img class="img-circle" src="<?= $src; ?>" style="width: 100%;" alt=""> </a> </div>
                  <a href="javascript:;"  onclick="open_modal_window(this,'employee_view','<?= $value->Employee_Id; ?>')">
                  <h4 class="panel-profile-name" >
                    <?= $value->First_Name." ".$value->Last_Name; ?>
                  </h4>
                  </a>
                  <p class="media-usermeta" style="color: #fff;">
                    <?= $value->Department_Name; ?>
                  </p>
                </div>
                <ul class="panel-options">
                  <li>
                    <div class="btn-group">
                      <button type="button" class="dropdown-toggle action-dropdown" style="font-size: 8px;" data-toggle="dropdown"><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i></button>
                      <ul class="dropdown-menu" role="menu">
                        <?php if(in_array($module_id."_view", $this->role_permissions)){ ?>
                        <li><a href="javascript:;" style="color: green;" onclick="open_modal_window(this,'employee_view','<?= $value->Employee_Id; ?>')"> <i class="fa fa-eye"></i> Employee Profile </a></li>
                        <?php }if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                        <li><a href="javascript:;" style="color: blue;" onclick="load_view(this,'form_employee_activity',<?= $value->Id; ?>)"> <i class="fa fa-pencil"></i> Edit </a></li>
                        <?php }if(in_array($module_id."_delete", $this->role_permissions)){ ?>
                        <li><a href="javascript:;" style="color: red;" onclick="delete_record('employee_activities','<?= $value->Id; ?>',this)"> <i class="fa fa-trash"></i> Delete </a></li>
                        <?php } ?>
                      </ul>
                    </div>
                  </li>
                </ul>
              </div>
              <div class="panel-body people-info" style="min-height: 150px;">
                <div class="info-group">
                  <table class="table table-bordered table-inverse nomargin att-table">
                    <tr>
                      <th class="" style="color: #a01a90 !important;"><?= $value->Activity_Type_Name; ?></th>
                    </tr>
                    <tr>
                      <td style="text-align: left;"><?= $value->Activity; ?></td>
                    </tr>
                  </table>
                </div>
                <div class="info-group">
                  <table class="table table-bordered table-inverse nomargin att-table">
                    <tr>
                      <th >Date:
                        <?= date("l M d, Y",strtotime($value->Activity_Date)); ?></th>
                    </tr>
                    <tr>
                      <th ><span style="color: #a01a90;">Time Spent:</span> <span style="color: #1206ca;">
                        <?= $time_spent; ?>
                        </span> </th>
                    </tr>
                  </table>
                </div>
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