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

//Registrar College - View Info/////////////////////////////////////////////////
Route::get('registrar_college/view_info/{idno}','RegistrarCollege\ViewInfo\ViewInfoController@view_info');
Route::post('registrar_college/save_info/{idno}','RegistrarCollege\ViewInfo\ViewInfoController@save_info');
//Registrar College - Student Record////////////////////////////////////////////
Route::get('registrar_college/student_record/{idno}','RegistrarCollege\StudentRecord\StudentRecordController@view_record');

//Registrar College Advising
//Set Up
Route::get('/registrar_college/advising/set_up', 'RegistrarCollege\Advising\AdvisingController@index');
Route::post('/registrar_college/advising/save_set_up', 'RegistrarCollege\Advising\AdvisingController@save');


//Registrar College Curriculum Management///////////////////////////////////////
//Curriculum
Route::get('/registrar_college/curriculum_management/curriculum', 'RegistrarCollege\CurriculumManagement\CurriculumController@index');
Route::get('/registrar_college/curriculum_management/view_curricula/{program_code}', 'RegistrarCollege\CurriculumManagement\CurriculumController@viewcurricula');
Route::get('/registrar_college/curriculum_management/list_curriculum/{program_code}/{curriculum_year}','RegistrarCollege\CurriculumManagement\CurriculumController@listcurriculum');
//Add Electives
Route::get('/registrar_college/curriculum_management/add_electives','RegistrarCollege\CurriculumManagement\AddElectivesController@index');
//Ajax Electives
Route::get('/ajax/registrar_college/curriculum_management/get_electives', 'RegistrarCollege\CurriculumManagement\Ajax\electives_ajax@getelectives');
Route::get('/ajax/registrar_college/curriculum_management/add_electives', 'RegistrarCollege\CurriculumManagement\Ajax\electives_ajax@addelectives');
Route::get('/ajax/registrar_college/curriculum_management/remove_electives', 'RegistrarCollege\CurriculumManagement\Ajax\electives_ajax@removeelectives');
//Upload Curriculum
Route::get('/registrar_college/curriculum_management/upload_curriculum', 'RegistrarCollege\CurriculumManagement\UploadCurriculumController@index');
Route::post('/registrar_college/curriculum_management/upload', 'RegistrarCollege\CurriculumManagement\UploadCurriculumController@upload');
//Course Offering
Route::get('/registrar_college/curriculum_management/course_offering', 'RegistrarCollege\CurriculumManagement\CourseOfferingController@index');
Route::get('/registrar_college/curriculum_management/view_offering/{program_code}', 'RegistrarCollege\CurriculumManagement\CourseOfferingController@viewofferings');
//Ajax Course Offering
Route::get('/ajax/registrar_college/curriculum_management/view_offering/{program_code}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@listcurriculum');
Route::get('/ajax/registrar_college/curriculum_management/view_course_offered/{program_code}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@listcourse_offered');
Route::get('/ajax/registrar_college/curriculum_management/add_to_course_offered/{course_code}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@add_to_course_offered');
Route::get('/ajax/registrar_college/curriculum_management/add_all_to_course_offered/', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@add_all_courses');
Route::get('/ajax/registrar_college/curriculum_management/remove_course_offered/{id}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@remove_course');
Route::get('/ajax/registrar_college/curriculum_management/add_offering_electives', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@addelectives');
//Course Schedule
Route::get('/registrar_college/curriculum_management/course_schedule', 'RegistrarCollege\CurriculumManagement\CourseScheduleController@index');
Route::get('/registrar_college/curriculum_management/edit_course_schedule/{course_offering_id}', 'RegistrarCollege\CurriculumManagement\CourseScheduleController@edit_course_schedule');
Route::post('/registrar_college/curriculum_management/add_course_schedule', 'RegistrarCollege\CurriculumManagement\CourseScheduleController@add_course_schedule');
Route::get('/registrar_college/curriculum_management/merge_schedule/{schedule_id}/{course_id}','RegistrarCollege\CurriculumManagement\CourseScheduleController@merge_schedule');
Route::get('/registrar_college/curriculum_management/unmerged_schedule/{course_offering_id}', 'RegistrarCollege\CurriculumManagement\CourseScheduleController@unmerged_schedule');
//Ajax Course Schedule
Route::get('/ajax/registrar_college/curriculum_management/course_to_schedule/{program_code}', 'RegistrarCollege\CurriculumManagement\Ajax\coursescheduling_ajax@listcourse_to_schedule');
Route::get('/ajax/registrar_college/curriculum_management/show_available_rooms/', 'RegistrarCollege\CurriculumManagement\Ajax\coursescheduling_ajax@show_available_rooms');
Route::get('/ajax/registrar_college/curriculum_management/get_section/', 'RegistrarCollege\CurriculumManagement\Ajax\coursescheduling_ajax@get_section');
//Assign Instructor
Route::get('/registrar_college/curriculum_management/faculty_loading', 'RegistrarCollege\CurriculumManagement\FacultyLoadingController@index');
Route::get('/registrar_college/curriculum_management/edit_faculty_loading/{idno}', 'RegistrarCollege\CurriculumManagement\FacultyLoadingController@edit_faculty_loading');
Route::post('/registrar_college/curriculum_management/add_faculty_loading', 'RegistrarCollege\CurriculumManagement\FacultyLoadingController@add_faculty_loading');
Route::get('/registrar_college/curriculum_management/remove_faculty_loading/{id}/{idno}', 'RegistrarCollege\CurriculumManagement\FacultyLoadingController@remove_faculty_loading');
//Ajax Assign Instructor
Route::get('/ajax/registrar_college/curriculum_management/show_available_loads/','RegistrarCollege\CurriculumManagement\Ajax\facultyloading_ajax@show_available_loads');
//Edit Schedule
Route::get('/registrar_college/curriculum_management/edit_schedule', 'RegistrarCollege\CurriculumManagement\ScheduleEditorController@index');

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


//Registrar Grade Management
//Close/Open Grade Module
Route::get('/registrar_college/grade_management/open_close', 'RegistrarCollege\GradeManagement\OpenCloseController@setup');
Route::post('/registrar_college/grade_management/open_close/submit', 'RegistrarCollege\GradeManagement\OpenCloseController@submit');
//View Grades
Route::get('/registrar_college/grade_management/view_grades', 'RegistrarCollege\GradeManagement\GradesController@view_grades');
//Ajax View Grades
Route::get('/ajax/registrar_college/grade_management/get_schedules', 'RegistrarCollege\GradeManagement\Ajax\AjaxViewGrades@view_grades');
Route::get('/ajax/registrar_college/grade_management/get_list_students', 'RegistrarCollege\GradeManagement\Ajax\AjaxViewGrades@get_list_students');
Route::get('/ajax/registrar_college/grade_management/lock/{idno}', 'RegistrarCollege\GradeManagement\Ajax\AjaxViewGrades@lock');
Route::get('/ajax/registrar_college/grade_management/unlock/{idno}', 'RegistrarCollege\GradeManagement\Ajax\AjaxViewGrades@unlock');
Route::get('/ajax/registrar_college/grade_management/approve_all', 'RegistrarCollege\GradeManagement\Ajax\AjaxViewGrades@approve_all');

//Registrar College Reports/////////////////////////////////////////////////////
//Student List
Route::get('/registrar_college/reports/student_list/search','RegistrarCollege\Reports\StudentListController@search');
Route::get('/registrar_college/reports/student_list/print_search/{school_year}/{level}/{period}/{program_code}','RegistrarCollege\Reports\StudentListController@print_search');
Route::get('/registrar_college/reports/student_list/per_course','RegistrarCollege\Reports\StudentListController@per_course');
Route::get('/registrar_college/reports/student_list/print_per_course/{course_id}/{section}/{section_name}/{school_year}/{level}/{period}/{program_code}','RegistrarCollege\Reports\StudentListController@print_per_course');
//Ajax Student List
Route::get('/ajax/registrar_college/reports/student_list/search','RegistrarCollege\Reports\Ajax\StudentList_ajax@search');
Route::get('/ajax/registrar_college/reports/student_list/select_section','RegistrarCollege\Reports\Ajax\StudentList_ajax@select_section');
Route::get('/ajax/registrar_college/reports/student_list/select_course','RegistrarCollege\Reports\Ajax\StudentList_ajax@select_course');
Route::get('/ajax/registrar_college/reports/student_list/list_per_course','RegistrarCollege\Reports\Ajax\StudentList_ajax@list_per_course');
//Enrollment Statistics
Route::get('/registrar_college/reports/enrollment_statistics/{school_year}/{period}','RegistrarCollege\Reports\EnrollmentStatisticsController@index');
Route::get('/registrar_college/reports/enrollment_statistics/print_enrollment_statistics/{school_year}/{period}','RegistrarCollege\Reports\EnrollmentStatisticsController@print_statistics');


//Dean - MAIN///////////////////////////////////////////////////////////////////
Route::get('/dean/viewrecord/{idno}','Dean\Record@view');
Route::get('/ajax/dean/getstudentlist','Dean\Ajax\GetStudentList_ajax@getstudentlist');
//Assessment////////////////////////////////////////////////////////////////////
Route::get('/dean/advising/{idno}','Dean\Advising\Advising@advising');
Route::get('/dean/advising/confirm_advised/{idno}/{program_code}/{level}/{curriculum_year}/{section}','Dean\Advising\Advising@confirm_advised');
Route::get('/dean/advising/print_advising_slip/{idno}','Dean\Advising\Advising@print_advising_slip');
//Ajax Assessment
Route::get('/ajax/dean/advising/get_section', 'Dean\Advising\Ajax\advising_ajax@get_section');
Route::get('/ajax/dean/advising/get_course_offering', 'Dean\Advising\Ajax\advising_ajax@get_course_offering');
Route::get('/ajax/dean/advising/add_to_course_offered','Dean\Advising\Ajax\advising_ajax@add_to_course_offered');
Route::get('/ajax/dean/advising/remove_to_course_offered','Dean\Advising\Ajax\advising_ajax@remove_to_course_offered');
Route::get('/ajax/dean/advising/get_offering_per_search','Dean\Advising\Ajax\advising_ajax@get_offering_per_search');
Route::get('/ajax/dean/advising/add_all_courses','Dean\Advising\Ajax\advising_ajax@add_all_courses');

//SRF
Route::get('/dean/srf','Dean\SRF\srf@index');
Route::get('/dean/srf/modify/{course_code}','Dean\SRF\srf@modify_srf');
Route::post('/dean/srf/set_srf','Dean\SRF\srf@set_srf');
//Print SRF
Route::get('/dean/srf/print_srf','Dean\SRF\srf@print_index');
Route::get('/dean/srf/print_srf_now/{program_code}/{level}/{period}/{curriculum_year}','Dean\SRF\srf@print_srf_now');
//Ajax SRF
Route::get('/ajax/dean/srf/get_list','Dean\SRF\Ajax\setup@get_list');
Route::get('/ajax/dean/srf/print_get_list','Dean\SRF\Ajax\setup@print_list');


//COLLEGE INSTRUCTOR - MAIN/////////////////////////////////////////////////////
Route::get('/college_instructor/grades/{schedule_id}', 'CollegeInstructor\Grades\GradesController@index');
Route::post('/college_instructor/grades/save_submit', 'CollegeInstructor\Grades\GradesController@save_submit');
Route::get('/college_instructor/print_list/{schedule_id}', 'CollegeInstructor\Grades\GradesController@print_list');
Route::get('/college_instructor/print_grade/{schedule_id}', 'CollegeInstructor\Grades\GradesController@print_grade');

//Ajax COLLEGE INSTRUCTOR///////////////////////////////////////////////////////
Route::get('/ajax/college_instructor/grades/change_midterm/{idno}', 'CollegeInstructor\Grades\Ajax\GradesAjaxController@change_midterm');
Route::get('/ajax/college_instructor/grades/change_finals/{idno}', 'CollegeInstructor\Grades\Ajax\GradesAjaxController@change_finals');
Route::get('/ajax/college_instructor/grades/change_grade_point/{idno}', 'CollegeInstructor\Grades\Ajax\GradesAjaxController@change_grade_point');

