<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\API\CommonController;

class DeleteLogFFPFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dooball:delete-ffp-log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For delete ffp log every 10 minutes';

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
        // $this->common::deleteLogFFP();
    }
}
