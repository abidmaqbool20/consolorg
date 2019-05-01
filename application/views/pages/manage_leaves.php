<div class="mainpanel">
  <div class="contentpanel" style="margin-top: 20px;"> 
    <div class="row">
     <div class="col-md-12">  
          <ul class="nav nav-tabs nav-inverse"> 
            <?php if(in_array('Manage Leave Applications',$this->allowed_modules_list)){ ?>
            <li class="active"  onclick="load_tab(this,'manage_leave_applications',<?= 0; ?>,'leave_tabs_body')"><a href="javascript:;" ><strong>Manage Leave Applications</strong></a></li> 
            <?php }if(in_array('Update Leaves',$this->allowed_modules_list)){ ?>
            <li  onclick="load_tab(this,'update_leaves',<?= 0; ?>,'leave_tabs_body')"><a href="javascript:;" ><strong>Update Leaves</strong></a></li> 
            <?php }if(in_array('Leaves Record',$this->allowed_modules_list)){ ?>
            <li  onclick="load_tab(this,'leaves_record',<?= 0; ?>,'leave_tabs_body')"><a href="javascript:;" ><strong>Employees Leaves Record</strong></a></li>
            <?php }if(in_array('Leaves History',$this->allowed_modules_list)){ ?>
            <li  onclick="load_tab(this,'leaves_history',<?= 0; ?>,'leave_tabs_body')"><a href="javascript:;" ><strong>Leaves History</strong></a></li>
            <?php }if(in_array('Manage Holidays',$this->allowed_modules_list)){ ?>
            <li onclick="load_tab(this,'manage_holidays',<?= 0; ?>,'leave_tabs_body')"><a href="javascript:;">Organization Holidays</a></li> 
            <?php }if(in_array('Holidays Calendar',$this->allowed_modules_list)){ ?>
            <li  onclick="load_tab(this,'holidays_calendar',<?= 0; ?>,'leave_tabs_body')"><a href="javascript:;" ><strong>Holidays Calendar</strong></a></li> 
            <?php }if(in_array('Leaves Setting',$this->allowed_modules_list)){ ?>
            <li  onclick="load_tab(this,'leave_settings',<?= 0; ?>,'leave_tabs_body')"><a href="javascript:;" ><strong>Leave Settings</strong></a></li>
            <?php } ?>  
          </ul>

         
          <div class="tab-content mb20">
            <div class="tab-pane active" id="leave_tabs_body">
              <?php $this->load->view("pages/manage_leave_applications"); ?>
            </div> 
          </div>
     </div>
    </div>  
  </div>
</div>
