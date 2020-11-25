<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Storage;

class LogFileController extends Controller
{
    public function logAsFile($fileName = '', $message = '', $mode = '')
    {
        if ($mode == 'prepend') {
            Storage::disk('files')->prepend($fileName, $message);
        } else if ($mode == 'append') {
            Storage::disk('files')->append($fileName, $message);
        } else {
            Storage::disk('files')->put($fileName, $message);
        }
    }

    public function seeLogAsFile($fileName = '')
    {
        $fileName = 'what.html';

        // check exist
        $exists = Storage::disk('files')->exists($fileName);
    }

    public function downloadLogAsFile()
    {
        // file url => $url = Storage::url('file.jpg');
        // return Storage::download('file.jpg');
        // return Storage::download('file.jpg', $name, $headers);
    }

    public function logData()
    {
        // --- start log --- //
        // $message = 'Common function: formToSqlDate(' . $form_dash . ')' . (int) $form_dash;
        // Log::info($message);
        // --- end log --- //
    }

    public function seeHtmlFile($path = '../storage/logs/log.html')
    {
        // '../../node-scraper/log.html' => another folder, not laravel project
        $logDatas = file_get_contents($path);
        $datas = array('log_content' => $logDatas);
        return view('log-html', $datas);
    
        // $logOneDatas = file_get_contents($path);
        // $logTwoDatas = file_get_contents('../storage/app/log2.html');
        // $datas = array('log_one' => $logOneDatas, 'log_two' => $logTwoDatas);
        // return view('logs-html', $datas);
    }

    public function copyLogAsFile()
    {
        Storage::copy('old/file.jpg', 'new/file.jpg');
    }

    public function moveLogAsFile()
    {
        Storage::move('old/file.jpg', 'new/file.jpg');
    }

    public function deleteLogAsFile()
    {
        // Storage::delete('file.jpg');
        // Storage::delete(['file.jpg', 'file2.jpg']);
    }

    public function seeTree()
    {
        // $files = Storage::files($directory);
        // $files = Storage::allFiles($directory);
    }

    public function lgLevel($message = 'Log level...')
    {
        Log::emergency('The system is down!');
        Log::alert($message);
        Log::critical($message);
        Log::error($message);
        Log::warning($message);
        Log::notice($message);
        Log::info('Showing user profile for user: 7');
        Log::debug('An informational message.');
    }

    /*
    public static function deleteLogFFP()
    {
        $basePath = base_path();
        $path = $basePath . '/resources/views/node-scraper/ffp';

        // $appPath = app_path();
        // $publicPath = public_path();
        // $storagePath = storage_path();
        // Log::info('base_path: ' . $basePath);
        // Log::info('app_path: ' . $appPath);
        // Log::info('public_path: ' . $publicPath);
        // Log::info('storage_path: ' . $storagePath);

        $allFiles = self::listAllFile($path);

        arsort($allFiles);
        $now = time();

        if (count($allFiles) > 0) {
            foreach ($allFiles as $fname) {
                if (is_file($fname)) {
                    // --- start log --- //
                    // $message = '(' . $now . ' - ' . filemtime($fname) . ') >= (' . (60 * 10) . ') is ' . (($now - filemtime($fname)) >= (60 * 10));
                    // Log::info($message);
                    // --- end log --- //

                    if (($now - filemtime($fname)) >= (60 * 10)) { // 10 minutes
                        // Log::info('unlink: ' . $fname);
                        unlink($fname);
                    }
                }
            }
        }

    }
    */
}
