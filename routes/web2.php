<?php
//ajax route
Route::get('/cashier/ajax/getstudentlist','Cashier\Ajax\GetStudentList@index');
//cashier
Route::get('/cashier/viewledger/{idno}','Cashier\StudentLedger@view');

Route::get('/cashier/reservation/{idno}','Cashier\StudentReservation@reservation');
Route::post('/cashier/reservation','Cashier\StudentReservation@postreservation');

Route::get('/cashier/viewreceipt/{reference_id}','Cashier\StudentLedger@viewreceipt');
Route::get('/cashier/other_payment/{idno}','Cashier\OtherPayment@other_payment');
?>
