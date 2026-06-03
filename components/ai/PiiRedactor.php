<?php
namespace app\components\ai;

class PiiRedactor
{
    public static function redact(string $text): string
    {
        $text = preg_replace('/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}\b/', '[REDACTED_EMAIL]', $text);
        $text = preg_replace('/\b(?:\+?91[-\s]?)?[6-9]\d{9}\b/', '[REDACTED_PHONE]', $text);
        $text = preg_replace('/\b\d{4}\s?\d{4}\s?\d{4}\b/', '[REDACTED_AADHAAR]', $text);
        return $text;
    }

    public static function redactArray(array $data, array $sensitiveKeys = ['phone_number','email','parent_phone','aadhaar','national_Identification_number','razorpay_payment_id']): array
    {
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $data[$k] = self::redactArray($v, $sensitiveKeys);
            } elseif (is_string($v)) {
                if (in_array($k, $sensitiveKeys, true)) {
                    $data[$k] = '[REDACTED]';
                } else {
                    $data[$k] = self::redact($v);
                }
            }
        }
        return $data;
    }
}
