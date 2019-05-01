<div class="panel-body">
	<div class="form-group">
        <div class="row">
        <?php

          $this->db->where(array("applicant_applications.Org_Id"=>$this->org_id,"applicant_applications.Deleted"=>0,"applicant_applications.Applicant_Id"=>$data));
          $this->db->select("applicant_applications.*,locations.Name as Location_Title,  job_posts.Title as Job_Title ");
          $this->db->from("applicant_applications"); 
          $this->db->join("locations","locations.Id = applicant_applications.Location_Id","left"); 
          $this->db->join("job_posts","job_posts.Id = applicant_applications.Job_Id","left"); 
          $this->db->order_by("applicant_applications.Id","DESC");
          $applicant_applications = $this->db->get();

          if($applicant_applications->num_rows() > 0)
          {  
            foreach ($applicant_applications->result() as $key => $value){ 

             

              // $document = "Not Uploaded";
              // if($value->Document && $value->Document != "")
              // {
              //   $src =  "assets/panel/userassets/applicant_applications/".$value->Id."/".$value->Document;
              //   if(file_exists($src))
              //   {
              //     $document = "<div><a target='_blank' href='".$src."'><i class='fa fa-file'></i> View Document</a></div>";
              //   } 
              // }

              echo '<div class="col-md-12 col-sm-12">
                      <div class="panel panel-inverse">
                        <div class="panel-heading" style="padding:0px;">
                            <div class="row">  
                              <div class="col-md-12">
                                <div class="col-md-6">
                                  <h4 style="color:#fff; text-align:left"><i class="fa fa-graduation-cap"></i> '.$value->Job_Title.'</h4>
                                </div>
                                
                              </div>
                            </div>
                        </div>
                        <div class="panel-body" style="background: #dcdcdc;">
                           <div class="table-responsive">
                              <table class="table table-bordered table-primary nomargin view_applicant_table"> 
                                  <tr> 
                                    <th>Post Title</th>
                                    <td>'.$value->Job_Title.'</td>
                                    <th>Location</th>
                                    <td>'.$value->Location_Title.'</td>
                                  </tr>
                                  <tr> 
                                    <th>Apply Date</th>
                                    <td>'.$value->Applied_Date.'</td>
                                    <th>Application Status</th>
                                    <td>'.$value->Application_Status.'</td>
                                  </tr>
                                  
                              </table>
                            </div>';

                            $this->db->where(array("applicant_interviews.Org_Id"=>$this->org_id,"applicant_interviews.Deleted"=>0,"applicant_interviews.Applicant_Id"=>$data,"applicant_interviews.Job_Id"=>$value->Job_Id,"applicant_interviews.Application_Id"=>$value->Id));
                            $this->db->select("applicant_interviews.*,employees.First_Name,employees.Last_Name");
                            $this->db->from("applicant_interviews"); 
                            $this->db->join("employees","employees.Id = applicant_interviews.Interviewer","left");  
                            $this->db->order_by("applicant_interviews.Id","DESC");
                            $applicant_interviews = $this->db->get();

                            if($applicant_interviews->num_rows() > 0)
                            {
                              foreach ($applicant_interviews->result() as $index => $interview_data) {
                                 
                                  echo '<div class="table-responsive" style="margin:10px 0px;" id="row_'.$interview_data->Id.'">
                                          <table class="table table-bordered table-primary nomargin view_applicant_table"> 
                                               
                                              <tr>  
                                                <th class="interview_table_head">Interviewer Name</th>
                                                <td >'.$interview_data->First_Name.' '.$interview_data->Last_Name.'</td>
                                              </tr>
                                              <tr>  
                                                <th class="interview_table_head">Interview Points</th>
                                                <td>'.$interview_data->Points.'</td>
                                              </tr>
                                              <tr>  
                                                <th class="interview_table_head">Interviewer Reviews</th>
                                                <td>'.$interview_data->Reviews.'</td>
                                              </tr>
                                              <tr>   
                                                <th class="interview_table_head">Interview Date</th>
                                                <td>'.$interview_data->Interview_Date.'</td>
                                              </tr>
                                               
                                          </table>
                                        </div>';


                              }
                            }
                            else
                            {
                              echo '<div class="table-responsive" style="margin:10px 0px;text-align: center;"> 
                                      <div class="col-md-12"><h3>No Interview Conducted</h3></div> 
                                    </div>';
                            }


                            $this->db->where(array("application_notes.Org_Id"=>$this->org_id,"application_notes.Deleted"=>0,"application_notes.Applicant_Id"=>$data,"application_notes.Job_Id"=>$value->Job_Id,"application_notes.Application_Id"=>$value->Id));
                            $this->db->select("application_notes.* ");
                            $this->db->from("application_notes");  
                            $this->db->order_by("application_notes.Id","DESC");
                            $application_notes = $this->db->get();

                            if($application_notes->num_rows() > 0)
                            {
                              foreach ($application_notes->result() as $counter => $notes) {

                               echo '<div class="table-responsive" style="margin:10px 0px;" id="row_'.$notes->Id.'">
                                      <div class="alert alert-info">  
                                        <p>'.$notes->Notes.'</p>
                                      </div>
                                     </div>';
                              }
                            }
                            else
                            {
                              echo '<div class="table-responsive" style="margin:10px 0px;text-align: center;"> 
                                      <div class="col-md-12"><h3>No Notes Added</h3></div> 
                                    </div>';
                            }

                             



                   echo '     </div> </div>  </div>';
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