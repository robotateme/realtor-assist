local key = KEYS[1]
local nowMs = tonumber(ARGV[1])
local windowMs = tonumber(ARGV[2])
local limit = tonumber(ARGV[3])
local cost = tonumber(ARGV[4])
local nonce = tostring(ARGV[5])
local windowStart = nowMs - windowMs

redis.call('ZREMRANGEBYSCORE', key, '-inf', windowStart)

local current = tonumber(redis.call('ZCARD', key))

if (current + cost) > limit then
    local oldest = redis.call('ZRANGE', key, 0, 0, 'WITHSCORES')
    local retryAfterMs = windowMs

    if oldest[2] ~= nil then
        retryAfterMs = tonumber(oldest[2]) + windowMs - nowMs

        if retryAfterMs < 0 then
            retryAfterMs = 0
        end
    end

    local remaining = limit - current

    if remaining < 0 then
        remaining = 0
    end

    local retryAfterSeconds = math.ceil(retryAfterMs / 1000)
    local resetAtUnix = math.ceil((nowMs + retryAfterMs) / 1000)

    return {0, remaining, retryAfterSeconds, resetAtUnix, limit, current}
end

for index = 1, cost do
    local member = string.format('%s-%s-%d', tostring(nowMs), nonce, index)
    redis.call('ZADD', key, nowMs, member)
end

redis.call('PEXPIRE', key, windowMs)

current = tonumber(redis.call('ZCARD', key))
local remaining = limit - current

if remaining < 0 then
    remaining = 0
end

return {1, remaining, 0, math.ceil((nowMs + windowMs) / 1000), limit, current}
