
 
<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Departments","Deleted"=>0,"Status"=>1));
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
              <h3><i class="fa fa-cubes"></i> Manage Departments</h3>
            </div> 
            
            <div class="col-lg-2 col-md-2 col-sm-2"> 
              <div class="btn-group pull-right people-pager">
                <button class="btn btn-primary-active"  onclick="load_view(this,'departments_table');"><i class="fa fa-th"></i></button>
                <a href="javascript:;" class="btn btn-default"  onclick="load_view(this,'departments_list');"><i class="fa fa-th-list"></i></a>
              </div>
            </div>
            <div class="col-lg-1 col-md-2 col-sm-2">
              <?php if(in_array($module_id."_add", $this->role_permissions)){ ?>
              <button onclick="load_view(this,'form_department')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add New</button> 
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

                $this->db->where(array("departments.Deleted"=>0,"departments.Org_Id"=>$this->org_id));
                $this->db->select("
                                    departments.*,
                                    employees.First_Name as Employee_FirstName,
                                    employees.Last_Name as Employee_LastName,
                                    addedby.First_Name as Addedby_FirstName,
                                    addedby.Last_Name as Addedby_LastName,
                                    updatedby.First_Name as Updatedby_FirstName,
                                    updatedby.Last_Name as Updatedby_LastName
                                  ");

                $this->db->from("departments");
                $this->db->join("employees","employees.Id = departments.Manager_Id","left");
                $this->db->join("users as addedby","addedby.Id = departments.Added_By","left");
                $this->db->join("users as updatedby","updatedby.Id = departments.Modified_By","left"); 
                $this->db->order_by("Id","DESC");
                $departments = $this->db->get();

                if($departments->num_rows() > 0)
                {
                  foreach ($departments->result() as $key => $value) 
                  { 
                   
            ?>
            <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12" id="row_<?= $value->Id; ?>">
              <div class="panel org_role">
                <div class="panel-heading">
                  <h4 class="panel-title" style="font-size: 18px;"><?= $value->Name; ?></h4>
                  <?php if($value->Employee_FirstName != "" ){ echo '<p><i class="fa fa-user"></i>'.$value->Employee_FirstName." ".$value->Employee_LastName.'</p>'; } ?> 
                </div>
                <div class="panel-body"> 
                  <?php if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                  <button onclick="load_view(this,'form_department','<?= $value->Id; ?>')" class="btn btn-info btn-stroke btn-icon tooltips mr5" data-toggle="toggle" title="" data-original-title="Edit Role"><i class="fa fa-pencil"></i></button>
                  <?php }if(in_array($module_id."_delete", $this->role_permissions)){ ?>
                  <button onclick="delete_record('departments','<?= $value->Id; ?>',this)" class="btn btn-danger btn-stroke btn-icon tooltips mr5" data-toggle="toggle" title="" data-original-title="Delete Role"><i class="fa fa-trash"></i></button> 
                  <?php } ?>
                </div>
              </div>
            </div> 
          <?php }}else{ echo no_record_found(); }  ?>
          </div> 
        </div>
      </div>
    </div> 

   

  </div><!-- contentpanel -->
</div>

 