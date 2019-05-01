 
<?php
 
  $record_data = array();
  if($edit_rec != "")
  {
    $this->db->where(array("employee_report_to.Org_Id"=>$this->org_id,"employee_report_to.Id"=>$edit_rec));
    $this->db->select("employee_report_to.* ");
    $this->db->from("employee_report_to");  
    $this->db->order_by("employee_report_to.Id","ASC");
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
            <input type="hidden" name="Table_Name" id="Table_Name" value="employee_report_to">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

             
            
            <div class="col-sm-12  col-xs-12">
              <div class="">
                <label class="control-label">Report To<span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select name="Report_To" id="Report_To" value="<?php if(isset($record_data['Report_To'])){ echo $record_data['Report_To']; } ?>" class="form-control select2 ">
                  <?php 
                    $this->db->order_by("First_Name","asc");
                    $this->db->select("First_Name,Last_Name, Id" );
                    $employees = $this->db->get_where("employees",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                    if($employees->num_rows() > 0)
                    {
                      foreach ($employees->result() as $key => $value) 
                      {
                        $selected = "";
                        if(isset($record_data['Report_To'])){ if($record_data['Report_To'] == $value->Id ){ $selected = "selected='selected'"; } }
                         echo '<option '.$selected.' value="'.$value->Id.'">'.$value->First_Name.' '.$value->Last_Name.'</option>';
                      }
                    }
                   ?>
                  
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

          $this->db->where(array("employee_report_to.Org_Id"=>$this->org_id,"employee_report_to.Deleted"=>0,"employee_report_to.Employee_Id"=>$data));
          $this->db->select("employee_report_to.*,employees.First_Name,employees.Last_Name  ");
          $this->db->from("employee_report_to"); 
          $this->db->join("employees","employees.Id = employee_report_to.Report_To","left"); 
          $this->db->order_by("employee_report_to.Id","ASC");
          $employee_report_to = $this->db->get();

          if($employee_report_to->num_rows() > 0)
          {  

               
               echo '<div class="col-md-12 col-sm-12" >
                      <div class="panel panel-inverse">
                        <div class="panel-heading" style="padding:0px;">
                            <div class="row">  
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <h3 style="color:#fff;"><i class="fa fa-graduation-cap"></i> Report To </h3>
                                </div>
                                
                              </div>
                            </div>
                        </div>
                        <div class="panel-body" style="background: #dcdcdc;">
                           <div class="table-responsive">
                              <table class="table table-bordered table-primary nomargin"> 
                               
                      ';
                             foreach ($employee_report_to->result() as $key => $value)
                             {  

                               echo ' <tr id="row_'.$value->Id.'"> 
                                        <th>'.$value->First_Name.' '.$value->Last_Name.'</th> 
                                        <th>
                                          <a href="javascript:;" style="color: blue;" onclick="load_tab(this,\'form_employee_report_to\','.$data.',\'employee_from_container\', '.$value->Id.')" > <i class="fa fa-pencil"></i> </a>&nbsp;&nbsp;
                                          <a href="javascript:;" onclick="delete_record(\'employee_report_to\','.$value->Id.',this)" style="color: red;"> <i class="fa fa-trash"></i></a>
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