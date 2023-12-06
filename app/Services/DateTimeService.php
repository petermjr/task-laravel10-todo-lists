<?php


namespace App\Services;

use Carbon\Carbon;

class DateTimeService
{
    /**
     * Given a string in the format Y-m-dTH:i and user time zone offset in minutes,
     * we convert the given datetime to UTC
     * @param $datetimeString
     * @param $timezoneOffsetMinutes
     * @return bool|Carbon
     * @throws \Exception
     */
    public function convertDateTimeFromUserTzToUTC($datetimeString, $timezoneOffsetMinutes): bool|Carbon
    {
        $timezoneName = timezone_name_from_abbr("", -$timezoneOffsetMinutes * 60, false);
        $deadline     = Carbon::createFromFormat('Y-m-d\TH:i', $datetimeString,
            new \DateTimeZone($timezoneName));

        // Convert the datetime to UTC
        $deadline->setTimezone(new \DateTimeZone('UTC'));
        return $deadline;
    }
}
