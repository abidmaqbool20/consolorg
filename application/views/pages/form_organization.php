
<?php
  
  $organization_data = array();
  if($data != "")
  {
    $check_organizations = $this->db->get_where("organizations",array("Deleted"=>0,"Id"=>$data));
    if($check_organizations->num_rows() > 0)
    {
      $organization_data = $check_organizations->result_array();
      $organization_data = $organization_data[0];
    }

  }
?>
<div class="mainpanel">
  <div class="contentpanel">
    <div class="panel">
      <div class="panel-body">
        <div class="col-md-12">
          <div class="col-md-6">
            <h3><i class="fa fa-user"></i>  Update Organization</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'organization_view')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
    <form id="common_form" method="post" action="<?= base_url("admin/save_organization") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
       <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($organization_data['Id'])){ echo $organization_data['Id']; } ?>">
       <input type="hidden" name="Table_Name" id="Table_Name" value="organizations">
      <div class="panel">
        <div class="panel-heading nopaddingbottom">
          <h4 class="panel-title">Organization Information</h4> 
        </div>
        <div class="panel-body nopaddingtop">
          <hr>
          <div class="error"></div>
          <div class="form-group">
            <div class="col-sm-3 col-xs-12">
              <label class="control-label">Name <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Name" id="Name" value="<?php if(isset($organization_data['Name'])){ echo $organization_data['Name']; } ?>" class="form-control required" placeholder="Type organization name..." required="" >
            </div>
             <div class="col-sm-3  col-xs-12">
              <label class="control-label">Account Name <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Account_Name" id="Account_Name" value="<?php if(isset($organization_data['Account_Name'])){ echo $organization_data['Account_Name']; } ?>" class="form-control required" placeholder="Type account name..." required="" >
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Primary Email <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Primary_Email" id="Primary_Email" value="<?php if(isset($organization_data['Primary_Email'])){ echo $organization_data['Primary_Email']; } ?>" class="form-control required" placeholder="Type your primary email..." required="" >
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Secondary Email <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Secondary_Email" id="Secondary_Email" value="<?php if(isset($organization_data['Secondary_Email'])){ echo $organization_data['Secondary_Email']; } ?>" class="form-control " placeholder="Type your secondary email..."  >
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Primary Phone  </label>
              <input type="text" name="Primary_Phone" id="Primary_Phone" value="<?php if(isset($organization_data['Primary_Phone'])){ echo $organization_data['Primary_Phone']; } ?>" class="form-control required" required="" placeholder="Type primary phone..." >
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Secondary Phone  </label>
              <input type="text" name="Secondary_Phone" id="Secondary_Phone" value="<?php if(isset($organization_data['Secondary_Phone'])){ echo $organization_data['Secondary_Phone']; } ?>" class="form-control" placeholder="Type secondary phone..." >
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Primary Mobile <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Primary_Mobile" id="Primary_Mobile" value="<?php if(isset($organization_data['Primary_Mobile'])){ echo $organization_data['Primary_Mobile']; } ?>" class="form-control required" placeholder="Type your primary mobile..." required="" >
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Secondary Mobile <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Secondary_Mobile" id="Secondary_Mobile" value="<?php if(isset($organization_data['Secondary_Mobile'])){ echo $organization_data['Secondary_Mobile']; } ?>" class="form-control " placeholder="Type your secondary mobile..."  >
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Country <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select name="Country" id="Country" onchange="get_states(this,'State','<?php if(isset($organization_data['State'])){ echo $organization_data['State']; } ?>')" value="<?php if(isset($organization_data['Country'])){ echo $organization_data['Country']; } ?>" class="form-control select2 required">
                <?php 
                  $this->db->order_by("id","asc");
                  $countries = $this->db->get_where("countries");
                  if($countries->num_rows() > 0)
                  {
                    foreach ($countries->result() as $key => $value) 
                    {
                      if(isset($organization_data['Country'])){ if($organization_data['Country'] == $value->id ){ $selected = "selected='selected'"; }else{ $selected = ""; }}
                       echo '<option '.$selected.' value="'.$value->id.'">'.$value->name.'</option>';
                    }
                  }
                 ?>
                
              </select>
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">State <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select name="State" id="State"  onchange="get_cities(this,'City','<?php if(isset($organization_data['City'])){ echo $organization_data['City']; } ?>')" value="<?php if(isset($organization_data['State'])){ echo $organization_data['State']; } ?>" class="form-control select2 required">
               
               <option>Select State</option> 
              </select>
            </div>
             <div class="col-sm-6  col-xs-12">
              <label class="control-label">City <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select name="City" id="City"   value="<?php if(isset($organization_data['City'])){ echo $organization_data['City']; } ?>" class="form-control required select2">
               
               <option>Select City</option> 
              </select>
            </div>
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Address <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Address" id="Address" value="<?php if(isset($organization_data['Address'])){ echo $organization_data['Address']; } ?>" class="form-control required" placeholder="Type your address..." required="" >
            </div>
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Zipcode <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Zipcode" id="Zipcode" value="<?php if(isset($organization_data['Zipcode'])){ echo $organization_data['Zipcode']; } ?>" class="form-control required" placeholder="Type your zipcode..." required="" >
            </div>
           
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Logo  </label>
              <input type="file" name="Logo" id="Logo" class="form-control" >
            </div>
             
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Domain <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Domain" id="Domain" value="<?php if(isset($organization_data['Domain'])){ echo $organization_data['Domain']; } ?>" class="form-control required" placeholder="Type your password..." required="" >
            </div>
            <div class="col-sm-12 col-xs-12">
              <label class="control-label">Summery <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <textarea type="text" rows="6" name="Summery" id="Summery"  class="form-control " placeholder="Type organization summery..."   ><?php if(isset($organization_data['Summery'])){ echo $organization_data['Summery']; } ?></textarea>
            </div>
          </div>
        </div>
      </div>
      
      <div class="panel">
        <div class="panel-body">
          <div class="">
            <button style="float: left;" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
            <button style="float: right; margin-left: 5px;"  onclick="load_view(this,'organization_view');" type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            <button  style="float: right;"  onclick="load_view(this,'form_organization')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
  $('.datepicker').datepicker();
  $('.select2').select2();
</script>