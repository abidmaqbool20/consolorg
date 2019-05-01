<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('test_method'))
{
    function test_method($var = '')
    {
        return $var;
    }   

    function create_childs($id,$processed=array(),$permissions=array())
    {

    	$html = "";
        $ci =& get_instance();
        $child_modules = $ci->db->get_where("application_modules",array("Deleted"=>0,"Status"=>1,"Parent_Module"=>$id));
        if($child_modules->num_rows() > 0)
        {
          $html .= '<ul>';
          foreach ($child_modules->result() as $key => $value) 
          {
          	if(!in_array($value->Id, $processed))
            {
            	$processed[] = $value->Id;  

	          	if(in_array($value->Id, $permissions))
	            {
	              $checkbox_checked = "checked='checked'";
	            }
	            else
	            {
	              $checkbox_checked = "";
	            }

		          $html .= '<li> <span><i class="fa fa-minus"></i> </span> 
		          			<label class="ckbox ckbox-primary">
		                      <input type="checkbox" onclick="check_modules(this)" '.$checkbox_checked.' value="'.$value->Id.'" id="module_'.$value->Id.'" name="Permissions[]" class="modules_checkbox"><span class="module_title">'.$value->Name.'</span>
		                    </label>';

		          $returned_info = create_childs($value->Id,$processed,$permissions);
		          $processed = $returned_info['ids'];
		          $html .= $returned_info['html'] ;
		          $html .= '</li>';
		     }
          }
          $html .= '</ul>';
        }

        $processed_data['ids'] = $processed;
        $processed_data['html'] = $html;
        return $processed_data;
    }

    function create_permissions_childs($id,$processed=array(),$permissions=array())
    {

        $html = "";
        $ci =& get_instance();
        $child_modules = $ci->db->get_where("application_modules",array("Deleted"=>0,"Status"=>1,"Parent_Module"=>$id));
        if($child_modules->num_rows() > 0)
        {
          $html .= '<ul>';
          foreach ($child_modules->result() as $key => $value) 
          {
            if(!in_array($value->Id, $processed))
            {
                  $processed[] = $value->Id;  

                  if(in_array($value->Id, $permissions))
                  {
                    $checkbox_checked = "checked='checked'";
                  }
                  else
                  {
                    $checkbox_checked = "";
                  }

                  $html .= '<li> <span><i class="fa fa-minus"></i> </span> 
                            <label class="ckbox ckbox-primary">
                              <input type="checkbox" onclick="check_modules(this)" '.$checkbox_checked.' value="'.$value->Id.'" id="module_'.$value->Id.'" name="Permissions[]" class="modules_checkbox"><span class="module_title">'.$value->Name.'</span>
                            </label>';

                  $returned_info = create_permissions_childs($value->Id,$processed,$permissions);
                  $processed = $returned_info['ids'];
                  $html .= $returned_info['html'] ;
                  $html .= '</li>';
            }
          }

          $html .= '</ul>';
        }
        else
        { 
          if(in_array($id."_add", $permissions)){ $add_checked = "checked='checked'"; }else{ $add_checked = ""; }
          if(in_array($id."_edit", $permissions)){ $edit_checked = "checked='checked'"; }else{ $edit_checked = ""; }
          if(in_array($id."_delete", $permissions)){ $delete_checked = "checked='checked'"; }else{ $delete_checked = ""; }
          if(in_array($id."_view", $permissions)){ $view_checked = "checked='checked'"; }else{ $view_checked = ""; }
          if(in_array($id."_export", $permissions)){ $export_checked = "checked='checked'"; }else{ $export_checked = ""; }
          if(in_array($id."_import", $permissions)){ $import_checked = "checked='checked'"; }else{ $import_checked = ""; }
          if(in_array($id."_filter", $permissions)){ $filter_checked = "checked='checked'"; }else{ $filter_checked = ""; }

          $html = '<ul>
                      <li> <span><i class="fa fa-minus"></i> </span> 
                        <label class="ckbox ckbox-primary">
                          <input type="checkbox" onclick="check_modules(this)" '.$add_checked.' value="'.$id.'_add" id="module_'.$id.'_add" name="Permissions[]" class="modules_checkbox"><span class="module_title">Add</span>
                        </label>
                      </li>
                      <li> <span><i class="fa fa-minus"></i> </span> 
                        <label class="ckbox ckbox-primary">
                          <input type="checkbox" onclick="check_modules(this)" '.$edit_checked.' value="'.$id.'_edit" id="module_'.$id.'_edit" name="Permissions[]" class="modules_checkbox"><span class="module_title">Edit</span>
                        </label>
                      </li>
                      <li> <span><i class="fa fa-minus"></i> </span> 
                        <label class="ckbox ckbox-primary">
                          <input type="checkbox" onclick="check_modules(this)" '.$delete_checked.' value="'.$id.'_delete" id="module_'.$id.'_delete" name="Permissions[]" class="modules_checkbox"><span class="module_title">Delete</span>
                        </label>
                      </li>
                      <li> <span><i class="fa fa-minus"></i> </span> 
                        <label class="ckbox ckbox-primary">
                          <input type="checkbox" onclick="check_modules(this)" '.$view_checked.' value="'.$id.'_view" id="module_'.$id.'_view" name="Permissions[]" class="modules_checkbox"><span class="module_title">View</span>
                        </label>
                      </li>
                      <li> <span><i class="fa fa-minus"></i> </span> 
                        <label class="ckbox ckbox-primary">
                          <input type="checkbox" onclick="check_modules(this)" '.$export_checked.' value="'.$id.'_export" id="module_'.$id.'_export" name="Permissions[]" class="modules_checkbox"><span class="module_title">Export</span>
                        </label>
                      </li>
                      <li> <span><i class="fa fa-minus"></i> </span> 
                        <label class="ckbox ckbox-primary">
                          <input type="checkbox" onclick="check_modules(this)" '.$import_checked.' value="'.$id.'_import" id="module_'.$id.'_import" name="Permissions[]" class="modules_checkbox"><span class="module_title">Import</span>
                        </label>
                      </li>
                      <li> <span><i class="fa fa-minus"></i> </span> 
                        <label class="ckbox ckbox-primary">
                          <input type="checkbox" onclick="check_modules(this)" '.$filter_checked.' value="'.$id.'_filter" id="module_'.$id.'_filter" name="Permissions[]" class="modules_checkbox"><span class="module_title">Filter</span>
                        </label>
                      </li>
                    </ul>
                  ';

        }
       

        $processed_data['ids'] = $processed;
        $processed_data['html'] = $html;
        return $processed_data;
    }

    function create_childs_modules($id,$processed=array(),$allowed_modules=array(),$allowed_modules_names=array())
    {

        $html = "";
        $ci =& get_instance();
        $child_modules = $ci->db->get_where("application_modules",array("Deleted"=>0,"Status"=>1,"Parent_Module"=>$id));
        if($child_modules->num_rows() > 0)
        {
          $html .= '<ul class="children" style="display: none;">';
          foreach ($child_modules->result() as $key => $value) 
          {
            if(in_array($value->Id, $allowed_modules))
            {
              if(!in_array($value->Id, $processed))
              {
                  $processed[] = $value->Id;  
                  $allowed_modules_names[] = $value->Name; 

                  $returned_info = create_childs_modules($value->Id,$processed,$allowed_modules,$allowed_modules_names);
                  $processed = $returned_info['ids'];   
                  $allowed_modules_names = $returned_info['allowed_modules_names'];   
              }
            }
          } 
        }

        $processed_data['ids'] = $processed; 
        $processed_data['allowed_modules_names'] = $allowed_modules_names;  
        return $processed_data;
    }


    function no_record_found()
    {
      return $no_record = '<div class="col-md-12 col-sm-12 col-xs-12">
                              <div class="no_record_found">
                                <img class="no_results" src="'.ASSETSPATH.'/images/no-record-found.png">
                              </div> 
                            </div>';
    }

    function get_file_icon($ext)
    {
        $images_extensions = array("png","jpg","ai","bmp","gif","ico","jpeg","ps","psd","svg","tif","tiff");
        $videos_extensions = array("mp4","mkv","flv","webm","vob","avi","wmv","svi");
        $audios_extensions = array("mp3","aif","cda","mid","wav","wma","wpl","mpa");
        $compressed_extensions = array("7z","arj","pkg","deb","rar","rpm","tar.gz","z","zip");
        $excel_extensions = array("ods","xlr","xls","xlsx");
        $word_extensions = array("doc","docx","txt","wpd","wks","wps","rtf");
        $pdf_extensions = array("pdf");

        $file_ico = "";

        if(in_array($ext, $images_extensions))
        {
          $file_ico = "fa fa-file-image-o";
        }
        elseif(in_array($ext, $videos_extensions))
        {
          $file_ico = "fa fa-file-video-o";
        }
        elseif(in_array($ext, $audios_extensions))
        {
          $file_ico = "fa fa-file-audio-o";
        }
        elseif(in_array($ext, $compressed_extensions))
        {
          $file_ico = "fa fa-file-archive-o";
        }
        elseif(in_array($ext, $excel_extensions))
        {
          $file_ico = "fa fa-file-excel-o";
        }
        elseif(in_array($ext, $word_extensions))
        {
          $file_ico = "fa fa-file-word-o";
        }
        elseif(in_array($ext, $pdf_extensions))
        {
          $file_ico = "fa fa-file-pdf-o";
        }

        return $file_ico;
    }

    function get_file_type($ext)
    {
        $images_extensions = array("png","jpg","ai","bmp","gif","ico","jpeg","ps","psd","svg","tif","tiff");
        $videos_extensions = array("mp4","mkv","flv","webm","vob","avi","wmv","svi");
        $audios_extensions = array("mp3","aif","cda","mid","wav","wma","wpl","mpa");
        $compressed_extensions = array("7z","arj","pkg","deb","rar","rpm","tar.gz","z","zip");
        $excel_extensions = array("ods","xlr","xls","xlsx");
        $word_extensions = array("doc","docx","txt","wpd","wks","wps","rtf");
        $pdf_extensions = array("pdf");

        $file_type = "";

        if(in_array($ext, $images_extensions))
        {
          $file_type = "image";
        }
        elseif(in_array($ext, $videos_extensions))
        {
          $file_type = "video";
        }
        elseif(in_array($ext, $audios_extensions))
        {
          $file_type = "audio";
        }
        elseif(in_array($ext, $compressed_extensions))
        {
          $file_type = "zip";
        }
        elseif(in_array($ext, $excel_extensions))
        {
          $file_type = "excel";
        }
        elseif(in_array($ext, $word_extensions))
        {
          $file_type = "word";
        }
        elseif(in_array($ext, $pdf_extensions))
        {
          $file_type = "pdf";
        }

        return $file_type;
    }


    function date_difference($date_start='',$date_end='')
    {
         
        $d_start    = new DateTime($date_start); 
        $d_end      = new DateTime($date_end); 
        $diff = $d_start->diff($d_end); 
        // return all data 
        $difference['Year'] = $diff->format('%y'); 
        $difference['Month'] = $diff->format('%m'); 
        $difference['Day'] = $diff->format('%d'); 
        $difference['Hour'] = $diff->format('%h'); 
        $difference['Minuts'] = $diff->format('%i'); 
        $difference['Seconds'] = $diff->format('%s'); 
       
        return $difference;
    }

    function employee_unique_id($joining_date,$emp_id){
      return "#".date("my",strtotime($joining_date)).$emp_id;
    }



} 