 
<?php
 
  $record_data = array();
  if($edit_rec != "")
  {
    $this->db->where(array("employee_work_record.Org_Id"=>$this->org_id,"employee_work_record.Id"=>$edit_rec));
    $this->db->select("employee_work_record.*");
    $this->db->from("employee_work_record");   
    $check_record = $this->db->get();

    if($check_record->num_rows() > 0)
    {
      $record_data = $check_record->result_array();
      $record_data = $record_data[0]; 
    }

  }
?>

    <div class="panel-body"> 
      <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
        <div class="error"></div>
        <div class="form-group">
           
            <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id']) && $record_data['Id'] != "" ){ echo $record_data['Id']; } ?>">
            <input type="hidden" name="Employee_Id" id="Employee_Id" value="<?= $data; ?>">
            <input type="hidden" name="Table_Name" id="Table_Name" value="employee_work_record">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

           
           
            <div class="col-sm-4 col-xs-12 ">
              <div class="">
                <label class="control-label">Select Location <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select class="form-control select2" id="Location_Id" name="Location_Id" value="<?php if(isset($record_data['Location_Id']) && $record_data['Location_Id'] != "" ){ echo $record_data['Location_Id']; } ?>">
                  <option value="0">Select Location</option> 
                       <?php 
                          $this->db->order_by("Name","asc");
                          $locations = $this->db->get_where("locations",array("Org_Id"=>$this->org_id));
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
            </div>
            <div class="col-sm-4 col-xs-12 ">
              <div class="">
                <label class="control-label">Select Department <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select class="form-control select2" id="Department_Id" name="Department_Id" value="<?php if(isset($record_data['Department_Id']) && $record_data['Department_Id'] != "" ){ echo $record_data['Department_Id']; } ?>">
                  <option value="0">Select Department</option> 
                       <?php 
                          $this->db->order_by("Name","asc");
                          $departments = $this->db->get_where("departments",array("Org_Id"=>$this->org_id));
                          if($departments->num_rows() > 0)
                          {
                            foreach ($departments->result() as $key => $value) 
                            {
                              $selected = "";
                              if(isset($record_data['Department_Id'])){ if($record_data['Department_Id'] == $value->Id ){ $selected = "selected='selected'"; } }
                               echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                            }
                          }
                        ?>
                </select> 
              </div>
            </div>
             <div class="col-sm-4 col-xs-12 ">
              <div class="">
                <label class="control-label">Select Designation <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select class="form-control select2" id="Designation_Id" name="Designation_Id" value="<?php if(isset($record_data['Designation_Id']) && $record_data['Designation_Id'] != "" ){ echo $record_data['Designation_Id']; } ?>">
                  <option value="0">Select Designation</option> 
                       <?php 
                          $this->db->order_by("Name","asc");
                          $designations = $this->db->get_where("designations",array("Org_Id"=>$this->org_id));
                          if($designations->num_rows() > 0)
                          {
                            foreach ($designations->result() as $key => $value) 
                            {
                              $selected = "";
                              if(isset($record_data['Designation_Id'])){ if($record_data['Designation_Id'] == $value->Id ){ $selected = "selected='selected'"; } }
                               echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                            }
                          }
                        ?>
                </select> 
              </div>
            </div>
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Employee Type <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select class="form-control select2" id="Employee_Type" name="Employee_Type" value="<?php if(isset($record_data['Employee_Type']) && $record_data['Employee_Type'] != "" ){ echo $record_data['Employee_Type']; } ?>">
                  <option value="0">Select Employee Type</option> 
                      <option  <?php if(isset($record_data['Employee_Type'])){ if($record_data['Employee_Type'] == "Permanent"){ echo "selected='selected'"; } } ?> value="Permanent">Permanent</option>
                      <option  <?php if(isset($record_data['Employee_Type'])){ if($record_data['Employee_Type'] == "Contract"){ echo "selected='selected'"; } } ?>  value="Contract">Contract</option> 
                      <option  <?php if(isset($record_data['Employee_Type'])){ if($record_data['Employee_Type'] == "Internee"){ echo "selected='selected'"; } } ?>  value="Internee">Internee</option> 
                </select> 
              </div>
            </div>
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Start Date <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control datepicker required" placeholder="Start Date" name="Start_Date" id="Start_Date" value="<?php if(isset($record_data['Start_Date'])){ echo $record_data['Start_Date']; } ?>">
              </div>
            </div>
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">End Date <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control datepicker required" placeholder="End Date" name="End_Date" id="End_Date" value="<?php if(isset($record_data['End_Date'])){ echo $record_data['End_Date']; } ?>">
              </div>
            </div>
            
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Document <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="file" class="form-control" placeholder="Upload Document" name="Document" id="Document">
              </div>
            </div>

            <div class="col-sm-12 col-xs-12 ">
              <div class="">
                <label class="control-label">Description <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <textarea name="Description" id="Description" class="form-control" ><?php if(isset($record_data['Description'])){ echo $record_data['Description']; } ?></textarea>
                <script> CKEDITOR.replace( 'Description' );</script> 
              </div>
            </div>

        </div>
            
        <div class="form-group"> 
          <div class="col-sm-12">
            <br>
            <button type="submit" id="" class="btn btn-success pull-right"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Submit </button> 
          </div>
        </div>
      </form>
      <br><br>
      <div class="form-group">
        <div class="row">
        <?php

          $this->db->where(array("employee_work_record.Org_Id"=>$this->org_id,"employee_work_record.Deleted"=>0,"employee_work_record.Employee_Id"=>$data));
          $this->db->select("employee_work_record.*,departments.Name as Department_Name,designations.Name as Designation_Name,locations.Name as Location_Name ");
          $this->db->from("employee_work_record"); 
          $this->db->join("departments","departments.Id = employee_work_record.Department_Id","left"); 
          $this->db->join("designations","designations.Id = employee_work_record.Designation_Id","left"); 
          $this->db->join("locations","locations.Id = employee_work_record.Location_Id","left"); 
          $this->db->order_by("employee_work_record.Id","DESC");
          $employee_work_record = $this->db->get();

          if($employee_work_record->num_rows() > 0)
          {  
            foreach ($employee_work_record->result() as $key => $value){  

              if($key > 0)
              {
                $this->db->update("employee_work_record",array("Status"=>0),array("Id"=>$value->Id));
              }


              $document = "Not Uploaded";
              if($value->Document && $value->Document != "")
              {
                $src =  "assets/panel/userassets/employee_work_record/".$value->Id."/".$value->Document;
                if(file_exists($src))
                {
                  $document = "<div><a target='_blank' href='".$src."'><i class='fa fa-file'></i> View Document</a></div>";
                } 
              }

              echo '<div class="col-md-12 col-sm-12" id="row_'.$value->Id.'">
                      <div class="panel panel-inverse">
                        <div class="panel-heading" style="padding:0px;">
                            <div class="row">  
                              <div class="col-md-12">
                                <div class="col-md-6">
                                  <h3 style="color:#fff;"><i class="fa fa-building"></i> '.$value->Designation_Name.'</h3>
                                </div>
                                <div class="col-md-6">
                                  <a href="javascript:;" class="btn btn-sm" onclick="load_tab(this,\'form_employee_work_info\','.$data.',\'employee_from_container\', '.$value->Id.')" style=" font-size:18px;float: right; color:#fff;" ><i class="fa fa-pencil"></i></a>
                                  <a href="javascript:;" class="btn btn-sm"  onclick="delete_record(\'employee_work_record\','.$value->Id.',this)" style="color: red;float: right; font-size:18px;"> <i class="fa fa-trash"></i></a>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="panel-body" style="background: #dcdcdc;">
                           <div class="table-responsive">
                              <table class="table table-bordered table-primary nomargin"> 
                                  <tr> 
                                    <th>Location Name</th>
                                    <td>'.$value->Location_Name.'</td>
                                    <th>Department Name</th>
                                    <td>'.$value->Department_Name.'</td>
                                  </tr>
                                  <tr> 
                                    <th>Designation </th>
                                    <td>'.$value->Designation_Name.'</td>
                                    <th>Document</th>
                                    <td>'.$document.'</td>
                                  </tr>
                                  <tr> 
                                    <th>Start Date</th>
                                    <td>'.$value->Start_Date.'</td>
                                    <th>End Date</th>
                                    <td>'.$value->End_Date.'</td>
                                  </tr> 
                                  <tr> 
                                    <th>Job Description</th>
                                    <td colspan="3">'.$value->Description.'</td> 
                                  </tr>  
                              </table>
                            </div>
                        </div>
                      </div> 
                    </div>';
              }
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