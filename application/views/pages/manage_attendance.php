<div class="mainpanel">
  <div class="contentpanel" style="margin-top: 20px;"> 
    <div class="row">
     <div class="col-md-12">  
          <ul class="nav nav-tabs nav-inverse"> 
            <?php if(in_array('Today Attendance',$this->allowed_modules_list)){ ?>
            <li class="active"  onclick="load_tab(this,'today_attendance',<?= 0; ?>,'attendance_tabs_body')"><a href="javascript:;" ><strong>Today Attendance</strong></a></li>
            <?php }if(in_array('Update Attendance',$this->allowed_modules_list)){ ?>
            <li  onclick="load_tab(this,'update_attendance',<?= 0; ?>,'attendance_tabs_body')"><a href="javascript:;" ><strong>Update Attendance</strong></a></li> 
            <?php }if(in_array('Attendance History',$this->allowed_modules_list)){ ?>
            <li  onclick="load_tab(this,'attendance_history',<?= 0; ?>,'attendance_tabs_body')"><a href="javascript:;" ><strong>Attendance History</strong></a></li>
          <?php }if(in_array('Attendance Sheet',$this->allowed_modules_list)){ ?>
            <li  onclick="load_tab(this,'attendance_sheet',<?= 0; ?>,'attendance_tabs_body')"><a href="javascript:;" ><strong>Attendance Sheet</strong></a></li>
            <?php } ?> 
          </ul>

         
          <div class="tab-content mb20">
            <div class="tab-pane active" id="attendance_tabs_body">
              <?php $this->load->view("pages/today_attendance"); ?>
            </div> 
          </div>
     </div>
    </div>  
  </div>
</div>
