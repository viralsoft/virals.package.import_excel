<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'ViralsBackpack\BackPackExcel\Http\Controllers',
], function () { // custom admin routes
    Route::get('excel-fields', 'ExcelFieldController@index')->name('excel-fields.index');
    Route::delete('excel-fields/{field}', 'ExcelFieldController@destroy')->name('excel-fields.destroy');
    Route::get('excel-fields/process-list', 'ExcelFieldController@process')->name('excel-fields.process-list');
    Route::get('excel-fields/create', 'ExcelFieldController@create')->name('excel-fields.create');
    Route::get('excel-fields/{field}', 'ExcelFieldController@show')->name('excel-fields.show');
    Route::get('excel-fields/{field}/edit', 'ExcelFieldController@edit')->name('excel-fields.edit');
    Route::post('excel-fields/get-columns', 'ExcelFieldController@getColumns')->name('excel-fields.get-columns');

    Route::get('excel-files', 'ExcelFileController@index')->name('excel-files.index');
    Route::delete('excel-files/{file}', 'ExcelFileController@destroy')->name('excel-files.destroy');
    Route::get('excel-files/process-list', 'ExcelFileController@process')->name('excel-files.process-list');
    Route::get('excel-files/{file}', 'ExcelFileController@show')->name('excel-files.show');
    Route::get('excel-files/download/{id}', 'ExcelFileController@downloadFile')->name('excel-files.download');
    Route::post('excel-files/get-relaton-column', 'ExcelFileController@getRelatonColumn')->name('excel-files.getRelatonColumn');

    Route::get('excel-logs', 'ExcelLogController@index')->name('excel-logs.index');
    Route::delete('excel-logs/{log}', 'ExcelLogController@destroy')->name('excel-logs.destroy');
    Route::get('excel-logs/process-list', 'ExcelLogController@process')->name('excel-logs.process-list');
    Route::get('excel-logs/{log}', 'ExcelLogController@show')->name('excel-logs.show');
});