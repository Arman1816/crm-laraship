<?php

Route::put('settings/{setting}', 'SettingsController@update')->name('api.settings.update');
Route::get('settings/value/{code}/{default?}', 'SettingsController@getSettingValue')->name('api.settings.value');
Route::get('settings/active-languages', 'SettingsController@getActiveLanguages')->name('api.settings.active_languages');