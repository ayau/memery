(function () {
	var input = document.getElementById("images"), 
		formdata = false;

	function showUploadedItem (source, file_name) {
		
		id = file_name.replace(".",""); 
		
		$("#preview_list").append("<li id="+id+" src="+file_name+" class='preview_panel'>"
		+"<i style='font-size:20px; display:block'>Click and drag on the image to select an area.</i>"
		+"<img class='preview_main' style='float:left' />"
		+"<div class='preview_settings'>"
			+"<div style='overflow:auto'>"
				+"<div class='thumbnail_preview'><img class='preview_mini' style='position: relative;' /></div>"
				+"<p class='thumbnail_preview_text'>The 100 x 100 thumbnail for this meme</p>"
			+"</div>"
			+"<div style='margin-top:15px'>"
				+"<input class='meme_name' type='text' size='30' placeholder='Name of Meme' value=''/>"
				+"<div style='margin-top:15px;'>"
					+"<p style='display:inline'>Privacy:  </p>"
					+"<select style='height:30px; font-size:16px'>"
  						+"<option value='public'>Public</option>"
  						+"<option value='private'>Private</option>"
  						+"<option value='grouponly'>Group only</option>"
					+"</select>"
        		+"</div>"
        		+"<div style='margin-top:15px;'>"
        			+"<input type='text' size='30' placeholder='Group tags (Optional)' value=''/>"
        		+"</div>"
			+"<div>"
		+"</div>"
	+"</li>")
	
	main = $("#"+id).find(".preview_main");
	main.attr("src", source);
	mini = $("#"+id).find(".preview_mini");
	mini.attr("src", source);
		
	main.imgAreaSelect({ x1:0, y1:0, x2: 100, y2: 100, aspectRatio: '1:1', handles: "corners", onSelectChange: preview });
	append_image_size(main.attr('src'), main);
	
	$("#upload_save").show();
	}   

	if (window.FormData) {
  		formdata = new FormData();
  		document.getElementById("btn").style.display = "none";
	}
	
 	input.addEventListener("change", function (evt) {
 		document.getElementById("response").innerHTML = "<h2>Uploading . . .</h2>"
 		var i = 0, len = this.files.length, img, reader, file;
	
		for ( ; i < len; i++ ) {
			//provides closure for the attribute file.name
			(function(file) {	
			if (!!file.type.match(/image.*/)) {
				if ( window.FileReader ) {
					reader = new FileReader();
					reader.onloadend = function (e) {
						showUploadedItem(e.target.result, file.name);
					};
					reader.readAsDataURL(file);
				}
				if (formdata) {
					formdata.append("images[]", file);
				}
			}
			})(this.files[i]);	
		}
	
		if (formdata) {
			var xhr = new XMLHttpRequest();
			xhr.onreadystatechange = function() {
  				if (xhr.readyState==4 && xhr.status==200){
    				document.getElementById("response").innerHTML =xhr.responseText;
    			}
  			}
			xhr.open("post", "/upload.php", true);
			xhr.send(formdata);
		}
	}, false);
	
	
	//Callback for preview plugin to allow for preview
	function preview(img, selection) {
    		var scaleX = 100 / (selection.width || 1);
    		var scaleY = 100 / (selection.height || 1);
  
    		$(img).parent().find(".thumbnail_preview img").css({
        		width: Math.round(scaleX * img.width) + 'px',
        		height: Math.round(scaleY * img.height) + 'px',
        		marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
        		marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
    		});
    }
	
    //When save button is clicked after upload
    $("#upload_save").live("click",function(){
    	$(".preview_panel").each(function(){
    		file_name = $(this).attr("src");
    		
    		main = $(this).find(".preview_main");
    		raw_w = main.attr('raw_width');
    		raw_h = main.attr('raw_height');
    		
    		mini = $(this).find(".preview_mini");
    		mini_h = parseInt(mini.css('height'));
    		mini_w = parseInt(mini.css('width'));
    		m_L = parseInt(mini.css('margin-left'));
    		m_T = parseInt(mini.css('margin-top'));
    		
    		x0 = -1*raw_w/mini_w * m_L;
    		y0 = -1*raw_h/mini_h * m_T;
    		x1 = x0 + raw_w/mini_w * 100;
    		y1 = y0 + raw_h/mini_h * 100;
    		
    		name = $(this).find(".meme_name").val();
    		file_name = "uploads/"+file_name; //change later when we change image host!!!!
    		
    		$.ajax({
		    	type: "POST",
		       	url: "db-interaction/templates.php",
		       	data: "action=update_template_info&name="+name+
		       	"&file_name="+file_name+
		       	"&x0="+x0+
		       	"&y0="+y0+
		       	"&x1="+x1+
		       	"&y1="+y1,
		       	success: function(){
		       		window.location = "/user.php";
		   		},
		     	error:function(){}  
  			});
    	})
    });
    
    //Get the original size of an image
    function append_image_size(src, main) {
    	var newImg = new Image();
    	newImg.onload = function() {
    		var height = newImg.height;
    		var width = newImg.width;
    		main.attr("raw_width",width);
    		main.attr("raw_height",height);
    	}

    	newImg.src = src; // this must be done AFTER setting onload

	}
}());
