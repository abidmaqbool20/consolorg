<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Question Categories","Deleted"=>0,"Status"=>1));
  if($module_data->num_rows() > 0)
  {
    $module_info = $module_data->result_array();
    $module_id = $module_info[0]['Id'];
  }
 
 ?>
 
    <div class="row">
      <div class="col-md-12"> 
        <div class="col-lg-11 col-md-10 col-sm-10"></div>
        <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
          <?php if(in_array($module_id."_add", $this->role_permissions)){ ?>
          <button onclick="load_tab(this,'form_question_categories',<?= 0; ?>,'questions_tabs_body')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add New</button> 
          <?php } ?>
        </div>
      </div> 
    </div> 
    <hr> 
    <div class="row">
      <?php


          $this->db->where(array("question_categories.Deleted"=>0,"question_categories.Status"=>1,"question_categories.Org_Id"=>$this->org_id));
          $this->db->select("
                              question_categories.*,  
                              addedby.First_Name as Addedby_FirstName,
                              addedby.Last_Name as Addedby_LastName,
                              updatedby.First_Name as Updatedby_FirstName,
                              updatedby.Last_Name as Updatedby_LastName,
                              COUNT(questions.Id) as Total_Questions,
                            ");

          $this->db->from("question_categories");  
          $this->db->join("employees as addedby","addedby.Id = question_categories.Added_By","left");
          $this->db->join("employees as updatedby","updatedby.Id = question_categories.Modified_By","left"); 
          $this->db->join("questions","questions.Category_Id = question_categories.Id","left"); 
          $this->db->order_by("question_categories.Id","DESC");
          $this->db->group_by(array("question_categories.Id"));
          $question_categories = $this->db->get();

          if($question_categories->num_rows() > 0)
          {
            foreach ($question_categories->result() as $key => $value) 
            { 
             
      ?>
      <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12" id="row_<?= $value->Id; ?>">
        <div class="panel org_role">
          <div class="panel-heading">
            <h4 class="panel-title" style="font-size: 18px;"><?= $value->Name; ?></h4> 
            <h5 style="color: darkmagenta;"><?= $value->Total_Questions; ?> Questions</5> 
          </div>
          <div class="panel-body"> 
            <?php if(in_array($module_id."_edit", $this->role_permissions)){ ?>
            <button onclick="load_tab(this,'form_question_categories',<?= $value->Id; ?>,'questions_tabs_body')" class="btn btn-info btn-stroke btn-icon tooltips mr5" data-original-title="Edit Category"><i class="fa fa-pencil"></i></button>
            <?php }if(in_array($module_id."_delete", $this->role_permissions)){ ?>
            <button onclick="delete_record('question_categories','<?= $value->Id; ?>',this)" class="btn btn-danger btn-stroke btn-icon tooltips mr5" ><i class="fa fa-trash"></i></button> 
            <?php } ?>
          </div>
        </div>
      </div> 
    <?php }}else{ echo no_record_found(); } ?>
    </div> 
 


