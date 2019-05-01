 
<?php
 
  $record_data = array();
  if($edit_rec != "")
  {
    $this->db->where(array("employee_assets.Org_Id"=>$this->org_id,"employee_assets.Id"=>$edit_rec));
    $this->db->select("employee_assets.*,organization_assets.Name as Asset_Name ");
    $this->db->from("employee_assets"); 
    $this->db->join("organization_assets","organization_assets.Id = employee_assets.Asset_Id","left"); 
    $this->db->order_by("employee_assets.Id","ASC");
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
            <input type="hidden" name="Table_Name" id="Table_Name" value="employee_assets">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

             
           
            
            <div class="col-sm-3  col-xs-12">
              <div class="">
                <label class="control-label">Select Asset <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select name="Asset_Id" id="Asset_Id" onchange="get_institute(this,'Institute')" value="<?php if(isset($record_data['Asset_Id'])){ echo $record_data['Asset_Id']; } ?>" class="form-control select2 ">
                  <?php 
                    $this->db->order_by("Name","asc");
                    $organization_assets = $this->db->get_where("organization_assets",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                    if($organization_assets->num_rows() > 0)
                    {
                      foreach ($organization_assets->result() as $key => $value) 
                      {
                        $selected = "";
                        if(isset($record_data['Asset_Id'])){ if($record_data['Asset_Id'] == $value->Id ){ $selected = "selected='selected'"; } }
                         echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                      }
                    }
                   ?>
                  
                </select>
              </div>
            </div>
            
       
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Start Date: <span class="error_message" style="color: #f00;">  </span></label>
                <input type="text" class="form-control datepicker" placeholder="Start Date" name="Start_Date" id="Start_Date" value="<?php if(isset($record_data['Start_Date'])){ echo $record_data['Start_Date']; } ?>">
              </div>
            </div>
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">End Date: <span class="error_message" style="color: #f00;">  </span></label>
                <input type="text" class="form-control datepicker" placeholder="End Date" name="End_Date" id="End_Date" value="<?php if(isset($record_data['End_Date'])){ echo $record_data['End_Date']; } ?>">
              </div>
            </div> 
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Document <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="file" class="form-control" placeholder="Upload Document" name="Document" id="Document">
              </div>
            </div>
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Allowed Quantity<span class="error_message" style="color: #f00;">  </span></label>
                <input type="number" class="form-control" placeholder="Allowed Quantity" name="Allowed_Quantity" id="Allowed_Quantity" value="<?php if(isset($record_data['Allowed_Quantity'])){ echo $record_data['Allowed_Quantity']; } ?>">
              </div>
            </div>
            <div class="col-sm-9 col-xs-12 ">
              <div class="">
                <label class="control-label">Description<span class="error_message" style="color: #f00;">  </span></label>
                <input type="text" class="form-control" placeholder="Asset Description" name="Description" id="Description" value="<?php if(isset($record_data['Description'])){ echo $record_data['Description']; } ?>">
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

          $this->db->where(array("employee_assets.Org_Id"=>$this->org_id,"employee_assets.Deleted"=>0,"employee_assets.Employee_Id"=>$data));
          $this->db->select("employee_assets.*,organization_assets.Name as Asset_Name");
          $this->db->from("employee_assets"); 
          $this->db->join("organization_assets","organization_assets.Id = employee_assets.Asset_Id","left"); 
          $this->db->order_by("employee_assets.Id","ASC");
          $employee_assets = $this->db->get();

          if($employee_assets->num_rows() > 0)
          {  
            foreach ($employee_assets->result() as $key => $value){ 

              $document = "Not Uploaded";
              if($value->Document && $value->Document != "")
              {
                $src =  "assets/panel/userassets/employee_assets/".$value->Id."/".$value->Document;
                if(file_exists($src))
                {
                  $document = "<div><a target='_blank' href='".$src."'><i class='fa fa-file'></i> View Document</a></div>";
                } 
              }

              echo '<div class="col-md-12 col-sm-12" id="row_'.$value->Id.'">
                      <div class="panel panel-inverse">
                        <div class="panel-heading" style="padding:0px;">
                            <div class="row">  
                              <div class="col-md-12">
                                <div class="col-md-6">
                                  <h3 style="color:#fff;"><i class="fa fa-graduation-cap"></i> '.$value->Asset_Name.'</h3>
                                </div>
                                <div class="col-md-6">
                                  <a href="javascript:;" class="btn btn-sm" onclick="load_tab(this,\'form_employee_assets\','.$data.',\'employee_from_container\', '.$value->Id.')" style=" font-size:18px;float: right; color:#fff;" ><i class="fa fa-pencil"></i></a>
                                  <a href="javascript:;" class="btn btn-sm"  onclick="delete_record(\'employee_assets\','.$value->Id.',this)" style="color: red;float: right; font-size:18px;"> <i class="fa fa-trash"></i></a>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="panel-body" style="background: #dcdcdc;">
                           <div class="table-responsive">
                              <table class="table table-bordered table-primary nomargin"> 
                                  <tr> 
                                    <th>Asset Name</th>
                                    <td>'.$value->Asset_Name.'</td>
                                    <th>Allowed Quantity</th>
                                    <td>'.$value->Allowed_Quantity.'</td>
                                  </tr>
                                  <tr> 
                                    <th>Start Date</th>
                                    <td>'.$value->Start_Date.'</td>
                                    <th>End Date</th>
                                    <td>'.$value->End_Date.'</td>
                                  </tr> 
                                  <tr>  
                                    <th>Document</th>
                                    <td>'.$document.'</td>
                                    <th>Description</th>
                                    <td>'.$value->Description.'</td>
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