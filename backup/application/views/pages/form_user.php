
<?php
  
  $user_data = array();
  if($data != "")
  {
    $check_user = $this->db->get_where("users",array("Deleted"=>0,"Id"=>$data));
    if($check_user->num_rows() > 0)
    {
      $user_data = $check_user->result_array();
      $user_data = $user_data[0];
    }

  }
?>
<div class="mainpanel">
  <div class="contentpanel">
    <div class="panel">
      <div class="panel-body">
        <div class="col-md-12">
          <div class="col-md-6">
            <h3><i class="fa fa-user"></i> Save / Update User</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'users-table')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
    <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
       <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($user_data['Id'])){ echo $user_data['Id']; } ?>">
       <input type="hidden" name="Table_Name" id="Table_Name" value="users">
      <div class="panel">
        <div class="panel-heading nopaddingbottom">
          <h4 class="panel-title">User Information</h4>
          <!-- <p>Please provide your name, email address (won't be published) and a comment.</p> --> 
        </div>
        <div class="panel-body nopaddingtop">
          <hr>
          <div class="error"></div>
          <div class="form-group">
            <div class="col-sm-3 col-xs-12">
              <label class="control-label">First Name <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="First_Name" id="First_Name" value="<?php if(isset($user_data['First_Name'])){ echo $user_data['First_Name']; } ?>" class="form-control required" placeholder="Type your full name..." required="" aria-required="true">
            </div>
             <div class="col-sm-3  col-xs-12">
              <label class="control-label">First Name <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Last_Name" id="Last_Name" value="<?php if(isset($user_data['Last_Name'])){ echo $user_data['Last_Name']; } ?>" class="form-control required" placeholder="Type your full name..." required="" aria-required="true">
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Email <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Email" id="Email" value="<?php if(isset($user_data['Email'])){ echo $user_data['Email']; } ?>" class="form-control required" placeholder="Type your email..." required="" aria-required="true">
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Phone  </label>
              <input type="text" name="Phone" id="Phone" value="<?php if(isset($user_data['Phone'])){ echo $user_data['Phone']; } ?>" class="form-control" placeholder="Type your phone..." aria-required="true">
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Mobile <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Mobile" id="Mobile" value="<?php if(isset($user_data['Mobile'])){ echo $user_data['Mobile']; } ?>" class="form-control required" placeholder="Type your mobile..." required="" aria-required="true">
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Address <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Address" id="Address" value="<?php if(isset($user_data['Address'])){ echo $user_data['Address']; } ?>" class="form-control required" placeholder="Type your address..." required="" aria-required="true">
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Profile Picture  </label>
              <input type="file" name="Image" id="Image" class="form-control" aria-required="true">
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Gender <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select name="Gender" id="Gender" value="<?php if(isset($user_data['Gender'])){ echo $user_data['Gender']; } ?>" class="form-control required">
                <option value="Male" <?php if(isset($user_data['Gender'])){ if($user_data['Gender'] == "Male"){ echo "selected='selected'"; }} ?>>Male</option>
                <option value="FeMale" <?php if(isset($user_data['Gender'])){ if($user_data['Gender'] == "FeMale"){ echo "selected='selected'"; }} ?>>FeMale</option>
              </select>
            </div>
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Password <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="password" name="Password" id="Password" value="<?php if(isset($user_data['Password'])){ echo $user_data['Password']; } ?>" class="form-control required" placeholder="Type your password..." required="" aria-required="true">
            </div>
            
          </div>
        </div>
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="">
            <button style="float: left;" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
            <button style="float: right; margin-left: 5px;"  onclick="load_view(this,'users-table');" type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            <button  style="float: right;"  onclick="load_view(this,'form_user')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
