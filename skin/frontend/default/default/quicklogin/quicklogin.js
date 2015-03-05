var checkurl = URL + "customer/account/signupformpopup/";
/* URL is define in header.phtml */
			

			$j('.alogin').colorbox({
				href:checkurl,
				title:' ',
				left: 0,
				top: 0,
//				innerWidth: 420,
				innerHeight: 250,
//				initialWidth: 420,
				initialHeight: 250,
				opacity: 0.75,
				overlayClose: false,
				escKey: false,
				className:'loginpop',
				onComplete: function() {
					var width = $j(window).width()/2 - $j('#colorbox').width() / 2;
					$j('#colorbox').css('cssText', 'left: ' + width + 'px !important; top: 200px !important; opacity: 100;');
					$j('#cboxLoadedContent').css('cssText', 'overflow:hidden !important;');
					$j('#email_address').focus();				
					$j('#login-wrap .signup button').click(function() {
						/*
						$j('#alogin').colorbox.resize({
							innerWidth: 420,
							innerHeight: 250
						});
						*/
						$j('#cboxContent').height(250);
						$j('#colorbox, #cboxWrapper').height(250 + 32);
						
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
			 
				/*
				$j('#alogin').colorbox.resize({
					innerWidth: 420,
					innerHeight: 300
				});
				*/
//				$j('#cboxContent').height(300);
//				$j('#colorbox, #cboxWrapper').height(300 + 32);
				
				$j('#loginspin').show();						
				var request = new Ajax.Request(
				 URL + "customer/account/ajaxLogin",
				{
					method:'post',
					onComplete: function(){
					   
					},
					onSuccess: function(transport){
					$j('#loginspin').hide();
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
					   if(location.hostname.substring(0,7) != 'staging') { 
							mixpanel.identify(response.email);
							mixpanel.people.set({
								"$email": response.email
							});
							ga('set', '&uid', response.email);
					   } else {
						   console.log('mixpanel.identify('+response.email+');' + '\n' + 
							'mixpanel.people.set({' + '\n' + 
							'	"$email": ' + response.email + '\n' + 
							'});');
							console.log("ga('set', '&uid', "+response.email+");");
					   }
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
				$j('#loginspin').hide();
				var checkHeight = setInterval(function(){  			
					newHeight = $j('#login-form').height();		
					if(newHeight != origHeight) {
						clearInterval(checkHeight);
						checkHeight = 0;										
						/*
						$j('#alogin').colorbox.resize({
							innerWidth: 420,
							innerHeight: (460 - 350 + newHeight)
						});
						*/
						$j('#cboxContent').height(460 - 350 + newHeight);
						$j('#colorbox, #cboxWrapper').height(460 - 350 + newHeight + 32);						
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
				$j('#regisspin').show();
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
							$j('#regisspin').hide();
							/* $j('#alogin').colorbox.resize({
								innerWidth: 420,
								innerHeight: 250
							}); */
							$j('#cboxContent').height(250);
							$j('#colorbox, #cboxWrapper').height(250 + 32);
							
							$j('#regis-form .step1').stop(true,true).slideUp(); $j('#regis-form .step2').stop(true, true).slideDown();
							$j('.errormsg').empty();
							$j('.step2 input').removeClass('validation-failed');
							$j('.validation-advice').remove();
							$j('#login-wrap h3').text('Protect Your Account');
							$j('#password').focus();
						} else {
							$j('#regisspin').hide();
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
						
					$j('#cboxContent').height(460);
					$j('#colorbox, #cboxWrapper').height(460 + 32);
					*/
					$j('#cboxContent').height(250);
					$j('#colorbox, #cboxWrapper').height(250 + 32);
			
					$j('#regis2spin').show();
						  var request = new Ajax.Request(
						URL + "customer/account/ajaxCreate",
						{
							method:'post',
							onComplete: function(){
							   
							},
							onSuccess: function(transport){
								// $j('#regis2spin').hide()
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
									var d = new Date();
									var signupDate = d.getFullYear() + '-' + ('0' + (d.getMonth()+1)).slice(-2) + '-' + ('0' + d.getDate()).slice(-2) + 'T' + ('0' + d.getHours()).slice(-2) + ':' + ('0' + d.getMinutes()).slice(-2) + ':' + ('0' + d.getSeconds()).slice(-2);
		
								   if(location.hostname.substring(0,7) != 'staging') {
									   mixpanel.track('Signup');
									   mixpanel.alias(response.email);
									   mixpanel.people.set({
											"$email": response.email,
											"$created": signupDate
									   });
								   } else {
									   console.log('mixpanel.track(\'Signup\');' + '\n' +
									   'mixpanel.alias('+response.email+');' + '\n' + 
										'mixpanel.people.set({' + '\n' + 
										'	"$email": ' + response.email + ','+ '\n' + 
										'	"$created": ' + signupDate + '\n' + 
										'});');
								   }
								   
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
								$j('#regis2spin').hide()
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
																	$j('#cboxContent').height(460 - 386 + newHeight);
																	$j('#colorbox, #cboxWrapper').height(460 - 386 + newHeight + 32);
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
						$j('#regis2spin').hide()
						var newHeight;
						var checkHeight = setInterval(function(){ 		
							newHeight = $j('#login-form').height();
							if(newHeight != origHeight) {
								clearInterval(checkHeight);
								checkHeight = 0;	
								/*
								$j('#alogin').colorbox.resize({
									innerWidth: 420,
									innerHeight: (460 - 386 + newHeight)
								});
								*/
								$j('#cboxContent').height(460 - 386 + newHeight);
								$j('#colorbox, #cboxWrapper').height(460 - 386 + newHeight + 32);
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
					$j('#forgotspin').show();
			var req2 = new Ajax.Request(URL + "customer/account/ajaxForgotPassword/",
			 {
				method:'post',
				parameters: $('forgot-form').serialize(true) ,
				onSuccess: function(transport){
					$j('#forgotspin').hide();
				   var response = eval('(' + transport.responseText + ')');
				   if(response.success){
					$j('#forgot-form p.error').text(response.message).show();
					$j('#forgot-form p.control').show();
					$j('#fpass p').not('.error, .control').hide();
					newHeight = $j('#forgot-form').height();
					/* $j('#alogin').colorbox.resize({
						innerWidth: 420,
						innerHeight: (460 - 386 + newHeight)
					}); */
					$j('#cboxContent').height(460 - 386 + newHeight);
					$j('#colorbox, #cboxWrapper').height(460 - 386 + newHeight + 32);
					setTimeout(function() {
						$j('#cboxClose').click();
					}, 5000);

				   }else{
					$j('#forgotspin').hide();
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
								/*
								$j('#alogin').colorbox.resize({
									innerWidth: 420,
									innerHeight: (460 - 386 + newHeight + 70)
								});
								*/
								$j('#cboxContent').height(460 - 386 + newHeight);
								$j('#colorbox, #cboxWrapper').height(460 - 386 + newHeight + 32);
							}
							setTimeout(function() {
								clearInterval(checkHeight);
							checkHeight = 0;}, 100);
						}, 10);
						return false;
		}
		}

