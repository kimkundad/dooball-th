<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Widget;
use App\Models\WidgetPosition;
use App\Models\WidgetOrder;
use Illuminate\Support\Facades\DB;
use \stdClass;

class WidgetController extends Controller
{

    public function widgetOrderList(Request $request)
    {
        $wPositionTotal = 0;
        $wPositionList = array();

        $position_dom_id = $request->input('position_dom_id');

        // ------------- start datas --------------- //
        $wpData = DB::table('widget_orders')
            ->leftJoin('widgets', 'widget_orders.widget_dom_id', '=', 'widgets.widget_dom_id')
            ->select('widget_orders.id as widget_order_id', 'widget_orders.widget_dom_id', 'widget_orders.active_status', 'widgets.widget_name')
            ->where('position_dom_id', $position_dom_id);

        // $dataList = $wpData->orderBy('articles.created_at', 'desc');
        $wPositionTotal = $wpData->count();

        if ($wPositionTotal > 0) {
            $wPositionList = $wpData->get();
        }

        // $wpData = new stdClass();

        $model = array('total' => $wPositionTotal, 'wPositionList' => $wPositionList);
        return response()->json($model);
    }

    public function widgetOrderInfo(Request $request)
    {
        $total = 0;
        $widget_order_id = (int) $request->input('widget_order_id');

        $data = new stdClass();
        $data->title = '';
        $data->detail = '';
        $data->show_title_name = 0;

        $wgOrderData = WidgetOrder::find($widget_order_id);
        if ($wgOrderData) {
            $total = 1;
            $data = $wgOrderData;
        }

        $model = array('total' => $total, 'data' => $data);
        return response()->json($model);
    }

    public function saveWidgetCreate(Request $request)
    {
        $total = 0;
        $message = '';
        $widget_order_id = 0;

        $widget_dom_id = $request->input('widget_dom_id');
        $position_dom_id = $request->input('position_dom_id');

        if ($widget_dom_id && $position_dom_id) {
            $data = new WidgetOrder;
            $data->widget_dom_id = $widget_dom_id;
            $data->position_dom_id = $position_dom_id;
            $saved = $data->save();

            if ($saved) {
                $total = 1;
                $message = 'Save success';
                $widget_order_id = $data->id;
            } else {
                $message = 'Save error!';
            }
        }

        $model = array('total' => $total, 'message' => $message, 'widget_order_id' => $widget_order_id);
        return response()->json($model);
    }

    public function saveWidgetUpdate(Request $request)
    {
        $total = 0;
        $message = '';

        if ( (int) $request->input('widget_order_id') ) {
            $data = WidgetOrder::find((int) $request->input('widget_order_id'));
            $data->title = $request->input('title');
            $data->show_title_name = $request->input('show_title_name');
            $data->detail = $request->input('detail');
            $saved = $data->save();
    
            if ($saved) {
                $total = 1;
                $message = 'Save success';
            } else {
                $message = 'Save error!';
            }
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

    public function showHideWidgetOrder(Request $request)
    {
        $total = 0;
        $message = '';
        $active = 0;

        $widget_order_id = (int) $request->input('widget_order_id');

        if ( $widget_order_id ) {
            $data = WidgetOrder::find($widget_order_id);
            $active_status = $data->active_status;
            $active = ((int) $active_status == 1) ? 0 : 1 ;
            $data->active_status = $active;
            $saved = $data->save();
    
            if ($saved) {
                $total = 1;
                $message = 'Save success';
            } else {
                $message = 'Save error!';
            }
        }

        $model = array('total' => $total, 'message' => $message, 'active' => $active);
        return response()->json($model);
    }

    public function deleteWidgetOrder(Request $request)
    {
        $total = 0;
        $message = '';
        $widget_order_id = (int) $request->input('widget_order_id');

        if ($widget_order_id) {
            DB::table('widget_orders')->where('id', $widget_order_id)->delete();
            $total = 1;
            $message = 'Success';
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }
}
