--	Part of FusionPBX
--	Copyright (C) 2013-2015 Mark J Crane <markjcrane@fusionpbx.com>
--	All rights reserved.
--
--	Redistribution and use in source and binary forms, with or without
--	modification, are permitted provided that the following conditions are met:
--
--	1. Redistributions of source code must retain the above copyright notice,
--	  this list of conditions and the following disclaimer.
--
--	2. Redistributions in binary form must reproduce the above copyright
--	  notice, this list of conditions and the following disclaimer in the
--	  documentation and/or other materials provided with the distribution.
--
--	THIS SOFTWARE IS PROVIDED ''AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES,
--	INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
--	AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
--	AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
--	OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
--	SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
--	INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
--	CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
--	ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
--	POSSIBILITY OF SUCH DAMAGE.

--record message menu
	function record_menu(type, tmp_file, greeting_id)
		if (session:ready()) then
			--clear the dtmf digits variable
				dtmf_digits = '';
			--flush dtmf digits from the input buffer
				session:flushDigits();
			--to listen to the recording press 1
				if (session:ready()) then
					if (string.len(dtmf_digits) == 0) then
						dtmf_digits = macro(session, "to_listen_to_recording", 1, 100, '');
					end
				end
			--to save the recording press 2
				if (session:ready()) then
					if (string.len(dtmf_digits) == 0) then
						dtmf_digits = macro(session, "to_save_recording", 1, 100, '');
					end
				end
			--to re-record press 3
				if (session:ready()) then
					if (string.len(dtmf_digits) == 0) then
						dtmf_digits = macro(session, "to_rerecord", 1, 3000, '');
					end
				end
			--process the dtmf
				if (session:ready()) then
					if (dtmf_digits == "1") then
						--listen to the recording
							session:streamFile(tmp_file);
							--session:streamFile(voicemail_dir.."/"..voicemail_id.."/msg_"..uuid.."."..vm_message_ext);
						--record menu (1=listen, 2=save, 3=re-record)
							record_menu(type, tmp_file, greeting_id);
					elseif (dtmf_digits == "2") then
						--save the message
							dtmf_digits = '';
							macro(session, "message_saved", 1, 100, '');
							if (type == "message") then
								--goodbye
									macro(session, "goodbye", 1, 100, '');
								--hangup the call
									session:hangup();
							end
							if (type == "greeting") then
								--remove old greeting file, and rename tmp file
									local real_file = string.gsub(tmp_file, ".tmp", "");
									if (file_exists(real_file)) then
										os.remove(real_file);
									end
									if (file_exists(tmp_file)) then
										os.rename(tmp_file, real_file);
									end
									if (storage_type == "base64") then
										--delete the greeting (retain local for better responsiveness)
										--os.remove(real_file);
									end

								--if base64, encode file
									if (storage_type == "base64") then
										--include the file io
											local file = require "resources.functions.file"

										--read file content as base64 string
											greeting_base64 = assert(file.read_base64(real_file));
									end

								--delete the previous recording
									local sql = "delete from v_voicemail_greetings ";
									sql = sql .. "where domain_uuid = :domain_uuid ";
									sql = sql .. "and voicemail_id = :voicemail_id ";
									sql = sql .. "and greeting_id = :greeting_id ";
									local params = {domain_uuid = domain_uuid, 
										voicemail_id = voicemail_id, greeting_id = greeting_id};
									--freeswitch.consoleLog("notice", "[SQL] DELETING: " .. greeting_id .. "\n");
									dbh:query(sql, params);

								--get a new uuid
									voicemail_greeting_uuid = api:execute("create_uuid");

								--save the message to the voicemail messages
									local array = {}
									table.insert(array, "INSERT INTO v_voicemail_greetings ");
									table.insert(array, "(");
									table.insert(array, "voicemail_greeting_uuid, ");
									table.insert(array, "domain_uuid, ");
									table.insert(array, "voicemail_id, ");
									table.insert(array, "greeting_id, ");
									if (storage_type == "base64") then
										table.insert(array, "greeting_base64, ");
									end
									table.insert(array, "greeting_name, ");
									table.insert(array, "greeting_filename ");
									table.insert(array, ") ");
									table.insert(array, "VALUES ");
									table.insert(array, "( ");
									table.insert(array, ":greeting_uuid, ");
									table.insert(array, ":domain_uuid, ");
									table.insert(array, ":voicemail_id, ");
									table.insert(array, ":greeting_id, ");
									if (storage_type == "base64") then
										table.insert(array, ":greeting_base64, ");
									end
									table.insert(array, ":greeting_name, ");
									table.insert(array, ":greeting_filename ");
									table.insert(array, ") ");
									sql = table.concat(array, "\n");
									params = {
										greeting_uuid = voicemail_greeting_uuid;
										domain_uuid = domain_uuid;
										voicemail_id = voicemail_id;
										greeting_id = greeting_id;
										greeting_base64 = greeting_base64;
										greeting_name = "Greeting "..greeting_id;
										greeting_filename = "greeting_"..greeting_id..".wav"
									};
									--freeswitch.consoleLog("notice", "[SQL] INSERTING: " .. greeting_id .. "\n");
									if (debug["sql"]) then
										freeswitch.consoleLog("notice", "[voicemail] SQL: " .. sql .. "; params:" .. json.encode(params) .. "\n");
									end
									if (storage_type == "base64") then
										local dbh = Database.new('system', 'base64');
										dbh:query(sql, params);
										dbh:release();
									else
										dbh:query(sql, params);
									end

								--use the new greeting
									sql = {}
									table.insert(sql, "update v_voicemails ");
									table.insert(sql, "set greeting_id = :greeting_id ");
									table.insert(sql, "where domain_uuid = :domain_uuid ");
									table.insert(sql, "and voicemail_id = :voicemail_id ");
									sql = table.concat(sql, "\n");
									params = {domain_uuid = domain_uuid, greeting_id = greeting_id,
										voicemail_id = voicemail_id};
									dbh:query(sql, params);

								advanced();
							end
							if (type == "name") then
								advanced();
							end
					elseif (dtmf_digits == "3") then
						--re-record the message
							timeouts = 0;
							dtmf_digits = '';
							if (type == "message") then
								record_message();
							end
							if (type == "greeting") then
								--remove temporary greeting file, if any
									if (file_exists(tmp_file)) then
										os.remove(tmp_file);
									end
								record_greeting(greeting_id);
							end
							if (type == "name") then
								record_name();
							end
					elseif (dtmf_digits == "*") then
						if (type == "greeting") then
							--remove temporary greeting file, if any
								if (file_exists(tmp_file)) then
									os.remove(tmp_file);
								end
						end
						--hangup
							if (session:ready()) then
								dtmf_digits = '';
								macro(session, "goodbye", 1, 100, '');
								session:hangup();
							end
					else
						if (session:ready()) then
							timeouts = timeouts + 1;
							if (timeouts < max_timeouts) then
								record_menu(type, tmp_file, greeting_id);
							else
								if (type == "message") then
									dtmf_digits = '';
									macro(session, "message_saved", 1, 100, '');
									macro(session, "goodbye", 1, 1000, '');
									session:hangup();
								end
								if (type == "greeting") then
									--remove temporary greeting file, if any
										if (file_exists(tmp_file)) then
											os.remove(tmp_file);
										end
									advanced();
								end
								if (type == "name") then
									advanced();
								end
							end
						end
					end
				end
		end
	end
