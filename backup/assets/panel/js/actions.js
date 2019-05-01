 
function add_loader()
{
	$("#loader").css("display","block");
}

function remove_loader()
{
	$("#loader").css("display","none");
}

function validation($this)
{
	$validation = true; 
	$($this).find(".required").each(function(e){
		if($(this).val() != "")
		{
			if($(this).attr("type") == "email")
			{
				if(validateEmail($(this).val()))
				{
					$(this).parent("div").find("label .error").remove();
				}
				else
				{
					$(this).parent("div").find("label .error").html('Enter Valid Email!');
					$validation = false;
				}
			}
			else
			{
				$(this).parent("div").find("label .error").html("");
			} 
		}
		else
		{
			$validation = false; 
			$(this).parent("div").find("label .error").html('*');
		}

		
	});
  
	return $validation;
}

function save_record($this)
{
	if(validation())
	{
		for ( instance in CKEDITOR.instances ){  CKEDITOR.instances[instance].updateElement(); }
		var formData = new FormData($this);
		$.ajax({
				    type:'POST',
				    url: $($this).attr("action"),
				    data: formData,  
				   	cache: false,
			        contentType: false,
			        processData: false,
				    beforeSend:function(){

				    	  add_loader();
				    },
				    xhr: function() 
				    { 
		                var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                percentComplete = parseInt(percentComplete * 100);
                                $("#loading_progress").html(percentComplete + '%');  
                            }
                        }, false);

                        return xhr; 
                    },
				    success:function(msg)
				    {  
				    	 $message = $.parseJSON(msg);
				    	 remove_loader()
				    	 
				    	 if($message['Success'])
				    	 { 

					    	if($($this).find("#Edit_Recorde").val() == "")
				    	 	{
				    	 		$("#Edit_Recorde").val($message['Id']); 
				    	 		$($this).trigger("reset"); 
				    	 	} 
 
			    	 		swal(
							      'Successful!',
							       $message['Message'],
							      'success'
								); 
				    	    
				    	 }
				    	 else
				    	 {
				    	 	 
				    	 	swal(
							      'Error!',
							       $message['Message'],
							      'error'
							    );
					    	 
				    	 }  
				    },
				    error:function(msg){

				    		 

				    	 	if(remove_loader())
				    	 	{
						    	 swal(
									      'Error!',
									       msg,
									      'error'
									 );
						    }
				    },
				    complete:function(){
				    	
				    	 remove_loader()
				    	 $("#loading_progress").html("");
				    }


				});

	}	
	 
	 return false;
 
}

function change_application_status($this)
{ 
	if(validation())
	{
		for ( instance in CKEDITOR.instances ){  CKEDITOR.instances[instance].updateElement(); }
		var formData = new FormData($this);

		$ids =   $('.table_record_checkbox:checked').map(function() 
			 	 {
				   return this.value;
				 }).get(); 
		formData.append('Applicants', $ids);
		$.ajax({
				    type:'POST',
				    url: $($this).attr("action"),
				    data: formData,  
				   	cache: false,
			        contentType: false,
			        processData: false,
				    beforeSend:function(){

				    	  add_loader();
				    },
				    xhr: function() 
				    { 
		                var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                percentComplete = parseInt(percentComplete * 100);
                                $("#loading_progress").html(percentComplete + '%');  
                            }
                        }, false);

                        return xhr; 
                    },
				    success:function(msg)
				    {  
				    	 $message = $.parseJSON(msg);
				    	 remove_loader()
				    	 
				    	 if($message['Success'])
				    	 {  
			    	 		swal(
							      'Successful!',
							       $message['Message'],
							      'success'
								); 
				    	    
				    	 }
				    	 else
				    	 {
				    	 	 
				    	 	swal(
							      'Error!',
							       $message['Message'],
							      'error'
							    );
					    	 
				    	 }  
				    },
				    error:function(msg){

				    		 

				    	 	if(remove_loader())
				    	 	{
						    	 swal(
									      'Error!',
									       msg,
									      'error'
									 );
						    }
				    },
				    complete:function(){
				    	
				    	 remove_loader()
				    	 $("#loading_progress").html("");
				    } 

				});

	}	
	 
	return false;
}

$counter = 0;
function selectallrecords($this,$table)
{ 
	$counter = 0;
	if($($this).is(":checked"))
	{	 
		$($this).prop("checked",true); 
	  	$("#"+$table).find(".table_record_checkbox").each(function(){
	  		$(this).prop("checked",true);
	  		$(this).closest("tr").find("td").css("background-color","rgb(225, 235, 255)");
	  		$counter = $counter + 1;
	  	}); 

		$("#total_selected_number").html($counter);
	}
	else
	{  
		$($this).prop("checked",false);
	  	$("#"+$table).find(".table_record_checkbox").each(function(){
	  		$(this).prop("checked",false);
	  		$(this).closest("tr").find("td").css("background-color","");
	  	});

	  	$("#total_selected_number").html(0);
	}
} 
 

$(document).on('click', '.table_record_checkbox', function () {
    if($(this).is(":checked"))
	{	 
		$(this).closest("tr").find("td").css("background-color","rgb(225, 235, 255)");
	    $counter = $counter + 1;
		$("#total_selected_number").html($counter);
	}
	else
	{  
		$(this).closest("tr").find("td").css("background-color","");
	    $counter = $counter - 1;
		$("#total_selected_number").html($counter); 
	}
}); 

$(document).on('click', '.status', function () { 

	$table = $(this).attr("table");
	$id = $(this).attr("table_id");
	$status = "Change_Status";
	add_loader(); 
	$.post("change_status",{table:$table,ids:$id,status:$status},function(response){
		if(response)
		{
			swal(
			      'Successful!',
			       "Status is changed successfully",
			      'success'
				);
		}
		else
		{
			swal(
			      'Error!',
			       "Sorry! status is not changed.",
			      'error'
				);
			
		}
	})
	.done(function() { remove_loader(); })
	.fail(function(jqxhr, settings, ex) { console.log('failed, ' + ex);  remove_loader(); }); 
});


 


function perform_group_action($table,$this)
{
	$action = $($this).val();
	if($action == "Delete")
	{
		delete_record($table,'',$this);
	}
	else if($action == "Enable" || $action == "Disable" || $action == "Change_Status" )
	{
		change_status($table,$this);
	}
	else if($action == "Download" )
	{
		download_files($table,$this);
	}
	else if($action == "Export" )
	{
		generate_excel_file($table,$this);
	}
	else if($action == "Application_Status" )
	{
		$ids =   $('.table_record_checkbox:checked').map(function() 
			 	 {
				   return this.value;
				 }).get(); 

		if($ids != "" || $ids.length > 0)
		{
		 $("#change_application_status_from").css("display","block");
		 $('.select2').select2();
		 $(".table-layout").css("display","block");
		}
		else
		{
			swal("Error!", "Please select records first.", "error");
		}
	}
	else if($action == "Assign_Interviewer" )
	{
		$ids =   $('.table_record_checkbox:checked').map(function() 
			 	 {
				   return this.value;
				 }).get(); 

		if($ids != "" || $ids.length > 0)
		{
		 $("#assign_interviewers_form").css("display","block");
		 $('.select2').select2();
		 $(".table-layout").css("display","block");
		}
		else
		{
			swal("Error!", "Please select records first.", "error");
		}
	}
	else if($action == "Add_Note" )
	{
		$ids =   $('.table_record_checkbox:checked').map(function() 
			 	 {
				   return this.value;
				 }).get(); 

		if($ids != "" || $ids.length > 0)
		{
		 $("#add_note_form").css("display","block");
		 $('.select2').select2();
		 $(".table-layout").css("display","block");
		}
		else
		{
			swal("Error!", "Please select records first.", "error");
		}
	}
	else if($action == "Move_To_Employees" )
	{
		$ids =   $('.table_record_checkbox:checked').map(function() 
			 	 {
				   return this.value;
				 }).get(); 

		if($ids != "" || $ids.length > 0)
		{
		 $("#move_applicants_form").css("display","block");
		 $('.select2').select2();
		 $(".table-layout").css("display","block");
		}
		else
		{
			swal("Error!", "Please select records first.", "error");
		}
	}
	else if($action == "Send_Email" )
	{
		$ids =   $('.table_record_checkbox:checked').map(function() 
			 	 {
				   return this.value;
				 }).get(); 

		if($ids != "" || $ids.length > 0)
		{
		 $("#send_email_form").css("display","block");
		 $('.select2').select2();
		 $(".table-layout").css("display","block");
		}
		else
		{
			swal("Error!", "Please select records first.", "error");
		}
	}
}


function assign_interviewers($this)
{
	$ids =   $('.table_record_checkbox:checked').map(function() { return this.value; }).get();  
	if($ids != "" || $ids.length > 0)
	{ 
		add_loader();
		$interviewers = $("#Interviewers").val();
		$.post("admin/assign_interviewers",{'ids':$ids,'interviewers':$interviewers}, function(response)
		{
			if(response)
			{  
			  swal("Success!", "The interviewers are assigned successfully...", "success");
			  $("#assign_interviewers_form_id")[0].reset();
			  $("#assign_interviewers_form").css("display","none"); 
			  $(".table-layout").css("display","none");

			}
			else
			{
				swal("Error!", "Some error occured. Please try again.", "error");
			}

			remove_loader();
		});
	}
	else
	{
		swal("Error!", "Please select records first.", "error");
	}

	return false;
}

function add_note_for_selected_applicants($this)
{
	$ids =   $('.table_record_checkbox:checked').map(function() { return this.value; }).get();  
	if($ids != "" || $ids.length > 0)
	{ 
		add_loader();
		$note = $("#Note").val();
		$.post("admin/add_note_for_selected_applicants",{'ids':$ids,'note':$note}, function(response)
		{
			if(response)
			{  
			  swal("Success!", "The note is added successfully...", "success");
			  $("#add_note_form_id")[0].reset();
			  $("#add_note_form").css("display","none"); 
			  $(".table-layout").css("display","none");

			}
			else
			{
				swal("Error!", "Some error occured. Please try again.", "error");
			}

			remove_loader();
		});
	}
	else
	{
		swal("Error!", "Please select records first.", "error");
	}

	return false;
}

function move_applicants_into_employees($this)
{

	for ( instance in CKEDITOR.instances ){  CKEDITOR.instances[instance].updateElement(); }
	var formData = new FormData($this);

	$ids =   $('.table_record_checkbox:checked').map(function() 
		 	 {
			   return this.value;
			 }).get(); 
	
		formData.append('ids', $ids);
		$.ajax({
			    type:'POST',
			    url: $($this).attr("action"),
			    data: formData,  
			   	cache: false,
		        contentType: false,
		        processData: false,
			    beforeSend:function(){

			    	  add_loader();
			    },
			    xhr: function() 
			    { 
	                var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            $("#loading_progress").html(percentComplete + '%');  
                        }
                    }, false);

                    return xhr; 
                },
			    success:function(msg)
			    {    
		    	  remove_loader();
		    	 
		    	  swal("Success!", "All selected applicants are successfulyy moved into employees list.", "success");
				  $("#move_applicants_form_id")[0].reset();
				  $("#move_applicants_form").css("display","none"); 
				  $(".table-layout").css("display","none");
			    },
			    error:function(msg){
						
						remove_loader();
						swal("Error!", "Please select records first.", "error");
			    	 	 
			    },
			    complete:function(){
			    	
			    	 remove_loader()
			    	 $("#loading_progress").html("");
			    } 

			});

	return false; 
	
}

function send_email_to_selected($this)
{

	for ( instance in CKEDITOR.instances ){  CKEDITOR.instances[instance].updateElement(); }
	var formData = new FormData($this);

	$ids =   $('.table_record_checkbox:checked').map(function() 
		 	 {
			   return this.value;
			 }).get(); 
	
		formData.append('ids', $ids);
		$.ajax({
			    type:'POST',
			    url: $($this).attr("action"),
			    data: formData,  
			   	cache: false,
		        contentType: false,
		        processData: false,
			    beforeSend:function(){

			    	  add_loader();
			    },
			    xhr: function() 
			    { 
	                var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            $("#loading_progress").html(percentComplete + '%');  
                        }
                    }, false);

                    return xhr; 
                },
			    success:function(msg)
			    {    
		    	  remove_loader();
		    	 
		    	  swal("Success!", "Email has been sent to all selected records.", "success");
				  $("#Send_Email_Form")[0].reset();
				  $("#send_email_form").css("display","none"); 
				  $(".table-layout").css("display","none");
			    },
			    error:function(msg){
						
						remove_loader();
						swal("Error!", "Please select records first.", "error");
			    	 	 
			    },
			    complete:function(){
			    	
			    	 remove_loader()
			    	 $("#loading_progress").html("");
			    } 

			});

	return false; 
	
}


function generate_excel_file($table,$this)
{
	$ids =   $('.table_record_checkbox:checked').map(function() { return this.value; }).get();  
 	//console.log($ids);
	if($ids != "" || $ids.length > 0)
	{ 
		add_loader();
		$status = $($this).val();
		$.post("admin/generate_excel_file",{'ids':$ids,'table':$table}, function(response)
		{
			if(response)
			{
				var url = response; 
				window.open(url, '_blank');
				swal("Success!", "The selected records have been exported successfully", "success");
			}
			else
			{
				swal("Error!", "Some error occured. Please try again.", "error");
			}

			remove_loader();
		});
	}
	else
	{
		swal("Error!", "Please select records first.", "error");
	}
}

function delete_record($table,$id,$this)
{
	if($id == "")
	{
		$ids =   $('.table_record_checkbox:checked').map(function() 
			 	 {
				   return this.value;
				 }).get(); 
	}
	else
	{
		$ids = $.makeArray( $id ); 
	}

	if($ids != "" || $ids.length > 0)
	{
	 swal({
		    title: 'Are you sure?',
		    text: "You won't be able to revert this!",
		    type: 'warning',
		    showCancelButton: true,
		    confirmButtonColor: '#3085d6',
		    cancelButtonColor: '#d33',
		    confirmButtonText: 'Confirm!'
		}).then(function($action){
				 
				$action = $.makeArray($action);
				if($action[0].dismiss && $action[0].dismiss == "cancel")
				{ 
			    	swal("Cancelled", "The action is aborted successfully :)", "error");
				}

				if($action[0].value)
				{ 
					$("#loader").css("display","block");
					$.post("delete_record",{'ids':$ids,'table':$table}, function(response)
					{
						$.each($ids, function(i)
						{
							$("#row_"+$ids[i]).remove();  
						}); 
						
						swal("Deleted!", "The record is deleted successfully....", "success");
					})
					.done(function() { $("#loader").css("display","none"); })
					.fail(function(jqxhr, settings, ex) { console.log('failed, ' + ex); $("#loader").css("display","none"); });
				}
				
		});
	}
	else
	{
		swal("Error!", "Please select records first.", "error");
	}

}

function change_status($table,$this)
{
	 
	$ids =   $('.table_record_checkbox:checked').map(function() { return this.value; }).get();  
 	//console.log($ids);
	if($ids != "" || $ids.length > 0)
	{ 
		add_loader();
		$status = $($this).val();
		$.post("change_status",{'ids':$ids,'table':$table,'status':$status}, function(response)
		{
			$.each($ids, function(i)
			{
				if($status == "Change_Status")
				{
					if($("#row_"+$ids[i]).find(".status").is(":checked"))
					{
						$("#row_"+$ids[i]).find(".status").prop("checked",false);  
					}
					else
					{
						$("#row_"+$ids[i]).find(".status").prop("checked",true);  
					}
				}
				else if($status == "Enable")
				{
					$("#row_"+$ids[i]).find(".status").prop("checked",true);
				}
				else if($status == "Disable")
				{
					$("#row_"+$ids[i]).find(".status").prop("checked",false);
				}
				
				
			}); 
			
			swal("Changed!", " The status for the selected records is changed successfully...", "success");
		})  
		.done(function() { remove_loader(); })
		.fail(function(jqxhr, settings, ex) { console.log('failed, ' + ex); remove_loader(); }); 
	}
	else
	{
		swal("Error!", "Please select records first.", "error");
	}

}


function download_files($table,$this)
{
	 
	$ids =   $('.table_record_checkbox:checked').map(function() { return this.value; }).get();  
 
	if($ids != "" || $ids.length > 0)
	{ 
		add_loader();
		
		$.post("admin/download_files",{'ids':$ids,'table':$table}, function(response)
		{ 
			$("body").append("<a href='"+response+"'><div id='download_zip_file'></div></a>");
			$("#download_zip_file").trigger("click");
			swal("Successful", " Download is started...", "success");
		})  
		.done(function() { remove_loader(); })
		.fail(function(jqxhr, settings, ex) { console.log('failed, ' + ex); remove_loader(); }); 
	}
	else
	{
		swal("Error!", "Please select records first.", "error");
	}

}


function check_modules($this)
{
	if($($this).is(":checked"))
	{
		$($this).closest("li").find("input[type=checkbox]").each(function(){  $(this).prop("checked",true); });
	}
	else
	{
		$($this).closest("li").find("input[type=checkbox]").each(function(){  $(this).prop("checked",""); });
	}
}
 

function get_states($this,$target,$selected_id)
{
	$country_id = $($this).val();
	if($country_id != "")
	{ 
		$.post(" admin/get_states ",{'country_id':$country_id,'selected_id':$selected_id}, function(response)
		{
			 if(response)
			 {
			 	$("#"+$target).html(response);
			 }
		});
	}
}

function get_cities($this,$target,$selected_id)
{
	$state_id = $($this).val();
	if($state_id != "")
	{ 
		$.post(" admin/get_cities ",{'state_id':$state_id,'selected_id':$selected_id}, function(response)
		{
			 if(response)
			 {
			 	$("#"+$target).html(response);
			 }
		});
	}
}


function get_institute($this,$target)
{
	$country_code = $($this).val();
	if($country_code != "")
	{ 
		$.post(" admin/get_institute ",{'country_code':$country_code}, function(response)
		{
			 if(response)
			 {
			 	$("#"+$target).html(response);
			 }
		});
	}
}

    
function filter_files($this, $file_type)
{ 
  $(".files_filter").find("label").removeClass("active");
  $($this).addClass("active");


  $(".all_files").css("display",'none');
 
  if($file_type == "All")
  {
    $(".all_files").css("display","block");
  }
  else if($file_type == "image")
  {
    $("[file-type=image]").css("display","block");
  }
  else if($file_type == "video")
  {
    $("[file-type=video]").css("display","block"); 
  }
  else if($file_type == "audio")
  {
    $("[file-type=audio]").css("display","block");  
  }
  else if($file_type == "document")
  {
    $("[file-type=word]").css("display","block"); 
    $("[file-type=excel]").css("display","block"); 
    $("[file-type=pdf]").css("display","block"); 
    $("[file-type=zip]").css("display","block");  
  } 
}

function get_job_posts($this,$target)
{
	$val = $($this).val();
	if($val != "")
	{ 
		$.post(" admin/get_job_posts ",{'location':$val}, function(response)
		{
			 if(response)
			 {
			 	$("#"+$target).html(response);
			 }
		});
	}
}


$(document).on('click', '.pagination li a', function(){ 
	$url = $(this).attr("href");
	fetch_records($url); 
	return false;
});

 
function fetch_records($url) 
{ 
	$counter = 0;
	$("#total_selected_number").html($counter);

	$filter_data = $("#filter_form").serializeArray(); 
	$url_strings = $url.split("/");
	$page =  $url_strings[$url_strings.length-1];
	 
	$per_page = $("#Per_Page").val(); 
	add_loader();
	$.post($url,{filter_data:$filter_data,page:$page,per_page:$per_page},function(response)
	{ 
		if(response)
		{ 	
			$result = $.parseJSON(response); 
			$links = $result.links;
			$(".pagination").html($links);
			$(".table_records").html($result.records);
			$("#total_records").html($result.total_records);
			
		}
		else
		{
			swal(
			      'Error!',
			       "Sorry! No record found.",
			      'error'
				); 
		}
	})
	.done(function() { remove_loader(); })
	.fail(function(jqxhr, settings, ex) { console.log('failed, ' + ex); remove_loader(); }); 
}


function openmodal() {
  document.getElementById("mymodal").style.display = "block";
}

function closemodal() {
  document.getElementById("mymodal").style.display = "none";
}
