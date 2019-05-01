<?php  
  
  //$data = (array) json_decode($data);
  $this->db->where(array("applicant_interviews.Org_Id"=>$this->org_id,"applicant_interviews.Application_Id"=>$data['applicantion_id'],"applicant_interviews.Applicant_Id"=>$data['applicant_id']));
  $this->db->select("
                      applicant_interviews.*,
                      employee_work_record.Designation_Id,
                      designations.Name as Designation, 
                      employees.Photo as Intervewer_Photo, 
                      employees.First_Name as Interviewer_First_Name,
                      employees.Last_Name as Interviewer_Last_Name,
                      applicants.First_Name as Applicant_First_Name, 
                      applicants.Last_Name as Applicant_Last_Name
                  ");

  $this->db->from("applicant_interviews"); 
  $this->db->join("employee_work_record","employee_work_record.Id = (select max(Id) from employee_work_record as e2 where e2.Employee_Id = applicant_interviews.Interviewer ) ","left"); 
  $this->db->join("designations","designations.Id = employee_work_record.Designation_Id","left");
  $this->db->join("applicants","applicants.Id = applicant_interviews.Applicant_Id","left"); 
  $this->db->join("employees","employees.Id = applicant_interviews.Interviewer","left");  
  $this->db->order_by("applicant_interviews.Id","DESC");
  $applicant_interviews = $this->db->get();
 
  $j = 0;
  if($applicant_interviews->num_rows() > 0)
  {  
    foreach ($applicant_interviews->result() as $key => $value)
    { 
      $j = $j + 2;
      $src =  "assets/images/default-user.png";
      if($value->Intervewer_Photo && $value->Intervewer_Photo != "")
      {
        $src =  "assets/panel/userassets/employees/".$value->Interviewer."/".$value->Intervewer_Photo;
        if(!file_exists($src))
        {
           $src =  "assets/images/default-user.png";
        } 
      }

      if($this->user_id === $value->Interviewer || $this->user_data['User_Type'] === "Super_Admin")
      {

        $points_numbers = "";
        for ($i=1; $i <= 10; $i++) 
        { 
            $selected = "";
            if($value->Points == $i){ $selected = "selected='selected'"; }
            $points_numbers .= '<option '.$selected.' value="'.$i.'">'.$i.'</option>';
        }

          echo '<div class="col-md-12 col-sm-12">
                  <div class="panel-group" id="accordion8"> 
                    <div class="panel panel-inverse">
                      <div class="interview-panel-heading" style="padding:0px;">
                        <div class="row">  
                          <div class="col-md-12">
                            <a data-toggle="collapse" data-parent="#accordion8" href="#collapseOne_'.$key.'" aria-expanded="false" class="collapsed">
                              <div class="col-md-6">
                                <div class="interviewer_img" > 
                                   <img src="'.$src.'" class="pro-img"> 
                                </div>
                                <div class="emp-tabe-name interviewer_name_div"> 
                                    <div class="interviewer_name">'.$value->Interviewer_First_Name." ".$value->Interviewer_Last_Name.'</div> 
                                    <div class="interview_designation" >'.$value->Designation.'</div>  
                                </div>   
                              </div>
                            </a> 
                          </div>
                        </div>
                      </div> 
                      <div id="collapseOne_'.$key.'" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="background: #dcdcdc; text-align:left; padding:10px 20px;">';
                          

                            $this->db->where(array("questions.Org_Id"=>$this->org_id,"questions.Deleted"=>0,"questions.Status"=>1,"questions.Category_Id"=>$value->Question_Category ));
                            $this->db->select("questions.*,question_categories.Name as Category_Name,interview_evaluations.Answer ");
                            $this->db->from("questions"); 
                            $this->db->join("question_categories","question_categories.Id = questions.Category_Id","left");   
                            $this->db->join("interview_evaluations","interview_evaluations.Question_Id = questions.Id","left");   
                            $this->db->order_by("questions.Id","DESC"); 
                            $this->db->group_by("questions.Id"); 
                            $questions = $this->db->get();

                            if($questions->num_rows() > 0)
                            {
                              foreach ($questions->result() as $index => $question_data) 
                              {
                                $q_number = $index + 1;
                                $answer = $question_data->Answer;
                                $if_json = is_string($answer) && is_array(json_decode($answer, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
                                if($if_json){
                                  $answer = implode(",", json_decode($answer));
                                }

                                echo '<div class="row question" id="row_'.$question_data->Id.'"> 
                                          <div class="col-md-12 >  </div>
                                          <div class="col-md-12 question-statement">  
                                            <h3 class="interview_table_head" style="color:#6b6868; font-size:19px;">Q'. $q_number .': '.$question_data->Statement.'</h3> 
                                          </div>
                                          <div class="col-md-12 question-statement">  
                                            <p class="interview_question_ans"><span>ANS:</span>'.$answer.' </p> 
                                          </div>
                                      </div>';
                              }
                            }
                            else
                            {
                              echo '<h2>Interview is pending!</h2>';
                            }


          echo '</div></div></div></div></div>';
      }
    }
  }
  else
  {
    echo no_record_found();  
  }

?>