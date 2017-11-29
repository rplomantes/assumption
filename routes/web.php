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
Auth::routes();
Route::get('/', 'HomeController@index')->name('home');

//Registrar College Curriculum Management
Route::get('/registrar_college/curriculum_management/curriculum', 'RegistrarCollege\CurriculumManagement\CurriculumController@index');
Route::get('/registrar_college/curriculum_management/view_curricula/{program_code}', 'RegistrarCollege\CurriculumManagement\CurriculumController@viewcurricula');
Route::get('/registrar_college/curriculum_management/list_curriculum/{program_code}/{curriculum_year}','RegistrarCollege\CurriculumManagement\CurriculumController@listcurriculum');

Route::get('/registrar_college/curriculum_management/course_offering', 'RegistrarCollege\CurriculumManagement\CourseOfferingController@index');
Route::get('/registrar_college/curriculum_management/view_offering/{program_code}', 'RegistrarCollege\CurriculumManagement\CourseOfferingController@viewofferings');
Route::get('/ajax/registrar_college/curriculum_management/view_offering/{program_code}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@listcurriculum');
Route::get('/ajax/registrar_college/curriculum_management/view_course_offered/{program_code}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@listcourse_offered');
Route::get('/ajax/registrar_college/curriculum_management/add_to_course_offered/{course_code}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@add_to_course_offered');
Route::get('/ajax/registrar_college/curriculum_management/add_all_to_course_offered/', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@add_all_courses');
Route::get('/ajax/registrar_college/curriculum_management/remove_course_offered/{id}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@remove_course');