<?php

	//application details
		$apps[$x]['name'] = "Yealink";
		$apps[$x]['uuid'] = "82626eb7-f2dd-4c25-bd0f-06235222ce76";
		$apps[$x]['category'] = "Vendor";
		$apps[$x]['subcategory'] = "";
		$apps[$x]['version'] = "1.0";
		$apps[$x]['license'] = "Mozilla Public License 1.1";
		$apps[$x]['url'] = "http://www.fusionpbx.com";
		$apps[$x]['description']['en-us'] = "";
		$apps[$x]['description']['ar-eg'] = "";
		$apps[$x]['description']['de-at'] = "";
		$apps[$x]['description']['de-ch'] = "";
		$apps[$x]['description']['de-de'] = "";
		$apps[$x]['description']['es-cl'] = "";
		$apps[$x]['description']['es-mx'] = "";
		$apps[$x]['description']['fr-ca'] = "";
		$apps[$x]['description']['fr-fr'] = "";
		$apps[$x]['description']['he-il'] = "";
		$apps[$x]['description']['it-it'] = "";
		$apps[$x]['description']['nl-nl'] = "";
		$apps[$x]['description']['pl-pl'] = "";
		$apps[$x]['description']['pt-br'] = "";
		$apps[$x]['description']['pt-pt'] = "";
		$apps[$x]['description']['ro-ro'] = "";
		$apps[$x]['description']['ru-ru'] = "";
		$apps[$x]['description']['sv-se'] = "";
		$apps[$x]['description']['uk-ua'] = "";

	//default settings
		$y=0;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "fc2fa8cd-b14e-48e3-99bd-7c01c9d6208d";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_time_zone";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "-5";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Time zone ranges from -11 to +12";
		$y=0;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "9009cb3d-5eb8-41fa-bcbb-ee6b63cdccfa";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_time_zone_name";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "United States-Eastern Time";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Time zone name example United States-Mountain Time";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "7f4a1607-4cbe-49f5-8cd2-6d599b89bd9b";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_time_format";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "1";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "0-12 Hour, 1-24 Hour";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "166b27d118604154-88d3-5e15781e7bbb";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_date_format";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "3";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "0-WWW MMM DD (default), 1-DD-MMM-YY, 2-YYYY-MM-DD, 3-DD/MM/YYYY, 4-MM/DD/YY, 5-DD MMM YYYY, 6-WWW DD MMM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "05bcbb47-06f6-4c81-a1c9-cdf2982d60a7";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_rport";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "boolean";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "1";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Send the response back to the source it came from.";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "70894b7b-2edd-41ad8526060589df4e11";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_session_timer";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "boolean";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "1";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "SIP Session Timers";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "aa200d6f-c04842039838-8dd05fa31bd5";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_retransmission";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "boolean";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "1";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Retransmission";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "bf122bc9-c9bf-497c-9dda-95d125293eaf";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_srtp_encryption";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "0";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "f0704072-c3d9-48df-b89b-2aea6035b3c4";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_call_waiting";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "1";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Call Waiting 1=enabled 0=disable";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "2c3445ff-0339-43d4-8762-3c87ec37be57";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_outbound_proxy_fallback_interval";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "numeric";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "3600";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Integer from 0 to 65535";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "78f05ec5-d59c-4033-aeb5-62c9f46999cb";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_rfc2543_hold";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "numeric";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "0";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Default 0";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "be02b916-3419-4bc7-881e46711134577c";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_blf_led_mode";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "numeric";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "0";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "The value is 0(default) or 1.";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "2f5e8429-6890-4da7-84ae-65c9c29b5091";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_trust_ctrl";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "numeric";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "1";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "(0-Disabled;1-Enabled)";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "561035ed-e388-4d5e-888d-367455faa05c";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_direct_ip_call_enable";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "numeric";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "0";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "(0-Disabled;1-Enabled) ";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "c45264b7-6e85-42e4-b245-f22caff091d4";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_hide_feature_access_codes_enable";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "numeric";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "0";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "(0-Disabled;1-Enabled)";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "ada36bd5-51ab-4bf8-a2ad-bc39671b2a3a";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_missed_call_power_led_flash_enable";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "numeric";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "0";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "(0-Disabled:power indicator LED is off;1-Enabled:power indicator LED is solid red)";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "fb13e647-ca3f-4da6-901c-584b8cde4055";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_voice_mail_popup_enable";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "numeric";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "0";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Display Voice Mail Popup";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "dc4a391f-25b7-44bb-accb-efb23f20662c";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_missed_call_popup_enable";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "numeric";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "0";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Display Missed Call Popup";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "85c7d304-2c91-447d-b139-c05d2499ea7d";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_cid_source";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "numeric";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "0";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "The type of SIP header(s) to carry the caller ID; 0-FROM (default), 1-PAI 2-PAI-FROM, 3-PRID-PAI-FROM, 4-PAI-RPID-FROM, 5-RPID-FROM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "6fd955ef-2f65-41f0-84cf-5b3ad917cc20";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_dtmf_hide";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "numeric";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "1";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " 0-Disabled 1-Enabled";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "968c9f53-305c-4fbd-a7dd-4dd6099e829e";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_sip_listen_port";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "numeric";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "5060";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " 5060 default";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "3f903069-c0fc-4bf1-9c33-907bf238475e";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_firmware_url";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "https://server.yourdomain.com/app/yealink/resources/firmware";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Base URL for Yealink Firmware";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "e8f3e617-a6b3-4859-af59-04e2349f3d49";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_firmware_cp860";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "cp860-37.81.0.10.rom";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Filename of the CP860 firmware ROM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "7a769f2f-2833-4b90-9789-ff96a4723773";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_firmware_cp960";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "cp960-73.80.0.25.rom";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Filename of the CP960 firmware ROM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "311d2739-a340-42f5-8e3e-6e6ecce1faa2";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_firmware_t29g";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "t29g-46.81.0.110.rom";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Filename of the T29G firmware ROM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "758509689226450f-a3e1-414ec1d610ee";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_firmware_t38g";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "t38g-38.70.0.185.rom";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Filename of the T38G firmware ROM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "7223c3a7-5ef9-46a8-84be-39338a6788a6";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_firmware_t40g";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "t40g-76.81.0.110.rom";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Filename of the T40G firmware ROM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "c5d9b7ea-8e3b44289845438be7be0baa";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_firmware_t40p";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "t40p-54.81.0.110.rom";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Filename of the T40P firmware ROM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "f714e8a0-9753-48e0-9674-0f4efc7e4ff1";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_firmware_t41p";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "t41p-36.81.0.110.rom";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Filename of the T41P firmware ROM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "9480f346-c038-462b-b440329383290b84";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_firmware_t41s";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "t41s-66.81.0.110.rom";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Filename of the T41S firmware ROM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "84eb10e2-bd9f-4bd0-8073-ac8cf002e7f0";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_firmware_t42g";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "t42g-29.81.0.110.rom";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Filename of the T42G firmware ROM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "768f216e-fc19-4c97-b843-03d48ce70c4e";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_firmware_t42s";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "t42s-66.81.0.110.rom";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Filename of the T42S firmware ROM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "5fb6ef46-0a7f-4bb6-b415-49b437454321";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_firmware_t46g";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "t46g-28.81.0.110.rom";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Filename of the T46G firmware ROM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "4e2b8fcb-7d06-4553-becf-9904e1331c4c";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_firmware_t46s";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "t46s-66.81.0.110.rom";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Filename of the T46S firmware ROM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "0276b965-0ef6-493a-bdd0-b9786bf8aad7";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_firmware_t48g";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "t48g-35.81.0.110.rom";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Filename of the T48G firmware ROM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "77314a84-6304-47d8-a6b4-fb6d12b8804f";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_firmware_t48s";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "t48s-66.81.0.110.rom";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Filename of the T48S firmware ROM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "c53fdce7-b0eb-49ef-9d58-5a3a212bf5bf";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_firmware_t49g";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "t49g-51.80.0.100.rom";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Filename of the T49Gfirmware ROM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "804003fc-f0e9-4b3b-a35b-6c2742f965eb";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_firmware_t52s";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "t52s-70.81.0.10.rom";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Filename of the T52Sfirmware ROM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "7378c0b632424989-8af6-903dfb437285";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_firmware_t54s";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "t54s-70.81.0.10.rom";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Filename of the T54S firmware ROM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "4ffd1ad7-1b00-41f7-8e9319412127245a";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_firmware_t56a";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "t56a-58.80.0.25.rom";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Filename of the T56A firmware ROM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "616d6358-d07e-4e42-8560-7de0f42cd5b1";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_firmware_t58a";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "t58a-58.80.0.25.rom";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Filename of the T58A firmware ROM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "2cdd8b87-db72-4ad1-b18b-745fc1f7e906";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_firmware_t58v";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "t58v-58.80.0.25.rom";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Filename of the T58V firmware ROM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "a39fc1af-9c30-46c7-a564-212e4af0331f";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_firmware_vp530";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "vp530-23.70.0.40.rom";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Filename of the VP530 firmware ROM";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "68eba481-aeb1-4c7e-a4e4-630946abc0e7";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_network_vpn_enable";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "boolean";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "1";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " (0-Disabled;1-Enabled)";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "85675560-82cf-48a5-8056-227c01a44f37";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "yealink_openvpn_url";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "hxxps://replace-this.url/openvpn.tar";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " (URL within 511 characters)";

?>
