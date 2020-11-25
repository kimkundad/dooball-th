<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
// use App\Http\Controllers\API\DBSyncController as DBSyncAPI;
use App\Http\Controllers\API\LogFileController;
use App\Models\DirList;
use App\Models\ContentDetail;
use Illuminate\Support\Facades\DB;

class SyncFFPDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dooball:sync-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For sync ffp every minute';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    // private $dbSync;
    
    private $logAsFile;

    public function __construct()
    {
        parent::__construct();
        // $this->dbSync = new DBSyncAPI();
        $this->logAsFile = new LogFileController;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $mainTotal = 0;
        $detailTotal = 0;
        $chkMainTotal = 0;
        $chkDetailTotal = 0;
        $chkLeagueTotal = 0;
        $findLatestDir = '';
        $findLatestId = '';
        $chkDatas = array();
        $chkDetailDatas = array();
        $chkLeagueDatas = array();
        $apiLink = env('SCRAP_PRICE'); // https://carinsuranceissue.com

        // --- start main datas --- //
        $mainLink =  $apiLink . '/main-price';

        $ffpListDatas = DirList::select('dir_name')->orderBy('dir_name', 'desc')->first();

        if ($ffpListDatas) {
            $findLatestDir = $ffpListDatas->dir_name;
            $mainLink .= '/' . $findLatestDir;
        }

        $datas = array();
        $tenRows = file_get_contents($mainLink);

        if ($tenRows) {
            $datas = json_decode($tenRows);

            if (count($datas) > 0) {
                foreach($datas as $data) {
                    $findDir = DirList::where('dir_name', $data->dir_name);

                    if ($findDir->count() == 0) {
                        $ffp = new DirList;
                        $ffp->dir_name = $data->dir_name;
                        $ffp->content = $data->content;
                        $ffp->scraping_status = $data->scraping_status;
                        $ffp->created_at = $data->created_at;
                        $saved = $ffp->save();

                        if ($saved) {
                            $mainTotal++;
                        }
                    }
                }
            }
        }
        // --- end main datas --- //

        // --- start detail datas --- //
        $detailLink =  $apiLink . '/graph-detail';

        $ffpListDatas = ContentDetail::select('id')->orderBy('id', 'desc')->first();

        if ($ffpListDatas) {
            $findLatestId = $ffpListDatas->id;
            $detailLink .= '/' . $findLatestId;
        }

        $datas = array();
        $tenRows = file_get_contents($detailLink);

        if ($tenRows) {
            $datas = json_decode($tenRows);

            if (count($datas) > 0) {
                foreach($datas as $data) {
                    $findDetail = ContentDetail::where('id', $data->id);

                    if ($findDetail->count() == 0) {
                        $ffpDetail = new ContentDetail;
                        $ffpDetail->id = $data->id;
                        $ffpDetail->code = $data->code;
                        $ffpDetail->link = $data->link;
                        $ffpDetail->dir_name = $data->dir_name;
                        $ffpDetail->file_name = $data->file_name;

                        if (! $ffpDetail->league_name) {
                            $ffpDetail->league_name = $data->league_name;
                        }

                        if (! $ffpDetail->vs) {
                            $ffpDetail->vs = $data->vs;
                        }

                        if (! $ffpDetail->event_time) {
                            $ffpDetail->event_time = $data->event_time;
                        }

                        $ffpDetail->content = $data->content;
                        $ffpDetail->created_at = $data->created_at;
                        $saved = $ffpDetail->save();

                        if ($saved) {
                            $detailTotal++;
                        }
                    }
                }
            }
        }
        // --- end detail datas --- //

        // --- start check status main datas --- //
        $chkLink =  $apiLink . '/chk-main-status';

        if ($findLatestDir) {
            $chkLink .= '/' . $findLatestDir;
        }

        $sttDatas = array();
        $tenRows = file_get_contents($chkLink);

        if ($tenRows) {
            $sttDatas = json_decode($tenRows);

            if (count($sttDatas) > 0) {
                foreach($sttDatas as $data) {
                    $ffp = DirList::where('dir_name', $data->dir_name)->first();

                    if($ffp) {
                        DB::table('ffp_list')->where('dir_name', $data->dir_name)->update(array('scraping_status' => $data->scraping_status));
                        $chkDatas[] = $data->dir_name;
                        $chkMainTotal++;
                    }
                }
            }
        }
        // --- end check status main datas --- //

        // --- start check detail datas --- //
        $ids = '';
        $idsList = array();

        $nullList = ContentDetail::select('id')->where('content', '');
        $nullList->orderBy('id', 'asc');

        $createdAt = Date('Y-m-d H:i:s');
		$this->logAsFile->logAsFile('sync-ffp-detail.html', 'Hi, null: ' . $nullList->count() . ' At ' . $createdAt);

        if ($nullList->count() > 0) {
            $datas = $nullList->skip(0)->take(20)->get();

            foreach($datas as $data) {
                $idsList[] = $data->id;
            }

            $ids = implode(',', $idsList);
            $chkDetailLink = $apiLink . '/chk-detail/' . $ids;
            
			$this->logAsFile->logAsFile('sync-ffp-detail.html', '<br>Call API: ' . $chkDetailLink, 'append');

            $tenChkRows = file_get_contents($chkDetailLink);

            if ($tenChkRows) {
                $chkDetailDatas = json_decode($tenChkRows);

                if ($chkDetailDatas) {
                    $this->logAsFile->logAsFile('sync-ffp-detail.html', '<br>Data From API: ' . count($chkDetailDatas), 'append');

                    if (count($chkDetailDatas) > 0) {
                        foreach($chkDetailDatas as $detail) {

                            if ((int) $detail->force_delete == 1) {
                                DB::table('ffp_detail')->where('id', $detail->id)->delete();
                            } else {
                                $dtData = ContentDetail::find($detail->id);
                                if ($dtData) {
                                    $dtData->content = $detail->content;
                                    $saved = $dtData->save();
            
                                    if ($saved) {
                                        $chkDetailTotal++;
                                    }
                                }
                            }

                        }
                    }

                    $this->logAsFile->logAsFile('sync-ffp-detail.html', '<br>Update total: ' . $chkDetailTotal, 'append');
                }
            }

        }
        // --- end check detail datas --- //

        // --- start check league datas --- //
        $leagueIds = '';
        $leagueIdsList = array();
        $leagueInfo = array();

        $nullList = DB::table('ffp_detail')->select('id')->whereNull('league_name')->whereNull('vs')->whereNull('event_time');
        $nullList->orderBy('id', 'asc');

        if ($nullList->count() > 0) {
            $datas = $nullList->skip(0)->take(20)->get();

            foreach($datas as $data) {
                $leagueIdsList[] = $data->id;
            }

            $leagueIds = implode(',', $leagueIdsList);
            $chkDetailLink = $apiLink . '/chk-league/' . $leagueIds;

            $tenChkLRows = file_get_contents($chkDetailLink);

            if ($tenChkLRows) {
                $chkLeagueDatas = json_decode($tenChkLRows);

                if ($chkLeagueDatas) {
                    if (count($chkLeagueDatas) > 0) {
                        foreach($chkLeagueDatas as $detail) {
                            $dtData = ContentDetail::find($detail->id);
                            if ($dtData) {
                                $dtData->league_name = $detail->league_name;
                                $dtData->vs = $detail->vs;
                                $dtData->event_time = $detail->event_time;
                                $saved = $dtData->save();
        
                                if ($saved) {
                                    $chkLeagueTotal++;
                                }
                            }
                        }
                    }
                }
            }

            $repeatNullList = DB::table('ffp_detail')->select(['id', 'link', 'dir_name'])->whereNull('league_name')->whereNull('vs')->whereNull('event_time');
            $repeatNullList->orderBy('id', 'asc');
    
            if ($repeatNullList->count() > 0) {
                $repeatDatas = $repeatNullList->skip(0)->take(20)->get();

                foreach($repeatDatas as $data) {
                    $detailId = $data->id;
                    $detailLink = $data->link;
                    $dirName = $data->dir_name;
    
                    $leagueInfo[] = $this->common->findLeagueInfoFromDetailLink($dirName, $detailLink, $detailId);
                }
            }
        }
        // --- end check league datas --- //

        // return ['main_total' => $mainTotal, 'detail_total' => $detailTotal];
    }
}
