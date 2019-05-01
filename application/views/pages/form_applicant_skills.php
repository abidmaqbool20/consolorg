 
<?php
 
  $record_data = array();
  if($edit_rec != "")
  {
    $this->db->where(array("applicant_skills.Org_Id"=>$this->org_id,"applicant_skills.Id"=>$edit_rec));
    $this->db->select("applicant_skills.*,skills.Name as Skill_Name ");
    $this->db->from("applicant_skills"); 
    $this->db->join("skills","skills.Id = applicant_skills.Skill_Id","left"); 
    $this->db->order_by("applicant_skills.Id","ASC");
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
            <input type="hidden" name="Applicant_Id" id="Applicant_Id" value="<?= $data; ?>">
            <input type="hidden" name="Table_Name" id="Table_Name" value="applicant_skills">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

             <div class="col-sm-6  col-xs-12">
              <div class="">
                <label class="control-label">Select Skill <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select name="Skill_Id" id="Skill_Id"  value="<?php if(isset($record_data['Skill_Id'])){ echo $record_data['Skill_Id']; } ?>" class="form-control select2 ">
                  <?php 
                    $this->db->order_by("Name","asc");
                    $skills = $this->db->get_where("skills");
                    if($skills->num_rows() > 0)
                    {
                      foreach ($skills->result() as $key => $value) 
                      {
                        $selected = "";
                        if(isset($record_data['Skill_Id'])){ if($record_data['Skill_Id'] == $value->Id ){ $selected = "selected='selected'"; } }
                         echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                      }
                    }
                   ?>
                  
                </select>
              </div>
            </div>
           
            <div class="col-sm-6 col-xs-12 ">
              <div class="">
                <label class="control-label">Skill Level <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select class="form-control select2" id="Skill_Level" name="Skill_Level" value="<?php if(isset($record_data['Skill_Level']) && $record_data['Skill_Level'] != "" ){ echo $record_data['Skill_Level']; } ?>">
                  <option value="0">Select Degree Type</option> 
                      <option  <?php if(isset($record_data['Skill_Level'])){ if($record_data['Skill_Level'] == "Basic"){ echo "selected='selected'"; } } ?> value="Basic">Basic</option>
                      <option  <?php if(isset($record_data['Skill_Level'])){ if($record_data['Skill_Level'] == "Intermediate"){ echo "selected='selected'"; } } ?>  value="Intermediate">Intermediate</option>
                      <option  <?php if(isset($record_data['Skill_Level'])){ if($record_data['Skill_Level'] == "Average"){ echo "selected='selected'"; } } ?>  value="Average">Average</option>
                      <option  <?php if(isset($record_data['Skill_Level'])){ if($record_data['Skill_Level'] == "Expert"){ echo "selected='selected'"; } } ?>  value="Expert">Expert</option> 
                </select> 
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

          $this->db->where(array("applicant_skills.Org_Id"=>$this->org_id,"applicant_skills.Deleted"=>0,"applicant_skills.Applicant_Id"=>$data)); 
          $this->db->select("applicant_skills.*,skills.Name as Skill_Name ");
          $this->db->from("applicant_skills"); 
          $this->db->join("skills","skills.Id = applicant_skills.Skill_Id","left"); 
          $this->db->order_by("applicant_skills.Id","ASC");
          $applicant_skills = $this->db->get();

          if($applicant_skills->num_rows() > 0)
          {  
            
              
              echo '<div class="col-md-12 col-sm-12" >
                      <div class="panel panel-inverse">
                        <div class="panel-heading" style="padding:0px;">
                            <div class="row">  
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <h3 style="color:#fff;"><i class="fa fa-graduation-cap"></i> Skills </h3>
                                </div>
                                
                              </div>
                            </div>
                        </div>
                        <div class="panel-body" style="background: #dcdcdc;">
                           <div class="table-responsive">
                              <table class="table table-bordered table-primary nomargin "> 
                               
                      ';
                             foreach ($applicant_skills->result() as $key => $value)
                             {  

                               echo '<tr id="row_'.$value->Id.'"> 
                                        <th>'.$value->Skill_Name.'</th>
                                        <td>'.$value->Skill_Level.'</td>
                                        <th>
                                          <a href="javascript:;" style="color: blue;" onclick="load_tab(this,\'form_applicant_skills\','.$data.',\'employee_from_container\', '.$value->Id.')" > <i class="fa fa-pencil"></i> </a>&nbsp;&nbsp;
                                          <a href="javascript:;" onclick="delete_record(\'applicant_skills\','.$value->Id.',this)" style="color: red;"> <i class="fa fa-trash"></i></a>
                                        </th> 
                                      </tr>
                                     ';

                              }

              echo '</table> </div> </div> </div>  </div>';
          
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