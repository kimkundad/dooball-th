<?php

namespace App\Http\Controllers\API\Admin;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;

class MenuController extends Controller
{
    private $order_by;
    public function __construct()
    {
        $this->order_by = array('id', 'parent_id', 'menu_name');
    }

    public function list(Request $request)
    {
        $ret_data = array();
        $draw = (int)$request->input('draw');
        $start = (int)$request->input('start');
        $length = (int)$request->input('length');
        $order = $request->input('order');

        // DB::enableQueryLog();
        // $users = DB::table('menus');
        // $ddt = $users->skip(4)->take(5);
        // $ddt = $users->get();
        // $q = DB::getQueryLog()[0]['query'];
        // dd($ddt);

        $mnTotal = DB::table('menus');
        $recordsTotal = $mnTotal->count();

        // DB::enableQueryLog();
        $mnData = DB::table('menus');
        $datas = $mnData;

        if (array_key_exists('column', $order[0]) && array_key_exists('dir', $order[0])) {
            $datas = $mnData->orderBy($this->order_by[$order[0]['column']], $order[0]['dir']);
        }

        $datas = $mnData->skip((int)$start)->take($length)->get();
        $total = count($datas);

        // $q = DB::getQueryLog()[0]['query'];
        // dd($q);
        // dd(DB::getQueryLog()[0]['time']);

        if ($total > 0) {
            foreach($datas as $menu) {
				$options = '<div class="flex-option">';
				// $options .= '<a href="'. URL('/') .'/admin/settings/menu/'. $menu->id .'" class="btn btn-info btn-sm tooltips" title="ดูรายละเอียดบทความ"><i class="fa fa-eye"></i></a>';
				$options .= '<a href="'. URL('/') .'/admin/settings/menu/'. $menu->id .'/edit" class="btn btn-warning btn-sm tooltips" title="แก้ไขบทความ"><i class="fa fa-pencil"></i></a>';

				// if ($filter=='0') {
				// 	$options .= '<button type="button" class="btn btn-primary btn-del btn-sm tooltips"  onclick="restoreMenu('. $menu->id .');" data-toggle="modal" data-target="#delete_modal" title="ลบรายการ"><i class="fa fa-refresh"></i></button>';
				// } else {
					$options .= '<button type="button" class="btn btn-danger btn-del btn-sm tooltips"  onclick="deleteMenu('. $menu->id .');" title="ลบรายการ"><i class="fa fa-trash"></i></button>';
				// }

                // $options .= $q;
                $options .= '</div>';

                $ret_data[] = array($menu->id
                                    , ((int) $menu->parent_id == 0) ? '-' : $this->parentMenuName((int) $menu->parent_id)
                                    , trim($menu->menu_name)
                                    , trim($menu->menu_url)
                                    , $menu->menu_seq
                                    , $menu->menu_status
                                    , $options);
            }
        }

        $datas = ["draw"	=> ($draw) ? $draw : 0,
        "recordsTotal" => (int)$recordsTotal,
        "recordsFiltered" => (int)$recordsTotal,
        "data" => $ret_data];

        echo json_encode($datas);
    }

    public function parentMenuName($parentId)
    {
        $name = '';
        $menuData = Menu::where('id', $parentId)->first();

        if ($menuData) {
            $name = $menuData->menu_name;
        }

        return $name;
    }

    public function saveCreate(Request $request)
    {
        $total = 0;
        $message = '';

        // $validator = Validator::make($request->all(), [
        //     'title' => 'required|unique:posts|max:255',
        //     'body' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return redirect('post/create')
        //                 ->withErrors($validator)
        //                 ->withInput();
        // }

        $parentId = (int) $request->parent_id;
        $menuUrl = ($parentId == 0) ? '' : trim($request->menu_url);

        $datas = new Menu;
        $datas->parent_id = $parentId;
        $datas->menu_name = trim($request->menu_name);
        $datas->menu_url = $menuUrl;
        $datas->menu_icon = trim($request->menu_icon);
        $datas->menu_seq = (int)$request->menu_seq;
        $datas->menu_status = (int)$request->menu_status;
        $saved = $datas->save();

        if ($saved) {
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

        // $validator = Validator::make($request->all(), [
        //     'title' => 'required|unique:posts|max:255',
        //     'body' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return redirect('post/create')
        //                 ->withErrors($validator)
        //                 ->withInput();
        // }

        $parentId = (int) $request->parent_id;
        $menuUrl = ($parentId == 0) ? '' : trim($request->menu_url);

        $datas = Menu::find($request->menu_id);
        $datas->parent_id = $parentId;
        $datas->menu_name = trim($request->menu_name);
        $datas->menu_url = $menuUrl;
        $datas->menu_icon = trim($request->menu_icon);
        $datas->menu_seq = (int)$request->menu_seq;
        $datas->menu_status = (int)$request->menu_status;
        $saved = $datas->save();

        if ($saved) {
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

    public function deleteMenu(Request $request)
    {
        $total = 0;
        $message = '';

        if ((int)$request->input('id')) {
            DB::table('menus')->where('id', (int)$request->input('id'))->delete();
            $total = 1;
            $message = 'Success';
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }
}
