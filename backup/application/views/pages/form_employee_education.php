 
<?php
 
  $record_data = array();
  if($edit_rec != "")
  {
    $this->db->where(array("employee_education.Org_Id"=>$this->org_id,"employee_education.Id"=>$edit_rec));
    $this->db->select("employee_education.*,universities.Name as Institute_Name,countries.sortname as Country_Code");
    $this->db->from("employee_education"); 
    $this->db->join("universities","universities.Id = employee_education.Institute","left");
    $this->db->join("countries","countries.sortname = employee_education.Country","left");
    $this->db->order_by("employee_education.Id","ASC");
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
            <input type="hidden" name="Table_Name" id="Table_Name" value="employee_education">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Degree Name <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control required" placeholder="Degree Name" name="Degree_Name" id="Degree_Name" value="<?php if(isset($record_data['Degree_Name']) && $record_data['Degree_Name'] != "" ){ echo $record_data['Degree_Name']; } ?>">
              </div>
            </div>
           
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Degree Type <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select class="form-control select2" id="Degree_Type" name="Degree_Type" value="<?php if(isset($record_data['Degree_Type']) && $record_data['Degree_Type'] != "" ){ echo $record_data['Degree_Type']; } ?>">
                  <option value="0">Select Degree Type</option> 
                      <option  <?php if(isset($record_data['Degree_Type'])){ if($record_data['Degree_Type'] == "High School"){ echo "selected='selected'"; } } ?> value="High School">High School</option>
                      <option  <?php if(isset($record_data['Degree_Type'])){ if($record_data['Degree_Type'] == "Matriculation/O-Levels"){ echo "selected='selected'"; } } ?>  value="Matriculation/O-Levels">Matriculation/O-Levels</option>
                      <option  <?php if(isset($record_data['Degree_Type'])){ if($record_data['Degree_Type'] == "Intermediate/A-Levels"){ echo "selected='selected'"; } } ?>  value="Intermediate/A-Levels">Intermediate/A-Levels</option>
                      <option  <?php if(isset($record_data['Degree_Type'])){ if($record_data['Degree_Type'] == "Bachelors"){ echo "selected='selected'"; } } ?>  value="Bachelors">Bachelors</option>
                      <option  <?php if(isset($record_data['Degree_Type'])){ if($record_data['Degree_Type'] == "Masters"){ echo "selected='selected'"; } } ?>  value="Masters">Masters</option>
                      <option  <?php if(isset($record_data['Degree_Type'])){ if($record_data['Degree_Type'] == "Doctorate"){ echo "selected='selected'"; } } ?>  value="Doctorate">Doctorate</option>
                      <option  <?php if(isset($record_data['Degree_Type'])){ if($record_data['Degree_Type'] == "Certificate"){ echo "selected='selected'"; } } ?>  value="Certificate">Certificate</option>
                      <option  <?php if(isset($record_data['Degree_Type'])){ if($record_data['Degree_Type'] == "Diploma"){ echo "selected='selected'"; } } ?>  value="Diploma">Diploma</option>
                      <option  <?php if(isset($record_data['Degree_Type'])){ if($record_data['Degree_Type'] == "Other"){ echo "selected='selected'"; } } ?>  value="Other">Other</option>
                </select> 
              </div>
            </div>
            <div class="col-sm-3  col-xs-12">
              <div class="">
                <label class="control-label">Select Country <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select name="Country" id="Country" onchange="get_institute(this,'Institute')" value="<?php if(isset($record_data['Country'])){ echo $record_data['Country']; } ?>" class="form-control select2 ">
                  <?php 
                    $this->db->order_by("name","asc");
                    $countries = $this->db->get_where("countries");
                    if($countries->num_rows() > 0)
                    {
                      foreach ($countries->result() as $key => $value) 
                      {
                        $selected = "";
                        if(isset($record_data['Country'])){ if($record_data['Country'] == $value->sortname ){ $selected = "selected='selected'"; } }
                         echo '<option '.$selected.' value="'.$value->sortname.'">'.$value->name.'</option>';
                      }
                    }
                   ?>
                  
                </select>
              </div>
            </div>
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                  <label class="control-label">Select Institute <span class="text-danger">*</span><span class="text-danger error"></span></label>
                  <select name="Institute" id="Institute" value="<?php if(isset($record_data['Institute'])){ echo $record_data['Institute']; } ?>" class="form-control select2 ">
                    <?php 
                      if(isset($record_data['Institute']) && $record_data['Institute'] > 0)
                      {
                        $this->db->order_by("id","asc");
                        $universities = $this->db->get_where("universities",array("Country_Code"=>$record_data['Country_Code'] ));
                        if($universities->num_rows() > 0)
                        {
                          foreach ($universities->result() as $key => $value) 
                          {
                            if(isset($record_data['Institute'])){ if($record_data['Institute'] == $value->Id ){ $selected = "selected='selected'"; }else{ $selected = ""; }}
                             echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                          }
                        }
                      }
                    ?>
                    
                  </select>
              </div>
            </div> 
       
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Result Date: <span class="error_message" style="color: #f00;">  </span></label>
                <input type="text" class="form-control datepicker" placeholder="Result Date" name="Result_Date" id="Result_Date" value="<?php if(isset($record_data['Result_Date'])){ echo $record_data['Result_Date']; } ?>">
              </div>
            </div>
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Marks Obtained <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control required" placeholder="Marks Obtained" name="Marks_Obtained" id="Marks_Obtained" value="<?php if(isset($record_data['Marks_Obtained'])){ echo $record_data['Marks_Obtained']; } ?>">
              </div>
            </div>
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Total Marks <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control required" placeholder="Total Marks" name="Total_Marks" id="Total_Marks" value="<?php if(isset($record_data['Total_Marks'])){ echo $record_data['Total_Marks']; } ?>">
              </div>
            </div>
             
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Document <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="file" class="form-control" placeholder="Upload Document" name="Document" id="Document">
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

          $this->db->where(array("employee_education.Org_Id"=>$this->org_id,"employee_education.Deleted"=>0,"employee_education.Employee_Id"=>$data));
          $this->db->select("employee_education.*,universities.Name as Institute_Name,countries.sortname as Country_Code,countries.name as Country_Name");
          $this->db->from("employee_education"); 
          $this->db->join("universities","universities.Id = employee_education.Institute","left");
          $this->db->join("countries","countries.id = employee_education.Country","left");
          $this->db->order_by("employee_education.Id","ASC");
          $employee_education = $this->db->get();

          if($employee_education->num_rows() > 0)
          {  
            foreach ($employee_education->result() as $key => $value){ 

              $document = "Not Uploaded";
              if($value->Document && $value->Document != "")
              {
                $src =  "assets/panel/userassets/employee_education/".$value->Id."/".$value->Document;
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
                                  <h3 style="color:#fff;"><i class="fa fa-graduation-cap"></i> '.$value->Degree_Name.'</h3>
                                </div>
                                <div class="col-md-6">
                                  <a href="javascript:;" class="btn btn-sm" onclick="load_tab(this,\'form_employee_education\','.$data.',\'employee_from_container\', '.$value->Id.')" style=" font-size:18px;float: right; color:#fff;" ><i class="fa fa-pencil"></i></a>
                                  <a href="javascript:;" class="btn btn-sm"  onclick="delete_record(\'employee_education\','.$value->Id.',this)" style="color: red;float: right; font-size:18px;"> <i class="fa fa-trash"></i></a>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="panel-body" style="background: #dcdcdc;">
                           <div class="table-responsive">
                              <table class="table table-bordered table-primary nomargin"> 
                                  <tr> 
                                    <th>Degree Name</th>
                                    <td>'.$value->Degree_Name.'</td>
                                    <th>Degree Type</th>
                                    <td>'.$value->Degree_Type.'</td>
                                  </tr>
                                  <tr> 
                                    <th>Institute</th>
                                    <td>'.$value->Institute_Name.'</td>
                                    <th>Country</th>
                                    <td>'.$value->Country_Name.'</td>
                                  </tr>
                                  <tr> 
                                    <th>Marks Obtained</th>
                                    <td>'.$value->Marks_Obtained.'</td>
                                    <th>Total Marks</th>
                                    <td>'.$value->Total_Marks.'</td>
                                  </tr>
                                  <tr> 
                                    <th>Result Date</th>
                                    <td>'.$value->Result_Date.'</td>
                                    <th>Document</th>
                                    <td>'.$document.'</td>
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