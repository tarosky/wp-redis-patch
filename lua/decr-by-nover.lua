local redis_key = KEYS[1]
local offset = ARGV[1]

local value = redis.call('get', redis_key)

if not value then
  return nil
end

local num = tonumber(value)
if not num then
  return nil
end

local offset_num = tonumber(offset)
if not offset_num then
  return nil
end

local new_num = num - offset_num
if new_num < 0 then
  new_num = 0
end

redis.call('set', redis_key, tostring(new_num))
return new_num
