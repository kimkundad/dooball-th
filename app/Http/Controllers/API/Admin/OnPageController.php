<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OnPage;
use App\Models\Page;

class OnPageController extends Controller
{
    private $order_by;
    private $article_path;

    public function __construct()
    {
        $this->order_by = array('id', 'code_name', 'seo_title', 'seo_description', 'page_top', 'page_bottom', 'active_status', '');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ret_data = array();
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $order = $request->input('order');
        $searchText = $request->input('search');
        $searchText = trim($searchText);

        // ------------- start total --------------- //
        $mnTotal = OnPage::select(['code_name', 'seo_title', 'seo_description', 'page_top', 'page_bottom']);

        if ($searchText) {
            $mnTotal->where('on_pages.code_name', 'like', '%' . $searchText . '%');
            $mnTotal->orWhere('on_pages.seo_title', 'like', '%' . $searchText . '%');
        }

        $recordsTotal = $mnTotal->count();
        // ------------- end total --------------- //

        // ------------- start datas --------------- //
        $mnData = OnPage::select(['id', 'code_name', 'seo_title', 'seo_description', 'page_top', 'page_bottom', 'active_status']);

        if (trim($searchText)) {
            $mnData->where('on_pages.code_name', 'like', '%' . $searchText . '%');
            $mnData->orWhere('on_pages.seo_title', 'like', '%' . $searchText . '%');
        }

        if (array_key_exists('column', $order[0]) && array_key_exists('dir', $order[0])) {
            $mnData->orderBy($this->order_by[$order[0]['column']], $order[0]['dir']);
        }

        $datas = $mnData->skip((int) $start)->take($length)->get();
        $total = count($datas);
        // ------------- end datas --------------- //

        if ($total > 0) {
            foreach ($datas as $onpage) {
                $pageTop = '';
                $pageBottom = '';

                if ((int) $onpage->page_top != 0) {
                    $pageRow = Page::find((int) $onpage->page_top);
                    if ($pageRow) {
                        $pageTop = $pageRow->page_name;
                    }
                }

                if ((int) $onpage->page_bottom != 0) {
                    $pageRow = Page::find((int) $onpage->page_bottom);
                    if ($pageRow) {
                        $pageBottom = $pageRow->page_name;
                    }
                }

                $status = ((int) $onpage->active_status == 1) ? '<span class="label label-success">ใช้งาน</span>' : '<span class="label label-danger">ไม่ใช้งาน</span>';

                $options = '<div class="flex-option">';
                $options .= '<a href="' . URL('/') . '/admin/on-page/' . $onpage->id . '/edit" class="btn btn-warning btn-sm tooltips" title="แก้ไขบทความ"><i class="fa fa-pencil"></i></a>';

                // if ((int) $onpage->active_status == 1) {
                //     $options .= '<button type="button" class="btn btn-danger btn-del btn-sm tooltips"  onclick="deleteItem(' . $onpage->id . ', \'on-pape\');" title="ลบข้อมูล"><i class="fa fa-trash"></i></button>';
                // } else {
                //     $options .= '<button type="button" class="btn btn-primary btn-del btn-sm tooltips"  onclick="restoreItem(' . $onpage->id . ', \'on-pape\');" title="เรียกคืนข้อมูล"><i class="fa fa-refresh"></i></button>';
                // }

                // $options .= $q;
                $options .= '</div>';

                $ret_data[] = array($onpage->id
                    , $onpage->code_name
                    , $onpage->seo_title
                    , $onpage->seo_description
                    , $pageTop
                    , $pageBottom
                    , $status
                    , $options);
            }

            $datas = ["draw" => ($draw) ? $draw : 0,
                "recordsTotal" => (int) $recordsTotal,
                "recordsFiltered" => (int) $recordsTotal,
                "data" => $ret_data];

            return response()->json($datas);
        } else {
            $datas = ["draw" => ($draw) ? $draw : 0,
                "recordsTotal" => (int) $recordsTotal,
                "recordsFiltered" => (int) $recordsTotal,
                "data" => $ret_data];

            return response()->json($datas);
        }
    }

    public function saveCreate(Request $request)
    {
        $total = 0;
        $message = '';

        $data = new OnPage;
        $data->code_name = $request->code_name;
        $data->seo_title = $request->seo_title;
        $data->seo_description = $request->seo_description;
        $data->page_top = ($request->page_top) ? $request->page_top : 0;
        $data->page_bottom = ($request->page_bottom) ? $request->page_bottom : 0;

        $saved = $data->save();
        $onpage_id = $data->id;

        if ($onpage_id != 0) {
            $total = 1;
            $message = 'Save success.';
        }

        $model = array('total' => $total, 'message' => $message);

        return response()->json($model);
    }

    public function saveUpdate(Request $request)
    {
        $total = 0;
        $message = '';

        $data = OnPage::find($request->onpage_id);
        $data->code_name = $request->code_name;
        $data->seo_title = $request->seo_title;
        $data->seo_description = $request->seo_description;
        $data->page_top = ($request->page_top) ? $request->page_top : 0;
        $data->page_bottom = ($request->page_bottom) ? $request->page_bottom : 0;

        $saved = $data->save();

        if ($saved != 0) {
            $total = 1;
            $message = 'Save success.';
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
