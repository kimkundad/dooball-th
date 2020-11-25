<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CommonController;
use App\Http\Requests\Backend\GeneralRequest;
use App\Models\Media;
use App\Models\General;
use Illuminate\Support\Facades\DB;

class GeneralController extends Controller
{
    private $general_path;
    private $common;

    public function __construct()
    {
        $this->general_path = 'public/generals';
        $this->common = new CommonController();
    }

    public function saveGeneral(GeneralRequest $request)
    {
        $total = 1;
        $message = 'Save success.';

        $saved = null;
        $media_id = 0;
        $g_data = General::find(1);

        if ($g_data) {
            $media_id = $g_data->media_id;

            $g_data->website_name = $request->website_name;
            $g_data->website_description = $request->website_description;
            $g_data->website_robot = $request->publishing_web_option;
            $g_data->website_gg_ua = $request->website_gg_analytics;
            $saved = $g_data->save();
        } else {
            $data = new General;
            $data->website_name = $request->website_name;
            $data->website_description = $request->website_description;
            $data->website_robot = $request->publishing_web_option;
            $data->website_gg_ua = $request->website_gg_analytics;
            $saved = $data->save();
        }

        if ($request->hasFile('logo_file')) {
            $image_name = 'logo' . $request->img_ext;
            $path = $this->common->storeImage($request->logo_file, $this->general_path, $image_name);
    
            if ($path) {
                $media_data = $this->common->uploadWebsiteLogo($image_name, $request->alt, $request->witdh, $request->height, $request->img_ext, $this->general_path);
                $mid = $media_data['id'];
                $gen_saved = $this->updateWebsiteLogoRef($mid, 1);
            }
        } else {
            if ($media_id != 0) {
                $media = Media::find($media_id);
                if ($media) {
                    $old_name = $media->media_name;
                    // $media->media_name = trim($request->img_name);
                    $media->alt = $request->alt;
                    $media_saved = $media->save();
                }
            }
        }

        $model = array('total' => $total, 'message' => $message);

        return response()->json($model);
    }

    public function saveSocial(Request $request)
    {
        $total = 0;
        $message = '';

        $saved = null;
        $media_id = 0;
        $g_data = General::find(1);

        if ($g_data) {
            $g_data->social_facebook = trim($request->social_facebook);
            $g_data->social_twitter =  trim($request->social_twitter);
            $g_data->social_linkedin =  trim($request->social_linkedin);
            $g_data->social_youtube =  trim($request->social_youtube);
            $g_data->social_instagram =  trim($request->social_instagram);
            $g_data->social_pinterest =  trim($request->social_pinterest);
            $saved = $g_data->save();

            $total = 1;
            $message = 'Save success.';
        } else {
            $data = new General;
            $data->social_facebook =  trim($request->social_facebook);
            $data->social_twitter =  trim($request->social_twitter);
            $data->social_linkedin =  trim($request->social_linkedin);
            $data->social_youtube =  trim($request->social_youtube);
            $data->social_instagram =  trim($request->social_instagram);
            $data->social_pinterest =  trim($request->social_pinterest);
            $saved = $data->save();

            $total = 1;
            $message = 'Save success.';
        }

        $model = array('total' => $total, 'message' => $message);

        return response()->json($model);
    }

    public function updateWebsiteLogoRef($media_id = 0, $general_id = 0)
    {
        $general_saved = null;

        if ($media_id && $media_id != 0) {
            $general = General::find($general_id);
            $general->media_id = $media_id;
            $general_saved = $general->save();
        }

        return $general_saved;
    }
}
