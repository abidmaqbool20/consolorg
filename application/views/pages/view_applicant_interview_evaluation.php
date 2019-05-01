<div class="panel-body">
	<div class="form-group"> 
        <?php 


          $this->db->where(array("applicant_interviews.Org_Id"=>$this->org_id,"applicant_interviews.Applicant_Id"=>$data));
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
                for ($i=1; $i <= 10; $i++) { $selected = "";
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
                                    $this->db->select("questions.*,question_categories.Name as Category_Name ");
                                    $this->db->from("questions"); 
                                    $this->db->join("question_categories","question_categories.Id = questions.Category_Id","left");   
                                    $this->db->order_by("questions.Id","DESC"); 
                                    $this->db->group_by("questions.Id"); 
                                    $questions = $this->db->get();

                                    if($questions->num_rows() > 0)
                                    {
                                      foreach ($questions->result() as $index => $question_data) 
                                      {
                                        $q_number = $index + 1;
                                        echo '<div class="row question" id="row_'.$question_data->Id.'"> 
                                                  <div class="col-md-12 save_update_'.$question_data->Id.'">  </div>
                                                  <div class="col-md-12 question-statement">  
                                                    <h5 class="interview_table_head">Q'. $q_number .': '.$question_data->Statement.'</h5> 
                                                  </div>';

                                        $question_content = "";
                                        $option_less_types = array("text","number");
                                        if( in_array($question_data->Type, $option_less_types))
                                        {
                                           
                                          $q_ans = $this->db->get_where("interview_evaluations",array("Question_Id"=>$question_data->Id));;
                                          if($q_ans->num_rows() > 0){ $q_ans = $q_ans->row()->Answer; }else{$q_ans = "";}
                                          if($question_data->Type == "text"){
                                            $question_content = '<div class="col-md-12"><input onblur="save_interview_question(this,'.$value->Id.','.$value->Interviewer.','.$question_data->Id.')" type="text" class="form-control" name="Question_'.$question_data->Id.'" id="Question_'.$question_data->Id.'" value="'.$q_ans.'"></div>';
                                          }
                                          if($question_data->Type == "number"){
                                            $question_content = '<div class="col-md-12"><input onblur="save_interview_question(this,'.$value->Id.','.$value->Interviewer.','.$question_data->Id.')" type="number" class="form-control" name="Question_'.$question_data->Id.'" id="Question_'.$question_data->Id.'" value="'.$q_ans.'"></div>';
                                          }
                                        }
                                        else
                                        {
                                          $question_options = $this->db->get_where("question_values",array("Deleted"=>0,"Status"=>1,"Question_Id"=>$question_data->Id));
                                          if($question_options->num_rows() > 0)
                                          { 
                                            $q_ans = $this->db->get_where("interview_evaluations",array("Question_Id"=>$question_data->Id));
                                            if($q_ans->num_rows() > 0){ $q_ans = $q_ans->row()->Answer; }else{$q_ans = "";}
                                            if($question_data->Type == "dropdown")
                                            {
                                              $options = "";
                                              foreach ($question_options->result() as $qindex => $q_opt) { $selected ="";
                                                if($q_ans == $q_opt->Value){ $selected = 'selected="selected"'; }
                                                $options .= '<option '.$selected.' value="'.$q_opt->Value.'">'.$q_opt->Value.'</option>';
                                              } 

                                              $question_content = '<div class="col-md-12 question-options">
                                                                      <select onchange="save_interview_question(this,'.$value->Id.','.$value->Interviewer.','.$question_data->Id.')" name="Question_'.$question_data->Id.'" class="form-control select2">'.$options.'</select> 
                                                                      
                                                                   </div>';
                                            }
                                            elseif($question_data->Type == "radio")
                                            { 

                                              foreach ($question_options->result() as $qindex => $q_opt) {  $checked = ""; 
                                                if($q_ans == $q_opt->Value){ $checked = 'checked="checked"'; }
                                                $question_content .= '<div class="col-md-12 question-options">
                                                                        <label class="rdiobox">
                                                                          <input '.$checked.' type="radio" onchange="save_interview_question(this,'.$value->Id.','.$value->Interviewer.','.$question_data->Id.')" name="Question_'.$question_data->Id.'" value="'.$q_opt->Value.'" >
                                                                          <span>'.$q_opt->Value.'</span>
                                                                        </label>
                                                                      </div>';
                                              }  
                                            }
                                            elseif($question_data->Type == "checkbox")
                                            {  
                                              $q_ans = json_decode($q_ans);
                                              foreach ($question_options->result() as $qindex => $q_opt){  
                                                $checked = "";
                                                if(in_array( $q_opt->Value, $q_ans )){ $checked = 'checked="checked"'; }
                                                $question_content .= '<div class="col-md-12 question-options">
                                                                        <label class="ckbox ckbox-primary">
                                                                          <input '.$checked.' type="checkbox" onclick="save_interview_question(this,'.$value->Id.','.$value->Interviewer.','.$question_data->Id.')" value="'.$q_opt->Value.'" id="Question_'.$question_data->Id.'"><span>'.$q_opt->Value.'</span>
                                                                        </label>
                                                                      </div>';
                                              }

                                              
                                            }
                                          } 
                                        }
                                       
                                        echo $question_content .'</div>'; 
                                      } 
                                    }
                                    
                                    $k = $j + 1;

                                    echo '<div class="row question">
                                            <div class="col-md-12 save_update_interviewer_reviews_'.$j.' error" style="margin-bottom:0px;"> </div>
                                            <div class="col-md-12 question-statement"> 
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <h5 class="interview_table_head">Write your reviews and suggesstions about this candidate.</h5> 
                                                </div>
                                                <!--- <div class="col-md-4 col-sm-4 col-xs-12" style="text-align:right;">
                                                    <div style="display:inline;">Speaking</div>&nbsp; 
                                                    <label class="switch">
                                                      <input type="checkbox" onclick="change_mode(this,'.$value->Interviewer.')" >
                                                      <span class="slider"></span>
                                                    </label> 
                                                </div> -->

                                            </div>
                                            <div class="col-md-12 question-options">
                                              <input type="hidden" id="speaking_mode" value="no">
                                              <textarea onkeyup="write_speaking(this,\'error\')" onchange="save_interviewer_opinions(this,'.$value->Id.','.$value->Interviewer.','.$j.')" id="Reviews_'.$value->Interviewer.'" class="form-control" rows="3">'.$value->Reviews.'</textarea>  
                                            </div>
                                          </div>
                                          <div class="row question">
                                            <div class="col-md-12 save_update_interviewer_reviews_'.$k.'"></div>
                                            <div class="col-md-12 question-statement">  
                                              <h5 class="interview_table_head">How do you rate this candidate?</h5> 
                                            </div>
                                            <div class="col-md-12 question-options">
                                              <select onchange="save_interviewer_opinions(this,'.$value->Id.','.$value->Interviewer.','.$k.')" id="Points_'.$value->Interviewer.'" class="form-control select2">
                                                '.$points_numbers.'
                                              </select> 
                                            </div>
                                          </div>
                                          </div></div></div></div></div>';
              }
              else
              {
                echo no_record_found();
              }
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

    