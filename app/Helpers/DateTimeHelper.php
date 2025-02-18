<?php

namespace App\Helpers;

final class DateTimeHelper
{
    /** @var int Одна секунда */
    public const SECOND = 1;
    /** @var int Количество секунд в минуте */
    public const MINUTE = 60;
    /** @var int Количество секунд в 3 минутах */
    public const THREE_MINUTES = 180;
    /** @var int Количество секунд в 10 минутах */
    public const TEN_MINUTES = 600;
    /** @var int Количество секунд в 15 минутах */
    public const FIFTEEN_MINUTES = 900;
    /** @var int Количество секунд в 30 минутах */
    public const HALF_HOUR = 1800;
    /** @var int Количество секунд в часе */
    public const HOUR = 3600;
    /** @var int Количество секунд в 2 часах */
    public const TWO_HOURS = 7200;
    /** @var int Количество секунд в 6 часах */
    public const SIX_HOURS = 14400;
    /** @var int Количество секунд в сутках */
    public const DAY = 86400;
    /** @var int Сутки - 1 секунда */
    public const DAY_WITHOUT_SECOND = 86399;
    /** @var int Количество секунд в неделе */
    public const WEEK = 604800;
}
