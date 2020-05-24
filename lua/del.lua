local redis_key, version_key = KEYS[1], KEYS[2]
local new_version = ARGV[1]

redis.call('set', version_key, new_version)
return redis.call('del', redis_key)
