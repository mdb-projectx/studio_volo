var checkurl = URL + "customer/account/signupformpopup/";
/* URL is define in header.phtml */
			

			$j('.alogin').colorbox({
				href:checkurl,
				title:' ',
				innerWidth: 390,
				innerHeight: 210,
				initialWidth: 390,
				initialHeight: 210,
				opacity: 0.75,
				overlayClose: false,
				escKey: false,
				onComplete: function() {
					$j('#email_address').focus();				
					$j('#login-wrap .signup button').click(function() {
						$j('#alogin').colorbox.resize({
							innerWidth: 390,
							innerHeight: 210
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
							innerHeight: 210
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
			                       redirectURL = URL+"checkout/onepage/";
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
	    /*Ajax Register Customer Function Step 1*/
		function preRegisterAjax()	{
			var valid = new Validation('regis-form');
                        if(valid.validate())	{
			} else	{
				//alert('valid');
				var request = new Ajax.Request(
				URL + "customer/account/ajaxPreCreate",
				{
					method:'post',
					onComplete: function(){
						//alert("completed");
					},
					onSuccess: function(transport){
						response = eval('(' + transport.responseText + ')');
						//alert(response.success);
						//alert(response.message);
						if (response.success) {
							//alert("success");
							$j('#alogin').colorbox.resize({
	                                                        innerWidth: 390,
	                                                        innerHeight: 190
	                                                });
							$j('#regis-form .step1').stop(true,true).slideUp(); $j('#regis-form .step2').stop(true, true).slideDown();
							$j('.errormsg').empty();
							$j('.step2 input').removeClass('validation-failed');
							$j('.validation-advice').remove();
							$j('#login-wrap h3').text('Protect Your Account');
							$j('#password').focus();
						} else {
							if ((typeof response.message) == 'string') {
                                                        	$j('.errormsg').html(response.message);
                                                        }
							return false;
						}
					},
                                        onFailure: function(){
                                        	alert("Failed, please try again later.");
                                        },
                                        parameters: Form.serialize('regis-form')
				})
			}
		}

          /*Ajax Register Customer Function */
                function registerAjax() {
					var origHeight = $j('#regis-form').height();		
						
					 var valid = new Validation('regis-form');
					 if(valid.validate()){				 
					/*
						$j('#alogin').colorbox.resize({
							innerWidth: 390,
							innerHeight: 460,
						});
					*/
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
								/*
								   var path=window.location.pathname;
								   path=path.replace('/index.php/','');		
									if ((path=='checkout/cart/') || (path=='checkout/cart/#'))
									{
										path='checkout/onepage/';
									}
								   redirectURL = URL+path;
								*/
								   redirectURL = URL + "checkout/onepage";
								   setTimeout("location.href = redirectURL;",redirectTime);
							    }else{
								if ((typeof response.message) == 'string') {
								$j('.errormsg').html(response.message);
								}
								var newHeight;
                		                                var checkHeight = setInterval(function(){
         	                                                newHeight = $j('#login-form').height();
	                                                        if(newHeight != origHeight) {
	                                                                clearInterval(checkHeight);
	                                                                checkHeight = 0;
									/*
                                                                	$j('#alogin').colorbox.resize({
                                                                	        innerWidth: 390,
                                                        	                innerHeight: (460 - 386 + newHeight)
                                                	                });
									*/
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
					  } else	{
						var newHeight;
						var checkHeight = setInterval(function(){ 		
							newHeight = $j('#login-form').height();
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
		var origHeight = $j('#forgot-form').height();
		var valid = new Validation('forgot-form');
                if(valid.validate()){
			var req2 = new Ajax.Request(URL + "customer/account/ajaxForgotPassword/",
			 {
				method:'post',
				parameters: $('forgot-form').serialize(true) ,
				onSuccess: function(transport){
				   var response = eval('(' + transport.responseText + ')');
				   if(response.success){
					$j('#forgot-form p.error').text(response.message).show();
					$j('#forgot-form p.control').show();
					$j('#fpass p').not('.error, .control').hide();
					newHeight = $j('#forgot-form').height();
					$j('#alogin').colorbox.resize({
                                                innerWidth: 390,
                                                innerHeight: (460 - 386 + newHeight)
                                        });
					setTimeout(function() {
						$j('#cboxClose').click();
					}, 5000);

				   }else{
					 alert(response.message);
				   }
				},
				onFailure: function(){ alert('Something went wrong...') }
			 });
 		} else {
			var newHeight;
                        var checkHeight = setInterval(function(){
                        	newHeight = $j('#forgot-form').height();
                        	if(newHeight != origHeight) {
                        		clearInterval(checkHeight);
	                        	checkHeight = 0;
                                        $j('#alogin').colorbox.resize({
                                        	innerWidth: 390,
                                                innerHeight: (460 - 386 + newHeight + 70)
                                        });
                                }
                                setTimeout(function() {
                                	clearInterval(checkHeight);
                                checkHeight = 0;}, 100);
                        }, 10);
                        return false;
		}
        }
