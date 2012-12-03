var checkurl = URL + "customer/account/signupformpopup/";
/* URL is define in header.phtml */
			

			$j('.alogin').colorbox({
				href:checkurl,
				title:' ',
				innerWidth: 390,
				innerHeight: 285,
				initialWidth: 390,
				initialHeight: 285,
				opacity: 0.75,
				onComplete: function() {
				
					$j('#login-wrap .signup button').click(function() {
						$j('#alogin').colorbox.resize({
							innerWidth: 390,
							innerHeight: 460,
						});
						
						$j('#signup-wrap').show();
						$j('#login-wrap').hide();
						// show/hide
					});
				
				}
			});
			
	        if($('alogin')){
				T$('alogin').onclick = function()
				{
                //TINY.box.show({url: checkurl ,width:620,height:100,opacity:20,topsplit:10});
				
				}
			} 
			 else if($('aclose')){
				T$('aclose').onclick = function()
				{
				TINY.box.alpha(p,-1,0,3)
				}
			}
                
		/*Ajax Login Function */
		function loginAjax() {	
				var origHeight = $j('#login-form').height();	
			var valid = new Validation('login-form');
			 if(valid.validate()){
			 
						$j('#alogin').colorbox.resize({
							innerWidth: 390,
							innerHeight: 285,
						});
						
			    var request = new Ajax.Request(
				 URL + "customer/account/ajaxLogin",
				{
				    method:'post',
				    onComplete: function(){
				       
				    },
				    onSuccess: function(transport){
				       if (transport && transport.responseText){
					 try{
					    response = eval('(' + transport.responseText + ')');
					  }
					  catch (e) {
						response = {};
					  }
					}
					
					if (response.success){
					   //alert('Successfully Loggedin');
					   redirectTime = "1";
					   var path=window.location.pathname;
					   path=path.replace('/index.php/','');		
						if ((path=='checkout/cart/')|| (path=='checkout/cart/#'))
						{
							path='checkout/onepage/';
						}
                       redirectURL = URL+path;
					   setTimeout("location.href = redirectURL;",redirectTime);
					}else{
					    if ((typeof response.message) == 'string') {
						$j('.errormsg').html(response.message);
					    } 
					    return false;
					}
				    },
				    onFailure: function(){
				      alert("Failed");
				    },
				    parameters: Form.serialize('login-form')
				}
			      );
			  }else{
				
				var checkHeight = setInterval(function(){  			
					newHeight = $j('#login-form').height();		
					if(newHeight != origHeight) {
						clearInterval(checkHeight);
						checkHeight = 0;										
						$j('#alogin').colorbox.resize({
							innerWidth: 390,
							innerHeight: 285 - 143 + newHeight
						});
					}					
					setTimeout(function() { 					
						clearInterval(checkHeight);
						checkHeight = 0;}, 100);
				}, 10);
				
			    return false;
			  }
			  
		}	
            /*Ajax Register Customer Function */
                function registerAjax() {	
					var origHeight = $j('#regis-form').height();		
						
					 var valid = new Validation('regis-form');
					 if(valid.validate()){				 
					 
						$j('#alogin').colorbox.resize({
							innerWidth: 390,
							innerHeight: 460,
						});
						
						  var request = new Ajax.Request(
						URL + "customer/account/ajaxCreate",
						{
							method:'post',
							onComplete: function(){
							   
							},
							onSuccess: function(transport){
							   if (transport && transport.responseText){
							 try{
								response = eval('(' + transport.responseText + ')');
							  }
							  catch (e) {
								response = {};
							  }
							}
							
							if (response.success){
							       //alert('Successfully Registered');
								   redirectTime = "1";
								   var path=window.location.pathname;
								   path=path.replace('/index.php/','');		
									if ((path=='checkout/cart/') || (path=='checkout/cart/#'))
									{
										path='checkout/onepage/';
									}
								   redirectURL = URL+path;
								   //redirectURL = URL + "customer/account";
								   setTimeout("location.href = redirectURL;",redirectTime);
							    }else{
								if ((typeof response.message) == 'string') {
								$j('.errormsg').html(response.message);
								}
								var newHeight;
                		                                var checkHeight = setInterval(function(){
        	                                               newHeight = $j('#regis-form').height();
	                                                        if(newHeight != origHeight) {
	                                                                clearInterval(checkHeight);
	                                                                checkHeight = 0;
                                                                	$j('#alogin').colorbox.resize({
                                                                	        innerWidth: 390,
                                                        	                innerHeight: (460 - 386 + newHeight)
                                                	                });
                                        	                }
                                	                        setTimeout(function() {
                        	                                        clearInterval(checkHeight);
                	                                                checkHeight = 0;}, 100);
		                                                }, 10);
 
								return false;
							}
							},
							onFailure: function(){
							  alert("Failed");
							},
							parameters: Form.serialize('regis-form')
						}
						  );
					  }else{
						var newHeight;
						var checkHeight = setInterval(function(){ 		
							newHeight = $j('#regis-form').height();						
							if(newHeight != origHeight) {
								clearInterval(checkHeight);
								checkHeight = 0;												
								$j('#alogin').colorbox.resize({
									innerWidth: 390,
									innerHeight: (460 - 386 + newHeight)
								});
							}					
							setTimeout(function() { 					
								clearInterval(checkHeight);
								checkHeight = 0;}, 100);
						}, 10);
						return false;
					  }
			  
				
		        }	
		/*Forget Password Function */
		function forgetpass(){	
			var req2 = new Ajax.Request(URL + "customer/account/ajaxForgotPassword/",
			 {
				method:'post',
				parameters: $('forgot-form').serialize(true) ,
				onSuccess: function(transport){
				   var response = eval('(' + transport.responseText + ')');
				   if(response.success){
					  alert(response.message);
					  TINY.box.hide();
					 

				   }else{
					 alert(response.message);
				   }
				},
				onFailure: function(){ alert('Something went wrong...') }
			 });
 
        }
