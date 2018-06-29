<?php
//CHED Enrollment
Route::get('/registrar_college/reports/ched_enrollment_reports','RegistrarCollege\Reports\ChedEnrollmentReportsController@index');
//Route::get('/registrar_college/reports/ajax/getstudent','RegistrarCollege\Reports\Ajax\GetStudent@getstudent');
Route::post('/registrar_college/reports/ched_enrollment_reports/generate', 'RegistrarCollege\Reports\ChedEnrollmentReportsController@print_report');

//print transcript of records
Route::get('/registrar_college/view_transcript/finalize_transcript/{idno}', 'RegistrarCollege\StudentRecord\StudentRecordController@finalize_transcript');
Route::post('/registrar_college/view_transcript/print_transcript', 'RegistrarCollege\StudentRecord\StudentRecordController@print_transcript');

//List Transfer Student
Route::get('/registrar_college/reports/list_transfer_student','RegistrarCollege\Reports\ListTransferStudentController@index');
Route::post('/registrar_college/reports/print_transfer_student','RegistrarCollege\Reports\ListTransferStudentController@print_transfer_student');

//List Unofficially Enrolled Student    
Route::get('/registrar_college/reports/list_unofficially_enrolled', 'RegistrarCollege\Reports\ListUnofficiallyEnrolledController@index');
Route::post('/registrar_college/reports/print_unofficially_enrolled', 'RegistrarCollege\Reports\ListUnofficiallyEnrolledController@print_unofficial');

//Total Daily Enrollment Statistics
Route::get('/registrar_college/reports/total_daily_enrollment_statistics/{date_start}/{date_end}', 'RegistrarCollege\Reports\TotalDailyEnrollmentController@index');
Route::get('/registrar_college/reports/enrollment_statistics/print_total_daily_enrollment_statistics/{date_start}/{date_end}', 'RegistrarCollege\Reports\TotalDailyEnrollmentController@print_daily_enrollment');
?>
