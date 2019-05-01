
<div class="mainpanel">
  <div class="contentpanel" style="margin-top: 20px;"> 
    <div class="row">
     <div class="col-md-12">  
          <ul class="nav nav-tabs nav-inverse"> 
            <?php if(in_array('Applicants Report',$this->allowed_modules_list)){ ?>
            <li class="active"  onclick="load_tab(this,'report_applicants_tab',<?= 0; ?>,'reports_tabs_body')"><a href="javascript:;" ><strong>Applicants Reports</strong></a></li>
            <?php }if(in_array('Applicants Report',$this->allowed_modules_list)){ ?>
            <li  onclick="load_tab(this,'report_job_posts',<?= 0; ?>,'reports_tabs_body')"><a href="javascript:;" ><strong> Job Posts Report</strong></a></li>  
            <?php } ?> 
          </ul>

         
          <div class="tab-content mb20">
            <div class="tab-pane active" id="reports_tabs_body">
              <?php $this->load->view("pages/report_applicants_tab"); ?>
            </div> 
          </div>
     </div>
    </div>  
  </div>
</div>

