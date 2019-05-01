  
    <?php 

        $record_data = array(); 
        if($data && $data != "")
        {

          $this->db->where(array("organization_holiday_replacement.Org_Id"=>$this->org_id,"organization_holiday_replacement.Deleted"=>0,"organization_holiday_replacement.Status"=>1,"organization_holiday_replacement.Holiday_Id"=>$data ));
          $this->db->select("
                              organization_holiday_replacement.*,
                              organization_holidays.Repeat_Yearly,
                              organization_holidays.From_Day as Holiday_From_Day,
                              organization_holidays.To_Day as Holiday_To_Day,
                              organization_holidays.From_Date as Holiday_From_Date,
                              organization_holidays.To_Date as Holiday_To_Date
                            ");
          $this->db->from("organization_holiday_replacement");  
          $this->db->join("organization_holidays","organization_holidays.Id = organization_holiday_replacement.Holiday_Id","left");
          $this->db->order_by("organization_holiday_replacement.Id","ASC");
          $check_record = $this->db->get();

          if($check_record->num_rows() > 0)
          {
            $record_data = $check_record->result_array();
            $record_data = $record_data[0]; 
            if(isset($record_data['From_Day']) && $record_data['From_Day'] != null){
              $from_day = explode("-",$record_data['From_Day']);
              $record_data['From_Month'] = $from_day[0];
              $record_data['From_Day'] = $from_day[1];
            }

            if(isset($record_data['To_Day']) && $record_data['To_Day'] != null){
              $to_day = explode("-",$record_data['To_Day']);
              $record_data['To_Month'] = $to_day[0];
              $record_data['To_Day'] = $to_day[1];
            }
          }
        }
        else{
          $data = 0;
        } 

    ?>
    <div class="panel-body"> 
      <form id="common_form" method="post" action="<?= base_url("Admin/save_organization_holiday_replacement") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
        <div class="error"></div>
        <div class="form-group"> 
            <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id']) && $record_data['Id'] != "" ){ echo $record_data['Id']; } ?>">  
            <input type="hidden" name="Table_Name" id="Table_Name" value="organization_holiday_replacement">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12"> 
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <h2>Add / Update Holiday Replacement</h2> <hr>
                </div>
              </div> 
            </div>

             <div class="col-sm-12  col-xs-12">
              <div class="row">
                <div class="col-md-8 col-sm-8 col-xs-12"> 
                  <select class="select2 form-control " required="required" name="Holiday_Id" id="Holiday_Id" onchange="get_month_and_days(this)" > 
                    <option value="0">Select Holiday</option>
                    <?php 

                      $holidays = $this->db->get_where("organization_holidays",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                      if($holidays->num_rows() > 0){
                        foreach ($holidays->result() as $key => $value) {
                          $selected = "";
                          if(isset($record_data['Holiday_Id']) > 0 && $record_data['Holiday_Id'] == $value->Id){ $selected = 'selected="selected"'; }
                          echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Title.'</option>';
                        }
                      }
                    ?> 
                  </select>
                </div>
              </div>
            </div> 
           
            <div class="row" id="repeated"  >  
              <div class="col-md-4 col-sm-12 col-xs-12 "> 
                <div class="col-md-12 col-sm-12 col-xs-12 ">
                  <label class="control-label">From Date </label>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-12"> 
                  <select class="select2 form-control " required="required"  name="From_Month" id="holiday_from_month" onchange="get_month_days(this,'holiday_from_day')">
                     <?php 
                        $from_month = $from_month_day = 0;
                        if(isset($record_data['Holiday_From_Day']) && !is_null($record_data['Holiday_From_Day']))
                        {
                          $applicable_date = date('m-d', strtotime(' +1 day', date("Y")."-".$record_data['Holiday_From_Day'] ));
                          
                          $date_parts = explode("-", $applicable_date);

                          $from_month = $date_parts[0];
                          $from_month_day = $date_parts[1]; 
                        }
                        elseif(isset($record_data['Holiday_From_Date']) && !is_null($record_data['Holiday_From_Date']))
                        {
                          $date_parts = explode("-", $record_data['Holiday_From_Date']);
                          $from_month = $date_parts[1];
                          $from_month_day = $date_parts[2]; 
                        }

                        if($from_month > 0 )
                        {
                           for ($i=$from_month; $i < 13; $i++) { 
                            $selected = "";
                            $month_name = date("F",strtotime(date("Y")."-".$i));
                            if(isset($record_data['From_Month']) && $record_data['From_Month'] > 0 && $record_data['From_Month'] == $i) { $selected = 'selected="selected"'; }
                            echo '<option '.$selected.' value="'.$i.'">'.$month_name.'</option>';
                           }
                        }
                     ?>
                  </select>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <select class="select2 col-md-12 col-xs-12" required="required"  name="From_Day" id="holiday_from_day">
                    <option value="0">Select Day</option>
                    <?php 

                      if($from_month_day > 0 )
                      {
                         $month_total_days = date("t", strtotime(date("Y")."-".$record_data['Holiday_From_Day']));
                         
                         for ($i=$from_month_day; $i < $month_total_days; $i++) { 
                          $selected = ""; 
                          if(isset($record_data['From_Day']) && $record_data['From_Day'] > 0 && $record_data['From_Day'] == $i) { $selected = 'selected="selected"'; }
                          echo '<option '.$selected.' value="'.$i.'">'.$i.'</option>';
                         }
                      } 
                    ?> 
                  </select>   
                </div>
              </div> 
              <div class="col-md-4 col-sm-12 col-xs-12 "> 
                <div class="col-md-12 col-sm-12 col-xs-12 ">
                  <label class="control-label">To Date </label>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-12">  
                  <select class="select2 form-control "  required="required" name="To_Month" id="holiday_to_month" onchange="get_month_days(this,'holiday_to_day')"> 
                     <?php 
                        $to_month = $to_month_day = 0;
                        if(isset($record_data['Repeat_Yearly']) && $record_data['Repeat_Yearly'] == "on")
                        {
                          
                          $my_date =  date("Y")."-".$record_data['Holiday_To_Day'];  
                          $date=date_create($my_date);
                          date_add($date,date_interval_create_from_date_string("1 days"));
                          $applicable_date =  date_format($date,"Y-m-d"); 

                          $date_parts = explode("-", $applicable_date);

                          $to_month = $date_parts[0];
                          $to_month_day = $date_parts[1]; 
                        }
                        elseif(isset($record_data['Repeat_Yearly']) && $record_data['Repeat_Yearly'] == "off")
                        {
                          $date_parts = explode("-", $record_data['Holiday_To_Date']);
                          $to_month = $date_parts[1];
                          $to_month_day = $date_parts[2]; 
                        }

                        if($to_month > 0 )
                        {
                           for ($i=$to_month; $i < 13; $i++) { 
                            $selected = "";
                            $month_name = date("F",strtotime(date("Y")."-".$i));
                            if(isset($record_data['From_Month']) && $record_data['From_Month'] > 0 && $record_data['From_Month'] == $i) { $selected = 'selected="selected"'; }
                            echo '<option '.$selected.' value="'.$i.'">'.$month_name.'</option>';
                           }
                        }
                     ?>
                  </select>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <select class="select2  col-md-12 col-xs-12" required="required"  name="To_Day" id="holiday_to_day">
                    <option value="0">Select Day</option>
                      <?php 

                        if($to_month_day > 0 )
                        {
                           $month_total_days = date("t", strtotime(date("Y")."-".$record_data['Holiday_To_Day']));
                           
                           for ($i=$to_month_day; $i < $month_total_days; $i++) { 
                            $selected = ""; 
                            if(isset($record_data['From_Day']) && $record_data['From_Day'] > 0 && $record_data['From_Day'] == $i) { $selected = 'selected="selected"'; }
                            echo '<option '.$selected.' value="'.$i.'">'.$i.'</option>';
                           }
                        } 
                      ?>
                  </select>   
                </div>
              </div> 
            </div> 
        </div> 
        <div class="form-group"> 
          <div class="col-sm-12">
            <br>
            <button type="submit" id="" class="btn btn-success pull-left btn-lg"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Submit </button>  
            <button type="button" style="margin-left: 10px;"  onclick="load_tab(this,'manage_holidays',<?= 0; ?>,'leave_tabs_body')" class="btn btn-danger pull-left btn-lg"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;Cancel </button>
          </div>
        </div>
      </form>
      <br><br>
       
    </div> 
      
         

 


<script type="text/javascript">
  $('.select2').select2(); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });

  function  get_month_and_days($this){
    $holiday_id = $($this).val();
    if($holiday_id > 0)
    {
      $.post("Admin/get_holiday_month_and_days",{id:$holiday_id},function(response){
          if(response){ 
            $data = $.parseJSON(response);
            $("#holiday_to_month").html($data.months);
            $("#holiday_to_day").html($data.days);
            $("#holiday_from_month").html($data.months);
            $("#holiday_from_day").html($data.days);
          }
      });
    }
  }


  function  get_month_days($this,$target){
    $month = $($this).val(); 
    if($month > 0 && $month < 13){
      var now = new Date(); 
      $year = now.getFullYear();
      $days = daysInMonth($month,$year);
      if($days > 0 && $days < 32){
        $days_options = "";
        for($i=1; $i <= $days; $i++){
          $days_options += '<option value="'+$i+'">'+$i+'</option>';
        }
        $("#"+$target).html($days_options);
      }
    }
    else{
      alert("Incorrect month number!");
    }
  }

  function daysInMonth (month, year){
    return new Date(year, month, 0).getDate();
  }

 
</script>