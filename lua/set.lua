local redis_key, version_key = KEYS[1], KEYS[2]
local expected_version, new_version = ARGV[1], ARGV[2]
local value, ex, px, nx, xx, keepttl = ARGV[3], ARGV[4], ARGV[5], ARGV[6], ARGV[7], ARGV[8]

if expected_version == '' then
  expected_version = false
end

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

if expected_version ~= actual_version then
  redis.call('set', version_key, new_version)
  redis.call('del', redis_key)
  return nil
end

local set_args = {redis_key, value}

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

if #set_args == 2 then
  table.insert(set_args, version_key)
  table.insert(set_args, new_version)

  -- MSET always returns 'OK'.
  return redis.call('mset', unpack(set_args))
end

local succeeded = redis.call('set', unpack(set_args))

if succeeded then
  redis.call('set', version_key, new_version)
end

return succeeded
