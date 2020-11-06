local redis_key, version_key = KEYS[1], KEYS[2]
local expected_version, new_version = ARGV[1], ARGV[2]
local value, ex, px, nx, xx, keepttl = ARGV[3], ARGV[4], ARGV[5], ARGV[6], ARGV[7], ARGV[8]

if ex == '' then
  ex = nil
end

if px == '' then
  px = nil
end

nx = nx == 'true'
xx = xx == 'true'
keepttl = keepttl == 'true'

local actual_version = redis.call('get', version_key)

if not nx and not xx and actual_version and expected_version ~= actual_version then
  redis.call('del', redis_key, version_key)
  return nil
end

local set_args = {value}

if ex ~= nil then
  table.insert(set_args, 'ex')
  table.insert(set_args, ex)
elseif px ~= nil then
  table.insert(set_args, 'px')
  table.insert(set_args, px)
end

if nx then
  table.insert(set_args, 'nx')
elseif xx then
  table.insert(set_args, 'xx')
end

if keepttl then
  table.insert(set_args, 'keepttl')
end

if #set_args == 1 then
  -- MSET always returns 'OK'.
  return redis.call('mset', redis_key, value, version_key, new_version)
end

local succeeded = redis.call('set', redis_key, unpack(set_args))

if succeeded then
  redis.call('set', version_key, new_version)
end

return succeeded
