
<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Employees","Deleted"=>0,"Status"=>1));
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
              <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                <h3><i class="fa fa-cubes"></i> Manage Employees</h3>
              </div>
              <div class="col-lg-1 col-md-1 col-sm-2 col-xs-12">
                <select class="form-control select2 selected_action"  onchange="perform_group_action('languages',this)">
                    <option selected="selected">With Selected</option>
                    <?php if(in_array($module_id."_delete", $this->role_permissions)){ ?>
                    <option value="Delete">Delete</option>
                    <?php } if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                    <!-- <option value="Disable">Disable</option>
                    <option value="Enable">Enable</option>
                    <option value="Change_Status">Change Status</option>  -->
                    <?php } ?>
                </select>
              </div>    
              <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
                <?php if(in_array($module_id."_add", $this->role_permissions)){ ?>
                <button onclick="load_view(this,'form_employee_add')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add New</button>
                <?php } ?> 
              </div>
            </div>
        </div>
      </div>

      <div class="panel" style="min-height: 75vh;">
        <div class="panel-heading">
          <form method="post"  action="<?= base_url("admin/get_employees"); ?>" id="filter_form">
            <div class="row">  
              <div class="col-md-3 col-lg-3 col-sm-2 col-xs-12">
                 <input type="text" class="form-control" name="Text" id="Text" placeholder="Write to search">
              </div>
             
              <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12">
                  <select class="form-control select2" name="Department_Id" id="Department_Id" >
                      <option selected="selected" value="0">All Department</option>
                      <?php 

                        $departments = $this->db->get_where('departments',array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                        if($departments->num_rows() > 0)
                        {
                          foreach ($departments->result() as $key => $value) {
                            echo '<option value="'.$value->Id.'">'.$value->Name.'</option>';
                          }
                        }

                      ?>  
                  </select>
              </div>
              <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12">
                   <select class="form-control select2" name="Role_Id" id="Role_Id"  >
                      <option selected="selected" value="0">All Role's</option>
                      <?php 

                        $roles = $this->db->get_where('roles',array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                        if($roles->num_rows() > 0)
                        {
                          foreach ($roles->result() as $key => $value) {
                            echo '<option value="'.$value->Id.'">'.$value->Name.'</option>';
                          }
                        }

                      ?>  
                  </select>
              </div>
              <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12">
                   <select class="form-control select2" name="Location_Id" id="Location_Id">
                      <option selected="selected" value="0">All Locations</option>
                      <?php 

                        $locations = $this->db->get_where('locations',array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                        if($locations->num_rows() > 0)
                        {
                          foreach ($locations->result() as $key => $value) {
                            echo '<option value="'.$value->Id.'">'.$value->Name.'</option>';
                          }
                        }

                      ?>  
                  </select>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                  <select class="form-control select2" name="Employee_Status">
                      <option selected="selected" value="0">Any Status</option>
                      <option value="Active">Active</option> 
                      <option value="Deceased">Deceased</option> 
                      <option value="Terminated">Terminated</option>   
                      <option value="Resigned">Resigned</option>   
                  </select>
                </div>  
              <div class="col-md-1 col-lg-1 col-sm-2 col-xs-12">
                 <button class="btn btn-warning btn-quirk" type="button" onclick="fetch_records('<?= base_url('admin/get_employees/1'); ?>')" ><i class="fa fa-filter"></i>&nbsp; Submit </button> 
              </div>
            </div> 
          </form>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordered nomargin">
              <thead>
                 <tr> 
                  <th>
                    <label class="ckbox ckbox-primary">
                      <input type="checkbox" onclick="selectallrecords(this,'employees')" ><span></span>
                    </label>
                  </th> 
                  <th></th>
                  <th>Basic Information</th>   
                  <th>Role</th>  
                  <th>Location</th>   
                  <th>Date Of Joining</th>   
                  <th>Created Date</th>
                  <th>Last Updated</th>
                  <th>Created By</th>
                  <th>Last Updated By</th> 
                  
                </tr>
              </thead>
              <tbody id="employees" class="table_records">
                <?php 

                  $total_records = $this->db->get_where("employees",array("Deleted"=>0,"Org_Id"=>$this->org_id))->num_rows();


                  $this->db->where(array("employees.Deleted" =>0,"employees.Org_Id"=>$this->org_id));
                  $this->db->select("
                                      employees.*, 
                                      roles.name as Role_Name, 
                                      locations.Name as Location_Name,  
                                      addedby.First_Name as Addedby_FirstName,
                                      addedby.Last_Name as Addedby_LastName,
                                      updatedby.First_Name as Updatedby_FirstName,
                                      updatedby.Last_Name as Updatedby_LastName

                                    ");

                  $this->db->from("employees"); 
                  $this->db->join("roles","roles.Id = employees.Role_Id","left"); 
                  $this->db->join("locations","locations.Id = employees.Location_Id","left"); 
                  $this->db->join("employees as addedby","addedby.Id = employees.Added_By","left");
                  $this->db->join("employees as updatedby","updatedby.Id = employees.Modified_By","left"); 
                  $this->db->order_by("Id","DESC");
                  $this->db->limit("5");
                  $employees = $this->db->get();
                  
                  if($employees->num_rows() > 0)
                  {
                    foreach ($employees->result() as $key => $value) 
                    { 
                      $src =  "assets/images/default-user.png";
                      if($value->Photo && $value->Photo != "")
                      {
                        $src =  "assets/panel/userassets/employees/".$value->Id."/".$value->Photo;
                        if(!file_exists($src))
                        {
                           $src =  "assets/images/default-user.png";
                        } 
                      }
                ?>
                <tr id="row_<?= $value->Id; ?>">
                  <td>
                    <label class="ckbox ckbox-primary">
                      <input class="table_record_checkbox" value="<?= $value->Id; ?>" type="checkbox" id="record_<?= $value->Id; ?>" ><span></span>
                    </label>
                  </td>
                  <td>
                    <div class="btn-group">
                      <button type="button" class="dropdown-toggle action-dropdown" data-toggle="dropdown"><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i></button>
                      <ul class="dropdown-menu" role="menu">
                        <?php if(in_array($module_id."_view", $this->role_permissions)){ ?>
                        <li><a href="javascript:;" style="color: green;" onclick="open_modal_window(this,'employee_view','<?= $value->Id; ?>')"> <i class="fa fa-eye"></i> View </a></li>
                        <?php }if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                        <li><a href="javascript:;" style="color: blue;" onclick="load_view(this,'form_employee_edit','<?= $value->Id; ?>')"> <i class="fa fa-pencil"></i> Edit </a></li>
                        <?php }if(in_array($module_id."_delete", $this->role_permissions)){ ?>
                        <li><a href="javascript:;" onclick="delete_record('employees','<?= $value->Id; ?>',this)" style="color: red;"> <i class="fa fa-trash"></i> Delete</a></li>
                        <?php } ?> 
                      </ul>
                    </div>
                  </td>
                  <td>
                      <div class="table-profile-img" >
                        <a  href="javascript:;" >
                          <img src="<?= $src; ?>" class="pro-img">
                        </a> 
                      </div> 
                      <div class="emp-tabe-name">
                          <?= $value->First_Name." ".$value->Last_Name; ?>
                          <span class="emp-table-email"><?= $value->Email; ?></span>  
                      </div>
                  </td> 
                  <td><?= $value->Role_Name; ?>   </td> 
                  <td><?= $value->Location_Name; ?>   </td> 
                  <td><?= $value->Joining_Date; ?>   </td> 
                  <td><?= $value->Date_Added; ?></td>
                  <td><?= $value->Date_Modification; ?></td>
                  <td><?= $value->Addedby_FirstName." ".$value->Addedby_LastName;  ?></td>
                  <td><?= $value->Updatedby_FirstName." ".$value->Updatedby_LastName; ?></td>
                   
                   
                </tr>
                <?php }} ?>
                
              </tbody>
            </table>
          </div><!-- table-responsive -->
        </div>
        <div class="panel-heading" style="padding-top: 0;   ">
          <div class="row">
            <div class="col-lg-1 col-md-1 col-sm-2 col-xs-12">
                <select class="form-control select2 selected_action" id="Per_Page" onchange="fetch_records('<?= base_url('admin/get_employees/1'); ?>')">
                    <option selected="selected" value="5">5</option>
                    <option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option> 
                </select>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-2 col-xs-12">
              <button class="btn btn-warning btn-quirk" onclick="load_sidebar_view(this,'employees_table');"><i class="fa fa-refresh"></i> &nbsp; Refresh</button>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-2 col-xs-12">
              <div style="text-align: center;">
                <h4>Total Employees: <?= $total_records; ?></h4>
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">  
              <ul class="pagination pagination-bordered" style="float: right; margin: 0px;">
                 <?php

                    $ci =& get_instance();
                    
                    $config['base_url'] = base_url('admin/get_employees');
                    $config['total_rows'] = $total_records;
                    $config['per_page'] = 5;
                    $config["uri_segment"] = 3;  
                    $config['num_links'] = 3;
                    $config['use_page_numbers'] = TRUE;
                    $config['reuse_query_string'] = TRUE;  
                    $config['enable_query_strings']= TRUE;
                    $config['first_url'] = base_url('admin/get_employees'); 
                    $config['first_link'] = 'First Page';
                    $config['first_tag_open'] = '<li class="firstlink">';
                    $config['first_tag_close'] = '</li>'; 
                    $config['last_link'] = 'Last Page';
                    $config['last_tag_open'] = '<li class="lastlink">';
                    $config['last_tag_close'] = '</li>'; 
                    $config['next_link'] = 'Next Page';
                    $config['next_tag_open'] = '<li class="nextlink">';
                    $config['next_tag_close'] = '</li>'; 
                    $config['prev_link'] = 'Prev Page';
                    $config['prev_tag_open'] = '<li class="prevlink">';
                    $config['prev_tag_close'] = '</li>'; 
                    $config['cur_tag_open'] = '<li class="active"><a href="javascript:;">';
                    $config['cur_tag_close'] = '</a></li>'; 
                    $config['num_tag_open'] = '<li class="numlink">';
                    $config['num_tag_close'] = '</li>'; 

                    $this->pagination->initialize($config);

                    echo $this->pagination->create_links();

                 ?>
              </ul>
 

            </div>
          </div> 
        </div>
    </div>
  </div> 
</div>
<script type="text/javascript">
  $('.datepicker').datepicker();
  $('.select2').select2();


</script>