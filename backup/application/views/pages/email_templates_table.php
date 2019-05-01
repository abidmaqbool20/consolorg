
<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Email Templates","Deleted"=>0,"Status"=>1));
  if($module_data->num_rows() > 0)
  {
    $module_info = $module_data->result_array();
    $module_id = $module_info[0]['Id'];
  }
 
 ?>


<div class="mainpanel">

    <div class="contentpanel">

     <div class="panel">
         
        <div class="panel-body">
            <div class="col-md-12">
              <div class="col-lg-7 col-md-6 col-sm-6">
                <h3><i class="fa fa-cubes"></i> Manage Email Templates</h3>
              </div> 
              <div class="col-lg-2 col-md-2 col-sm-3">
                  <select class="form-control selected_action"  onchange="perform_group_action('email_templates',this)">
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
              <div class="col-lg-2 col-md-2 col-sm-2"> 
                <div class="btn-group pull-right people-pager">
                  <button class="btn btn-primary-active"  onclick="load_view(this,'email_templates_table');"><i class="fa fa-th"></i></button>
                  <a href="javascript:;" class="btn btn-default"  onclick="load_view(this,'email_templates_list');"><i class="fa fa-th-list"></i></a>
                </div>
              </div>
              <div class="col-lg-1 col-md-2 col-sm-2">
                <?php if(in_array($module_id."_add", $this->role_permissions)){ ?>
                <button onclick="load_view(this,'form_email_template')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add New</button> 
                <?php } ?>
              </div>
            </div>
        </div>
      </div>

      <div class="panel"> 
        <div class="panel-body">
          <div class="table-responsive">
            <table id="dataTable1" class="table table-bordered table-striped-col">
              <thead>
                <tr> 
                  <th>
                    <label class="ckbox ckbox-primary">
                      <input type="checkbox" onclick="selectallrecords(this,'email_templates')" ><span></span>
                    </label>
                  </th> 
                  <th>Template Name</th>  
                  <th>Template Type</th>  
                  <th>Created Date</th>
                  <th>Last Updated</th>
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
                      <input type="checkbox" onclick="selectallrecords(this,'email_templates')" ><span></span>
                    </label>
                  </th> 
                  <th>Template Name</th>  
                  <th>Template Type</th>  
                  <th>Created Date</th>
                  <th>Last Updated</th>
                  <th>Created By</th>
                  <th>Last Updated By</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </tfoot>

              <tbody id="email_templates">
                <?php 

                  $this->db->where(array("email_templates.Deleted"=>0,"email_templates.Org_Id"=>$this->org_id));
                  $this->db->select("
                                      email_templates.*, 
                                      addedby.First_Name as Addedby_FirstName,
                                      addedby.Last_Name as Addedby_LastName,
                                      updatedby.First_Name as Updatedby_FirstName,
                                      updatedby.Last_Name as Updatedby_LastName
                                    ");

                  $this->db->from("email_templates"); 
                  $this->db->join("employees as addedby","addedby.Id = email_templates.Added_By","left");
                  $this->db->join("employees as updatedby","updatedby.Id = email_templates.Modified_By","left"); 
                  $this->db->order_by("Id","DESC");
                  $email_templates = $this->db->get();

                  if($email_templates->num_rows() > 0)
                  {
                    foreach ($email_templates->result() as $key => $value) 
                    { 
                       
                ?>
                <tr id="row_<?= $value->Id; ?>">
                  <td>
                    <label class="ckbox ckbox-primary">
                      <input class="table_record_checkbox" value="<?= $value->Id; ?>" type="checkbox" id="record_<?= $value->Id; ?>" ><span></span>
                    </label>
                  </td>
                  <td><?= $value->Name; ?>   </td>
                  <td><?= $value->Type; ?></td> 
                  <td><?= $value->Date_Added; ?></td>
                  <td><?= $value->Date_Modification; ?></td>
                  <td><?= $value->Addedby_FirstName." ".$value->Addedby_LastName;  ?></td>
                  <td><?= $value->Updatedby_FirstName." ".$value->Updatedby_LastName; ?></td>
                  <td>
                    <?php if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                    <label class="switch">
                      <input type="checkbox" <?php if($value->Status == 1){ echo "checked='checked'"; } ?> class="status" table="email_templates" table_id="<?= $value->Id; ?>">
                      <span class="slider"></span>
                    </label>
                    <?php } ?>
                  </td>
                  <td>
                    <?php if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                    <a href="javascript:;" style="color: blue;" onclick="load_view(this,'form_email_template','<?= $value->Id; ?>')"> <i class="fa fa-pencil"></i> </a>&nbsp;&nbsp;
                    <?php }if(in_array($module_id."_delete", $this->role_permissions)){ ?>
                    <a href="javascript:;" onclick="delete_record('email_templates','<?= $value->Id; ?>',this)" style="color: red;"> <i class="fa fa-trash"></i></a>
                    <?php } ?>
                  </td>
                </tr>
                <?php }} ?>
              </tbody>
            </table>
          </div>
        </div>
      </div><!-- panel -->

      <!-- panel -->

    </div><!-- contentpanel -->
  </div>

  <script type="text/javascript">
    $('#dataTable1').DataTable();
  </script>