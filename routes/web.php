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

Route::get('/', function () {
    return view('home');
});

Route::get('/metodologia', function () {
    return view('method');
});

Route::get('/informacion_publica', function () {
    return view('public_info');
});

Route::get('/sujeto_obligado', function () {
    return view('subject_options');
});

Route::get('/numeral', function () {
    return view('numeral_options');
});

Route::get('/lista_so', function () {
    $sectors = \App\Sector::all();
    return view('subject_list', ['sectors' => $sectors]);
});

/*visualizations*/

Route::get('/cumplimiento_so/{round_id?}', 'Visualization@fulfillment');
Route::get('/sector/{round_id?}', 'Visualization@sector');
Route::get('/cumplimiento_num/{round_id?}', 'Visualization@numFulfillment');
Route::get('/sujeto/{subject_id}', 'Visualization@subject');
Route::get('/sujeto/{subject_id}/{round_id}', 'Visualization@subject');
//Route::get('/sujeto/pdf/{subject_id}/{round_id}', 'Visualization@subjectPDF');
Route::get('/sujeto/viewpdf/{subject_id}/{round_id}', 'Visualization@subjectViewPDF');
Route::get('/numeral/{numeral_id}/{round_id}', 'Visualization@numeral');
Route::get('/numeral/{numeral_id}', 'Visualization@numeral');
Route::get('/lista_numeral/{round_id?}', 'Visualization@numSorted');
Route::get('/avance/{round_id?}', 'Visualization@advancement');
Route::post('/generate_pdfs', 'Visualization@sendPDFs');
Route::get('/descargas','Visualization@downloads');
Route::get('/descargas/so','Visualization@downloadSoFile');
Route::get('/descargas/{round_id?}/{file_type?}','Visualization@downloadFile');
Route::post('mark0', 'AdminTools@mark0');

/*load scripts*/
//Route::get('load', 'Load@excel');
//Route::get('calc1', 'Load@calcFulfillment');
Route::post('calc2', 'Load@calcArtFulfillment');
Route::post('calc3', 'Load@calcSubjectArticle');
Route::post('calc4', 'Load@createCsv');
Route::post('calc5', 'Load@createSoCsv');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/*admin views*/
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
	Route::get('trackquestions/{track_id?}','QuestionsAdmin@index');
	Route::get('addtracks/{round_id?}','AdminTools@createTracks');
    
});