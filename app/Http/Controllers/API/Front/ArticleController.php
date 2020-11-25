<?php

namespace App\Http\Controllers\API\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CommonController;
use Illuminate\Support\Facades\DB;
use App\Models\Article;
use App\Models\ArticleScore;
use App\Models\ArticleTag;
use App\Models\Tag;
use \stdClass;

class ArticleController extends Controller
{
    private $common;
    private $perpage;

    public function __construct()
    {
        $this->common = new CommonController();
        $this->perpage = 6;
    }

    public function articlePage()
    {
        $atcList = array();

        // ------------- start article pick --------------- //
        // DB::enableQueryLog();
        $mnData = DB::table('articles')
            ->leftJoin('media', 'articles.media_id', '=', 'media.id')
            ->select('articles.id as article_id', 'articles.slug', 'articles.title', 'articles.description', 'articles.count_view', 'articles.media_id', 'media.path', 'media.media_name', 'media.alt');

        $mnData->where('articles.article_status', 1);
        $mnData->where('articles.active_status', 1);
        $mnData->orderBy('articles.created_at', 'desc');

        // $mnData->paginate($this->perpage, ['*'], 'page', $currentPage);
        $atcList = $mnData->skip(0)->take(6)->get();
        // $q = DB::getQueryLog()[0]['query'];
        // dd($q);
        // dd(DB::getQueryLog()[0]['time']);
        // ------------- end article pick --------------- //

        if (count($atcList) > 0) {
            foreach($atcList as $key => $val) {
                if ((int) $val->media_id == 0) {
                    $atcList[$key]->showImage = asset('images/no-image.jpg');
                } else {
                    $atcList[$key]->showImage = asset('storage/articles/' . $val->media_name);
                }

                $scoreData = $this->findScore($val->article_id);
                $atcList[$key]->vote = $scoreData['total'];
                $atcList[$key]->score = $scoreData['score'];
            }
        }
    
        return $atcList;
    }

    public function articleList($currentPage = 1, $cateId = 1)
    {
        $atcList = array();

        // ------------- start article pick --------------- //
        // DB::enableQueryLog();
        $mnData = DB::table('articles')
            ->leftJoin('media', 'articles.media_id', '=', 'media.id')
            ->select('articles.id as article_id', 'articles.slug', 'articles.title', 'articles.count_view', 'articles.created_at', 'articles.media_id', 'media.path', 'media.media_name', 'media.alt');

        $mnData->where('articles.article_status', 1);
        $mnData->where('articles.active_status', 1);
        // ->orWhere('address', 'like', '%' . $searchText . '%');

        $mnData->orderBy('articles.created_at', 'desc');

        $atcList = $mnData->paginate($this->perpage, ['*'], 'page', $currentPage);
        // $atcList = $mnData->skip(0)->take(6)->get();

        // $q = DB::getQueryLog()[0]['query'];
        // dd($q);
        // dd(DB::getQueryLog()[0]['time']);
        // ------------- end article pick --------------- //

        if (count($atcList) > 0) {
            foreach($atcList as $key => $val) {
                if ((int) $val->media_id == 0) {
                    $atcList[$key]->showImage = asset('images/no-image.jpg');
                } else {
                    $atcList[$key]->showImage = asset('storage/articles/' . $val->media_name);
                }

                $atcList[$key]->created_at = $this->common->showDate($val->created_at, 1);

                $scoreData = $this->findScore($val->article_id);
                $atcList[$key]->vote = $scoreData['total'];
                $atcList[$key]->score = $scoreData['score'];
            }
        }

        return $atcList;
    }

    public function tagOnpage($slug = '')
    {
        $title = '';
        $description = '';
        $detail = '';

        $tagDatas = Tag::where('tag_name', $slug);

        if ($tagDatas->count() > 0) {
            $rows = $tagDatas->get();
            $row = $rows[0];

            $title = $row->title;
            $description = $row->description;
            $detail = $row->detail;
        }

        return array('title' => $title, 'description' => $description, 'detail' => $detail);
    }

    public function articleTag($currentPage = 1, $slug = '')
    {
        $atcList = array();

        // ------------- start article pick --------------- //
        // DB::enableQueryLog();
        $mnData = DB::table('articles')
            ->leftJoin('media', 'articles.media_id', '=', 'media.id')
            ->leftJoin('article_tags', 'article_tags.article_id', '=', 'articles.id')
            ->leftJoin('tags', 'article_tags.tag_id', '=', 'tags.id')
            ->select('articles.id as article_id', 'articles.slug', 'articles.title', 'articles.count_view', 'articles.created_at', 'articles.media_id', 'media.path', 'media.media_name', 'media.alt');

        $mnData->where('articles.article_status', 1);
        $mnData->where('articles.active_status', 1);
        $mnData->where('tags.tag_name', $slug);
        // ->orWhere('address', 'like', '%' . $searchText . '%');

        $mnData->orderBy('articles.created_at', 'desc');

        $atcList = $mnData->paginate($this->perpage, ['*'], 'page', $currentPage);
        // $atcList = $mnData->skip(0)->take(6)->get();

        // $q = DB::getQueryLog()[0]['query'];
        // dd($q);
        // dd(DB::getQueryLog()[0]['time']);
        // ------------- end article pick --------------- //

        if (count($atcList) > 0) {
            foreach($atcList as $key => $val) {
                if ((int) $val->media_id == 0) {
                    $atcList[$key]->showImage = asset('images/no-image.jpg');
                } else {
                    $atcList[$key]->showImage = asset('storage/articles/' . $val->media_name);
                }

                $atcList[$key]->created_at = $this->common->showDate($val->created_at, 1);

                $scoreData = $this->findScore($val->article_id);
                $atcList[$key]->vote = $scoreData['total'];
                $atcList[$key]->score = $scoreData['score'];
            }
        }

        return $atcList;
    }

    public function articleDetail($slug = '')
    {
        $artc = new stdClass();
        $artc->id = 0;
        $artc->code = '';
        $artc->category_id = 0;
        $artc->title = '';
        $artc->slug = '';
        $artc->description = '';
        $artc->team_id = 0;
        $artc->tournament_id = 0;
        $artc->channel_id = 0;
        $artc->detail = '';
        $artc->seo_title = '';
        $artc->seo_description = '';
        $artc->media_id = 0;
        $artc->count_view = 0;
        $artc->count_like = 0;
        $artc->vote = 0;
        $artc->score = 0;
        $artc->user_id = 0;
        $artc->related = '';
        $artc->createdFormat = '';
        $artc->created_at = '';
        $artc->updated_at = '';
        $artc->tags = '';

        $article = Article::where('slug', $slug);

        if ($article->count() > 0) {
            $atcList = $article->get();
            $artc = $atcList[0];
            $artc->createdFormat = $this->common->showDate($artc->created_at, 1);

            $scoreData = $this->findScore($artc->id);
            $artc->vote = $scoreData['total'];
            $artc->score = $scoreData['score'];

            if ((int) $artc->media_id == 0) {
                $artc->showImage = asset('images/no-image.jpg');
            } else {
                $image = $this->common->getImage($artc->media_id);
                $artc->showImage = ($image) ? asset('storage/' . $image) : asset('images/no-image.jpg');
            }

            $atcTag = ArticleTag::where('article_id', $artc->id);
            if ($atcTag->count() > 0) {
                // $tagList = array();
                $tags = '';

                foreach($atcTag->get() as $tag) {
                    $tagName = $this->tagName($tag->tag_id);
                    $tags .= '<a href="' . url('/tags/' . $tagName) . '" class="btn btn-secondary mr-1">' . $tagName . '</a>';
                    // $tagList[] = $tagName;
                }

                $artc->tags = $tags;
            }
        }

        return $artc;
    }
    
    public function articleLatest()
    {
        $datas = array();

        $atcDatas = Article::select(['id', 'title', 'slug', 'count_view', 'media_id', 'created_at'])->where('article_status', 1)->where('active_status', 1)->orderBy('created_at', 'desc');

        if ($atcDatas->count() > 0) {
            $datas = $atcDatas->skip(0)->take(5)->get();
            foreach($datas as $k => $val) {
                $datas[$k]->createdAt = $this->common->showDate($val->created_at, 1);

                $scoreData = $this->findScore($val->id);
                $datas[$k]->vote = $scoreData['total'];
                $datas[$k]->score = $scoreData['score'];

                if ((int)  $datas[$k]->media_id == 0) {
                    $datas[$k]->showImage = asset('images/no-image.jpg');
                } else {
                    $image = $this->common->getImage($val->media_id);
                    $datas[$k]->showImage = ($image) ? asset('storage/' . $image) : asset('images/no-image.jpg');
                }
            }
        }

        return $datas;
    }

    public function articlePopular()
    {
        $datas = array();

        $atcDatas = Article::select(['id', 'title', 'slug', 'count_view', 'media_id', 'created_at'])->where('article_status', 1)->where('active_status', 1)->orderBy('count_view', 'desc');

        if ($atcDatas->count() > 0) {
            $datas = $atcDatas->skip(0)->take(5)->get();
            foreach($datas as $k => $val) {
                $datas[$k]->createdAt = $this->common->showDate($val->created_at, 1);

                $scoreData = $this->findScore($val->id);
                $datas[$k]->vote = $scoreData['total'];
                $datas[$k]->score = $scoreData['score'];
                
                if ((int)  $datas[$k]->media_id == 0) {
                    $datas[$k]->showImage = asset('images/no-image.jpg');
                } else {
                    $image = $this->common->getImage($val->media_id);
                    $datas[$k]->showImage = ($image) ? asset('storage/' . $image) : asset('images/no-image.jpg');
                }
            }
        }

        return $datas;
    }

    public function articleRelated(Request $request)
    {
        $relatedString = $request->related;
        $articleList = array();

        if (trim($relatedString)) {
            $relatedList = explode(',', trim($relatedString));
            $articles = Article::whereIn('id', $relatedList)->where('active_status', 1)->where('article_status', 1);
            if ($articles->count() > 0) {
                $articleList = $articles->get();

                foreach($articleList as $k => $article) {
                    $articleList[$k]->createdFormat = $this->common->showDate($article->created_at, 1);
        
                    $scoreData = $this->findScore($article->id);
                    $articleList[$k]->vote = $scoreData['total'];
                    $articleList[$k]->score = $scoreData['score'];
        
                    if ((int)  $articleList[$k]->media_id == 0) {
                        $articleList[$k]->showImage = asset('images/no-image.jpg');
                    } else {
                        $image = $this->common->getImage($article->media_id);
                        $articleList[$k]->showImage = ($image) ? asset('storage/' . $image) : asset('images/no-image.jpg');
                    }
                }
            }
        }

        return response()->json($articleList);
    }

    public function findScore($article_id = 0)
    {
        $sumScore = 0;
        $rate = 0.00;

        $artcScore = ArticleScore::where('article_id', $article_id);
        $totalFound = $artcScore->count();

        if ($totalFound > 0) {
            $scores = $artcScore->get();
            foreach($scores as $row) {
                $score = (int) $row->score;
                $sumScore += $score;
            }

            $rate = (float) ($sumScore / $totalFound);
        }

        $rate = number_format($rate, 2, '.', ',');

        return array('total' => $totalFound, 'score' => $rate);
    }

    public function tagName($tagId)
    {
        $tagName = '';
        $tData = Tag::where('id', $tagId);

        if ($tData->count() > 0) {
            $tName = $tData->get();
            $tagName = $tName[0]->tag_name;
        }

        return $tagName;
    }
}
