<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CommonController;
use App\Http\Requests\Backend\ArticleRequest;
use App\Http\Requests\Backend\UpdateArticleRequest;
use App\Models\Article;
use App\Models\ArticleTag;
use App\Models\ArticleLanguage;
use App\Models\Media;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    private $order_by;
    private $order_related_by;
    private $article_path;
    private $common;

    public function __construct()
    {
        $this->order_by = array('', 'atc_id', '', 'title', '', 'created_at', 'article_status');
		$this->order_related_by = array('atc_id','image','title','created_at','updated_at','article_status','options');
        $this->article_path = 'public/articles';
        $this->common = new CommonController();
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
        $mnTotal = DB::table('articles')
            ->join('users', 'articles.user_id', '=', 'users.id');
            // ->select('users.username', 'articles.id as atc_id', 'articles.title', 'articles.media_id', 'articles.created_at', 'articles.article_status', 'articles.active_status'); // as username
        $dts = $mnTotal;

        if ($searchText) {
            $dts = $mnTotal->where('title', 'like', $searchText . '%');
        }

        $recordsTotal = $dts->count();
        // $q = DB::getQueryLog()[0]['query'];
        // dd($q);
        // ------------- end total --------------- //

        // ------------- start datas --------------- //
        // DB::enableQueryLog();
        $mnData = DB::table('articles')
            ->join('users', 'users.id', '=', 'articles.user_id')
            ->select('users.username', 'articles.id as atc_id', 'articles.title', 'articles.media_id', 'articles.created_at', 'articles.article_status', 'articles.active_status'); // as username

        if (trim($searchText)) {
            $datas = $mnData->where('title', 'like', $searchText . '%');
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
            foreach ($datas as $article) {
                $check = '<label class="ctainer">';
                $check .= '<input type="checkbox" class="chk-box" name="chk[]" id="chk_' . $article->atc_id . '" onclick="tickCheckbox()" />';
                $check .= '<span class="checkmark"></span>';
                $check .= '</label>';

                $image = $this->common->getImage($article->media_id);

                $cover = ($image) ? '<img src="' . asset('storage/' . $image) . '" width="100">' : '<i class="fa fa-camera fa-5x"></i>';

                $createDate = $article->created_at;
                $status = ((int) $article->article_status == 1)? '<span class="label label-success">เผยแผ่</span>' : '<span class="label label-warning">ฉบับร่าง</span>';

                $options = '<div class="flex-option">';
                $options .= '<a href="' . URL('/') . '/admin/article/' . $article->atc_id . '/related" class="btn btn-info btn-sm tooltips" title="บทความที่เกี่ยวข้อง"><i class="fa fa-link"></i></a>';
                $options .= '<a href="' . URL('/') . '/admin/article/' . $article->atc_id . '/edit" class="btn btn-warning btn-sm tooltips" title="แก้ไขบทความ"><i class="fa fa-pencil"></i></a>';

                if ((int) $article->active_status == 1) {
                    $options .= '<button type="button" class="btn btn-danger btn-del btn-sm tooltips"  onclick="deleteItem(' . $article->atc_id . ', \'article\');" title="ลบบทความ"><i class="fa fa-trash"></i></button>';
                } else {
                    $options .= '<button type="button" class="btn btn-primary btn-del btn-sm tooltips"  onclick="restoreItem(' . $article->atc_id . ', \'article\');" title="เรียกคืนบทความ"><i class="fa fa-refresh"></i></button>';
                }

                // $options .= $q;
                $options .= '</div>';

                $ret_data[] = array($check
                    , $article->atc_id
                    , $cover
                    , $article->title
                    , $article->username
                    , $createDate
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

    public function saveCreate(ArticleRequest $request)
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

        $tags = $request->tags;

        $data = new Article;
        $data->title = $request->title_th;
        $data->slug = $request->slug;
        $data->description = $request->description_th;
        $data->team_id = (int) $request->team_id;
        $data->tournament_id = (int) $request->tournament_id;
        $data->channel_id = (int) $request->channel_id;
        $data->detail = $request->detail_th;
        $data->seo_title = $request->seo_title_th;
        $data->seo_description = $request->seo_description_th;
        $data->user_id = (int) $user_id;
        $data->created_at = $dateTime;
        $data->article_status = $request->publishing_option; // 1:เผยแพร่, 0:ไม่เผยแพร่, 2:ตั้งเวลา
        // 3:ตั้งเป็นบทความเด่น ต้องเพิ่มเข้าไปอีก ตาราง

        $saved = $data->save();
        $article_id = $data->id;

        if ($article_id) {
            $total = 1;
            $message = 'Save success';

            if (trim($tags)) {
                // insert tag
                $arr_tag = explode(',', $tags);

                if (count($arr_tag) > 0) {
                    foreach($arr_tag as $val){
                        $tag_id = $this->findTag(trim($val));

                        if ($tag_id != 0) {
                            $find_artc_tag = $this->findArticleTag($article_id, $tag_id);

                            if ($find_artc_tag == 0) {
                                $artcTag = new ArticleTag;
                                $artcTag->article_id = $article_id;
                                $artcTag->tag_id = $tag_id;
                                $atcTagSaved = $artcTag->save();
                            }
                        }
                    }
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

                        $artLang = new ArticleLanguage;
                        $artLang->article_id = $article_id;
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

            if ($request->hasFile('media_file')) {
                $media_data = $this->common->uploadImage($request->img_name, $request->alt, $request->witdh, $request->height, $request->img_ext, $this->article_path);
                $media_id = $media_data['id'];
                $article_saved = $this->updateArticleCoverRef($media_id, $article_id);
                if ($article_saved) {
                    $path = $this->common->storeImage($request->media_file, $this->article_path, $media_data['file_name']);
                }
            }
        } else {
            $message = 'Save error!';
        }

        $model = array('total' => $total, 'message' => $message);

        return response()->json($model);
    }

    public function saveUpdate(UpdateArticleRequest $request)
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

        $tags = $request->tags;
        $articleId = $request->article_id;

        $data = Article::find($articleId);
        $data->title = $request->title_th;
        $data->slug = $request->slug;
        $data->description = $request->description_th;
        $data->team_id = (int) $request->team_id;
        $data->tournament_id = (int) $request->tournament_id;
        $data->channel_id = (int) $request->channel_id;
        $data->detail = $request->detail_th;
        $data->seo_title = $request->seo_title_th;
        $data->seo_description = $request->seo_description_th;
        $data->user_id = (int) $user_id;
        $data->updated_at = $dateTime;
        $data->article_status = $request->publishing_option;
        // $data->active_status = $request->active_status;
        $saved = $data->save();

        $media = Media::find($request->media_id);
        if ($media) {
            $old_name = $media->media_name;
            // $media->media_name = trim($request->img_name);
            $media->alt = $request->alt;
            $media_saved = $media->save();
        }
        
		if (trim($tags)) {
			// insert tag
			$arr_tag = explode(',', $tags);
            $arr_tag_id = array();

			if (count($arr_tag) > 0) {
				foreach($arr_tag as $val){
					$tag_id = $this->findTag(trim($val));

					if ($tag_id != 0) {
						$arr_tag_id[] = $tag_id;
						$find_artc_tag = $this->findArticleTag($articleId, $tag_id);

						if ($find_artc_tag == 0) {
                            $artcTag = new ArticleTag;
                            $artcTag->article_id = $articleId;
                            $artcTag->tag_id = $tag_id;
                            $atcTagSaved = $artcTag->save();
						}
					}
                }

				// remove if not found in form
                $rsAtcTag = ArticleTag::select('tag_id')->where('article_id', $articleId);

				if ($rsAtcTag->count() != 0) {
                    $result_artc_tags = $rsAtcTag->get();
					foreach($result_artc_tags as $val){
						if (! in_array($val->tag_id, $arr_tag_id)) {
                            $del_db = DB::table('article_tags')->where('article_id', $articleId)->where('tag_id', $val->tag_id)->delete();
						}
					}
				}
			}
		}

        if ($saved) {
            $total = 1;
            $message = 'Save success';

            if ($request->hasFile('media_file')) {
                $media_data = $this->common->uploadImage($request->img_name, $request->alt, $request->witdh, $request->height, $request->img_ext, $this->article_path);
                $media_id = $media_data['id'];
                $article_saved = $this->updateArticleCoverRef($media_id, $request->article_id);
                if ($article_saved) {
                    $path = $this->common->storeImage($request->media_file, $this->article_path, $media_data['file_name']);
                    // $del = $this->common->deleteImageFromId($request->media_id);
                }
            } else {
                if ($old_name != trim($request->img_name)) {
                    // update media_name
                }
            }
        } else {
            $message = 'Save error!';
        }

        $model = array('total' => $total, 'message' => $message);

        return response()->json($model);
    }

    public function savePublish(Request $request) {
        $total = 0;
        $message = '';

        if ((int) $request->input('article_id')) {
            $datas = Article::find((int) $request->input('article_id'));
            $datas->article_status = (int) $request->input('publishing_option');
            $saved = $datas->save();

            $total = 1;
            $message = 'Success';
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

    public function info($id)
    {
        //
    }

    public function deleteArticle(Request $request)
    {
        $total = 0;
        $message = '';

        if ((int) $request->input('id')) {
            $datas = Article::find((int) $request->input('id'));
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
            $datas = Article::whereIn('id', $ids)->update(['active_status' => '2']);

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
            $datas = Article::whereIn('id', $ids)->update(['active_status' => '1']);

            if ($datas) {
                $total = 1;
                $message = 'Success';
            }
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

    public function restoreArticle(Request $request)
    {
        $total = 0;
        $message = '';

        if ((int) $request->input('id')) {
            $datas = Article::find((int) $request->input('id'));
            $datas->active_status = 1;
            $saved = $datas->save();

            $total = 1;
            $message = 'Success';
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

    public function updateArticleCoverRef($media_id = 0, $article_id = 0)
    {
        $article_saved = null;

        if ($media_id && $media_id != 0) {
            $article = Article::find($article_id);
            $article->media_id = $media_id;
            $article_saved = $article->save();
        }

        return $article_saved;
    }

    
    function relatedList(Request $request) {
        $ret_data = array();
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $order = $request->input('order');
        $searchText = $request->input('search');
        $searchText = trim($searchText);
        $article_id = $request->input('article_id');

        // ------------- start total --------------- //
        // DB::enableQueryLog();
        $mnTotal = DB::table('articles')
            ->join('users', 'articles.user_id', '=', 'users.id');
            // ->select('users.username', 'articles.id as atc_id', 'articles.title', 'articles.media_id', 'articles.created_at', 'articles.article_status', 'articles.active_status'); // as username
        $dts = $mnTotal;

        if ($searchText) {
            $dts = $mnTotal->where('title', 'like', $searchText . '%');
        }

        $dts = $mnTotal->where('articles.id', '<>', $article_id);

        $recordsTotal = $dts->count();
        // $q = DB::getQueryLog()[0]['query'];
        // dd($q);
        // ------------- end total --------------- //

        // ------------- start datas --------------- //
        // DB::enableQueryLog();
        $mnData = DB::table('articles')
            ->join('users', 'users.id', '=', 'articles.user_id')
            ->select('users.username', 'articles.id as atc_id', 'articles.title', 'articles.media_id', 'articles.created_at', 'articles.article_status', 'articles.active_status'); // as username

        if (trim($searchText)) {
            $datas = $mnData->where('title', 'like', $searchText . '%');
        }

        $datas = $mnData->where('articles.id', '<>', $article_id);

        if (array_key_exists('column', $order[0]) && array_key_exists('dir', $order[0])) {
            $datas = $mnData->orderBy($this->order_related_by[$order[0]['column']], $order[0]['dir']);
        }

        $datas = $mnData->skip((int) $start)->take($length)->get();
        $total = count($datas);

        // $q = DB::getQueryLog()[0]['query'];
        // dd($q);
        // dd(DB::getQueryLog()[0]['time']);
        // ------------- end datas --------------- //

        if ($total > 0) {
            foreach ($datas as $article) {
                $image = $this->common->getImage($article->media_id);

                $cover = ($image) ? '<img src="' . asset('storage/' . $image) . '" width="100">' : '<i class="fa fa-camera fa-5x"></i>';

                $createDate = $article->created_at;
                $status = ((int) $article->article_status == 1)? '<span class="label label-success">เผยแผ่</span>' : '<span class="label label-warning">ฉบับร่าง</span>';

                $options = '<div class="flex-option">';
                // $options .= 	'<input type="checkbox" class="chkb" id="article_id_'. $article->atc_id .'" value="T"  onclick="pushInTemp('. $article->atc_id .')" />';
                // $options .= 	'<label class="cb" for="article_id_'. $article->atc_id .'"></label>';

                $options .=     '<label class="ctainer">';
                $options .=         '<input type="checkbox" class="chk-box" id="article_id_'. $article->atc_id .'" value="T"  onclick="pushInTemp('. $article->atc_id .')" />';
                $options .=         '<span class="checkmark"></span>';
                $options .=     '</label>';

                $options .= '</div>';

                // 'atc_id','image','title','created_at','updated_at','article_status','options'

                $ret_data[] = array($article->atc_id
                    , $cover
                    , $article->title
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

    public function related(Request $request)
    {
        $total = 0;
        $message = '';

		$arrIds = $request->datas;

		if (count($arrIds) > 0) {
			$related = implode(',', $arrIds);

            $data = Article::find($request->article_id);
            $data->related = $related;
            $saved = $data->save();
            
            $total = 1;
            $message = 'Success.';
        }

        $model = array('total' => $total, 'message' => $message);

        return response()->json($model);
    }

    public function findTag($tagString)
    {
        $findTheTag = Tag::where('tag_name', trim($tagString));

		if ($findTheTag->count() !=0 ) {
            $tData = $findTheTag->get();
			$tag_id = $tData[0]->id;
			return $tag_id;
		} else {
            $tagId = 0;

            $tag = new Tag;
            $tag->tag_name = trim($tagString);
            $saved = $tag->save();

            if ($saved) {
                $tagId = $tag->id;
            }

			return $tagId;
		}
    }

    public function findArticleTag($article_id, $tag_id)
    {
        $artcTag = ArticleTag::where('article_id', $article_id)->where('tag_id', $tag_id);

        $foundArtcTag = $artcTag->count();

		return $foundArtcTag;
    }
}
