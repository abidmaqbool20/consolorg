 
<?php
 
  $record_data = array();
  if($data && $data != "")
  {
    $this->db->where(array("question_categories.Org_Id"=>$this->org_id,"question_categories.Id"=>$data));
    $this->db->select("question_categories.*  ");
    $this->db->from("question_categories");  
    $this->db->order_by("question_categories.Id","ASC");
    $check_record = $this->db->get();

    if($check_record->num_rows() > 0)
    {
      $record_data = $check_record->result_array();
      $record_data = $record_data[0]; 
    }
  }

?>
<div class="row">
  <div class="col-md-12"> 
    <form id="common_form" method="post" action="<?= base_url("admin/save_record") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
      <div class="panel">
        <div class="panel-body">  
              <div class="error"></div>
              <div class="form-group">
               
                <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id']) && $record_data['Id'] != "" ){ echo $record_data['Id']; } ?>"> 
                <input type="hidden" name="Table_Name" id="Table_Name" value="question_categories">
                <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

                 
               
                <div class="col-sm-12 col-xs-12 ">
                  <div class="">
                    <label class="control-label">Category Name <span class="text-danger">*</span><span class="text-danger error"></span></label>
                    <input type="text" class="form-control" placeholder="Enter Category Name" name="Name" id="Name" value="<?php if(isset($record_data['Name'])){ echo $record_data['Name']; } ?>">
                  </div>
                </div> 
                <div class="col-sm-12 col-xs-12">
                  <label class="control-label">Instructions for Interviewer <span class="text-danger">*</span><span class="text-danger error"></span></label>
                  <textarea id="Interviewer_Instructions" name="Interviewer_Instructions" class="form-control"><?php if(isset($record_data['Interviewer_Instructions'])){ echo $record_data['Interviewer_Instructions']; } ?></textarea> 
                  <script> CKEDITOR.replace( 'Interviewer_Instructions' );</script> 
                </div>
                <div class="col-sm-12 col-xs-12">
                  <label class="control-label">Interviewer Responsibilities<span class="text-danger">*</span><span class="text-danger error"></span></label>
                  <textarea id="Interviewer_Responsibilities" name="Interviewer_Responsibilities" class="form-control"><?php if(isset($record_data['Interviewer_Responsibilities'])){ echo $record_data['Interviewer_Responsibilities']; } ?></textarea> 
                  <script> CKEDITOR.replace( 'Interviewer_Responsibilities' );</script> 
                </div> 
            </div>
            <hr> 
        </div>  
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="">
            <button type="submit" style="float: left;" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
            <button style="float: right; margin-left: 5px;" onclick="load_tab(this,'question_categories',<?= 0; ?>,'questions_tabs_body')"  type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            <button  style="float: right;" onclick="load_tab(this,'form_question_categories',<?= 0; ?>,'questions_tabs_body')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
          </div>
        </div>
      </div>

    </form> 
  </div>
</div>    
 
 