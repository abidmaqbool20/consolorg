 
<?php
 
  $record_data = array();
  if($edit_rec != "")
  {
    $this->db->where(array("employee_experience.Org_Id"=>$this->org_id,"employee_experience.Id"=>$edit_rec));
    $this->db->select("employee_experience.*,industries.Name as Industry_Name");
    $this->db->from("employee_experience"); 
    $this->db->join("industries","industries.Id = employee_experience.Industry","left"); 
    $this->db->order_by("employee_experience.Id","ASC");
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
            <input type="hidden" name="Table_Name" id="Table_Name" value="employee_experience">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Degree Name <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control required" placeholder="Organization Name" name="ORG_Name" id="ORG_Name" value="<?php if(isset($record_data['ORG_Name']) && $record_data['ORG_Name'] != "" ){ echo $record_data['ORG_Name']; } ?>">
              </div>
            </div>
           
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Organization Type <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select class="form-control select2" id="ORG_Type" name="ORG_Type" value="<?php if(isset($record_data['ORG_Type']) && $record_data['ORG_Type'] != "" ){ echo $record_data['ORG_Type']; } ?>">
                  <option value="0">Select Degree Type</option> 
                      <option  <?php if(isset($record_data['ORG_Type'])){ if($record_data['ORG_Type'] == "Private"){ echo "selected='selected'"; } } ?> value="Private">Private</option>
                      <option  <?php if(isset($record_data['ORG_Type'])){ if($record_data['ORG_Type'] == "Government"){ echo "selected='selected'"; } } ?>  value="Government">Government</option> 
                </select> 
              </div>
            </div>
            <div class="col-sm-3  col-xs-12">
              <div class="">
                <label class="control-label">Industry<span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select name="Industry" id="Industry" value="<?php if(isset($record_data['Industry'])){ echo $record_data['Industry']; } ?>" class="form-control select2 ">
                  <?php 
                    $this->db->order_by("Name","asc");
                    $industries = $this->db->get_where("industries");
                    if($industries->num_rows() > 0)
                    {
                      foreach ($industries->result() as $key => $value) 
                      {
                        $selected = "";
                        if(isset($record_data['Industry'])){ if($record_data['Industry'] == $value->Id ){ $selected = "selected='selected'"; } }
                         echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                      }
                    }
                   ?>
                  
                </select>
              </div>
            </div>
             
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Designation <span class="error_message" style="color: #f00;">  </span></label>
                <input type="text" class="form-control" placeholder="Designation" name="Designation" id="Designation" value="<?php if(isset($record_data['Designation'])){ echo $record_data['Designation']; } ?>">
              </div>
            </div>
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Job Field<span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control required" placeholder="Job Field" name="Job_Field" id="Job_Field" value="<?php if(isset($record_data['Job_Field'])){ echo $record_data['Job_Field']; } ?>">
              </div>
            </div>
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Job Start Date <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control datepicker required" placeholder="Job Start Date" name="Job_Start_Date" id="Job_Start_Date" value="<?php if(isset($record_data['Job_Start_Date'])){ echo $record_data['Job_Start_Date']; } ?>">
              </div>
            </div>
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Job End Date <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control datepicker required" placeholder="Job End Date" name="Job_End_Date" id="Job_End_Date" value="<?php if(isset($record_data['Job_End_Date'])){ echo $record_data['Job_End_Date']; } ?>">
              </div>
            </div>
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Previous Salary <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control required" placeholder="Salary " name="Previous_Salary" id="Previous_Salary" value="<?php if(isset($record_data['Previous_Salary'])){ echo $record_data['Previous_Salary']; } ?>">
              </div>
            </div> 
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Organization Location <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control required" placeholder="Organization Location " name="Org_Location" id="Org_Location" value="<?php if(isset($record_data['Org_Location'])){ echo $record_data['Org_Location']; } ?>">
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
                <label class="control-label">Job Description <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <textarea name="Job_Description" id="Job_Description" class="form-control" ><?php if(isset($record_data['Job_Description'])){ echo $record_data['Job_Description']; } ?></textarea>
                <script> CKEDITOR.replace( 'Job_Description' );</script> 
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

          $this->db->where(array("employee_experience.Org_Id"=>$this->org_id,"employee_experience.Deleted"=>0,"employee_experience.Employee_Id"=>$data));
          $this->db->select("employee_experience.*,industries.Name as Industry_Name ");
          $this->db->from("employee_experience"); 
          $this->db->join("industries","industries.Id = employee_experience.Industry","left"); 
          $this->db->order_by("employee_experience.Id","ASC");
          $employee_experience = $this->db->get();

          if($employee_experience->num_rows() > 0)
          {  
            foreach ($employee_experience->result() as $key => $value){ 

              $document = "Not Uploaded";
              if($value->Document && $value->Document != "")
              {
                $src =  "assets/panel/userassets/employee_experience/".$value->Id."/".$value->Document;
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
                                  <h3 style="color:#fff;"><i class="fa fa-building"></i> '.$value->ORG_Name.'</h3>
                                </div>
                                <div class="col-md-6">
                                  <a href="javascript:;" class="btn btn-sm" onclick="load_tab(this,\'form_employee_experience\','.$data.',\'employee_from_container\', '.$value->Id.')" style=" font-size:18px;float: right; color:#fff;" ><i class="fa fa-pencil"></i></a>
                                  <a href="javascript:;" class="btn btn-sm"  onclick="delete_record(\'employee_experience\','.$value->Id.',this)" style="color: red;float: right; font-size:18px;"> <i class="fa fa-trash"></i></a>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="panel-body" style="background: #dcdcdc;">
                           <div class="table-responsive">
                              <table class="table table-bordered table-primary nomargin"> 
                                  <tr> 
                                    <th>Organization Name</th>
                                    <td>'.$value->ORG_Name.'</td>
                                    <th>Organization Type</th>
                                    <td>'.$value->ORG_Type.'</td>
                                  </tr>
                                  <tr> 
                                    <th>Industry</th>
                                    <td>'.$value->Industry_Name.'</td>
                                    <th>Designation</th>
                                    <td>'.$value->Designation.'</td>
                                  </tr>
                                  <tr> 
                                    <th>Job Start Date</th>
                                    <td>'.$value->Job_Start_Date.'</td>
                                    <th>Job End Date</th>
                                    <td>'.$value->Job_End_Date.'</td>
                                  </tr>
                                  <tr> 
                                    <th>Job Field</th>
                                    <td>'.$value->Job_Field.'</td>
                                    <th>Salary</th>
                                    <td>'.$value->Previous_Salary.'</td>
                                  </tr> 
                                  <tr> 
                                    <th>Organization Location</th>
                                    <td>'.$value->Org_Location.'</td>
                                    <th>Document</th>
                                    <td>'.$document.'</td>
                                  </tr>
                                  <tr> 
                                    <th>Job Description</th>
                                    <td colspan="3">'.$value->Job_Description.'</td> 
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