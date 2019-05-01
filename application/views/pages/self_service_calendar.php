

<?php 

  $shift_id = "";
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Calendar","Deleted"=>0,"Status"=>1));
  if($module_data->num_rows() > 0)
  {
    $module_info = $module_data->result_array();
    $module_id   = $module_info[0]['Id'];
  }
  

  $shift_employees_rec = $this->db->get_where("shift_employees",array("Deleted"=>0,"Status"=>1,"Employee_Id"=>$this->user_id));
  if($shift_employees_rec->num_rows() > 0)
  {
    $shift_employees_data = $shift_employees_rec->result_array();
    $shift_id = $shift_employees_data[0]['Shift_Id']; 
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
  <div class="mainpanel"> 
    <div class="contentpanel"> 
      <div class="panel"> 
        <div class="panel-body">  
          <div class="row">
            <input type="hidden" id="Shift" value="<?= $shift_id; ?>">
              <div class="col-md-offset-2 col-md-8 col-md-offset-2 col-sm-12 col-xs-12">
                 <div id='holidays_calendar'></div> 
              </div>
          </div>
        </div>
      </div>
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
