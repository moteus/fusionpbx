require "resources.functions.trim"

local api = api or freeswitch.API()

function channel_variable(uuid, name)
	local result = api:executeString("uuid_getvar " .. uuid .. " " .. name)

	if result:sub(1, 4) == '-ERR' then return nil, result end
	if result == '_undef_' then return false end

	return result
end

function channel_evalute(uuid, cmd)
	local result = api:executeString("eval uuid:" .. uuid .. " " .. cmd)

	if result:sub(1, 4) == '-ERR' then return nil, result end
	if result == '_undef_' then return false end

	return result
end

function channel_transfer(uuid, ext, typ, ctx)
	local cmd = ("uuid_transfer %s %s %s %s"):format(uuid, ext, typ, ctx)
	local result = trim(api:executeString(cmd))
	if result:sub(1, 4) == '-ERR' then return nil, result end
	if result == '_undef_' then return false end
	return result
end

function channel_kill(uuid, cause)
	cause = cause or 'CALL_REJECTED'
	local res = trim(api:executeString("uuid_kill " .. uuid .. " " .. cause))
	return res == '+OK'
end
