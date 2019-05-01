 
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
		$form_data = $("#assign_interviewers_form_id").serializeArray();
		$interviewers = $("#Interviewers").val();
		$.post("admin/assign_interviewers",{'ids':$ids,'form_data':$form_data}, function(response)
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
		    	  console.log(msg);
		    	  swal("Success!", "Email has been sent to all selected records.", "success");
				  $("#Send_Applicant_Email_Form")[0].reset();
				  $("#send_email_form").css("display","none"); 
				  $(".table-layout").css("display","none");
			    },
			    error:function(msg){
						

						remove_loader();
						swal("Error!", "Some error occured "+msg, "error");
			    	 	 
			    },
			    complete:function(){
			    	
			    	 remove_loader()
			    	 $("#loading_progress").html("");
			    }  
			});

	return false;  
}

function save_week_work($this)
{
	if($("#Location_Id").val() > 0)
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
	else
	{
		swal("Error!", "Location field can not be empty", "error");
	}	
	 
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
	if($url != "#")
	{
		fetch_records($url); 
		return false;
	}
	
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

function delete_week_work_days($table,$id,$this)
{
	if($id  != "" )
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
					$.post("admin/delete_week_work_days",{'id':$id,'table':$table}, function(response)
					{
						$("#row_"+$id).remove();  
						swal("Deleted!", "The record is deleted successfully....", "success");
					})
					.done(function() { $("#loader").css("display","none"); })
					.fail(function(jqxhr, settings, ex) { console.log('failed, ' + ex); $("#loader").css("display","none"); });
				}
				
		});
	}
	else
	{
		swal("Error!", "No location is set for this record.", "error");
	} 
}

function employee_signout($attendance_id,$employee_id)
{
	if($attendance_id  != "" )
	{
		 swal({
			    title: 'Are you sure?',
			    text: "The employee will be logout!",
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
						$.post("admin/employee_signout",{'id':$attendance_id}, function(response)
						{ 
							$message = $.parseJSON(response);
							if($message['Status'])
							{
								$("#row_"+$employee_id).find(".panel").css("background-color","red !important");
								$("#employee_signout_"+$attendance_id).html($message['signout_time']);  
								 
								swal("Success!", "This employee has signed out successfully...", "success");
							}
							else
							{
								swal("Error!", "Some error occured. Please try again.", "error");
							}
							
						})
						.done(function() { $("#loader").css("display","none"); })
						.fail(function(jqxhr, settings, ex) { console.log('failed, ' + ex); $("#loader").css("display","none"); });
					}
					
			});
	}
	else
	{
		swal("Error!", "The employee didn't signed in yet", "error");
	}
} 

function get_employee_activities($this)
{  
	$validation = true;
	$($this).find(".required").each(function(e){
		if($(this).val() == "")
		{
			$validation = false;
		}  
	});

	if($validation)
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
		    	  remove_loader();
		    	  $("#employee_activities").html(msg);
		    	 // $($this).trigger("reset");
			    },
			    error:function(msg){
						
						remove_loader();
						swal("Error!", "Some error occured please try again", "error");
			    	 	 
			    },
			    complete:function(){
			    	
			    	 remove_loader()
			    	 $("#loading_progress").html("");
			    } 

			});
	}
	else
	{
		swal("Error!", "Validation error occured. Fill the required fields.", "error");
	}

	return false; 
} 

function get_attendance_history($this)
{  
	$validation = true;
	$($this).find(".required").each(function(e){
		if($(this).val() == "")
		{
			$validation = false;
		}  
	});

	if($validation)
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
		    	  remove_loader();
		    	  $("#attendance_history").html(msg);
		    	 // $($this).trigger("reset");
			    },
			    error:function(msg){
						
						remove_loader();
						swal("Error!", "Some error occured please try again", "error");
			    	 	 
			    },
			    complete:function(){
			    	
			    	 remove_loader()
			    	 $("#loading_progress").html("");
			    } 

			});
	}
	else
	{
		swal("Error!", "Validation error occured. Fill the required fields.", "error");
	}

	return false; 
}

function get_leaves_history($this)
{  
	$validation = true;
	$($this).find(".required").each(function(e){
		if($(this).val() == "")
		{
			$validation = false;
		}  
	});

	if($validation)
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
		    	  remove_loader();
		    	  $("#leaves_history").html(msg);
		    	 // $($this).trigger("reset");
			    },
			    error:function(msg){
						
						remove_loader();
						swal("Error!", "Some error occured please try again", "error");
			    	 	 
			    },
			    complete:function(){
			    	
			    	 remove_loader()
			    	 $("#loading_progress").html("");
			    } 

			});
	}
	else
	{
		swal("Error!", "Validation error occured. Fill the required fields.", "error");
	}

	return false; 
}

function reject_leave_application($rec_id)
{
	if($rec_id != "" && $rec_id > 0)
	{
		swal({
			    title: 'Are you sure?',
			    text: "Action can't be reverted!",
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
						$.post("admin/reject_leave_application",{'id':$rec_id}, function(response)
						{ 
							$("#row_"+$rec_id).remove(); 
							swal("Success!", "Application is rejected successfully", "success");
							
						})
						.done(function() { $("#loader").css("display","none"); })
						.fail(function(jqxhr, settings, ex) { console.log('failed, ' + ex); $("#loader").css("display","none"); });
					}
					
			});
	}
	else
	{
		swal("Error!", "Sorry! some error occured", "error");
	}
}

function approve_leave_application($rec_id,$emp_id) 
{
	if($rec_id != "" && $rec_id > 0 && $emp_id != "" && $emp_id > 0)
	{
	 
		add_loader();
		$.post("admin/approve_leave_application",{id: $rec_id,emp_id:$emp_id},function(response)
		{ 
			if(response)
			{ 	 
				$("#row_"+$rec_id).remove();
				swal(
				      'Success!',
				       "Done! Leave application is approved successfully.",
				      'success'
					);
			}
			else
			{
				swal(
				      'Error!',
				       "Sorry! Some error occured. Please try again.",
				      'error'
					); 
			}
		})
		.done(function() { remove_loader(); })
		.fail(function(jqxhr, settings, ex) { console.log('failed, ' + ex); remove_loader(); });
	} 
}

function change_leave_application_status($this,$rec_id,$emp_id,$status) 
{
	if($rec_id != "" && $rec_id > 0 && $emp_id != "" && $emp_id > 0 && $status != "")
	{
	 
		add_loader();
		$.post("admin/change_leave_application_status",{id: $rec_id,emp_id:$emp_id,status:$status},function(response)
		{ 
			if(response)
			{ 	 
				//$("#row_"+$rec_id).remove();
				swal(
				      'Success!',
				       "Done! Leave application is "+$status+" successfully.",
				      'success'
					);
			}
			else
			{
				swal(
				      'Error!',
				       "Sorry! Some error occured. Please try again.",
				      'error'
					); 
			}
		})
		.done(function() { remove_loader(); })
		.fail(function(jqxhr, settings, ex) { console.log('failed, ' + ex); remove_loader(); });
	} 
} 

function get_employee_leaves($this)
{
	$employee_id = $($this).val();
	if($employee_id > 0)
	{
		add_loader();
		$.post("admin/get_employee_leaves",{emp_id:$employee_id},function(response)
		{ 
			$leave_data = $.parseJSON(response);
			if($leave_data['Success'])
			{ 	 
				$("#Paid_Leaves").val($leave_data['Paid_Leaves']);	 	
				$("#Unpaid_Leaves").val($leave_data['Unpaid_Leaves']);	 	
				$("#Consumed_Paid_Leaves").val($leave_data['Consumed_Paid_Leaves']);	 	
				$("#Consumed_Unpaid_Leaves").val($leave_data['Consumed_Unpaid_Leaves']);	
				if($leave_data['Edit_Recorde'])
				{
					$("#Edit_Recorde").val($leave_data['Edit_Recorde']);
				} 	
			}
			 
		})
		.done(function() { remove_loader(); })
		.fail(function(jqxhr, settings, ex) { console.log('failed, ' + ex); remove_loader(); });
	}
}

function rotate_shifts($this)
{
	if($("#Shift_A").val() > 0 && $("#Shift_A").val() > 0)
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
	else
	{
		swal("Error!", "Please select shifts from dropdown", "error");
	}	
	 
	return false;
}

function change_annual_leave_quota_setting($this)
{
	$add_remaining_leaves_into_next_year = "No";

	if($($this).is(":checked")) { $add_remaining_leaves_into_next_year = "Yes"; }
	add_loader();
	$.post("admin/change_annual_leave_quota_setting",{add_leave_status:$add_remaining_leaves_into_next_year},function(response)
	{  
		if(response)
		{ 	 
			 swal("Success","Settings are saved successfully...","success");
		}
		 
	})
	.done(function() { remove_loader(); })
	.fail(function(jqxhr, settings, ex) { console.log('failed, ' + ex); remove_loader(); swal("Error","Some error occured. Please try again later","error"); });
 
}

function save_report_points($this)
{
	$edit_rec_id = $("input[name='Edit_Recorde']",$this).val(); 
	if($edit_rec_id != "" && $edit_rec_id > 0)
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

				    	  //add_loader();
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
				    	 //remove_loader()
				    	 
				    	 if($message['Success'])
				    	 {  
			    	 		$("#row_"+$edit_rec_id).remove(); 
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

				    		swal(
								      'Error!',
								       msg,
								      'error'
								 );
				    },
				    complete:function(){
				    	
				    	 //remove_loader()
				    	 $("#loading_progress").html("");
				    } 

				});

	}
	else
	{
		swal("Error!", "Report is not selected", "error");
	}	
	 
	return false;
}

function employee_daily_reports($this)
{
	$employee_id = $("#Employee_Id").val(); 
	if($employee_id != "" )
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
				    	remove_loader()
				    	$("#employee_daily_reports").html(msg);
				    },
				    error:function(msg){
				    		remove_loader()
				    		swal(
								      'Error!',
								       msg,
								      'error'
								 );
				    },
				    complete:function(){
				    	
				    	 remove_loader()
				    	 $("#loading_progress").html("");
				    } 

				});

	}
	else
	{
		swal("Error!", "Employee is not selected", "error");
	}	
	 
	return false;
}

function delete_shift_settings($id,$this)
{ 

	if($id != "" )
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
					$.post("admin/delete_shift_settings",{'id':$id}, function(response)
					{
						$("#row_"+$id).remove();  
						  
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
 
function save_interview_question($this,$interview_id,$interviewer,$question)
{

	if($interview_id > 0, $interviewer > 0, $question > 0  )
	{	
		if($($this).attr("type") == "checkbox"){
			$q_answers = ""; 
			$q_ans =   $('#Question_'+$question+':checked').map(function() 
		 	 {
			   return this.value;
			 }).get(); 
			 
		}
		else
		{
			$q_ans = $($this).val(); 
		}
		
		$.ajax({
				    type:'POST',
				    url: "admin/save_interview_question",
				    data: {'Interview_Id': $interview_id, 'Interviewer': $interviewer, 'Question_Id':$question, 'Answer':$q_ans},   
				    beforeSend:function(){
				    	$(".save_update_"+$question).html('<h5>Action is saving...<span id="save_progress_'+$question+'"></span></h5>');
				    	  
				    },
				    xhr: function() 
				    { 
		                var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                percentComplete = parseInt(percentComplete * 100);
                                $("#save_progress_"+$question).html(percentComplete + '%');  
                            }
                        }, false);

                        return xhr; 
                    },
				    success:function(msg)
				    {  	  
				    	$(".save_update_"+$question).html('<h5 id="progress_'+$question+'">Saved successfully</h5>'); 
				    	setTimeout(function(){  $("#progress_"+$question).fadeOut(); }, 3000);
				    },
				    error:function(msg){
				    		 
				    		swal(
								      'Error!',
								       msg,
								      'error'
								 );
				    },
				    complete:function(){
				    	
				    	 
				    	 $("#loading_progress").html("");
				    } 

				});

	}
	else
	{
		swal("Error!", "Employee is not selected", "error");
	}	
	 
	return false;
} 

function save_interviewer_opinions($this,$interview_id,$interviewer,$update_key)
{

	if($interview_id > 0, $interviewer > 0 )
	{
		$reviwes = $("#Reviews_"+$interviewer).val(); 
		$points = $("#Points_"+$interviewer).val(); 
		$.ajax({
				    type:'POST',
				    url: "admin/save_interviewer_opinions",
				    data: {'Interview_Id': $interview_id, 'Interviewer': $interviewer, 'Reviews':$reviwes, 'Points':$points},   
				    beforeSend:function(){ 
				    	$(".save_update_interviewer_reviews_"+$update_key).html('<h5>Action is saving...<span id="save_progress__interviewer'+$update_key+'"></span></h5>');
				    	  
				    },
				    xhr: function() 
				    { 
		                var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                percentComplete = parseInt(percentComplete * 100);
                                $("#save_progress__interviewer"+$update_key).html(percentComplete + '%'); 
                            }
                        }, false);

                        return xhr; 
                    },
				    success:function(msg)
				    {  	  
				    	$(".save_update_interviewer_reviews_"+$update_key).html('<h5 id="progress_interviewer_sec_'+$update_key+'">Saved successfully</h5>');  
				    	setTimeout(function(){   
				    		$("#progress_interviewer_sec_"+$update_key).fadeOut(); 
				        }, 3000);

				    },
				    error:function(msg){
				    		 
				    		swal(
								      'Error!',
								       msg,
								      'error'
								 );
				    },
				    complete:function(){
				    	 
				    } 

				});

	}
	else
	{
		swal("Error!", "Employee is not selected", "error");
	}	
	 
	return false;
} 
 
function write_speaking($target,error)
{
	$mode = $("#speaking_mode").val();
	
	if($mode == "yes")
	{ 
		
	    if('webkitSpeechRecognition' in window)
	    {
	      var speechRecognizer = new webkitSpeechRecognition();
	      speechRecognizer.continuous = true;
	      speechRecognizer.interimResults = true;
	      speechRecognizer.lang = 'en-IN';
	      speechRecognizer.start();

	      var finalTranscripts = '';
	      speechRecognizer.onresult = function(){
	        var interimTranscripts = '';
	        for(var i=event.resultIndex; i < event.results.length; i++)
	        {
	          var transcript = event.results[i][0].transcript;
	          console.log(transcript);
	          transcript.replace("\n","<br>");
	          if(event.results[i].isFinal)
	          {
	            finalTranscripts= transcript;
	          }
	          else
	          {
	            interimTranscripts = transcript;
	          }
	        }


	        $($target).val( finalTranscripts +" "+ interimTranscripts) ;
	        console.log(finalTranscripts +" "+ interimTranscripts);
	      }

	      speechRecognizer.onerror = function(){

	      }
	    }
	    else
	    {
	    	console.log("error");
	      $(".error").html( "Browser is not supported" );
	    }
	}
}

function change_mode($this,$id)
{ 
	if($($this).val() == "on")
	{
	  $("#speaking_mode").val("yes"); 
	  $("#Reviews_"+$id).trigger("click").focus();
	  $result_id = "#Reviews_"+$id;
	  write_speaking($result_id,'error');
	}
	else
	{
	  $("#speaking_mode").val("no");
	}
}

function get_department_designations($this,$target)
{
	$dep_id = $($this).val();
	if($dep_id != "")
	{ 
		$.post(" admin/get_department_designations ",{'id':$dep_id}, function(response)
		{
			 if(response)
			 {
			 	$("#"+$target).html(response);
			 }
		});
	}
}
 