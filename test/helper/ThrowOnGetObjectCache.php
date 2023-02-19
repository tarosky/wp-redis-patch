<?php

class ThrowOnGetObjectCache extends WP_Object_Cache {
    public $error_message = 'default error message';
    public $error_ifs = [true, true, true, true];

    public $call_count = 0;
    public $trigger_error_count = 0;

    protected function exception_handler($exception) {
        $this->trigger_error_count++;
    }

    protected function call_redis_method($method, $args) {
        if ($method === 'get') {
            if ($this->error_ifs[$this->call_count++]) {
                throw new RedisTestException($this->error_message);
            }
        }

        return parent::call_redis_method($method, $args);
    }
}
