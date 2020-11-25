<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (env('APP_ENV') === 'production') {
    \URL::forceScheme('https');
}

// Route::resource('/', 'Web\WelcomeController');
Route::get('/', 'Web\WelcomeController@index')->name('index');
Route::get('/basketball', 'Web\BasketballController@index')->name('basketball'); // ดูบาส
Route::get('/blog', 'Web\ArticleController@articleList');
Route::get('/tags/{tag_name}', 'Web\ArticleController@articleTag');
Route::get('/game', 'Web\WelcomeController@prediction')->name('game-free-credit');
Route::get('/bet-stats/{user}/{month?}', 'Web\WelcomeController@betStats');
Route::get('/bet-stats-user/{user_id}/{month?}', 'Web\WelcomeController@betStatsUser');

Route::get('/ราคาบอล', 'Web\WelcomeController@footballPrice')->name('football-price');
Route::get('/ราคาบอลไหล', 'Web\WelcomeController@ffpDetail')->name('football-price-detail');

Route::get('/live', 'Web\FootballController@live')->name('live');
Route::get('/livescore/{date?}', 'Web\FootballController@livescore')->name('livescore');
Route::get('/ผลบอลเมื่อคืน', 'Web\FootballController@resultScore')->name('result');
Route::get('/h2h/{fixtureId}', 'Web\FootballController@headToHead')->name('h2h');
// Route::get('/scheduled-game', 'Web\FootballController@scheduledGame')->name('scheduled-game');
Route::get('matches', 'Web\FootballController@matches');

Route::get('/ทีเด็ดบอล/{date?}', 'Web\WelcomeController@tdedBall')->name('tdedball');
Route::get('/ทีเด็ดบอลต่อ/{month?}', 'Web\WelcomeController@tdedBallCont')->name('tdedball-cont');
Route::get('/ทีเด็ดบอลรอง/{month?}', 'Web\WelcomeController@tdedBallNotCont')->name('tdedball-not-cont');
Route::get('/ทีเด็ดบอลเต็ง/{month?}', 'Web\WelcomeController@tdedBallTeng')->name('tdedball-teng');
Route::get('/ทีเด็ดบอลสเต็ป2/{month?}', 'Web\WelcomeController@tdedBallStepTwo')->name('tdedball-step-two');
Route::get('/ทีเด็ดบอลสเต็ป3/{month?}', 'Web\WelcomeController@tdedBallStepThree')->name('tdedball-step-three');

Route::get('/ทีเด็ดบาส/{date?}', 'Web\BasketballController@tdedBas')->name('tdedbas');
Route::get('/ทีเด็ดบาสวันละตัว/{month?}', 'Web\BasketballController@tdedBasPerDay')->name('tdedbas-perday');

Route::get('/analysis', 'Web\WelcomeController@analysis')->name('analysis');
Route::get('/dooball', 'Web\WelcomeController@dooball')->name('dooball');
Route::get('/highlights', 'Web\WelcomeController@highlights')->name('highlights');
Route::get('/score', 'Web\WelcomeController@score')->name('score');
Route::get('/odds', 'Web\WelcomeController@odds')->name('odds');
Route::get('/programs', 'Web\WelcomeController@programs')->name('programs');
Route::get('/team', 'Web\WelcomeController@team')->name('team');
Route::get('/player', 'Web\WelcomeController@player')->name('player');

Route::get('/login-after-social', 'Auth\LoginController@loginAfterSocial'); // register + login

Route::post('check-login', 'Auth\LoginController@checkLogin')->name('check-login');
Route::get('/home', 'Web\HomeController@index')->name('home');
Auth::routes();

Route::get('auth/social', 'Auth\LoginController@show')->name('social.login');
Route::get('oauth/{driver}', 'Auth\LoginController@redirectToProvider')->name('social.oauth');
Route::get('oauth/{driver}/callback', 'Auth\LoginController@handleProviderCallback')->name('social.callback');

Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return redirect('admin/match');
    });

    Route::get('dashboard', 'Admin\DashboardController@index');

    Route::group(['middleware' => ['AdminAuth', 'DBConnection']], function () {
        Route::resource('page', 'Admin\PageController'); // admin/page/1 => view
        Route::resource('on-page', 'Admin\OnPageController');

        Route::resource('match', 'Admin\MatchController');
        Route::resource('link', 'Admin\LinkController');
        Route::resource('tournament', 'Admin\TournamentController');
        Route::resource('leaguesubpage', 'Admin\LeagueSubpageController');
        Route::resource('team', 'Admin\TeamController');
        Route::resource('channel', 'Admin\ChannelController');

        Route::resource('article', 'Admin\ArticleController');
        Route::get('article/{id}/related', 'Admin\ArticleController@related');

        Route::resource('tag', 'Admin\TagController');

        Route::resource('pre-bet', 'Admin\PreBetController');
        Route::resource('prediction', 'Admin\BetController');
        Route::resource('bet', 'Admin\BetController');
        Route::resource('text', 'Admin\TextController');
        Route::resource('tded', 'Admin\TdedController');

        Route::get('file-manager', 'Admin\FileManagerController@index');
        Route::get('file-manager/{directory}', 'Admin\FileManagerController@path');

        Route::get('ffp', 'Admin\FFPController@index');

        Route::get('theme-editor', 'Admin\ThemeEditorController@index');

        Route::prefix('settings')->group(function () {
            Route::resource('menu', 'Admin\MenuController');
            Route::resource('user', 'Admin\UserController');
            Route::get('user/{id}/reset-password', 'Admin\UserController@resetPassword');
            Route::resource('general', 'Admin\GeneralController');
            Route::get('social', 'Admin\GeneralController@social');
            Route::get('widget', 'Admin\WidgetController@index');
            Route::resource('league-decoration', 'Admin\WidgetLeagueController');
            Route::get('league-decoration/{id}/items', 'Admin\WidgetLeagueController@items'); // li list
            Route::get('league-decoration/{id}/items/{item_id?}', 'Admin\WidgetLeagueController@items'); // edit li
            Route::get('league-decoration/{id}/add-item', 'Admin\WidgetLeagueController@addItem'); // add li

            Route::get('api-football', 'Admin\APIFootballController@index');
            Route::get('api-football/create-api-page/{id}', 'Admin\APIFootballController@addItem');
            Route::get('api-football/edit-api-page/{id}/{item_id?}', 'Admin\APIFootballController@editItem');
        });

        Route::get('/nvs-php-v', function() {
            $data = phpinfo();
            return response()->json($data);
        });

        Route::get('/nvs-sql-v', function () {
            $dbName = env('DB_DATABASE');
            $userDB = env('DB_USERNAME');
            $passDB = env('DB_PASSWORD');
            $pdo = new PDO('mysql:host=localhost;dbname=' . $dbName, $userDB, $passDB);
            $sqlVersion = $pdo->query('select version()')->fetchColumn();
            return response()->json($sqlVersion);
        });
    });

    Route::resource('login', 'Admin\LoginController');
    Route::get('logout', 'Admin\LoginController@logout');
    // Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
});

// Route::get('/nvs-pass', function() {
    // $url = $_SERVER['REQUEST_URI'];
    // $url = urldecode($url);
    // $request = request();
    // $uri = $request->path();

//     $pass = \Hash::make('admin4321!');
//     echo $pass;
// });

Route::get('/googleb49fba0c8d22dcb2.html', 'Web\WelcomeController@google');
Route::get('/see-sitemap', 'Web\WelcomeController@seeSitemap');
Route::get('/robots.txt', 'Web\WelcomeController@robots');

// Route::get('/test-sync', 'Web\WelcomeController@testSync');
Route::get('/test-query', 'Web\WelcomeController@testQuery');

Route::get('/log/{file_name?}', 'Web\WelcomeController@logFile');

Route::get('/slide-handmade', function () {
    return view('frontend/slide-handmade');
});

Route::get('api-football', 'Web\FootballController@livescoreToday');
Route::get('/teams/{name}', 'Web\FootballController@leagueTeams')->name('teams');

// Route::get('/{title}', 'Web\ArticleDetailController@index');
Route::get('/{slug}', 'Web\ArticleDetailController@index');
Route::get('/{slug}/{page_url}', 'Web\ArticleDetailController@leaguePage');
// Route::get('/{slug}', 'Web\PageController@index'); // ไปเช็คใน ArticleDetailController@index