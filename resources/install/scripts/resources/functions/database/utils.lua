local NULL, DEFAULT = {}, {}

local param_pattern = "%f[%a%d:][:]([%a][%a%d_]*)"

local replace_params = function(sql, callback)
  local err

  local str = string.gsub(sql, param_pattern, function(param)
    -- skip after error
    if err then return end

    local value

    value, err = callback(param)
    if value then return value end
  end)

  if err then return nil, err end
  return str
end

local function next_placeholder(db, array, value)
  local n = #array + 1
  array[n] = value
  return (db:dbtype() == 'pgsql') and ('$' .. n) or '?'
end

local function apply_params_value(db, sql, params)
  params = params or {}

  return replace_params(sql, function(param)
    local v, t = params[param], type(params[param])

    if "string"   == t then return db:quote(v)      end
    if "number"   == t then return tostring(v)      end
    if "boolean"  == t then return v and '1' or '0' end
    if db.NULL    == v then return 'NULL'           end
    if db.DEFAULT == v then return 'DEFAULT'        end

    return nil, 'invalid value ' .. tostring(v) .. ' (' .. t .. ') for parameter: `' .. param .. '`'
  end)
end

local function apply_params(db, sql, params)
  local array = {}

  local str, err = replace_params(sql, function(param)
    local value = params[param]
    local vtype = type(value)

    if "string"   == vtype then return next_placeholder(db, array, value) end
    if "number"   == vtype then return tostring(value)      end
    if "boolean"  == vtype then return value and '1' or '0' end
    if db.NULL    == value then return 'NULL'               end
    if db.DEFAULT == value then return 'DEFAULT'            end

    return nil, 'invalid value ' .. tostring(value) .. ' (' .. vtype .. ') for parameter: `' .. param .. '`'
  end)

  if err then return nil, err end

  return str, array
end

local sql_escape

if freeswitch then
  local api = require "resources.functions.api"
  sql_escape = function(str)
    return api:execute('sql_escape', str)
  end
else
  sql_escape = function(str)
    return (string.gsub(str, "'", "''"))
  end
end

return {
  NULL               = NULL;
  DEFAULT            = DEFAULT;
  replace_params     = replace_params;
  apply_params_value = apply_params_value;
  apply_params       = apply_params;
  sql_escape         = sql_escape;
}