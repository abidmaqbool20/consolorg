 
<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Manage Questions","Deleted"=>0,"Status"=>1));
  if($module_data->num_rows() > 0)
  {
    $module_info = $module_data->result_array();
    $module_id = $module_info[0]['Id'];
  }
 
 ?>



<div class="row"> 
    <div class="col-md-12">
      <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
        <h3><i class="fa fa-cubes"></i> Manage Questions</h3>
      </div>
      <div class="col-lg-1 col-md-1 col-sm-2 col-xs-12">
        <select class="form-control select2 selected_action"  onchange="perform_group_action('questions',this)">
            <option selected="selected">With Selected</option>
            <?php if(in_array($module_id."_delete", $this->role_permissions)){ ?>
            <option value="Delete">Delete</option>
            <?php } if(in_array($module_id."_edit", $this->role_permissions)){ ?>
            <option value="Disable">Disable</option>
            <option value="Enable">Enable</option>
            <option value="Change_Status">Change Status</option> 
            <?php } ?>
        </select>
      </div>    
      <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
        <?php if(in_array($module_id."_add", $this->role_permissions)){ ?>
        <button onclick="load_tab(this,'form_question_add',<?= 0; ?>,'questions_tabs_body')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add New</button>
        <?php } ?> 
      </div>
    </div> 
    <div class="col-md-12"><hr></div>
    <div class="col-md-12"> 
      <div class="table-responsive">
        <table id="dataTable1" class="table table-bordered table-striped-col">
          <thead>
            <tr> 
              <th>
                <label class="ckbox ckbox-primary">
                  <input type="checkbox" onclick="selectallrecords(this,'questions')" ><span></span>
                </label>
              </th> 
              <th>Question Statement</th>  
              <th>Category</th>   
              <th>Sort Order</th>   
              <th>Created By</th>
              <th>Last Updated By</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>

          <tfoot>
            <tr>
             
              <th>
                <label class="ckbox ckbox-primary">
                  <input type="checkbox" onclick="selectallrecords(this,'questions')" ><span></span>
                </label>
              </th> 
              <th>Question Statement</th>  
              <th>Category</th> 
              <th>Sort Order</th>     
              <th>Created By</th>
              <th>Last Updated By</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </tfoot>

          <tbody id="questions">
            <?php 

              $this->db->where(array("questions.Deleted"=>0,"questions.Org_Id"=>$this->org_id));
              $this->db->select("
                                  questions.*, 
                                  question_categories.Name as Category,
                                  addedby.First_Name as Addedby_FirstName,
                                  addedby.Last_Name as Addedby_LastName,
                                  updatedby.First_Name as Updatedby_FirstName,
                                  updatedby.Last_Name as Updatedby_LastName
                                ");

              $this->db->from("questions");
              $this->db->join("question_categories","question_categories.Id = questions.Category_Id","left");
              $this->db->join("employees as addedby","addedby.Id = questions.Added_By","left");
              $this->db->join("employees as updatedby","updatedby.Id = questions.Modified_By","left"); 
              $this->db->order_by("Id","DESC");
              $questions = $this->db->get();

              if($questions->num_rows() > 0)
              {
                foreach ($questions->result() as $key => $value) 
                { 
                   
            ?>
            <tr id="row_<?= $value->Id; ?>">
              <td>
                <label class="ckbox ckbox-primary">
                  <input class="table_record_checkbox" value="<?= $value->Id; ?>" type="checkbox" id="record_<?= $value->Id; ?>" ><span></span>
                </label>
              </td>
              <td><?= $value->Statement; ?>   </td>
              <td><?= $value->Category; ?></td> 
              <td><?= $value->Sort_Order; ?></td>
              <td><?= $value->Addedby_FirstName." ".$value->Addedby_LastName; ?></td>  
              <td><?= $value->Updatedby_FirstName." ".$value->Updatedby_LastName; ?></td>
              <td>
                <?php if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                <label class="switch">
                  <input type="checkbox" <?php if($value->Status == 1){ echo "checked='checked'"; } ?> class="status" table="questions" table_id="<?= $value->Id; ?>">
                  <span class="slider"></span>
                </label>
                <?php } ?>
              </td>
              <td>
                 <?php if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                <button onclick="load_tab(this,'form_question_add',<?= $value->Id; ?>,'questions_tabs_body')" class="btn btn-info btn-stroke btn-icon tooltips mr5" data-original-title="Edit Question"><i class="fa fa-pencil"></i></button>
                <?php }if(in_array($module_id."_delete", $this->role_permissions)){ ?>
                <button onclick="delete_record('questions','<?= $value->Id; ?>',this)" class="btn btn-danger btn-stroke btn-icon tooltips mr5" ><i class="fa fa-trash"></i></button> 
                <?php } ?>
              </td>
            </tr>
            <?php }} ?>
          </tbody>
        </table>
      </div>
    </div> 
  </div>

  <script type="text/javascript">
    $('#dataTable1').DataTable();
  </script>