require "resources.functions.config"
require "resources.functions.split"

local log           = require "resources.functions.log".mwi_subscribe
local file          = require "resources.functions.file"
local Database      = require "resources.functions.database"
local ievents       = require "resources.functions.ievents"
local IntervalTimer = require "resources.functions.interval_timer"
local cache         = require "resources.functions.cache"
local api           = require "resources.functions.api"

local vm_to_uuid_sql = [[SELECT v.voicemail_uuid
FROM v_voicemails as v inner join v_domains as d on v.domain_uuid = d.domain_uuid
WHERE v.voicemail_id = '%s' and d.domain_name = '%s']]

local vm_messages_sql = [[SELECT
( SELECT count(*)
  FROM v_voicemail_messages
  WHERE voicemail_uuid = %s
  AND (message_status is null or message_status = '')
) as new_messages,

( SELECT count(*)
  FROM v_voicemail_messages
  WHERE voicemail_uuid = %s
  AND message_status = 'saved'
) as saved_messages
]]

local function vm_message_count(account, use_cache)
	local id, domain_name = split_first(account, '@', true)
	if not domain_name then return end

	-- FusionPBX support only numeric voicemail id
	if not tonumber(id) then
		log.warningf('non numeric voicemail id: %s', id)
		return
	end

	local dbh = Database.new('system')
	if not dbh then return end

	local uuid
	if use_cache and cache.support() then
		local uuid = cache.get('voicemail_uuid:' .. account)
		if not uuid then
			local sql = string.format(vm_to_uuid_sql,
				dbh:escape(id), dbh:escape(domain_name)
			)
			uuid = dbh:first_value(sql)

			if uuid and #uuid > 0 then
				cache.set('voicemail_uuid:' .. account, uuid, 3600)
			end
		end
	end

	local sql 
	if uuid and #uuid > 0 then
		sql = string.format(vm_messages_sql,
			dbh:quoted(uuid), dbh:quoted(uuid)
		)
	else
		local uuid_sql = '(' .. string.format(vm_to_uuid_sql,
			dbh:escape(id), dbh:escape(domain_name)
		) .. ')'

		sql = string.format(vm_messages_sql,
			uuid_sql, uuid_sql
		)
	end

	local row = sql and dbh:first_row(sql)

	dbh:release()

	if not row then return end

	return row.new_messages, row.saved_messages
end

local function mwi_notify(account, new_messages, saved_messages)
	local event = freeswitch.Event("message_waiting")
	if (new_messages == "0") then
		event:addHeader("MWI-Messages-Waiting", "no")
	else
		event:addHeader("MWI-Messages-Waiting", "yes")
	end
	event:addHeader("MWI-Message-Account", "sip:" .. account)
	event:addHeader("MWI-Voice-Message", new_messages.."/"..saved_messages.." (0/0)")
	return event:fire()
end

local sleep    = 60000
local pid_file = scripts_dir .. "/run/mwi_subscribe.tmp"
local pid = api:execute("create_uuid") or tostring(api:getTime())
file.write(pid_file, pid)

log.notice("start");

local timer = IntervalTimer.new(sleep):start()

for event in ievents("MESSAGE_QUERY", 1, timer:rest()) do
	if (not event) or (timer:rest() < 1000) then
		if not file.exists(pid_file) then break end
		local stored = file.read(pid_file)
		if stored and stored ~= pid then break end
		timer:restart()
	end

	if event then
		-- log.notice("event:" .. event:serialize("xml"));
		local account_header = event:getHeader('Message-Account')
		if account_header then
			local proto, account = split_first(account_header, ':', true)

			if (not account) or (proto ~= 'sip' and proto ~= 'sips') then
				log.warningf("invalid format for voicemail id: %s", account_header)
			else
				local new_messages, saved_messages = vm_message_count(account)
				if not new_messages then
					log.warningf('can not find voicemail: %s', account)
				else
					log.noticef('voicemail %s has %s/%s messages', account, new_messages, saved_messages)
					mwi_notify(account, new_messages, saved_messages)
				end
			end
		end
	end
end

log.notice("stop")
