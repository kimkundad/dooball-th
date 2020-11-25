<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

if (env('APP_ENV') === 'production') {
    \URL::forceScheme('https');
}

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('big-league', 'API\PublicController@index'); // nothing

Route::get('live', 'API\PublicController@live');
Route::get('livescore', 'API\PublicController@livescore');
Route::get('programs', 'API\PublicController@programs'); // same livescore, diff response
Route::get('find-league-id', 'API\PublicController@findLeagueIdFromDate'); // same livescore, diff response
Route::get('h2h', 'API\PublicController@headToHead');
Route::get('lineups', 'API\PublicController@lineups');
Route::get('leagueTable', 'API\PublicController@leagueTable');
Route::get('statistics', 'API\PublicController@statistics');
Route::get('topscorers', 'API\PublicController@topscorers');

Route::get('fixtures-team', 'API\PublicController@fixturesTeam');
Route::get('statistics-alt', 'API\PublicController@statisticsAlt');
Route::get('playersTeam', 'API\PublicController@playersTeam');
Route::get('transfers', 'API\PublicController@transfers');
Route::get('playersSquad', 'API\PublicController@playersSquad');
Route::get('coachInfo', 'API\PublicController@coachInfo');

Route::get('leagueDatas', 'API\PublicController@leagueDatas');
Route::get('find-big-league', 'API\PublicController@findBigLeague');

Route::post('api-football', 'API\PublicController@apiFootball'); // call all API

// Route::get('livescore-old', 'API\PublicController@livescoreOld');
// Route::get('scheduled-game', 'API\PublicController@scheduledGame');
Route::post('receive-match-from-client', 'API\PublicController@matchPostHope');

Route::group(['middleware' => ['DBConnection']], function () {
    Route::post('/web/count-view', 'API\Front\CountViewController@index');
    Route::post('/web/rate', 'API\Front\CountViewController@rate');
    Route::post('/article-related', 'API\Front\ArticleController@articleRelated');
    Route::post('bet', 'API\Front\PredictionController@bet')->name('bet');

    // --- start move from server --- //
    Route::post('ffp', 'API\Front\ContentDetailController@index')->name('ffp'); // latest content
    Route::post('dir-list', 'API\Front\ContentDetailController@dirList')->name('dir-list'); // dir list
    Route::post('content-detail', 'API\Front\ContentDetailController@contentDetail')->name('content-detail');
    Route::post('data-to-graph', 'API\Front\ContentDetailController@dataToGraph')->name('data-to-graph'); // data to graph
    Route::post('current-detail-content', 'API\Front\ContentDetailController@currentDetailcontent')->name('current-detail-content');

    Route::post('ffp-custom', 'API\Front\ContentDetailController@ffpCustom')->name('ffp-custom'); // latest content premier league
    Route::post('prediction', 'API\Front\PredictionController@index')->name('prediction'); // same ffp, but get top of inner detail
    // --- end move from server --- //

    Route::get('game', 'API\PublicController@game')->name('game');
    Route::post('/save-to-ffp-temp', 'API\Front\ContentDetailController@saveToFFPTemp');

    Route::post('/save-to-prediction-temp', 'API\Front\PredictionController@saveToPredictionTemp');
    Route::post('/save-to-tded', 'API\Front\PredictionController@saveToTded');

    Route::post('register-normal', 'API\LoginController@registerNormal')->name('register-normal');
    Route::post('register-after-social', 'Auth\LoginController@registerAfterSocial')->name('register-after-social');
});

Route::post('/admin/login', 'API\LoginController@login');

Route::group(['middleware' => ['AdminAuth', 'DBConnection']], function () {
    Route::post('/admin/menu/list', 'API\Admin\MenuController@list');
    Route::post('/admin/menu/save-create', 'API\Admin\MenuController@saveCreate');
    Route::post('/admin/menu/save-update', 'API\Admin\MenuController@saveUpdate');
    Route::post('/admin/menu/delete', 'API\Admin\MenuController@deleteMenu');

    Route::post('/admin/user/list', 'API\Admin\UserController@list');
    Route::post('/admin/user/save-create', 'API\Admin\UserController@saveCreate');
    Route::post('/admin/user/save-update', 'API\Admin\UserController@saveUpdate');
    Route::post('/admin/user/reset-password', 'API\Admin\UserController@resetPassword');
    Route::post('/admin/user/delete', 'API\Admin\UserController@deleteUser');
    Route::post('/admin/user/restore', 'API\Admin\UserController@restoreUser');

    Route::post('/admin/match/list', 'API\Admin\MatchController@list');
    Route::post('/admin/match/save-create', 'API\Admin\MatchController@saveCreate');
    Route::post('/admin/match/save-update', 'API\Admin\MatchController@saveUpdate');
    Route::post('/admin/match/save-seq', 'API\Admin\MatchController@saveSeq');
    Route::post('/admin/match/reset-seq', 'API\Admin\MatchController@resetSeq');
    Route::post('/admin/match/delete', 'API\Admin\MatchController@deleteMatch');
    Route::post('/admin/match/multidelete', 'API\Admin\MatchController@multipleDelete');
    Route::post('/admin/match/restore', 'API\Admin\MatchController@restoreMatch');

    Route::post('/admin/match/key-filter', 'API\Admin\MatchController@keyFilter');

    Route::post('/admin/link/list', 'API\Admin\LinkController@list');
    Route::post('/admin/link/match-list', 'API\Admin\LinkController@matchList');
    Route::post('/admin/link/save-create', 'API\Admin\LinkController@saveCreate');
    Route::post('/admin/link/save-update', 'API\Admin\LinkController@saveUpdate');
    Route::post('/admin/link/delete', 'API\Admin\LinkController@deleteLink');

    Route::post('/admin/link-match/delete', 'API\Admin\LinkController@deleteLinkMatch');
    Route::post('/admin/link-match/save-seq', 'API\Admin\LinkController@saveSeq');

    Route::get('/admin/tournament/list', 'API\Admin\TournamentController@qList');
    Route::post('/admin/tournament/list', 'API\Admin\TournamentController@index');
    Route::post('/admin/tournament/save-create', 'API\Admin\TournamentController@saveCreate');
    Route::post('/admin/tournament/save-update', 'API\Admin\TournamentController@saveUpdate');
    Route::post('/admin/tournament/delete', 'API\Admin\TournamentController@deleteTournament');
    Route::post('/admin/tournament/multidelete', 'API\Admin\TournamentController@multipleDelete');
    Route::post('/admin/tournament/restore', 'API\Admin\TournamentController@restoreTournament');

    Route::post('/admin/leaguesubpage/list', 'API\Admin\LeagueSubpageController@index');
    Route::post('/admin/leaguesubpage/save-create', 'API\Admin\LeagueSubpageController@saveCreate');
    Route::post('/admin/leaguesubpage/save-update', 'API\Admin\LeagueSubpageController@saveUpdate');

    Route::get('/admin/team/list', 'API\Admin\TeamController@qList');
    Route::post('/admin/team/list', 'API\Admin\TeamController@list');
    Route::post('/admin/team/save-create', 'API\Admin\TeamController@saveCreate');
    Route::post('/admin/team/save-update', 'API\Admin\TeamController@saveUpdate');
    Route::post('/admin/team/delete', 'API\Admin\TeamController@deleteTeam');
    Route::post('/admin/team/multidelete', 'API\Admin\TeamController@multipleDelete');
    Route::post('/admin/team/restore', 'API\Admin\TeamController@restoreTeam');

    Route::get('/admin/channel/list', 'API\Admin\ChannelController@qList');
    Route::post('/admin/channel/list', 'API\Admin\ChannelController@list');
    Route::post('/admin/channel/save-create', 'API\Admin\ChannelController@saveCreate');
    Route::post('/admin/channel/save-update', 'API\Admin\ChannelController@saveUpdate');
    Route::post('/admin/channel/delete', 'API\Admin\ChannelController@deleteChannel');
    Route::post('/admin/channel/multidelete', 'API\Admin\ChannelController@multipleDelete');
    Route::post('/admin/channel/restore', 'API\Admin\ChannelController@restoreChannel');

    Route::post('/admin/article/list', 'API\Admin\ArticleController@list');
    Route::post('/admin/article/save-create', 'API\Admin\ArticleController@saveCreate');
    Route::post('/admin/article/save-update', 'API\Admin\ArticleController@saveUpdate');
    Route::post('/admin/article/save-publish', 'API\Admin\ArticleController@savePublish');
    Route::post('/admin/article/delete', 'API\Admin\ArticleController@deleteArticle');
    Route::post('/admin/article/multidelete', 'API\Admin\ArticleController@multipleDelete');
    Route::post('/admin/article/multirestore', 'API\Admin\ArticleController@multipleRestore');
    Route::post('/admin/article/restore', 'API\Admin\ArticleController@restoreArticle');

    Route::post('/admin/article/related-list', 'API\Admin\ArticleController@relatedList');
    Route::post('/admin/article/related', 'API\Admin\ArticleController@related');

    Route::get('/admin/tag/list', 'API\Admin\TagController@qList');
    Route::post('/admin/tag/list', 'API\Admin\TagController@list');
    Route::post('/admin/tag/save-create', 'API\Admin\TagController@saveCreate');
    Route::post('/admin/tag/save-update', 'API\Admin\TagController@saveUpdate');
    Route::post('/admin/tag/delete', 'API\Admin\TagController@deleteTeam');
    Route::post('/admin/tag/multidelete', 'API\Admin\TagController@multipleDelete');
    Route::post('/admin/tag/restore', 'API\Admin\TagController@restoreTeam');
    
    Route::post('/admin/pre-bet/list', 'API\Admin\PreBetController@list');
    Route::post('/admin/pre-bet/save-update', 'API\Admin\PreBetController@saveUpdate');

    Route::post('/admin/tded/list', 'API\Admin\TdedController@index');
    Route::post('/admin/tded/save-update', 'API\Admin\TdedController@saveUpdate');

    Route::post('/admin/prediction/list', 'API\Admin\PredictionController@list');
    Route::post('/admin/prediction/save-create', 'API\Admin\PredictionController@saveCreate');
    Route::post('/admin/prediction/save-update', 'API\Admin\PredictionController@saveUpdate');
    Route::post('/admin/prediction/delete', 'API\Admin\PredictionController@deletePrediction');
    Route::post('/admin/prediction/multidelete', 'API\Admin\PredictionController@multipleDelete');
    Route::post('/admin/prediction/multirestore', 'API\Admin\PredictionController@multipleRestore');
    Route::post('/admin/prediction/restore', 'API\Admin\PredictionController@restorePrediction');

    Route::post('/admin/bet/list', 'API\Admin\BetController@list');
    Route::post('/admin/bet/save-create', 'API\Admin\BetController@saveCreate');
    Route::post('/admin/bet/save-update', 'API\Admin\BetController@saveUpdate');
    Route::post('/admin/bet/delete', 'API\Admin\BetController@deleteBet');
    Route::post('/admin/bet/multidelete', 'API\Admin\BetController@multipleDelete');
    Route::post('/admin/bet/multirestore', 'API\Admin\BetController@multipleRestore');
    Route::post('/admin/bet/restore', 'API\Admin\BetController@restoreBet');

    Route::get('/admin/text/list', 'API\Admin\TextController@qList');
    Route::post('/admin/text/list', 'API\Admin\TextController@list');
    Route::post('/admin/text/save-create', 'API\Admin\TextController@saveCreate');
    Route::post('/admin/text/save-update', 'API\Admin\TextController@saveUpdate');
    Route::post('/admin/text/delete', 'API\Admin\TextController@deleteText');
    Route::post('/admin/text/multidelete', 'API\Admin\TextController@multipleDelete');
    Route::post('/admin/text/multirestore', 'API\Admin\TextController@multipleRestore');
    Route::post('/admin/text/restore', 'API\Admin\TextController@restoreText');

    Route::post('/admin/page/list', 'API\Admin\PageController@list');
    Route::post('/admin/page/save-create', 'API\Admin\PageController@saveCreate');
    Route::post('/admin/page/save-update', 'API\Admin\PageController@saveUpdate');
    Route::post('/admin/page/save-publish', 'API\Admin\PageController@savePublish');
    Route::post('/admin/page/delete', 'API\Admin\PageController@deletePage');
    Route::post('/admin/page/multidelete', 'API\Admin\PageController@multipleDelete');
    Route::post('/admin/page/multirestore', 'API\Admin\PageController@multipleRestore');
    Route::post('/admin/page/restore', 'API\Admin\PageController@restorePage');

    Route::post('/admin/on-page/list', 'API\Admin\OnPageController@index');
    Route::post('/admin/on-page/save-create', 'API\Admin\OnPageController@saveCreate');
    Route::post('/admin/on-page/save-update', 'API\Admin\OnPageController@saveUpdate');

    Route::post('/admin/add-directory', 'API\CommonController@addDirectoryAPI');
    Route::post('/admin/delete-directory', 'API\CommonController@deleteDirectoryAPI');
    Route::post('/admin/add-file', 'API\CommonController@addFileAPI');
    Route::post('/admin/rename-file', 'API\CommonController@renameFileAPI');
    Route::post('/admin/delete-file', 'API\CommonController@deleteFileAPI');

    Route::post('/admin/general/save', 'API\Admin\GeneralController@saveGeneral');
    Route::post('/admin/general/save-social', 'API\Admin\GeneralController@saveSocial');

    Route::post('/admin/widget/order-list', 'API\Admin\WidgetController@widgetOrderList');
    Route::post('/admin/widget/order-info', 'API\Admin\WidgetController@widgetOrderInfo');
    Route::post('/admin/widget/order-create', 'API\Admin\WidgetController@saveWidgetCreate');
    Route::post('/admin/widget/order-update', 'API\Admin\WidgetController@saveWidgetUpdate');
    Route::post('/admin/widget/order-show-hide', 'API\Admin\WidgetController@showHideWidgetOrder');
    Route::post('/admin/widget/order-delete', 'API\Admin\WidgetController@deleteWidgetOrder');

    Route::post('/admin/league-decoration/list', 'API\Admin\WidgetLeagueController@index');
    Route::post('/admin/league-decoration/save-create', 'API\Admin\WidgetLeagueController@saveCreate');
    Route::post('/admin/league-decoration/save-update', 'API\Admin\WidgetLeagueController@saveUpdate');
    Route::post('/admin/league-decoration/li-list', 'API\Admin\WidgetLeagueController@liList');
    Route::post('/admin/league-decoration/save-li-create', 'API\Admin\WidgetLeagueController@saveLiCreate');
    Route::post('/admin/league-decoration/save-li-update', 'API\Admin\WidgetLeagueController@saveLiUpdate');

    Route::post('/admin/api-football/list', 'API\Admin\APIFootballController@index');
    Route::post('/admin/api-football/save-create', 'API\Admin\APIFootballController@saveCreate');
    Route::post('/admin/api-football/save-update', 'API\Admin\APIFootballController@saveUpdate');
    Route::post('/admin/api-football/delete', 'API\Admin\APIFootballController@deleteItem');

    Route::post('/admin/ffp/list', 'API\Admin\FFPController@list');
});

Route::get('test-server', function() {
    return 'NVS Good.';
});
