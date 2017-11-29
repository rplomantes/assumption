<?php
//ajax route
Route::get('/dean/ajax/getstudentlist','Dean\Ajax\GetStudentList@index');
Route::get('/cashier/ajax/getstudentlist','Cashier\Ajax\GetStudentList@index');
//dean
Route::get('/dean/assessment/{idno}','Dean\Assessment@assess');
Route::get('/dean/viewrecord/{idno}','Dean\Record@view');
//cashier
Route::get('/cashier/viewledger/{idno}','Cashier\StudentLedger@view');
?>