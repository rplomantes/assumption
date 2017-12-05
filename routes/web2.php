<?php
//ajax route
Route::get('/dean/ajax/getstudentlist','Dean\Ajax\GetStudentList@index');
Route::get('/cashier/ajax/getstudentlist','Cashier\Ajax\GetStudentList@index');
//dean
Route::get('/dean/assessment/{idno}','Dean\Assessment@assess');
Route::get('/dean/viewrecord/{idno}','Dean\Record@view');
//cashier
Route::get('/cashier/viewledger/{idno}','Cashier\StudentLedger@view');
Route::get('/cashier/reservation/{idno}','Cashier\StudentLedger@reservation');
Route::post('/cashier/reservation','Cashier\StudentLedger@postreservation');
Route::get('/cashier/viewreceipt/{reference_id}','Cashier\StudentLedger@viewreceipt');
?>