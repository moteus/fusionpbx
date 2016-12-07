--
-- Lua-ODBC-Pool backend to FusionPBX database class
--

local log      = require "resources.functions.log".database
local utils    = require "resources.functions.database.utils"
local odbc     = require "odbc.dba"
local odbcpool = require "odbc.dba.pool"

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

-----------------------------------------------------------
local OdbcPoolDatabase = {} do
OdbcPoolDatabase.__index = OdbcPoolDatabase
OdbcPoolDatabase._backend_name = 'ODBC Pool'

function OdbcPoolDatabase.new(name)
  local self = setmetatable({}, OdbcPoolDatabase)
  self._cli           = odbcpool.client(name)
  self._timeout       = 1000
  self._rows_affected = nil
  return self
end

function OdbcPoolDatabase:_query(sql, params, fn)
  self._rows_affected = nil
  params = params or {}
  local cli = self._cli

  local ok, err
  if fn then
    ok, err = cli:acquire(self._timeout, function(dbh)
      local ok, err = dbh:neach(sql, params, function(row)
        local n = tonumber((fn(remove_null(row, odbc.NULL, ""))))
        if n and n ~= 0 then
          return true
        end
      end)
      if err and not ok then
        log.errf("Can not execute sql: %s\n%s", tostring(err), sql)
      end
      return not not dbh:connected(), true
    end)
  else
    ok, err = cli:acquire(self._timeout, function(dbh)
      local ok, err = dbh:exec(sql, params)
      if err and not ok then
        log.errf("Can not execute sql: %s\n%s", tostring(err), sql)
      end
      self._rows_affected = ok
      return not not dbh:connected(), ok
    end)
  end

  if err and not ok then
    log.errf("Can not get database handle: %s", tostring(err))
  end

  return ok
end

function OdbcPoolDatabase:query(sql, fn)
  return OdbcPoolDatabase._query(self, sql, nil, fn)
end

function OdbcPoolDatabase:parameter_query(sql, params, fn)
  sql, params = utils.apply_params(self, sql, params)
  if not sql then return nil, params end

  return OdbcPoolDatabase._query(self, sql, params, fn)
end

function OdbcPoolDatabase:affected_rows()
  return self._rows_affected;
end

function OdbcPoolDatabase:release()
  self._cli = nil
end

function OdbcPoolDatabase:connected()
  return not not self._cli
end

function OdbcPoolDatabase:dbtype()
  return 'odbc'
end

end
-----------------------------------------------------------

return OdbcPoolDatabase