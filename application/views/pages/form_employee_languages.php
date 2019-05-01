 
<?php
 
  $record_data = array();
  if($edit_rec != "")
  {
    $this->db->where(array("employee_languages.Org_Id"=>$this->org_id,"employee_languages.Id"=>$edit_rec));
    $this->db->select("employee_languages.*,languages.name as Language_Name ");
    $this->db->from("employee_languages"); 
    $this->db->join("languages","languages.id = employee_languages.Language_Id","left"); 
    $this->db->order_by("employee_languages.Id","ASC");
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
            <input type="hidden" name="Table_Name" id="Table_Name" value="employee_languages">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

             <div class="col-sm-6  col-xs-12">
              <div class="">
                <label class="control-label">Select Language <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select name="Language_Id" id="Language_Id"  value="<?php if(isset($record_data['Language_Id'])){ echo $record_data['Language_Id']; } ?>" class="form-control select2 ">
                  <?php 
                    $this->db->order_by("name","asc");
                    $languages = $this->db->get_where("languages");
                    if($languages->num_rows() > 0)
                    {
                      foreach ($languages->result() as $key => $value) 
                      {
                        $selected = "";
                        if(isset($record_data['Language_Id'])){ if($record_data['Language_Id'] == $value->id ){ $selected = "selected='selected'"; } }
                         echo '<option '.$selected.' value="'.$value->id.'">'.$value->name.'</option>';
                      }
                    }
                   ?>
                  
                </select>
              </div>
            </div>
           
            <div class="col-sm-6 col-xs-12 ">
              <div class="">
                <label class="control-label">Language Level <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select class="form-control select2" id="Language_Level" name="Language_Level" value="<?php if(isset($record_data['Language_Level']) && $record_data['Language_Level'] != "" ){ echo $record_data['Language_Level']; } ?>">
                  <option value="0">Select Degree Type</option> 
                      <option  <?php if(isset($record_data['Language_Level'])){ if($record_data['Language_Level'] == "Basic"){ echo "selected='selected'"; } } ?> value="Basic">Basic</option>
                      <option  <?php if(isset($record_data['Language_Level'])){ if($record_data['Language_Level'] == "Intermediate"){ echo "selected='selected'"; } } ?>  value="Intermediate">Intermediate</option>
                      <option  <?php if(isset($record_data['Language_Level'])){ if($record_data['Language_Level'] == "Average"){ echo "selected='selected'"; } } ?>  value="Average">Average</option>
                      <option  <?php if(isset($record_data['Language_Level'])){ if($record_data['Language_Level'] == "Expert"){ echo "selected='selected'"; } } ?>  value="Expert">Expert</option> 
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

          $this->db->where(array("employee_languages.Org_Id"=>$this->org_id,"employee_languages.Deleted"=>0,"employee_languages.Employee_Id"=>$data)); 
          $this->db->select("employee_languages.*,languages.name as Language_Name ");
          $this->db->from("employee_languages"); 
          $this->db->join("languages","languages.id = employee_languages.Language_Id","left"); 
          $this->db->order_by("employee_languages.Id","ASC");
          $employee_languages = $this->db->get();

          if($employee_languages->num_rows() > 0)
          {  
            
              
              echo '<div class="col-md-12 col-sm-12" >
                      <div class="panel panel-inverse">
                        <div class="panel-heading" style="padding:0px;">
                            <div class="row">  
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <h3 style="color:#fff;"><i class="fa fa-graduation-cap"></i> Languages </h3>
                                </div>
                                
                              </div>
                            </div>
                        </div>
                        <div class="panel-body" style="background: #dcdcdc;">
                           <div class="table-responsive">
                              <table class="table table-bordered table-primary nomargin"> 
                               
                      ';
                             foreach ($employee_languages->result() as $key => $value)
                             {  

                               echo ' <tr id="row_'.$value->Id.'"> 
                                        <th>'.$value->Language_Name.'</th>
                                        <td>'.$value->Language_Level.'</td>
                                        <th>
                                          <a href="javascript:;" style="color: blue;" onclick="load_tab(this,\'form_employee_languages\','.$data.',\'employee_from_container\', '.$value->Id.')" > <i class="fa fa-pencil"></i> </a>&nbsp;&nbsp;
                                          <a href="javascript:;" onclick="delete_record(\'employee_languages\','.$value->Id.',this)" style="color: red;"> <i class="fa fa-trash"></i></a>
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