 
<?php
    
  

    $record_data = array();
 
    $check_record = $this->db->get_where("org_work_days",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));

    if($check_record->num_rows() > 0)
    {
      $record_data = $check_record->result_array();
      $record_data = $record_data[0]; 
    }

   
?>

<style type="text/css">
  .table tr th
  {
    color: #f8ff87 !important;
    font-weight: bold !important;
    font-size: 18px;
  }

  .table tr td
  {
    color: #fff !important;
    font-weight: bold !important;
    font-size: 15px;
  }

</style>



    <div class="panel-body"> 
      <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
        <div class="error"></div>
        <div class="form-group">
           
            <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id']) && $record_data['Id'] != "" ){ echo $record_data['Id']; } ?>"> 
            <input type="hidden" name="Table_Name" id="Table_Name" value="org_work_days">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

            <div class="col-sm-12 col-xs-12 ">
              <h3>Choose Location</h3>
              <hr>
              <div class="col-sm-12 col-xs-12 " style="margin-bottom: 50px;">
                <label class="control-label">Select Location <span class="text-danger">*</span><span class="text-danger error"></span></label>
                  <select name="Location_Id" id="Location_Id" onchange="get_institute(this,'Institute')" value="<?php if(isset($record_data['Location_Id'])){ echo $record_data['Location_Id']; } ?>" class="form-control select2 ">
                      <option value="0">Select Location</option>
                      <?php  
                        $this->db->order_by("Name","asc");
                        $locations = $this->db->get_where("locations",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                        if($locations->num_rows() > 0)
                        {
                          foreach ($locations->result() as $key => $value) 
                          {
                            $selected = "";
                            if(isset($record_data['Location_Id'])){ if($record_data['Location_Id'] == $value->Id ){ $selected = "selected='selected'"; } }
                             echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                          }
                        }

                       ?>
                      
                  </select>
              </div>

              <h3>Weekend Definition</h3>
              <hr>
              <div class="col-sm-12 col-xs-12">
                  <div class="table-responsive">
                      <table class="table table-bordered table-primary nomargin view_applicant_table" style="background: #9fa8bc;"> 
                          <tr>
                            <th>Day Name</th>
                            <th>1st</th>
                            <th>2nd</th>
                            <th>3rd</th>
                            <th>4th</th>
                            <th>5th</th>
                          </tr>
                          <tr>
                            <td>Sunday</td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="1" name="Sunday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="2" name="Sunday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="3" name="Sunday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="4" name="Sunday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="5" name="Sunday[]"><span></span>
                              </label>
                            </td>
                             
                          </tr>
                          <tr>
                            <td>Monday</td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="1" name="Monday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="2" name="Monday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="3" name="Monday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="4" name="Monday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="5" name="Monday[]"><span></span>
                              </label>
                            </td> 
                          </tr>
                          <tr>
                            <td>Tuesday</td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="1" name="Tuesday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="2" name="Tuesday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="3" name="Tuesday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="4" name="Tuesday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="5" name="Tuesday[]"><span></span>
                              </label>
                            </td>
                             
                          </tr>
                          <tr>
                            <td>Wednesday</td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="1" name="Wednesday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="2" name="Wednesday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="3" name="Wednesday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="4" name="Wednesday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="5" name="Wednesday[]"><span></span>
                              </label>
                            </td>
                             
                          </tr>
                          <tr>
                            <td>Thursday</td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="1" name="Thursday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="2" name="Thursday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="3" name="Thursday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="4" name="Thursday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="5" name="Thursday[]"><span></span>
                              </label>
                            </td>
                             
                          </tr>
                          <tr>
                            <td>Friday</td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="1" name="Friday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="2" name="Friday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="3" name="Friday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="4" name="Friday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="5" name="Friday[]"><span></span>
                              </label>
                            </td>
                             
                          </tr>
                          <tr>
                            <td>Saturday</td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="1" name="Saturday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="2" name="Saturday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="3" name="Saturday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="4" name="Saturday[]"><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" checked="" value="5" name="Saturday[]"><span></span>
                              </label>
                            </td> 
                          </tr>
                      </table>
                  </div>
              </div> 
            </div>
           
          
                  
        </div>
            
        <div class="form-group"> 
          <div class="col-sm-12">
            <br>
            <button type="submit" id="" class="btn btn-success pull-left"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Submit </button> 
          </div>
        </div>
      </form>
      <br><br>
      
    </div> 
      
         

 


<script type="text/javascript">
  $('.select2').select2(); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
</script>