<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\API\DooballScraperController as DBScraperAPI;
use App\Http\Controllers\Web\FootballController as Football;

class SyncMatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dooball:sync-match';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For sync match every 10 minutes';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    private $scraper;
    private $football;

    public function __construct()
    {
        parent::__construct();
        $this->scraper = new DBScraperAPI();
        $this->football = new Football();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $matches = array();

        $stringMatches = file_get_contents(env('SCRAP_LINK'));
        if ($stringMatches) {
            $matches = json_decode($stringMatches);
        }

        // dd(gettype($matches));
        // $matches = (array) $matches;

        if (count($matches) > 0) {
            foreach($matches as $key => $value) {
                $this->scraper->algorithmCheckExistingMatch($value);
            }
        }
    }
}
