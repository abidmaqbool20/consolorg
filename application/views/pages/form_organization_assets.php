
<?php
  
  $record_data = array();
  if($data != "")
  {
    $check_record = $this->db->get_where("organization_assets",array("Deleted"=>0,"Id"=>$data,"Org_Id"=>$this->org_id));
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
            <h3><i class="fa fa-user"></i> Save / Update Asset</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'organization_assets_table')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
    <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
       <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id'])){ echo $record_data['Id']; } ?>">
       <input type="hidden" name="Table_Name" id="Table_Name" value="organization_assets">
       <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>">
      <div class="panel">
        <div class="panel-heading nopaddingbottom">
          <h4 class="panel-title">Department Information</h4>
           
        </div>
        <div class="panel-body nopaddingtop">
          <hr>
          <div class="error"></div>
          <div class="form-group">
            <div class="col-sm-3 col-xs-12">
              <label class="control-label">Asset Name <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Name" id="Name" value="<?php if(isset($record_data['Name'])){ echo $record_data['Name']; } ?>" class="form-control required" placeholder="Type asset name..." required="" aria-required="true">
            </div>
            <div class="col-sm-3 col-xs-12">
              <label class="control-label">Quantity <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="number" name="Quantity" id="Quantity" value="<?php if(isset($record_data['Quantity'])){ echo $record_data['Quantity']; } ?>" class="form-control required" placeholder="Type quantity..." required="" aria-required="true">
            </div>
            <div class="col-sm-3 col-xs-12">
              <label class="control-label">Purchase Date<span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Purchase_Date" id="Purchase_Date" value="<?php if(isset($record_data['Purchase_Date'])){ echo $record_data['Purchase_Date']; } ?>" class="form-control required datepicker" placeholder="Type purchase date..." required="" >
            </div>
            <div class="col-sm-3 col-xs-12">
              <label class="control-label">Expiry Date <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Expiry_Date" id="Expiry_Date" value="<?php if(isset($record_data['Expiry_Date'])){ echo $record_data['Expiry_Date']; } ?>" class="form-control required datepicker" placeholder="Type asset expiry date..." required="" aria-required="true">
            </div>
             
          
          </div>
        </div>
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="">
            <button style="float: left;" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
            <button style="float: right; margin-left: 5px;"  onclick="load_view(this,'organization_assets_table');" type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            <button  style="float: right;"  onclick="load_view(this,'form_organization_assets')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
  $('.select2').select2();
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
</script>