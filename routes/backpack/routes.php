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
    CRUD::resource('virals-excel-file', 'ViralsExcelFileCrudController');
    Route::get('virals-excel-file/{id}/show-log', 'ViralsExcelFileCrudController@showLog')->name('crud.virals-excel-file.showLog ');
    Route::post('virals-excel-file/get-relaton-column', 'ViralsExcelFileCrudController@getRelatonColumn')->name('virals.excel.getRelatonColumn');
    Route::get('virals-excel-file/download/{id}', 'ViralsExcelFileCrudController@downloadFile')->name('virals.excel.download');

    CRUD::resource('virals-excel-file-log', 'ViralsExcelFileLogCrudController');
    CRUD::resource('virals-excel-field', 'ViralsExcelFieldCrudController');
    Route::get('virals-excel-field/get-data/{id}', 'ViralsExcelFieldCrudController@getData')->name('virals-excel-field.get-data');
});