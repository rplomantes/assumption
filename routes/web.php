<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|


Route::get('/', function () {
    return view('welcome');
});
*/

include_once 'web2.php';

Auth::routes();
Route::get('/', 'HomeController@index')->name('home');

//Registrar College - MAIN//////////////////////////////////////////////////////
Route::get('/ajax/registrar_college/getstudentlist', 'RegistrarCollege\Ajax\GetStudentList_ajax@getstudentlist');


//Registrar College Advising
//Set Up
Route::get('/registrar_college/advising/set_up', 'RegistrarCollege\Advising\AdvisingController@index');
Route::post('/registrar_college/advising/save_set_up', 'RegistrarCollege\Advising\AdvisingController@save');


//Registrar College Curriculum Management///////////////////////////////////////
//Curriculum
Route::get('/registrar_college/curriculum_management/curriculum', 'RegistrarCollege\CurriculumManagement\CurriculumController@index');
Route::get('/registrar_college/curriculum_management/view_curricula/{program_code}', 'RegistrarCollege\CurriculumManagement\CurriculumController@viewcurricula');
Route::get('/registrar_college/curriculum_management/list_curriculum/{program_code}/{curriculum_year}','RegistrarCollege\CurriculumManagement\CurriculumController@listcurriculum');
//Upload Curriculum
Route::get('/registrar_college/curriculum_management/upload_curriculum', 'RegistrarCollege\CurriculumManagement\UploadCurriculumController@index');
Route::post('/registrar_college/curriculum_management/upload', 'RegistrarCollege\CurriculumManagement\UploadCurriculumController@upload');

//Registrar College Admission///////////////////////////////////////////////////
//New Student
Route::get('/registrar_college/admission/new_student','RegistrarCollege\Admission\NewStudentController@index');
Route::post('/registrar_college/admission/add_new_student','RegistrarCollege\Admission\NewStudentController@add_new_student');

//Registrar College Instructors
//View Instructors
Route::get('/registrar_college/instructor/view_instructor', 'RegistrarCollege\Instructor\ViewInstructorsController@index');
Route::get('/registrar_college/instructor/add_instructor', 'RegistrarCollege\Instructor\ViewInstructorsController@view_add');
Route::post('/registrar_college/instructor/add_new_instructor', 'RegistrarCollege\Instructor\ViewInstructorsController@add');
Route::get('/registrar_college/instructor/modify_instructor/{idno}', 'RegistrarCollege\Instructor\ViewInstructorsController@view_modify');
Route::post('/registrar_college/instructor/modify_old_instructor', 'RegistrarCollege\Instructor\ViewInstructorsController@modify');


//Registrar College Reports/////////////////////////////////////////////////////
//Student List
Route::get('/registrar_college/reports/student_list/search','RegistrarCollege\Reports\StudentListController@search');
Route::get('/registrar_college/reports/student_list/print_search/{school_year}/{level}/{period}/{program_code}','RegistrarCollege\Reports\StudentListController@print_search');
Route::get('/registrar_college/reports/student_list/per_course','RegistrarCollege\Reports\StudentListController@per_course');
Route::get('/registrar_college/reports/student_list/print_per_course/{course_id}/{section}/{school_year}/{level}/{period}/{program_code}','RegistrarCollege\Reports\StudentListController@print_per_course');
//Ajax Student List
Route::get('/ajax/registrar_college/reports/student_list/search','RegistrarCollege\Reports\Ajax\StudentList_ajax@search');
Route::get('/ajax/registrar_college/reports/student_list/select_section','RegistrarCollege\Reports\Ajax\StudentList_ajax@select_section');
Route::get('/ajax/registrar_college/reports/student_list/select_course','RegistrarCollege\Reports\Ajax\StudentList_ajax@select_course');
Route::get('/ajax/registrar_college/reports/student_list/list_per_course','RegistrarCollege\Reports\Ajax\StudentList_ajax@list_per_course');
//Enrollment Statistics
Route::get('/registrar_college/reports/enrollment_statistics','RegistrarCollege\Reports\EnrollmentStatisticsController@index');


//Dean - MAIN///////////////////////////////////////////////////////////////////
Route::get('/dean/viewrecord/{idno}','Dean\Record@view');
Route::get('/ajax/dean/getstudentlist','Dean\Ajax\GetStudentList_ajax@getstudentlist');
//Assessment////////////////////////////////////////////////////////////////////
Route::get('/dean/advising/{idno}','Dean\Advising\Advising@advising');
Route::get('/dean/advising/confirm_advised/{idno}/{program_code}/{level}/{curriculum_year}','Dean\Advising\Advising@confirm_advised');
Route::get('/dean/advising/print_advising_slip/{idno}','Dean\Advising\Advising@print_advising_slip');
//Ajax Assessment
Route::get('/ajax/dean/advising/get_section', 'Dean\Advising\Ajax\advising_ajax@get_section');
Route::get('/ajax/dean/advising/get_course_offering', 'Dean\Advising\Ajax\advising_ajax@get_course_offering');
Route::get('/ajax/dean/advising/add_to_course_offered','Dean\Advising\Ajax\advising_ajax@add_to_course_offered');
Route::get('/ajax/dean/advising/remove_to_course_offered','Dean\Advising\Ajax\advising_ajax@remove_to_course_offered');
Route::get('/ajax/dean/advising/get_offering_per_search','Dean\Advising\Ajax\advising_ajax@get_offering_per_search');
Route::get('/ajax/dean/advising/add_all_courses','Dean\Advising\Ajax\advising_ajax@add_all_courses');