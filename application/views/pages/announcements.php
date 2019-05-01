 
<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Announcements","Deleted"=>0,"Status"=>1));
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
                <h3><i class="fa fa-cubes"></i> Manage Announcements</h3>
              </div> 
              <div class="col-lg-2 col-md-2 col-sm-3">
                  <select class="form-control selected_action"  onchange="perform_group_action('departments',this)">
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
                 
              </div>
              <div class="col-lg-1 col-md-2 col-sm-2">
                <?php if(in_array($module_id."_add", $this->role_permissions)){ ?>
                <button onclick="load_view(this,'form_announcement')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add New</button> 
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
                      <input type="checkbox" onclick="selectallrecords(this,'announcements')" ><span></span>
                    </label>
                  </th> 
                  <th>Annoucnement Title</th>  
                  <th>Announcement</th>  
                  <th>Targeted Shifts</th> 
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
                      <input type="checkbox" onclick="selectallrecords(this,'announcements')" ><span></span>
                    </label>
                  </th> 
                  <th>Annoucnement Title</th>  
                  <th>Announcement</th>  
                  <th>Targeted Shifts</th> 
                  <th>Created By</th>
                  <th>Last Updated By</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </tfoot>

              <tbody id="announcements">
                <?php 

                  $this->db->where(array("announcements.Deleted"=>0,"announcements.Org_Id"=>$this->org_id));
                  $this->db->select("
                                      announcements.*, 
                                      addedby.First_Name as Addedby_FirstName,
                                      addedby.Last_Name as Addedby_LastName,
                                      updatedby.First_Name as Updatedby_FirstName,
                                      updatedby.Last_Name as Updatedby_LastName
                                    ");

                  $this->db->from("announcements");  
                  $this->db->join("employees as addedby","addedby.Id = announcements.Added_By","left");
                  $this->db->join("employees as updatedby","updatedby.Id = announcements.Modified_By","left"); 
                  $this->db->order_by("Id","DESC");
                  $announcements = $this->db->get();

                  if($announcements->num_rows() > 0)
                  {
                    foreach ($announcements->result() as $key => $value) 
                    { 
                      $shifts_names = array();
                      $announcement_shifts_array = array();
                      $announcement_shifts = $this->db->get_where("announcement_shifts",array("Deleted"=>0,"Org_Id"=>$this->org_id,"Announcement_Id"=>$value->Id));
                      
                      if($announcement_shifts->num_rows() > 0)
                      {
                        foreach ($announcement_shifts->result() as $index => $ann_data) 
                        {
                          $announcement_shifts_array[] = $ann_data->Shift_Id; 
                        }

                         
                        if(sizeof($announcement_shifts_array) > 0)
                        {
                          $this->db->where_in("Id",$announcement_shifts_array);
                          $shifts = $this->db->get_where("shifts",array("Deleted"=>0,"Status"=>1));
                          if($shifts->num_rows() > 0)
                          {
                            foreach ($shifts->result() as $index => $shift_rec_data) {
                               $shifts_names[] = $shift_rec_data->Name;
                            }
                          }
                        }
                      }

                       
                ?>
                <tr id="row_<?= $value->Id; ?>">
                  <td>
                    <label class="ckbox ckbox-primary">
                      <input class="table_record_checkbox" value="<?= $value->Id; ?>" type="checkbox" id="record_<?= $value->Id; ?>" ><span></span>
                    </label>
                  </td>
                  <td><?= $value->Title; ?>   </td>
                  <td><?= $value->Description; ?></td> 
                  <td><?= implode(", ",$shifts_names); ?></td> 
                   
                  <td><?= $value->Addedby_FirstName." ".$value->Addedby_LastName;  ?></td>
                  <td><?= $value->Updatedby_FirstName." ".$value->Updatedby_LastName; ?></td>
                  <td>
                    <?php if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                    <label class="switch">
                      <input type="checkbox" <?php if($value->Status == 1){ echo "checked='checked'"; } ?> class="status" table="departments" table_id="<?= $value->Id; ?>">
                      <span class="slider"></span>
                    </label>
                    <?php } ?>
                  </td>
                  <td>
                    <?php if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                    <a href="javascript:;" style="color: blue;" onclick="load_view(this,'form_announcement','<?= $value->Id; ?>')"> <i class="fa fa-pencil"></i> </a>&nbsp;&nbsp;
                    <?php }if(in_array($module_id."_delete", $this->role_permissions)){ ?>
                    <a href="javascript:;" onclick="delete_record('announcements','<?= $value->Id; ?>',this)" style="color: red;"> <i class="fa fa-trash"></i></a>
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