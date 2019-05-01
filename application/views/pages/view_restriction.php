
<?php
  
  $record_data = array();
  if($data != "")
  {
    $check_record = $this->db->get_where("restrictions",array("Deleted"=>0,"Id"=>$data,"Org_Id"=>$this->org_id));
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
            <h3><i class="fa fa-user"></i> Save / Update Restrictions</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'restrictions')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
     <div class="panel">
        <div class="panel-heading nopaddingbottom">
          <h3 class="panel-title" style="font-size: 20px;"><?= $record_data['Title']; ?></h3> 
        </div>
        <div class="panel-body nopaddingtop">
          <hr>
          
          <div class="form-group">
            <label><h4>Description</h4></label>
            <p><?= $record_data['Description']; ?></p> 
          </div>
          <div class="form-group">
            <label><h4>Allowed IP's</h4></label>
            <p style="word-wrap: break-word;"><?= $record_data['Allowed_IP']; ?></p> 
          </div>
            
          </div>
        </div>
      </div>
  </div>
</div>

 