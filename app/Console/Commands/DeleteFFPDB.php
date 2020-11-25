<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DirList;
use Illuminate\Support\Facades\DB;
// use App\Http\Controllers\API\CommonController;

class DeleteFFPDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dooball:delete-ffp-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For delete ffp db every 10 minutes';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    // private $common;

    public function __construct()
    {
        parent::__construct();
        // $this->common = new CommonController();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // $sqlString = "DELETE FROM ffp_list WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 HOUR);";
        // $sqlStr = "DELETE FROM ffp_detail WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 HOUR);";

        DB::table('ffp_list')->whereRaw('created_at < DATE_SUB(NOW(), INTERVAL 48 HOUR)')->delete();
        DB::table('ffp_detail')->whereRaw('created_at < DATE_SUB(NOW(), INTERVAL 48 HOUR)')->delete();

        $allDir = array();
        $dirList = DirList::where('scraping_status', 1);

        if ($dirList->count() > 0) {
            foreach($dirList->get() as $dir) {
                $allDir[] = $dir->dir_name;
            }
        }

        DB::table('ffp_list_temp')->whereNotIn('dir_name', $allDir)->delete();

        // --- start match & match link --- //
        DB::table('matches')->whereRaw('created_at < DATE_SUB(NOW(), INTERVAL 3000 HOUR)')->delete();

        $matchDatas = DB::table('matches')->select('id');

        $matchIds = array();

        if ($matchDatas->count() > 0) {
            foreach($matchDatas->get() as $matchRow) {
                $matchIds[] = $matchRow->id;
            }
        }

        DB::table('match_links')->whereNotIn('match_id', $matchIds)->delete();
        // --- end match & match link --- //

        // maybe some common need run here
    }
}
