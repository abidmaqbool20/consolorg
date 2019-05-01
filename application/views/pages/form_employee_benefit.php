 
<?php
 
  $record_data = array();
  if($edit_rec != "")
  {
    $this->db->where(array("employee_benefits.Org_Id"=>$this->org_id,"employee_benefits.Id"=>$edit_rec));
    $this->db->select("employee_benefits.*,benefit_types.Name as Benefit_Type_Name ");
    $this->db->from("employee_benefits"); 
    $this->db->join("benefit_types","benefit_types.Id = employee_benefits.Benefit_Type_Id","left"); 
    $this->db->order_by("employee_benefits.Id","ASC");
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
            <input type="hidden" name="Table_Name" id="Table_Name" value="employee_benefits">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

            <div class="col-sm-6  col-xs-12">
              <div class="">
                <label class="control-label">Select Benefit Type <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select name="Benefit_Type_Id" id="Benefit_Type_Id"  value="<?php if(isset($record_data['Benefit_Type_Id'])){ echo $record_data['Benefit_Type_Id']; } ?>" class="form-control select2 ">
                  <?php 
                    $this->db->order_by("Name","asc");
                    $benefit_types = $this->db->get_where("benefit_types");
                    if($benefit_types->num_rows() > 0)
                    {
                      foreach ($benefit_types->result() as $key => $value) 
                      {
                        $selected = "";
                        if(isset($record_data['Benefit_Type_Id'])){ if($record_data['Benefit_Type_Id'] == $value->Id ){ $selected = "selected='selected'"; } }
                         echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                      }
                    }
                   ?>
                  
                </select>
              </div>
            </div>
            <div class="col-sm-6 col-xs-12 ">
              <div class="">
                <label class="control-label">Benefit Duration <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select class="form-control select2" id="Benefit_Duration" name="Benefit_Duration" value="<?php if(isset($record_data['Benefit_Duration']) && $record_data['Benefit_Duration'] != "" ){ echo $record_data['Benefit_Duration']; } ?>">
                  <option value="0">Select Benefit Duration</option> 
                      <option  <?php if(isset($record_data['Benefit_Duration'])){ if($record_data['Benefit_Duration'] == "Daily"){ echo "selected='selected'"; } } ?> value="Daily">Daily</option>
                      <option  <?php if(isset($record_data['Benefit_Duration'])){ if($record_data['Benefit_Duration'] == "Weekly"){ echo "selected='selected'"; } } ?>  value="Weekly">Weekly</option>
                      <option  <?php if(isset($record_data['Benefit_Duration'])){ if($record_data['Benefit_Duration'] == "Monthly"){ echo "selected='selected'"; } } ?>  value="Monthly">Monthly</option>
                      <option  <?php if(isset($record_data['Benefit_Duration'])){ if($record_data['Benefit_Duration'] == "Yearly"){ echo "selected='selected'"; } } ?>  value="Yearly">Yearly</option> 
                </select> 
              </div>
            </div> 
            <div class="col-sm-6 col-xs-12 ">
              <div class="">
                <label class="control-label">Benefit Worth <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control required " required="" id="Benefit_Worth" name="Benefit_Worth" value="<?php if(isset($record_data['Benefit_Worth'])){ echo $record_data['Benefit_Worth']; } ?>">
              </div>
            </div> 
            <div class="col-sm-6 col-xs-12 ">
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

          $this->db->where(array("employee_benefits.Org_Id"=>$this->org_id,"employee_benefits.Deleted"=>0,"employee_benefits.Employee_Id"=>$data));  
          $this->db->select("employee_benefits.*,benefit_types.Name as Benefit_Type_Name ,currency.Symbol as Currency");
          $this->db->from("employee_benefits"); 
          $this->db->join("benefit_types","benefit_types.Id = employee_benefits.Benefit_Type_Id","left"); 
          $this->db->join("currency","currency.Id = employee_benefits.Currency","left");
          $this->db->order_by("employee_benefits.Id","ASC"); 
          $employee_benefits = $this->db->get();

          if($employee_benefits->num_rows() > 0)
          {   
              
              echo '<div class="col-md-12 col-sm-12" >
                      <div class="panel panel-inverse">
                        <div class="panel-heading" style="padding:0px;">
                            <div class="row">  
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <h3 style="color:#fff;"><i class="fa fa-graduation-cap"></i> Employee Benefits </h3>
                                </div>
                                
                              </div>
                            </div>
                        </div>
                        <div class="panel-body" style="background: #dcdcdc;">
                           <div class="table-responsive">
                              <table class="table table-bordered table-primary nomargin"> 
                               
                      ';
                             foreach ($employee_benefits->result() as $key => $value)
                             {  

                               echo ' <tr id="row_'.$value->Id.'"> 
                                        <th>'.$value->Benefit_Type_Name.'</th>
                                        <td>'.$value->Benefit_Duration.'</td>
                                        <td>'.$value->Currency.' '.$value->Benefit_Worth.'</td> 
                                        <th>
                                          <a href="javascript:;" style="color: blue;" onclick="load_tab(this,\'form_employee_benefit\','.$data.',\'employee_from_container\', '.$value->Id.')" > <i class="fa fa-pencil"></i> </a>&nbsp;&nbsp;
                                          <a href="javascript:;" onclick="delete_record(\'employee_benefits\','.$value->Id.',this)" style="color: red;"> <i class="fa fa-trash"></i></a>
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