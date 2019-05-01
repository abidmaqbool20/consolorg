
<div class="mainpanel">
  <div class="contentpanel" style="margin-top: 20px;"> 
    <div class="row">
     <div class="col-md-12">  
          <ul class="nav nav-tabs nav-inverse"> 
            <?php if(in_array('Question Categories',$this->allowed_modules_list)){ ?>
            <li class="active"  onclick="load_tab(this,'question_categories',<?= 0; ?>,'questions_tabs_body')"><a href="javascript:;" ><strong>Question Categories</strong></a></li>
            <?php }if(in_array('Manage Questions',$this->allowed_modules_list)){ ?>
            <li  onclick="load_tab(this,'manage_questions',<?= 0; ?>,'questions_tabs_body')"><a href="javascript:;" ><strong>Manage Questions</strong></a></li>  
            <?php } ?> 
          </ul> 
         
          <div class="tab-content mb20">
            <div class="tab-pane active" id="questions_tabs_body">
              <?php $this->load->view("pages/question_categories"); ?>
            </div> 
          </div>
     </div>
    </div>  
  </div>
</div>
