

function checkEmail(email){
	//allowed email regex
	var exp = /^[A-Za-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[A-Za-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum)\b$/;
    if(exp.test(email)==false){
    	$("#error_mail").text("Please enter a valid email").show();
        return false;
    }else{
     	return true;
    }
}



function checkPassword(password, passwordre){
	if (password!=passwordre){
    	$("#error_repass").text("Please retype your passwords (they don't match)").show();
    	return false;
    }else{
    	//allowed characters for password
    	var exp = /^[a-zA-Z0-9_-]{6,18}$/;
      	if(exp.test(password)==false){
        	$("#error_pass").text('Password should be 6-18 characters. Letters, numbers, underscores and hyphens allowed.').show();
        	return false;
        }else{
        	return true;
        }
    }       
}
      


function checkName(first_name,last_name){
	
	//allowed characters
	var exp = /^([a-zA-Z0-9_-][ ]{0,1}){1,20}$/;
	
    if (first_name.length>20){
    	$("#error_firstname").text("Maximum 20 characters please.").show();
    }
    if (last_name.length>20){
    	$("#error_lastname").text("Maximum 20 characters please.").show();
    }
    
    if(last_name.length>20 || first_name.length>20){
    	return false;
    }
    
    if(exp.test(first_name)==false){
    	$("#error_firstname").text('Letters, numbers, underscores, hyphens and space(no consecutive spaces) allowed.').show();
    }
    
    if(exp.test(last_name)==false){
    	$("#error_lastname").text('Letters, numbers, underscores, hyphens and space(no consecutive spaces) allowed.').show();
    }
    if(exp.test(first_name)==false || exp.test(last_name)==false){
    	return false;
    }else{
     	return true;
    }
}
      
  

$("#register").live("click",function(){
	$(".error_signup").hide();
    checkEmail($("#username").val());
    checkPassword($("#password").val(),$("#passwordre").val());
    checkName($("#firstname").val(),$("#lastname").val());
    
    if (checkEmail($("#username").val()) && checkPassword($("#password").val(),$("#passwordre").val()) && checkName($("#firstname").val(),$("#lastname").val())){
    	register();       	
   	}
});
  
//insecure login
function register(){
  	$.ajax({
    	type: "POST",
       	url: "db-interaction/users.php",
       	data: "action=account_create&email="+$("#username").val()+
       	"&password="+$("#password").val()+
       	"&firstname="+$("#firstname").val()+
       	"&lastname="+$("#lastname").val(),
       	success: function(r){
       	if(r==1)
       		$("#error_mail").text("Sorry, that email is already in use.").show();
       	else if(r==0)
       		window.location = "/signup.php?status=completed"; 
       	
   		},
     	error:function(){}  
  });
}
