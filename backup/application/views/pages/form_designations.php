
<?php
  
  $designation_data = array();
  if($data != "")
  {
    $check_designation = $this->db->get_where("designations",array("Deleted"=>0,"Id"=>$data,"Org_Id"=>$this->org_id));
    if($check_designation->num_rows() > 0)
    {
      $designation_data = $check_designation->result_array();
      $designation_data = $designation_data[0];
    } 
  }
?>
<div class="mainpanel">
  <div class="contentpanel">
    <div class="panel">
      <div class="panel-body">
        <div class="col-md-12">
          <div class="col-md-6">
            <h3><i class="fa fa-user"></i> Save / Update Designation</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'designations_table')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
    <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
       <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($designation_data['Id'])){ echo $designation_data['Id']; } ?>">
       <input type="hidden" name="Table_Name" id="Table_Name" value="designations">
       <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>">
      <div class="panel">
        <div class="panel-heading nopaddingbottom">
          <h4 class="panel-title">Designation Information</h4>
           
        </div>
        <div class="panel-body nopaddingtop">
          <hr>
          <div class="error"></div>
          <div class="form-group">
            
            <div class="col-sm-3  col-xs-12">
              <div class="">
                <label class="control-label">Select Department <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select name="Department_Id" id="Department_Id" onchange="get_institute(this,'Institute')" value="<?php if(isset($record_data['Department_Id'])){ echo $record_data['Department_Id']; } ?>" class="form-control select2 ">
                  <?php 
                    $this->db->order_by("Name","asc");
                    $departments = $this->db->get_where("departments",array("Deleted"=>0,"Org_Id"=>$this->org_id,"Status"=>1));
                    if($departments->num_rows() > 0)
                    {
                      foreach ($departments->result() as $key => $value) 
                      {
                        $selected = "";
                        if(isset($record_data['Department_Id'])){ if($record_data['Department_Id'] == $value->Id ){ $selected = "selected='selected'"; } }
                         echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                      }
                    }
                   ?>
                  
                </select>
              </div>
            </div>
            <div class="col-sm-3  col-xs-12">
              <div class="">
                <label class="control-label">Parent Designation <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select name="Parent_Id" id="Parent_Id" onchange="get_institute(this,'Institute')" value="<?php if(isset($record_data['Parent_Id'])){ echo $record_data['Parent_Id']; } ?>" class="form-control select2 ">
                  <option selected="selected" value="0">Select Designation</option>
                  <?php 
                    $this->db->order_by("Name","asc");
                    $designations = $this->db->get_where("designations",array("Deleted"=>0,"Org_Id"=>$this->org_id,"Status"=>1));
                    if($designations->num_rows() > 0)
                    {
                      foreach ($designations->result() as $key => $value) 
                      {
                        $selected = "";
                        if(isset($record_data['Parent_Id'])){ if($record_data['Parent_Id'] == $value->Id ){ $selected = "selected='selected'"; } }
                         echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                      }
                    }
                   ?>
                  
                </select>
              </div>
            </div> 
            <div class="col-sm-3 col-xs-12">
              <label class="control-label">Designation Name <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Name" id="Name" value="<?php if(isset($designation_data['Name'])){ echo $designation_data['Name']; } ?>" class="form-control required" placeholder="Type module name..." required="" aria-required="true">
            </div> 


          </div>

        </div>
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="">
            <button style="float: left;" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
            <button style="float: right; margin-left: 5px;"  onclick="load_view(this,'designations_table');" type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            <button  style="float: right;"  onclick="load_view(this,'form_designations')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
  $('.select2').select2();
</script>