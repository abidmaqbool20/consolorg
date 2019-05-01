<div class="panel-body"> 
	<div class="form-group">
    	<div class="row">
    <?php

      $this->db->where(array("applicant_education.Org_Id"=>$this->org_id,"applicant_education.Deleted"=>0,"applicant_education.applicant_Id"=>$data));
      $this->db->select("applicant_education.*,universities.Name as Institute_Name,countries.sortname as Country_Code,countries.name as Country_Name");
      $this->db->from("applicant_education"); 
      $this->db->join("universities","universities.Id = applicant_education.Institute","left");
      $this->db->join("countries","countries.id = applicant_education.Country","left");
      $this->db->order_by("applicant_education.Id","ASC");
      $applicant_education = $this->db->get();

      if($applicant_education->num_rows() > 0)
      {  
        foreach ($applicant_education->result() as $key => $value){ 

          $document = "Not Uploaded";
          if($value->Document && $value->Document != "")
          {
            $src =  "assets/panel/userassets/applicant_education/".$value->Id."/".$value->Document;
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
                              <h4 style="color:#fff; text-align:left;"><i class="fa fa-graduation-cap"></i> '.$value->Degree_Name.'</h4>
                            </div>
                            
                          </div>
                        </div>
                    </div>
                    <div class="panel-body" style="background: #dcdcdc;">
                       <div class="table-responsive">
                          <table class="table table-bordered table-primary nomargin view_applicant_table"> 
                              <tr> 
                                <th>Degree Name</th>
                                <td>'.$value->Degree_Name.'</td>
                                <th>Degree Type</th>
                                <td>'.$value->Degree_Type.'</td>
                              </tr>
                              <tr> 
                                <th>Institute</th>
                                <td>'.$value->Institute_Name.' ('.$value->Institute_Campus.')</td>
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