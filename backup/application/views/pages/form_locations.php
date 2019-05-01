
<?php
  
  $location_data = array();
  if($data != "")
  {
    $check_location = $this->db->get_where("locations",array("Deleted"=>0,"Id"=>$data,"Org_Id"=>$this->org_id));
    if($check_location->num_rows() > 0)
    {
      $location_data = $check_location->result_array();
      $location_data = $location_data[0];
    } 
  }
?>
<div class="mainpanel">
  <div class="contentpanel">
    <div class="panel">
      <div class="panel-body">
        <div class="col-md-12">
          <div class="col-md-6">
            <h3><i class="fa fa-user"></i> Save / Update Locations</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'locations_table')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
    <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
       <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($location_data['Id'])){ echo $location_data['Id']; } ?>">
       <input type="hidden" name="Table_Name" id="Table_Name" value="locations">
       <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>">
      <div class="panel">
        <div class="panel-heading nopaddingbottom">
          <h4 class="panel-title">Locations Information</h4>
           
        </div>
        <div class="panel-body nopaddingtop">
          <hr>
          <div class="error"></div>
          <div class="form-group">
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Country <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select name="Country_Id" id="Country_Id" onchange="get_states(this,'State_Id','<?php if(isset($location_data['State'])){ echo $location_data['State']; } ?>')" value="<?php if(isset($location_data['Country_Id'])){ echo $location_data['Country_Id']; } ?>" class="form-control select2 required">
                <?php 
                  $this->db->order_by("id","asc");
                  $countries = $this->db->get_where("countries");
                  if($countries->num_rows() > 0)
                  {
                    foreach ($countries->result() as $key => $value) 
                    {
                      if(isset($location_data['Country_Id'])){ if($location_data['Country_Id'] == $value->id ){ $selected = "selected='selected'"; }else{ $selected = ""; }}
                       echo '<option '.$selected.' value="'.$value->id.'">'.$value->name.'</option>';
                    }
                  }
                 ?> 
              </select>
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">State <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select name="State_Id" id="State_Id"  value="<?php if(isset($location_data['State_Id'])){ echo $location_data['State_Id']; } ?>" class="form-control select2 required"> 
               <option>Select State</option> 
                <?php 
                  if($location_data['Country_Id'] > 0)
                  {
                    $this->db->order_by("id","asc");
                    $states = $this->db->get_where("states",array("country_id"=>$location_data['Country_Id']));
                    if($states->num_rows() > 0)
                    {
                      foreach ($states->result() as $key => $value) 
                      {
                        if(isset($location_data['State_Id'])){ if($location_data['State_Id'] == $value->id ){ $selected = "selected='selected'"; }else{ $selected = ""; }}
                         echo '<option '.$selected.' value="'.$value->id.'">'.$value->name.'</option>';
                      }
                    }
                  }
                ?> 
              </select>
            </div>
            <div class="col-sm-3 col-xs-12">
              <label class="control-label">Locations Name <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Name" id="Name" value="<?php if(isset($location_data['Name'])){ echo $location_data['Name']; } ?>" class="form-control required" placeholder="Type location name..." required="" aria-required="true">
            </div>  
          </div>
        </div>
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="">
            <button style="float: left;" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
            <button style="float: right; margin-left: 5px;"  onclick="load_view(this,'locations_table');" type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            <button  style="float: right;"  onclick="load_view(this,'form_locations')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
  $('.select2').select2();
</script>