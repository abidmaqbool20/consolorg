 
<?php
  
  $benefit_type_data = array();
  if($data != "")
  {
    $check_benefit_type = $this->db->get_where("benefit_types",array("Deleted"=>0,"Id"=>$data,"Org_Id"=>$this->org_id));
    if($check_benefit_type->num_rows() > 0)
    {
      $benefit_type_data = $check_benefit_type->result_array();
      $benefit_type_data = $benefit_type_data[0];
    }

  }
?>
<div class="mainpanel">
  <div class="contentpanel">
    <div class="panel">
      <div class="panel-body">
        <div class="col-md-12">
          <div class="col-md-6">
            <h3><i class="fa fa-user"></i> Save / Update Benefit Type</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'manage_benefit_types')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
    <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
       <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($benefit_type_data['Id'])){ echo $benefit_type_data['Id']; } ?>">
       <input type="hidden" name="Table_Name" id="Table_Name" value="benefit_types">
       <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>">
      <div class="panel">
        <div class="panel-heading nopaddingbottom">
          <h4 class="panel-title">Benefit Type Information</h4>
           
        </div>
        <div class="panel-body nopaddingtop">
          <hr>
          <div class="error"></div>
          <div class="form-group">
            <div class="col-sm-12 col-xs-12">
              <label class="control-label">Benefit Type Name <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Name" id="Name" value="<?php if(isset($benefit_type_data['Name'])){ echo $benefit_type_data['Name']; } ?>" class="form-control required" placeholder="Type module name..." required="" aria-required="true">
            </div>
             
          </div>
        </div>
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="">
            <button style="float: left;" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
            <button style="float: right; margin-left: 5px;"  onclick="load_view(this,'manage_benefit_types');" type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            <button  style="float: right;"  onclick="load_view(this,'form_benefit_types')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
  $('.select2').select2();
</script>