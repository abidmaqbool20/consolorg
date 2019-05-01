
<?php
  
  $record_data = array();
  if($data != "")
  {
    $check_record = $this->db->get_where("app_plans",array("Deleted"=>0,"Id"=>$data));
    if($check_record->num_rows() > 0)
    {
      $record_data = $check_record->result_array();
      $record_data = $record_data[0];
    }

  }
?>
  <div class="mainpanel">
  <div class="contentpanel">
    <div class="panel">
      <div class="panel-body">
        <div class="col-md-12">
          <div class="col-md-6">
            <h3><i class="fa fa-user"></i> Save / Update Plan</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'app_plans')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
    <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
       <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id'])){ echo $record_data['Id']; } ?>">
       <input type="hidden" name="Table_Name" id="Table_Name" value="app_plans">
      <div class="panel">
        <div class="panel-heading nopaddingbottom">
          <h4 class="panel-title">Plan Information</h4> 
        </div>
        <div class="panel-body nopaddingtop">
          <hr>
          <div class="error"></div>
          <div class="form-group"> 
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Select Organization Role <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select name="Role_Id" id="Role_Id" value="<?php if(isset($record_data['Role_Id'])){ echo $record_data['Role_Id']; } ?>" class="form-control required select2">
                <?php 
                  $this->db->order_by("id","asc");
                  $organization_roles = $this->db->get_where("organization_roles");
                  if($organization_roles->num_rows() > 0)
                  {
                    foreach ($organization_roles->result() as $key => $value) 
                    {
                      if(isset($record_data['Role_Id'])){ if($record_data['Role_Id'] == $value->Id ){ $selected = "selected='selected'"; }else{ $selected = ""; }}
                       echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                    }
                  }
                 ?>
                
              </select>
            </div>
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Plan Name <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Name" id="Name" value="<?php if(isset($record_data['Name'])){ echo $record_data['Name']; } ?>" class="form-control required" placeholder="Type module name..." required="" aria-required="true">
            </div> 
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Plan Price <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="number" name="Price" id="Price" value="<?php if(isset($record_data['Price'])){ echo $record_data['Price']; } ?>" class="form-control required" placeholder="Type price..." required="" aria-required="true">
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Price Currency <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select name="Currency" id="Currency" value="<?php if(isset($record_data['Currency'])){ echo $record_data['Currency']; } ?>" class="form-control required select2">
                <option value="PKR" <?php if(isset($user_data['Currency'])){ if($user_data['Currency'] == "PKR"){ echo "selected='selected'"; }} ?>>PKR</option>
                <option value="US$" <?php if(isset($user_data['Currency'])){ if($user_data['Currency'] == "US$"){ echo "selected='selected'"; }} ?>>US$</option>
              </select>
            </div> 
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Plan Duration <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="number" name="Duration" id="Duration" value="<?php if(isset($record_data['Duration'])){ echo $record_data['Duration']; } ?>" class="form-control required" placeholder="Type duration" required="" aria-required="true">
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Duration Unit <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select name="Duration_Unit" id="Duration_Unit" value="<?php if(isset($record_data['Duration_Unit'])){ echo $record_data['Duration_Unit']; } ?>" class="form-control required select2">
                <option value="Month" <?php if(isset($user_data['Duration_Unit'])){ if($user_data['Duration_Unit'] == "Month"){ echo "selected='selected'"; }} ?>>Month</option>
                <option value="Year" <?php if(isset($user_data['Duration_Unit'])){ if($user_data['Duration_Unit'] == "Year"){ echo "selected='selected'"; }} ?>>Year</option>
              </select>
            </div>
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Storage Limit ( MB ) <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="number" name="Storage_Limit" id="Storage_Limit" value="<?php if(isset($record_data['Storage_Limit'])){ echo $record_data['Storage_Limit']; } ?>" class="form-control required" placeholder="Type storage limit in MB's" required="" aria-required="true">
            </div> 
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Allowed Employees <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="number" name="Employees" id="Employees" value="<?php if(isset($record_data['Employees'])){ echo $record_data['Employees']; } ?>" class="form-control required" placeholder="Type Employees Number" required="" aria-required="true">
            </div>
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Allowed Job Posts <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="number" name="Job_Posts" id="Job_Posts" value="<?php if(isset($record_data['Job_Posts'])){ echo $record_data['Job_Posts']; } ?>" class="form-control required" placeholder="Type Job Posts" required="" aria-required="true">
            </div> 
            <div class="col-sm-2  col-xs-12">
              <label class="control-label">Plan Status <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select name="Status" id="Status" value="<?php if(isset($record_data['Status'])){ echo $record_data['Status']; } ?>" class="form-control required select2">
                <option value="1" <?php if(isset($user_data['Status'])){ if($user_data['Status'] == "1"){ echo "selected='selected'"; }} ?>>Active</option>
                <option value="0" <?php if(isset($user_data['Status'])){ if($user_data['Status'] == "0"){ echo "selected='selected'"; }} ?>>Blocked</option>
              </select>
            </div>
            <div class="col-sm-2  col-xs-12">
              <label class="control-label">Default Plan <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select name="Default_Plan" id="Default_Plan" value="<?php if(isset($record_data['Default_Plan'])){ echo $record_data['Default_Plan']; } ?>" class="form-control required select2">
                <option value="1" <?php if(isset($user_data['Default_Plan'])){ if($user_data['Default_Plan'] == "1"){ echo "selected='selected'"; }} ?>>Yes</option>
                <option value="0" <?php if(isset($user_data['Default_Plan'])){ if($user_data['Default_Plan'] == "0"){ echo "selected='selected'"; }} ?>>No</option>
              </select>
            </div>
            <div class="col-sm-2  col-xs-12">
              <label class="control-label">Show In Customer Panel <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select name="Show_In_Customer_Panel" id="Show_In_Customer_Panel" value="<?php if(isset($record_data['Show_In_Customer_Panel'])){ echo $record_data['Show_In_Customer_Panel']; } ?>" class="form-control required select2">
                <option value="1" <?php if(isset($user_data['Show_In_Customer_Panel'])){ if($user_data['Show_In_Customer_Panel'] == "1"){ echo "selected='selected'"; }} ?>>Yes</option>
                <option value="0" <?php if(isset($user_data['Show_In_Customer_Panel'])){ if($user_data['Show_In_Customer_Panel'] == "0"){ echo "selected='selected'"; }} ?>>No</option>
              </select>
            </div>
            
          </div>
        </div>
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="">
            <button style="float: left;" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
            <button style="float: right; margin-left: 5px;"  onclick="load_view(this,'app_plans','','tab_container');" type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            <button  style="float: right;"  onclick="load_view(this,'form_plan')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
          </div>
        </div>
      </div>
    </form>
 </div>
    </div>

<script type="text/javascript">
  $('.select2').select2();
</script>