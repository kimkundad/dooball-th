<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DirList;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\CommonController;

class ArrangeFFPMain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dooball:arrange-ffp-main';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For arrange ffp every 5 minutes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    
    private $common;

    public function __construct()
    {
        parent::__construct();
        $this->common = new CommonController();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // --- start separate main datas --- //
        // $insertIds = array();

        /*
        $dirDatas = DB::table('ffp_list_split')->select('dir_name')->groupBy('dir_name');

        $dirExistList = array();

        if ($dirDatas->count() > 0) {
            foreach($dirDatas->get() as $data) {
                $dirExistList[] = $data->dir_name;
            }
        }

        $dirLeft = DirList::where('content', '<>', '[]')->whereNotIn('dir_name', $dirExistList)->orderBy('dir_name', 'asc');

        if ($dirLeft->count() > 0) {
            $dirDatas = $dirLeft->take(5)->get();

            foreach($dirDatas as $minDir) {
                $dirName = $minDir->dir_name;
                $content = $minDir->content;
                $createdAt = $minDir->created_at;

                $contentList = json_decode($content);

                if (count($contentList) > 0) {
                    foreach($contentList as $data) {
                        $topHead = $data->top_head;
                        $topHeadType = $this->common->getTopHeadType($topHead);
                        $leagueList = $data->datas;

                        $newLeagueList = array();

                        if (count($leagueList) > 0) {
                            foreach($leagueList as $league) {
                                $lName = $league->league_name;
                                $lId = $this->common->leagueIdFromName($lName);

                                $matchDatas = $league->match_datas;

                                if (count($matchDatas) > 0) {
                                    foreach($matchDatas as $match) {
                                        $splitId = DB::table('ffp_list_split')->insertGetId(
                                            ['dir_name' => $dirName,
                                            'top_head_type' => $topHeadType,
                                            'league_id' => $lId,
                                            'match' => json_encode($match),
                                            'created_at' => $createdAt]
                                        );
                                        // $insertIds[] = $splitId;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }*/
        // --- end separate main datas --- //
    }
}
