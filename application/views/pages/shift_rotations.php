 <?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Shifts","Deleted"=>0,"Status"=>1));
  if($module_data->num_rows() > 0)
  {
    $module_info = $module_data->result_array();
    $module_id = $module_info[0]['Id'];
  }
 
 ?>

        <div class="row">
          <form method="post" action="<?= base_url("admin/rotate_shifts"); ?>" onsubmit="return rotate_shifts(this)"> 
            
            <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
               <select class="form-control select2 required" name="Shift_A" id="Shift_A" > 
                    <option value="0">Select Shift A</option>  
                    <?php 

                        $this->db->order_by("Name","ASC");
                        $shifts = $this->db->get_where("shifts",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                        if($shifts->num_rows() > 0)
                        {
                          foreach ($shifts->result() as $key => $value) {
                            echo '<option value="'.$value->Id.'">'.$value->Name.'</option>';
                          }
                        }

                    ?>
                </select>
            </div> 
            <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
               <select class="form-control select2 required" name="Shift_B" id="Shift_B" > 
                    <option value="0">Select Shift B</option>  
                    <?php 

                        $this->db->order_by("Name","ASC");
                        $shifts = $this->db->get_where("shifts",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                        if($shifts->num_rows() > 0)
                        {
                          foreach ($shifts->result() as $key => $value) {
                            echo '<option value="'.$value->Id.'">'.$value->Name.'</option>';
                          }
                        }

                    ?>
                </select>
            </div> 
             
            <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Rotate Shifts</button>
            </div>
            <div class="col-md-offset-2 col-lg-offset-2 col-sm-offset-3 col-xs-12"></div>
   
          </form>

        </div>
        <hr>
        <div class="row">
          <div class="col-md-12">
          <div class="table-responsive">
            <table id="dataTable1" class="table table-bordered table-striped-col">
              <thead>
                <tr> 
                  <th>Shift Rotated</th>   
                  <th>Rotated By</th>  
                  <th>Rotation Date</th> 
                </tr>
              </thead>

              <tfoot>
                <tr> 
                  <th>Shift Rotated</th>  
                  <th>Rotated By</th> 
                  <th>Rotation Date</th>
                </tr>
              </tfoot>

              <tbody id="shift_rotations">
                <?php 

                  $this->db->where(array("shift_rotations.Deleted"=>0,"shift_rotations.Org_Id"=>$this->org_id));
                  $this->db->select("
                                      shift_rotations.*,
                                      addedby.First_Name as Addedby_FirstName,
                                      addedby.Last_Name as Addedby_LastName, 
                                      shifts_a.Name as Shift_A_Name,
                                      shifts_b.Name as Shift_B_Name,
                                    ");

                  $this->db->from("shift_rotations");
                  $this->db->join("employees as addedby","addedby.Id = shift_rotations.Added_By","left"); 
                  $this->db->join("shifts as shifts_a","shifts_a.Id = shift_rotations.Shift_A","left");
                  $this->db->join("shifts as shifts_b","shifts_b.Id = shift_rotations.Shift_B","left");
                  $this->db->order_by("Id","DESC");
                  $shift_rotations = $this->db->get();

                  if($shift_rotations->num_rows() > 0)
                  {
                    foreach ($shift_rotations->result() as $key => $value) 
                    { 
                      $implentation_time =  date("Y-m-d H:i:s", strtotime('+10 hours',strtotime($value->Date_Added)));
                      $time_now = date("Y-m-d H:i:s");
                      $diff = date_difference($time_now,$implentation_time);

                      if($value->Rotation_Status == "Pending"){
                          $rotation_rec = '<div>
                                            <button class="btn btn-xs btn-warning">Pending</button>
                                            <span>Shifts will be rotated in '.$diff['Hour'].' Hours and '.$diff['Minuts'].' Minutes</span>
                                            <span class="btn btn-danger btn-xs" onclick="delete_record(\'shift_rotations\','.$value->Id.',this)"><i class="fa fa-times"></i>&nbsp;Cancel Rotation</span>
                                          </div>';
                      }
                      else{
                        $rotation_rec = date("l M d,Y",strtotime($value->Date_Added));
                      }
                ?>
                <tr id="row_<?= $value->Id; ?>">
                  
                  <td><?= 
                          $value->Shift_A_Name.' 
                          <i class="fa fa-arrows-h" style="font-size: 15px; font-weight: bold; "></i> '.
                          $value->Shift_B_Name; 
                      ?> 
                  </td>
                  <td><?= $value->Addedby_FirstName." ".$value->Addedby_LastName; ?></td> 
                  <td><?= $rotation_rec; ?></td> 
              
                </tr>
                <?php }} ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>  
    </div> 
  </div>

  <script type="text/javascript">
    $('#dataTable1').DataTable();
    $('.select2').select2(); 
  </script>