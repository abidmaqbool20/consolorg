  
    <?php 

        $record_data = array(); 
        if($data && $data != "")
        {
          $this->db->where(array("organization_holidays.Org_Id"=>$this->org_id,"organization_holidays.Id"=>$data ));
          $this->db->select("organization_holidays.*  ");
          $this->db->from("organization_holidays");  
          $this->db->order_by("organization_holidays.Id","ASC");
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
      <form id="common_form" method="post" action="<?= base_url("Admin/save_organization_holiday") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
        <div class="error"></div>
        <div class="form-group"> 
            <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id']) && $record_data['Id'] != "" ){ echo $record_data['Id']; } ?>">  
            <input type="hidden" name="Table_Name" id="Table_Name" value="organization_holidays">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 
            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12"> 
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <h2>Add / Update Holiday</h2> <hr>
                </div>
              </div> 
            </div>

             <div class="col-sm-12  col-xs-12">
              <div class="">
                <label class="control-label">Holiday Title <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control required" name="Title" id="Title"  value="<?php if(isset($record_data['Title']) && $record_data['Title'] != "" ){ echo $record_data['Title']; } ?>"  >
              </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 "  >
              <div class="">
                <label class="control-label"></label> 
                <label class="ckbox ckbox-primary" style="margin-top: 15px;">
                    <input onclick="check_holiday_level(this)" type="checkbox" id="Repeat_Yearly" name="Repeat_Yearly" <?php  if($data == 0 || (isset($record_data['Repeat_Yearly']) && $record_data['Repeat_Yearly'] == "on" )){ echo 'checked="checked"'; }  ?>><span>Repeat Every Year</span>
                </label>
              </div>
            </div>
            <div id="not_repeated" style="display: <?php if(isset($record_data['Repeat_Yearly']) && $record_data['Repeat_Yearly'] == "on" ){ echo 'none'; }elseif($data == 0){  echo "none";  } ?>"> 
              <div class="col-sm-4 col-xs-12 ">
                <div class="">
                  <label class="control-label">From Date<span class="text-danger">*</span><span class="text-danger error"></span></label>
                  <input type="text" name="From_Date" id="From_Date" class="form-control datepicker"  value="<?php if(isset($record_data['From_Date']) && $record_data['From_Date'] != "" ){ echo $record_data['From_Date']; }else{ echo date("Y-m-d"); } ?>"  >  
                </div>
              </div> 
              <div class="col-sm-4 col-xs-12 ">
                <div class="">
                  <label class="control-label">To Date<span class="text-danger">*</span><span class="text-danger error"></span></label>
                  <input type="text" name="To_Date" id="To_Date" class="form-control datepicker" value="<?php  if(isset($record_data['To_Date']) && $record_data['To_Date'] != "" ){ echo $record_data['To_Date']; }else{ echo date("Y-m-d"); } ?>"  >  
                </div>
              </div> 
            </div>
            <div class="row" id="repeated" style="display: <?php if(isset($record_data['Repeat_Yearly']) && $record_data['Repeat_Yearly'] == "on" ){ echo 'block'; } ?>">  
              <div class="col-md-4 col-sm-12 col-xs-12 "> 
                <div class="col-md-12 col-sm-12 col-xs-12 ">
                  <label class="control-label">From Date </label>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-12"> 
                  <select class="select2 form-control " name="From_Month" id="holiday_from_month" onchange="get_month_days(this,'holiday_from_day')">
                    <option <?php  if(isset($record_data['From_Month'])){ if($record_data['From_Month'] == "1"){ echo "selected='selected'"; }} ?> value="1">January</option>
                    <option <?php  if(isset($record_data['From_Month'])){ if($record_data['From_Month'] == "2"){ echo "selected='selected'"; }} ?> value="2">February</option>
                    <option <?php  if(isset($record_data['From_Month'])){ if($record_data['From_Month'] == "3"){ echo "selected='selected'"; }} ?> value="3">March</option>
                    <option <?php  if(isset($record_data['From_Month'])){ if($record_data['From_Month'] == "4"){ echo "selected='selected'"; }} ?> value="4">April</option>
                    <option <?php  if(isset($record_data['From_Month'])){ if($record_data['From_Month'] == "5"){ echo "selected='selected'"; }} ?> value="5">May</option>
                    <option <?php  if(isset($record_data['From_Month'])){ if($record_data['From_Month'] == "6"){ echo "selected='selected'"; }} ?> value="6">June</option>
                    <option <?php  if(isset($record_data['From_Month'])){ if($record_data['From_Month'] == "7"){ echo "selected='selected'"; }} ?> value="7">July</option>
                    <option <?php  if(isset($record_data['From_Month'])){ if($record_data['From_Month'] == "8"){ echo "selected='selected'"; }} ?> value="8">August</option>
                    <option <?php  if(isset($record_data['From_Month'])){ if($record_data['From_Month'] == "9"){ echo "selected='selected'"; }} ?> value="9">September</option>
                    <option <?php  if(isset($record_data['From_Month'])){ if($record_data['From_Month'] == "10"){ echo "selected='selected'"; }} ?> value="10">October</option>
                    <option <?php  if(isset($record_data['From_Month'])){ if($record_data['From_Month'] == "11"){ echo "selected='selected'"; }} ?> value="11">November</option>
                    <option <?php  if(isset($record_data['From_Month'])){ if($record_data['From_Month'] == "12"){ echo "selected='selected'"; }} ?> value="12">December</option>
                  </select>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <select class="select2 col-md-12 col-xs-12" name="From_Day" id="holiday_from_day">
                    <option value="0">Select Day</option>
                    <?php 
                      if(isset($record_data['From_Day'])){
                        $leave_date = date("Y")."-".$record_data['From_Month']."-".$record_data['From_Day'];
                        $month_days = date("t", strtotime($leave_date));
                        if($month_days > 0)
                        {
                          for($i=0; $i<=$month_days;$i++){
                            $selected = "";
                            if($record_data['To_Day'] == $i){ $selected = 'selected="selected"'; }
                            echo '<option '.$selected.' value="'.$i.'">'.$i.'</option>';
                          }
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
                  <select class="select2 form-control " name="To_Month" id="holiday_to_month" onchange="get_month_days(this,'holiday_to_day')"> 
                    <option <?php  if(isset($record_data['To_Month'])){ if($record_data['To_Month'] == "1"){ echo "selected='selected'"; }} ?> value="1">January</option>
                    <option <?php  if(isset($record_data['To_Month'])){ if($record_data['To_Month'] == "2"){ echo "selected='selected'"; }} ?> value="2">February</option>
                    <option <?php  if(isset($record_data['To_Month'])){ if($record_data['To_Month'] == "3"){ echo "selected='selected'"; }} ?> value="3">March</option>
                    <option <?php  if(isset($record_data['To_Month'])){ if($record_data['To_Month'] == "4"){ echo "selected='selected'"; }} ?> value="4">April</option>
                    <option <?php  if(isset($record_data['To_Month'])){ if($record_data['To_Month'] == "5"){ echo "selected='selected'"; }} ?> value="5">May</option>
                    <option <?php  if(isset($record_data['To_Month'])){ if($record_data['To_Month'] == "6"){ echo "selected='selected'"; }} ?> value="6">June</option>
                    <option <?php  if(isset($record_data['To_Month'])){ if($record_data['To_Month'] == "7"){ echo "selected='selected'"; }} ?> value="7">July</option>
                    <option <?php  if(isset($record_data['To_Month'])){ if($record_data['To_Month'] == "8"){ echo "selected='selected'"; }} ?> value="8">August</option>
                    <option <?php  if(isset($record_data['To_Month'])){ if($record_data['To_Month'] == "9"){ echo "selected='selected'"; }} ?> value="9">September</option>
                    <option <?php  if(isset($record_data['To_Month'])){ if($record_data['To_Month'] == "10"){ echo "selected='selected'"; }} ?> value="10">October</option>
                    <option <?php  if(isset($record_data['To_Month'])){ if($record_data['To_Month'] == "11"){ echo "selected='selected'"; }} ?> value="11">November</option>
                    <option <?php  if(isset($record_data['To_Month'])){ if($record_data['To_Month'] == "12"){ echo "selected='selected'"; }} ?> value="12">December</option>
                  </select>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <select class="select2  col-md-12 col-xs-12" name="To_Day" id="holiday_to_day">
                    <option value="0">Select Day</option>
                     <?php 
                      if(isset($record_data['To_Day'])){
                        $leave_date = date("Y")."-".$record_data['To_Month']."-".$record_data['To_Day'];
                        $month_days = date("t", strtotime($leave_date));
                        if($month_days > 0)
                        {
                          for($i=0; $i<=$month_days;$i++){
                            $selected = "";
                            if($record_data['To_Day'] == $i){ $selected = 'selected="selected"'; }
                            echo '<option '.$selected.' value="'.$i.'">'.$i.'</option>';
                          }
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
            <button type="button" style="margin-left: 10px;"  onclick="load_tab(this,'form_organization_holiday',<?= 1; ?>,'leave_tabs_body')" class="btn btn-warning pull-left btn-lg"><i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;Reset </button> 
            <button type="button" style="margin-left: 10px;"  onclick="load_tab(this,'manage_holidays',<?= 0; ?>,'leave_tabs_body')" class="btn btn-danger pull-right btn-lg"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;Cancel </button>
          </div>
        </div>
      </form>
      <br><br>
       
    </div> 
      
         

 


<script type="text/javascript">
  $('.select2').select2(); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });

  //$("#repeated").css("display","none");
  //$("#not_repeated").css("display","none");

  function check_holiday_level($this){
    if($($this).is(":checked")){
      $("#repeated").css("display","block");
      $("#not_repeated").css("display","none");
    }
    else{
      $("#repeated").css("display","none");
      $("#not_repeated").css("display","block");
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