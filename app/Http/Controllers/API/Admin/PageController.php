<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\API\Admin\KeyController;
use App\Http\Requests\Backend\PageRequest;
use App\Http\Requests\Backend\PageUpdateRequest;
use App\Models\Page;
use App\Models\PageLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    private $order_by;
    private $page_path;
    private $common;
    private $key;

    public function __construct()
    {
        $this->order_by = array('page_id', 'page_name', 'created_at', '', 'show_on_menu');
        $this->page_path = 'public/pages';
        $this->common = new CommonController();
        $this->key = new KeyController();
    }

    function list(Request $request) {
        $ret_data = array();
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $order = $request->input('order');
        $searchText = $request->input('search');
        $searchText = trim($searchText);

        // ------------- start total --------------- //
        // DB::enableQueryLog();
        $mnTotal = DB::table('pages')
            ->join('users', 'users.id', '=', 'pages.user_id')
            ->select('users.username', 'pages.id as page_id', 'pages.page_name', 'pages.created_at', 'pages.show_on_menu', 'pages.active_status'); // as username
        $dts = $mnTotal;

        if ($searchText) {
            $dts = $mnTotal->where('page_name', 'like', '%' . $searchText . '%');
        }

        $recordsTotal = $dts->count();
        // $q = DB::getQueryLog()[0]['query'];
        // dd($q);
        // ------------- end total --------------- //

        // ------------- start datas --------------- //
        // DB::enableQueryLog();
        $mnData = DB::table('pages')
            ->join('users', 'users.id', '=', 'pages.user_id')
            ->select('users.username', 'pages.id as page_id', 'pages.page_name', 'pages.created_at', 'pages.show_on_menu', 'pages.active_status'); // as username

        if (trim($searchText)) {
            $datas = $mnData->where('page_name', 'like', '%' . $searchText . '%');
        }

        if (array_key_exists('column', $order[0]) && array_key_exists('dir', $order[0])) {
            $datas = $mnData->orderBy($this->order_by[$order[0]['column']], $order[0]['dir']);
        }

        $datas = $mnData->skip((int) $start)->take($length)->get();
        $total = count($datas);

        // $q = DB::getQueryLog()[0]['query'];
        // dd($q);
        // dd(DB::getQueryLog()[0]['time']);
        // ------------- end datas --------------- //

        if ($total > 0) {
            foreach ($datas as $page) {
                $check = '<label class="ctainer">';
                $check .= '<input type="checkbox" class="chk-box" name="chk[]" id="chk_' . $page->page_id . '" onclick="tickCheckbox()" />';
                $check .= '<span class="checkmark"></span>';
                $check .= '</label>';

                $createDate = $page->created_at;
                $status = ((int) $page->show_on_menu == 1)? '<span class="label label-success">แสดง</span>' : '<span class="label label-warning">ไม่แสดง</span>';

                $options = '<div class="flex-option">';
                $options .= '<a href="' . URL('/') . '/admin/page/' . $page->page_id . '/edit" class="btn btn-warning btn-sm tooltips" title="แก้ไขเพจ"><i class="fa fa-pencil"></i></a>';

                if ((int) $page->active_status == 1) {
                    $options .= '<button type="button" class="btn btn-danger btn-del btn-sm tooltips"  onclick="deleteItem(' . $page->page_id . ', \'page\');" title="ลบเพจ"><i class="fa fa-trash"></i></button>';
                } else {
                    $options .= '<button type="button" class="btn btn-primary btn-del btn-sm tooltips"  onclick="restoreItem(' . $page->page_id . ', \'page\');" title="เรียกคืนเพจ"><i class="fa fa-refresh"></i></button>';
                }

                // $options .= $q;
                $options .= '</div>';

                $ret_data[] = array($check
                    , $page->page_name
                    , $createDate
                    , $page->username
                    , $status
                    , $options);
            }

            $datas = ["draw" => ($draw) ? $draw : 0,
                "recordsTotal" => (int) $recordsTotal,
                "recordsFiltered" => (int) $recordsTotal,
                "data" => $ret_data];

            echo json_encode($datas);
        } else {
            $datas = ["draw" => ($draw) ? $draw : 0,
                "recordsTotal" => (int) $recordsTotal,
                "recordsFiltered" => (int) $recordsTotal,
                "data" => $ret_data];

            echo json_encode($datas);
        }

    }

    public function saveCreate(PageRequest $request)
    {
        $total = 0;
        $message = '';
        $dateTime = Date('Y-m-d H:i:s');

        $user_id = 0;
        if (isset($_SESSION["loginBackAdminStart"])) {
            $user_id = $_SESSION["login_back_admin_id"];
        } else if (isset($_SESSION["loginBackMemberStart"])) {
            $user_id = $_SESSION["login_back_member_id"];
        }

        $data = new Page;
        $data->page_name = $request->page_name;
        $data->slug = $request->slug;
        $data->page_condition = $request->page_condition;
        $data->league_name = $request->league_name;
        $data->team_name = $request->team_name;
        $data->title = $request->title_th;
        $data->description = $request->description_th;
        $data->detail = $request->detail_th;
        $data->seo_title = $request->seo_title_th;
        $data->seo_description = $request->seo_description_th;
        $data->user_id = (int) $user_id;
        $data->created_at = $dateTime;
        $data->show_on_menu = (int) $request->show_on_menu; // 1:show on menu, 0:not show

        $saved = $data->save();
        $page_id = $data->id;

        if ($page_id) {
            $total = 1;
            $message = 'Save success';

            if ($request->page_condition == 'T') {
                $keyNames = isset($request->key_names) ? $request->key_names : array();

                if (count($keyNames) > 0) {
                    $keyValues = isset($request->key_values) ? $request->key_values : array();
                    $this->key->addKey('page', $page_id, $keyNames, $keyValues);
                }
            }

            /*
            $titles = $request->title;
            if (count($titles) > 0) {
                $language = '';
                $title = '';
                $desc = '';
                $dtl = '';
                $seo_tt = '';
                $seo_kw = '';
                $seo_desc = '';

                foreach ($titles as $lang => $value) {
                    $title = $value;
                    if (trim($title)) {
                        $language = $lang;
                        $desc = $request->description[$lang];
                        $dtl = $request->detail[$lang];
                        $seo_tt = $request->seo_title[$lang];
                        $seo_desc = $request->seo_description[$lang];

                        $artLang = new PageLanguage;
                        $artLang->page_id = $page_id;
                        $artLang->language = $language;
                        $artLang->title = $title;
                        $artLang->description = $desc;
                        $artLang->detail = $dtl;
                        $artLang->seo_title = $seo_tt;
                        $artLang->seo_description = $seo_desc;
                        $artLang->user_id = (int) $user_id;
                        $artLang->created_at = $dateTime;
                        $artLangSaved = $artLang->save();
                    }
                }
            }*/
        } else {
            $message = 'Save error!';
        }

        $model = array('total' => $total, 'message' => $message);

        return response()->json($model);
    }

    public function saveUpdate(PageUpdateRequest $request)
    {
        $total = 0;
        $message = '';
        $dateTime = Date('Y-m-d H:i:s');
        
        $user_id = 0;
        if (isset($_SESSION["loginBackAdminStart"])) {
            $user_id = $_SESSION["login_back_admin_id"];
        } else if (isset($_SESSION["loginBackMemberStart"])) {
            $user_id = $_SESSION["login_back_member_id"];
        }

        $data = Page::find($request->page_id);
        $data->page_name = $request->page_name;
        $data->slug = $request->slug;
        $data->page_condition = $request->page_condition;
        $data->league_name = $request->league_name;
        $data->team_name = $request->team_name;
        $data->title = $request->title_th;
        $data->description = $request->description_th;
        $data->detail = $request->detail_th;
        $data->seo_title = $request->seo_title_th;
        $data->seo_description = $request->seo_description_th;
        $data->user_id = (int) $user_id;
        $data->updated_at = $dateTime;
        $data->show_on_menu = (int) $request->show_on_menu;
        // $data->active_status = $request->active_status;
        $saved = $data->save();

        if ($saved) {
            $total = 1;
            $message = 'Save success';
        } else {
            $message = 'Save error!';
        }

        if ($request->page_condition == 'T') {
            $keyIds = isset($request->key_ids) ? $request->key_ids : array();

            if (count($keyIds) > 0) {
                $keyNames = isset($request->key_names) ? $request->key_names : array();
                $keyValues = isset($request->key_values) ? $request->key_values : array();
                $keyUpdate = $this->key->editKey('page', $request->page_id, $keyIds, $keyNames, $keyValues);
                // dd($keyUpdate);
            } else {
                $this->key->checkClearForm('page', $request->page_id);
            }
        }

        $model = array('total' => $total, 'message' => $message);

        return response()->json($model);
    }

    public function info($id)
    {
        //
    }

    public function deletePage(Request $request)
    {
        $total = 0;
        $message = '';

        if ((int) $request->input('id')) {
            $datas = Page::find((int) $request->input('id'));
            $datas->active_status = 2;
            $saved = $datas->save();

            $total = 1;
            $message = 'Success';
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

    public function multipleDelete(Request $request)
    {
        $total = 0;
        $message = '';

        if ($request->input('ids')) {
            $strIds = $request->input('ids');
            $ids = explode(',', $strIds);
            $datas = Page::whereIn('id', $ids)->update(['active_status' => '2']);

            if ($datas) {
                $total = 1;
                $message = 'Success';
            }
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

    public function multipleRestore(Request $request)
    {
        $total = 0;
        $message = '';

        if ($request->input('ids')) {
            $strIds = $request->input('ids');
            $ids = explode(',', $strIds);
            $datas = Page::whereIn('id', $ids)->update(['active_status' => '1']);

            if ($datas) {
                $total = 1;
                $message = 'Success';
            }
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

    public function restorePage(Request $request)
    {
        $total = 0;
        $message = '';

        if ((int) $request->input('id')) {
            $datas = Page::find((int) $request->input('id'));
            $datas->active_status = 1;
            $saved = $datas->save();

            $total = 1;
            $message = 'Success';
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }
}
