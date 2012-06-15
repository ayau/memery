<?php
	include_once "common/base.php"; 
	$pageTitle = "meme generator";
	include_once "common/header.php";
	include_once "inc/class.template.inc.php";
	$template = new Template();
?>
<div id="container">
	<br /><br /><br /><br />
	
<?php
	if (!$_SESSION['logged']):
		echo "<center><h2>Sorry, you have to log in to create memes</h2></center>";
	else:
	if(isset($_GET['id'])):
		$t = $template -> get_template_by_id($_GET['id']);
		if($t!=null):
		echo "<div id='generator_panel'><img src='".$t['src']."' temp_id='".$t['id']."' source='".$t['src']."'alt=\"I don't always fail to display this meme. But when I do, I display this text instead.\"/></div>";
?>	
	<div id='generator_settings'>
		<textarea id='toptext' type="text" row='4' placeholder='Top Text'>Tried to make the generator respond to input</textarea>
		<textarea id='bottomtext' type="text" row='4' placeholder="Bottom Text">Success</textarea>
		<center><div id='generator_preview' class='btn' type="button" >preview</div></center>
		<input id='title' type='text' placeholder='Title'/>
		<div style='margin:15px 0px;'>
  			<input type='radio' name='privacy' id='public' value='0' checked/>
  			<label for='public'>Public</label>
  			<br />
  			<input type='radio' name='privacy' id='private' value='1'/>
  			<label for='private'>Private <p class='hint'>(tagged groups only)</p></label>
  			<br />
  			<input type='radio' name='privacy' id='hidden' value='2'/>
  			<label for='hidden'>Hidden <p class='hint'>(available only through url)</p></label>
        </div>
        <div id='tag_settings'>
			<input type="text" name="q" id="query" placeholder='Tag groups, users or keywords'/>
			<ul id='group_tags' class='tags_container'></ul>
			<ul id='keyword_tags' class='tags_container'></ul>
		</div>
		<center><div id='generator_submit' class='btn' type="button" >submit</div></center>
	</div>
	
<?php else:

	echo "<center><h2>This meme does not exist.</h2></center>";
	endif;
endif; ?>
	
<?php
	
	include_once "inc/class.group.inc.php";
	$group = new Group();
	$groups = $group -> get_groups($_SESSION['uid']);
	$group_suggestions = array();
	$group_data = array();
	for ($i = 0; $i < count($groups); $i ++){
		$g = $groups[$i];
		array_push($group_suggestions, $g['groupname']);
		array_push($group_data, array($g['id'], 'group', $g['privacy']));
	}
	$local = array('suggestions'=>$group_suggestions, 'data' =>$group_data);
	
	endif;
	
	include_once "common/footer.php";
	
?>
	<link rel="stylesheet" href="plugins/autocomplete/styles.css" type="text/css" />
	<script type="text/javascript" src="plugins/autocomplete/jquery.autocomplete.js"></script>
	<script type="text/javascript">
		
		$('input[type="radio"]').click(function(){	
			if($(this).attr("id") === "hidden"){
				$("#tag_settings").slideUp();
			}else{
				$("#tag_settings").slideDown();
			}
		})	
	
		
		var group_tags = [];
		var keyword_tags = [];
		var options, a;
		
		jQuery(function(){
  			options = { 
  				serviceUrl:'service/autocomplete.php',
  				minChars:2,
  				deferRequestBy: 0,
  				onSelect: function(value, data){ add_group_tag(value, data)},
  				lookup: <?php echo json_encode($local);?>
		 };
  			a = $('#query').autocomplete(options);
		});
		
		
		function add_group_tag(value, data){
			included = false;
			for(i = 0; i<group_tags.length; i++){
				t = group_tags[i];
				if( t[0] === data[0] && t[1] === data[1]){
					included = true;
					break;
				}
			}
			if(!included){
				if($("#group_tags li").length==0)
					$("#group_tags").append("<li><h3>Groups</h3></li>");
				$("#group_tags").append("<li type='"+data[1]+"' class='tags hover_remove' tag_id='"+data[0]+"'>"+value +"</li>");				
				tag = [];
				tag[0] = data[0];	//id
				tag[1] = data[1];	//type, group or keyword
				tag[2] = data[2];	//privacy
				group_tags.push(tag);
				$("#query").val("");
			}
		}
		
		function add_keyword_tag(value, data){
			included = false;
			for(i = 0; i<keyword_tags.length; i++){
				t = keyword_tags[i];
				if( t[0] === data[0] && t[1] === data[1]){
					included = true;
					break;
				}
			}
			if(!included){
				if($("#keyword_tags li").length==0)
						$("#keyword_tags").append("<li><h3>Tags</h3></li>");
					$("#keyword_tags").append("<li type='"+data[1]+"' class='tags hover_remove' tag_id='"+data[0]+"'>"+"#"+value +"</li>");			
				tag = [];
				tag[0] = data[0];	//value of keyword
				tag[1] = data[1];	//type, group or keyword
				keyword_tags.push(tag);
				$("#query").val("");
			}
		}
		
		
		//Remove tags when tags are clicked on
		$(".tags").live("click",function(){
			if($(this).attr("type")==='group'){
				for(i = 0; i < group_tags.length; i++){
					t = group_tags[i];
					if( t[0] === $(this).attr("tag_id") && t[1] === $(this).attr("type")){
						$(this).remove();
						if($("#group_tags li").length==1)
							$("#group_tags").empty();
						group_tags.splice(i, 1);
						break;
					}
				}
			}else{
				for(i = 0; i < keyword_tags.length; i++){
					t = keyword_tags[i];
					if( t[0] === $(this).attr("tag_id") && t[1] === $(this).attr("type")){
						$(this).remove();
						if($("#keyword_tags li").length==1)
							$("#keyword_tags").empty();
						keyword_tags.splice(i, 1);
						break;
					}
				}
			}
		})
		
		//if enter is pressed, add tags
    	$('#query').keypress(function(e){
    		if (e.which == 13){
    			//removing random symbols and spaces, making hashtag into one word
    			keyword = $(this).val().toLowerCase().replace(/[^a-z0-9]/g, '');
    			add_keyword_tag(keyword, [keyword, 'keyword']);  			
			}
		});
		
		//Generator preview when "Preview" is clicked
		$("#generator_preview").live("click",function(){
			$("#generator_panel").css("min-height", function(){ return $(this).height()});
			src = $("#generator_panel img").attr("source");
			temp_id = $("#generator_panel img").attr("temp_id");
			$("#generator_panel").empty();
			$("#generator_panel").append("<img src='meme_creator.php?meme="+src+"&top_text="+$("#toptext").val()+"&bottom_text="+$("#bottomtext").val()+"' temp_id='"+temp_id+"' source='"+src+"' alt=\"I don't always fail to display this meme. But when I do, I display this text instead.\" />");
		})
		
		//Submits ajax to database when "Submit" is clicked
		$("#generator_submit").live("click",function(){
			
			if(check_privacy()){
			
				text_top = $("#toptext").val();
				text_bottom = $("#bottomtext").val();
				temp_id = $("#generator_panel img").attr("temp_id");
				title = $("#title").val();
				
				$.ajax({
			    	type: "POST",
			       	url: "db-interaction/memes.php",
			       	data: "action=insert_meme&title="+title+
			       	"&temp_id="+temp_id+
			       	"&text_top="+text_top+
			       	"&text_bottom="+text_bottom+
			       	"&privacy="+$("input[type='radio']:checked").attr("value"),
			       	success: function(r){
			       		meme_privacy = $("input[type='radio']:checked").attr("value");
			    		if(meme_privacy == 2)		//don't add tags if meme is secret.
			    			window.location = "/user.php";
			    		else
			       			submit_group_tags(r);
			   		},
			     	error:function(){}  
	  			});
  			}
		})
		
		//submit group tags to database. Called when submit button is pressed.
		function submit_group_tags(meme_id){
			group_ids = [];
			//collecting all group ids
			for(i = 0; i < group_tags.length; i++){
				group_ids.push(group_tags[i][0]);
			}
			
			$.ajax({
		    	type: "POST",
		       	url: "db-interaction/memes.php",
		       	data: "action=insert_group_tags&meme_id="+meme_id+
			    "&groups[]="+group_ids,
			    success: function(){
			    	submit_keyword_tags(meme_id);
		       		//window.location = "/user.php";
		   		},
		     	error:function(){}  
			});
		}
		
		//submit keyword tags to database. Called after group tags are submitted.
		function submit_keyword_tags(meme_id){
			keywords = [];
			//collecting all group ids
			for(i = 0; i < keyword_tags.length; i++){
				keywords.push(keyword_tags[i][0]);		//should sanitize inputs first
			}
			
			$.ajax({
		    	type: "POST",
		       	url: "db-interaction/memes.php",
		       	data: "action=insert_keyword_tags&meme_id="+meme_id+
			    "&keywords[]="+keywords,
			    success: function(){
		       		window.location = "/user.php";
		   		},
		     	error:function(){}  
			});
		}
		
		//Checks the meme privacy matches the group privacy
		function check_privacy(){
			meme_privacy = $("input[type='radio']:checked").attr("value");
			if(meme_privacy == 1){
				if(group_tags.length===0){
					alert("A private meme must belong to one or more groups");	//we can make it belong to the user?
					return false;
				}else{
					publics = [];
					for(i = 0; i < group_tags.length; i++){
						t = group_tags[i];
						if(t[2]==0)
							publics.push($(".tags[type='group'][tag_id='"+t[0]+"']").text());							
					}
					if(publics.length>0){
						var answer = confirm ("Warning: This meme is marked as private but you included one or more public groups ("+publics.join(", ")+"). Proceed?");
						if (answer)
							return true;
						else
							return false;
					}
				}
			}
			return true;
		}
	
	</script>