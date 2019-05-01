<div class="panel-body">
	<div class="form-group">
        <div class="row">
        <?php

          $this->db->where(array("applicant_experience.Org_Id"=>$this->org_id,"applicant_experience.Deleted"=>0,"applicant_experience.Applicant_Id"=>$data));
          $this->db->select("applicant_experience.*,industries.Name as Industry_Name ");
          $this->db->from("applicant_experience"); 
          $this->db->join("industries","industries.Id = applicant_experience.Industry","left"); 
          $this->db->order_by("applicant_experience.Id","ASC");
          $applicant_experience = $this->db->get();

          if($applicant_experience->num_rows() > 0)
          {  
            foreach ($applicant_experience->result() as $key => $value){ 

              $document = "Not Uploaded";
              if($value->Document && $value->Document != "")
              {
                $src =  "assets/panel/userassets/applicant_experience/".$value->Id."/".$value->Document;
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
                                <div class="col-md-12">
                                  <h4 style="color:#fff; text-align:left;"><i class="fa fa-building"></i> '.$value->ORG_Name.'</h4>
                                </div>
                                 
                              </div>
                            </div>
                        </div>
                        <div class="panel-body" style="background: #dcdcdc;">
                           <div class="table-responsive">
                              <table class="table table-bordered table-primary nomargin view_applicant_table"> 
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