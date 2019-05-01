 
<?php
 
  $record_data = array();
  if($edit_rec != "")
  {
    $this->db->where(array("employee_salary_record.Org_Id"=>$this->org_id,"employee_salary_record.Id"=>$edit_rec));
    $this->db->select("employee_salary_record.*");
    $this->db->from("employee_salary_record");  
    $this->db->order_by("employee_salary_record.Id","ASC");
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
            <input type="hidden" name="Table_Name" id="Table_Name" value="employee_salary_record">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 
 
           
           
            
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Salary <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control" placeholder="Enter Salary" name="Salary" id="Salary" value="<?php if(isset($record_data['Salary'])){ echo $record_data['Salary']; } ?>">
              </div>
            </div>

            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Currency  <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select name="Currency" id="Currency" value="<?php if(isset($record_data['Currency'])){ echo $record_data['Currency']; } ?>" class="form-control select2 ">
                  <?php 

                    $this->db->group_by("Name");
                    $this->db->order_by("Name","asc");
                    $currency = $this->db->get_where("currency");
                    if($currency->num_rows() > 0)
                    {
                      foreach ($currency->result() as $key => $value) 
                      {
                        $selected = "";
                        if(isset($record_data['Currency'])){ if($record_data['Currency'] == $value->Id ){ $selected = "selected='selected'"; } }
                         echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.' ( '.$value->Symbol.' )</option>';
                      }
                    }
                   ?>
                  
                </select> 
              </div>
            </div>
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Start Date <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control datepicker required" placeholder="Start Date" name="Start_Date" id="Start_Date" value="<?php if(isset($record_data['Start_Date'])){ echo $record_data['Start_Date']; } ?>">
              </div>
            </div>
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">End Date <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control datepicker required" placeholder="End Date" name="End_Date" id="End_Date" value="<?php if(isset($record_data['End_Date'])){ echo $record_data['End_Date']; } ?>">
              </div>
            </div>
            <div class="col-sm-12 col-xs-12 ">
              <div class="">
                <label class="control-label">Description <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <textarea name="Description" id="Description" class="form-control" ><?php if(isset($record_data['Description'])){ echo $record_data['Description']; } ?></textarea>
                <script> CKEDITOR.replace( 'Description' );</script> 
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

          $this->db->where(array("employee_salary_record.Org_Id"=>$this->org_id,"employee_salary_record.Deleted"=>0,"employee_salary_record.Employee_Id"=>$data));
          $this->db->select("employee_salary_record.*,currency.Symbol as Currency");
          $this->db->from("employee_salary_record");  
          $this->db->join("currency","currency.Id = employee_salary_record.Currency","left");
          $this->db->order_by("employee_salary_record.Id","DESC");
          $employee_salary_record = $this->db->get();

          if($employee_salary_record->num_rows() > 0)
          {  
            foreach ($employee_salary_record->result() as $key => $value){  

              

              $end_date = "Till Today";
              $start_date = date("d F, Y",strtotime($value->Start_Date));
              if($value->End_Date && $value->End_Date != "" && $value->End_Date != ""){ $end_date = date("d F, Y",strtotime($value->End_Date)); }
              
              echo '<div class="col-md-12 col-sm-12" id="row_'.$value->Id.'">
                      <div class="panel panel-inverse">
                        <div class="panel-heading" style="padding:0px;">
                            <div class="row">  
                              <div class="col-md-12">
                                <div class="col-md-6">
                                  <h3 style="color:#fff;"><i class="fa fa-building"></i> '.$start_date.' - '.$end_date.'</h3>
                                </div>
                                <div class="col-md-6">
                                  <a href="javascript:;" class="btn btn-sm" onclick="load_tab(this,\'form_employee_work_info\','.$data.',\'employee_from_container\', '.$value->Id.')" style=" font-size:18px;float: right; color:#fff;" ><i class="fa fa-pencil"></i></a>
                                  <a href="javascript:;" class="btn btn-sm"  onclick="delete_record(\'employee_salary_record\','.$value->Id.',this)" style="color: red;float: right; font-size:18px;"> <i class="fa fa-trash"></i></a>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="panel-body" style="background: #dcdcdc;">
                           <div class="table-responsive">
                              <table class="table table-bordered table-primary nomargin"> 
                                  <tr> 
                                    <th>From Date</th>
                                    <td>'.$start_date.'</td>
                                    <th>To Date</th>
                                    <td>'.$end_date.'</td>
                                  </tr>
                                  <tr> 
                                    <th>Salary </th>
                                    <td colspan="3">'.$value->Currency.' '.$value->Salary.'</td> 
                                  </tr> 
                                  <tr> 
                                    <th>Job Description</th>
                                    <td colspan="3">'.$value->Description.'</td> 
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
      
         

 


<script type="text/javascript">
  $('.select2').select2(); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
</script>