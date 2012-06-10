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
		<input type="text" name="q" id="query" placeholder='Tag groups, users or keywords'/>
		<ul id='group_tags' class='tags_container'></ul>
		<ul id='keyword_tags' class='tags_container'></ul>
		<center><div id='generator_submit' class='btn' type="button" >submit</div></center>
	</div>
	
<?php else:

	echo "<center><h2>This meme does not exist.</h2></center>";
	endif;
endif; ?>
	
<?php
	include_once "common/footer.php";
	
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

?>
	<link rel="stylesheet" href="plugins/autocomplete/styles.css" type="text/css" />
	<script type="text/javascript" src="plugins/autocomplete/jquery.autocomplete.js"></script>
	<script type="text/javascript">
	
		var options, a;
		jQuery(function(){
  			options = { 
  				serviceUrl:'service/autocomplete.php',
  				minChars:2,
  				deferRequestBy: 0,
  				onSelect: function(value, data){ add_tag(value, data)},
  				lookup: <?php echo json_encode($local);?>
		 };
  			a = $('#query').autocomplete(options);
		});
		
		var tags = [];
		function add_tag(value, data){
			included = false;
			for(i = 0; i<tags.length; i++){
				t = tags[i];
				if( t[0] === data[0] && t[1] === data[1]){
					included = true;
					break;
				}
			}
			if(!included){
				if(data[1]=== 'group'){
					if($("#group_tags li").length==0)
						$("#group_tags").append("<li><h3>Groups</h3></li>");
					$("#group_tags").append("<li type="+data[1]+" class='tags' tag_id="+data[0]+">"+value +"</li>");					
				}else if(data[1] === 'keyword'){
					if($("#keyword_tags li").length==0)
						$("#keyword_tags").append("<li><h3>Keywords</h3></li>");
					$("#keyword_tags").append("<li type="+data[1]+" class='tags' tag_id="+data[0]+">"+value +"</li>");
				}
				tag = [];
				tag[0] = data[0];	//id
				tag[1] = data[1];	//type, group or user
				tag[2] = data[2];	//privacy
				tags.push(tag);
			}
		}
		
		//if enter is pressed, add tags
    	$('#query').keypress(function(e){
    		if (e.which == 13)
    			add_tag($(this).val(), [$(this).val(), 'keyword', 0]);
		});
	
		$("#generator_preview").live("click",function(){
			$("#generator_panel").css("min-height", function(){ return $(this).height()});
			src = $("#generator_panel img").attr("source");
			temp_id = $("#generator_panel img").attr("temp_id");
			$("#generator_panel").empty();
			$("#generator_panel").append("<img src='meme_creator.php?meme="+src+"&top_text="+$("#toptext").val()+"&bottom_text="+$("#bottomtext").val()+"' temp_id='"+temp_id+"' source='"+src+"' alt=\"I don't always fail to display this meme. But when I do, I display this text instead.\" />");
		})
		
		$("#generator_submit").live("click",function(){
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
		       	"&text_bottom="+text_bottom,
		       	success: function(){
		       		window.location = "/user.php";
		   		},
		     	error:function(){}  
  			});
		})
	
	</script>