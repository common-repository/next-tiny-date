(function($)
{	$(document).ready(function(){


 

  $('.js-date-before').click(function (e) {
  e.preventDefault();

  const today = new Date();
  //var date = new Date($('#ntdt_datepicker').val());
  var date = new Date($('.js-ntdt-dates').val());
  var day = date.getDate(); 
  var month = date.getMonth()+1;
  var year = date.getFullYear();

  var tomorrow = new Date();
  tomorrow.setFullYear(year,month-1,day-1);
  var day = tomorrow.getDate();
  var month = tomorrow.getMonth()+1;
  var year = tomorrow.getFullYear();
  var strDay = day.toString();     if (strDay.length < 2) strDay = "0" + strDay;
  var strMonth = month.toString(); if (strMonth.length < 2) strMonth = "0" + strMonth;
  var strYear = year.toString();
  var tmpPostDate = strDay + strMonth + strYear;
  
  //if(tomorrow < today) throw "exit";
  
  var tmpValDate = strYear + '-' + strMonth + '-' + strDay;
  $('#ntdt_datepicker').val(tmpValDate);

  //--- Form data:
  //const ajaxurl = $(this).attr('action');
  /*const data = { action: $(this).find('input[name=action]').val(), 
                 nonce:  $(this).find('input[name=nonce]').val(),
                 id: $(this).find('input[name=id]').val()
               }*/
               
  //--- Button data:
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
                      //$(this).hide(); //Hide the form
                      
                      $('.ntdt-RV').html(response.data);
                      //$('.ntdt-RV').html(tmpPostDate); //Display returned HTML
                    });
                });
                
                
                
                
                
  $('.js-date-after').click(function (e) {
  e.preventDefault();

  //var date = new Date($('#ntdt_datepicker').val());
  var date = new Date($('.js-ntdt-dates').val());
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
  $('#ntdt_datepicker').val(tmpValDate);
  
  //--- Form data:
  //const ajaxurl = $(this).attr('action');
  /*const data = { action: $(this).find('input[name=action]').val(), 
                 nonce:  $(this).find('input[name=nonce]').val(),
                 id: $(this).find('input[name=id]').val()
               }*/
               
  //--- Button data:
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
                      //$(this).hide(); //Hide the form
                      
                      $('.ntdt-RV').html(response.data);
                      //$('.ntdt-RV').html(date); //Display returned HTML
                    });
                });
                
  
  
  
  
  $('.js-ntdt-dates').change(function (e) {
  // Empêcher l'envoi classique du formulaire
  e.preventDefault();

  var date = new Date($('#ntdt_datepicker').val());
  //var date = new Date($('.js-ntdt-dates').val());
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
                      //$(this).hide(); // Cacher le formulaire
                      
                      $('.ntdt-RV').html(response.data);
                      //$('.ntdt-RV').html(tmpPostDate); //Display returned HTML
                    });
                });

  
  });
})(jQuery);
