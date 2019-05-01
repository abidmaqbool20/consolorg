<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Training Groups","Deleted"=>0,"Status"=>1));
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
                <h3><i class="fa fa-cubes"></i> Manage Training Groups</h3>
              </div> 
              <div class="col-lg-2 col-md-2 col-sm-3">
                  <select class="form-control selected_action"  onchange="perform_group_action('training_groups',this)">
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
                  <button class="btn btn-primary-active"  onclick="load_view(this,'training_groups_table');"><i class="fa fa-th"></i></button>
                  <a href="javascript:;" class="btn btn-default"  onclick="load_view(this,'training_groups_list');"><i class="fa fa-th-list"></i></a>
                </div>
              </div>
              <div class="col-lg-1 col-md-2 col-sm-2">
                <?php if(in_array($module_id."_add", $this->role_permissions)){ ?>
                <button onclick="load_view(this,'form_training_group')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add New</button> 
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
                      <input type="checkbox" onclick="selectallrecords(this,'training_groups')" ><span></span>
                    </label>
                  </th> 
                   <th>Title</th>  
                  <th>Trainer</th>  
                  <th>Organizer</th>  
                  <th>Location</th>  
                  <th>Start Date</th>  
                  <th>End Date</th>
                  <th>Start Time</th>  
                  <th>End Time</th>   
                  <th>Total Duration</th>  
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
                      <input type="checkbox" onclick="selectallrecords(this,'training_groups')" ><span></span>
                    </label>
                  </th> 
                  <th>Title</th>  
                  <th>Trainer</th>  
                  <th>Organizer</th>  
                  <th>Location</th>  
                  <th>Start Date</th>  
                  <th>End Date</th>
                  <th>Start Time</th>  
                  <th>End Time</th>   
                  <th>Total Duration</th>  
                  <th>Created Date</th>
                  <th>Last Updated</th>
                  <th>Created By</th>
                  <th>Last Updated By</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </tfoot>

              <tbody id="training_groups">
                <?php 

                  $this->db->where(array("training_groups.Deleted"=>0,"training_groups.Org_Id"=>$this->org_id));
                  $this->db->select("
                                      training_groups.*, 
                                      employees.First_Name as Trainer_FirstName,
                                      employees.First_Name as Trainer_LastName,
                                      locations.Name as Location_Name,
                                      addedby.First_Name as Addedby_FirstName,
                                      addedby.Last_Name as Addedby_LastName,
                                      updatedby.First_Name as Updatedby_FirstName,
                                      updatedby.Last_Name as Updatedby_LastName
                                    ");

                  $this->db->from("training_groups"); 
                  $this->db->join("employees","employees.Id = training_groups.Trainer_Id","left");
                  $this->db->join("locations","locations.Id = training_groups.Location_Id","left");
                  $this->db->join("users as addedby","addedby.Id = training_groups.Added_By","left");
                  $this->db->join("users as updatedby","updatedby.Id = training_groups.Modified_By","left"); 
                  $this->db->order_by("Id","DESC");
                  $training_groups = $this->db->get();

                  if($training_groups->num_rows() > 0)
                  {
                    foreach ($training_groups->result() as $key => $value) 
                    { 
                       
                ?>
                <tr id="row_<?= $value->Id; ?>">
                  <td>
                    <label class="ckbox ckbox-primary">
                      <input class="table_record_checkbox" value="<?= $value->Id; ?>" type="checkbox" id="record_<?= $value->Id; ?>" ><span></span>
                    </label>
                  </td>
                  <td><?= $value->Title; ?>   </td>
                  <td><?= $value->Trainer_FirstName." ".$value->Trainer_LastName; ?>   </td>
                  <td><?= $value->Organizer_Name; ?>   </td>
                  <td><?= $value->Location_Name; ?>   </td>
                  <td><?= $value->Start_Date; ?>   </td>
                  <td><?= $value->End_Date; ?>   </td>
                  <td><?= $value->Start_Time; ?>   </td>
                  <td><?= $value->End_Time; ?>   </td>
                  <td><?php 
                          $date1=date_create($value->Start_Date); 
                          $date2=date_create($value->End_Date); 
                          $diff=date_diff($date1,$date2);
                          echo $diff->format("%a Days");
                      ?>
                  </td> 
                  <td><?= $value->Date_Added; ?></td>
                  <td><?= $value->Date_Modification; ?></td>
                  <td><?= $value->Addedby_FirstName." ".$value->Addedby_LastName;  ?></td>
                  <td><?= $value->Updatedby_FirstName." ".$value->Updatedby_LastName; ?></td>
                  <td>
                    <?php if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                    <label class="switch">
                      <input type="checkbox" <?php if($value->Status == 1){ echo "checked='checked'"; } ?> class="status" table="training_groups" table_id="<?= $value->Id; ?>">
                      <span class="slider"></span>
                    </label>
                    <?php } ?>
                  </td>
                  <td>
                    <?php if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                    <a href="javascript:;" style="color: blue;" onclick="load_view(this,'form_training_group','<?= $value->Id; ?>')"> <i class="fa fa-pencil"></i> </a>&nbsp;&nbsp;
                    <?php } if(in_array($module_id."_delete", $this->role_permissions)){ ?>
                    <a href="javascript:;" onclick="delete_record('training_groups','<?= $value->Id; ?>',this)" style="color: red;"> <i class="fa fa-trash"></i></a>
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