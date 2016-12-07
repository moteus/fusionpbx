--
-- Native backend to FusionPBX database class
--

local log = require "resources.functions.log".database
local utils = require "resources.functions.database.utils"

assert(freeswitch, "Require FreeSWITCH environment")

-----------------------------------------------------------
local FsDatabase = {} do

require "resources.functions.file_exists"
FsDatabase.__index = FsDatabase
FsDatabase._backend_name = 'native'

function FsDatabase.new(name)
  local dbh = assert(name)
  local dbtype = 'sqlite'

  if type(name) == 'string' then
    if name == 'switch' and file_exists(database_dir.."/core.db") then
      dbh = freeswitch.Dbh("sqlite://"..database_dir.."/core.db")
    else
      local connection_string = assert(database[name])
      dbtype = string.match(connection_string, "^(.-)://")
      dbh = freeswitch.Dbh(connection_string)
    end
  end
  assert(dbh:connected())

  local self = setmetatable({
    _dbh = dbh;
    _dbtype = dbtype;
  }, FsDatabase)

  return self
end

function FsDatabase:query(sql, fn)
  if fn then
    return self._dbh:query(sql, fn)
  end
  return self._dbh:query(sql)
end

if freeswitch.Dbh.SUPPORTS_PARAMS then

function FsDatabase:parameter_query(sql, params, fn)
  sql, params = utils.apply_params(self, sql, params)
  if not sql then return nil, params end

  if fn then
    return self._dbh:query(sql, params, fn)
  end
  return self._dbh:query(sql, params)
end

end

function FsDatabase:affected_rows()
  if self._dbh then
    return self._dbh:affected_rows()
  end
end

function FsDatabase:release()
  if self._dbh then
    self._dbh:release()
    self._dbh = nil
  end
end

function FsDatabase:connected()
  return self._dbh and self._dbh:connected()
end

function FsDatabase:dbtype()
  return self._dbtype
end

end
-----------------------------------------------------------

return FsDatabase