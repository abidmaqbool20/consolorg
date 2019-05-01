

<?php 
   
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Holidays Calendar","Deleted"=>0,"Status"=>1));
  if($module_data->num_rows() > 0)
  {
    $module_info = $module_data->result_array();
    $module_id = $module_info[0]['Id'];
  }
 

 ?>
<style type="text/css">
  .working_day
  {
    background-color: green !important;
    color: white !important;
  }
  .leave_day
  {
    background-color: red !important;
    color: white !important;
  }
  .org_holiday
  {
    background-color: #fbff14 !important;
    color: black !important;
  }
</style>
    
    <div class="row">
      <form method="post" action="<?= base_url("admin/get_holidays_calendar"); ?>" onsubmit="return get_holidays_calendar(this)">   
        <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
           <select class="form-control select2 required" name="Shift" id="Shift" >  
                <?php 

                    $first_shift_name = "No Shift";
                    $this->db->order_by("Name","ASC");
                    $shifts = $this->db->get_where("shifts",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                    if($shifts->num_rows() > 0)
                    {
                      foreach ($shifts->result() as $key => $value) {
                        if($key == 0){ $first_shift_name = $value->Name; }
                        echo '<option value="'.$value->Id.'">'.$value->Name.'</option>';
                      }
                    }

                ?>
            </select>
        </div> 
         
        <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
          <button type="button" class="btn btn-primary" onclick="shift_changer(this)"><i class="fa fa-check"></i> Change Shift</button>
        </div> 
        <div class="col-md-4" style="text-align: center;"><h3 id="shift_name"><?= $first_shift_name; ?></h3></div>
      </form>

    </div>
        
    <hr>  
    <div class="row">
        <div class="col-md-offset-2 col-md-8 col-md-offset-2 col-sm-12 col-xs-12">
           <div id='holidays_calendar'></div> 
        </div>
    </div>
       

 
  <script type="text/javascript">
    $('#dataTable1').DataTable();
    $('.select2').select2(); 
  </script>
  <script>
 

function shift_changer()
{ 
  $shift_id = $("#Shift").val();  
  $shift_name = $("#Shift option[value='"+$shift_id+"']").text();
  $("#shift_name").html($shift_name);
  fullCalendar($shift_id);  
}
  
fullCalendar();  
 
function fullCalendar($shift_id='')
{
  add_loader();
  $('#holidays_calendar').html("");

  $holidays = new Array();

  if($shift_id == ""){  $shift_id = $("#Shift").val();  }
 
  if($shift_id != "" )
  {  
    jQuery.ajax({
                    type: "POST",
                    data: {shift_id:$shift_id},
                    url: "<?= base_url('admin/get_holidays_for_calendar') ?>",
                    success: function (data) 
                    {   
                        $holidays =   $.parseJSON(data); 
                    }, 
                    async: false,  
                });    
  }

  $('#holidays_calendar')
  .fullCalendar({
          
          theme: false,
          header: {
              left: 'prev,next today',
              center: 'title',
              right: 'month'
               
          }, 
          
          navLinks: true, // can click day/week names to navigate views
          editable: false,
          eventLimit: false, // allow "more" link when too many events
          events: $holidays, 
          
  })
  remove_loader();
}
 

</script>
