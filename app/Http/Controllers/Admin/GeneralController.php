<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\CheckSystemController;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\General;
use App\Models\Media;
use \stdClass;

class GeneralController extends Controller
{
    private $conn;
    private $menus;
    private $general_path;

    public function __construct()
    {
        $this->conn = CheckSystemController::checkTableExist('generals');

        if ($this->conn['table']) {
            $this->menus = Menu::allMenus();
            $this->general_path = 'public/generals';
        }
    }

    public function index()
    {
        if ($this->conn['table']) {
            $form = new stdClass();
            $form->id = 0;
            $form->website_name = '';
            $form->website_description = '';
            $form->website_robot = 0;
            $form->website_gg_ua = '';

            $form->media_id = 0;
            $form->media_name = '';
            $form->path = '';
            $form->alt = '';
            $form->witdh = 0;
            $form->height = 0;
            $form->showImage = '';
    
            $gen = General::find(1);
            if ($gen) {
                $form = $gen;
                $form->media_name = '';
                $form->path = '';
                $form->alt = '';
                $form->witdh = 0;
                $form->height = 0;
                $form->showImage = '';
    
                $media = Media::find($gen->media_id);
                if ($media) {
                    $form->media_id = $media->id;
                    $form->media_name = $media->media_name;
                    $form->path = $media->path;
                    $form->alt = $media->alt;
                    $form->witdh = $media->witdh;
                    $form->height = $media->height;
                    $form->showImage = asset('storage/generals/' . $media->media_name);
                }
            }

            return view('backend/general/index', ['menus' => $this->menus, 'form' => $form]);
        } else {
            $message = ($this->conn['table'])? 'Found table: "generals"' : 'Not found table: "generals"';
            abort(403, $message);
        }
    }

    public function social()
    {
        if ($this->conn['table']) {
            $form = new stdClass();
            $form->id = 0;
            $form->social_facebook = '';
            $form->social_twitter = '';
            $form->social_linkedin = '';
            $form->social_youtube = '';
            $form->social_instagram = '';
            $form->social_pinterest = '';

            $gen = General::find(1);
            if ($gen) {
                $form = $gen;
            }

            return view('backend/social/index', ['menus' => $this->menus, 'form' => $form]);
        } else {
            $message = ($this->conn['table'])? 'Found table: "generals"' : 'Not found table: "generals"';
            abort(403, $message);
        }
    }
}
