
<?php
  
  $department_data = array();
  if($data != "")
  {
    $check_department = $this->db->get_where("departments",array("Deleted"=>0,"Id"=>$data,"Org_Id"=>$this->org_id));
    if($check_department->num_rows() > 0)
    {
      $department_data = $check_department->result_array();
      $department_data = $department_data[0];
    }

  }
?>
<div class="mainpanel">
  <div class="contentpanel">
    <div class="panel">
      <div class="panel-body">
        <div class="col-md-12">
          <div class="col-md-6">
            <h3><i class="fa fa-user"></i> Save / Update Department</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'departments_table')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
    <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
       <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($department_data['Id'])){ echo $department_data['Id']; } ?>">
       <input type="hidden" name="Table_Name" id="Table_Name" value="departments">
       <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>">
      <div class="panel">
        <div class="panel-heading nopaddingbottom">
          <h4 class="panel-title">Department Information</h4>
           
        </div>
        <div class="panel-body nopaddingtop">
          <hr>
          <div class="error"></div>
          <div class="form-group">
            <div class="col-sm-3 col-xs-12">
              <label class="control-label">Department Name <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Name" id="Name" value="<?php if(isset($department_data['Name'])){ echo $department_data['Name']; } ?>" class="form-control required" placeholder="Type module name..." required="" aria-required="true">
            </div>
             <div class="col-sm-3  col-xs-12">
              <label class="control-label">Head Of Department <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select id="Manager_Id" name="Manager_Id" class="form-control select2" style="width: 100%" data-placeholder="Select Parent Module">
                  <option value="">&nbsp;</option>
                  <option value="0">Select Employee</option>
                  <?php  
                    $employees = $this->db->get_where("employees",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                    if($employees->num_rows() > 0)
                    {
                      foreach ($employees->result() as $key => $value) 
                      { 
                         if(isset($department_data['Manager_Id'] ) && $department_data['Manager_Id'] != ""){ if($department_data['Manager_Id'] == $value->Id){ $selected = 'selected="selected"'; }else{ $selected = ""; } }

                         echo '<option '.$selected.' value="'.$value->Id.'">'.$value->First_Name.''.$value->Last_Name.'</option>';
                      }
                    }
                  ?>
                  
              </select>
            </div>
           
          
          </div>
        </div>
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="">
            <button style="float: left;" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
            <button style="float: right; margin-left: 5px;"  onclick="load_view(this,'departments_table');" type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            <button  style="float: right;"  onclick="load_view(this,'form_department')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
  $('.select2').select2();
</script>