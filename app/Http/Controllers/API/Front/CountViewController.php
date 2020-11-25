<?php

namespace App\Http\Controllers\API\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CountViewController extends Controller
{

    public function index(Request $request)
    {
        $count = 0;
        $message = '';

        $data = Article::find($request->id);
        if ($data) {
            $count = ((int) $data->count_view + 1);
            $data->count_view = $count;
            $saved = $data->save();
        }

        $model = array('total' => $count);

        return response()->json($model);
    }

    public function rate(Request $request)
    {
        $count = 0;
        $message = '';
        $article_id = $request->article_id;
	    $secret_code = $request->secret_code;
	    $score = $request->score;
        $createdAt = Date('Y-m-d H:i:s');

		if ($article_id != 0 && $secret_code != '' && $score != 0) {
            $scCode = DB::table('site_config')->where('config_key', 'secret_code')->where('config_value', $secret_code);

			if ($scCode->count() > 0) {
                // $scCodeData = $scCode->get();

                $atcScore = new ArticleScore;
                $atcScore->article_id = $article_id;
                $atcScore->score = $score;
                $atcScore->created_at = $createdAt;
                $saved = $atcScore->save();
                $count++;
                $message = 'Rate successfull.';
			} else {
				$message = 'รหัสลับไม่ถูกต้อง';
			}
		} else {
			$message = 'ข้อมูลไม่ถูกต้อง';
        }

        $model = array('total' => $count, 'message' => $message);

        return response()->json($model);
    }

}
