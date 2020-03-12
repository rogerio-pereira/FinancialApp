<?php

namespace Tests\Unit\Model\Useful;

use App\Model\Useful\DateConversion;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class DateConversionTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideDates
     */
    public function testNewDateByPeriod($input, $period, $expected)
    {
        $date = Carbon::createFromFormat('Y-m-d', $input)->format('Y-m-d');
        $date2 = DateConversion::newDateByPeriod($date, $period);

        $this->assertEquals($expected, $date2->toDateString());
    }

    public function provideDates()
    {
        return [
            //Daily
            ['2020-03-15', 'Daily', '2020-03-16'],
            ['2020-03-31', 'Daily', '2020-04-01'],
            ['2020-04-30', 'Daily', '2020-05-01'],
            ['2020-02-29', 'Daily', '2020-03-01'],
            ['2019-02-28', 'Daily', '2019-03-01'],
            ['2020-12-31', 'Daily', '2021-01-01'],
            //Weekly
            ['2020-03-01', 'Weekly', '2020-03-08'],
            ['2020-03-27', 'Weekly', '2020-04-03'],
            ['2020-12-29', 'Weekly', '2021-01-05'],
            //Biweekly
            ['2020-03-06', 'Biweekly', '2020-03-20'],
            ['2020-03-20', 'Biweekly', '2020-04-03'],
            ['2020-02-15', 'Biweekly', '2020-02-29'],
            ['2019-02-15', 'Biweekly', '2019-03-01'],
            ['2020-12-25', 'Biweekly', '2021-01-08'],
            //Monthly
            ['2020-03-15', 'Monthly', '2020-04-15'],
            ['2020-03-31', 'Monthly', '2020-05-01'],
            ['2020-01-30', 'Monthly', '2020-03-01'],
            ['2020-01-29', 'Monthly', '2020-02-29'],
            ['2019-01-29', 'Monthly', '2019-03-01'],
            //Quarterly
            ['2019-11-29', 'Quarterly', '2020-02-29'],
            ['2018-11-29', 'Quarterly', '2019-03-01'],
            ['2018-11-29', 'Quarterly', '2019-03-01'],
            ['2019-11-30', 'Quarterly', '2020-03-01'],
            //Semiannually
            ['2020-08-31', 'Semiannually', '2021-03-03'],
            ['2018-08-31', 'Semiannually', '2019-03-03'],
            ['2019-08-31', 'Semiannually', '2020-03-02'],
            ['2019-08-29', 'Semiannually', '2020-02-29'],
            ['2018-08-30', 'Semiannually', '2019-03-02'],
            ['2020-05-31', 'Semiannually', '2020-12-01'],
            //Anually
            ['2020-02-29', 'Annually', '2021-03-01'],
            ['2020-02-28', 'Annually', '2021-02-28'],
        ];
    }
}
