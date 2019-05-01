
<?php
  
  $policy_data = array();
  if($data != "")
  {
    $check_policy = $this->db->get_where("organization_policies",array("Deleted"=>0,"Id"=>$data,"Org_Id"=>$this->org_id));
    if($check_policy->num_rows() > 0)
    {
      $policy_data = $check_policy->result_array();
      $policy_data = $policy_data[0];
    }

  }
?>
<div class="mainpanel">
  <div class="contentpanel">
    <div class="panel">
      <div class="panel-body">
        <div class="col-md-12">
          <div class="col-md-6">
            <h3><i class="fa fa-user"></i> Save / Update Policy</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'policies_table')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
    <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
       <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($policy_data['Id'])){ echo $policy_data['Id']; } ?>">
       <input type="hidden" name="Table_Name" id="Table_Name" value="organization_policies">
       <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>">
      <div class="panel">
        <div class="panel-heading nopaddingbottom">
          <h4 class="panel-title">Department Information</h4>
           
        </div>
        <div class="panel-body nopaddingtop">
          <hr>
          <div class="error"></div>
          <div class="form-group">
            <div class="col-sm-12 col-xs-12">
              <label class="control-label">Policy Title <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Title" id="Title" value="<?php if(isset($policy_data['Title'])){ echo $policy_data['Title']; } ?>" class="form-control required" placeholder="Type policy title..." required="" aria-required="true">
            </div>
            <div class="col-sm-12 col-xs-12">
              <label class="control-label">Policy Description <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <textarea id="Policy" name="Policy" class="form-control required" required=""><?php if(isset($policy_data['Policy'])){ echo $policy_data['Policy']; } ?></textarea> 
              <script> CKEDITOR.replace( 'Policy' );</script> 
            </div> 
          </div>
        </div>
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="">
            <button style="float: left;" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
            <button style="float: right; margin-left: 5px;"  onclick="load_view(this,'policies_table');" type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            <button  style="float: right;"  onclick="load_view(this,'form_policy')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
  $('.select2').select2();
  
</script>