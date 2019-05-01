
<?php
  
  $module_data = array();
  if($data != "")
  {
    $check_module = $this->db->get_where("application_modules",array("Deleted"=>0,"Id"=>$data));
    if($check_module->num_rows() > 0)
    {
      $module_data = $check_module->result_array();
      $module_data = $module_data[0];
    }

  }
?>
<div class="mainpanel">
  <div class="contentpanel">
    <div class="panel">
      <div class="panel-body">
        <div class="col-md-12">
          <div class="col-md-6">
            <h3><i class="fa fa-user"></i> Save / Update Module</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'modules')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
    <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
       <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($module_data['Id'])){ echo $module_data['Id']; } ?>">
       <input type="hidden" name="Table_Name" id="Table_Name" value="application_modules">
      <div class="panel">
        <div class="panel-heading nopaddingbottom">
          <h4 class="panel-title">Module Information</h4>
           
        </div>
        <div class="panel-body nopaddingtop">
          <hr>
          <div class="error"></div>
          <div class="form-group">
            <div class="col-sm-3 col-xs-12">
              <label class="control-label">Module Name <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Name" id="Name" value="<?php if(isset($module_data['Name'])){ echo $module_data['Name']; } ?>" class="form-control required" placeholder="Type module name..." required="" aria-required="true">
            </div>
             <div class="col-sm-3  col-xs-12">
              <label class="control-label">Parent Module <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select id="Parent_Module" name="Parent_Module" class="form-control select2" style="width: 100%" data-placeholder="Select Parent Module">
                  <option value="">&nbsp;</option>
                  <option value="0">No Parent Module</option>
                  <?php 

                    $modules = $this->db->get_where("application_modules",array("Deleted"=>0,"Status"=>1));
                    if($modules->num_rows() > 0)
                    {
                      foreach ($modules->result() as $key => $value) {

                         if(isset($module_data['Parent_Module'] ) && $module_data['Parent_Module'] != ""){ if($module_data['Parent_Module'] == $value->Id){ $selected = 'selected="selected"'; }else{ $selected = ""; } }

                         echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
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
            <button style="float: right; margin-left: 5px;"  onclick="load_view(this,'modules');" type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            <button  style="float: right;"  onclick="load_view(this,'form_module')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
  $('.select2').select2();
</script>