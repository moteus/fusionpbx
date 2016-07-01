<?php
/*
	FusionPBX
	Version: MPL 1.1

	The contents of this file are subject to the Mozilla Public License Version
	1.1 (the "License"); you may not use this file except in compliance with
	the License. You may obtain a copy of the License at
	http://www.mozilla.org/MPL/

	Software distributed under the License is distributed on an "AS IS" basis,
	WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
	for the specific language governing rights and limitations under the
	License.

	The Original Code is FusionPBX

	The Initial Developer of the Original Code is
	Mark J Crane <markjcrane@fusionpbx.com>
	Portions created by the Initial Developer are Copyright (C) 2008-2016
	the Initial Developer. All Rights Reserved.

	Contributor(s):
	Mark J Crane <markjcrane@fusionpbx.com>
	James Rose <james.o.rose@gmail.com>
*/

//includes
	include "root.php";
	require_once "resources/require.php";

//check permissions
	require_once "resources/check_auth.php";
	if (permission_exists('music_on_hold_view') || permission_exists('music_on_hold_global_view')) {
		//access granted
	}
	else {
		echo "access denied";
		exit;
	}

//add multi-lingual support
	$language = new text;
	$text = $language->get();

//increase the exucution time
	ini_set('max_execution_time', 7200);

//get moh records, build array
	$sql = "select * from v_music_on_hold ";
	$sql .= "where domain_uuid = '".$domain_uuid."' ";
	if (permission_exists('music_on_hold_global_view')) {
		$sql .= "or domain_uuid is null ";
	}
	$sql .= "order by domain_uuid desc, music_on_hold_rate asc, music_on_hold_name asc";
	$prep_statement = $db->prepare(check_sql($sql));
	$prep_statement->execute();
	$result = $prep_statement->fetchAll(PDO::FETCH_NAMED);
	if (count($result) > 0) {
		foreach($result as $row) {
			$stream_name_only = (substr_count($row['music_on_hold_name'], '/') > 0) ? substr($row['music_on_hold_name'], 0, strpos($row['music_on_hold_name'], '/')) : $row['music_on_hold_name'];
			$stream_domain_uuid = ($row['domain_uuid'] != '') ? $row['domain_uuid'] : '_global_';
			$stream_rate = $row['music_on_hold_rate'];

			$mohs[$stream_domain_uuid][$stream_name_only][$stream_rate]['uuid'] = $row['music_on_hold_uuid'];
			$mohs[$stream_domain_uuid][$stream_name_only][$stream_rate]['name'] = $row['music_on_hold_name']; //value may include '/[rate]'
			$mohs[$stream_domain_uuid][$stream_name_only][$stream_rate]['path'] = str_replace('$${sounds_dir}', $_SESSION['switch']['sounds']['dir'], $row['music_on_hold_path']);
			$mohs[$stream_domain_uuid][$stream_name_only][$stream_rate]['shuffle'] = $row['music_on_hold_shuffle'];
			$mohs[$stream_domain_uuid][$stream_name_only][$stream_rate]['channels'] = $row['music_on_hold_channels'];
			$mohs[$stream_domain_uuid][$stream_name_only][$stream_rate]['interval'] = $row['music_on_hold_interval'];
			$mohs[$stream_domain_uuid][$stream_name_only][$stream_rate]['chime_list'] = $row['music_on_hold_chime_list'];
			$mohs[$stream_domain_uuid][$stream_name_only][$stream_rate]['chime_freq'] = $row['music_on_hold_chime_freq'];
			$mohs[$stream_domain_uuid][$stream_name_only][$stream_rate]['chime_max'] = $row['music_on_hold_chime_max'];

			$stream_names[(($stream_domain_uuid == '_global_') ? 'global' : 'local')][] = $stream_name_only;
			$stream_paths[$row['music_on_hold_uuid']] = str_replace('$${sounds_dir}', $_SESSION['switch']['sounds']['dir'], $row['music_on_hold_path']);
			$stream_domains[$row['music_on_hold_uuid']][] = $row['domain_uuid'];
		}
	}
	unset($sql, $prep_statement, $result);
	foreach ($mohs as &$moh) { ksort($moh); }
	$stream_names['global'] = array_unique($stream_names['global']);
	$stream_names['local'] = array_unique($stream_names['local']);
	sort($stream_names['global'], SORT_NATURAL);
	sort($stream_names['local'], SORT_NATURAL);
	//echo "<pre>".print_r($mohs, true)."</pre>\n\n\n\n\n"; exit;

//download moh file
	if ($_GET['action'] == "download") {
		$stream_uuid = $_GET['id'];
		$stream_file = base64_decode($_GET['file']);
		$stream_full_path = path_join($stream_paths[$stream_uuid], $stream_file);

		session_cache_limiter('public');
		if (file_exists($stream_full_path)) {
			$fd = fopen($stream_full_path, "rb");
			if ($_GET['t'] == "bin") {
				header("Content-Type: application/force-download");
				header("Content-Type: application/octet-stream");
				header("Content-Type: application/download");
				header("Content-Description: File Transfer");
			}
			else {
				$stream_file_ext = pathinfo($stream_file, PATHINFO_EXTENSION);
				switch ($stream_file_ext) {
					case "wav" : header("Content-Type: audio/x-wav"); break;
					case "mp3" : header("Content-Type: audio/mpeg"); break;
					case "ogg" : header("Content-Type: audio/ogg"); break;
				}
			}
			header('Content-Disposition: attachment; filename="'.$stream_file.'"');
			header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
			header("Content-Length: ".filesize($stream_full_path));
			fpassthru($fd);
		}
		exit;
	}

//upload moh file
	if ($_POST['action'] == 'upload' && is_array($_FILES) && is_uploaded_file($_FILES['file']['tmp_name'])) {
		//determine name & scope
			if ($_POST['name_new'] != '') {
				$stream_scope = (permission_exists('music_on_hold_global_add')) ? $_POST['scope'] : 'local';
				$stream_name_only = strtolower($_POST['name_new']);
				$stream_new_name = true;
			}
			else {
				$tmp = explode('|', $_POST['name']);
				$stream_scope = $tmp[0];
				$stream_name_only = $tmp[1];
				$stream_new_name = false;
			}
		//get remaining values
			$stream_rate = $_POST['rate'];
			$stream_file_name_temp = $_FILES['file']['tmp_name'];
			$stream_file_name = $_FILES['file']['name'];
			$stream_file_ext = strtolower(pathinfo($stream_file_name, PATHINFO_EXTENSION));
		//check file type
			$valid_file_type = ($stream_file_ext == 'wav' || $stream_file_ext == 'mp3' || $stream_file_ext == 'ogg') ? true : false;
		//check permissions
			$has_permission = ( ($stream_scope == 'global' && permission_exists('music_on_hold_global_add')) || ($stream_scope == 'local' && permission_exists('music_on_hold_add')) ) ? true : false;
		//process, if possible
			if (!$valid_file_type) {
				$_SESSION['message'] = $text['message-unsupported_file_type'];
			}
			else if ($has_permission) {
				//strip slashes, replace spaces
					$slashes = array("/", "\\");
					$stream_name_only = str_replace($slashes, '', $stream_name_only);
					$stream_name_only = str_replace(' ', '_', $stream_name_only);
					$stream_file_name = str_replace($slashes, '', $stream_file_name);
					$stream_file_name = str_replace(' ', '-', $stream_file_name);
				//detect auto rate
					if ($stream_rate == 'auto') {
						$stream_rate = '';
						$path_rate = '48000';
						$stream_rate_auto = true;
					}
					else {
						$path_rate = $stream_rate;
						$stream_rate_auto = false;
					}
				//define default path
					$stream_path = path_join($_SESSION['switch']['sounds']['dir'], 'music',
						(($stream_scope == 'global') ? 'global' : $_SESSION['domain_name']),
						$stream_name_only, $path_rate
					);
				//flag to mark if there already has such moh profile
					$stream_path_found = false;
				//begin query
					$music_on_hold_uuid = uuid();
					$sql = "insert into v_music_on_hold ";
					$sql .= "( ";
					$sql .= "music_on_hold_uuid, ";
					$sql .= "domain_uuid, ";
					$sql .= "music_on_hold_name, ";
					$sql .= "music_on_hold_path, ";
					$sql .= "music_on_hold_rate, ";
					$sql .= "music_on_hold_shuffle, ";
					$sql .= "music_on_hold_channels, ";
					$sql .= "music_on_hold_interval, ";
					$sql .= "music_on_hold_timer_name, ";
					$sql .= "music_on_hold_chime_list, ";
					$sql .= "music_on_hold_chime_freq, ";
					$sql .= "music_on_hold_chime_max ";
					$sql .= ") values ";
				//new name
					if ($stream_new_name) {
						$music_on_hold_name = $stream_name_only;
						$music_on_hold_path = str_replace($_SESSION['switch']['sounds']['dir'], '$${sounds_dir}', $stream_path);
						$sql .= "( ";
						$sql .= "'".$music_on_hold_uuid."',";
						$sql .= (($stream_scope == 'global') ? 'null' : "'".$domain_uuid."'").", ";
						$sql .= "'".check_str($music_on_hold_name)."', ";
						$sql .= "'".check_str($music_on_hold_path)."', ";
						$sql .= "'".check_str($stream_rate)."', ";
						$sql .= "'false', ";
						$sql .= "1, ";
						$sql .= "20, ";
						$sql .= "'soft', ";
						$sql .= "null, ";
						$sql .= "null, ";
						$sql .= "null ";
						$sql .= ") ";
						unset($music_on_hold_name, $music_on_hold_path);
					}
				//existing name
					else {
						//get existing path
							$stream_settings = $mohs[(($stream_scope == 'global') ? '_global_' : $domain_uuid)][$stream_name_only][$stream_rate];
							if (($stream_rate_auto && $stream_name_only == $stream_settings['name']) ||
								(!$stream_rate_auto && path_join($stream_name_only, $stream_rate) == $stream_settings['name'])) {
								$stream_path = $stream_settings['path'];
								$stream_path_found = true;
							}
						//not found, finish query
							else {
								$music_on_hold_name = $stream_name_only;
								$music_on_hold_path = str_replace($_SESSION['switch']['sounds']['dir'], '$${sounds_dir}', $stream_path);

								$sql .= "( ";
								$sql .= "'".$music_on_hold_uuid."',";
								$sql .= (($stream_scope == 'global') ? 'null' : "'".$domain_uuid."'").", ";
								$sql .= "'".check_str($music_on_hold_name)."', ";
								$sql .= "'".check_str($music_on_hold_path)."', ";
								$sql .= "'".check_str($stream_rate)."', ";
								$sql .= "'false', ";
								$sql .= "1, ";
								$sql .= "20, ";
								$sql .= "'soft', ";
								$sql .= "null, ";
								$sql .= "null, ";
								$sql .= "null ";
								$sql .= ") ";
								unset($music_on_hold_name, $music_on_hold_path);
							}
					}
				//execute query
					if (!$stream_path_found) {
							$db->exec(check_sql($sql));
							unset($sql);
					}
				//check target folder, move uploaded file
					if (!is_dir($stream_path)) {
						event_socket_mkdir($stream_path);
					}
					if (is_dir($stream_path)) {
						if (copy($stream_file_name_temp, $stream_path.'/'.$stream_file_name)) {
							@unlink($stream_file_name_temp);
						}
					}
				//set message
					$_SESSION['message'] = $text['message-upload_completed'];
			}

		//redirect
			header("Location: music_on_hold.php");
			exit;
	}

//delete moh/file
	if ($_GET['action'] == "delete") {
		//get submitted values
			$stream_uuid = check_str($_GET['id']);
			$stream_file = check_str(base64_decode($_GET['file']));
		//check permissions
			if (
				($stream_domains[$stream_uuid] == '' && permission_exists('music_on_hold_global_delete')) ||
				($stream_domains[$stream_uuid] != '' && permission_exists('music_on_hold_delete'))
				) {
					$stream_path = $stream_paths[$stream_uuid];
				//remove specified file
					if ($stream_file != '') {
						@unlink(path_join($stream_path, $stream_file));
					}
				//remove all audio files
					else {
						array_map('unlink', glob(path_join($stream_path, '*.wav')));
						array_map('unlink', glob(path_join($stream_path, '*.mp3')));
						array_map('unlink', glob(path_join($stream_path, '*.ogg')));
					}
				//remove record and folder(s), if empty
					$file_count = 0;
					$file_count += ($files = glob(path_join($stream_path, '*.wav'))) ? count($files) : 0;
					$file_count += ($files = glob(path_join($stream_path, '*.mp3'))) ? count($files) : 0;
					$file_count += ($files = glob(path_join($stream_path, '*.ogg'))) ? count($files) : 0;
					if ($file_count == 0) {
						//remove rate folder
							rmdir($stream_path);
						//remove record
							$sql = "delete from v_music_on_hold ";
							$sql .= "where music_on_hold_uuid = '".$stream_uuid."' ";
							if (!permission_exists('music_on_hold_global_delete')) {
								$sql .= "and domain_uuid = '".$domain_uuid."' ";
							}
							//echo $sql; exit;
							$prep_statement = $db->prepare(check_sql($sql));
							$prep_statement->execute();
							unset($sql);
						//remove parent folder, if empty
							$parent_path = dirname($stream_path);
							$parent_path_files = glob(path_join($parent_path, '*'));
							if (sizeof($parent_files) === 0) { rmdir($parent_path); }
					}
				//set message
					$_SESSION['message'] = $text['message-delete'];
			}
		//redirect
			header("Location: music_on_hold.php");
			exit;
	}

//get variables used to control the order
	$order_by = check_str($_GET["order_by"]);
	$order = check_str($_GET["order"]);

//add the search term
	$search = check_str($_GET["search"]);
	if (strlen($search) > 0) {
		$sql_search = "and (";
		$sql_search .= "music_on_hold_name like '%".$search."%'";
		$sql_search .= "or music_on_hold_path like '%".$search."%'";
		$sql_search .= "or music_on_hold_rate like '%".$search."%'";
		$sql_search .= "or music_on_hold_shuffle like '%".$search."%'";
		$sql_search .= "or music_on_hold_channels like '%".$search."%'";
		$sql_search .= "or music_on_hold_interval like '%".$search."%'";
		$sql_search .= "or music_on_hold_timer_name like '%".$search."%'";
		$sql_search .= "or music_on_hold_chime_list like '%".$search."%'";
		$sql_search .= "or music_on_hold_chime_freq like '%".$search."%'";
		$sql_search .= "or music_on_hold_chime_max like '%".$search."%'";
		$sql_search .= ")";
	}

//additional includes
	require_once "resources/paging.php";

//prepare to page the results
	$sql = "select count(*) as num_rows from v_music_on_hold ";
	$sql .= "where domain_uuid = '$domain_uuid' ";
	$sql .= $sql_search;
	if (strlen($order_by)> 0) { $sql .= "order by $order_by $order "; }
	$prep_statement = $db->prepare($sql);
	if ($prep_statement) {
		$prep_statement->execute();
		$row = $prep_statement->fetch(PDO::FETCH_ASSOC);
		if ($row['num_rows'] > 0) {
				$num_rows = $row['num_rows'];
		}
		else {
				$num_rows = '0';
		}
	}

//prepare to page the results
	$rows_per_page = ($_SESSION['domain']['paging']['numeric'] != '') ? $_SESSION['domain']['paging']['numeric'] : 50;
	$param = "";
	$page = $_GET['page'];
	if (strlen($page) == 0) { $page = 0; $_GET['page'] = 0; }
	list($paging_controls, $rows_per_page, $var3) = paging($num_rows, $param, $rows_per_page);
	$offset = $rows_per_page * $page;

//set the default order by
	if (strlen($order_by) == 0) { $order_by = 'music_on_hold_name, music_on_hold_rate'; }
	if (strlen($order) == 0) { $order = 'asc'; }

//get the list
	$sql = "select * from v_music_on_hold ";
	$sql .= "where (";
	$sql .= "domain_uuid = '".$_SESSION['domain_uuid']."' ";
	if (permission_exists('music_on_hold_global_view')) {
		$sql .= "or domain_uuid is null ";
	}
	$sql .= ") ";
	$sql .= $sql_search;
	$sql .= "order by $order_by $order ";
	$sql .= "limit $rows_per_page offset $offset ";
	$prep_statement = $db->prepare(check_sql($sql));
	$prep_statement->execute();
	$streams = $prep_statement->fetchAll(PDO::FETCH_NAMED);
	unset ($prep_statement, $sql);

//include the header
	require_once "resources/header.php";
	$document['title'] = $text['title-moh'];

	echo "<script language='JavaScript' type='text/javascript'>\n";

	echo "	function check_filetype(file_input) {\n";
	echo "		file_ext = file_input.value.substr((~-file_input.value.lastIndexOf('.') >>> 0) + 2);\n";
	echo "		if (file_ext != 'mp3' && file_ext != 'wav' && file_ext != 'ogg' && file_ext != '') {\n";
	echo "			display_message(\"".$text['message-unsupported_file_type']."\", 'negative', '2750');\n";
	echo "		}\n";
	echo "		var selected_file_path = file_input.value;\n";
	echo "		selected_file_path = selected_file_path.replace(\"C:\\\\fakepath\\\\\",'');\n";
	echo "		document.getElementById('file_label').innerHTML = selected_file_path;\n";
	echo "	}\n";

	echo "	function name_mode(mode) {\n";
	echo "		if (mode == 'new') {\n";
	echo "			document.getElementById('name_select').style.display='none';\n";
	echo "			document.getElementById('btn_new').style.display='none';\n";
	if (permission_exists('music_on_hold_global_view') && permission_exists('music_on_hold_global_add')) {
		echo "		document.getElementById('scope').selectedIndex = 0;\n";
		echo "		document.getElementById('scope').style.display='';\n";
	}
	echo "			document.getElementById('name_new').style.display='';\n";
	echo "			document.getElementById('btn_select').style.display='';\n";
	echo "			document.getElementById('name_new').focus();\n";
	echo "		}\n";
	echo "		else if (mode == 'select') {\n";
	if (permission_exists('music_on_hold_global_view') && permission_exists('music_on_hold_global_add')) {
		echo "		document.getElementById('scope').style.display='none';\n";
		echo "		document.getElementById('scope').selectedIndex = 0;\n";
	}
	echo "			document.getElementById('name_new').style.display='none';\n";
	echo "			document.getElementById('name_new').value = '';\n";
	echo "			document.getElementById('btn_select').style.display='none';\n";
	echo "			document.getElementById('name_select').selectedIndex = 0;\n";
	echo "			document.getElementById('name_select').style.display='';\n";
	echo "			document.getElementById('btn_new').style.display='';\n";
	echo "		}\n";
	echo "	}\n";

	echo "</script>\n";
	echo "<script language='JavaScript' type='text/javascript' src='".PROJECT_PATH."/resources/javascript/reset_file_input.js'></script>\n";

	echo "<b>".$text['label-moh']."</b>";
	echo "<br /><br />\n";
	echo $text['desc-moh']."\n";
	echo "<br /><br />\n";

//show the upload form
	if (permission_exists('music_on_hold_add') || permission_exists('music_on_hold_global_add')) {
		echo "<b>".$text['label-upload-moh']."</b>\n";
		echo "<br><br>\n";

		echo "<form name='frm' id='frm' method='post' enctype='multipart/form-data'>\n";
		echo "<input name='action' type='hidden' value='upload'>\n";

		echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
		echo "<tr>\n";

		echo "<td width='40%' style='vertical-align: top;'>\n";

		echo "	<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
		echo "		<tr>\n";
		echo "			<td class='vncell' width='30%' valign='top' nowrap='nowrap'>\n";
		echo "				".$text['label-category']."\n";
		echo "			</td>\n";
		echo "			<td class='vtable' width='70%' style='white-space: nowrap;'>\n";
		echo "				<select name='name' id='name_select' class='formfld' style='width: auto;'>\n";
		if (is_array($stream_names['local']) && sizeof($stream_names['local']) > 0) {
			if (permission_exists('music_on_hold_global_view') && permission_exists('music_on_hold_global_add')) {
				echo "			<optgroup label='".$text['option-local']."'>\n";
			}
			foreach ($stream_names['local'] as $local_stream_name) {
				echo "				<option value='local|".$local_stream_name."'>".str_replace('_',' ',$local_stream_name)."</option>\n";
			}
			if (permission_exists('music_on_hold_global_view') && permission_exists('music_on_hold_global_add')) {
				echo "			</optgroup>\n";
			}
		}
		if (permission_exists('music_on_hold_global_add') && is_array($stream_names['global']) && sizeof($stream_names['global']) > 0) {
			echo "				<optgroup label='".$text['option-global']."'>\n";
			foreach ($stream_names['global'] as $global_stream_name) {
				echo "				<option value='global|".$global_stream_name."' style='font-style: italic;'>".str_replace('_',' ',$global_stream_name)."</option>\n";
			}
			echo "				</optgroup>\n";
		}
		echo "				</select>";
		echo "				<button type='button' id='btn_new' class='btn btn-default list_control_icon' style='margin-left: 3px;' onclick=\"name_mode('new');\"><span class='glyphicon glyphicon-plus'></span></button>";
		if (permission_exists('music_on_hold_global_view') && permission_exists('music_on_hold_global_add')) {
			echo "			<select name='scope' id='scope' class='formfld' style='display: none;' onchange=\"document.getElementById('name_new').focus();\">\n";
			echo "				<option value='local' selected>".$text['option-local']."</option>\n";
			echo "				<option value='global'>".$text['option-global']."</option>\n";
			echo "	    	</select>\n";
		}
		echo "				<input class='formfld' style='width: 100px; display: none;' type='text' name='name_new' id='name_new' maxlength='255' value=''>";
		echo "				<button type='button' id='btn_select' class='btn btn-default list_control_icon' style='display: none; margin-left: 3px;' onclick=\"name_mode('select');\"><span class='glyphicon glyphicon-list-alt'></span></button>";
		echo "			</td>\n";
		echo "		</tr>\n";
		echo "	</table>\n";

		echo "</td>\n";
		echo "<td width='30%' style='vertical-align: top;'>\n";

		echo "	<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
		echo "		<tr>\n";
		echo "			<td class='vncell' width='30%' valign='top' nowrap='nowrap'>\n";
		echo "				".$text['label-sampling']."\n";
		echo "			</td>\n";
		echo "			<td class='vtable' width='70%'>\n";
		echo "				<select id='rate' name='rate' class='formfld' style='width: auto;'>\n";
		echo "					<option value='auto'>".$text['option-default']."</option>\n";
		echo "					<option value='8000'>8 kHz</option>\n";
		echo "					<option value='16000'>16 kHz</option>\n";
		echo "					<option value='32000'>32 kHz</option>\n";
		echo "					<option value='48000'>48 kHz</option>\n";
		echo "				</select>\n";
		echo "			</td>\n";
		echo "		</tr>\n";
		echo "	</table>\n";

		echo "</td>\n";
		echo "<td width='30%' style='vertical-align: top;'>\n";

		echo "	<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
		echo "		<tr>\n";
		echo "			<td class='vncell' width='30%' valign='top' nowrap='nowrap'>\n";
		echo "				".$text['label-file-path'];
		echo "			</td>\n";
		echo "			<td class='vtable' width='70%'>\n";
		echo "				<input name='file' id='file' type='file' style='display: none;' onchange=\"check_filetype(this);\">";
		echo "				<label id='file_label' for='file' class='txt' style='width: 150px; overflow: hidden; white-space: nowrap;'>".$text['label-select_a_file']."</label>\n";
		echo "			</td>\n";
		echo "		</tr>\n";
		echo "	</table>\n";

		echo "</td>\n";

		echo "</tr>\n";
		echo "</table>\n";

		echo "<div style='float: right; margin-top: 6px;'>";
		echo "	<input type='reset' class='btn' value='".$text['button-reset']."' onclick=\"reset_file_input('file'); document.getElementById('file_label').innerHTML = '".$text['label-select_a_file']."'; name_mode('select'); return true;\">\n";
		echo "	<input name='submit' type='submit' class='btn' id='upload' value='".$text['button-upload']."'>\n";
		echo "</div>\n";

		echo "</form>\n";
	}

//set the row styles
	$c = 0;
	$row_style["0"] = "row_style0";
	$row_style["1"] = "row_style1";

//set the variable with an empty string
	$previous_name = '';

//show the array of data
	if (is_array($streams)) {

		//start the table
			echo "<table class='tr_hover' width='100%' border='0' cellpadding='0' cellspacing='0' style='margin-bottom: 3px;'>\n";

		//loop through the array
			foreach($streams as $row) {

				//set the variables
					$music_on_hold_name = $row['music_on_hold_name'];
					$music_on_hold_rate = $row['music_on_hold_rate'];
			
					$stream_scope = $row['domain_uuid'];
					if (!$stream_scope) $stream_scope = '_global_';
					$tmp = explode('/', $row['music_on_hold_name']);
					$stream_name_only = $tmp[0];
					$stream_rate = $row['music_on_hold_rate'];
					$stream_settings = $mohs[$stream_scope][$stream_name_only][$stream_rate]; 

				//add vertical space
					echo "<tr class='tr_link_void'><td colspan='5'><div style='width: 1px; height: 10px;'></div></td></tr>\n";

				//add the name
					if ($previous_name != $music_on_hold_name) {
						echo "<tr class='tr_link_void'><td colspan='5'><div style='width: 1px; height: 20px;'></div></td></tr>\n";
						echo "<tr class='tr_link_void'>\n";
						echo "	<td colspan='5'><b><i>".$music_on_hold_name."</i></b>";
						if ($row['domain_uuid'] == null) { 
							echo "&nbsp;&nbsp;- ".$text['label-global']."\n";
						}
						echo "	</td>\n";
						echo "</tr>\n";
					}

				//determine if rate was set to auto or not
					$auto_rate = (strlen($music_on_hold_rate) == 0) ? true : false;

				//determine icons to show
					$stream_icons = array();
					$i = 0;
					if (permission_exists('music_on_hold_path')) {
						$stream_icons[$i]['glyphicon'] = 'glyphicon-folder-open';
						$stream_icons[$i]['title'] = $stream_paths[$row['music_on_hold_uuid']];
						$i++;
					}
					if ($stream_settings['shuffle'] == 'true') {
						$stream_icons[$i]['glyphicon'] = 'glyphicon-random';
						$stream_icons[$i]['title'] = $text['label-shuffle'];
						$i++;
					}
					if ($stream_settings['chime_list'] != '') {
						$stream_icons[$i]['glyphicon'] = 'glyphicon-bell';
						$stream_icons[$i]['title'] = $text['label-chime_list'].': '.$stream_settings['chime_list'];
						$i++;
					}
					if ($stream_settings['channels'] == '2') {
						$stream_icons[$i]['glyphicon'] = 'glyphicon-headphones';
						$stream_icons[$i]['title'] = $text['label-stereo'];
						$stream_icons[$i]['margin'] = 6;
						$i++;
					}
					if (is_array($stream_icons) && sizeof($stream_icons) > 0) {
						foreach ($stream_icons as $stream_icon) {
							$icons .= "<span class='glyphicon ".$stream_icon['glyphicon']." icon_glyphicon_body' title='".$stream_icon['title']."' style='width: 12px; height: 12px; margin-left: ".(($stream_icon['margin'] != '') ? $stream_icon['margin'] : 8)."px; vertical-align: text-top; cursor: help;'></span>";
						}
					}

				//show the table header
					echo "	<tr>\n";
					echo "		<th class='listhdr'>".(($auto_rate) ? ($music_on_hold_rate/1000).' kHz / '.$text['option-default'] : ($music_on_hold_rate/1000)." kHz").$icons."</th>\n";
					echo "		<th class='listhdr' style='width: 55px;'>".$text['label-tools']."</th>\n";
					echo "		<th class='listhdr' style='width: 65px; text-align: right; white-space: nowrap;'>".$text['label-file-size']."</th>\n";
					echo "		<th class='listhdr' style='width: 150px; text-align: right;'>".$text['label-uploaded']."</th>\n";
					echo "		<td class='".((!permission_exists('music_on_hold_global_delete')) ? 'list_control_icon' : 'list_control_icons')." tr_link_void'>";
					if (permission_exists('music_on_hold_edit')) {
						echo "<a href='music_on_hold_edit.php?id=".$row['music_on_hold_uuid']."' alt='".$text['button-edit']."'>$v_link_label_edit</a>";
					}
					if (permission_exists('music_on_hold_delete')) {
						echo "<a href='music_on_hold_delete.php?id=".$row['music_on_hold_uuid']."' alt='".$text['button-delete']."' onclick=\"return confirm('".$text['confirm-delete']."')\">$v_link_label_delete</a>";
					}
					echo 		"</td>\n";
					echo "	</tr>";
					unset($stream_icons, $icons);

				//add the uuid of to the link
					if (permission_exists('music_on_hold_edit')) {
						$tr_link = "href='music_on_hold_edit.php?id=".$row['music_on_hold_uuid']."'";
					}

				//get the music on hold path
					$stream_path = $row['music_on_hold_path'];
					$stream_path = str_replace("\$\${sounds_dir}",$_SESSION['switch']['sounds']['dir'], $stream_path);

				//show the files
					if (file_exists($stream_path)) {
						$stream_files = array_merge(glob($stream_path.'/*.wav'), glob($stream_path.'/*.mp3'), glob($stream_path.'/*.ogg'));
						foreach ($stream_files as $stream_file_path) {
							$stream_file = strtolower(pathinfo($stream_file_path, PATHINFO_BASENAME));
							$stream_file_size = byte_convert(filesize($stream_file_path));
							$stream_file_date = date("M d, Y H:i:s", filemtime($stream_file_path));
							$stream_file_ext = pathinfo($stream_file, PATHINFO_EXTENSION);
							switch ($stream_file_ext) {
								case "wav" : $stream_file_type = "audio/wav"; break;
								case "mp3" : $stream_file_type = "audio/mpeg"; break;
								case "ogg" : $stream_file_type = "audio/ogg"; break;
							}
							$row_uuid = uuid();
							echo "<tr id='recording_progress_bar_".$row_uuid."' style='display: none;'><td colspan='4' class='".$row_style[$c]." playback_progress_bar_background' style='padding: 0; border: none;'><span class='playback_progress_bar' id='recording_progress_".$row_uuid."'></span></td></tr>\n";
							$tr_link = "href=\"javascript:recording_play('".$row_uuid."');\"";
							echo "<tr ".$tr_link.">\n";
							echo "	<td class='".$row_style[$c]."'>".str_replace('_', '_&#8203;', $stream_file)."</td>\n";
							echo "	<td valign='top' class='".$row_style[$c]." row_style_slim tr_link_void'>";
							echo 		"<audio id='recording_audio_".$row_uuid."' style='display: none;' preload='none' ontimeupdate=\"update_progress('".$row_uuid."')\" onended=\"recording_reset('".$row_uuid."');\" src='?action=download&id=".$row['music_on_hold_uuid']."&file=".base64_encode($stream_file)."' type='".$stream_file_type."'></audio>";
							echo 		"<span id='recording_button_".$row_uuid."' onclick=\"recording_play('".$row_uuid."')\" title='".$text['label-play']." / ".$text['label-pause']."'>".$v_link_label_play."</span>";
							echo 		"<span onclick=\"recording_stop('".$row_uuid."')\" title='".$text['label-stop']."'>".$v_link_label_stop."</span>";
							echo "	</td>\n";
							echo "	<td valign='top' class='".$row_style[$c]."' style='text-align: right; white-space: nowrap;'>".$stream_file_size."</td>\n";
							echo "	<td valign='top' class='".$row_style[$c]."' style='text-align: right;'>".$stream_file_date."</td>\n";
							echo "	<td valign='top' class='".((!permission_exists('music_on_hold_global_delete')) ? 'list_control_icon' : 'list_control_icons')."'>\n";
							echo 		"<a href='?action=download&id=".$row['music_on_hold_uuid']."&file=".base64_encode($stream_file)."' title='".$text['label-download']."'>".$v_link_label_download."</a>";
							if ( ($domain_uuid == '_global_' && permission_exists('music_on_hold_global_delete')) || ($domain_uuid != '_global_' && permission_exists('music_on_hold_delete')) ) {
								echo 	"<a href='?action=delete&id=".$row['music_on_hold_uuid']."&file=".base64_encode($stream_file)."' onclick=\"return confirm('".$text['confirm-delete']."')\">".$v_link_label_delete."</a>";
							}
							echo "	</td>\n";
							echo "</tr>\n";
							$c = ($c) ? 0 : 1;
						}
					}

				//set the previous music_on_hold_name
					$previous_name = $music_on_hold_name;

				//toggle the light highlighting
					if ($c==0) { $c=1; } else { $c=0; }
			} //end foreach
			unset($sql, $result, $row_count);

		//end the table
			echo "</table>\n";

	} //end if results

	echo "<tr>\n";
	echo "<td colspan='11' align='left'>\n";
	echo "	<table width='100%' cellpadding='0' cellspacing='0'>\n";
	echo "	<tr>\n";
	echo "		<td width='33.3%' nowrap='nowrap'>&nbsp;</td>\n";
	echo "		<td width='33.3%' align='center' nowrap='nowrap'>$paging_controls</td>\n";
	echo "		<td class='list_control_icons'>";
	echo "			&nbsp;";
	echo "		</td>\n";
	echo "	</tr>\n";
 	echo "	</table>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>";
	echo "<br /><br />";

//include the footer
	require_once "resources/footer.php";

?>
