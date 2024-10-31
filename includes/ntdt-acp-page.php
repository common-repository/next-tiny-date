<script>
function ntdt_InfoCmt_Change(val)
{ if (document.getElementById('optInfoCmt').checked == true)
     { document.getElementById('divInfoCmt').style.display = "";
     }
  else
     { document.getElementById('divInfoCmt').style.display = "none";
     }
}

function ntdt_optClosed_Change(ind,val)
{ document.getElementById('optOpenAM_' + ind).checked = (val == false); 
  document.getElementById('optOpenPM_' + ind).checked = (val == false); 

  if (val)
     strState = 'hidden';
  else
     strState = 'number';
  document.getElementById('optStartHourAM_' + ind).type = strState; 
  document.getElementById('optStartMinAM_' + ind).type = strState; 
  document.getElementById('optEndHourAM_' + ind).type = strState; 
  document.getElementById('optEndMinAM_' + ind).type = strState; 
  document.getElementById('optStartHourPM_' + ind).type = strState; 
  document.getElementById('optStartMinPM_' + ind).type = strState; 
  document.getElementById('optEndHourPM_' + ind).type = strState; 
  document.getElementById('optEndMinPM_' + ind).type = strState; 
}

function ntdt_optOpenAM_Change(ind,val)
{ if (val)
     { document.getElementById('optStartHourAM_' + ind).type = 'number'; 
       document.getElementById('optStartMinAM_' + ind).type = 'number'; 
       document.getElementById('optEndHourAM_' + ind).type = 'number'; 
       document.getElementById('optEndMinAM_' + ind).type = 'number'; 

       if (document.getElementById('optClosed_' + ind).checked == true)
          document.getElementById('optClosed_' + ind).checked = false;
     }
  else
     { document.getElementById('optStartHourAM_' + ind).type = 'hidden'; 
       document.getElementById('optStartMinAM_' + ind).type = 'hidden'; 
       document.getElementById('optEndHourAM_' + ind).type = 'hidden'; 
       document.getElementById('optEndMinAM_' + ind).type = 'hidden'; 
       
       if (document.getElementById('optOpenPM_' + ind).checked == false)
          document.getElementById('optClosed_' + ind).checked = true;
     }
}

function ntdt_optOpenPM_Change(ind,val)
{ if (val)
     { document.getElementById('optStartHourPM_' + ind).type = 'number'; 
       document.getElementById('optStartMinPM_' + ind).type = 'number'; 
       document.getElementById('optEndHourPM_' + ind).type = 'number'; 
       document.getElementById('optEndMinPM_' + ind).type = 'number'; 
       
       if (document.getElementById('optClosed_' + ind).checked == true)
          document.getElementById('optClosed_' + ind).checked = false;
     }
  else
     { document.getElementById('optStartHourPM_' + ind).type = 'hidden'; 
       document.getElementById('optStartMinPM_' + ind).type = 'hidden'; 
       document.getElementById('optEndHourPM_' + ind).type = 'hidden'; 
       document.getElementById('optEndMinPM_' + ind).type = 'hidden'; 
       
       if (document.getElementById('optOpenAM_' + ind).checked == false)
          document.getElementById('optClosed_' + ind).checked = true;
     }
}

function js_ntdt_ModifyRV(form)
{ var tmpIndice = form.indice.value;
  var tmpHour = form.hour.value;
  var tmpRVs = form.RVs.value;
  var tmpName = "";
  var tmpReason = "";
  var tmpEmail = "";
  var tmpColEnabled = form.colEnabled.value;
  var tmpColDisabled = form.colDisabled.value;
  var tmpTRnum = form.trnum.value;
  var tmpTDnum = form.tdnum.value;
  var tmpPathImgLock = "<?php echo plugin_dir_url(__DIR__).'images/ntdt-lock.png';?>";
  var tmpPathImgUnlock = "<?php echo plugin_dir_url(__DIR__).'images/ntdt-unlock.png';?>";
  
  tmpIndButton = tmpTRnum - 1;
  var tmpValue = document.getElementById('idButton_'+tmpIndButton).value; 
  //alert('is: '+tmpValue + ' => id button: '+tmpIndButton + '- TR/TD N°' + tmpTRnum + '/' + tmpTDnum);
       
  //document.getElementById("txtHint").innerHTML = '...';

  ret = false;
  if (tmpValue == "Cancel")
     <?php echo 'ret = confirm(tmpHour + ": " + "' . esc_html__('Delete this appointment','next-tiny-date') . '" + " ?");';?>

  
  promptReason = null;
  if (tmpValue == "Lock")
     { //promptReason = prompt("Please enter your reason","Perso!");
       <?php echo 'promptReason = prompt("' . esc_html__('Please enter your reason','next-tiny-date') . ':","' . esc_html__('Perso','next-tiny-date') . '");';?>
       promptReason = promptReason.trim();
     }
     
  if ((ret == true) || ((promptReason != null)&&(promptReason!="")) || (tmpValue == "Unlock"))
     { var A_tr = document.getElementById("tableRV").getElementsByTagName("tr");
    
       switch(tmpValue)
             { case "Cancel": A_tr[tmpTRnum].style.backgroundColor = tmpColEnabled;
                              var A_td = document.getElementById("tableRV").getElementsByTagName("td");
                              tmpTDnum++; A_td[tmpTDnum].innerHTML = "";
                              tmpTDnum++; A_td[tmpTDnum].innerHTML = "";
                              tmpTDnum++; A_td[tmpTDnum].innerHTML = "";
                              tmpTDnum++; A_td[tmpTDnum].innerHTML = "";
                              tmpImageURL = tmpPathImgLock;
                              document.getElementById('idButton_'+tmpIndButton).title = "<?php esc_html_e('Lock','next-tiny-date') ?>";
                              document.getElementById('idButton_'+tmpIndButton).value = "Lock";
                              tmpName = form.name.value;
                              tmpReason = form.reason.value;
                              tmpEmail = form.email.value;
                              break;
               case "Unlock": A_tr[tmpTRnum].style.backgroundColor = tmpColEnabled;
                              var A_td = document.getElementById("tableRV").getElementsByTagName("td");
                              tmpTDnum++; A_td[tmpTDnum].innerHTML = "";
                              tmpTDnum++; A_td[tmpTDnum].innerHTML = "";
                              tmpImageURL = tmpPathImgLock;
                              document.getElementById('idButton_'+tmpIndButton).title = "<?php esc_html_e('Lock','next-tiny-date') ?>";
                              document.getElementById('idButton_'+tmpIndButton).value = "Lock";
                              break;
               case "Lock":   A_tr[tmpTRnum].style.backgroundColor = tmpColDisabled;
                              var A_td = document.getElementById("tableRV").getElementsByTagName("td");
                              tmpTDnum++; A_td[tmpTDnum].innerHTML = "...";
                              tmpTDnum++; A_td[tmpTDnum].innerHTML = promptReason;
                              tmpImageURL = tmpPathImgUnlock;
                              document.getElementById('idButton_'+tmpIndButton).title = "<?php esc_html_e('Unlock','next-tiny-date') ?>";
                              document.getElementById('idButton_'+tmpIndButton).value = "Unlock";
                              tmpReason = promptReason;
                              break;
             }
       document.getElementById('imageButton_'+tmpIndButton).src = tmpImageURL;


jQuery(document).ready(function($) {
		var data = {
			'action': 'ntdt_ModifyRV',
			'what': tmpValue,
			'rvs': tmpRVs,
			'ind': tmpIndice,
			'h': tmpHour,
			'n': tmpName,
			'r': tmpReason,
			'e': tmpEmail
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl,data,function(response) {
		                         document.getElementById("txtHint").innerHTML = response;
			                       //alert('Got this from the server: ' + response);
			                       //console.log(response);
		                                              });
	                                  });
     }
}
</script>

<?php
global $wpdb;
$TableName = $wpdb->prefix . 'ntdtRV';

$ntdt_CurrentVersion = get_option('ntdtCurrentVersion');
$ntdt_CurrentType = get_option('ntdtCurrentType');

$tmpVersionGD = "";
if (function_exists('gd_info'))
   { $A_InfoGD = gd_info();
     $tmpVersionGD = (empty($A_InfoGD['GD Version'])?"Not found!":$A_InfoGD['GD Version']);
   }
echo '<div align="right">' . esc_attr($ntdt_CurrentType) . ' Version v.' . esc_attr($ntdt_CurrentVersion) . ' - (GD Version: ' . esc_attr($tmpVersionGD)  . ')</div>';

if(!empty($_POST['do']))
  { $strDateTime = date('Ymd-His');
	  switch($_POST['do'])
	        { case esc_html__('Export','next-tiny-date'):
                 $retExported = 0;	        
	               ob_end_clean();
                 $fd = @fopen('php://output','w');
                 header("Content-disposition: attachment; filename = next-tiny-date_" . $strDateTime . ".csv");
                 $retExported = ntdt_ExportRV($fd);
                 fclose($fd);
                 ob_end_flush();
                 die();
                 //exit;
             	   if ($retExported > 0)
	                  { $tmpMsg = esc_html__('Appointments exported','next-tiny-date') . ": $retExported";
                      ntdt_LogFile($tmpMsg,"info");
                      $retExported = 0;
                    }
                 break;
         
            case esc_html__('Clean DB','next-tiny-date'):
                 $tmpSQL = $wpdb->prepare("SELECT id, DayRVs FROM $TableName ORDER BY id ASC");
                 $retDayRVs = $wpdb->get_results($tmpSQL);
                 
                 $NbDayRVs = count($retDayRVs);
                 $today = date('Ymd');
                 $NbDeleted = 0;
                 for ($i=0;$i<$NbDayRVs;$i++)
                     { if ($retDayRVs[$i]->id < $today)
                          { $tmpSQL = $wpdb->prepare("DELETE FROM $TableName WHERE id = %s",$retDayRVs[$i]->id);
                            $wpdb->query($tmpSQL);
                            $NbDeleted++;
                          }
                     }
                 $tmpMsg = esc_html__('Deleted records','next-tiny-date') . ": $NbDeleted";
                 ntdt_LogFile($tmpMsg,"info");
                 break;
	               
	           default:
	                 break;
	        }
  }


$tmpTab = sanitize_text_field($_GET['tab']);
$tab = (isset($tmpTab) and $tmpTab != "")?$tmpTab:'ntdt_view';
$tmpSection = sanitize_text_field($_GET['section']);
if($tab==='ntdt_settings')
  { $section = (isset($tmpSection) and $tmpSection != "")?$tmpSection:'hours';
  }

    $opt_StepRV = get_option('optStepRV',60);
    $opt_SendMail = get_option('optSendMail',1);
    $opt_Redirect = get_option('optRedirect');
    $opt_DayTillRV = get_option('optDayTillRV','6 months');
    $opt_Enabled = get_option('optEnabled','#80FF80');
    $opt_Booked = get_option('optBooked','#FDCB55');
    $opt_Disabled = get_option('optDisabled','#FF8080');
    $opt_Reason = get_option('optReason');
    $opt_BtnBg = get_option('optBtnBg','#2271B1');
    $opt_BtnBgHover = get_option('optBtnBgHover','#40FFFF');
    $opt_BtnCol = get_option('optBtnCol','#FFFFFF');
    $opt_BtnColHover = get_option('optBtnColHover','#FFFFFF');
    $opt_FormatDateRV = get_option('optFormatDateRV',0);
    $opt_PublicHolidays = get_option('optPublicHolidays');
    $opt_UserHolidays = get_option('optUserHolidays');
    $opt_NbHourButtons = get_option('optNbHourButtons',5);
    $opt_InfoCmt = get_option('optInfoCmt',0);
    $opt_InfoCmtIcon = get_option('optInfoCmtIcon','info-outline');
    $opt_InfoCmtMsg1 = get_option('optInfoCmtMsg1',"");
    $opt_InfoCmtFromDate1 = get_option('optInfoCmtFromDate1');
    $opt_InfoCmtToDate1 = get_option('optInfoCmtToDate1');
    $opt_InfoCmtColor1 = get_option('optInfoCmtColor1','#000000');
    $opt_InfoCmtMsg2 = get_option('optInfoCmtMsg2',"");
    $opt_InfoCmtFromDate2 = get_option('optInfoCmtFromDate2');
    $opt_InfoCmtToDate2 = get_option('optInfoCmtToDate2');
    $opt_InfoCmtColor2 = get_option('optInfoCmtColor2','#000000');
    $opt_InfoCmtMsg3 = get_option('optInfoCmtMsg3',"");
    $opt_InfoCmtFromDate3 = get_option('optInfoCmtFromDate3');
    $opt_InfoCmtToDate3 = get_option('optInfoCmtToDate3');
    $opt_InfoCmtColor3 = get_option('optInfoCmtColor3','#000000');
  
function ntdt_LogFile($parMsg,$parNoticeType)
{ echo "<div class=\"notice notice-" . esc_attr($parNoticeType) . " is-dismissible\"><p>" . esc_attr($parMsg) . "</p></div>";
}
?>

<div class="wrap">
<nav class="nav-tab-wrapper">
     <a href="?page=ntdt-acp&tab=ntdt_view" class="nav-tab <?php if($tab==='ntdt_view'):?>nav-tab-active<?php endif; ?>"><?php esc_html_e('Planning','next-tiny-date'); ?></a>
     <a href="?page=ntdt-acp&tab=ntdt_settings" class="nav-tab <?php if($tab==='ntdt_settings'):?>nav-tab-active<?php endif; ?>"><?php esc_html_e('General Settings','next-tiny-date'); ?></a>
     <a href="?page=ntdt-acp&tab=ntdt_stats" class="nav-tab <?php if($tab==='ntdt_stats'):?>nav-tab-active<?php endif; ?>"><?php esc_html_e('Stats','next-tiny-date'); ?></a>
     <a href="?page=ntdt-acp&tab=ntdt_help" class="nav-tab <?php if($tab==='ntdt_help'):?>nav-tab-active<?php endif; ?>"><?php esc_html_e('Help','next-tiny-date'); ?></a>
</nav>

    <div class="tab-content">
    <?php switch($tab)
          { case 'ntdt_settings': ?> 
          
              <?php
    switch ($section)
           { case 'settings':?>
                  <h1 class="screen-reader-text">Toolbox</h1>
		              <ul class="subsubsub"><li><a href="/wp-admin/admin.php?page=ntdt-acp&amp;tab=ntdt_settings&amp;section=settings" class="current"><?php esc_html_e('Settings','next-tiny-date'); ?></a> | </li>
		                                    <li><a href="/wp-admin/admin.php?page=ntdt-acp&amp;tab=ntdt_settings&amp;section=hours" class=""><?php esc_html_e('Opening hours','next-tiny-date'); ?></a> </li>
		              </ul><br class="clear">
           
    <form method="post" action="options.php">
    <?php settings_fields('ntdt-settings-group'); ?>
    <?php do_settings_sections('ntdt-settings-group'); ?>
  
    <h2 class="title"><?php esc_html_e('Appointments','next-tiny-date'); ?></h2>
    <table class="form-table">   
        <tr>
        <th scope="row"><?php esc_html_e('Duration','next-tiny-date'); ?></th> 
        <td><select name="optStepRV">
            <option value="15" <?php echo($opt_StepRV==15?"selected ":"");?>><?php esc_html_e('15 min','next-tiny-date'); ?></option>
            <option value="20" <?php echo($opt_StepRV==20?"selected ":"");?>><?php esc_html_e('20 min','next-tiny-date'); ?></option>
            <option value="30" <?php echo($opt_StepRV==30?"selected ":"");?>><?php esc_html_e('30 min','next-tiny-date'); ?></option>
            <option value="45" <?php echo($opt_StepRV==45?"selected ":"");?>><?php esc_html_e('45 min','next-tiny-date'); ?></option>
            <option value="60" <?php echo($opt_StepRV==60?"selected ":"");?>><?php esc_html_e('60 min','next-tiny-date'); ?></option>
            <option value="90" <?php echo($opt_StepRV==90?"selected ":"");?>><?php esc_html_e('90 min','next-tiny-date'); ?></option>
            <option value="120" <?php echo($opt_StepRV==120?"selected ":"");?>><?php esc_html_e('120 min (2 hours)','next-tiny-date'); ?></option>
            <option value="180" <?php echo($opt_StepRV==180?"selected ":"");?>><?php esc_html_e('180 min (3 hours)','next-tiny-date'); ?></option>
            <option value="240" <?php echo($opt_StepRV==240?"selected ":"");?>><?php esc_html_e('240 min (4 hours)','next-tiny-date'); ?></option>
            </select>
            <div class="tooltip">
             <span class="dashicons dashicons-info-outline">
                   <span class="tooltiptext"><?php esc_html_e('Select the duration of appointments.','next-tiny-date'); ?></span>
             </span>
            </div>
        </td></tr>

        <tr valign="top">
        <th scope="row"><?php esc_html_e('Colors','next-tiny-date'); ?></th>
        <td>
        <input type="color" name="optEnabled" value="<?php echo esc_attr($opt_Enabled); ?>" class="xxx" /> <?php esc_html_e('Available','next-tiny-date'); ?><br>
        <input type="color" name="optBooked" value="<?php echo esc_attr($opt_Booked); ?>" class="xxx" /> <?php esc_html_e('Booked','next-tiny-date'); ?><br>
        <input type="color" name="optDisabled" value="<?php echo esc_attr($opt_Disabled); ?>" class="xxx" /> <?php esc_html_e('Not available','next-tiny-date'); ?><br>
        </td></tr>
    
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Till date','next-tiny-date'); ?></th> 
        <td><select name="optDayTillRV">
            <option value="2 weeks" <?php echo($opt_DayTillRV=="2 weeks"?"selected ":"");?>><?php esc_html_e('2 weeks','next-tiny-date'); ?></option>
            <option value="1 month" <?php echo($opt_DayTillRV=="1 month"?"selected ":"");?>><?php esc_html_e('1 month','next-tiny-date'); ?></option>
            <option value="2 months" <?php echo($opt_DayTillRV=="2 months"?"selected ":"");?>><?php esc_html_e('2 months','next-tiny-date'); ?></option>
            <option value="3 months" <?php echo($opt_DayTillRV=="3 months"?"selected ":"");?>><?php esc_html_e('3 months','next-tiny-date'); ?></option>
            <option value="6 months" <?php echo($opt_DayTillRV=="6 months"?"selected ":"");?>><?php esc_html_e('6 months','next-tiny-date'); ?></option>
            <option value="9 months" <?php echo($opt_DayTillRV=="9 months"?"selected ":"");?>><?php esc_html_e('9 months','next-tiny-date'); ?></option>
            <option value="1 year" <?php echo($opt_DayTillRV=="1 year"?"selected ":"");?>><?php esc_html_e('1 year','next-tiny-date'); ?></option>
            </select>
            <br><em><font color="#808080"><?php esc_html_e('Select the length period to book appointments.','next-tiny-date'); ?></font></em>
        </td></tr>
        
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Reasons','next-tiny-date'); ?></th> 
        <td><textarea rows="6" cols="50" name="optReason"><?php echo esc_attr($opt_Reason);?></textarea>
            <br><em><font color="#808080"><?php esc_html_e('Enter each reason on a separate line.','next-tiny-date');echo "<br>";esc_html_e('Start with a star to set a title in the scrolling list.','next-tiny-date'); ?></font></em>
        </td></tr>

        <tr valign="top">
        <th scope="row"><?php esc_html_e('Date format',NXTWM_DOMAIN); ?></th>
        <td><input type="radio" name="optFormatDateRV" value=0 <?php echo($opt_FormatDateRV==0?"checked ":"");?> /> <?php esc_html_e('Day/Month/Year','next-tiny-date'); ?><br>
            <input type="radio" name="optFormatDateRV" value=1 <?php echo($opt_FormatDateRV==1?"checked ":"");?> /> <?php esc_html_e('Month/Day/Year','next-tiny-date'); ?>
        </td></tr>
    </table>
        
    <h2 class="title"><?php esc_html_e('Booking','next-tiny-date'); ?></h2>
    <table class="form-table">   
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Booking button','next-tiny-date'); ?></th> 
        <td>
        <input type="color" name="optBtnBg" value="<?php echo esc_attr($opt_BtnBg); ?>" class="xxx" /> <?php esc_html_e('Background color','next-tiny-date'); ?><br>
        <input type="color" name="optBtnBgHover" value="<?php echo esc_attr($opt_BtnBgHover); ?>" class="xxx" /> <?php esc_html_e('Background color on mouse over','next-tiny-date'); ?><br>
        <input type="color" name="optBtnCol" value="<?php echo esc_attr($opt_BtnCol); ?>" class="xxx" /> <?php esc_html_e('Font color','next-tiny-date'); ?><br>
        <input type="color" name="optBtnColHover" value="<?php echo esc_attr($opt_BtnColHover); ?>" class="xxx" /> <?php esc_html_e('Font color on mouse over','next-tiny-date'); ?>
        </td></tr>
        
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Maximum of booking buttons','next-tiny-date'); ?></th> 
        <td><input type="number" size="2" min="1" max= "10" id="optNbHourButtons" name="optNbHourButtons" value="<?php echo esc_attr($opt_NbHourButtons);?>"> <?php esc_html_e('Maximum number of booking buttons in a column','next-tiny-date'); ?>
        </td></tr>
        
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Holidays','next-tiny-date'); ?></th> 
            <td><input type="text" size="32" maxlength="256" name="optUserHolidays" value="<?php echo esc_attr($opt_UserHolidays);?>"> <?php esc_html_e('e.g. for summer: 07/01/23-07/15/23, 08/31/23 or 01/07/23-15/07/23, 31/08/23','next-tiny-date'); ?>
            <br><em><font color="#808080"><?php echo esc_html__('Enter your holiday dates in your prefered format (mm/dd/yy or dd/mm/yy).','next-tiny-date') . "<br>";
                                                echo esc_html__('Each day or range of days separated by a coma.','next-tiny-date');?></font></em>
        </td></tr>
        
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Public Holidays','next-tiny-date'); ?></th> 
        <td><input type="text" size="32" maxlength="256" name="optPublicHolidays" placeholder="01/01/23,01/05/23,08/05/23,14/07,23,15/08/23,11/11/23,25/12/23" value="<?php echo esc_attr($opt_PublicHolidays);?>"> <?php esc_html_e('e.g. for Christmas: 12/25/23 or 25/12/23','next-tiny-date'); ?>
            <br><em><font color="#808080"><?php echo esc_html__('Enter each public holiday date in your prefered format (mm/dd/yy or dd/mm/yy) separated by a coma.','next-tiny-date') ?></font></em>
        </td></tr>
        
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Comments on booking dates','next-tiny-date'); ?></th> 
        <td><input type="checkbox" onChange="ntdt_InfoCmt_Change('<?php echo($opt_InfoCmt);?>');" id="optInfoCmt" name="optInfoCmt" value=1 <?php echo($opt_InfoCmt==1?"checked ":"");?>class="wppd-ui-toggle" /> <?php esc_html_e('Check to set information or warning messages on bookings between two dates','next-tiny-date'); ?>
        <tr valign="top">
        <th scope="row"></th> 
        <td>
        <?php
        $tmpVal = ""; if(!$opt_InfoCmt) $tmpVal = "none";
        echo '<div id="divInfoCmt" name="divInfoCmt" style="display:' . $tmpVal . ';">';
        ?>
        <?php esc_html_e('Icon','next-tiny-date'); ?>
        &nbsp;<input type="radio" name="optInfoCmtIcon" value="info-outline" <?php echo($opt_InfoCmtIcon=="info-outline"?"checked ":"");?> /> <span class="dashicons dashicons-info-outline"></span>
        &nbsp;<input type="radio" name="optInfoCmtIcon" value="info" <?php echo($opt_InfoCmtIcon=="info"?"checked ":"");?> /> <span class="dashicons dashicons-info"></span>
        &nbsp;<input type="radio" name="optInfoCmtIcon" value="warning" <?php echo($opt_InfoCmtIcon=="warning"?"checked ":"");?> /> <span class="dashicons dashicons-warning"></span>
        &nbsp;<input type="radio" name="optInfoCmtIcon" value="flag" <?php echo($opt_InfoCmtIcon=="flag"?"checked ":"");?> /> <span class="dashicons dashicons-flag"></span>
        &nbsp;<input type="radio" name="optInfoCmtIcon" value="star-filled" <?php echo($opt_InfoCmtIcon=="star-filled"?"checked ":"");?> /> <span class="dashicons dashicons-star-filled"></span>
        &nbsp;<input type="radio" name="optInfoCmtIcon" value="star-empty" <?php echo($opt_InfoCmtIcon=="star-empty"?"checked ":"");?> /> <span class="dashicons dashicons-star-empty"></span><br>
        <br>
        <?php esc_html_e('Message','next-tiny-date'); ?> <input type="text" size="64" maxlength="256" name="optInfoCmtMsg1" value="<?php echo esc_attr($opt_InfoCmtMsg1);?>"><br>
        <?php esc_html_e('Between','next-tiny-date'); ?> <input type="date" id="optInfoCmtFromDate1" name="optInfoCmtFromDate1" value="<?php echo date($opt_InfoCmtFromDate1); ?>">
        <?php esc_html_e('and','next-tiny-date'); ?> <input type="date" id="optInfoCmtToDate1" name="optInfoCmtToDate1" value="<?php echo date($opt_InfoCmtToDate1); ?>"><br>
        <?php esc_html_e('Icon color','next-tiny-date'); ?> <input type="color" name="optInfoCmtColor1" value="<?php echo esc_attr($opt_InfoCmtColor1); ?>" class="xxx" /><br>
        <br>
        <?php esc_html_e('Message','next-tiny-date'); ?> <input type="text" size="64" maxlength="256" name="optInfoCmtMsg2" value="<?php echo esc_attr($opt_InfoCmtMsg2);?>"><br>
        <?php esc_html_e('Between','next-tiny-date'); ?> <input type="date" id="optInfoCmtFromDate2" name="optInfoCmtFromDate2" value="<?php echo date($opt_InfoCmtFromDate2); ?>">
        <?php esc_html_e('and','next-tiny-date'); ?> <input type="date" id="optInfoCmtToDate2" name="optInfoCmtToDate2" value="<?php echo date($opt_InfoCmtToDate2); ?>"><br>
        <?php esc_html_e('Icon color','next-tiny-date'); ?> <input type="color" name="optInfoCmtColor2" value="<?php echo esc_attr($opt_InfoCmtColor2); ?>" class="xxx" /><br>
        <br>
        <?php esc_html_e('Message','next-tiny-date'); ?> <input type="text" size="64" maxlength="256" name="optInfoCmtMsg3" value="<?php echo esc_attr($opt_InfoCmtMsg3);?>"><br>
        <?php esc_html_e('Between','next-tiny-date'); ?> <input type="date" id="optInfoCmtFromDate3" name="optInfoCmtFromDate3" value="<?php echo date($opt_InfoCmtFromDate3); ?>">
        <?php esc_html_e('and','next-tiny-date'); ?> <input type="date" id="optInfoCmtToDate3" name="optInfoCmtToDate3" value="<?php echo date($opt_InfoCmtToDate3); ?>"><br>
        <?php esc_html_e('Icon color','next-tiny-date'); ?> <input type="color" name="optInfoCmtColor3" value="<?php echo esc_attr($opt_InfoCmtColor3); ?>" class="xxx" /><br>
        <em><font color="#808080"><?php echo esc_html__('Left empty if not used.','next-tiny-date') ?></font></em></div>
        </td></tr>
       
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Send e-mail','next-tiny-date'); ?></th> 
        <td><input type="checkbox" name="optSendMail" value=1 <?php echo($opt_SendMail==1?"checked ":"");?>class="wppd-ui-toggle" /> <?php esc_html_e('Check to send an automatic e-mail to the client after booking','next-tiny-date'); ?>
        </td></tr>
        
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Redirect','next-tiny-date'); ?></th> 
        <td><input type="text" size="32" maxlength="256" name="optRedirect" value="<?php echo esc_attr($opt_Redirect);?>"> <?php esc_html_e('Redirect link to the paiement page after booking','next-tiny-date'); ?>
            <br><em><font color="#808080"><?php echo esc_html__('Left empty if not used.','next-tiny-date') ?></font></em>
        </td></tr>
    </table>

    <h2 class="title"><?php esc_html_e('e-mail','next-tiny-date'); ?></h2>
    <table class="form-table">   
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Sender e-mail','next-tiny-date'); ?></th> 
        <?php if ($opt_Sender == "")
                 $placeholder = get_option('admin_email');
              else
                 $placeholder = $opt_Sender;?>
        <td><input type="email" size="32" maxlength="256" name="optSender" placeholder="<?php echo esc_attr($placeholder);?>" value="<?php echo esc_attr($opt_Sender);?>">
            <br><em><font color="#808080"><?php echo esc_html__('Enter the sender email. (If empty,','next-tiny-date') . " " . get_option('admin_email') . " " . esc_html__('will be used.)','next-tiny-date'); ?></font></em>
        </td></tr>

        <tr valign="top">
        <th scope="row"><?php esc_html_e('Sender signature','next-tiny-date'); ?></th> 
        <?php if ($opt_Firm == "")
                 $placeholder = get_option('siteurl');
              else
                 $placeholder = $opt_Firm;?>
        <td><input type="text" size="32" maxlength="256" name="optFirm" placeholder="<?php echo esc_attr($placeholder);?>" value="<?php echo esc_attr($opt_Firm);?>">
            <br><em><font color="#808080"><?php echo esc_html__('Enter the sender signature. (If empty,','next-tiny-date') . " " . get_option('siteurl') . " " . esc_html__('will be used.)','next-tiny-date'); ?></font></em>
        </td></tr>
    </table>    

    <h2 class="title"><?php esc_html_e('Export','next-tiny-date'); ?></h2>
    <table class="form-table">   
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Appointments only','next-tiny-date'); ?></th> 
        <td><input type="checkbox" name="optOnlyRV" value=1 <?php echo($opt_OnlyRV==1?"checked ":"");?>class="wppd-ui-toggle" /> <?php esc_html_e('Check to export only booked appointments','next-tiny-date'); ?>
            <br><em><font color="#808080"><?php esc_html_e('Enable to export only booked appointments instead of full daily planning.','next-tiny-date'); ?></font></em>
        </td></tr>
    </table>       
    <?php submit_button(esc_html__('Save','next-tiny-date')); ?>
</form>
                       <?php break;


                  
                  
                  
             case 'hours':?>
                  <h1 class="screen-reader-text">Toolbox</h1>
		              <ul class="subsubsub"><li><a href="/wp-admin/admin.php?page=ntdt-acp&amp;tab=ntdt_settings&amp;section=settings" class=""><?php esc_html_e('Settings','next-tiny-date'); ?></a> | </li>
		                                    <li><a href="/wp-admin/admin.php?page=ntdt-acp&amp;tab=ntdt_settings&amp;section=hours" class="current"><?php esc_html_e('Opening hours','next-tiny-date'); ?></a> </li>
		              </ul><br class="clear">

    <form method="post" action="options.php">
    <?php settings_fields('ntdt-hours-group'); ?>
    <?php do_settings_sections('ntdt-hours-group'); ?>
    
    <h2 class="title"><?php esc_html_e('Opening hours','next-tiny-date'); ?> <span class="dashicons dashicons-clock"></span></h2>
    <table class="form-table" border="0">
    <?php
    for($i=0;$i<=6;$i++)
       { $opt_StartHourAM = get_option('optStartHourAM_' . $i); if ($opt_StartHourAM == "") $opt_StartHourAM = "08";
         $opt_StartMinAM = get_option('optStartMinAM_' . $i); if ($opt_StartMinAM == "") $opt_StartMinAM = "00";
         $opt_EndHourAM = get_option('optEndHourAM_' . $i); if ($opt_EndHourAM == "") $opt_EndHourAM = "12";
         $opt_EndMinAM = get_option('optEndMinAM_' . $i); if ($opt_EndMinAM == "") $opt_EndMinAM = "00";
         
         $opt_StartHourPM = get_option('optStartHourPM_' . $i); if ($opt_StartHourPM == "") $opt_StartHourPM = "14";
         $opt_StartMinPM = get_option('optStartMinPM_' . $i); if ($opt_StartMinPM == "") $opt_StartMinPM = "00";
         $opt_EndHourPM = get_option('optEndHourPM_' . $i); if ($opt_EndHourPM == "") $opt_EndHourPM = "18";
         $opt_EndMinPM = get_option('optEndMinPM_' . $i); if ($opt_EndMinPM == "") $opt_EndMinPM = "00";
         
         $opt_OpenAM = get_option('optOpenAM_' . $i,'0');
         $opt_OpenPM = get_option('optOpenPM_' . $i,'0');
         $opt_Closed = get_option('optClosed_' . $i,'1');

         $opt_Sender = get_option('optSender');
         $opt_Firm = get_option('optFirm');
         $opt_OnlyRV = get_option('optOnlyRV');
         
         if ($opt_OpenAM)
            { if ($opt_StartHourAM > $opt_EndHourAM)
                 { ?>
                   <div class="notice notice-warning is-dismissible">
                   <p><?php echo esc_attr(ntdt_GetDayName($i)) . " AM: " . esc_attr($opt_StartHourAM) . ":" . esc_attr($opt_StartMinAM) .  " " . esc_html__('is before','next-tiny-date') . " " . esc_attr($opt_EndHourAM) . ":" . esc_attr($opt_EndMinAM) . " !"; ?>
                   </div>
                   <?php
                 }
            }
         if (($opt_OpenAM) and ($opt_OpenPM))
            { if ($opt_EndHourAM > $opt_StartHourPM)
                 { ?>
                   <div class="notice notice-warning is-dismissible">
                   <p><?php echo esc_attr(ntdt_GetDayName($i)) . " AM/PM: " . esc_attr($opt_EndHourAM) . ":" . esc_attr($opt_EndMinAM) .  " " . esc_html__('is before','next-tiny-date') . " " . esc_attr($opt_StartHourPM) . ":" . esc_attr($opt_StartMinPM) . " !"; ?>
                   </div>
                   <?php
                 }
            }
         if ($opt_OpenPM)
            { if ($opt_StartHourPM > $opt_EndHourPM)
                 { ?>
                   <div class="notice notice-warning is-dismissible">
                   <p><?php echo esc_attr(ntdt_GetDayName($i)). " PM: " . esc_attr($opt_StartHourPM) . ":" . esc_attr($opt_StartMinPM) .  " " . esc_html__('is before','next-tiny-date') . " " . esc_attr($opt_EndHourPM) . ":" . esc_attr($opt_EndMinPM) . " !"; ?>
                   </div>
                   <?php
                 }
            }
         
         echo '<tr valign="top">';
         echo '<th scope="row">' . esc_attr(ntdt_GetDayName($i));
         echo '<br><br><input type="checkbox" onchange="ntdt_optClosed_Change(' . $i . ',this.checked);" id="optClosed_' . $i . '" name="optClosed_' . $i . '" value=1 ' . ($opt_Closed==1?"checked ":"") . 'class="wppd-ui-toggle" /> ' . esc_html__('Closed','next-tiny-date');
         echo '</th>';
         echo '<td>';
         
         echo '<table border="0"><tr><td>';
         $tmpState = ($opt_OpenAM?"number":"hidden");
         echo '<input type="' . esc_attr($tmpState) . '" size="2" min="0" max= "23" id="optStartHourAM_' . esc_attr($i) . '" name="optStartHourAM_' . esc_attr($i) . '" value="' . esc_attr($opt_StartHourAM) . '">:';
         echo '<input type="' . esc_attr($tmpState) . '" size="2" min="0" max= "59" id="optStartMinAM_' . esc_attr($i) . '"  name="optStartMinAM_' . esc_attr($i) . '" value="' . esc_attr($opt_StartMinAM) . '"> - ';
         echo '<input type="' . esc_attr($tmpState) . '" size="2" min="0" max= "23" id="optEndHourAM_' . esc_attr($i) . '"  name="optEndHourAM_' . esc_attr($i) . '" value="' . esc_attr($opt_EndHourAM) . '">:';
         echo '<input type="' . esc_attr($tmpState) . '" size="2" min="0" max= "59" id="optEndMinAM_' . esc_attr($i) . '"  name="optEndMinAM_' . esc_attr($i) . '" value="' . esc_attr($opt_EndMinAM) . '"> ';
         echo '<br><input type="checkbox" onchange="ntdt_optOpenAM_Change(' . esc_attr($i) . ',this.checked);" id="optOpenAM_' . esc_attr($i) . '" name="optOpenAM_' . esc_attr($i) . '" value=1 ' . ($opt_OpenAM==1?"checked ":"") . 'class="wppd-ui-toggle" /> ' . esc_html__('Open morning','next-tiny-date');
         echo '</td><td>';
         $tmpState = ($opt_OpenPM?"number":"hidden");
         echo '<input type="' . esc_attr($tmpState) . '" size="2" min="0" max= "23" id="optStartHourPM_' . esc_attr($i) . '"  name="optStartHourPM_' . esc_attr($i) . '" value="' . esc_attr($opt_StartHourPM) . '">:';
         echo '<input type="' . esc_attr($tmpState) . '" size="2" min="0" max= "59" id="optStartMinPM_' . esc_attr($i) . '"  name="optStartMinPM_' . esc_attr($i) . '" value="' . esc_attr($opt_StartMinPM) . '"> - ';
         echo '<input type="' . esc_attr($tmpState) . '" size="2" min="0" max= "23" id="optEndHourPM_' . esc_attr($i) . '"  name="optEndHourPM_' . esc_attr($i) . '" value="' . esc_attr($opt_EndHourPM) . '">:';
         echo '<input type="' . esc_attr($tmpState) . '" size="2" min="0" max= "59" id="optEndMinPM_' . esc_attr($i) . '"  name="optEndMinPM_' . esc_attr($i) . '" value="' . esc_attr($opt_EndMinPM) . '"> ';
         echo '<br><input type="checkbox" onchange="ntdt_optOpenPM_Change(' . esc_attr($i) . ',this.checked);" id="optOpenPM_' . esc_attr($i) . '" name="optOpenPM_' . esc_attr($i) . '" value=1 ' . ($opt_OpenPM==1?"checked ":"") . 'class="wppd-ui-toggle" /> ' . esc_html__('Open afternoon','next-tiny-date');
         echo '</td></tr></table>';
         
         echo '</td></tr>';
       }
?>
    </table>
    <?php submit_button(esc_html__('Save','next-wc-product-toolbox')); ?>
</form>

    <?php
    break;   
      }
        
         break;
         
         
 case 'ntdt_view': ?> 
    <form method="post" action="options.php">
    <?php settings_fields('ntdt-view-group'); ?>
    <?php do_settings_sections('ntdt-view-group'); ?>

    <h2 class="title"><?php esc_html_e('View appointments','next-tiny-date');?> <span class="dashicons dashicons-visibility"></span></h2>

<table border="0">      
<tr><td valign="top">
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Pick a date','next-tiny-date'); ?></th> 
        <td align="center">
         <input class="js-ntdt-dates" data-action="ntdt_ShowRV"
         data-nonce="<?php echo wp_create_nonce('ntdt_ShowRV'); ?>"
         data-date="000"
         data-ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>"
         type="date" id="ntdt_datepicker" name="ntdt_datepicker" value="<?php echo date("Y-m-d"); ?>">
         <br>
         <button
	       class="js-date-before"
         data-nonce="<?php echo wp_create_nonce('ntdt_ShowRV'); ?>"
         data-action="ntdt_ShowRV"
         data-date="111"
         data-ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>"
         ><</button>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         <button
	       class="js-date-after"
         data-nonce="<?php echo wp_create_nonce('ntdt_ShowRV'); ?>"
         data-date="999"
         data-action="ntdt_ShowRV"
         data-ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>"
         >></button>
        </td></tr>
    </table> 
</td>
<td valign="top">
    <ol class="ntdt-RV">
    <?php
    $tmpTodayDate = date("Ymd");
    $tmpSQL = $wpdb->prepare("SELECT DayRVs FROM $TableName WHERE id = %d",$tmpTodayDate);
    $strData = $wpdb->get_var($tmpSQL);

    $opt_FormatDateRV = get_option('optFormatDateRV',0);
    switch ($opt_FormatDateRV)
           { case 0: $tmpDateFormat = "d M Y"; break;
             case 1: $tmpDateFormat = "M d, Y"; break;
           }
         
    $tmpDayNum = date("w");
    echo "<b>" . esc_attr(ntdt_GetDayName($tmpDayNum)) . " " . date($tmpDateFormat) . "</b><br>";
    
    if ($strData != "")
       { $htmlTableRV = ntdt_GetRV($tmpTodayDate,$tmpDayNum);
       }
    else
       { $htmlTableRV = ntdt_GetNoRV($tmpTodayDate,$tmpDayNum);
       }
    
    $A_htmlRV = array(
        'b'          => array(),
        'br'         => array(),
        'p'          => array(),
        'span'       => array(
        'id'         => true,
        ),
        'em'           => array(),
        'button'       => array(
            'disabled' => true,
            'name'     => true,
            'type'     => true,
            'value'    => true,
            'image'    => true,
            'title'    => true,
            'id'       => true,
            'onclick'  => true,
            
        ),
        'form'         => array(
            'method'   => true,
        ),
        'a'            => array(
            'href'     => true,
        ),        
        'image'        => array(
            'alt'      => true,
            'id'       => true,
            'src'      => true,
            'height'   => true,
            'width'    => true,
        ),
        'img'          => array(
            'alt'      => true,
            'align'    => true,
            'border'   => true,
            'height'   => true,
            'hspace'   => true,
            'longdesc' => true,
            'vspace'   => true,
            'src'      => true,
            'usemap'   => true,
            'width'    => true,
        ),
        'input'        => array(
            'type'     => true,
            'id'       => true,
            'name'     => true,
            'value'    => true,
        ),
        'table'      => array(
            'name'        => true,
            'id'          => true,
            'align'       => true,
            'bgcolor'     => true,
            'border'      => true,
            'cellpadding' => true,
            'cellspacing' => true,
            'dir'         => true,
            'rules'       => true,
            'class'       => true,
            'summary'     => true,
            'width'       => true,
        ),
        'tbody'      => array(
            'align'   => true,
            'char'    => true,
            'charoff' => true,
            'valign'  => true,
        ),
        'td'         => array(
            'abbr'    => true,
            'align'   => true,
            'axis'    => true,
            'bgcolor' => true,
            'char'    => true,
            'charoff' => true,
            'colspan' => true,
            'dir'     => true,
            'headers' => true,
            'height'  => true,
            'nowrap'  => true,
            'rowspan' => true,
            'scope'   => true,
            'valign'  => true,
            'width'   => true,
            'style'   => true,
        ),
        'th'         => array(
            'abbr'    => true,
            'align'   => true,
            'axis'    => true,
            'bgcolor' => true,
            'char'    => true,
            'charoff' => true,
            'colspan' => true,
            'headers' => true,
            'height'  => true,
            'nowrap'  => true,
            'rowspan' => true,
            'scope'   => true,
            'valign'  => true,
            'width'   => true,
            'style'   => true,
        ),
        'title'      => array(),
        'tr'         => array(
            'align'   => true,
            'bgcolor' => true,
            'char'    => true,
            'charoff' => true,
            'valign'  => true,
            'style'  => true,
        ),
    );
    echo wp_kses($htmlTableRV,$A_htmlRV);
    ?>    
    </ol>
</td>
</table>

</form>
<form method="post" action="admin.php?page=ntdt-acp">
<input type="submit" name="do" value="<?php esc_html_e('Export','next-tiny-date'); ?>" title="<?php esc_html_e('Export appointments in a .CSV file','next-tiny-date'); ?>" class="button" />
<input type="submit" name="do" value="<?php esc_html_e('Clean DB','next-tiny-date'); ?>" title="<?php esc_html_e('Remove old appointments before today','next-tiny-date'); ?>" class="button" />
</form>

    <?php 
    break;
    
    case 'ntdt_stats': ?> 
    <h2 class="title"><?php esc_html_e('Stats','next-tiny-date'); ?></h2>
    
  <?php
  global $wpdb;
  $TableName = $wpdb->prefix . NTDT_PLUGIN_TABLE;

  $tmpSQL = $wpdb->prepare("SELECT id, DayRVs FROM $TableName ORDER BY id ASC");
  $retDayRVs = $wpdb->get_results($tmpSQL);
  $NbDayRVs = count($retDayRVs);
  $indReason = 0;    
  $NbTotalReasons = 0;  
  
  $NbTotalDays = 0;  
  for ($d=0;$d<=6;$d++)
      $A_DayNum[$d] = 0;
      
  for ($i=0;$i<$NbDayRVs;$i++)
      { $tmpLineAllRV = $retDayRVs[$i]->DayRVs;
      
        if (strpos($tmpLineAllRV,"=>"))
           { $tmpLineAllRV = str_replace('&amp;','&',$tmpLineAllRV);
             $listAllRV = explode(';',$tmpLineAllRV);
             $NbRV = count($listAllRV);

             $tmpCurDate = $retDayRVs[$i]->id;
             $tmpYear = substr($tmpCurDate,0,4);
             $tmpMonth = substr($tmpCurDate,4,2);
             $tmpDay = substr($tmpCurDate,6,2);
             $tmpDate = date_create();
             date_date_set($tmpDate,$tmpYear,$tmpMonth,$tmpDay);
             $tmpDayNum = date_format($tmpDate,"w");
             $tmpDate = date_format($tmpDate,"d-M-Y"); 
             $A_DayNum[$tmpDayNum]++; $NbTotalDays++;
             
             for($rv=0;$rv<$NbRV;$rv++)
                { $tmpLineOneRV = trim($listAllRV[$rv]);
                  if (substr($tmpLineOneRV,0,2) != "//")
                     { $HourRV = substr($tmpLineOneRV,0,5);
                       $tmpLineOneRV = substr($tmpLineOneRV,5);
                       if ($tmpLineOneRV != "")
                          { $listOnePatient = explode('=>',trim($tmpLineOneRV));
                            if (trim($listOnePatient[0]) != "")
                               { $PatientRV = substr(trim($listOnePatient[0]),1);
                                 $InfoPatient = trim($listOnePatient[1]);
                                 $listInfoPatient = explode('#',trim($InfoPatient));
                                 $ReasonRV = trim($listInfoPatient[0]);
                                 if (in_array($ReasonRV, $A_Reasons))
                                    { for ($r=0;$r<$indReason;$r++)
                                          if ($A_Reasons[$r] == $ReasonRV)
                                             $A_NbReasons[$r]++;
                                    }
                                 else
                                    { $A_Reasons[$indReason] = $ReasonRV;
                                      $A_NbReasons[$indReason] = 1;
                                      $indReason++;
                                    }
                                 $NbTotalReasons++;
                               }
                            else
                               { if (!$opt_OnlyRV)
                                    { $nop=0;
                                    }
                               }
                          }
                       else
                          { if (!$opt_OnlyRV) $nop=0;
                          }
                     }
                }
           }
      }
  
  for ($d=0;$d<=6;$d++)
      $A_DayName[$d] = ntdt_GetDayName($d);
    ?>
    
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Appointments per day','next-tiny-date'); ?></th> 
        <td>
        <?php ntdt_Pie3D($A_DayNum,$A_DayName,$NbTotalDays);?>
        </td></tr>

        <tr valign="top">
        <th scope="row"><?php esc_html_e('Appointments per reason','next-tiny-date'); ?></th> 
        <td>
        <?php ntdt_StatsBar($A_NbReasons,$A_Reasons,"REASON");?>
        </td></tr>
    </table>
         
    <?php
    break;

    case 'ntdt_help': ?> 
    <h2 class="title"><?php esc_html_e('Shortcode','next-tiny-date'); ?></h2>
    
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Example','next-tiny-date'); ?></th> 
        <td>[next_tiny_date]
            <br><em><font color="#808080"><?php esc_html_e('Insert the shortcode into a widget or a page to display the appointment booking form','next-tiny-date'); ?></font></em><br>
        </td></tr>
    </table>
         
    <?php
    break;

    default:
    break;
        } ?>
  </div>
</div>