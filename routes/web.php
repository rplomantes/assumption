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

//Registrar College Assessment//////////////////////////////////////////////////
//Assessment
Route::get('/registrar_college/assessment/{idno}','RegistrarCollege\Assessment\AssessmentController@index');
Route::get('/registrar_college/assessment/save_assessment/{idno}','RegistrarCollege\Assessment\AssessmentController@save_assessment');
Route::get('/registrar_college/reassess/{idno}','RegistrarCollege\Assessment\AssessmentController@reassess');
//Ajax College Asssessment
Route::get('/ajax/registrar_college/assessment/get_assessed_payment', 'RegistrarCollege\Assessment\Ajax\assessment_ajax@get_assessed_payment');

//Registrar College Curriculum Management///////////////////////////////////////
//Curriculum
Route::get('/registrar_college/curriculum_management/curriculum', 'RegistrarCollege\CurriculumManagement\CurriculumController@index');
Route::get('/registrar_college/curriculum_management/view_curricula/{program_code}', 'RegistrarCollege\CurriculumManagement\CurriculumController@viewcurricula');
Route::get('/registrar_college/curriculum_management/list_curriculum/{program_code}/{curriculum_year}','RegistrarCollege\CurriculumManagement\CurriculumController@listcurriculum');
//Course Offering
Route::get('/registrar_college/curriculum_management/course_offering', 'RegistrarCollege\CurriculumManagement\CourseOfferingController@index');
Route::get('/registrar_college/curriculum_management/view_offering/{program_code}', 'RegistrarCollege\CurriculumManagement\CourseOfferingController@viewofferings');
//Ajax Course Offering
Route::get('/ajax/registrar_college/curriculum_management/view_offering/{program_code}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@listcurriculum');
Route::get('/ajax/registrar_college/curriculum_management/view_course_offered/{program_code}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@listcourse_offered');
Route::get('/ajax/registrar_college/curriculum_management/add_to_course_offered/{course_code}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@add_to_course_offered');
Route::get('/ajax/registrar_college/curriculum_management/add_all_to_course_offered/', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@add_all_courses');
Route::get('/ajax/registrar_college/curriculum_management/remove_course_offered/{id}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@remove_course');
//Course Schedule
Route::get('/registrar_college/curriculum_management/course_schedule', 'RegistrarCollege\CurriculumManagement\CourseScheduleController@index');
Route::get('/registrar_college/curriculum_management/edit_course_schedule/{course_offering_id}', 'RegistrarCollege\CurriculumManagement\CourseScheduleController@edit_course_schedule');
Route::post('/registrar_college/curriculum_management/add_course_schedule', 'RegistrarCollege\CurriculumManagement\CourseScheduleController@add_course_schedule');
Route::get('/registrar_college/curriculum_management/delete_course_schedule/{course_offering_id}/{schedule_id}', 'RegistrarCollege\CurriculumManagement\CourseScheduleController@delete_course_schedule');
//Ajax Course Schedule
Route::get('/ajax/registrar_college/curriculum_management/course_to_schedule/{program_code}', 'RegistrarCollege\CurriculumManagement\Ajax\coursescheduling_ajax@listcourse_to_schedule');
Route::get('/ajax/registrar_college/curriculum_management/show_available_rooms/', 'RegistrarCollege\CurriculumManagement\Ajax\coursescheduling_ajax@show_available_rooms');
//Assign Instructor
Route::get('/registrar_college/curriculum_management/faculty_loading', 'RegistrarCollege\CurriculumManagement\FacultyLoadingController@index');
Route::get('/registrar_college/curriculum_management/edit_faculty_loading/{idno}', 'RegistrarCollege\CurriculumManagement\FacultyLoadingController@edit_faculty_loading');
Route::post('/registrar_college/curriculum_management/add_faculty_loading', 'RegistrarCollege\CurriculumManagement\FacultyLoadingController@add_faculty_loading');
Route::get('/registrar_college/curriculum_management/remove_faculty_loading/{id}/{idno}', 'RegistrarCollege\CurriculumManagement\FacultyLoadingController@remove_faculty_loading');
//Ajax Assign Instructor
Route::get('/ajax/registrar_college/curriculum_management/show_available_loads/','RegistrarCollege\CurriculumManagement\Ajax\facultyloading_ajax@show_available_loads');

//Registrar College Admission///////////////////////////////////////////////////
//New Student
Route::get('/registrar_college/admission/new_student','RegistrarCollege\Admission\NewStudentController@index');
Route::post('/registrar_college/admission/add_new_student','RegistrarCollege\Admission\NewStudentController@add_new_student');

//Registrar College Reports/////////////////////////////////////////////////////
//Student List
Route::get('/registrar_college/reports/student_list/search','RegistrarCollege\Reports\StudentListController@search');
//Ajax Student List
Route::get('/ajax/registrar_college/reports/student_list/search','RegistrarCollege\Reports\Ajax\StudentList_ajax@search');



//Dean - MAIN///////////////////////////////////////////////////////////////////
Route::get('/dean/viewrecord/{idno}','Dean\Record@view');
Route::get('/ajax/dean/getstudentlist','Dean\Ajax\GetStudentList_ajax@getstudentlist');

//Assessment////////////////////////////////////////////////////////////////////
Route::get('/dean/assessment/{idno}','Dean\Assessment\Assessment@assess');
Route::get('/dean/assessment/confirm_advised/{idno}/{program_code}/{level}','Dean\Assessment\Assessment@confirm_advised');
Route::get('/dean/assessment/print_advising_slip/{idno}','Dean\Assessment\Assessment@print_advising_slip');
//Ajax Assessment
Route::get('/ajax/dean/assessment/get_section', 'Dean\Assessment\Ajax\assessment_ajax@get_section');
Route::get('/ajax/dean/assessment/get_course_offering', 'Dean\Assessment\Ajax\assessment_ajax@get_course_offering');
Route::get('/ajax/dean/assessment/add_to_course_offered','Dean\Assessment\Ajax\assessment_ajax@add_to_course_offered');
Route::get('/ajax/dean/assessment/remove_to_course_offered','Dean\Assessment\Ajax\assessment_ajax@remove_to_course_offered');
Route::get('/ajax/dean/assessment/get_offering_per_search','Dean\Assessment\Ajax\assessment_ajax@get_offering_per_search');
Route::get('/ajax/dean/assessment/add_all_courses','Dean\Assessment\Ajax\assessment_ajax@add_all_courses');