<?php

namespace TSProj\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BaseController extends Controller
{
    public function timeConsumingCalculation($Time_Min){
        
        $TimeConsumingDay = 0;
        $TimeConsumingHour = 0;
        $TimeConsumingMin = 0;
        
        if ($Time_Min < 60)
        {
            $TimeConsumingMin = $Time_Min;
        }
        else if ($Time_Min >= 60 && $Time_Min < 1440)
        {
            $TimeConsumingHour = floor($Time_Min/60);
            $TimeConsumingMin = $Time_Min - ($TimeConsumingHour * 60);
        }
        else if ($Time_Min >= 1440)
        {
            $TimeConsumingDay = floor($Time_Min/1440);
            $TimeConsumingHour = floor(($Time_Min - ($TimeConsumingDay * 1440))/60);
            $TimeConsumingMin = $Time_Min - ($TimeConsumingDay *1440) - ($TimeConsumingHour * 60);
        }
        else
        {
            $TimeConsumingDay = 0;
            $TimeConsumingHour = 0;
            $TimeConsumingMin = 0;
        }
        return array($TimeConsumingDay, $TimeConsumingHour, $TimeConsumingMin);;
    }
    
    public function percentFinishedCalculation($Time_Min, $esDay, $esHour, $esMin, $finished){
        
        $PercentFinished = 0;
        
        if ($finished == 1)
        {
            $PercentFinished = 100;
        }
        else
        {
            $PercentFinished = ROUND(($Time_Min/(($esDay *24*60) + ($esHour*60) + $esMin))*100);
        }    
        return $PercentFinished;
    }
}
