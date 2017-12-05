<?php
//ajax route
Route::get('/cashier/ajax/getstudentlist','Cashier\Ajax\GetStudentList@index');
//cashier
Route::get('/cashier/viewledger/{idno}','Cashier\StudentLedger@view');
Route::get('/cashier/reservation/{idno}','Cashier\StudentLedger@reservation');
Route::post('/cashier/reservation','Cashier\StudentLedger@postreservation');
Route::get('/cashier/viewreceipt/{reference_id}','Cashier\StudentLedger@viewreceipt');
?>
