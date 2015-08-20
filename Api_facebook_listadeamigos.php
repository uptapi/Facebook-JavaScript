<?php

$sApplicationId = '1624563567785623';
$sApplicationSecret = 'b3560a3b85b2c362e5f4b3530e6fcf13';
$iLimit = 99;

?>
<!DOCTYPE html>
<html lang="en" xmlns:fb="https://www.facebook.com/2008/fbml">
    <head>
        <meta charset="utf-8" />
        <title>Facebook API -  Lista de amigos</title>
        <link href="css/main.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
       <center>
            <h1>Inicia Sesion:</h1>
            <div id="user-info"></div>
            <button id="fb-auth">Iniciar Sesion</button>
        </center>

        <div id="result_friends"></div>
        <div id="botones">
         </div>
        <div id="fb-root"></div>

        <script>
        function sortMethod(a, b) {
            var x = a.name.toLowerCase();
            var y = b.name.toLowerCase();
            return ((x < y) ? -1 : ((x > y) ? 1 : 0));
        }

        window.fbAsyncInit = function() {
            FB.init({ appId: '<?= $sApplicationId ?>', 
                status: true, 
                cookie: true,
                xfbml: true,
                oauth: true
            });

            function updateButton(response) {
                var button = document.getElementById('fb-auth');

                if (response.authResponse) { // in case if we are logged in
                    var userInfo = document.getElementById('user-info');
                    FB.api('/me', function(response) {
                        userInfo.innerHTML = '<img src="https://graph.facebook.com/' + response.id + '/picture">' + response.name;
                        button.innerHTML = 'Logout';
                    					});
					
					// get friends
                    FB.api('/me/friends', function(response) {
                        var result_holder = document.getElementById('result_friends');
                        var friend_data = response.data.sort(sortMethod);

                        var results = '';
						
						var numAmigos='';
						numAmigos=friend_data.length;
					     var selector;	
						 document.getElementById("select").disabled.true;
						 document.getElementById("select").options[0]= new Option("Selecciona un amigo","0");
                        for (var i = 1; i <= friend_data.length; i++) {
							document.getElementById("select").options[i]= new Option(friend_data[i].name,friend_data[i].id);							
							}
							  
                        });	
						
						 
					//aqui
					     var botones=document.getElementById("botones");
						 botones.innerHTML='<br><center><select id="select" name="seleccion"></select></center>'+
						 					'<center><label>Número de Post:</label><input required="required" id="numPost" type="text" name="numPost" 	 											/></center><br>'+
                                           '<center><button onClick="mostrarPostPersona()">Mostrar posts</button></center><br>';
										   
						 
			            button.onclick = function() {
                        FB.logout(function(response) {
                            window.location.reload();
                        });
                    };
                } else { // otherwise - dispay login button
                    button.onclick = function() {
                        FB.login(function(response) {
                            if (response.authResponse) {
                                window.location.reload();
                            }
                        }, {scope:'email,user_likes,user_friends,user_photos,user_posts,publish_actions'});
                    }
                }
            }

            // run once with current status and whenever the status changes
            FB.getLoginStatus(updateButton);
            FB.Event.subscribe('auth.statusChange', updateButton);    
        };
            
        (function() {
            var e = document.createElement('script'); e.async = true;
            e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
            document.getElementById('fb-root').appendChild(e);
        }());
		
		function obtenerLikes(id){
                   var cad="/"+id+"/likes";
				   var numLikes=0;
				   
				   FB.api(cad,function(response){
					var doc=document.getElementById("posts");   
					   numLikes=response.data.length;
					   doc.innerHTML+="Likes:"+numLikes+"<br>";
					   
					   });
					   
					   
			}//Final obtenerLikes(id)
			
	function obtenerLink(id){
		var cad="/"+id;
		FB.api(cad, {"fields":"picture"},function(response){
			var links="";
			var doc=document.getElementById("posts");   
			if(response.picture){
		    links=response.picture;
			
			//alert("Link:"+links);
			var dato='<img src="'+links+'" height="200px" width="200px">';
			doc.innerHTML+="Imagen:"+dato+"<br>";
				}
			
			});
		
		}
		
		function obtenerComentarios(id){
			var cad="/"+id+"/comments";
			
			FB.api(cad,function(response){
				var datos="<p>";
				//alert("Num comentarios:"+response.data.length);
				for(var i=0;i<response.data.length;i++){
					
					datos=datos+"<br>"+response.data[i].message+", De:"+response.data[i].from.name;
					}
					datos=datos+"</p>";
              var doc=document.getElementById("posts");   
			  doc.innerHTML+=datos;
				});
		}//Final obtenerComentarios(id)		
		
		function obtenerDatos(id){
			var cad="/"+id;
			FB.api(cad, function(response){
				var datos="";
				if(response.message){
				
					if(response.story){
						datos="Mensaje:"+response.message+"<br>"+"Historia:"+response.story+"<br>";	
						
						}else{
				        
						datos="Mensaje:"+response.message+"<br>"+"Historia: <br>";
						
							}
				}else{
					datos="Mensaje: <br>"+"Historia:"+response.story+"<br>";	
					
					}
					datos=datos+"Fecha:"+response.created_time+"<br>";
					
			   var doc=document.getElementById("posts");
			   //alert("Datos:"+datos);
			   
              
			   doc.innerHTML+=datos;
			   
			   
			   
			   
			   
			   
			   });
			}		
        function obtenerInfoPost(id){
			
            var doc=document.getElementById("posts");
			  // alert("Datos:"+datos);
                   ;
				   
				   
                obtenerDatos(id)
			  
			  //setInterval(obtenerDatos(id),10);
			  obtenerLikes(id);
			 // setInterval(obtenerLikes(id),1000);
			 
			  obtenerComentarios(id);
			 // setInterval(obtenerComentarios(id),1000);
			  obtenerLink(id);
			 // setInterval(obtenerLink(id),1000);
			  
				
			   
				
	         

			}
							
		function mostrarPostPersona() {
			var v1=document.getElementById("select");
            var valor=v1.options[v1.selectedIndex].value;
			var cad="/"+valor+"/posts";

			FB.api(cad, function(response){
				var result= document.getElementById('posts');
						  var rel="";
						
						  
						  if(response.data.length==0){
							  result.innerHTML=  '<h3>La persona no es un desarrollador</h3>';
							  
							  }else{
						      var numPost=document.getElementById("numPost").value;
							 result.innerHTML= '<h2>Posts de persona:</h2>';
							 
   	 						if(numPost<=response.data.length){
										
								for(var i=0;i < numPost;i++){
							    //result.innerHTML+="Posts:"+(i+1)+"<br>";
					              alert("Post:"+(i+1)+" Obtenido");
					              obtenerInfoPost(response.data[i].id);
								
								}
							}else{
								
								for(var i=0;i < response.data.length;i++){
							    //result.innerHTML+="Posts:"+(i+1)+"<br>";
					            alert("Post:"+(i+1)+" Obtenido");
								obtenerInfoPost(response.data[i].id);
								
								
								}
								}
							  
							}
						  
			             
						});	

		}//Final de mostrarPostPersona()
			
		
		
        </script>
        
 
<div id="posts"> </div>
</body>
</html> 
