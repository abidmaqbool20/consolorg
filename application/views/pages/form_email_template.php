
<?php
  
  $record_data = array();
  if($data != "")
  {
    $check_record = $this->db->get_where("email_templates",array("Deleted"=>0,"Id"=>$data,"Org_Id"=>$this->org_id));
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
            <h3><i class="fa fa-user"></i> Save / Update Email Templates</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'email_templates_table')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
    <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
       <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id'])){ echo $record_data['Id']; } ?>">
       <input type="hidden" name="Table_Name" id="Table_Name" value="email_templates">
       <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>">
      <div class="panel">
        <div class="panel-heading nopaddingbottom">
          <h4 class="panel-title">Email Template Information</h4>
           
        </div>
        <div class="panel-body nopaddingtop">
          <hr>
          <div class="error"></div>
          <div class="form-group">
            
            <!--  <div class="col-sm-6  col-xs-12">
              <label class="control-label">Template Type <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select id="Type" name="Type" class="form-control select2" value="<?php if(isset($record_data['Type'])){ echo $record_data['Type']; } ?>" style="width: 100%" data-placeholder="Select Template Type">
                  <option value="">&nbsp;</option>
                  <option value="Welcome New Employee" <?php if(isset($record_data['Type'])){ if($record_data['Type'] == "Welcome New Employee"){ echo "selected='selected'"; } }; ?>>Welcome New Employee</option>
                  <option value="Employee Attendance Warning" <?php if(isset($record_data['Type'])){ if($record_data['Type'] == "Employee Attendance Warning"){ echo "selected='selected'"; } }; ?>>Employee Attendance Warning</option>
                  <option value="Employee Late Warning" <?php if(isset($record_data['Type'])){ if($record_data['Type'] == "Employee Attendance Warning"){ echo "selected='selected'"; } }; ?>>Employee Attendance Warning</option>
 
              </select>
            </div> -->
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Template Name <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Name" id="Name" value="<?php if(isset($record_data['Name'])){ echo $record_data['Name']; } ?>" class="form-control required" placeholder="Type module name..." required="" aria-required="true">
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Available Merge Fields<span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select id="Tables" class="form-control select2" onchange="get_table_fields(this);"  style="width: 100%" data-placeholder="Select Table">
                  <option value="">&nbsp;</option>
                  <option value="applicants">Applicants</option>
                  <option value="job_posts">Job Posts</option>
                  <option value="employees">Employees</option>
                  <option value="departments">Departments</option>
                  <option value="designations">Designations</option>
                  <option value="other fields">Other Fields</option> 
              </select>
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Available Merge Fields<span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select id="Other_Fields" class="form-control select2" onchange="show_field_value(this)" >
                 
              </select>
            </div>
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Field Value <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" id="Field_Value"  class="form-control " readonly="readonly">
            </div>
            <div class="col-sm-12 col-xs-12">
              <label class="control-label">Email Subject <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Subject" id="Subject" value="<?php if(isset($record_data['Subject'])){ echo $record_data['Subject']; } ?>" class="form-control required" placeholder="Type Subject..." required="" aria-required="true">
            </div>
            <div class="col-sm-12 col-xs-12">
              <label class="control-label">Email Message <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <textarea name="Message" id="Message" class="form-control required" required=""><?php if(isset($record_data['Message'])){ echo $record_data['Message']; } ?></textarea>
               <script> CKEDITOR.replace( 'Message' );</script> 
            </div> 
          </div>
        </div>
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="">
            <button style="float: left;" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
            <button style="float: right; margin-left: 5px;"  onclick="load_view(this,'email_templates_table');" type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            <button  style="float: right;"  onclick="load_view(this,'form_email_template')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
  $('.select2').select2();
  function get_table_fields($this)
  {
    $html = "";
    $value = $($this).val();
    if($value != "")
    {
      if($value == "other fields")
      {
        $html = get_other_fields();
      }
      else
      {
        $.post("admin/get_table_fields",{table:$value},function(response){
          $("#Other_Fields").html(response);
        });
      }
    }

    $("#Other_Fields").html($html);
  }

  function get_other_fields()
  {
    return $html = '<optgroup label=""><option value="@@Person performing this action@@">Person performing this action </option> <option value="@@Reporting To@@">Whom ever the loggedin user is reporting to</option></optgroup><optgroup label="Timezone Details"><option value="@@currenttime@@">Current Date &amp; Time</option><option value="@@currentdate@@">Current date</option></optgroup><optgroup label="Company Details"><option value="@@companyCity@@">Company City</option><option value="@@companyFirstAddress@@">Company First Address</option><option value="@@companyLogo@@">Company Logo</option><option value="@@companyName@@">Company Name</option><option value="@@companyState@@">Company State</option><option value="@@companyZipcode@@">Company Zip code</option><option value="@@companyContactPerson@@">Company Contact Person</option><option value="@@companyCountry@@">Company Country</option><option value="@@companyContactNumber@@">Company Contact Number</option><option value="@@companyWebsite@@">Company Website</option>';
  }

  function show_field_value($this)
  {
    $("#Field_Value").val($($this).val());
  }
</script>