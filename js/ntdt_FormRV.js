function js_ntdt_ToggleRV(parStrShow,parStrHide)
{ var ToggleRVButton = document.getElementById("ToggleRV");
  var ListHourRV = document.getElementsByClassName('toggable');
  for (var i = 0; i < ListHourRV.length; i ++)
      { if (ListHourRV[i].style.display === "none")
           { ListHourRV[i].style.display = '';
             ToggleRVButton.innerHTML = parStrHide;
           }
        else
           { ListHourRV[i].style.display = 'none';
             ToggleRVButton.innerHTML = parStrShow;
           }
      }
}

function js_ntdt_SetHour(parNiceDate,parIndice,parHour)
{ event.preventDefault();

  document.getElementById("SpanHourRV").innerHTML = parNiceDate + ' - ' + parHour;
  document.getElementById("LstRV").value = parHour;
  document.getElementById("CurDate").value = parIndice;
}

function js_ntdt_CalRV(parStartDate)
{ var ajaxURL = F_Send.ajaxurl.value;

  jQuery(document).ready(function($) {
		var data = {
			'action': 'ntdt_display_CalRV',
			'StartD': parStartDate
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxURL,data,function(response) {
		                         document.getElementById("ntdt_FormCalRV").innerHTML = response;
		                         //$('#ntdt_FormCalRV').text("...");
		                         //$('#ntdt_FormCalRV').html("<p>...</p>");
			                       //console.log(response);
		                                              });
	                                  });
}

function SendRV(parForm)
{ var LstReason = F_Send.LstReason.options[F_Send.LstReason.selectedIndex].value;
  var CurDate = F_Send.CurDate.value;
  if (CurDate == "")
     { alert("Please select date and time");
       return false;
     } 

  var LstRV = F_Send.LstRV.value;
  eval('var RVs = F_Send.RVs_' + CurDate + '.value;');
  var EmailRV = F_Send.EmailRV.value;
  var TelRV = F_Send.TelRV.value;
  var NameRV = F_Send.NameRV.value;
  var lnkRV = F_Send.lnkRedir.value;
  var MsgRV = F_Send.RV_Msg.value;
  var xmlURL = F_Send.action.value;
  var ajaxURL = F_Send.ajaxurl.value;

  event.preventDefault();
     
  if (TelRV == "")
     { //F_Send.EmailRV.style.background = 'Yellow'; 
       F_Send.TelRV.style.borderColor = "red";
       F_Send.TelRV.focus();
       alert("Please enter your phone number");
       return false;
     }
  else
     F_Send.TelRV.style.borderColor = "";
       
  if (NameRV == "")
     { F_Send.NameRV.style.borderColor = "red";
       F_Send.NameRV.focus();
       alert("Please enter your name");
       return false;
     }
  else
     F_Send.NameRV.style.borderColor = "";
     
  if (EmailRV == "")
     { F_Send.EmailRV.style.borderColor = "red";
       F_Send.EmailRV.focus();
       alert("Please enter your e-mail");
       return false;
     }
  else
     F_Send.EmailRV.style.borderColor = "";
  
  
  
  
  jQuery(document).ready(function($) {
		var data = {
			'action': 'ntdt_SendRV',
			'rvs': RVs,
			'ind': CurDate,
			'h': LstRV,
			'e': EmailRV,
			't': TelRV,
			'r': LstReason,
			'lnk': lnkRV,
			'n': NameRV,
			'm': MsgRV
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxURL,data,function(response) {
		                         document.getElementById("ntdt_FormSent").innerHTML = response;
			                       //console.log(response);
			                       
			                       let NotConfirmed = response.includes("!!!");
	                           if (!NotConfirmed)
	                              { F_Send.style.display = 'none';
	                                if (lnkRV != "") window.open(lnkRV,'_PayRV');
	                              }
		                                              });
	                                  });
}



(function($)
{	$(document).ready(function(){


  $('.js-rv-before').click(function (e) {
  e.preventDefault();

  const today = new Date();
  var date = new Date($('#ntdt_FormRV_picker').val());
  var day = date.getDate(); 
  var month = date.getMonth()+1;
  var year = date.getFullYear();

  var yesterday = new Date();
  yesterday.setFullYear(year,month-1,day-1);
  var day = yesterday.getDate();
  var month = yesterday.getMonth()+1;
  var year = yesterday.getFullYear();
  var strDay = day.toString();     if (strDay.length < 2) strDay = "0" + strDay;
  var strMonth = month.toString(); if (strMonth.length < 2) strMonth = "0" + strMonth;
  var strYear = year.toString();
  var tmpPostDate = strDay + strMonth + strYear;

  today.setDate(today.getDate()+1); //Disable to book the same day!
  if(yesterday < today) throw "exit";
  
  var tmpValDate = strYear + '-' + strMonth + '-' + strDay;
  $('#ntdt_FormRV_picker').val(tmpValDate);
  
  //--- Form data:
  //const ajaxurl = $(this).attr('action');
  /*const data = { action: $(this).find('input[name=action]').val(), 
                 nonce:  $(this).find('input[name=nonce]').val(),
                 postid: $(this).find('input[name=postid]').val()
               }*/
  //--- Form button
  const ajaxurl = $(this).data('ajaxurl');
  const data = { action: $(this).data('action'), 
                 nonce:  $(this).data('nonce'),
                 date: tmpPostDate //$(this).data('date') //
               }
               
  //console.log('MyAjax: '+ajaxurl);
  //console.log('MyDataNonce: '+data);
  //console.log('Ret: '+tmpPostDate);

  //--- Ajax Fetch request:
  fetch(ajaxurl, { method: 'POST',
                   headers: { 'Content-Type': 'application/x-www-form-urlencoded',
                              'Cache-Control': 'no-cache',
                            },
                   body: new URLSearchParams(data),
                 })
  .then(response => response.json())
  .then(response => { console.log(response);
                      //--- on error:
                      if (!response.success) { alert(response.data);
                                               return;
                                             }
                      //--- on success:
                      //$(this).hide(); //Hide form
                      
                      $('.ntdt-FormRV').html(response.data);
                      //$('.ntdt-RV').html(tmpPostDate); //Display returned HTML
                    });
                });
                
                
                
                
  $('.js-rv-after').click(function (e) {
  e.preventDefault();

  var date = new Date($('#ntdt_FormRV_picker').val());
  var day = date.getDate(); 
  var month = date.getMonth()+1;
  var year = date.getFullYear();

  var tomorrow = new Date();
  tomorrow.setFullYear(year,month-1,day+1);
  var day = tomorrow.getDate();
  var month = tomorrow.getMonth()+1;
  var year = tomorrow.getFullYear();
  var strDay = day.toString();     if (strDay.length < 2) strDay = '0' + strDay;
  var strMonth = month.toString(); if (strMonth.length < 2) strMonth = '0' + strMonth;
  var strYear = year.toString();
  var tmpPostDate = strDay + strMonth + strYear;

  var tmpValDate = strYear + '-' + strMonth + '-' + strDay;
  $('#ntdt_FormRV_picker').val(tmpValDate);

  //--- Form data:
  //const ajaxurl = $(this).attr('action');
  /*const data = { action: $(this).find('input[name=action]').val(), 
                 nonce:  $(this).find('input[name=nonce]').val(),
                 postid: $(this).find('input[name=postid]').val()
               }*/
  //--- Form button
  const ajaxurl = $(this).data('ajaxurl');
  const data = { action: $(this).data('action'), 
                 nonce:  $(this).data('nonce'),
                 date: tmpPostDate //$(this).data('date') //
               }
               
  //console.log('MyAjax: '+ajaxurl);
  //console.log('MyDataNonce: '+data);
  //console.log('Ret: '+tmpPostDate);

  //--- Ajax Fetch request:
  fetch(ajaxurl, { method: 'POST',
                   headers: { 'Content-Type': 'application/x-www-form-urlencoded',
                              'Cache-Control': 'no-cache',
                            },
                   body: new URLSearchParams(data),
                 })
  .then(response => response.json())
  .then(response => { console.log(response);
                      //--- on error:
                      if (!response.success) { alert(response.data);
                                               return;
                                             }
                      //--- on success:
                      //$(this).hide(); //Hide form
                      
                      $('.ntdt-FormRV').html(response.data);
                      //$('.ntdt-RV').html(tmpPostDate); //Display returned HTML
                    });
                });              
                   
                   
                   
                          
  $('.js-ntdt-FormRV').change(function (e) {
  e.preventDefault();

  var date = new Date($('#ntdt_FormRV_picker').val());
  var day = date.getDate();
  var month = date.getMonth() + 1;
  var year = date.getFullYear();
  var strDay = day.toString();     if (strDay.length < 2) strDay = '0' + strDay;
  var strMonth = month.toString(); if (strMonth.length < 2) strMonth = '0' + strMonth;
  var strYear = year.toString();
  var tmpPostDate = strDay + strMonth + strYear;

  //--- Form data:
  //const ajaxurl = $(this).attr('action');
  /*const data = { action: $(this).find('input[name=action]').val(), 
                 nonce:  $(this).find('input[name=nonce]').val(),
                 postid: $(this).find('input[name=postid]').val()
               }*/
  //--- Form button
  const ajaxurl = $(this).data('ajaxurl');
  const data = { action: $(this).data('action'), 
                 nonce:  $(this).data('nonce'),
                 date: tmpPostDate //$(this).data('date') //
               }
               
  //console.log('MyAjax: '+ajaxurl);
  //console.log('MyDataNonce: '+data);
  //console.log('Ret: '+tmpPostDate);

  //--- Ajax Fetch request:
  fetch(ajaxurl, { method: 'POST',
                   headers: { 'Content-Type': 'application/x-www-form-urlencoded',
                              'Cache-Control': 'no-cache',
                            },
                   body: new URLSearchParams(data),
                 })
  .then(response => response.json())
  .then(response => { //console.log(response);
                      //--- on error:
                      if (!response.success) { alert(response.data);
                                               return;
                                             }
                      //--- on success:
                      //$(this).hide(); //Hide form
                      
                      $('.ntdt-FormRV').html(response.data);
                      //$('.ntdt-RV').html(tmpPostDate); //Display returned HTML
                    });
                });

  
  });
})(jQuery);