
<div class="mainpanel">
  <div class="contentpanel" style="margin-top: 20px;"> 
    <div class="row">
     <div class="col-md-12">  
          <ul class="nav nav-tabs nav-inverse"> 
            <?php if(in_array('Shifts',$this->allowed_modules_list)){ ?>
            <li class="active"  onclick="load_tab(this,'shifts',<?= 0; ?>,'shifts_tabs_body')"><a href="javascript:;" ><strong>Manage Shifts</strong></a></li>
            <?php }if(in_array('Shift Rotations',$this->allowed_modules_list)){ ?>
            <li  onclick="load_tab(this,'shift_rotations',<?= 0; ?>,'shifts_tabs_body')"><a href="javascript:;" ><strong>Shift Rotations</strong></a></li>  
          <?php }if(in_array('Shift Settings',$this->allowed_modules_list)){ ?>
            <li  onclick="load_tab(this,'shift_settings',<?= 0; ?>,'shifts_tabs_body')"><a href="javascript:;" ><strong>Shift Settings</strong></a></li>  
            <?php } ?> 
          </ul> 
         
          <div class="tab-content mb20">
            <div class="tab-pane active" id="shifts_tabs_body">
              <?php $this->load->view("pages/shifts"); ?>
            </div> 
          </div>
     </div>
    </div>  
  </div>
</div>
