 <div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
      <h2>Manage Holiday Replacement</h2> 
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <button onclick="load_tab(this,'manage_holidays',0,'leave_tabs_body')" style="float: right;" class="btn btn-danger btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; Go Back</button>
    </div> 
  </div>
  <hr>
  <div class="row">
    <div class="col-md-12">  
      <div class="table-responsive">
        <table id="dataTable1" class="table table-bordered table-striped-col">
          <thead>
            <tr> 
              <th>Holiday Title</th>   
              <th>Holiday Date</th>  
              <th>Replacement Date</th>  
              <th>Implementation Status</th>   
              <th>Date Saved</th>   
              <th>Action</th> 
            </tr>
          </thead>  
          <tbody id="shift_settings">
            <?php 

              $this->db->where(array("organization_holiday_replacement.Org_Id"=>$this->org_id,"organization_holiday_replacement.Deleted"=>0,"organization_holiday_replacement.Status"=>1,"organization_holiday_replacement.Holiday_Id"=>$data ));
              $this->db->select("
                                  organization_holiday_replacement.*,
                                  organization_holidays.Repeat_Yearly,
                                  organization_holidays.Title,
                                  organization_holidays.From_Day as Holiday_From_Day,
                                  organization_holidays.To_Day as Holiday_To_Day,
                                  organization_holidays.From_Date as Holiday_From_Date,
                                  organization_holidays.To_Date as Holiday_To_Date
                                ");
              $this->db->from("organization_holiday_replacement");  
              $this->db->join("organization_holidays","organization_holidays.Id = organization_holiday_replacement.Holiday_Id","left");
              $this->db->order_by("organization_holiday_replacement.Id","ASC");
              $records = $this->db->get();

              if($records->num_rows() > 0)
              { 
                foreach ($records->result() as $key => $value) 
                { 

                    if($value->Repeat_Yearly == "on"){
                      $holiday_from_date = date("F d",strtotime(date("Y")."-".$value->Holiday_From_Day));
                      $holiday_to_date = date("F d",strtotime(date("Y")."-".$value->Holiday_To_Day));

                      $replacement_from_date = date("F d",strtotime(date("Y")."-".$value->From_Day));
                      $replacement_to_date = date("F d",strtotime(date("Y")."-".$value->To_Day));

                    }
                    else{
                      $holiday_from_date = date("F d",strtotime( $value->Holiday_From_Date));
                      $holiday_to_date = date("F d",strtotime( $value->Holiday_To_Date));

                      $replacement_from_date = date("F d",strtotime( $value->From_Date));
                      $replacement_to_date = date("F d",strtotime( $value->To_Date));
                    }
 

              ?>
              <tr id="row_<?= $value->Id; ?>"> 
                <td><?= $value->Title; ?> </td>
                <td><?= $holiday_from_date." TO ".$holiday_to_date; ?></td> 
                <td><?= $replacement_from_date." TO ".$replacement_to_date; ?></td> 
                <td><?= $value->Implementation_Status; ?> </td>
                <td><?= date("d F, Y H:i:s", strtotime($value->Date_Added)); ?> </td> 
                <td>
                  <?php if($value->Implementation_Status == "Pending"){ ?>
                  <button onclick="delete_record('organization_holiday_replacement','<?= $value->Id; ?>',this)" class="btn btn-danger btn-stroke btn-icon tooltips mr5"><i class="fa fa-trash"></i></button></td>   
                  <?php } ?>
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