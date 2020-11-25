<?php

namespace App\Http\Controllers\API\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Widget;
use App\Models\WidgetOrder;
use App\Models\WidgetPosition;

class HomeController extends Controller
{
    public function widgetRelation()
    {
        // $timelineData = TimelineStory::whereDate('created_at', $date)->orderBy('created_at', 'desc')->get();
        // return $timelineData;
    }

}
