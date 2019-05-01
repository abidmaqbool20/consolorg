
<div class="mainpanel">
  <div class="contentpanel" style="margin-top: 20px;"> 
    <div class="row">
     <div class="col-md-12">  
          <ul class="nav nav-tabs nav-inverse"> 
            <?php if(in_array('Attendance Report',$this->allowed_modules_list)){ ?>
            <li class="active"  onclick="load_tab(this,'report_late_time',<?= 0; ?>,'reports_tabs_body')"><a href="javascript:;" ><strong>Late Coming Time</strong></a></li>
            <?php }if(in_array('Attendance Report',$this->allowed_modules_list)){ ?>
            <li  onclick="load_tab(this,'report_attendance_tab',<?= 0; ?>,'reports_tabs_body')"><a href="javascript:;" ><strong> Attendance Report</strong></a></li>  
            <?php } ?> 
          </ul>

         
          <div class="tab-content mb20">
            <div class="tab-pane active" id="reports_tabs_body">
              <?php $this->load->view("pages/report_late_time"); ?>
            </div> 
          </div>
     </div>
    </div>  
  </div>
</div>

