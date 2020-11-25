<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Facades\Auth;

class Menu extends Model
{
    protected $table = 'menus';

    protected $fillable = ['menu_name', 'menu_url', 'menu_icon'];
    public $timestamps = false;

    public static function allMenus()
    {
        $menus = array();

        $role = 'Admin'; // Auth::user()->role;

        if ($role == 'Admin') {
            $datas = Menu::where('parent_id', 0)
                ->where('menu_status', 1);
                // ->whereNotNull('menu_name')
                // ->where('menu_name', '<>', '');
            if ($datas->count()) {
                $parents = $datas->orderBy('menu_seq', 'asc')->get();
                $url = $_SERVER['REQUEST_URI'];

                foreach($parents as $parent) {
                    $groups = self::urlGroup($parent->id);
                    $find = self::findUrlCurrent($groups, $url);
                    $this_active = ($find) ? 'active' : '';
                    $childs = self::getChilds($parent->id);
                    $menus[] = array('id' => $parent->id
                                    , 'menu_name' => $parent->menu_name
                                    , 'menu_url' => $parent->menu_url
                                    , 'menu_icon' => $parent->menu_icon
                                    , 'this_active' => $this_active
                                    , 'childs' => $childs);
                }
            }
        }

        // dd($menus);
        return $menus;
    }

    public static function findUrlCurrent($groups = array(), $url = '') {
        $found = false;
        if (count($groups) > 0) {
            foreach($groups as $val) {
                if (stripos($url, $val) !== false) {
                    $found = true;
                }
            }
        }
        return $found;
    }

    public static function allParents()
    {
        $datas = self::where('parent_id', 0)->where('menu_status', 1)->get();

        return $datas;
    }

    public static function urlGroup($parent_id = 0)
    {
        $url_group = array();

        $datas = Menu::where('parent_id', $parent_id)->where('menu_status', 1);

		if ($datas->count()) {
            $menus = $datas->orderBy('menu_seq', 'asc')->get();

			foreach($menus as $val){
				$url_group[] = $val->menu_url;
				$sub = self::getChilds($val->id);
				if (count($sub)!=0) {
					$sub_group = self::urlGroup($val->id);
					$url_group = array_merge($url_group, $sub_group);
				}
			}
        }

		return $url_group;
    }

    public static function getChilds($parent_id = 0)
    {
        $childs = array();

        // id,menu_name,menu_url
        $datas = Menu::where('parent_id', $parent_id)
                        ->where('menu_status', 1)
                        ->whereNotNull('menu_name')
                        ->where('menu_name', '<>', '');

		if ($datas->count()) {
            $menus = $datas->orderBy('menu_seq', 'asc')->get();

			foreach($menus as $val){
                $url = $_SERVER['REQUEST_URI'];
                $second_active = '';
                if (stripos($url, $val->menu_url) !== false) {
                    $second_active = 'active';
                }

                $three_childs = self::getChilds($val->id);

                $childs[] = array('menu_name' => $val->menu_name
                                ,'menu_icon' => $val->menu_icon
                                , 'menu_url' => $val->menu_url
                                , 'second_active' => $second_active
                                , 'three_childs' => $three_childs);

			}
        }

		return $childs;
    }

}
