<?php
if (!defined('NTDT_KEY_DONATE'))
   { define('NTDT_KEY_DONATE','Y6SDT3BDBPB3C');
   }
if (!defined('NTDT_PLUGIN_NAME'))
   { define('NTDT_PLUGIN_NAME','Next Tiny Date');
   }
if (!defined('NTDT_PLUGIN_SLUG'))
   { define('NTDT_PLUGIN_SLUG','next-tiny-date');
   }
if (!defined('NTDT_PLUGIN_PAGE'))
   { define('NTDT_PLUGIN_PAGE','ntdt-acp');
   }
if (!defined('NTDT_PLUGIN_TABLE'))
   { define('NTDT_PLUGIN_TABLE','ntdtRV');
   }
   
global $wpdb;
global $opt_VersionDB;
$opt_VersionDB = '1.1';

add_action('admin_enqueue_scripts', 'ntdt_Styles');
function ntdt_Styles()
{ $tmpStr = plugins_url('/',__FILE__);
  if (substr($tmpStr,-1) == "/")
     $tmpPos = strrpos($tmpStr,'/',-2);
  else   
     $tmpPos = strrpos($tmpStr,'/',-1);
  $tmpStr = substr($tmpStr,0,$tmpPos);
  $tmpPathCSS = $tmpStr . '/css/style.css';

  wp_enqueue_style('ntdt_style_css', $tmpPathCSS);
}

//Add Dashicons in WordPress Front-end:
add_action('wp_enqueue_scripts','ntdt_load_dashicons_front_end' );
function ntdt_load_dashicons_front_end()
{ wp_enqueue_style('dashicons');
}

function ntdt_BuildCSS()
{ $tmpStr = plugin_dir_path( __FILE__ );
  if (substr($tmpStr,-1) == "/")
     $tmpPos = strrpos($tmpStr,'/',-2);
  else   
     $tmpPos = strrpos($tmpStr,'/',-1);
  $tmpStr = substr($tmpStr,0,$tmpPos);
  $tmpPathCSS = $tmpStr . '/css/styleRV.css';

  $opt_BtnBg = get_option('optBtnBg','#2271B1');
  $opt_BtnBgHover = get_option('optBtnBgHover','#40FFFF');
  $opt_BtnCol = get_option('optBtnCol','#FFFFFF');
  $opt_BtnColHover = get_option('optBtnColHover','#FFFFFF');
  
  $fd = fopen($tmpPathCSS, "w");

  fwrite($fd,".btnRV {\n");
  fwrite($fd,"background-color: " . $opt_BtnBg . ";\n");
  fwrite($fd,"border: none;\n");
  fwrite($fd,"color: " . $opt_BtnCol . ";\n");
  fwrite($fd,"padding: 8px 8px;\n");
  fwrite($fd,"text-align: center;\n");
  fwrite($fd,"text-decoration: none;\n");
  fwrite($fd,"display: inline-block;\n");
  fwrite($fd,"font-size: 14px;\n");
  fwrite($fd,"margin: 2px 2px;\n");
  fwrite($fd,"cursor: pointer;\n");
  fwrite($fd,"border-radius: 4px;\n");
  fwrite($fd,"-webkit-transition-duration: 0.4s;\n"); //Safari
  fwrite($fd,"transition-duration: 0.4s;\n");
  fwrite($fd,"box-shadow: 0 5px 5px 0 rgba(0,0,0,0.4), 0 6px 6px 0 rgba(0,0,0,0.19);\n");
  fwrite($fd,"}\n");

  fwrite($fd,".btnRV:hover {\n");
  fwrite($fd,"background-color: " . $opt_BtnBgHover . ";\n");
  fwrite($fd,"color: " . $opt_BtnColHover . ";\n");
  fwrite($fd,"box-shadow: 0 0px 0px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);\n");
  fwrite($fd,"}\n");
      
  fclose($fd);
}
add_action('updated_option','ntdt_BuildCSS', 10, 3);

function ntdt_CheckVersion()
{ $tmpCurVersion = get_option('ntdtCurrentVersion');
  $tmpCurType = get_option('ntdtCurrentType');
  if((version_compare($tmpCurVersion, NTDT_VERSION, '<')) or (NTDT_TYPE !== $tmpCurType))
    { ntdt_PluginActivation();
    }
}
add_action('plugins_loaded', 'ntdt_checkVersion');

add_action('admin_menu','ntdt_Add_Menu');
function ntdt_Add_Menu()
{ add_menu_page(
      'Next Tiny Date',
      NTDT_PLUGIN_NAME,
      'manage_options',
      'ntdt-acp',
      'ntdt_acp_callback',
      'dashicons-calendar-alt'
    );
  
  add_submenu_page('ntdt-acp', __('Planning','next-tiny-date'), __('Planning','next-tiny-date'), 'manage_options', 'ntdt-acp&tab=ntdt_view', 'render_generic_settings_page');
  add_submenu_page('ntdt-acp', __('General Settings','next-tiny-date'), __('General Settings','next-tiny-date'), 'manage_options', 'ntdt-acp&tab=ntdt_settings', 'render_generic_settings_page');
  add_submenu_page('ntdt-acp', __('Stats','next-tiny-date'), __('Stats','next-tiny-date'), 'manage_options', 'ntdt-acp&tab=ntdt_stats', 'render_generic_settings_page');
  add_submenu_page('ntdt-acp', __('Help','next-tiny-date'), __('Help','next-tiny-date'), 'manage_options', 'ntdt-acp&tab=ntdt_help', 'render_generic_settings_page');

	add_action('admin_init','register_ntdt_settings');  
}

add_action('init','ntdt_load_textdomain');
function ntdt_load_textdomain()
{ load_plugin_textdomain('next-tiny-date',false,NTDT_PLUGIN_SLUG . '/languages/'); 
}

function register_ntdt_settings()
{ register_setting('ntdt-settings-group','ntdtCurrentVersion');
  register_setting('ntdt-settings-group','ntdtCurrentType');
  
  register_setting('ntdt-settings-group','optPathRV');
  register_setting('ntdt-settings-group','optReadDataRV');
  register_setting('ntdt-settings-group','optSendMail');
  register_setting('ntdt-settings-group','optRedirect');
  register_setting('ntdt-settings-group','optSender');
  register_setting('ntdt-settings-group','optFirm');
  register_setting('ntdt-settings-group','optOnlyRV');
  
  for($i=0;$i<=6;$i++)
     { register_setting('ntdt-hours-group','optStartHourAM_'.$i); 
       register_setting('ntdt-hours-group','optStartMinAM_'.$i);
       register_setting('ntdt-hours-group','optEndHourAM_'.$i);
       register_setting('ntdt-hours-group','optEndMinAM_'.$i);
       register_setting('ntdt-hours-group','optStartHourPM_'.$i); 
       register_setting('ntdt-hours-group','optStartMinPM_'.$i);
       register_setting('ntdt-hours-group','optEndHourPM_'.$i);
       register_setting('ntdt-hours-group','optEndMinPM_'.$i);
       
       register_setting('ntdt-hours-group','optOpenAM_'.$i);
       register_setting('ntdt-hours-group','optOpenPM_'.$i);
       register_setting('ntdt-hours-group','optClosed_'.$i);
     }
  register_setting('ntdt-settings-group','optStepRV');
  register_setting('ntdt-settings-group','optDayTillRV');
  register_setting('ntdt-settings-group','optEnabled');
  register_setting('ntdt-settings-group','optBooked');
  register_setting('ntdt-settings-group','optDisabled');
  register_setting('ntdt-settings-group','optReason');
  register_setting('ntdt-settings-group','optBtnBg');
  register_setting('ntdt-settings-group','optBtnCol');
  register_setting('ntdt-settings-group','optBtnBgHover');
  register_setting('ntdt-settings-group','optBtnColHover');
  register_setting('ntdt-settings-group','optFormatDateRV');
  register_setting('ntdt-settings-group','optPublicHolidays');
  register_setting('ntdt-settings-group','optUserHolidays');
  register_setting('ntdt-settings-group','optNbHourButtons');
  register_setting('ntdt-settings-group','optInfoCmt');
  register_setting('ntdt-settings-group','optInfoCmtIcon');
  register_setting('ntdt-settings-group','optInfoCmtMsg1');
  register_setting('ntdt-settings-group','optInfoCmtFromDate1');
  register_setting('ntdt-settings-group','optInfoCmtToDate1');
  register_setting('ntdt-settings-group','optInfoCmtColor1');
  register_setting('ntdt-settings-group','optInfoCmtMsg2');
  register_setting('ntdt-settings-group','optInfoCmtFromDate2');
  register_setting('ntdt-settings-group','optInfoCmtToDate2');
  register_setting('ntdt-settings-group','optInfoCmtColor2');
  register_setting('ntdt-settings-group','optInfoCmtMsg3');
  register_setting('ntdt-settings-group','optInfoCmtFromDate3');
  register_setting('ntdt-settings-group','optInfoCmtToDate3');
  register_setting('ntdt-settings-group','optInfoCmtColor3');
}

function ntdt_acp_callback()
{ global $title;

  if (!current_user_can('administrator'))
     { wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	   }
	
  print '<div class="wrap">';
  print "<h1 class=\"stabilo\">$title</h1><hr>";

  $file = plugin_dir_path( __FILE__ ) . "ntdt-acp-page.php";
  if (file_exists($file))
      require $file;

  //echo "<p><em><b>" . esc_html__('Add for free nice other','next-tiny-date') . " <a target=\"_blank\" href=\"https://nxt-web.com/wordpress-plugins/\" style=\"color:#FE5500;font-weight:bold;font-size:1.2em\">" . esc_html__('Plugins for Wordpress','next-tiny-date') . "</a></b></em></p>";
  echo "<p><em><b>" . esc_html__('You like this plugin?','next-tiny-date') . " <a target=\"_blank\" href=\"https://www.paypal.com/donate/?hosted_button_id=" . NTDT_KEY_DONATE . "\" style=\"color:#FE5500;font-weight:bold;font-size:1.2em\">" . esc_html__('Offer me a coffee!','next-tiny-date') . "</a></b></em>";
  $CoffeePath = plugin_dir_url( dirname( __FILE__ ) )  . '/images/coffee-donate.gif';
  echo '&nbsp;<img src="' . esc_attr($CoffeePath) . '"></p>';
  
  echo "<p><em><b>" . esc_html__('Need more?','next-tiny-date') . " <a target=\"_blank\" href=\"https://nxt-web.com/wordpress-plugins-pro\" style=\"color:#FE5500;font-weight:bold;font-size:1.2em\">" . esc_html__('Go Pro!','next-tiny-date') . "</a></b></em></p>";  
  esc_html_e('Next Tiny Date PRO allows you to manage several people (employees, ..) in several places, so each one can have personal settings for a custom schedule.','next-tiny-date');
  print '</div>';  
}

add_action("admin_enqueue_scripts", "ntdt_add_script_upload");
function ntdt_add_script_upload()
{	wp_enqueue_media();

  wp_register_script('ntdt_date', plugins_url('/',__DIR__).'js/ntdt_date.js', array('jquery'), '1', true );
  
  wp_enqueue_script('ntdt_date');
}

function ntdt_ShowRV()
{	global $wpdb;
  $TableName = $wpdb->prefix . NTDT_PLUGIN_TABLE;

  $tmpNonce = $_REQUEST['nonce'];
  if ((!isset($tmpNonce)) or (!wp_verify_nonce($tmpNonce,'ntdt_ShowRV')))
     {	wp_send_json_error("You cannot do that!",403);
  	 }
    
  if (!isset($_POST['date'])) 
     {	wp_send_json_error("Missing date!", 403 );
  	 }

  $postCurDate = intval(sanitize_text_field($_POST['date']));
  $tmpCurDate = $postCurDate;
  $tmpYear = substr($tmpCurDate,-4); $tmpCurDate = substr($tmpCurDate,0,-4);
  $tmpMonth = substr($tmpCurDate,-2); $tmpCurDate = substr($tmpCurDate,0,-2);
  $tmpDay = sprintf("%02d",$tmpCurDate);
  $tmpDate = date_create();
  date_date_set($tmpDate,$tmpYear,$tmpMonth,$tmpDay);
  $tmpDayNum = date_format($tmpDate,"w");
  $strValDate = $tmpYear . $tmpMonth . $tmpDay;

  $tmpSQL = $wpdb->prepare("SELECT DayRVs FROM $TableName WHERE id = %d",$strValDate);
  $strData = $wpdb->get_var($tmpSQL);
  
 $opt_FormatDateRV = get_option('optFormatDateRV',0);
  switch ($opt_FormatDateRV)
         { case 0: $tmpDateFormat = "d M Y"; break;
           case 1: $tmpDateFormat = "M d, Y"; break;
         }
         
  $html .= "<b>" . ntdt_GetDayName($tmpDayNum) . " " . date_format($tmpDate,$tmpDateFormat) . "</b><br>";
  if ($strData != "")
     { $html .= ntdt_GetRV($strValDate,$tmpDayNum);
     }
  else
     { $html .= ntdt_GetNoRV($strValDate,$tmpDayNum);
     }

	wp_send_json_success($html);
}
add_action('wp_ajax_ntdt_ShowRV','ntdt_ShowRV');
add_action('wp_ajax_nopriv_ntdt_ShowRV','ntdt_ShowRV');

function ntdt_IsHolidays($pardate) //'Ymd' format
{ $opt_UserHolidays = get_option('optUserHolidays');
  //e.g.: 01/07/23-15/07/23,30/07/23,15/08/23-31/08/23
  
  $tmpUserHolidays = str_replace(' ','',$opt_UserHolidays);
  $opt_FormatDateRV = get_option('optFormatDateRV',0);

  $tmpY = substr($pardate,2,2);
  $tmpM = substr($pardate,4,2);
  $tmpD = substr($pardate,6,2);
  $tmpStrdate = "$tmpM/$tmpD/$tmpY";
  $tmpDate = date_create($tmpStrdate);
  
  $lstHolidays = explode(',',$tmpUserHolidays);
  $NbRangeHolidays = count($lstHolidays);

  for ($i=0;$i<$NbRangeHolidays;$i++)
      { $lstRange = explode('-',$lstHolidays[$i]);

        if ($lstRange[0] == $lstHolidays[$i]) //Only day
           { if ($opt_FormatDateRV == 0) //dd/mm/yy
                { $LstBegin = explode('/',$lstRange[0]); $tmpStrBegin = $LstBegin[1] . "/" . $LstBegin[0] . "/" . $LstBegin[2];
                }
             else //mm/dd/yy
                { $tmpStrBegin = $lstRange[0];
                }
                
             $tmpStrEnd = $tmpStrBegin;
           }
        else //Range of days
           { if ($opt_FormatDateRV == 0) //dd/mm/yy
                { $LstBegin = explode('/',$lstRange[0]); $tmpStrBegin = $LstBegin[1] . "/" . $LstBegin[0] . "/" . $LstBegin[2];
                  $LstEnd = explode('/',$lstRange[1]);   $tmpStrEnd = $LstEnd[1] . "/" . $LstEnd[0] . "/" . $LstEnd[2];
                }
             else //mm/dd/yy
                { $tmpStrBegin = $lstRange[0];
                  $tmpStrEnd = $lstRange[1];
                }
           }
             
        $tmpBeginDate = date_create($tmpStrBegin);
        $tmpEndDate = date_create($tmpStrEnd);
        if (($tmpDate >= $tmpBeginDate) && ($tmpDate <= $tmpEndDate))
           { return true;
           }
      }
  return false;
}

function ntdt_IsFeried($pardate) //'Ymd' format
{ $opt_PublicHolidays = get_option('optPublicHolidays');
  $opt_FormatDateRV = get_option('optFormatDateRV',0);

  $tmpY = substr($pardate,2,2);
  $tmpM = substr($pardate,4,2);
  $tmpD = substr($pardate,6,2);

  if ($opt_FormatDateRV == 0) //dd/mm/yy 
     $tmpStrdate = "$tmpD/$tmpM/$tmpY";
  else //mm/dd/yy
     $tmpStrdate = "$tmpM/$tmpD/$tmpY";
  
  $tmpPos = strpos($opt_PublicHolidays,$tmpStrdate);
  if ($tmpPos === false)
     return false;
  else
     return true;
}

function ntdt_GetRV($parDayRVs,$parDayNum)
{ global $wpdb;
  $TableName = $wpdb->prefix . NTDT_PLUGIN_TABLE;

  $opt_Enabled = get_option('optEnabled','#80FF80');
  $opt_Booked = get_option('optBooked','#FDCB55');
  $opt_Disabled = get_option('optDisabled','#FF8080');

  $retHTML = "";
  $retHTML .= '<table name="tableRV" id="tableRV" class="form-table" border="1">';
  $retHTML .= "<tr style=\"color: #ffffff; background: #2271B1;\"><th style=\"text-align:center\">" . esc_html__('Time','next-tiny-date') . "</th><th style=\"text-align:center\">" . esc_html__('Name','next-tiny-date') . "</th><th style=\"text-align:center\">" . esc_html__('Reason','next-tiny-date') . "</th><th style=\"text-align:center\">" . esc_html__('Phone number','next-tiny-date') . "</th><th style=\"text-align:center\">" . esc_html__('e-mail','next-tiny-date') . "</th><th>&nbsp;</th></tr>";

  $tmpSQL = $wpdb->prepare("SELECT DayRVs FROM $TableName WHERE id = %s",$parDayRVs);
  $tmpLineAllRV = trim($wpdb->get_var($tmpSQL));
  $tmpLineAllRV = str_replace('&amp;','&',$tmpLineAllRV);
  $listAllRV = explode(';',$tmpLineAllRV);
  $NbRV = count($listAllRV);
  $tmpIndTD = 0;
  for($rv=0;$rv<$NbRV;$rv++)
     { $tmpLineOneRV = trim($listAllRV[$rv]);
       $tmpIndTR = $rv + 1;
       
       if (substr($tmpLineOneRV,0,2)=="//")
          { $retHTML .= "<tr><td colspan=\"6\">" . esc_html__('Day break','next-tiny-date') . "</td></tr>"; 
            $tmpIndTD++;
          }
       else
          {
       
       $HourRV = substr($tmpLineOneRV,0,5);
       $tmpLineOneRV = substr($tmpLineOneRV,5);
       if ($tmpLineOneRV != "")
          { $listOnePatient = explode('=>',trim($tmpLineOneRV));
            if (trim($listOnePatient[0]) != "")
               { $retHTML .= "<tr bgcolor=\"" .  esc_attr($opt_Booked) . "\"><td>" . esc_attr($HourRV) . "</td>";
                 $PatientRV = substr(trim($listOnePatient[0]),1); 
                 $retHTML .= "<td><b>" . esc_attr($PatientRV) . "</b></td>";
                 $InfoPatient = trim($listOnePatient[1]);
                 $listInfoPatient = explode('#',trim($InfoPatient));
                 $ReasonRV = trim($listInfoPatient[0]); $retHTML .= "<td><em>" . esc_attr($ReasonRV) . "</em></td>";
                 $TelRV = trim($listInfoPatient[1]); $retHTML .= "<td><em>" . esc_attr($TelRV) . "</em></td>";
                 $EmailRV = trim($listInfoPatient[2]); $retHTML .= "<td><em><a href=\"mailto:" . esc_attr($EmailRV) . "\">" . esc_attr($EmailRV) . "</a></em></td>";
                 $retHTML .= '<td><form method="post">';
                 $retHTML .= '<input type="hidden" id="hour" name="hour" value="' . $HourRV . '">';
                 $retHTML .= '<input type="hidden" id="name" name="name" value="' . $PatientRV . '">';
                 $retHTML .= '<input type="hidden" id="reason" name="reason" value="' . $ReasonRV . '">';
                 $retHTML .= '<input type="hidden" id="email" name="email" value="' . $EmailRV . '">';
                 $retHTML .= '<input type="hidden" id="indice" name="indice" value="' . $parDayRVs . '">';
                 $retHTML .= '<input type="hidden" id="colEnabled" name="colEnabled" value="' . $opt_Enabled . '">';
                 $retHTML .= '<input type="hidden" id="colDisabled" name="colDisabled" value="' . $opt_Disabled . '">';
                 $retHTML .= '<input type="hidden" id="trnum" name="trnum" value="' . $tmpIndTR . '">'; 
                 $retHTML .= '<input type="hidden" id="tdnum" name="tdnum" value="' . $tmpIndTD . '">';
                 $retHTML .= '<input type="hidden" id="RVs" name="RVs" value="">';
                 $retHTML .= '<button id="idButton_' . $rv . '" title="' . esc_html__('Cancel','next-tiny-date') . '" value="Cancel" type="button" onclick="js_ntdt_ModifyRV(this.form);">';
                 $retHTML .= '<image id="imageButton_' . $rv . '" src="' . plugin_dir_url(dirname(__FILE__)).'/images/ntdt-trash.png' . '">';
                 $retHTML .= '</button>';   
                 $retHTML .= '</form>';
               }
            else
               { $ReasonRV = trim($listOnePatient[1]);
                 $retHTML .= "<tr bgcolor=\"" .  esc_attr($opt_Disabled) . "\"><td>" . esc_attr($HourRV) . "</td>";
                 $retHTML .= "<td>" . esc_html__('Me!','next-tiny-date') . "</td><td><em>" . esc_attr($ReasonRV) . "</em></td><td></td><td></td>";
                 $retHTML .= '<td><form method="post">';
                 $retHTML .= '<input type="hidden" id="hour" name="hour" value="' . $HourRV . '">';
                 $retHTML .= '<input type="hidden" id="reason" name="reason" value="' . $ReasonRV . '">';
                 $retHTML .= '<input type="hidden" id="indice" name="indice" value="' . $parDayRVs . '">';
                 $retHTML .= '<input type="hidden" id="colEnabled" name="colEnabled" value="' . $opt_Enabled . '">';
                 $retHTML .= '<input type="hidden" id="colDisabled" name="colDisabled" value="' . $opt_Disabled . '">';
                 $retHTML .= '<input type="hidden" id="trnum" name="trnum" value="' . $tmpIndTR . '">'; 
                 $retHTML .= '<input type="hidden" id="tdnum" name="tdnum" value="' . $tmpIndTD . '">'; 
                 $retHTML .= '<input type="hidden" id="RVs" name="RVs" value="">';
                 $retHTML .= '<button id="idButton_' . $rv . '" title="' . esc_html__('Unlock','next-tiny-date') . '" value="Unlock" type="button" onclick="js_ntdt_ModifyRV(this.form);">';
                 $retHTML .= '<image id="imageButton_' . $rv . '" src="' . plugin_dir_url(dirname(__FILE__)).'/images/ntdt-unlock.png' . '">';
                 $retHTML .= '</button>';   
                 $retHTML .= '</form>';
               }   
          }
       else
          { $retHTML .= "<tr bgcolor=\"" .  esc_attr($opt_Enabled) . "\"><td>" . esc_attr($HourRV) . "</td>";
            $retHTML .= "<td></td><td></td><td></td><td></td>";
            $retHTML .= '<td><form method="post">';
            $retHTML .= '<input type="hidden" id="hour" name="hour" value="' . $HourRV . '">';
            $retHTML .= '<input type="hidden" id="indice" name="indice" value="' . $parDayRVs . '">';
            $retHTML .= '<input type="hidden" id="colEnabled" name="colEnabled" value="' . $opt_Enabled . '">';
            $retHTML .= '<input type="hidden" id="colDisabled" name="colDisabled" value="' . $opt_Disabled . '">';
            $retHTML .= '<input type="hidden" id="trnum" name="trnum" value="' . $tmpIndTR . '">'; 
            $retHTML .= '<input type="hidden" id="tdnum" name="tdnum" value="' . $tmpIndTD . '">';
            $retHTML .= '<input type="hidden" id="RVs" name="RVs" value="">';
            $retHTML .= '<button id="idButton_' . $rv . '" title="' . esc_html__('Lock','next-tiny-date') . '" value="Lock" type="button" onclick="js_ntdt_ModifyRV(this.form);">';
            $retHTML .= '<image id="imageButton_' . $rv . '" src="' . plugin_dir_url(dirname(__FILE__)).'/images/ntdt-lock.png' . '">';
            $retHTML .= '</button>';   
            $retHTML .= '</form>';
          }          
       $retHTML .= "</td></tr>";
       $tmpIndTD +=6;
       }
     }
  $retHTML .= "</table>";
  
  $retHTML .= '<p><em><font color="#808080"><span id="txtHint"></span></font></em></p>';    
   
  return $retHTML;
}

function ntdt_GetNoRV($parDayRVs,$parDayNum)
{ $opt_StartHourAM = get_option('optStartHourAM_' . $parDayNum); if ($opt_StartHourAM == "") $opt_StartHourAM = "08";
  $opt_StartMinAM = get_option('optStartMinAM_' . $parDayNum); if ($opt_StartMinAM == "") $opt_StartMinAM = "00";
  $opt_EndHourAM = get_option('optEndHourAM_' . $parDayNum); if ($opt_EndHourAM == "") $opt_EndHourAM = "12";
  $opt_EndMinAM = get_option('optEndMinAM_' . $parDayNum); if ($opt_EndMinAM == "") $opt_EndMinAM = "00";
  
  $opt_StartHourPM = get_option('optStartHourPM_' . $parDayNum); if ($opt_StartHourPM == "") $opt_StartHourPM = "14";
  $opt_StartMinPM = get_option('optStartMinPM_' . $parDayNum); if ($opt_StartMinPM == "") $opt_StartMinPM = "00";
  $opt_EndHourPM = get_option('optEndHourPM_' . $parDayNum); if ($opt_EndHourPM == "") $opt_EndHourPM = "18";
  $opt_EndMinPM = get_option('optEndMinPM_' . $parDayNum); if ($opt_EndMinPM == "") $opt_EndMinPM = "00";
         
  $opt_OpenAM = get_option('optOpenAM_' . $parDayNum,'0');
  $opt_OpenPM = get_option('optOpenPM_' . $parDayNum,'0');
  $opt_Closed = get_option('optClosed_' . $parDayNum,'1');
  
  $opt_StepRV = get_option('optStepRV',60);
  $opt_Enabled = get_option('optEnabled','#80FF80');
  $opt_Disabled = get_option('optDisabled','#FF8080');

  $rv = 0;
  if ($opt_OpenAM)
     { $strTimeStart = sprintf("%02d",$opt_StartHourAM) . ':' . sprintf("%02d",$opt_StartMinAM);
       $StartUNX = strtotime($strTimeStart);
       $strTimeEnd = sprintf("%02d",$opt_EndHourAM) . ':' . sprintf("%02d",$opt_EndMinAM) . '-' . $opt_StepRV . 'min';
       $EndUNX = strtotime($strTimeEnd);

       $strTime = date('H:i',$StartUNX);
       $TimeUNX = strtotime($strTime);
       While ($TimeUNX <= $EndUNX)
             { $A_RVs[$rv] = date('H:i',$TimeUNX);
               $strTime = date('H:i',$TimeUNX) . '+' . $opt_StepRV . 'min';
               $TimeUNX = strtotime($strTime);
               $rv++;
             }
     }
  if (($opt_OpenAM) and ($opt_OpenPM))
     { $A_RVs[$rv] = "//";
       $rv++;
     }
  if ($opt_OpenPM)
     { $strTimeStart = sprintf("%02d",$opt_StartHourPM) . ':' . sprintf("%02d",$opt_StartMinPM);
       $StartUNX = strtotime($strTimeStart);
       $strTimeEnd = sprintf("%02d",$opt_EndHourPM) . ':' . sprintf("%02d",$opt_EndMinPM) . '-' . $opt_StepRV . 'min';
       $EndUNX = strtotime($strTimeEnd);

       $strTime = date('H:i',$StartUNX);
       $TimeUNX = strtotime($strTime);
       While ($TimeUNX <= $EndUNX)
             { $A_RVs[$rv] = date('H:i',$TimeUNX);
               $strTime = date('H:i',$TimeUNX) . '+' . $opt_StepRV . 'min';
               $TimeUNX = strtotime($strTime);
               $rv++;
             }
     }
  $strRVs = "";
  for ($i=0;$i<=count($A_RVs);$i++)
      if (trim($A_RVs[$i])!="") $strRVs .= $A_RVs[$i] . ";";
  $strRVs = substr($strRVs,0,-1);
  
  $retHTML = "";
  if ($opt_Closed)
     { $retHTML .= "<br><br><b>" . esc_html__('Closed','next-tiny-date') . "</b>";
     }
  else
     { if (ntdt_IsFeried($parDayRVs))
          { $retHTML .= "<br><br><b><font color='#FE5500'>" . esc_html__('Public holiday','next-tiny-date') . "</font></b>";
            return $retHTML;
          }
       if (ntdt_IsHolidays($parDayRVs))
          { $retHTML .= "<br><br><b><font color='#FE5500'>" . esc_html__('Holidays','next-tiny-date') . "</font></b>";
            return $retHTML;
          }
          
       $retHTML .= '<table name="tableRV" id="tableRV" class="form-table" border="1">';
       $retHTML .= "<tr style=\"color: #ffffff; background: #2271B1;\"><th style=\"text-align:center\">" . esc_html__('Time','next-tiny-date') . "</th><th style=\"text-align:center\">" . esc_html__('Name','next-tiny-date') . "</th><th style=\"text-align:center\">" . esc_html__('Reason','next-tiny-date') . "</th><th style=\"text-align:center\">" . esc_html__('Phone number','next-tiny-date') . "</th><th style=\"text-align:center\">" . esc_html__('e-mail','next-tiny-date') . "</th><th>&nbsp;</th></tr>";

       $tmpIndTR = 0; $rv = -1;
       $tmpIndTD = 0;
       if ($opt_OpenAM)
          { $strTimeStart = sprintf("%02d",$opt_StartHourAM) . ':' . sprintf("%02d",$opt_StartMinAM);
            $StartUNX = strtotime($strTimeStart);
            $strTimeEnd = sprintf("%02d",$opt_EndHourAM) . ':' . sprintf("%02d",$opt_EndMinAM) . '-' . $opt_StepRV . 'min';
            $EndUNX = strtotime($strTimeEnd);

            $strTime = date('H:i',$StartUNX);
            $TimeUNX = strtotime($strTime);
            While ($TimeUNX <= $EndUNX)
                  { $tmpIndTR++; $rv++;
                    $HourRV = date('H:i',$TimeUNX);
                    $retHTML .= "<tr bgcolor=\"" .  esc_attr($opt_Enabled) . "\"><td>" . esc_attr($HourRV) . "</td><td></td><td></td><td></td><td></td>";
                    $retHTML .= '<td><form method="post">';
                    $retHTML .= '<input type="hidden" id="hour" name="hour" value="' . esc_attr($HourRV) . '">';
                    $retHTML .= '<input type="hidden" id="indice" name="indice" value="' . $parDayRVs . '">';
                    $retHTML .= '<input type="hidden" id="colEnabled" name="colEnabled" value="' . $opt_Enabled . '">';
                    $retHTML .= '<input type="hidden" id="colDisabled" name="colDisabled" value="' . $opt_Disabled . '">';
                    $retHTML .= '<input type="hidden" id="trnum" name="trnum" value="' . $tmpIndTR . '">'; 
                    $retHTML .= '<input type="hidden" id="tdnum" name="tdnum" value="' . $tmpIndTD . '">';
                    $retHTML .= '<input type="hidden" id="RVs" name="RVs" value="' . $strRVs . '">';
                    $retHTML .= '<button id="idButton_' . $rv . '" title="' . esc_html__('Lock','next-tiny-date') . '" value="Lock" type="button" onclick="js_ntdt_ModifyRV(this.form);">';
                    $retHTML .= '<image id="imageButton_' . $rv . '" src="' . plugin_dir_url(dirname(__FILE__)).'/images/ntdt-lock.png' . '">';
                    $retHTML .= '</button>';   
                    $retHTML .= '</form>';
                    $strTime = date('H:i',$TimeUNX) . '+' . $opt_StepRV . 'min';
                    $TimeUNX = strtotime($strTime);
                    $tmpIndTD +=6;
                  }
          }

       if (($opt_OpenAM) and ($opt_OpenPM))
          { $retHTML .= "<tr><td colspan=\"6\">" . esc_html__('Day break','next-tiny-date') . "</td></tr>"; 
            $tmpIndTR++; $rv++; $tmpIndTD++;
          }

       if ($opt_OpenPM)
          { $strTimeStart = sprintf("%02d",$opt_StartHourPM) . ':' . sprintf("%02d",$opt_StartMinPM);
            $StartUNX = strtotime($strTimeStart);
            $strTimeEnd = sprintf("%02d",$opt_EndHourPM) . ':' . sprintf("%02d",$opt_EndMinPM) . '-' . $opt_StepRV . 'min';
            $EndUNX = strtotime($strTimeEnd);

            $strTime = date('H:i',$StartUNX);
            $TimeUNX = strtotime($strTime);
            While ($TimeUNX <= $EndUNX)
                  { $tmpIndTR++; $rv++;
                    $HourRV = date('H:i',$TimeUNX);
                    $retHTML .= "<tr bgcolor=\"" .  esc_attr($opt_Enabled) . "\"><td>" . esc_attr($HourRV) . "</td><td></td><td></td><td></td><td></td>";
                    $retHTML .= '<td><form method="post">';
                    $retHTML .= '<input type="hidden" id="hour" name="hour" value="' . esc_attr($HourRV) . '">';
                    $retHTML .= '<input type="hidden" id="indice" name="indice" value="' . $parDayRVs . '">';
                    $retHTML .= '<input type="hidden" id="colEnabled" name="colEnabled" value="' . $opt_Enabled . '">';
                    $retHTML .= '<input type="hidden" id="colDisabled" name="colDisabled" value="' . $opt_Disabled . '">';
                    $retHTML .= '<input type="hidden" id="trnum" name="trnum" value="' . $tmpIndTR . '">';
                    $retHTML .= '<input type="hidden" id="tdnum" name="tdnum" value="' . $tmpIndTD . '">';
                    $retHTML .= '<input type="hidden" id="RVs" name="RVs" value="' . $strRVs . '">'; 
                    $retHTML .= '<button id="idButton_' . $rv . '" title="' . esc_html__('Lock','next-tiny-date') . '" value="Lock" type="button" onclick="js_ntdt_ModifyRV(this.form);">';
                    $retHTML .= '<image id="imageButton_' . $rv . '" src="' . plugin_dir_url(dirname(__FILE__)).'/images/ntdt-lock.png' . '">';
                    $retHTML .= '</button>';   
                    $retHTML .= '</form>';
                    $strTime = date('H:i',$TimeUNX) . '+' . $opt_StepRV . 'min';
                    $TimeUNX = strtotime($strTime);
                    $tmpIndTD +=6;
                  }
          }

       $retHTML .= "</td></tr>";
       $retHTML .= "</table>";
     }
  
  $retHTML .= '<p><em><font color="#808080"><span id="txtHint"></span></font></em></p>';
  
  return $retHTML;
}

function ntdt_GetHourRV($parDayRVs,$parDayNum,$parCmt1,$parCmt2,$parCmt3)
{ global $wpdb;
  $TableName = $wpdb->prefix . NTDT_PLUGIN_TABLE;

  $opt_NbHourButtons = get_option('optNbHourButtons',5);
  $Nb_FirstRV = 0;
  $retHTML = "";
  
  $tmpYear = substr($parDayRVs,0,4);
  $tmpMonth = substr($parDayRVs,4,2);
  $tmpDay = substr($parDayRVs,6,2);
  $opt_FormatDateRV = get_option('optFormatDateRV',0);
  switch ($opt_FormatDateRV)
         { case 0: $tmpSendDate = $tmpDay . '/' . $tmpMonth . '/' . $tmpYear; break;
           case 1: $tmpSendDate = $tmpMonth . '/' . $tmpDay . '/' . $tmpYear; break;
         }
  
  $tmpSQL = $wpdb->prepare("SELECT DayRVs FROM $TableName WHERE id = %s",$parDayRVs);
  $tmpLineAllRV = trim($wpdb->get_var($tmpSQL));
  $tmpLineAllRV = str_replace('&amp;','&',$tmpLineAllRV);
  $listAllRV = explode(';',$tmpLineAllRV);
  $NbRV = count($listAllRV);

  $retHTML .= '<input type="hidden" id="RVs_' . $parDayRVs . '" name="RVs_' . $parDayRVs . '" value="">';
  //if ($NbRV > 0) $retHTML .= '<select id="LstRV" name="LstRV">';
  if ($NbRV <= 0)
     { $retHTML .= '<font color="#ff0000"><td>-</td></font">';
     }
  else
     { $tmpVisible = '';
       $tmpInfoCmtStr = "";
       if (($parCmt1) or ($parCmt2) or ($parCmt3))
          { $opt_InfoCmtIcon = get_option('optInfoCmtIcon','info-outline');
            if ($parCmt1) $tmpInfoCmtStr .= "<font color=\"" . get_option('optInfoCmtColor1','#000000') . "\"><span class=\"dashicons dashicons-" . $opt_InfoCmtIcon . "\"></span></font>";
            if ($parCmt2) $tmpInfoCmtStr .= "<font color=\"" . get_option('optInfoCmtColor2','#000000') . "\"><span class=\"dashicons dashicons-" . $opt_InfoCmtIcon . "\"></span></font>";
            if ($parCmt3) $tmpInfoCmtStr .= "<font color=\"" . get_option('optInfoCmtColor3','#000000') . "\"><span class=\"dashicons dashicons-" . $opt_InfoCmtIcon . "\"></span></font>";
          }     
          
       $retHTML .= "<td>";
       for($rv=0;$rv<$NbRV;$rv++)
          { $tmpLineOneRV = trim($listAllRV[$rv]);
            if (substr($tmpLineOneRV,0,2) != "//")
               { $HourRV = substr($tmpLineOneRV,0,5);
                 $tmpLineOneRV = substr($tmpLineOneRV,5);
                 if ($tmpLineOneRV == "")
                    { $Nb_FirstRV++;
                      if ($Nb_FirstRV > $opt_NbHourButtons) $tmpVisible = 'class="toggable" style="display: none;" ';
                      $retHTML .= "<p " . $tmpVisible  . "value=\"" . $HourRV . "\"><button onClick=\"js_ntdt_SetHour('$tmpSendDate','$parDayRVs','$HourRV');\" class=\"btnRV\">" . $HourRV . " " . $tmpInfoCmtStr . "</button></p>";
                    }          
               }
          }
       if($Nb_FirstRV == 0) $retHTML .= '-';
       $retHTML .= "</td>";
     }
  return $retHTML;
}

function ntdt_GetHourNoRV($parDayRVs,$parDayNum,$parCmt1,$parCmt2,$parCmt3)
{ $opt_StartHourAM = get_option('optStartHourAM_' . $parDayNum); if ($opt_StartHourAM == "") $opt_StartHourAM = "08";
  $opt_StartMinAM = get_option('optStartMinAM_' . $parDayNum); if ($opt_StartMinAM == "") $opt_StartMinAM = "00";
  $opt_EndHourAM = get_option('optEndHourAM_' . $parDayNum); if ($opt_EndHourAM == "") $opt_EndHourAM = "12";
  $opt_EndMinAM = get_option('optEndMinAM_' . $parDayNum); if ($opt_EndMinAM == "") $opt_EndMinAM = "00";
  
  $opt_StartHourPM = get_option('optStartHourPM_' . $parDayNum); if ($opt_StartHourPM == "") $opt_StartHourPM = "14";
  $opt_StartMinPM = get_option('optStartMinPM_' . $parDayNum); if ($opt_StartMinPM == "") $opt_StartMinPM = "00";
  $opt_EndHourPM = get_option('optEndHourPM_' . $parDayNum); if ($opt_EndHourPM == "") $opt_EndHourPM = "18";
  $opt_EndMinPM = get_option('optEndMinPM_' . $parDayNum); if ($opt_EndMinPM == "") $opt_EndMinPM = "00";
  
  $opt_OpenAM = get_option('optOpenAM_' . $parDayNum,'0');
  $opt_OpenPM = get_option('optOpenPM_' . $parDayNum,'0');
  $opt_Closed = get_option('optClosed_' . $parDayNum,'1');
  
  $opt_StepRV = get_option('optStepRV',60);
  
  $opt_NbHourButtons = get_option('optNbHourButtons',5);
  $Nb_FirstRV = 0;
  
  $tmpYear = substr($parDayRVs,0,4);
  $tmpMonth = substr($parDayRVs,4,2);
  $tmpDay = substr($parDayRVs,6,2);
  $opt_FormatDateRV = get_option('optFormatDateRV',0);
  switch ($opt_FormatDateRV)
         { case 0: $tmpSendDate = $tmpDay . '/' . $tmpMonth . '/' . $tmpYear; break;
           case 1: $tmpSendDate = $tmpMonth . '/' . $tmpDay . '/' . $tmpYear; break;
         }

  $rv = 0;
  if ($opt_OpenAM)
     { $strTimeStart = sprintf("%02d",$opt_StartHourAM) . ':' . sprintf("%02d",$opt_StartMinAM);
       $StartUNX = strtotime($strTimeStart);
       $strTimeEnd = sprintf("%02d",$opt_EndHourAM) . ':' . sprintf("%02d",$opt_EndMinAM) . '-' . $opt_StepRV . 'min';
       $EndUNX = strtotime($strTimeEnd);

       $strTime = date('H:i',$StartUNX);
       $TimeUNX = strtotime($strTime);
       While ($TimeUNX <= $EndUNX)
             { $A_RVs[$rv] = date('H:i',$TimeUNX);
               $strTime = date('H:i',$TimeUNX) . '+' . $opt_StepRV . 'min';
               $TimeUNX = strtotime($strTime);
               $rv++;
             }
     }
  if (($opt_OpenAM) and ($opt_OpenPM))
     { $A_RVs[$rv] = "//";
       $rv++;
     }
  if ($opt_OpenPM)
     { $strTimeStart = sprintf("%02d",$opt_StartHourPM) . ':' . sprintf("%02d",$opt_StartMinPM);
       $StartUNX = strtotime($strTimeStart);
       $strTimeEnd = sprintf("%02d",$opt_EndHourPM) . ':' . sprintf("%02d",$opt_EndMinPM) . '-' . $opt_StepRV . 'min';
       $EndUNX = strtotime($strTimeEnd);

       $strTime = date('H:i',$StartUNX);
       $TimeUNX = strtotime($strTime);
       While ($TimeUNX <= $EndUNX)
             { $A_RVs[$rv] = date('H:i',$TimeUNX);
               $strTime = date('H:i',$TimeUNX) . '+' . $opt_StepRV . 'min';
               $TimeUNX = strtotime($strTime);
               $rv++;
             }
     }
  $strRVs = "";
  for ($i=0;$i<=count($A_RVs);$i++)
      if (trim($A_RVs[$i])!="") $strRVs .= $A_RVs[$i] . ";";
  $strRVs = substr($strRVs,0,-1);
  
  $retHTML = "";
  $tmpVisible = '';
  $retHTML .= '<input type="hidden" id="RVs_' . $parDayRVs . '" name="RVs_' . $parDayRVs . '" value="' . $strRVs . '">';

  if ($opt_Closed)
     { $retHTML .= '<font color="#ff0000">' . esc_html__('Closed','next-tiny-date') . "</font>";
     }
  else
     { $tmpInfoCmtStr = "";
       if (($parCmt1) or ($parCmt2) or ($parCmt3))
          { $opt_InfoCmtIcon = get_option('optInfoCmtIcon','info-outline');
            if ($parCmt1) $tmpInfoCmtStr .= "<font color=\"" . get_option('optInfoCmtColor1','#000000') . "\"><span class=\"dashicons dashicons-" . $opt_InfoCmtIcon . "\"></span></font>";
            if ($parCmt2) $tmpInfoCmtStr .= "<font color=\"" . get_option('optInfoCmtColor2','#000000') . "\"><span class=\"dashicons dashicons-" . $opt_InfoCmtIcon . "\"></span></font>";
            if ($parCmt3) $tmpInfoCmtStr .= "<font color=\"" . get_option('optInfoCmtColor3','#000000') . "\"><span class=\"dashicons dashicons-" . $opt_InfoCmtIcon . "\"></span></font>";
          }
          
       if ($opt_OpenAM)
          { $retHTML .= '<td>';
            $strTimeStart = sprintf("%02d",$opt_StartHourAM) . ':' . sprintf("%02d",$opt_StartMinAM);
            $StartUNX = strtotime($strTimeStart);
            $strTimeEnd = sprintf("%02d",$opt_EndHourAM) . ':' . sprintf("%02d",$opt_EndMinAM) . '-' . $opt_StepRV . 'min';
            $EndUNX = strtotime($strTimeEnd);

            $strTime = date('H:i',$StartUNX);
            $TimeUNX = strtotime($strTime);
            While ($TimeUNX <= $EndUNX)
                  { $Nb_FirstRV++;
                    if ($Nb_FirstRV > $opt_NbHourButtons) $tmpVisible = 'class="toggable" style="display: none;" ';
                    $HourRV = date('H:i',$TimeUNX);
                    $retHTML .= "<p " . $tmpVisible  . "value=\"" . $HourRV . "\"><button onClick=\"js_ntdt_SetHour('$tmpSendDate','$parDayRVs','$HourRV');\" class=\"btnRV\" >" . $HourRV . " " . $tmpInfoCmtStr . "</button></p>";
                    $strTime = date('H:i',$TimeUNX) . '+' . $opt_StepRV . 'min';
                    $TimeUNX = strtotime($strTime);
                  }
          }
                      
       if ($opt_OpenPM)
          { if (!$opt_OpenAM) $retHTML .= '<td>';
            $strTimeStart = sprintf("%02d",$opt_StartHourPM) . ':' . sprintf("%02d",$opt_StartMinPM);
            $StartUNX = strtotime($strTimeStart);
            $strTimeEnd = sprintf("%02d",$opt_EndHourPM) . ':' . sprintf("%02d",$opt_EndMinPM) . '-' . $opt_StepRV . 'min';
            $EndUNX = strtotime($strTimeEnd);

            $strTime = date('H:i',$StartUNX);
            $TimeUNX = strtotime($strTime);
            While ($TimeUNX <= $EndUNX)
                  { $Nb_FirstRV++;
                    if ($Nb_FirstRV > $opt_NbHourButtons) $tmpVisible = 'class="toggable" style="display: none;" ';
                    $HourRV = date('H:i',$TimeUNX);
                    $retHTML .= "<p " . $tmpVisible  . "value=\"" . $HourRV . "\"><button onClick=\"js_ntdt_SetHour('$tmpSendDate','$parDayRVs','$HourRV');\" class=\"btnRV\">" . $HourRV . " " . $tmpInfoCmtStr . "</button></p>";
                    $strTime = date('H:i',$TimeUNX) . '+' . $opt_StepRV . 'min';
                    $TimeUNX = strtotime($strTime);
                  }
          }
       if (($opt_OpenAM) or ($opt_OpenPM)) $retHTML .= '</td>';
     }
  return $retHTML;
}

function ntdt_GetHourList($parIndiceDate,$parCmt1,$parCmt2,$parCmt3)
{ global $wpdb;
  $TableName = $wpdb->prefix . NTDT_PLUGIN_TABLE;
  
  $tmpYear = substr($parIndiceDate,0,4);
  $tmpMonth = substr($parIndiceDate,4,2);
  $tmpDay = substr($parIndiceDate,6,2);
  $tmpDate = date_create();
  date_date_set($tmpDate,$tmpYear,$tmpMonth,$tmpDay);
  $tmpDayNum = date_format($tmpDate,"w");

  $todayDate = date("Y-m-d");
  $CompareDate = date_format($tmpDate,"Y-m-d");
  if ($CompareDate <= $todayDate)
     { return '<font color="#ff0000"><td>-</td></font>';
     }
  $opt_DayTillRV = get_option('optDayTillRV','2 weeks');
  $LastDate = date('Y-m-d', strtotime("+".$opt_DayTillRV));
  if ($CompareDate > $LastDate)
     { return "<td>" . esc_html__('Not bookable yet','next-tiny-date') . "...</td>";
     }
    
  $tmpSQL = $wpdb->prepare("SELECT DayRVs FROM $TableName WHERE id = %s",$parIndiceDate);
  $strData = trim($wpdb->get_var($tmpSQL));

  $html = "";
  $opt_HideHolidays = false;
  $opt_Closed = get_option('optClosed_' . $tmpDayNum,0);
  if ($opt_Closed)
     { $html .= "<td style='color: #808080; background: #F8F6F2;'>" . esc_html__('Closed','next-tiny-date') . "</td>";
     }
  elseif (ntdt_IsFeried($parIndiceDate))
     { if ($opt_HideHolidays)
          $html .= "<td style='color: #808080; background: #F8F6F2;'>" . esc_html__('Closed','next-tiny-date') . "</td>";
       else   
          $html .= "<td style='color: #808080; background: #F8F6F2;'>" . esc_html__('Public holiday','next-tiny-date') . "</td>";
     }
  elseif (ntdt_IsHolidays($parIndiceDate))
     { if ($opt_HideHolidays)
          $html .= "<td style='color: #808080; background: #F8F6F2;'>" . esc_html__('Closed','next-tiny-date') . "</td>";
       else 
          $html .= "<td style='color: #808080; background: #F8F6F2;'>" . esc_html__('Holidays','next-tiny-date') . "</td>";
     }
  else
     { if ($strData != "")
          { $html .= ntdt_GetHourRV($parIndiceDate,$tmpDayNum,$parCmt1,$parCmt2,$parCmt3);
          }
       else
          { $html .= ntdt_GetHourNoRV($parIndiceDate,$tmpDayNum,$parCmt1,$parCmt2,$parCmt3);
          }  
     }
  return $html;
}

function ntdt_display_CalTable($parDate)
{ $NumDayName =  date('w'); //1 for monday
  $PrevMondayDate_YMD = date('Y-m-d', strtotime($parDate . "-1 monday"));
  //if ($NumDayName == 1)
     $NextMondayDate_YMD = date('Y-m-d', strtotime($parDate . "+2 mondays"));
  //else
     //$NextMondayDate_YMD = date('Y-m-d', strtotime($parDate . "+1 mondays"));
  
  $tmpDisableDay = "";
  if ($PrevMondayDate_YMD <= date('Y-m-d', strtotime("-7 days"))) $tmpDisableDay = "disabled ";

  echo '<div style="overflow-x:auto;">';
  echo '<table class="NoTable" XXstyle="XXwidth: 100%; display: block; overflow-x: auto;">';

  echo '<tr><td><button ' . $tmpDisableDay . 'id="idButton_PrevRV" title="' . esc_html__('Previous week','next-tiny-date') . ' [' . $PrevMondayDate_YMD . ']" value="Previous week" type="button" onclick="js_ntdt_CalRV(\'' . $PrevMondayDate_YMD . '\');">';
  echo '<span class="dashicons dashicons-arrow-left"></span>'; //<image id="imageButton_PrevRV" src="' . plugin_dir_url(dirname(__FILE__)).'/images/ntdt-left.png' . '">';
  echo '</button></td>';

  $opt_FormatDateRV = get_option('optFormatDateRV',0);
  switch ($opt_FormatDateRV)
         { case 0: $tmpDateFormat = "d/m"; break;
           case 1: $tmpDateFormat = "m/d"; break;
         }
         
  $opt_BtnBgHover = get_option('optBtnBgHover','#40FFFF');
  $opt_BtnColHover = get_option('optBtnColHover','#FFFFFF');
  for ($j=0;$j<=6;$j++) 
      { $tmpDateRV = date("d/m/Y", strtotime($parDate . "+" . $j . " days"));
        if ($tmpDateRV == date("d/m/Y"))
           { echo "<td align=\"center\" style=\"background-color: " . $opt_BtnBgHover . ";\"><b><font color=\"" . $opt_BtnColHover  . "\">" . substr(ntdt_GetDayName($j+1),0,3) . "</font></b><br>";
             echo "<b><font color=\"" . $opt_BtnColHover  . "\">" . date($tmpDateFormat, strtotime($parDate . "+" . $j . " days")) . "</font></b></td>";
           }
        else
           { echo "<td align=\"center\"><b>" . substr(ntdt_GetDayName($j+1),0,3) . "</b><br>";
             echo date($tmpDateFormat, strtotime($parDate . "+" . $j . " days")) . "</td>";
           }
      }
      
  $tmpDisableDay = "";
  $opt_DayTillRV = get_option('optDayTillRV','6 months');
  if ($NextMondayDate_YMD > date('Y-m-d', strtotime("+".$opt_DayTillRV))) $tmpDisableDay = "disabled ";
  echo '<td><button ' . $tmpDisableDay . 'id="idButton_NextRV" title="' . esc_html__('Next week','next-tiny-date') . ' [' . $NextMondayDate_YMD . ']" value="Next week" type="button" onclick="js_ntdt_CalRV(\'' . $NextMondayDate_YMD . '\');">';
  echo '<span class="dashicons dashicons-arrow-right"></span>'; //<image id="imageButton_NextRV" src="' . plugin_dir_url(dirname(__FILE__)).'/images/ntdt-right.png' . '">';
  echo '</button></td></tr>';
  
  //--- Test if info comment message needed:
  $opt_InfoCmt = get_option('optInfoCmt','0');
  if ($opt_InfoCmt)
     { $opt_InfoCmtMsg1 = get_option('optInfoCmtMsg1');
       $opt_InfoCmtFromDate1 = get_option('optInfoCmtFromDate1');
       $opt_InfoCmtToDate1 = get_option('optInfoCmtToDate1');
       $opt_InfoCmtMsg2 = get_option('optInfoCmtMsg2');
       $opt_InfoCmtFromDate2 = get_option('optInfoCmtFromDate2');
       $opt_InfoCmtToDate2 = get_option('optInfoCmtToDate2');
       $opt_InfoCmtMsg3 = get_option('optInfoCmtMsg3');
       $opt_InfoCmtFromDate3 = get_option('optInfoCmtFromDate3');
       $opt_InfoCmtToDate3 = get_option('optInfoCmtToDate3');

       /*$todayDate = date("Y-m-d");
       $tmpInfoCmtNeeded = 0;
       if (($todayDate>=$opt_InfoCmtFromDate1) and ($todayDate<=$opt_InfoCmtToDate1))
          $tmpInfoCmtNeeded = 1;*/
     }
  
  echo '<tr align="center"><td></td>'; //Prev col
  $tmpFoundCmt1 = false;
  $tmpFoundCmt2 = false;
  $tmpFoundCmt3 = false;
  for ($j=0;$j<=6;$j++) 
      { $tmpDate = date("Ymd", strtotime($parDate . "+" . $j . " days"));
        $tmpDateRV = date("Y-m-d", strtotime($parDate . "+" . $j . " days"));
        $p1 = false;
        $p2 = false;
        $p3 = false;
        if ($opt_InfoCmt)
           { if ($opt_InfoCmtMsg1 != "") 
                { $tmpFrom = $opt_InfoCmtFromDate1; $tmpTo = $opt_InfoCmtToDate1;
                  if($tmpFrom > $tmpTo)
                    { $tmpFrom = $opt_InfoCmtToDate1; $tmpTo = $opt_InfoCmtFromDate1; }
                  if(($tmpDateRV>=$tmpFrom) and ($tmpDateRV<=$tmpTo))
                    { $p1 = true; $tmpFoundCmt1 = true;}
                }
             if ($opt_InfoCmtMsg2 != "") 
                { $tmpFrom = $opt_InfoCmtFromDate2; $tmpTo = $opt_InfoCmtToDate2;
                  if($tmpFrom > $tmpTo)
                    { $tmpFrom = $opt_InfoCmtToDate2; $tmpTo = $opt_InfoCmtFromDate2; }
                  if(($tmpDateRV>=$tmpFrom) and ($tmpDateRV<=$tmpTo))
                    { $p2 = true; $tmpFoundCmt2 = true;}
                }
             if ($opt_InfoCmtMsg3 != "")  
                { $tmpFrom = $opt_InfoCmtFromDate3; $tmpTo = $opt_InfoCmtToDate3;
                  if($tmpFrom > $tmpTo)
                    { $tmpFrom = $opt_InfoCmtToDate3; $tmpTo = $opt_InfoCmtFromDate3; }
                  if(($tmpDateRV>=$tmpFrom) and ($tmpDateRV<=$tmpTo))
                    { $p3 = true; $tmpFoundCmt3 = true;}
                }                
           }
        echo ntdt_GetHourList($tmpDate,$p1,$p2,$p3);
      }
  echo '<td></td></tr>'; //Next col
  echo '</table>';
  echo '</div>';
  ?>

  <center>
  <table><tr><td>
  <?php
  if (($tmpFoundCmt1) or ($tmpFoundCmt2) or ($tmpFoundCmt3))
     { $opt_InfoCmtIcon = get_option('optInfoCmtIcon','info-outline');
       if ($tmpFoundCmt1) 
          { $opt_InfoCmtColor1 = get_option('optInfoCmtColor1','#000000');
            echo "<font color=\"" . $opt_InfoCmtColor1 . "\"><span class=\"dashicons dashicons-" . $opt_InfoCmtIcon . "\"></span></font> $opt_InfoCmtMsg1<br>";
          }
       if ($tmpFoundCmt2) 
          { $opt_InfoCmtColor2 = get_option('optInfoCmtColor2','#000000');
            echo "<font color=\"" . $opt_InfoCmtColor2 . "\"><span class=\"dashicons dashicons-" . $opt_InfoCmtIcon . "\"></span></font> $opt_InfoCmtMsg2<br>";
          }
       if ($tmpFoundCmt3) 
          { $opt_InfoCmtColor3 = get_option('optInfoCmtColor3','#000000');
            echo "<font color=\"" . $opt_InfoCmtColor3 . "\"><span class=\"dashicons dashicons-" . $opt_InfoCmtIcon . "\"></span></font> $opt_InfoCmtMsg3<br>";
          }
     } 
  ?>
  </td></tr></table>
  <button class="btnRV" id="ToggleRV" type="button" onclick="js_ntdt_ToggleRV('<?php esc_html_e('See more appointments','next-tiny-date');?>','<?php esc_html_e('See less appointments','next-tiny-date');?>');">
  <?php esc_html_e('Show more appointments','next-tiny-date');
  //<span class="dashicons dashicons-arrow-down"></span>
  //echo '<image src="' . plugin_dir_url(dirname(__FILE__)).'/images/ntdt-show.png' . '">';
  ?>
  </button></center>
<?php
}

function ntdt_display_CalRV()
{ if (isset($_REQUEST['StartD']))
     { $BeginDate = sanitize_text_field($_REQUEST['StartD']);
       ntdt_display_CalTable($BeginDate);
     }
  wp_die(); 
}
add_action('wp_ajax_ntdt_display_CalRV','ntdt_display_CalRV');
add_action('wp_ajax_nopriv_ntdt_display_CalRV','ntdt_display_CalRV');

function ntdt_display_FormRV($atts)
{ ?>
  <link rel='stylesheet' id='RV-css' href='<?php echo plugins_url('/',__DIR__)?>css/styleRV.css?ver=6.1.1' type='text/css' media='all' />
  <script type='text/javascript' src='<?php echo plugins_url('/',__DIR__)?>js/ntdt_FormRV.js?ver=1.2' id='jquery-core-js'></script>  
  <?php
  $opt_DayTillRV = get_option('optDayTillRV','6 months');
  $tmpDateTo = date('Y-m-d',strtotime('+'.$opt_DayTillRV));
  $NumDayName =  date('w'); //1 for monday
  if ($NumDayName == 1) //Today is Monday!
     $MondayDate_YMD = date('Y-m-d');
  else
     $MondayDate_YMD = date('Y-m-d', strtotime("previous monday"));
     
  switch ($NumDayName)
         { case 0: //Sunday
                $MondayDate_YMD = date('Y-m-d', strtotime("next monday"));
                break;
           case 5: //Friday
                $opt_ClosedSAT = get_option('optClosed_6' . $tmpDayNum,0);
                $opt_ClosedSUN = get_option('optClosed_0' . $tmpDayNum,0);
                if (($opt_ClosedSAT) and ($opt_ClosedSAT))
                   $MondayDate_YMD = date('Y-m-d', strtotime("next monday")); 
                break;
           case 6: //Saturday
               $opt_ClosedSUN = get_option('optClosed_0' . $tmpDayNum,0);
               if ($opt_ClosedSUN)
                  $MondayDate_YMD = date('Y-m-d', strtotime("next monday")); 
               break;
         }
     
  $PrevMondayDate_YMD = date('Y-m-d', strtotime($MondayDate_YMD . "-1 monday"));
  if ($NumDayName == 1)
     $NextMondayDate_YMD = date('Y-m-d', strtotime($MondayDate_YMD . "+1 monday"));
  else
     $NextMondayDate_YMD = date('Y-m-d', strtotime($MondayDate_YMD . "+2 mondays"));
?>
  
  <h3><?php esc_html_e('Book an appointment','next-tiny-date');?></h3>   
  <p><em><font color="#808080"><span name="ntdt_FormSent" id="ntdt_FormSent"></span></font></em></p> 
  <form name="F_Send" id="F_Send" method="post" onsubmit="SendRV(this.form)">
  <input type="hidden" id="ajaxurl" name="ajaxurl" value="<?php echo admin_url('admin-ajax.php'); ?>">
  <?php
  $tmpRedir = get_option('optRedirect');
  ?>
  <input type="hidden" id="lnkRedir" name="lnkRedir" value="<?php echo esc_attr($tmpRedir); ?>">
  
  <?php
  echo '<p><span id="ntdt_FormCalRV" name="ntdt_FormCalRV"></span></p>';

  echo '<script type="text/javascript">js_ntdt_CalRV(\'' . $MondayDate_YMD . '\');</script>';
  echo '<input name="CurDate" id="CurDate" type="hidden" value="">';
  echo '<input name="LstRV" id="LstRV" type="hidden" value="">';
  ?>
  <h3><span id="SpanHourRV">-</span></h3>
  <table border="1">
  <tr>
  <td><label for="NameRV"><?php esc_html_e('Name','next-tiny-date'); ?></label> *<input type="text" id="NameRV" name="NameRV"></td>
  <td>

  <label for="ReasonRV"><?php esc_html_e('Reason for the appointment','next-tiny-date');?>
  <select id="LstReason" name="LstReason">
  <?php
  $opt_Reason = get_option('optReason');
  if ($opt_Reason != "")
     { $tmpReason = trim($opt_Reason);
       $tmpReason  = strtr($tmpReason, array(';' => '-'));
       $strCRLF   = array("\r\n", "\n", "\r");
       $strNew = ';';
       $tmpReason = str_replace($strCRLF, $strNew, $tmpReason);
       $listAllReason = explode(';',$tmpReason);
       $NbReason = count($listAllReason);
       $tmpInGroup = false;
       for($r=0;$r<$NbReason;$r++)
          { $strReason = trim($listAllReason[$r]);
            if (substr($strReason,0,1)=='*')
               { if ($tmpInGroup) echo '</optgroup>';
                    $tmpInGroup = true;
                 echo '<optgroup label="' . esc_attr(substr($strReason,1)) . '">';
               }
            else
               { echo '<option value="' . esc_attr($strReason) . '">' . esc_attr($strReason) . '</option>';
               }
          }
     }
  else
     echo '<option value="' . esc_html__('Appointment','next-tiny-date') . '">' . esc_html__('Appointment','next-tiny-date') . '</option>';
  if ($tmpInGroup) echo '</optgroup>';
  ?>
  </select>
  </td></tr>
  
  <tr>
  <td><label for="TelRV"><?php esc_html_e('Phone number','next-tiny-date'); ?></label> *<input type="tel" id="TelRV" name="TelRV" pattern="[0-9]{10}"></td>
  <td><label for="EmailRV"><?php esc_html_e('e-mail','next-tiny-date'); ?></label> *<input type="email" id="EmailRV" name="EmailRV"></td>
  </tr>
  
  <tr><td colspan="2"><label for="RV_Msg"><?php esc_html_e('Message','next-tiny-date'); ?></label><textarea id="RV_Msg" name="RV_Msg" rows="4"></textarea></td></tr>
  
  <tr><td colspan="2"><input id="B_ok" name="B_ok" type="submit" value="OK"></td></tr>
  </table>
  </form>  
  <?php
} 
add_shortcode('next_tiny_date','ntdt_display_FormRV');

function ntdt_ExportRV($fd)
{ global $wpdb;
  $TableName = $wpdb->prefix . NTDT_PLUGIN_TABLE;

  $tmpSQL = $wpdb->prepare("SELECT id, DayRVs FROM $TableName ORDER BY id ASC");
  $retDayRVs = $wpdb->get_results($tmpSQL);
  $NbDayRVs = count($retDayRVs);
  $opt_OnlyRV = get_option('optOnlyRV','0');
   
  $opt_FormatDateRV = get_option('optFormatDateRV',0);
  switch ($opt_FormatDateRV)
         { case 0: $tmpDateFormat = "d M Y"; break;
           case 1: $tmpDateFormat = "M d, Y"; break;
         }
         
  $tmpHeader = utf8_decode(esc_html__('Date','next-tiny-date')) . ";" . utf8_decode(esc_html__('Hour','next-tiny-date')) .";" . utf8_decode(esc_html__('Name','next-tiny-date')) . ";" . utf8_decode(esc_html__('Reason','next-tiny-date')) . ";" . utf8_decode(esc_html__('Phone number','next-tiny-date')) . ";" . utf8_decode(esc_html__('e-mail','next-tiny-date'));
  fwrite($fd,$tmpHeader . "\n");
  $NbExported = 0;
  $NbBookedRV = 0;
  for ($i=0;$i<$NbDayRVs;$i++)
      { $tmpLineAllRV = $retDayRVs[$i]->DayRVs;
        $tmpLineAllRV = str_replace('&amp;','&',$tmpLineAllRV);
        $listAllRV = explode(';',$tmpLineAllRV);
        $NbRV = count($listAllRV);
        
        for($rv=0;$rv<$NbRV;$rv++)
           { $tmpCurDate = $retDayRVs[$i]->id;
             $tmpYear = substr($tmpCurDate,0,4);
             $tmpMonth = substr($tmpCurDate,4,2);
             $tmpDay = substr($tmpCurDate,6,2);
             $tmpDate = date_create();
             date_date_set($tmpDate,$tmpYear,$tmpMonth,$tmpDay);
             $tmpDate = date_format($tmpDate,$tmpDateFormat);
             
             $tmpLineOneRV = trim($listAllRV[$rv]);
             if (substr($tmpLineOneRV,0,2) != "//")
                { $HourRV = substr($tmpLineOneRV,0,5);
                  $tmpLineOneRV = substr($tmpLineOneRV,5);
                  if ($tmpLineOneRV != "")
                     { $listOnePatient = explode('=>',trim($tmpLineOneRV));
                       if (trim($listOnePatient[0]) != "")
                          { fwrite($fd,$tmpDate . ";");        
                            fwrite($fd,$HourRV . ";");        
                            $PatientRV = substr(trim($listOnePatient[0]),1); fwrite($fd,utf8_decode($PatientRV) . ";");
                            $InfoPatient = trim($listOnePatient[1]);
                            $listInfoPatient = explode('#',trim($InfoPatient));
                            $ReasonRV = trim($listInfoPatient[0]);
                            $TelRV = trim($listInfoPatient[1]);
                            $EmailRV = trim($listInfoPatient[2]);
                            fwrite($fd,utf8_decode($ReasonRV) . ";" . $TelRV . ";" . $EmailRV . "\n");
                            $NbBookedRV++;
                          }
                       else
                          { if (!$opt_OnlyRV)
                               { $tmpLocked = utf8_decode(esc_html__('Me!','next-tiny-date')) . ";" . utf8_decode(esc_html__('Locked...','next-tiny-date'));
                                 fwrite($fd,$tmpDate . ";");        
                                 fwrite($fd,$HourRV . ";");
                                 fwrite($fd,$tmpLocked . "\n");
                               }
                          }
                     }
                  else
                     { if (!$opt_OnlyRV)
                          { fwrite($fd,$tmpDate . ";");        
                            fwrite($fd,$HourRV . ";");
                            fwrite($fd,";;;;\n");
                          }
                     }
                  $NbExported++;
                }
           }
      }
  
  if ($opt_OnlyRV)
     return $NbBookedRV;
  else
     return $NbExported;
}

function ntdt_GetDayName($parDay)
{ $parDay = $parDay % 7;
  switch($parDay)
        { case 0: return esc_html__('Sunday','next-tiny-date');    break;
          case 1: return esc_html__('Monday','next-tiny-date');    break;
          case 2: return esc_html__('Tuesday','next-tiny-date');   break;
          case 3: return esc_html__('Wednesday','next-tiny-date'); break;
          case 4: return esc_html__('Thursday','next-tiny-date');  break;
          case 5: return esc_html__('Friday','next-tiny-date');    break;
          case 6: return esc_html__('Saturday','next-tiny-date');  break;
        }
}

function ntdt_ModifyRV()
{ global $wpdb;
  $TableName = $wpdb->prefix . 'ntdtRV';

  $HourRV = sanitize_text_field($_REQUEST["h"]);
  $Indice = sanitize_text_field($_REQUEST["ind"]);
  $What = sanitize_text_field($_REQUEST["what"]);
  $tmpName = sanitize_text_field($_REQUEST["n"]);
  $tmpReason = sanitize_text_field($_REQUEST["r"]);
  $tmpEmail = sanitize_text_field($_REQUEST["e"]);

  $opt_FormatDateRV = get_option('optFormatDateRV',0);
  switch ($opt_FormatDateRV)
         { case 0: $tmpDateFormat = "d M Y"; break;
           case 1: $tmpDateFormat = "M d, Y"; break;
         }
         
if (isset($What))
   { $ToAdd = false;
     $strRV = $wpdb->get_var("SELECT DayRVs FROM $TableName WHERE id = '$Indice'");
     if ($strRV == "")
        { $ToAdd = true;
          $strRV = sanitize_text_field($_REQUEST["rvs"]);
        }
     
     if ($strRV != "")
        { $posStart = strpos($strRV,$HourRV);
          if ($posStart !== false)
             { $str1 = substr($strRV,0,$posStart);
             }
          $posEnd = strpos($strRV,";",$posStart);
          if ($posEnd !== false)
             { $str2 = substr($strRV,$posEnd);
             }
          switch($What)
                { case "Cancel":
                  $tmpRes = $str1 . $HourRV . $str2;
                  
                  $tmpCurDate = $Indice;
                  $tmpYear = substr($tmpCurDate,0,4); $tmpCurDate = substr($tmpCurDate,4);
                  $tmpMonth = substr($tmpCurDate,0,2); $tmpCurDate = substr($tmpCurDate,2);
                  $tmpDay = sprintf("%02d",$tmpCurDate);
                  $tmpDate = date_create();
                  date_date_set($tmpDate,$tmpYear,$tmpMonth,$tmpDay);
            
                  $tmpDateRV = esc_attr(date_format($tmpDate,$tmpDateFormat)) . ' - ' . esc_attr($HourRV);
                  $opt_Sender = get_option('optSender');
                  if ($opt_Sender != "") 
                     $tmpSender = $opt_Sender; 
                  else
                     $tmpSender = get_option('admin_email');

                  $headers = 'From: <' . $tmpSender . '>';
                  $tmpMsg = esc_html__('Dear','next-tiny-date') . ' ' . esc_attr($tmpName) . ",\n\n" . esc_attr($tmpDateRV) . ": " . esc_html__('Your appointement of','next-tiny-date') . " '" . esc_attr($tmpReason) . "' " . esc_html__('has been cancelled','next-tiny-date') . "!";
                  
                  $opt_Firm = get_option('optFirm');
                  if ($opt_Firm != "") 
                     $tmpFirm = $opt_Firm; 
                  else
                     $tmpFirm = get_option('siteurl');
                  $tmpMsg .= "\n\n" . esc_html__('Warmest regards','next-tiny-date') . "\n" . esc_attr($tmpFirm);
                  $ret = mail($tmpEmail,$tmpReason . " - " . esc_html__('Appointment cancelled','next-tiny-date') . ": " . $tmpDateRV,$tmpMsg,$headers);
                  break;
             
                  case "Lock":
                  //$tmpRes = $str1 .$HourRV . "=>XX" . $str2;
                  $tmpRes = $str1 .$HourRV . "=>" . $tmpReason . $str2;
                  break;
             
                  case "Unlock":
                  $tmpRes = $str1 . $HourRV  . $str2;
                  break;     
                }
           
          if ($ToAdd)
             { if ($wpdb->insert($TableName,array('id'=>$Indice,'DayRVs'=>$tmpRes)) === false)
     	            { echo "Cannot add data to the DB table: $TableName !<br>";
                  }
               else
                  { echo esc_html__('Data updated successfully','next-tiny-date') . ".<br>";
                  }
             }
          else
             { if($wpdb->update($TableName,array('DayRVs'=>$tmpRes),array('id'=>$Indice)) === false)
                 { echo "Cannot update data in the DB table: $TableName !<br>";
                 }
               else
                 { echo esc_html__('Data updated successfully','next-tiny-date') . ".<br>";
                 }
             }
        }
   }
  wp_die();   
}
add_action('wp_ajax_ntdt_ModifyRV','ntdt_ModifyRV');
add_action('wp_ajax_nopriv_ntdt_ModifyRV','ntdt_ModifyRV');

function ntdt_SendRV()
{ global $wpdb;
  $TableName = $wpdb->prefix . 'ntdtRV';

  $HourRV = sanitize_text_field($_REQUEST['h']);
  if ($HourRV != "")
     { $Indice = sanitize_text_field($_REQUEST['ind']);
       $ToAdd = false;
       $strRV = $wpdb->get_var("SELECT DayRVs FROM $TableName WHERE id = '$Indice'");
       if ($strRV == "")
          { $ToAdd = true;
            $strRV = sanitize_text_field($_REQUEST['rvs']);
          }

       $tmpReason = sanitize_text_field($_REQUEST['r']);
       $tmpName = sanitize_text_field($_REQUEST['n']);
       $tmpTel = sanitize_text_field($_REQUEST['t']);
       $tmpMail = sanitize_text_field($_REQUEST['e']);
       $tmpMsgRV = sanitize_text_field($_REQUEST['m']);
     
       if ($strRV != "")
          { $posStart = strpos($strRV,$HourRV);
            if ($posStart !== false)
               { $str1 = substr($strRV,0,$posStart);
               }
            $posEnd = strpos($strRV,";",$posStart);
            if ($posEnd !== false)
               { $str2 = substr($strRV,$posEnd);
                 $strStillOK = substr($strRV,$posStart,$posEnd-$posStart);   
               }
            else
               { $strStillOK = substr($strRV,$posStart);   
               }
            
            $posNoRV = strpos($strStillOK,"=>");
            if ($posNoRV)
               { echo "<b><font color=\"ff0000\">" . esc_html__('Appointment no longer available','next-tiny-date') . "!!!</font></b><br>" .  esc_html__('Please choose another date','next-tiny-date') . "<br>";
                 wp_die();  
               }
               
            $tmpRes = $str1 .$HourRV . "-" . $tmpName . "=>" . $tmpReason . "#" . $tmpTel . "#" . $tmpMail . $str2;

            $tmpCurDate = $Indice;
            $tmpYear = substr($tmpCurDate,0,4); $tmpCurDate = substr($tmpCurDate,4);
            $tmpMonth = substr($tmpCurDate,0,2); $tmpCurDate = substr($tmpCurDate,2);
            $tmpDay = sprintf("%02d",$tmpCurDate);
            $tmpDate = date_create();
            date_date_set($tmpDate,$tmpYear,$tmpMonth,$tmpDay);

            $opt_FormatDateRV = get_option('optFormatDateRV',0);
            switch ($opt_FormatDateRV)
                   { case 0: $tmpDateFormat = "d M Y"; break;
                     case 1: $tmpDateFormat = "M d, Y"; break;
                   }
         
         if ($ToAdd)
               { if ($wpdb->insert($TableName,array('id'=>$Indice,'DayRVs'=>$tmpRes)) === false)
     	              { echo "Cannot add data to the DB table: " . esc_attr($TableName) . " !<br>";
                    }
                 else
                    { echo "<b>" . esc_attr(date_format($tmpDate,$tmpDateFormat)) . " - " . esc_attr($HourRV) . "</b>: " . esc_html__('Appointment confirmed for','next-tiny-date') . " " . esc_attr($tmpReason);
                    }
               }
            else
               { if ($wpdb->update($TableName,array('DayRVs'=>$tmpRes),array('id'=>$Indice)) === false)
                    { echo "Cannot update data in the DB table: " . esc_attr($TableName) . " !<br>";
                    }
                 else
                    { echo "<b>" . esc_attr(date_format($tmpDate,$tmpDateFormat)) . " - " . esc_attr($HourRV) . "</b>: " . esc_html__('Appointment confirmed for','next-tiny-date') . " " . esc_attr($tmpReason);
                    }
               }
         
            $tmpDateRV = esc_attr(date_format($tmpDate,$tmpDateFormat)) . ' - ' . esc_attr($HourRV);
            $opt_Sender = get_option('optSender');
            if ($opt_Sender != "") 
               $tmpSender = $opt_Sender; 
            else
               $tmpSender = get_option('admin_email');

            $headers = 'From: <' . $tmpSender . '>';
            $tmpMsg = $tmpDateRV . "\n" . esc_attr($tmpReason) . "\n\n" . esc_attr($tmpName) . "\n" . esc_attr($tmpTel) . "\n" . esc_attr($tmpMail) . "\n" . esc_attr($tmpMsgRV);
            $ret = mail($tmpSender,$tmpReason . " - " . esc_html__('New appointment','next-tiny-date') . ": " . $tmpDateRV,$tmpMsg,$headers);
          
            $IsSendMail = get_option('optSendMail','1');
            if($IsSendMail)
              { $headers = 'From: <' . $tmpSender . '>';
                $tmpMsg = esc_html__('Dear','next-tiny-date') . ' ' . esc_attr($tmpName) . ",\n\n" . esc_attr($tmpDateRV) . ": " . esc_html__('Your appointement of','next-tiny-date') . " '" . esc_attr($tmpReason) . "' " . esc_html__('is confirmed','next-tiny-date') . ".";
                $opt_LnkRedirect = get_option('optRedirect');
                if ($opt_LnkRedirect != "")
                   $tmpMsg .= "\n\n" . esc_html__('Please be sure to check in on','next-tiny-date') . ": " . esc_attr($opt_LnkRedirect);
                   
                $opt_Firm = get_option('optFirm');
                if ($opt_Firm != "") 
                   $tmpFirm = $opt_Firm; 
                else
                   $tmpFirm = get_option('siteurl');
                $tmpMsg .= "\n\n" . esc_html__('Warmest regards','next-tiny-date') . "\n" . esc_attr($tmpFirm);
                $ret = mail($tmpMail,$tmpReason . " - " . esc_html__('Appointment confirmed','next-tiny-date') . ": " . $tmpDateRV,$tmpMsg,$headers);
              }
          }
    }
  else
    echo "<b><font color=\"ff0000\">" . esc_html__('No appointment available','next-tiny-date') . "!!!</font></b><br>" .  esc_html__('Please choose another date','next-tiny-date') . "<br>";
  
  wp_die();   
}
add_action('wp_ajax_ntdt_SendRV','ntdt_SendRV');
add_action('wp_ajax_nopriv_ntdt_SendRV','ntdt_SendRV');

function ntdt_StatsBar($parData,$parDataName,$parType)
{ $tmpIndCatList = count($parData);

  $BarHeight = ($tmpIndCatList) * 30 ;$BarWidth = 380;
  $imageBar = imagecreatetruecolor($BarWidth,$BarHeight);
  $transparent_color = imagecolorallocate($imageBar, 0xff, 0xff, 0xff);
  imagefill($imageBar,0,0,$transparent_color);
  imagecolortransparent($imageBar,$transparent_color);

  $tmpYearColor = "#2271B1";
  list($red,$green,$blue) = sscanf($tmpYearColor,"#%02x%02x%02x");
  $tmpChartColor = imageColorAllocate($imageBar,$red,$green,$blue);
  $tmpColorDarker = ntdt_DarkenColor($tmpYearColor);
  list($red,$green,$blue) = sscanf($tmpColorDarker,"#%02x%02x%02x");
  $tmpChartColorDarken = imageColorAllocate($imageBar,$red,$green,$blue);
    
  $upload_path = wp_upload_dir();     
  $path = $upload_path['basedir'];
  $FontPath = plugin_dir_path( __DIR__ ) . "fonts/Arial.ttf";
          
  $black = imagecolorallocate($imageBar, 0, 0, 0);
  $tmpMax = 0;
  for ($c=0;$c<$tmpIndCatList;$c++)
           { if ($parData[$c] > $tmpMax) $tmpMax = $parData[$c];
             $A_BarPerc[$c] = 0;
           }

  $tmpFrom = 5;
  $FontHeight = 0;
  $tmpStartX = 35;

  for ($c=0;$c<$tmpIndCatList;$c++)
      { if ($parData[$c] != 0)
           $A_BarPerc[$c] = $parData[$c] * ($BarWidth-$tmpStartX-10) / $tmpMax;
      }
  
  for ($c=0;$c<$tmpIndCatList;$c++)
      { if ($parData[$c] > 0) imagefilledrectangle($imageBar,$tmpStartX,$tmpFrom,$tmpStartX+$A_BarPerc[$c],$tmpFrom+20,$tmpChartColor);
        imagettftext($imageBar,9,0,$tmpStartX-20,$tmpFrom+15,$black,$FontPath,$parData[$c]);
        imagettftext($imageBar,9,0,40,$tmpFrom+15,$gray,$FontPath,$parDataName[$c]); 
        $tmpFrom += 30;
      }
     
  $opt_Pie3D = true;
  if ($opt_Pie3D)
     { $tmpFrom = 0;
       for ($c=0;$c<$tmpIndCatList;$c++)
           { if ($A_BarPerc[$c] != 0) 
                { $values = array($tmpStartX+5,$tmpFrom,
                                  $tmpStartX,$tmpFrom+5,
                                  $tmpStartX+$A_BarPerc[$c]-$FontHeight,$tmpFrom+5,
                                  $tmpStartX+$A_BarPerc[$c]-$FontHeight+5,$tmpFrom);
                  imagefilledpolygon($imageBar,$values,4,$tmpChartColorDarken);
                       
                  $values = array($tmpStartX+$A_BarPerc[$c]-$FontHeight+5,$tmpFrom,
                                  $tmpStartX+$A_BarPerc[$c]-$FontHeight+5,$tmpFrom+20,
                                  $tmpStartX+$A_BarPerc[$c]-$FontHeight,$tmpFrom+25,
                                  $tmpStartX+$A_BarPerc[$c]-$FontHeight,$tmpFrom+5);
                  imagefilledpolygon($imageBar,$values,4,$tmpChartColorDarken);
                }
             $tmpFrom += 30;
           }
     }
           
  imageline($imageBar,$tmpStartX,0,$tmpStartX,$BarHeight,$black);
  
  $gray = imagecolorallocate($imageBar,0x80,0x80,0x80);
  $style = Array($gray, 
                 $gray, 
                 $gray, 
                 $gray, 
                 IMG_COLOR_TRANSPARENT, 
                 IMG_COLOR_TRANSPARENT, 
                 IMG_COLOR_TRANSPARENT, 
                 IMG_COLOR_TRANSPARENT);
  imagesetstyle($imageBar, $style);
  $A_BarPercMax = $tmpMax * ($BarWidth-10) / $tmpMax;
  imageline($imageBar,$A_BarPercMax,0,$A_BarPercMax,$BarHeight,IMG_COLOR_STYLED);
  imagettftext($imageBar,8,90,$A_BarPercMax-5,20,$gray,$FontPath,$tmpMax);

  if ($tmpMax != 0)
     { ob_start();
       imagepng($imageBar);
       $img = ob_get_clean();
       echo "<img src='data:image/gif;base64," . base64_encode($img) . "'>";
       imagedestroy($imageBar);
     }
  if ($parType == "REASON")
     echo '<br><em><font color="#808080">' . esc_html__('Number of appointments per reason.','next-tiny-date') . '</font></em>';
  else
     echo '<br><em><font color="#808080">' . esc_html__('Number of appointments per day.','next-tiny-date') . '</font></em>';
}

function ntdt_Pie3D($parData,$parDataName,$parTotal)
{ $PieHeight = 220;$PieWidth = 220;
  $BarHeight = 220;$BarWidth = 250;
  $PieHeight = $PieHeight/2+30;

  $imagePie = imagecreatetruecolor($PieWidth,$PieHeight);
  $transparent_color = imagecolorallocate($imagePie, 0xff, 0xff, 0xff);
  imagefill($imagePie,0,0,$transparent_color);
  imagecolortransparent($imagePie,$transparent_color);
  
  $imageBar = imagecreatetruecolor($BarWidth,$BarHeight);
  $transparent_color = imagecolorallocate($imageBar, 0xff, 0xff, 0xff);
  imagefill($imageBar,0,0,$transparent_color);
  imagecolortransparent($imageBar,$transparent_color);
              
  $A_BasicColor[0] = "#ff0000";
  $A_BasicColor[1] = "#ffff00";
  $A_BasicColor[2] = "#00ff00";
  $A_BasicColor[3] = "#00ffff";
  $A_BasicColor[4] = "#0000ff";
  $A_BasicColor[5] = "#ff00ff";
  $A_BasicColor[6] = "#f0000f";
       
  $tmpTotalY = $parTotal;
  
  for($i=0;$i<=6;$i++)
     { list($red,$green,$blue) = sscanf($A_BasicColor[$i],"#%02x%02x%02x");
       $A_PieColor[$i] = imageColorAllocate($imagePie,$red,$green,$blue);
       $tmpColorDarker = ntdt_DarkenColor($A_BasicColor[$i]);
       list($red,$green,$blue) = sscanf($tmpColorDarker,"#%02x%02x%02x");
       $A_PieColorDarken[$i] = imageColorAllocate($imagePie,$red,$green,$blue);
     }
     
  for ($c=0;$c<=6;$c++)
      { $A_PiePerc[$c] = $parData[$c] * 100 / $tmpTotalY;
      }
          
  $CenterX = $PieWidth/2;$CenterY = $PieHeight/2;
  $CenterY = $PieHeight/2-15;
  $ArcWidth = 200;$ArcHeight = 200;

  $ArcHeight = $ArcHeight / 2;
  $Pie3DBorder = 30;
  $tmpFrom = 0; $tmpTo = 0;
  for ($c=$CenterY + $Pie3DBorder;$c>$CenterY;$c--)
      { for ($i=0;$i<=6;$i++)
            { if ($A_PiePerc[$i] != 0)
                 { $tmpNewTo = $tmpTo + $A_PiePerc[$i]*3.6;
                   $tmpFrom = $tmpTo;
                   $tmpTo = $tmpNewTo;
                   imagefilledarc($imagePie,$CenterX,$c,$ArcWidth,$ArcHeight,$tmpFrom,$tmpTo,$A_PieColorDarken[$i],IMG_ARC_PIE);
                 }
            }
      }
    
  $tmpFrom = 0; $tmpTo = 0;
  for ($i=0;$i<=6;$i++)
      { if ($A_PiePerc[$i] != 0)
           { $tmpNewTo = $tmpTo + $A_PiePerc[$i]*3.6;
             $tmpFrom = $tmpTo;
             $tmpTo = $tmpNewTo;
             imagefilledarc($imagePie,$CenterX,$CenterY,$ArcWidth,$ArcHeight,$tmpFrom,$tmpTo,$A_PieColor[$i],IMG_ARC_PIE);
           }
      }

       $tmpMax = 0;
       $FontHeight = 60;
       for ($c=0;$c<=6;$c++)
           { if ($parData[$c] > $tmpMax) $tmpMax = $parData[$c];
             $A_Bar[$c] = 0;
           }
       for ($c=0;$c<=6;$c++)
           { if ($parData[$c] != 0)
                $A_Bar[$c] = $parData[$c] * ($BarHeight-$FontHeight-10) / $tmpMax;
           }
                   
       $upload_path = wp_upload_dir();     
       $path = $upload_path['basedir'];
       $FontPath = plugin_dir_path( __DIR__ ) . "fonts/Arial.ttf";

       $tmpFrom = 0;
       $black = imagecolorallocate($imageBar, 0, 0, 0);
       for ($i=0;$i<=6;$i++)
           { if ($A_Bar[$i] != 0) imagefilledrectangle($imageBar,$tmpFrom,$BarHeight-$FontHeight,$tmpFrom+20,$BarHeight-$FontHeight-$A_Bar[$i],$A_PieColor[$i]);
             imagettftext($imageBar,8,90,$tmpFrom+15,$BarHeight+0,$black,$FontPath,$parDataName[$i]);
             $tmpFrom += 30;
           }

       if (true)
          { $tmpFrom = 0;
            for ($i=0;$i<=6;$i++)
                { if ($A_Bar[$i] != 0) 
                     { $values = array($tmpFrom+20,$BarHeight-$FontHeight,
                                       $tmpFrom+25,$BarHeight-$FontHeight-5,
                                       $tmpFrom+25,$BarHeight-$FontHeight-$A_Bar[$i]-5, //+5
                                       $tmpFrom+20,$BarHeight-$FontHeight-$A_Bar[$i]);
                       imagefilledpolygon($imageBar,$values,4,$A_PieColorDarken[$i]);
                       
                       $values = array($tmpFrom,$BarHeight-$FontHeight-$A_Bar[$i],
                                       $tmpFrom+5,$BarHeight-$FontHeight-$A_Bar[$i]-5,
                                       $tmpFrom+25,$BarHeight-$FontHeight-$A_Bar[$i]-5,
                                       $tmpFrom+20,$BarHeight-$FontHeight-$A_Bar[$i]);
                       imagefilledpolygon($imageBar,$values,4,$A_PieColorDarken[$i]);
                     }
                  $tmpFrom += 30;
                }
          }
          
       $tmpMedia = $tmpTotalY/(7);
       $tmpMediaPerc = $tmpMedia * ($BarHeight-$FontHeight-10) / $tmpMax;
       $gray = imagecolorallocate($imageBar,0x80,0x80,0x80);
       $style = Array(
                $gray, 
                $gray, 
                $gray, 
                $gray, 
                IMG_COLOR_TRANSPARENT, 
                IMG_COLOR_TRANSPARENT, 
                IMG_COLOR_TRANSPARENT, 
                IMG_COLOR_TRANSPARENT);
       imagesetstyle($imageBar, $style);
       imageline($imageBar,0,$BarHeight-$FontHeight-$tmpMediaPerc,$BarWidth-1,$BarHeight-$FontHeight-$tmpMediaPerc,IMG_COLOR_STYLED);

       imageline($imageBar,$BarWidth-20,$BarHeight-$FontHeight,$BarWidth-20,5,$black);
       imagettftext($imageBar,8,90,$BarWidth-8,$BarHeight-$FontHeight-$tmpMediaPerc-2,$gray,$FontPath,sprintf('%0.2f',$tmpMedia));
              
       imageline($imageBar,$BarWidth-1,$BarHeight-$FontHeight,0,$BarHeight-$FontHeight,$black);

  echo "<table><tr><td>";
  ob_start();
  imagepng($imageBar);
  $img = ob_get_clean();
  echo "<img src='data:image/gif;base64," . base64_encode($img) . "'>";
  imagedestroy($imageBar);
  echo '<br><em><font color="#808080">(' . esc_html__('Media line shown in gray','next-tiny-date') . ')</font></em>';

  echo "</td><td>";

  ob_start();
  imagepng($imagePie);
  $img = ob_get_clean();
  echo "<img src='data:image/gif;base64," . base64_encode($img) . "'><br>";
  imagedestroy($imagePie);
  echo '<br><em><font color="#808080">' . esc_html__('Number of appointments per day.','next-tiny-date') . '</font></em>';
  echo "</td></tr></table>";
}

function ntdt_DarkenColor($parRGB,$ParDark=2)
{ $diese = (strpos($parRGB,'#') !== false)?'#':'';
  $parRGB = (strlen($parRGB) == 7)?str_replace('#','',$parRGB):((strlen($parRGB)==6)?$parRGB:false);
  if (strlen($parRGB) != 6) return $diese . '000000';
  $ParDark = ($ParDark > 1)?$ParDark:1;
 
  list($red,$green,$blue) = str_split($parRGB,2);
  $red = sprintf("%02X",floor(hexdec($red)/($ParDark/1)));
  $green= sprintf("%02X",floor(hexdec($green)/($ParDark/1)));
  $blue = sprintf("%02X",floor(hexdec($blue)/($ParDark/1)));

  return $diese . $red . $green . $blue;
}