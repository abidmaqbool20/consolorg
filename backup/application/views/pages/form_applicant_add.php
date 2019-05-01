
<?php
  
  $record_data = array();
  if($data != "")
  {
    $check_record = $this->db->get_where("applicants",array("Deleted"=>0,"Id"=>$data,"Org_Id"=>$this->org_id));
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
            <h3><i class="fa fa-user"></i> Save Applicant</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'applicants')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
    <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
       <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id'])){ echo $record_data['Id']; } ?>">
       <input type="hidden" name="Table_Name" id="Table_Name" value="applicants">
       <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>">
      <div class="panel">
        <div class="panel-heading nopaddingbottom">
          <h4 class="panel-title">Applicant Information</h4> 
        </div>
        <div class="panel-body nopaddingtop">
          <hr>
          <div class="error"></div>
          <div class="form-group">
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">First Name <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="First_Name" id="First_Name" value="<?php if(isset($record_data['First_Name'])){ echo $record_data['First_Name']; } ?>" class="form-control required" placeholder="Type first name..." required="" aria-required="true">
            </div>
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Last Name <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Last_Name" id="Last_Name" value="<?php if(isset($record_data['Last_Name'])){ echo $record_data['Last_Name']; } ?>" class="form-control required" placeholder="Type last name..." required="" aria-required="true">
            </div>
            
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Email Address<span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Email" id="Email" value="<?php if(isset($record_data['Email'])){ echo $record_data['Email']; } ?>" class="form-control required" placeholder="Type organizer name..." required="" aria-required="true">
            </div>
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Password <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Password" id="Password" value="<?php if(isset($record_data['Password'])){ echo $record_data['Password']; } ?>" class="form-control required" placeholder="Type last name..." required="" aria-required="true">
            </div>
          </div>
        </div>
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="">
            <button style="float: left;" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
            <button style="float: right; margin-left: 5px;"  onclick="load_view(this,'applicants');" type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            <button  style="float: right;"  onclick="load_view(this,'form_applicant_add')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
  $('.select2').select2(); 
</script>