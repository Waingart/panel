/*!

 =========================================================
 * Paper Bootstrap Wizard - v1.0.2
 =========================================================
 
 * Product Page: https://www.creative-tim.com/product/paper-bootstrap-wizard
 * Copyright 2017 Creative Tim (http://www.creative-tim.com)
 * Licensed under MIT (https://github.com/creativetimofficial/paper-bootstrap-wizard/blob/master/LICENSE.md)
 
 =========================================================
 
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 */

// Paper Bootstrap Wizard Functions

searchVisible = 0;
transparent = true;

        $(document).ready(function(){

            /*  Activate the tooltips      */
            $('[rel="tooltip"]').tooltip({trigger:'manual'});

            // Code for the Validator
            var $validator = $('form#contacts').validate({
        		  rules: {
        		    name: {
        		      required: true,
        		      minlength: 3
        		    },
        		    phone: {
        		      required: true,
        		      minlength: 3
        		    },
        		    email: {
        		      required: true
        		    }
                },
                messages: {
				email: "Введите, пожалуйста, ваш email",

			}

        	});
            var testvalid = true;
            var $validator1 = $('form#egrul').validate({
        		  rules: {
        		    ogrn: {
        		      required: true,
        		      minlength: 11
        		    },
        		    inn_kpp: {
        		      required: true,
        		      minlength: 15
        		    },
        		    address: {
        		      required: true
        		    },
        		    managmentname: {
        		      required: true
        		    }
                },
                messages: {
				//email: "Введите, пожалуйста, ваш email",

			}

        	});
        	$("#noorg").on("ifChecked", function(){
        	    testvalid = false;
        	   /* alert("ifChecked");
        	    var $validator1 = $('form#egrul').validate({
        		  rules: {
        		    ogrn: {
        		      required: false,
        		      minlength: 0
        		    },
        		    inn_kpp: {
        		      required: false,
        		      minlength: 0
        		    },
        		    address: {
        		      required: false,
        		    },
        		    managmentname: {
        		      required: false,
        		    }
                },
                messages: {
				//email: "Введите, пожалуйста, ваш email",

			}

        	});
        	    */
        	});
        		$("#noorg").on("ifUnchecked", function(){
        		    testvalid = true;
        	    //alert("ifUnchecked");
        	    
        	    
        	});
        	$('#finish').click(function(){
        	    if(testvalid){
        	    var $valid = $('form#egrul').valid();
                	if(!$valid) {
                		$validator1.focusInvalid();
                		return false;
                	}else{
                	    send_form("form#egrul");
                	}
        	    }else{
        	        send_form("form#egrul");
        	    }
        	    $('#wizard').modal('hide');
        	    location.reload();
        	});
        	function send_form(form){
        	    var postData = $(form).serializeArray();
        	    //alert($("#phone").intlTelInput("getNumber"));
        	    if(form == 'form#contacts'){
        	        postData[2].value =  $("#phone").intlTelInput("getNumber");
        	    }
                          var formURL = '/crm/self_add/';
                          $.ajax(
                          {
                              url : formURL,
                              type: "POST",
                              data : postData,
                             
                              success:function(data, textStatus, jqXHR) 
                              {
                               
                                
                              },
                              error: function(jqXHR, textStatus, errorThrown) 
                              {
                                 $btn.button('reset');   
                              }
                          });
        	}
            // Wizard Initialization
          	$('.wizard-card').bootstrapWizard({
                'tabClass': 'nav nav-pills',
                'nextSelector': '.btn-next',
                'previousSelector': '.btn-previous',

                onNext: function(tab, navigation, index) {
                   // if(index==2){
                    	var $valid = $('form#contacts').valid();
                    	if(!$valid) {
                    		$validator.focusInvalid();
                    		return false;
                    	}else{
                    	    send_form("form#contacts");
                    	    
                    	}
                   // }
                },

                onInit : function(tab, navigation, index){

                  //check number of tabs and fill the entire row
                  var $total = navigation.find('li').length;
                  $width = 100/$total;

                  navigation.find('li').css('width',$width + '%');

                },

                onTabClick : function(tab, navigation, index){
                    return false;
                    /*var $valid = $('form#contacts').valid();
                	if(!$valid) {
                		$validator.focusInvalid();
                		return false;
                	}else{
                	    send_form("form#contacts");
                	    
                	}
                	*/

                },

                onTabShow: function(tab, navigation, index) {
                    var $total = navigation.find('li').length;
                    var $current = index+1;

                    var $wizard = navigation.closest('.wizard-card');

                    // If it's the last tab then hide the last button and show the finish instead
                    if($current >= $total) {
                        $($wizard).find('.btn-next').hide();
                        $($wizard).find('.btn-finish').show();
                    } else {
                        $($wizard).find('.btn-next').show();
                        $($wizard).find('.btn-finish').hide();
                    }

                    //update progress
                    var move_distance = 100 / $total;
                    move_distance = move_distance * (index) + move_distance / 2;

                    $wizard.find($('.progress-bar')).css({width: move_distance + '%'});
                    //e.relatedTarget // previous tab

                    $wizard.find($('.wizard-card .nav-pills li.active a .icon-circle')).addClass('checked');

                }
	        });


                // Prepare the preview for profile picture
                $("#wizard-picture").change(function(){
                    readURL(this);
                });

                $('[data-toggle="wizard-radio"]').click(function(){
                    wizard = $(this).closest('.wizard-card');
                    wizard.find('[data-toggle="wizard-radio"]').removeClass('active');
                    $(this).addClass('active');
                    $(wizard).find('[type="radio"]').removeAttr('checked');
                    $(this).find('[type="radio"]').attr('checked','true');
                });

                $('[data-toggle="wizard-checkbox"]').click(function(){
                    if( $(this).hasClass('active')){
                        $(this).removeClass('active');
                        $(this).find('[type="checkbox"]').removeAttr('checked');
                    } else {
                        $(this).addClass('active');
                        $(this).find('[type="checkbox"]').attr('checked','true');
                    }
                });

                $('.set-full-height').css('height', 'auto');

            });



         //Function to show image before upload

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
