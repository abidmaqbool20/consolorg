

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
<form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
  <div class="panel-body"> 
      <div class="error"></div>
      <div class="form-group">
        
       <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id'])){ echo $record_data['Id']; } ?>">
       <input type="hidden" name="Table_Name" id="Table_Name" value="applicants">
       <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>">
        
        <div class="row">
          <div class="col-md-12">
            <h4>Account Information</h4><hr>
          </div>
        </div>

        <div class="col-sm-6  col-xs-12">
          <label class="control-label">Profile Photo  </label>
          <input type="file" name="Photo" id="Photo" class="form-control" >
        </div>
        <div class="col-sm-6 col-xs-12">
          <label class="control-label">First Name <span class="text-danger">*</span><span class="text-danger error"></span></label>
          <input type="text" name="First_Name" id="First_Name" value="<?php if(isset($record_data['First_Name'])){ echo $record_data['First_Name']; } ?>" class="form-control " placeholder="Type first name..."  >
        </div>
        <div class="col-sm-6 col-xs-12">
          <label class="control-label">Last Name <span class="text-danger">*</span><span class="text-danger error"></span></label>
          <input type="text" name="Last_Name" id="Last_Name" value="<?php if(isset($record_data['Last_Name'])){ echo $record_data['Last_Name']; } ?>" class="form-control " placeholder="Type last name..."  >
        </div>
        <div class="col-sm-6 col-xs-12">
          <label class="control-label">Email Address<span class="text-danger">*</span><span class="text-danger error"></span></label>
          <input type="text" name="Email" id="Email" value="<?php if(isset($record_data['Email'])){ echo $record_data['Email']; } ?>" class="form-control " placeholder="Type email..."  >
        </div>
        <div class="col-sm-6 col-xs-12">
          <label class="control-label">Password <span class="text-danger">*</span><span class="text-danger error"></span></label>
          <input type="text" name="Password" id="Password" value="<?php if(isset($record_data['Password'])){ echo $record_data['Password']; } ?>" class="form-control " placeholder="Type Password..."  >
        </div>
       
        
         
        
 
        <div class="row">
          <div class="col-md-12" style="margin-top: 25px;">
           <h4>Contact Information</h4><hr>
          </div>
        </div> 
        <div class="col-sm-6 col-xs-12">
          <label class="control-label">Phone Number ( Land line )<span class="text-danger">*</span><span class="text-danger error"></span></label>
          <input type="text" name="Phone_Number" id="Phone_Number" value="<?php if(isset($record_data['Phone_Number'])){ echo $record_data['Phone_Number']; } ?>" class="form-control " placeholder="Type phone number..."  >
        </div>
        <div class="col-sm-6 col-xs-12">
          <label class="control-label">Mobile Number 1<span class="text-danger">*</span><span class="text-danger error"></span></label>
          <input type="text" name="Mobile_Number_1" id="Mobile_Number_1" value="<?php if(isset($record_data['Mobile_Number_1'])){ echo $record_data['Mobile_Number_1']; } ?>" class="form-control " placeholder="Type mobile number..."  >
        </div>
        <div class="col-sm-6 col-xs-12">
          <label class="control-label">Mobile Number 2<span class="text-danger">*</span><span class="text-danger error"></span></label>
          <input type="text" name="Mobile_Number_2" id="Mobile_Number_2" value="<?php if(isset($record_data['Mobile_Number_2'])){ echo $record_data['Mobile_Number_2']; } ?>" class="form-control " placeholder="Type mobile number..."  >
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form_employee_address">Current Address</div>
            <div class="col-sm-6  col-xs-12">
                <label class="control-label">Country <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select name="Country" id="Country" onchange="get_states(this,'State','<?php if(isset($record_data['State'])){ echo $record_data['State']; } ?>')" value="<?php if(isset($record_data['Country'])){ echo $record_data['Country']; } ?>" class="form-control select2 ">
                  <?php 
                    $this->db->order_by("id","asc");
                    $countries = $this->db->get_where("countries");
                    if($countries->num_rows() > 0)
                    {
                      foreach ($countries->result() as $key => $value) 
                      {
                        if(isset($record_data['Country'])){ if($record_data['Country'] == $value->id ){ $selected = "selected='selected'"; }else{ $selected = ""; }}
                         echo '<option '.$selected.' value="'.$value->id.'">'.$value->name.'</option>';
                      }
                    }
                   ?>
                  
                </select>
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">State <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select name="State" id="State"  onchange="get_cities(this,'City','<?php if(isset($record_data['City'])){ echo $record_data['City']; } ?>')" value="<?php if(isset($record_data['State'])){ echo $record_data['State']; } ?>" class="form-control select2 "> 
               <option>Select State</option> 
                <?php 
                  if(isset($record_data['State']))
                  {
                    $this->db->order_by("id","asc");
                    $states = $this->db->get_where("states",array("id"=>$record_data['State']));
                    if($states->num_rows() > 0)
                    {
                      foreach ($states->result() as $key => $value) 
                      {
                        if(isset($record_data['State'])){ if($record_data['State'] == $value->id ){ $selected = "selected='selected'"; }else{ $selected = ""; }}
                         echo '<option '.$selected.' value="'.$value->id.'">'.$value->name.'</option>';
                      }
                    }
                  }
                 ?>
              </select>
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">City <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select name="City" id="City" value="<?php if(isset($record_data['City'])){ echo $record_data['City']; } ?>" class="form-control  select2"> 
               <option>Select City</option> 
                <?php 
                  if(isset($record_data['City']))
                  {
                    $this->db->order_by("id","asc");
                    $cities = $this->db->get_where("cities",array("id"=>$record_data['City']));
                    if($cities->num_rows() > 0)
                    {
                      foreach ($cities->result() as $key => $value) 
                      {
                        if(isset($record_data['City'])){ if($record_data['City'] == $value->id ){ $selected = "selected='selected'"; }else{ $selected = ""; }}
                         echo '<option '.$selected.' value="'.$value->id.'">'.$value->name.'</option>';
                      }
                    }
                  }
                 ?>
              </select>
            </div>
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Address <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Address" id="Address" value="<?php if(isset($record_data['Address'])){ echo $record_data['Address']; } ?>" class="form-control " placeholder="Type your address..."  >
            </div>
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Zipcode <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Zipcode" id="Zipcode" value="<?php if(isset($record_data['Zipcode'])){ echo $record_data['Zipcode']; } ?>" class="form-control " placeholder="Type your zipcode..."  >
            </div>
         </div>
       </div>
       <div class="row">
        <div class="col-md-12">
          <div class="form_employee_address">Permanent Address</div>
          <div class="col-sm-6  col-xs-12">
              <label class="control-label">Country <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select name="P_Country" id="P_Country" onchange="get_states(this,'P_State','<?php if(isset($record_data['P_State'])){ echo $record_data['P_State']; } ?>')" value="<?php if(isset($record_data['P_Country'])){ echo $record_data['P_Country']; } ?>" class="form-control select2 ">
                <?php 
                  $this->db->order_by("id","asc");
                  $countries = $this->db->get_where("countries");
                  if($countries->num_rows() > 0)
                  {
                    foreach ($countries->result() as $key => $value) 
                    {
                      if(isset($record_data['P_Country'])){ if($record_data['P_Country'] == $value->id ){ $selected = "selected='selected'"; }else{ $selected = ""; }}
                       echo '<option '.$selected.' value="'.$value->id.'">'.$value->name.'</option>';
                    }
                  }
                 ?>
                
              </select>
          </div>
          <div class="col-sm-6  col-xs-12">
            <label class="control-label">State <span class="text-danger">*</span><span class="text-danger error"></span></label>
            <select name="P_State" id="P_State"  onchange="get_cities(this,'P_City','<?php if(isset($record_data['P_City'])){ echo $record_data['P_City']; } ?>')" value="<?php if(isset($record_data['P_State'])){ echo $record_data['P_State']; } ?>" class="form-control select2 "> 
             <option>Select State</option> 
              <?php 
                  if(isset($record_data['P_State']))
                  {
                    $this->db->order_by("id","asc");
                    $states = $this->db->get_where("states",array("id"=>$record_data['P_State']));
                    if($states->num_rows() > 0)
                    {
                      foreach ($states->result() as $key => $value) 
                      {
                        if(isset($record_data['P_State'])){ if($record_data['P_State'] == $value->id ){ $selected = "selected='selected'"; }else{ $selected = ""; }}
                         echo '<option '.$selected.' value="'.$value->id.'">'.$value->name.'</option>';
                      }
                    }
                  }
                 ?>
            </select>
          </div>
           <div class="col-sm-6  col-xs-12">
            <label class="control-label">City <span class="text-danger">*</span><span class="text-danger error"></span></label>
            <select name="P_City" id="P_City"   value="<?php if(isset($record_data['P_City'])){ echo $record_data['P_City']; } ?>" class="form-control  select2"> 
             <option>Select City</option>
              <?php 
                  if(isset($record_data['P_City']))
                  {
                    $this->db->order_by("id","asc");
                    $cities = $this->db->get_where("cities",array("id"=>$record_data['P_City']));
                    if($cities->num_rows() > 0)
                    {
                      foreach ($cities->result() as $key => $value) 
                      {
                        if(isset($record_data['P_City'])){ if($record_data['P_City'] == $value->id ){ $selected = "selected='selected'"; }else{ $selected = ""; }}
                         echo '<option '.$selected.' value="'.$value->id.'">'.$value->name.'</option>';
                      }
                    }
                  }
                 ?> 
            </select>
          </div>
          <div class="col-sm-6 col-xs-12">
            <label class="control-label">Address <span class="text-danger">*</span><span class="text-danger error"></span></label>
            <input type="text" name="P_Address" id="P_Address" value="<?php if(isset($record_data['P_Address'])){ echo $record_data['P_Address']; } ?>" class="form-control " placeholder="Type your address..."  >
          </div>
          <div class="col-sm-6 col-xs-12">
            <label class="control-label">Zipcode <span class="text-danger">*</span><span class="text-danger error"></span></label>
            <input type="text" name="P_Zipcode" id="P_Zipcode" value="<?php if(isset($record_data['P_Zipcode'])){ echo $record_data['P_Zipcode']; } ?>" class="form-control " placeholder="Type your zipcode..."  >
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12" style="margin-top: 25px;">
          <h4>Other Information</h4><hr>
        </div>
      </div> 
         
        <div class="col-sm-6 col-xs-12">
          <label class="control-label">CNIC ( Govt ID )<span class="text-danger">*</span><span class="text-danger error"></span></label>
          <input type="text" name="CNIC" id="CNIC" value="<?php if(isset($record_data['CNIC'])){ echo $record_data['CNIC']; } ?>" class="form-control " placeholder="Type your Govt ID"  >
        </div>
        
        <div class="col-sm-6 col-xs-12">
          <label class="control-label">DOB ( DATE OF BIRTH ) <span class="text-danger">*</span><span class="text-danger error"></span></label>
          <input type="text" name="DOB" id="DOB" value="<?php if(isset($record_data['DOB'])){ echo $record_data['DOB']; } ?>" class="form-control  datepicker" placeholder="Select DOB"  >
        </div>
        <div class="col-sm-6 col-xs-12"> 
            <label class="control-label">Gender<span class="text-danger">*</span><span class="text-danger error"></span></label>
            <select class="form-control select2" id="Gender" name="Gender" value="<?php if(isset($record_data['Gender'])){ echo $record_data['Gender']; } ?>" > 
                <option selected="selected" <?php if(isset($record_data['Gender'])){  if($record_data['Gender'] == "Male"){ echo "selected='selected'"; } } ?> value="Male">Male</option> 
                <option value="Female" <?php if(isset($record_data['Gender'])){  if($record_data['Gender'] == "Female"){ echo "selected='selected'"; } } ?>>Female</option> 
                <option value="Other" <?php if(isset($record_data['Gender'])){  if($record_data['Gender'] == "Other"){ echo "selected='selected'"; } } ?>>Other</option>  
            </select> 
        </div>
        <div class="col-sm-6 col-xs-12"> 
          <label class="control-label">Martial Status<span class="text-danger">*</span><span class="text-danger error"></span></label>
          <select class="form-control select2" id="Martial_Status" name="Martial_Status" value="<?php if(isset($record_data['Martial_Status'])){ echo $record_data['Martial_Status']; } ?>" > 
              <option selected="selected" value="Single" <?php if(isset($record_data['Martial_Status'])){  if($record_data['Martial_Status'] == "Single"){ echo "selected='selected'"; } } ?>>Single</option> 
              <option value="Married" <?php if(isset($record_data['Martial_Status'])){  if($record_data['Martial_Status'] == "Married"){ echo "selected='selected'"; } } ?>>Married</option> 
              <option value="Divorced" <?php if(isset($record_data['Martial_Status'])){  if($record_data['Martial_Status'] == "Divorced"){ echo "selected='selected'"; } } ?>>Divorced</option>  
          </select> 
        </div>
        <div class="col-sm-6 col-xs-12"> 
          <label class="control-label">Blood Group<span class="text-danger">*</span><span class="text-danger error"></span></label> 
          <select class="form-control select2" id="Blood_Group" name="Blood_Group" value="<?php if(isset($record_data['Blood_Group'])){ echo $record_data['Blood_Group']; } ?>" > 
              <option selected="selected" value="A+" <?php if(isset($record_data['Blood_Group'])){  if($record_data['Blood_Group'] == "A+"){ echo "selected='selected'"; } } ?>>A+</option> 
              <option value="A-" <?php if(isset($record_data['Blood_Group'])){  if($record_data['Blood_Group'] == "A-"){ echo "selected='selected'"; } } ?>>A-</option> 
              <option value="O+"  <?php if(isset($record_data['Blood_Group'])){  if($record_data['Blood_Group'] == "O+"){ echo "selected='selected'"; } } ?>>O+</option>  
              <option value="O-"  <?php if(isset($record_data['Blood_Group'])){  if($record_data['Blood_Group'] == "O-"){ echo "selected='selected'"; } } ?>>O-</option>  
              <option value="AB+"  <?php if(isset($record_data['Blood_Group'])){  if($record_data['Blood_Group'] == "AB+"){ echo "selected='selected'"; } } ?>>AB+</option>  
              <option value="AB-"  <?php if(isset($record_data['Blood_Group'])){  if($record_data['Blood_Group'] == "AB-"){ echo "selected='selected'"; } } ?>>AB-</option>  
              <option value="B-"  <?php if(isset($record_data['Blood_Group'])){  if($record_data['Blood_Group'] == "B-"){ echo "selected='selected'"; } } ?>>B+</option>  
          </select> 
        </div>
        <div class="col-sm-6 col-xs-12"> 
            <label class="control-label">Religion<span class="text-danger">*</span><span class="text-danger error"></span></label>  
            <select class="form-control select2" id="Religion" name="Religion" value="<?php if(isset($record_data['Religion'])){ echo $record_data['Religion']; } ?>" > 
               <?php 
                  $this->db->order_by("Id","asc");
                  $religions = $this->db->get_where("religions");
                  if($religions->num_rows() > 0)
                  {
                    foreach ($religions->result() as $key => $value) 
                    {
                      if(isset($record_data['Religion'])){ if($record_data['Religion'] == $value->Id ){ $selected = "selected='selected'"; }else{ $selected = ""; }}
                       echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Religion.'</option>';
                    }
                  }
               ?>
            </select> 
        </div>
       
        <div class="col-sm-6 col-xs-12">
          <label class="control-label">Source Of Apply <span class="text-danger">*</span><span class="text-danger error"></span></label>
          <input type="text" name="Source_Of_Hire" id="Source_Of_Hire" value="<?php if(isset($record_data['Source_Of_Hire'])){ echo $record_data['Source_Of_Hire']; } ?>" class="form-control " placeholder="Type source of apply"  >
        </div>
       <div class="col-sm-6 col-xs-12">
          <label class="control-label">Resume <span class="text-danger">*</span><span class="text-danger error"></span></label>
          <input type="file" name="Resume" id="Resume" class="form-control " placeholder="Upload Resume"  >
        </div>
       
        <div class="col-sm-12 col-xs-12">
          <label class="control-label">About Applicant <span class="text-danger">*</span><span class="text-danger error"></span></label>
          <textarea type="text" rows="6" name="About_Info" id="About_Info"  class="form-control " placeholder="Type Applicant About_Info..."   ><?php if(isset($record_data['About_Info'])){ echo $record_data['About_Info']; } ?></textarea>
        </div>

        <div class="row">
          <div class="col-md-12" style="margin-top: 25px;"><hr> </div>
        </div> 
        <div class="col-sm-6 col-xs-12">
           <button style="float: left;" type="Submit" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
        </div>
      </div>
  </div>
</form>

<script type="text/javascript">
  $('.select2').select2(); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
</script>