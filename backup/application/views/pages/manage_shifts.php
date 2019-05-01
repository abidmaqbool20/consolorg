<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Manage Shifts","Deleted"=>0,"Status"=>1));
  if($module_data->num_rows() > 0)
  {
    $module_info = $module_data->result_array();
    $module_id = $module_info[0]['Id'];
  }
 
 ?>
 


<div class="mainpanel"> 
  <div class="contentpanel"> 
   <div class="panel"> 
      <div class="panel-body">
          <div class="col-md-12">
            <div class="col-lg-9 col-md-8 col-sm-8">
              <h3><i class="fa fa-cubes"></i> Manage Shifts</h3>
            </div> 
            
            <div class="col-lg-2 col-md-2 col-sm-2"> 
              
            </div>
            <div class="col-lg-1 col-md-2 col-sm-2">
              <?php if(in_array($module_id."_add", $this->role_permissions)){ ?>
              <button onclick="load_view(this,'form_shifts')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add New</button> 
            <?php } ?>
            </div>
          </div>
      </div>
    </div>

    <div class="panel"> 
      <div class="panel-body">
        <div class=""> 
          <div class="row">
            <?php

                $this->db->where(array("shifts.Deleted"=>0,"shifts.Status"=>1,"shifts.Org_Id"=>$this->org_id));
                $this->db->select("
                                    shifts.*, 
                                    employees.First_Name as Incharge_First_Name,
                                    employees.Last_Name as Incharge_Last_Name,
                                    addedby.First_Name as Addedby_FirstName,
                                    addedby.Last_Name as Addedby_LastName,
                                    updatedby.First_Name as Updatedby_FirstName,
                                    updatedby.Last_Name as Updatedby_LastName,
                                    COUNT(shift_employees.Shift_Id) as Total_Employees,
                                  ");

                $this->db->from("shifts"); 
                $this->db->join("employees","employees.Id = shifts.Shift_Incharge","left");
                $this->db->join("employees as addedby","addedby.Id = shifts.Added_By","left");
                $this->db->join("employees as updatedby","updatedby.Id = shifts.Modified_By","left"); 
                $this->db->join("shift_employees","shift_employees.Shift_Id = shifts.Id","left"); 
                $this->db->order_by("Id","DESC");
                $this->db->group_by(array("shift_employees.Shift_Id", "employees.Id"));
                $shifts = $this->db->get();

                if($shifts->num_rows() > 0)
                {
                  foreach ($shifts->result() as $key => $value) 
                  { 
                   
            ?>
            <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12" id="row_<?= $value->Id; ?>">
              <div class="panel org_role">
                <div class="panel-heading">
                  <h4 class="panel-title" style="font-size: 18px;"><?= $value->Name; ?></h4> 
                  <h5 style="color: darkmagenta;"><?= $value->Total_Employees; ?> Employees</5>
                  <h5><?= $value->Start_Time." TO ".$value->End_Time; ?></5>
                </div>
                <div class="panel-body"> 
                  <?php if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                  <button onclick="load_view(this,'form_shifts','<?= $value->Id; ?>')" class="btn btn-info btn-stroke btn-icon tooltips mr5" data-toggle="toggle" title="" data-original-title="Edit Role"><i class="fa fa-pencil"></i></button>
                  <?php }if(in_array($module_id."_delete", $this->role_permissions)){ ?>
                  <button onclick="delete_record('shifts','<?= $value->Id; ?>',this)" class="btn btn-danger btn-stroke btn-icon tooltips mr5" data-toggle="toggle" title="" data-original-title="Delete Role"><i class="fa fa-trash"></i></button> 
                  <?php } ?>
                </div>
              </div>
            </div> 
          <?php }}else{ echo no_record_found(); } ?>
          </div> 
        </div>
      </div>
    </div> 

   

  </div> 
</div>

 

 
  <script type="text/javascript">
    $('#dataTable1').DataTable();
  </script>