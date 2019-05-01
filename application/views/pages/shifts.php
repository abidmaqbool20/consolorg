<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Shifts","Deleted"=>0,"Status"=>1));
  if($module_data->num_rows() > 0)
  {
    $module_info = $module_data->result_array();
    $module_id = $module_info[0]['Id'];
  }
 
 ?>
 
    <div class="row">
      <div class="col-md-12"> 
        <div class="col-lg-11 col-md-10 col-sm-10"></div>
        <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
          <?php if(in_array($module_id."_add", $this->role_permissions)){ ?>
          <button onclick="load_tab(this,'form_shifts',<?= 0; ?>,'shifts_tabs_body')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add New</button> 
          <?php } ?>
        </div>
      </div> 
    </div> 
    <hr> 
    <div class="row">
      <?php 

          $this->db->where(array("shifts.Deleted"=>0,"shifts.Status"=>1 ,"shifts.Org_Id"=>$this->org_id));
          $this->db->select("
                              shifts.*, 
                              employees.First_Name as Incharge_First_Name,
                              employees.Last_Name as Incharge_Last_Name,
                              addedby.First_Name as Addedby_FirstName,
                              addedby.Last_Name as Addedby_LastName,
                              updatedby.First_Name as Updatedby_FirstName,
                              updatedby.Last_Name as Updatedby_LastName,
                              
                            ");

          $this->db->from("shifts"); 
          $this->db->join("employees","employees.Id = shifts.Shift_Incharge","left");
          $this->db->join("employees as addedby","addedby.Id = shifts.Added_By","left");
          $this->db->join("employees as updatedby","updatedby.Id = shifts.Modified_By","left");  
          $this->db->order_by("shifts.Id","DESC");
          $this->db->group_by(array("shifts.Id"));
          $shifts = $this->db->get();

          if($shifts->num_rows() > 0)
          {
            foreach ($shifts->result() as $key => $value) 
            { 
             $this->db->select("COUNT(shift_employees.Shift_Id) as Total_Employees");
             $shift_employees = $this->db->get_where("shift_employees",array("Shift_Id"=>$value->Id,"Deleted"=>0,"Status"=>1))->row()->Total_Employees;
      ?>
      <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12" id="row_<?= $value->Id; ?>">
        <div class="panel org_role">
          <div class="panel-heading">
            <h4 class="panel-title" style="font-size: 18px;"><?= $value->Name; ?></h4>  
            <hr style="border-color: #5bc0de !important;">
            <h5 class="panel-title" style="font-size: 13px;color: #ab8619;">
              <span style=" color: blue; ">S.I</span> : <?= $value->Incharge_First_Name." ".$value->Incharge_Last_Name; ?>
            </h5> 
            <h5 style="color: darkmagenta;"><?= $shift_employees; ?> Employees</5> 
          </div>
          <div class="panel-body"> 
            <?php if(in_array($module_id."_edit", $this->role_permissions)){ ?>
            <button onclick="load_tab(this,'form_shifts',<?= $value->Id; ?>,'shifts_tabs_body')" class="btn btn-info btn-stroke btn-icon tooltips mr5" data-original-title="Edit Shift"><i class="fa fa-pencil"></i></button>
            <?php }if(in_array($module_id."_delete", $this->role_permissions)){ ?>
            <button onclick="delete_record('shifts','<?= $value->Id; ?>',this)" class="btn btn-danger btn-stroke btn-icon tooltips mr5" ><i class="fa fa-trash"></i></button> 
            <?php } ?>
          </div>
        </div>
      </div> 
    <?php }}else{ echo no_record_found(); } ?>
    </div> 
         
  <script type="text/javascript">
    $('#dataTable1').DataTable();
  </script>


