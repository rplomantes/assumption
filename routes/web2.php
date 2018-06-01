<?php

//admission view student info
Route::get('/admission_hed/view_info/{idno}','AdmissionHED\ViewInfoAdmissionHedController@view_info');
Route::post('/admission_hed/update_info','AdmissionHED\ViewInfoAdmissionHedController@update_info');
//ajax route
Route::get('/bedregistrar/ajax/view_list', 'BedRegistrar\Ajax\GetStudentList@view_list');
Route::get('/cashier/ajax/getstudentlist', 'Cashier\Ajax\GetStudentList@index');
Route::get('/cashier/ajax/getreceipt','Cashier\Ajax\GetStudentList@getreceipt');
Route::get('/cashier/ajax/setreceipt','Cashier\Ajax\GetStudentList@setreceipt');
Route::get('/accounting/ajax/getotherpayment','Accounting\Ajax\GetOtherPayment@getotherpayment');
Route::get('/bedregistrar/ajax/getstudentlist','BedRegistrar\Ajax\GetStudentList@index');
Route::get('/bedregistrar/ajax/book_materials/{current_level}','BedRegistrar\Ajax\BookMaterials@index');
Route::get('/bedregistrar/ajax/peuniforms/{current_level}','BedRegistrar\Ajax\BookMaterials@peuniforms');
Route::get('/bedregistrar/ajax/getUniformAmount','BedRegistrar\Ajax\BookMaterials@getUniformAmount');
Route::get('/bedregistrar/ajax/getsection','BedRegistrar\Ajax\GetStudentList@getsection');
Route::get('/bedregistrar/ajax/studentlevel','BedRegistrar\Ajax\GetStudentList@studentlevel');
Route::get('/bedregistrar/ajax/sectioncontrol','BedRegistrar\Ajax\GetStudentList@sectioncontrol');
Route::get('/bedregistrar/ajax/sectionlist','Bedregistrar\Ajax\GetStudentList@pop_section_list');
Route::get('/registrarcollege/ajax/getprogram', 'RegistrarCollege\Admission\Ajax\getcourseController@getCourse');
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
Route::get('/cashier/print/list_of_checks/{date_from}/{date_to}','Cashier\PrintController@print_list_of_checks');
Route::get('/cashier/credit_cards/{date_from}/{date_to}','Cashier\CollectionReport@credit_cards');
Route::get('/cashier/bank_deposits/{date_from}/{date_to}','Cashier\CollectionReport@bank_deposits');
Route::get('/cashier/non_student_payment','Cashier\OtherPayment@non_student_payment');
Route::post('/cashier/non_student_payment','Cashier\OtherPayment@post_non_student_payment');
Route::get('/cashier/set_receiptd','Cashier\ColllectioReport@set_receipt');
Route::get('/cashier/deposit_slip/{transaction_date}','Cashier\CollectionReport@deposit_slip');
Route::post('/cashier/deposit_slip','Cashier\CollectionReport@post_deposit_slip');
Route::get('/cashier/remove_deposit/{id}','Cashier\CollectionReport@remove_deposit');
Route::get('/cashier/printreceipt/{reference_id}', 'Cashier\PrintController@printreceipt');
Route::get('/cashier/print_collection_report/{datefrom}/{dateto}','Cashier\PrintController@print_collection_report');
//Registrar College Assessment//////////////////////////////////////////////////
//Assessment/////

Route::get('/registrar_college/assessment/{idno}', 'RegistrarCollege\Assessment\AssessmentController@index');
Route::get('/registrar_college/assessment/save_assessment/{idno}', 'RegistrarCollege\Assessment\AssessmentController@save_assessment');
Route::get('/registrar_college/reassess/{idno}', 'RegistrarCollege\Assessment\AssessmentController@reassess');
Route::get('/registrar_college/print_registration_form/{idno}/{school_year}/{period}', 'RegistrarCollege\Assessment\AssessmentController@print_registration_form');
Route::Post('/registrar_college/assessment/save_assessment','RegistrarCollege\Assessment\AssessmentController@save_assessment');
//Ajax College Asssessment


Route::get('/ajax/registrar_college/assessment/get_assessed_payment', 'RegistrarCollege\Assessment\Ajax\assessment_ajax@get_assessed_payment');

Route::get('/accounting/debit_memo/{idno}','Accounting\DebitMemo@index');
Route::post('/accounting/debit_memo','Accounting\DebitMemo@post_debit_memo');
Route::get('/accounting/view_debit_memo/{reference_id}','Accounting\DebitMemo@view_debit_memo');
Route::get('/accounting/add_to_account/{idno}','Accounting\AddAccount@add_to_account');
Route::get('/accounting/set_other_payment','Accounting\AddAccount@set_other_payment');
Route::post('/accounting/add_to_account','Accounting\AddAccount@post_add_to_account');
Route::get('/accounting/remove_other_payment/{id}','Accounting\AddAccount@remove_other_payment');
Route::get('/accounting/set_other_payment','Accounting\AddAccount@set_other_payment');
Route::post('/accounting/set_other_payment','Accounting\AddAccount@post_set_other_payment');
Route::get('/accounting/remove_set_other_payment/{id}','Accounting\AddAccount@remove_set_other_payment');
Route::get('/accounting/cash_receipt','Accounting\BookOfAccount@cash_receipt');
//BED registrar
Route::get('/bedregistrar/info/{idno}','BedRegistrar\Registration@info');
Route::get('/bedregistrar/assess/{idno}','BedRegistrar\Assess@assess');
Route::get('/bedregistrar/enrollment_statistics/{school_year}','BedRegistrar\Assess@enrollment_statistics');
Route::get('/bedregistrar/registration','BedRegistrar\Registration@register');
Route::post('/bedregistrar/registration','BedRegistrar\Registration@post_register');
Route::post('/bedregistrar/assess','BedRegistrar\Assess@post_assess');
Route::get('/bedregistrar/reassess/{idno}','BedRegistrar\Assess@reassess');
Route::post('/bedregistrar/resetpassword','BedRegistrar\Registration@reset_password');
Route::get('/bedregistrar/student_list','BedRegistrar\Registration@student_list');
Route::post('/bedregistrar/updateinfo/{idno}','BedRegistrar\Registration@updateinfo');
Route::get('/bedregistrar/print/student_list/{level}/{strand}/{section}/{school_year}','BedRegistrar\Ajax\GetStudentList@print_student_list');
Route::get('/bedregistrar/sectioning','BedRegistrar\Registration@sectioning');
//update
//Route::get('updatebedlevel','Updater@updateBedLevel');

//bookstore ajax

Route::get('bookstore/ajax/getstudentlist','Bookstore\Ajax\GetStudentList@index');

//bookstore
Route::get('/bookstore/view_order/{idno}','Bookstore\Order@view_order');
Route::get('/bookstore/place_order/{idno}','Bookstore\Order@place_order');
Route::get('/bookstore/print_order/{idno}','Bookstore\Order@print_order');
//bookstor ajax
Route::get('/bookstore/ajax/change_remarks','Bookstore\Ajax\BookMaterial@change_remarks');

?>
