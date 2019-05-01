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

              echo '<div class="col-md-12 col-sm-12" id="row_'.$value->Id.'">
                      <div class="panel panel-inverse">
                        <div class="panel-heading" style="padding:0px;">
                            <div class="row">  
                              <div class="col-md-12">
                                <div class="col-md-12">
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