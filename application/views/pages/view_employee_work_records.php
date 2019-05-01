 

    <div class="panel-body"> 
      
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
                                  <h3 style="color:#fff; text-align:left;"><i class="fa fa-building"></i> '.$value->Designation_Name.'</h3>
                                </div>
                                <div class="col-md-6">
                                  
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