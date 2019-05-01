 
<?php
  
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Manage Questions","Deleted"=>0,"Status"=>1));
  if($module_data->num_rows() > 0)
  {
    $module_info = $module_data->result_array();
    $module_id = $module_info[0]['Id'];
  }


  $record_data = array();
  if($data && $data != "")
  {
    $this->db->where(array("questions.Org_Id"=>$this->org_id,"questions.Id"=>$data));
    $this->db->select("questions.*  ");
    $this->db->from("questions");  
    $this->db->order_by("questions.Id","ASC");
    $check_record = $this->db->get();

    if($check_record->num_rows() > 0)
    {
      $record_data = $check_record->result_array();
      $record_data = $record_data[0]; 
    }
  }


  $totla_values = 1;
?>

<style>

</style>
<div class="row">
  <div class="col-md-12"> 
    <form id="common_form" method="post" action="<?= base_url("admin/save_question") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
      <div class="panel">
        <div class="panel-body">  
              <div class="error"></div>
              <div class="form-group">
               
                <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id']) && $record_data['Id'] != "" ){ echo $record_data['Id']; } ?>"> 
                <input type="hidden" name="Table_Name" id="Table_Name" value="questions">
                <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

                 
               
                 <div class="col-sm-12 col-xs-12 ">
                  <div class="">
                    <label class="control-label">Statement<span class="text-danger">*</span><span class="text-danger error"></span></label>
                    <input type="text" class="form-control" placeholder="Enter Statement" name="Statement" id="Statement" value="<?php if(isset($record_data['Statement'])){ echo $record_data['Statement']; } ?>">
                  </div>
                </div>
                <div class="col-sm-4  col-xs-12">
                  <div class="">
                    <label class="control-label">Select Question Category <span class="text-danger">*</span><span class="text-danger error"></span></label>
                    <select name="Category_Id" id="Category_Id" value="<?php if(isset($record_data['Category_Id'])){ echo $record_data['Category_Id']; } ?>" class="form-control select2 ">
                      <option value="0">Select Category </option>
                      <?php 

                        $this->db->select("Name,Id");
                        $this->db->order_by("Name","asc");
                        $question_categories = $this->db->get_where("question_categories",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                        if($question_categories->num_rows() > 0)
                        {
                          foreach ($question_categories->result() as $key => $value) 
                          {
                            $selected = "";
                            if(isset($record_data['Category_Id'])){ if($record_data['Category_Id'] == $value->Id ){ $selected = "selected='selected'"; } }
                             echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                          }
                        }

                       ?>
                      
                    </select>
                  </div>
                </div> 
                <div class="col-sm-4  col-xs-12">
                  <div class="">
                    <label class="control-label">Select Question Type<span class="text-danger">*</span><span class="text-danger error"></span></label>
                    <select name="Type" id="Type" value="<?php if(isset($record_data['Type'])){ echo $record_data['Type']; } ?>" class="form-control select2 ">
                      <option value="0">Select Type </option>
                      <option value="text" <?php if(isset($record_data['Type']) && $record_data['Type'] == 'text'){ echo 'selected="selected"'; } ?>>Text</option>
                      <option value="number" <?php if(isset($record_data['Type']) && $record_data['Type'] == 'number'){ echo 'selected="selected"'; } ?>>Numeric</option>
                      <option value="checkbox" <?php if(isset($record_data['Type']) && $record_data['Type'] == 'checkbox'){ echo 'selected="selected"'; } ?>>Checkbox</option>
                      <option value="radio" <?php if(isset($record_data['Type']) && $record_data['Type'] == 'radio'){ echo 'selected="selected"'; } ?>>Radio</option>
                      <option value="dropdown" <?php if(isset($record_data['Type']) && $record_data['Type'] == 'dropdown'){ echo 'selected="selected"'; } ?>>Dropdown</option> 
                    </select>
                  </div>
                </div>
                <div class="col-sm-4  col-xs-12">
                  <div class="">
                    <label class="control-label">Select Sort Order<span class="text-danger">*</span><span class="text-danger error"></span></label>
                    <select name="Sort_Order" id="Sort_Order" value="<?php if(isset($record_data['Sort_Order'])){ echo $record_data['Sort_Order']; } ?>" class="form-control select2 ">
                      <option value="0">Select Sort Order </option>
                      <?php  
                          
                        foreach (range(0, 50) as $number)  
                        {
                          $selected = "";
                          if(isset($record_data['Sort_Order'])){ if($record_data['Sort_Order'] == $number ){ $selected = "selected='selected'"; } }
                           echo '<option '.$selected.' number="'.$number.'">'.$number.'</option>';
                        }
                        

                       ?>
                      
                    </select>
                  </div>
                </div>  
            </div>
            <hr>
            <div class="">
              <div class="row" style="background: #c1c1c1; margin: 0; padding: 10px 0px;">
                <div class="col-md-12 col-sm-12 col-xs-12" id="question_values_records">
                 <?php 

                    if(isset($record_data['Id']))
                    {
                      $qvalues = $this->db->get_where("question_values",array("Question_Id"=>$record_data['Id'],"Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                       
                      if($qvalues->num_rows() > 0)
                      {
                        foreach ($qvalues->result() as $key => $value) { 

                   ?>
                    <div  id="val_<?= $totla_values; ?>">
                      <div class="col-md-5 col-sm-5 col-xs-10 ">
                        <div class=""> 
                          <input type="text" class="form-control" placeholder="Enter value" name="Question_Values[]" id="Question_Values[]" value="<?= $value->Value; ?>">
                        </div>
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-2 ">
                        <div class="">
                           <?php if(in_array($module_id."_add", $this->role_permissions)){ ?>
                          <button type="button" onclick="add_val(this,<?= $totla_values; ?>)" class="btn btn-default btn-quirk"><i class="fa fa-plus"></i></button>
                           <?php }if(in_array($module_id."_delete", $this->role_permissions)){ ?>
                          <button type="button" style="background:#d9534f;color:white;" onclick="delete_val(this,<?= $totla_values; ?>)" class="btn btn-default btn-quirk"><i class="fa fa-minus"></i></button>
                           <?php } ?>
                        </div>
                      </div>
                    </div>
                  <?php 
                   $totla_values++; }}
                    else
                    {
                  ?> 
                  <div  id="val_1">
                    <div class="col-md-5 col-sm-5 col-xs-10 " id="val_<?= $totla_values; ?>">
                      <div class=""> 
                        <input type="text" class="form-control" placeholder="Enter value" name="Question_Values[]" id="Question_Values[]" value="">
                      </div>
                    </div>
                    <div class="col-md-1 col-sm-1 col-xs-2 ">
                      <div class="">
                        <button type="button" onclick="add_val(this)" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                <?php }}else{ ?>
                  <div  id="val_1">
                    <div class="col-md-5 col-sm-5 col-xs-10 " id="val_<?= $totla_values; ?>">
                      <div class=""> 
                        <input type="text" class="form-control" placeholder="Enter value" name="Question_Values[]" id="Question_Values[]" value="">
                      </div>
                    </div>
                    <div class="col-md-1 col-sm-1 col-xs-2 ">
                      <div class="">
                        <button type="button" onclick="add_val(this)" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                <?php } ?>
                </div>
              </div>
            </div> 
        </div>  
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="">
            <button type="submit" style="float: left;" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
            <button style="float: right; margin-left: 5px;" onclick="load_tab(this,'manage_questions',<?= 0; ?>,'questions_tabs_body')"  type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            <button  style="float: right;" onclick="load_tab(this,'form_question_add',<?= 0; ?>,'questions_tabs_body')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
          </div>
        </div>
      </div>

    </form> 
  </div>
</div>    
 

<script type="text/javascript">
  $('.select2').select2(); 
   
  $values = <?= $totla_values; ?>;
  function add_val($this,$value)
  {
    $values = +$values + 1;
    $("#question_values_records").append('<div id="val_'+$values+'"><div class="col-md-5 col-sm-5 col-xs-10 ">'+
                                            '<div class="">'+ 
                                                '<input type="text" class="form-control" placeholder="Enter value" name="Question_Values[]" id="Question_Values[]" value="">'+
                                              '</div>'+
                                            '</div>'+
                                            '<div class="col-md-1 col-sm-1 col-xs-2 ">'+
                                              '<div class="">'+
                                                '<button type="button" onclick="add_val(this,'+$values+')" class="btn btn-default btn-quirk"><i class="fa fa-plus"></i></button>'+
                                                '<button style="background:#d9534f;color:white;" type="button" onclick="delete_val(this,'+$values+')" class="btn btn-default btn-quirk"><i class="fa fa-minus"></i></button>'+
                                              '</div>'+
                                            '</div></div>');

     
  }

  function delete_val($this,$id)
  {
    if($id > 1){
      $("#val_"+$id).remove();
      $values = +$values - 1;
    }
  } 

</script>