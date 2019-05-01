 
<?php
     
    $record_data = array();
    if($edit_rec && $edit_rec != "")
    {
      $check_record = $this->db->get_where("org_work_days",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Location_Id"=>$edit_rec)); 
      if($check_record->num_rows() > 0)
      {
        $record_data = $check_record->result_array(); 
      }
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
      <form id="common_form" method="post" action="<?= base_url("admin/save_week_work") ?>" onsubmit="return save_week_work(this);" class="form-horizontal" >
        <div class="error"></div>
        <div class="form-group">
           
            <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Location_Id']) && $record_data['Location_Id'] != "" ){ echo $record_data['Id']; } ?>"> 
            <input type="hidden" name="Table_Name" id="Table_Name" value="org_work_days">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

            <div class="col-sm-12 col-xs-12 ">
              <h3>Choose Location</h3>
              <hr>
              <div class="col-sm-12 col-xs-12 " style="margin-bottom: 50px;">
                <label class="control-label">Select Location <span class="text-danger">*</span><span class="text-danger error"></span></label>
                  <select name="Location_Id" id="Location_Id" required="required" value="<?php if(isset($record_data[0]['Location_Id'])){ echo $record_data[0]['Location_Id']; } ?>" class="form-control select2 required ">
                      <option value="0">Select Location</option>
                      <?php  
                        $this->db->order_by("Name","asc");
                        $locations = $this->db->get_where("locations",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                        if($locations->num_rows() > 0)
                        {
                          foreach ($locations->result() as $key => $value) 
                          {
                            $selected = "";
                            if(isset($record_data[0]['Location_Id'])){ if($record_data[0]['Location_Id'] == $value->Id ){ $selected = "selected='selected'"; } }
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
                                <input type="checkbox" value="1" name="Sunday_1" <?php if(isset($record_data[0]['Sunday'])){ if($record_data[0]['Sunday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Sunday_2" <?php if(isset($record_data[1]['Sunday'])){ if($record_data[1]['Sunday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Sunday_3" <?php if(isset($record_data[2]['Sunday'])){ if($record_data[2]['Sunday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Sunday_4" <?php if(isset($record_data[3]['Sunday'])){ if($record_data[3]['Sunday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Sunday_5" <?php if(isset($record_data[4]['Sunday'])){ if($record_data[4]['Sunday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                             
                          </tr>
                          <tr>
                            <td>Monday</td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Monday_1" <?php if(isset($record_data[0]['Monday'])){ if($record_data[0]['Monday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Monday_2" <?php if(isset($record_data[1]['Monday'])){ if($record_data[1]['Monday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Monday_3" <?php if(isset($record_data[2]['Monday'])){ if($record_data[2]['Monday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Monday_4" <?php if(isset($record_data[3]['Monday'])){ if($record_data[3]['Monday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Monday_5" <?php if(isset($record_data[4]['Monday'])){ if($record_data[4]['Monday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td> 
                          </tr>
                          <tr>
                            <td>Tuesday</td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Tuesday_1" <?php if(isset($record_data[0]['Tuesday'])){ if($record_data[0]['Tuesday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Tuesday_2" <?php if(isset($record_data[1]['Tuesday'])){ if($record_data[1]['Tuesday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Tuesday_3" <?php if(isset($record_data[2]['Tuesday'])){ if($record_data[2]['Tuesday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Tuesday_4" <?php if(isset($record_data[3]['Tuesday'])){ if($record_data[3]['Tuesday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Tuesday_5" <?php if(isset($record_data[4]['Tuesday'])){ if($record_data[4]['Tuesday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                             
                          </tr>
                          <tr>
                            <td>Wednesday</td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Wednesday_1" <?php if(isset($record_data[0]['Wednesday'])){ if($record_data[0]['Wednesday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Wednesday_2" <?php if(isset($record_data[1]['Wednesday'])){ if($record_data[1]['Wednesday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Wednesday_3" <?php if(isset($record_data[2]['Wednesday'])){ if($record_data[2]['Wednesday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Wednesday_4" <?php if(isset($record_data[3]['Wednesday'])){ if($record_data[3]['Wednesday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Wednesday_5" <?php if(isset($record_data[4]['Wednesday'])){ if($record_data[4]['Wednesday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                             
                          </tr>
                          <tr>
                            <td>Thursday</td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Thursday_1" <?php if(isset($record_data[0]['Thursday'])){ if($record_data[0]['Thursday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Thursday_2" <?php if(isset($record_data[1]['Thursday'])){ if($record_data[1]['Thursday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Thursday_3" <?php if(isset($record_data[2]['Thursday'])){ if($record_data[2]['Thursday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Thursday_4" <?php if(isset($record_data[3]['Thursday'])){ if($record_data[3]['Thursday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Thursday_5" <?php if(isset($record_data[4]['Thursday'])){ if($record_data[4]['Thursday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                             
                          </tr>
                          <tr>
                            <td>Friday</td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Friday_1" <?php if(isset($record_data[0]['Friday'])){ if($record_data[0]['Friday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Friday_2" <?php if(isset($record_data[1]['Friday'])){ if($record_data[1]['Friday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Friday_3" <?php if(isset($record_data[2]['Friday'])){ if($record_data[2]['Friday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Friday_4" <?php if(isset($record_data[3]['Friday'])){ if($record_data[3]['Friday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Friday_5" <?php if(isset($record_data[4]['Friday'])){ if($record_data[4]['Friday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                             
                          </tr>
                          <tr>
                            <td>Saturday</td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Saturday_1" <?php if(isset($record_data[0]['Saturday'])){ if($record_data[0]['Saturday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Saturday_2" <?php if(isset($record_data[1]['Saturday'])){ if($record_data[1]['Saturday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Saturday_3" <?php if(isset($record_data[2]['Saturday'])){ if($record_data[2]['Saturday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Saturday_4" <?php if(isset($record_data[3]['Saturday'])){ if($record_data[3]['Saturday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
                              </label>
                            </td>
                            <td>
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" value="1" name="Saturday_5" <?php if(isset($record_data[4]['Saturday'])){ if($record_data[4]['Saturday'] == 1){ echo 'checked="checked"'; }} ?>><span></span>
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
            <div class="col-sm-12">
              <br>
              <button  type="submit" id="" class="btn btn-success pull-left btn-lg"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Submit </button> 
              <button  onclick="load_tab(this,'form_work_week_days','','settings_from_container')" type="button" id="" style="margin-left: 10px;" class="btn btn-warning pull-left btn-lg"><i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;Reset </button> 
            </div>
          </div>
        </div>
      </form>
      <br><br>
      <hr>
      <div class="form-group">
        <div class="row">
        <?php

          $this->db->where(array("org_work_days.Org_Id"=>$this->org_id,"org_work_days.Deleted"=>0));
          $this->db->select("org_work_days.*,locations.Name as Location_Name ");
          $this->db->from("org_work_days"); 
          $this->db->join("locations","locations.Id = org_work_days.Location_Id","left"); 
          $this->db->order_by("org_work_days.Id","ASC");
          $org_work_days = $this->db->get();

          if($org_work_days->num_rows() > 0)
          {    
              $days_array = $Sunday = $Monday = $Tuesday = $Wednesday = $Thursday = $Friday = $Saturday = array();
              foreach ($org_work_days->result() as $key => $value)
              { 

                  $Sunday[] = $value->Sunday;
                  $Monday[] = $value->Monday;
                  $Tuesday[] = $value->Tuesday;
                  $Wednesday[] = $value->Wednesday;
                  $Thursday[] = $value->Thursday;
                  $Friday[] = $value->Friday;
                  $Saturday[] = $value->Saturday;
              }
              
              $days_array['Sunday'] = $Sunday; 
              $days_array['Monday'] = $Monday; 
              $days_array['Tuesday'] = $Tuesday; 
              $days_array['Wednesday'] = $Wednesday; 
              $days_array['Thursday'] = $Thursday; 
              $days_array['Friday'] = $Friday; 
              $days_array['Saturday'] = $Saturday; 

              echo '<div class="col-md-12 col-sm-12" id="row_'.$value->Location_Id.'">
                      <div class="panel panel-inverse">
                        <div class="panel-heading" style="padding:0px;">
                            <div class="row">  
                              <div class="col-md-12">
                                <div class="col-md-6">
                                  <h3 style="color:#fff;"><i class="fa fa-globe"></i> '.$value->Location_Name.'</h3>
                                </div>
                                <div class="col-md-6">
                                  <a href="javascript:;" class="btn btn-sm" onclick="load_tab(this,\'form_work_week_days\','.$value->Id.',\'settings_from_container\', '.$value->Location_Id.')" style=" font-size:18px;float: right; color:#fff;" ><i class="fa fa-pencil"></i></a>
                                  <a href="javascript:;" class="btn btn-sm"  onclick="delete_week_work_days(\'org_work_days\','.$value->Location_Id.',this)" style="color: red;float: right; font-size:18px;"> <i class="fa fa-trash"></i></a>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="panel-body" style="background: #dcdcdc;">
                           <div class="table-responsive">
                              <table class="table table-bordered table-primary nomargin"> 
                      ';
                      // echo "<pre>";
                      // print_r($days_array);
                      // echo "<pre>";
                      echo '<tr>  
                              <th style="color:#879bff !important;border-bottom: 2px solid;">Day Name</th>
                              <th style="color:#879bff !important;border-bottom: 2px solid;">1st</th>
                              <th style="color:#879bff !important;border-bottom: 2px solid;">2nd</th>
                              <th style="color:#879bff !important;border-bottom: 2px solid;">3rd</th>
                              <th style="color:#879bff !important;border-bottom: 2px solid;">4th</th>
                              <th style="color:#879bff !important;border-bottom: 2px solid;">5th</th> 
                            </tr>
                            ';
                      foreach ($days_array as $key => $value) 
                      { 
                          echo '<tr>  <th style="color:#505b72 !important; font-size:16px !important;">'.$key.'</th>';

                          foreach ($value as $index => $status) 
                          { 

                              if($status == 1){ $day_status = "ON"; $color = "green"; }else{ $day_status = "OFF";  $color = "red"; }

                              echo  '<td style="color:'.$color.' !important">'.$day_status.'</td>';
                          }
                      }

              echo '</table> </div> </div> </div> </div>';
               
          }
          else
          {
            echo no_record_found(); 
          }

        ?>
        </div>
      </div>
    </div> 
       
<script type="text/javascript">
  $('.select2').select2(); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
</script>