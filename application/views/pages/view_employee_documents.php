 
<?php $record_data = array(); ?>
<style type="text/css">
  .file-ico
  {
    font-size: 110px;
    padding: 0 30px;
  }
</style>
    <div class="panel-body"> 
      
    
      <div class="form-group">
        <div class="row">
          <div class="well well-asset-options clearfix">
            <div class="btn-toolbar btn-toolbar-media-manager pull-left" role="toolbar">
              <div class="btn-group" role="group">
                <!-- <button type="button" class="btn btn-default"><i class="fa fa-share"></i> Share</button> -->
                <button type="button" class="btn btn-default" value="Download" onclick="perform_group_action('files',this)"><i class="fa fa-download"></i> Download</button>
              </div>
              <div class="btn-group" role="group"> 
                <button type="button" class="btn btn-default " value="Delete" onclick="perform_group_action('files',this)"><i class="fa fa-trash"></i> Delete</button>
              </div>
            </div><!-- btn-toolbar -->

            <div class="btn-group pull-right files_filter" data-toggle="buttons">
              <label class="btn btn-default-active active" onclick="filter_files(this,'All')"> All </label>
              <label class="btn btn-default-active" onclick="filter_files(this,'image')"> Images </label>
              <label class="btn btn-default-active" onclick="filter_files(this,'video')"> Videos </label>
              <label class="btn btn-default-active" onclick="filter_files(this,'document')"> Documents </label>
              <label class="btn btn-default-active" onclick="filter_files(this,'audio')"> Audio </label>
            </div>
          </div>
        </div>
        <div class="row filemanager">
        
        <?php

          $this->db->where(array("files.Org_Id"=>$this->org_id,"files.Employee_Id"=>$data,"files.Deleted"=>0));
          $this->db->select("files.*");
          $this->db->from("files");  
          $this->db->order_by("files.Id","DESC");
          $files = $this->db->get();
          

          if($files->num_rows() > 0)
          {  
             foreach ($files->result() as $key => $value) 
             {  
                $file_name_parts = explode(".", $value->Original_Name);
                
                if(isset($file_name_parts[1]))
                {
                  $ext = strtolower($file_name_parts[1]); 
                  $file_ico_class = get_file_icon($ext);
                  $file_type = get_file_type($ext);
                  if(strlen($value->File_Name) > 20)
                  {
                    $file_name = substr($value->File_Name,0,20).".".$ext;
                  }
                  else
                  {
                    $file_name = $value->File_Name;
                  }




                  $file_path = "assets/panel/userassets/".$value->Table_Name."/".$value->Table_Id."/".$value->Original_Name; 

                      echo '<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 all_files" file-type="'.$file_type.'" id="row_'.$value->Id.'">
                              <div class="thmb">
                                <label class="ckbox">
                                  <input type="checkbox" class="table_record_checkbox" value="'.$value->Id.'" id="record_'.$value->Id.'"><span></span>
                                </label>
                                <div class="btn-group fm-group">
                                    <button type="button" class="btn btn-default dropdown-toggle fm-toggle" data-toggle="dropdown">
                                      <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu pull-right fm-menu" role="menu"> 
                                      <li><a href="'.$file_path.'" download><i class="fa fa-download"></i> Download</a></li> 
                                      <li ><a href="javascript:;" onclick="delete_record(\'files\','.$value->Id.',this)" ><i class="fa fa-trash-o"></i> Delete</a></li>
                                    </ul>
                                </div> 
                                <div class="thmb-prev">
                                  <a target="_blank" href="'.$file_path.'">
                                    <div class="file-ico"><i class="'.$file_ico_class.'"></i></div>
                                  </a>
                                </div>
                                <h5 class="fm-title"><a href="javascript:;">'.$file_name.'</a></h5>
                                <small class="text-muted">Added: '.date("M d, Y", strtotime($value->Date_Added)).'</small>
                              </div> 
                          </div>';   
                }
              }  
          }
          else
          {
            echo no_record_found(); 
          }

        ?>
        </div>
      </div>
    </div> 
      
 <script>
   

 
    jQuery('.thmb').hover(function(){
      var t = jQuery(this);
      t.find('.ckbox').show();
      t.find('.fm-group').show();
    }, function() {
      var t = jQuery(this);
      if(!t.closest('.thmb').hasClass('checked')) {
        t.find('.ckbox').hide();
        t.find('.fm-group').hide();
      }
    });

    jQuery('.ckbox').each(function(){
      var t = jQuery(this);
      var parent = t.parent();
      if(t.find('input').is(':checked')) {
        t.show();
        parent.find('.fm-group').show();
        parent.addClass('checked');
      }
    });


    jQuery('.ckbox').click(function(){
      var t = jQuery(this);
      if(!t.find('input').is(':checked')) {
        t.closest('.thmb').removeClass('checked');
        enable_itemopt(false);
      } else {
        t.closest('.thmb').addClass('checked');
        enable_itemopt(true);
      }
    });

    jQuery('#selectall').click(function(){
      if(jQuery(this).is(':checked')) {
        jQuery('.thmb').each(function(){
          jQuery(this).find('input').attr('checked',true);
          jQuery(this).addClass('checked');
          jQuery(this).find('.ckbox, .fm-group').show();
        });
        enable_itemopt(true);
      } else {
        jQuery('.thmb').each(function(){
          jQuery(this).find('input').attr('checked',false);
          jQuery(this).removeClass('checked');
          jQuery(this).find('.ckbox, .fm-group').hide();
        });
        enable_itemopt(false);
      }
    });

    function enable_itemopt(enable) {
      if(enable) {
        jQuery('.itemopt').removeClass('disabled');
      } else {

        // check all thumbs if no remaining checks
        // before we can disabled the options
        var ch = false;
        jQuery('.thmb').each(function(){
          if(jQuery(this).hasClass('checked'))
            ch = true;
        });

        if(!ch)
          jQuery('.itemopt').addClass('disabled');
      }
    }
 

 
</script>