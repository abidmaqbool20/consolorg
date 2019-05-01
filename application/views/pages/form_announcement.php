
<?php
  
  $record_data = array();
  if($data != "")
  {
    $check_record = $this->db->get_where("announcements",array("Deleted"=>0,"Id"=>$data,"Org_Id"=>$this->org_id));
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
            <h3><i class="fa fa-user"></i> Save / Update Announcements</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'announcements')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
    <form id="common_form" method="post" action="<?= base_url("admin/save_announcement") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
       <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id'])){ echo $record_data['Id']; } ?>">
       <input type="hidden" name="Table_Name" id="Table_Name" value="announcements">
       <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>">
      <div class="panel">
        <div class="panel-heading nopaddingbottom">
          <h4 class="panel-title">Announcements Information</h4>
           
        </div>
        <div class="panel-body nopaddingtop">
          <hr>
          <div class="error"></div>
          <div class="form-group">
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Announcement Title <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Title" id="Title" value="<?php if(isset($record_data['Title'])){ echo $record_data['Title']; } ?>" class="form-control required" placeholder="Type title..." required="" aria-required="true">
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Shifts <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select id="Shifts" name="Shifts[]" multiple="multiple" class="form-control select2" style="width: 100%" data-placeholder="Select Shifts">
                  <option value="">&nbsp;</option>
                  <option value="0">Select Shifts</option>
                  <?php  

                    $selected_shifts = array();
                    if(isset($record_data['Id']))
                    { 
                      $this->db->select("Shift_Id");
                      $announcements_shifts = $this->db->get_where("announcement_shifts",array("Announcement_Id"=>$record_data['Id']));
                      if($announcements_shifts->num_rows() > 0)
                      {
                        foreach ($announcements_shifts->result() as $key => $value) {
                          $selected_shifts[] = $value->Shift_Id;
                        }

                      }
                    }
 

                    $shifts = $this->db->get_where("shifts",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                    if($shifts->num_rows() > 0)
                    {
                      foreach ($shifts->result() as $key => $value) 
                      { 
                        $selected = ""; 
                        if(sizeof($selected_shifts) > 0){ if(in_array($value->Id,$selected_shifts)){ $selected = 'selected="selected"'; } }

                        echo '<option '.$selected.'  value="'.$value->Id.'">'.$value->Name.'</option>';
                      }
                    }
                  ?>
                  
              </select>
            </div> 
            <div class="col-sm-12 col-xs-12 ">
              <div class="">
                <label class="control-label">Description <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <textarea name="Description" id="Description" class="form-control" ><?php if(isset($record_data['Description'])){ echo $record_data['Description']; } ?></textarea>
                <script> CKEDITOR.replace( 'Description' );</script> 
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="">
            <button style="float: left;" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
            <button style="float: right; margin-left: 5px;"  onclick="load_view(this,'announcements');" type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            <button  style="float: right;"  onclick="load_view(this,'form_announcement')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
  $('.select2').select2();
</script>