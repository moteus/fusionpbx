--
-- Lua-ODBC backend to FusionPBX database class
--

local log  = require "resources.functions.log".database
local utils = require "resources.functions.database.utils"
local odbc = require "odbc.dba"

local function remove_null(row, null, null_value)
  local o = {}
  for k, v in pairs(row) do
    if v == null then
      o[k] = null_value
    else
      o[k] = tostring(v)
    end
  end
  return o
end

local OdbcDatabase = {} do
OdbcDatabase.__index = OdbcDatabase
OdbcDatabase._backend_name = 'ODBC'

function OdbcDatabase.new(name)
  local self = setmetatable({}, OdbcDatabase)

  local connection_string = assert(database[name])

  local typ, dsn, user, password = connection_string:match("^(.-)://(.-):(.-):(.-)$")
  assert(typ == 'odbc', "unsupported connection string:" .. connection_string)

  self._dbh = odbc.Connect(dsn, user, password)

  return self
end

function OdbcDatabase:_query(sql, params, fn)
  self._rows_affected = nil
  params = params or {}
  if fn then
    return self._dbh:neach(sql, params, function(row)
      local n = tonumber((fn(remove_null(row, odbc.NULL, ""))))
      if n and n ~= 0 then
        return true
      end
    end)
  end
  local ok, err = self._dbh:exec(sql, params)
  if not ok then return nil, err end
  self._rows_affected = ok
  return self._rows_affected
end

function OdbcDatabase:query(sql, fn)
  return OdbcDatabase._query(self, sql, nil, fn)
end

function OdbcDatabase:parameter_query(sql, params, fn)
  sql, params = utils.apply_params(self, sql, params)
  if not sql then return nil, params end

  return OdbcDatabase._query(self, sql, params, fn)
end

function OdbcDatabase:affected_rows()
  return self._rows_affected;
end

function OdbcDatabase:release()
  if self._dbh then
    self._dbh:destroy()
    self._dbh = nil
  end
end

function OdbcDatabase:connected()
  return self._dbh and self._dbh:connected()
end

function OdbcDatabase:dbtype()
  return 'odbc'
end

end

return OdbcDatabase