---
-- @usage
--  -- Use default backend
--  dbh = Database.new("system")
--  .....
--
-- @usage
--  -- Use LuaSQL backend
--  dbh = Database.backend.luasql("system")
--  .....

require 'resources.functions.config'

require "resources.functions.split"

local log = require "resources.functions.log".database

local BACKEND = database and database.backend
if type(BACKEND) ~= 'table' then BACKEND = {main = BACKEND} end
BACKEND.main = BACKEND.main or 'native'

local unpack = unpack or table.unpack

-----------------------------------------------------------

local NULL, DEFAULT = {}, {}

local param_pattern = "[:]([%a][%a%d%%_]*)"

local uuid_pattern = '^%x%x%x%x%x%x%x%x%-%x%x%x%x%-%x%x%x%x%-%x%x%x%x%-%x%x%x%x%x%x%x%x%x%x%x%x$'

local params_hints = {
  is_extension     = function(s) return (#s <= 30) and (not string.find(s, "[^%a%d%._-]")) end;
  is_uuid          = function(s) return not not string.match(s, uuid_pattern)              end;
  is_domain        = function(s) return (#s <= 50) and (not string.find(s, "[^%a%d%._-]")) end;
  is_path          = function(s) return not string.find(s, "[^%a%d%.\\/_-]")               end;
  is_numeric       = function(s) return not string.find(s, "[^%d]")                        end;
  is_alpha_numeric = function(s) return not string.find(s, "[^%a%d]")                      end;
}

--
-- Substitude named parameters to query
--
-- @tparam string sql query text
-- @tparam table params values for parameters
-- @treturn[1] string new sql query
-- @treturn[2] nil
-- @treturn[2] string error message
--
local function apply_params(db, sql, params)
  params = params or {}

  local err
  local str = string.gsub(sql, param_pattern, function(param)
    if err then return end

    local hint
    param, hint = split_first(param, '%', true)

    local value = params[param]

    assert(value ~= nil, 'invalid value for parameter: `' .. param .. '`')

    if hint then
      local hint_fn = params_hints[hint]
      if not hint_fn then
        err = 'unsupported parameter hint: `' .. hint .. '`'
      elseif not hint_fn(value) then
        err = 'invalid value for parameter: `' .. param .. '`'
      end
      if err then return end
    end

    local vtype = type(value)

    if "string"  == vtype then return db:quote(value)      end
    if "number"  == vtype then return tostring(value)      end
    if "boolean" == vtype then return value and '1' or '0' end
    if NULL      == value then return 'NULL'               end
    if DEFAULT   == value then return 'DEFAULT'            end

    err = 'invalid value for parameter: `' .. param .. '`'
  end)

  if err then return nil, err end

  return str
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

-----------------------------------------------------------
local installed_classes = {}
local default_backend = FsDatabase
local function new_database(backend, backend_name)
  local class = installed_classes[backend]
  if class then return class end

  local Database = {} do
  Database.__index = Database
  Database.__base = backend or default_backend
  Database = setmetatable(Database, Database.__base)

  Database.NULL = NULL

  Database.DEFAULT = NULL

  function Database.new(...)
    local self = Database.__base.new(...)
    setmetatable(self, Database)
    return self
  end

  function Database:backend_name()
    return backend_name
  end

  function Database:_apply_params(sql, params)
    return apply_params(self, sql, params)
  end

  function Database:query(sql, ...)
    local params, callback

    local argc = select('#', ...)

    if argc > 0 then
      local p = select(argc, ...)
      if (p == nil) or (type(p) == 'function') then
        callback = p
        argc = argc - 1
      end
    end

    if argc > 0 then
      local p = select(argc, ...)
      if (p == nil) or (type(p) == 'table') then
        params = p
        argc = argc - 1
      end
    end

    assert(argc == 0, 'invalid argument #' .. tostring(argc))

    if params then
      -- backend supports parameters natively
      if self.__base.parameter_query then
        return self.__base.parameter_query(self, sql, params, callback)
      end

      -- use emulation of parametes
      local err
      sql, err = self:_apply_params(sql, params)
      if not sql then return nil, err end
    end

    return self.__base.query(self, sql, callback)
  end

  function Database:first_row(sql, params)
    local result
    local ok, err = self:query(sql, params, function(row)
      result = row
      return 1
    end)
    if not ok then return nil, err end
    return result
  end

  function Database:first_value(sql, params)
    local result, err = self:first_row(sql, params)
    if not result then return nil, err end
    local k, v = next(result)
    return v
  end

  function Database:first(sql, ...)
    local t = type((...))
    local has_params = (t == 'nil') or (t == 'table')

    local result, err
    if has_params then
      result, err = self:first_row(sql, (...))
    else
      result, err = self:first_row(sql)
    end

    if not result then return nil, err end

    local t, n, c = {}, select('#', ...), 0
    for i = (has_params and 2 or 1), n do
      c = c + 1
      t[c] = result[(select(i, ...))]
    end

    return unpack(t, 1, c)
  end

  function Database:fetch_all(sql)
    local result = {}
    local ok, err = self:query(sql, function(row)
      result[#result + 1] = row
    end)
    if (not ok) and err then return nil, err end
    return result
  end

  function Database:escape(str)
    return sql_escape(str)
  end

  function Database:quote(str)
    return "'" .. self:escape(str) .. "'"
  end

  function Database.__self_test__(...)
    log.info('self_test Database - ' ..  Database._backend_name)
    local db = Database.new(...)

    assert(db:connected())

    do local x = 0
    db:query("select 1 as v union all select 2 as v", function(row)
      x = x + 1
      return 1
    end)
    assert(x == 1, ("Got %d expected %d"):format(x, 1))
    end

    do local x = 0
    db:query("select 1 as v union all select 2 as v", function(row)
      x = x + 1
      return -1
    end)
    assert(x == 1, ("Got %d expected %d"):format(x, 1))
    end

    do local x = 0
    db:query("select 1 as v union all select 2 as v", function(row)
      x = x + 1
      return 0
    end)
    assert(x == 2, ("Got %d expected %d"):format(x, 2))
    end

    do local x = 0
    db:query("select 1 as v union all select 2 as v", function(row)
      x = x + 1
      return true
    end)
    assert(x == 2, ("Got %d expected %d"):format(x, 2))
    end

    do local x = 0
    db:query("select 1 as v union all select 2 as v", function(row)
      x = x + 1
      return false
    end)
    assert(x == 2, ("Got %d expected %d"):format(x, 2))
    end

    do local x = 0
    db:query("select 1 as v union all select 2 as v", function(row)
      x = x + 1
      return "1"
    end)
    assert(x == 1, ("Got %d expected %d"):format(x, 2))
    end

    assert("1" == db:first_value("select 1 as v union all select 2 as v"))

    local t = assert(db:first_row("select '1' as v union all select '2' as v"))
    assert(t.v == "1")

    t = assert(db:fetch_all("select '1' as v union all select '2' as v"))
    assert(#t == 2)
    assert(t[1].v == "1")
    assert(t[2].v == "2")

    local a, b = assert(db:first("select '1' as b, '2' as a", 'a', 'b'))
    assert(a == "2")
    assert(b == "1")

    -- assert(nil == db:first_value("some non sql query"))

    -- select NULL
    local a = assert(db:first_value("select NULL as a"))
    assert(a == "")

    -- escape
    local values = {"hello';select 'world", "hello'"}
    for _, value in ipairs(values) do
      local a = assert(db:first_value(
        string.format("select '%s' as a", db:escape(value))
      ))
      assert(a == value)
    end

    -- close
    db:release()
    assert(not db:connected())

    -- second close
    db:release()
    assert(not db:connected())

    local db = Database.new(...)

    assert(db:connected())

    -- test substitude parameters
    t = assert(db:first_row('select :p1 as p1, :p2 as p2', {p1 = 'hello', p2 = 'world'}))
    assert(t.p1 == 'hello')
    assert(t.p2 == 'world')

    -- test escape string
    -- `sql_escape` on freeswitch do `trim`
    if not freeswitch then
      -- test no trim value
      local v = " hello "
      a = assert(db:first_value('select :p1', {p1 = v}))
      assert(a == v)

      -- test newline 
      -- On Windows with pgsql it replace `\n` to `\r\n`)
      local v = "\r\nhello\r\nworld\r\n"
      a = assert(db:first_value('select :p1', {p1 = v}))
      assert(a == v, string.format('%q', tostring(a)))
    end

    -- test backslash
    local v = "\\hello\\world\\"
    a = assert(db:first_value('select :p1', {p1 = v}))
    assert(a == v, string.format('%q', tostring(a)))

    -- test single quote
    local v = "'hello''world'''"
    a = assert(db:first_value('select :p1', {p1 = v}))
    assert(a == v, string.format('%q', tostring(a)))

    -- test empty string
    local v = ""
    a = assert(db:first_value('select :p1', {p1 = v}))
    assert(a == v, string.format('%q', tostring(a)))

    db:release()
    log.info('self_test Database - pass')
  end

  end

  installed_classes[backend] = Database
  return Database
end
-----------------------------------------------------------

-----------------------------------------------------------
local Database = {} do

local backend_loader = setmetatable({}, {__index = function(self, backend)
  local class = require("resources.functions.database." .. backend)
  local database = new_database(class, backend)
  self[backend] = function(...)
    return database.new(...)
  end
  return self[backend]
end})

Database.backend = backend_loader

function Database.new(dbname, role)
  local backend = role and BACKEND[role] or BACKEND.main
  return Database.backend[backend](dbname)
end

Database.__self_test__ = function(backends, ...)
  for _, backend in ipairs(backends) do
    local t = Database.backend[backend]
    t(...).__self_test__(...)
  end
end;

end
-----------------------------------------------------------

return Database