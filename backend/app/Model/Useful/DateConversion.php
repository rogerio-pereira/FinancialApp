<?php

namespace App\Model\Useful;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DateConversion extends Model
{
    public static function newDateByPeriod($dateString, $period) : Carbon
    {
        $date = Carbon::createFromFormat('Y-m-d', $dateString);

        switch($period) {
            case 'Daily':
                $date = $date->addDay();
                break;
            case 'Weekly':
                $date = $date->addWeek();
                break;
            case 'Biweekly':
                $date = $date->addWeek(2);
                break;
            case 'Monthly':
                $date = $date->addMonth();
                break;
            case 'Quarterly':
                $date = $date->addMonth(3);
                break;
            case 'Semiannually':
                $date = $date->addMonth(6);
                break;
            case 'Annually':
                $date = $date->addYear();
                break;
        }

        return $date;
    }
}
