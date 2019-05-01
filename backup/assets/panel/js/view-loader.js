//var $path = $(location).attr("href");
var $path = "/account";
var $img_path = "/assets/";
	 
	function load_sidebar_view($this,$view,$data='')
	{  
		make_active($this);
		$("#view_loader").html(loader());
		$("#view_loader").load($path+"/load_view",{data:$data,view:$view});
	}

	function load_view($this,$view,$data='')
	{   
		$("#view_loader").html(loader());
		$("#view_loader").load($path+"/load_view",{data:$data,view:$view});
	}

	function load_tab($this,$view,$data='',$container,$edit_id='')
	{  
		make_tab_active($this)
		$("#"+$container).html(loader());
		$("#"+$container).load($path+"/load_view",{data:$data,edit_rec:$edit_id,view:$view});
	}
 	
 	function loader()
 	{
 		return '<div class="loader"><img src="'+$img_path+'images/loader/loader.gif" ></div>';
 	}

 	function make_active($this)
 	{
 		$("#sidebar-menu").find("li").removeClass("active");
 		$($this).addClass("active"); 
 	}

 	function make_tab_active($this)
 	{
 		$($this).parent("ul").find("li").removeClass("active");
 		$($this).addClass("active"); 
 	}

 	function open_modal_window($this,$view,$data='')
 	{
 		openmodal();
 		$("#modalbody").html(loader());
		$("#modalbody").load($path+"/load_view",{data:$data,view:$view});
 	}