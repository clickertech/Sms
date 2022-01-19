<?php

if (!function_exists('sms')) {
    /**
     * Access SmsManager through helper.
     * @return \Clickertech\Sms\Sms
     */
    function sms()
    {
        return app('clickertech-sms');
    }
}
