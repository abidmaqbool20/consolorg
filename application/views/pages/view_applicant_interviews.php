<div class="panel-body">
	<div class="form-group">
        <div class="row"> 
          <div class="col-md-12 col-lg-12" style="padding: 0;">
            <ul class="nav nav-tabs nav-tabs-primary " style="background-color: transparent;"> 
              
            <?php
              $view_data['applicant_id'] = $data;
              $view_data['applicantion_id'] = 0;
              $first_application_id = 0;
              $this->db->where(array("applicant_applications.Org_Id"=>$this->org_id,"applicant_applications.Deleted"=>0,"applicant_applications.Applicant_Id"=>$data));
              $this->db->select("applicant_applications.*,locations.Name as Location_Title,  job_posts.Title as Job_Title ");
              $this->db->from("applicant_applications"); 
              $this->db->join("locations","locations.Id = applicant_applications.Location_Id","left"); 
              $this->db->join("job_posts","job_posts.Id = applicant_applications.Job_Id","left"); 
              $this->db->order_by("applicant_applications.Id","DESC");
              $applicant_applications = $this->db->get();
              $total_applications = $applicant_applications->num_rows(); 
              if($applicant_applications->num_rows() > 0)
              {  
                foreach ($applicant_applications->result() as $key => $value)
                {  
                  if($key == 0){ $view_data['applicantion_id'] = $value->Id; }
                  echo '<li class="nav-item application " onclick=\'load_tab(this,"view_applicant_interview_evaluations",'.json_encode($view_data).',"applicant_interview_evaluations")\'> 
                          <a href="javascript:;" class="nav-link" style="padding:10px;">
                            <div class="" >
                              <div class="panel" style="background-color:#3b4354; margin-bottom:0px !important;"> 
                                <div class="panel-body" style="text-align: center;">
                                  <h4 style="color:#fff">'.$value->Job_Title.'</h4>
                                  <p  style="color:#ffd91e">'.$value->Location_Title.' - '.date("F d, Y",strtotime($value->Applied_Date)).'</p>
                                </div>
                              </div>
                            </div>
                          </a>
                        </li>'; 
                }
              } 
              else
              {
                echo no_record_found(); 
              }

            ?>
          </ul> 
        </div>
        <?php if($total_applications > 0){ ?>
        <div class="col-md-12 col-lg-12" style="padding: 0;"><hr></div>
        <div class="col-md-12 col-lg-12" id="applicant_interview_evaluations" style="padding: 0;"> 
            
        </div>
        <?php } ?>
      </div>
    </div> 
  </div> 

<style type="text/css">
  .application .active{
    color: #fff;
    background-color: #909bb1 !important;
    border: 0;
  }
</style>
 
  