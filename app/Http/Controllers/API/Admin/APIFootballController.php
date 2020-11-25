<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class APIFootballController extends Controller
{
    private $order_by;
    private $rapidApiHost;

    public function __construct()
    {
        $this->order_by = array('id', '', '', '', '');
        $this->rapidApiHost = 'api-football-v1.p.rapidapi.com';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ret_data = array();
        $draw = (int)$request->input('draw');
        $start = (int)$request->input('start');
        $length = (int)$request->input('length');
        $order = $request->input('order');
        $apiPageId = $request->api_page_id;

        $mnTotal = DB::table('api_page_items')->where('api_page_id', $apiPageId);
        $recordsTotal = $mnTotal->count();

        $mnData = DB::table('api_page_items')->where('api_page_id', $apiPageId);

        if (array_key_exists('column', $order[0]) && array_key_exists('dir', $order[0])) {
            $mnData->orderBy($this->order_by[$order[0]['column']], $order[0]['dir']);
        }

        $datas = $mnData->skip((int)$start)->take($length)->get();
        $total = count($datas);

        if ($total > 0) {
            foreach($datas as $k => $api) {
				$options = '<div class="flex-option">';

                $options .= '<a href="'. URL('/') .'/admin/settings/api-football/edit-api-page/' . $api->api_page_id . '/'. $api->id .'" class="btn btn-warning btn-sm tooltips" title="แก้ไขบทความ"><i class="fa fa-pencil"></i></a>';
				$options .= '<button type="button" class="btn btn-danger btn-del btn-sm tooltips"  onclick="deleteItem('. $api->id .');" title="ลบรายการ"><i class="fa fa-trash"></i></button>';

                $options .= '</div>';

                $apiFullUrl = 'https://' . $this->rapidApiHost . '/v2/' . $api->api_url;
                $inputZone = '<input type="text" class="form-control params-data" id="params-mask-' . $api->id . '" value="' . $api->params_mask . '">';
                $buttonZone = '<button type="button" class="btn btn-primary" data-url="' . $apiFullUrl . '" api-id="' . $api->id . '">GET</button>';

                $apiCalling = '<div class="d-flex just-between">';
                $apiCalling .=  '<div>' . $apiFullUrl . (((int) $api->params_count > 0) ? $inputZone : '') . '</div>';
                $apiCalling .=  '<div>' . $buttonZone . '</div>';
                $apiCalling .= '</div>';

                $ret_data[] = array(($k + 1)
                                    , $api->api_url
                                    , $api->api_desc
                                    // , 'https://' . $this->rapidApiHost . '/v2/' . $api->api_url . (($api->params_mask)? $api->params_mask : '')
                                    , $apiCalling
                                    , $options);
            }
        }

        $datas = ["draw"	=> ($draw) ? $draw : 0,
        "recordsTotal" => (int)$recordsTotal,
        "recordsFiltered" => (int)$recordsTotal,
        "data" => $ret_data];

        return response()->json($datas);
        // echo json_encode($datas);
    }

    public function saveCreate(Request $request)
    {
        $total = 0;
        $message = '';

        $pageId = (int) $request->api_page_id;
        $apiUrl = $request->api_url;
        $apiDesc = $request->api_desc;
        $paramsCount = $request->params_count;
        $paramsTypes = $request->params_types;
        $paramsMasks = $request->params_masks;

        $paramsType = '';
        $paramsMask = '';

        if ((int) $paramsCount > 0) {
            $paramsType = implode('/', $paramsTypes);
            $paramsMask = implode('/', $paramsMasks);
        }

        $createApiPage = array('api_page_id' => $pageId,
                            'api_url' => $apiUrl,
                            'api_desc' => $apiDesc,
                            'params_count' => (int) $paramsCount,
                            'params_type' => $paramsType,
                            'params_mask' => $paramsMask);

        $apiItemId = DB::table('api_page_items')->insertGetId(
            $createApiPage
        );

        if ((int) $apiItemId > 0) {
            $total = 1;
            $message = 'Save success';
        } else {
            $message = 'Save error!';
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

    public function saveUpdate(Request $request)
    {
        $total = 0;
        $message = '';

        $pageId = (int) $request->api_page_id;
        $apiId = (int) $request->api_id;
        $apiUrl = $request->api_url;
        $apiDesc = $request->api_desc;
        $paramsCount = $request->params_count;
        $paramsTypes = $request->params_types;
        $paramsMasks = $request->params_masks;

        $paramsType = '';
        $paramsMask = '';

        if ((int) $paramsCount > 0) {
            $paramsType = implode('/', $paramsTypes);
            $paramsMask = implode('/', $paramsMasks);
        }

        $updateApiPage = array('api_page_id' => $pageId,
                            'api_url' => $apiUrl,
                            'api_desc' => $apiDesc,
                            'params_count' => (int) $paramsCount,
                            'params_type' => $paramsType,
                            'params_mask' => $paramsMask);

        $affected = DB::table('api_page_items')
                                ->where('id', $apiId)
                                ->update($updateApiPage);

        if ($affected) {
            $total = 1;
            $message = 'Save success';
        } else {
            $message = 'Save error!';
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

    public function info($id)
    {
        //
    }

    public function deleteItem(Request $request)
    {
        $total = 0;
        $message = '';

        if ((int)$request->input('id')) {
            DB::table('api_page_items')->where('id', (int)$request->input('id'))->delete();
            $total = 1;
            $message = 'Success';
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
