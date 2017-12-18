<?php

//ajax route
Route::get('/cashier/ajax/getstudentlist', 'Cashier\Ajax\GetStudentList@index');
//cashier
Route::get('/cashier/viewledger/{idno}', 'Cashier\StudentLedger@view');

Route::get('/cashier/reservation/{idno}', 'Cashier\StudentReservation@reservation');
Route::post('/cashier/reservation', 'Cashier\StudentReservation@postreservation');

Route::get('/cashier/viewreceipt/{reference_id}', 'Cashier\StudentLedger@viewreceipt');
Route::get('/cashier/other_payment/{idno}', 'Cashier\OtherPayment@other_payment');
Route::post('/cashier/other_payment', 'Cashier\OtherPayment@post_other_payment');
Route::get('/cashier/main_payment/{idno}', 'Cashier\MainPayment@main_payment');
Route::post('/cashier/main_payment', 'Cashier\MainPayment@post_main_payment');
Route::get('/cashier/reverserestore/{reference_id}', 'Cashier\StudentLedger@reverserestore');
Route::get('/cashier/collection_report/{date_from}/{date_to}','Cashier\CollectionReport@collection');
Route::get('/cashier/list_of_checks/{date_from}/{date_to}','Cashier\CollectionReport@list_of_checks');
Route::get('/cashier/credit_cards/{date_from}/{date_to}','Cashier\CollectionReport@credit_cards');
Route::get('/cashier/bank_deposits/{date_from}/{date_to}','Cashier\CollectionReport@bank_deposits');
Route::get('/cashier/non_student_payment','Cashier\OtherPayment@non_student_payment');
Route::post('/cashier/non_student_payment','Cashier\OtherPayment@post_non_student_payment');

//Registrar College Assessment//////////////////////////////////////////////////
//Assessment/////

Route::get('/registrar_college/assessment/{idno}', 'RegistrarCollege\Assessment\AssessmentController@index');
Route::get('/registrar_college/assessment/save_assessment/{idno}', 'RegistrarCollege\Assessment\AssessmentController@save_assessment');
Route::get('/registrar_college/reassess/{idno}', 'RegistrarCollege\Assessment\AssessmentController@reassess');
Route::get('/registrar_college/print_registration_form/{idno}', 'RegistrarCollege\Assessment\AssessmentController@print_registration_form');
Route::Post('/registrar_college/assessment/save_assessment','RegistrarCollege\Assessment\AssessmentController@save_assessment');
//Ajax College Asssessment
Route::get('/ajax/registrar_college/assessment/get_assessed_payment', 'RegistrarCollege\Assessment\Ajax\assessment_ajax@get_assessed_payment');
?>
