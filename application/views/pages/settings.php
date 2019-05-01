 
<div class="mainpanel">
  <div class="contentpanel">
    <div class="panel">
      <div class="panel-body">
        <div class="col-md-12">
          <div class="col-md-12">
            <h3><i class="fa fa-user"></i>Organization Settings </h3>
          </div>
           
        </div>
      </div>
    </div>
    
    <div class="row tab-side-wrapper">
        <div class="col-xs-4 col-sm-2 tab-left applicant_tabs">
          <!-- Nav tabs -->
          <ul class="nav nav-pills nav-stacked">

            <li onclick="load_tab(this,'form_work_week_days','','settings_from_container')" class="active">
              <a href="#javascript:;"><strong>Week Work Days</strong></a>
            </li> 
            <li onclick="load_tab(this,'form_holidays_settings','','settings_from_container')" >
              <a href="#javascript:;"><strong>Leaves Setting</strong></a>
            </li> 
            <li onclick="load_tab(this,'form_org_email_settings','','settings_from_container')" >
              <a href="#javascript:;"><strong>Email Settings</strong></a>
            </li> 
            
          </ul>
        </div>
        <div class="col-xs-8 col-sm-10 col-xs-offset-4 col-sm-offset-2 tab-main"> 
          <div class="tab-content" style="min-height: 100vh;">
            <div class="tab-pane active" id="settings_from_container">
              <?php $this->load->view("pages/form_work_week_days"); ?>
            </div> 
          </div>
        </div>
      </div>
      
  </div>
</div>

<script type="text/javascript">
  $('.select2').select2(); 
</script>