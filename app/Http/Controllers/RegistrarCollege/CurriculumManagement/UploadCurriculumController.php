<?php

namespace App\Http\Controllers\RegistrarCollege\CurriculumManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Excel;
use Illuminate\Support\Facades\Input;

class UploadCurriculumController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            return view('reg_college.curriculum_management.upload_curriculum');
        }
    }
    
    function upload(Request $request) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $row = 9;
            $path = Input::file('import_file')->getRealPath();
//            Excel::selectSheets('curriculum')->load($path, function($reader) use ($row) {
//                $uploaded = array();
//                do {
//                    $course_code = $reader->getActiveSheet()->getCell('A' . $row)->getValue();
//                    $course_name = $reader->getActiveSheet()->getCell('B' . $row)->getValue();
//                    $lec = $reader->getActiveSheet()->getCell('G' . $row)->getValue();
//                    $lab = $reader->getActiveSheet()->getCell('H' . $row)->getValue();
//                    $hours = $reader->getActiveSheet()->getCell('I' . $row)->getValue();
//
//                    $uploaded[] = array('course_code' => $course_code, 'course_name' => $course_name, 'lec' => $lec, 'lab' => $lab, 'hours' => $hours);
//                    $row++;
//                } while (strlen($reader->getActiveSheet()->getCell('A' . $row)->getValue()) > 1);
//
//                session()->flash('courses', $uploaded);
//            });

            Excel::selectSheets('curriculum')->load($path, function($reader) {

                $program_code = $reader->getActiveSheet()->getCell('B1')->getValue();

                session()->flash('program_codes', $program_code);
            });

            Excel::selectSheets('curriculum')->load($path, function($reader) {

                $program_name = $reader->getActiveSheet()->getCell('B2')->getValue();

                session()->flash('program_name', $program_name);
            });
            
            Excel::selectSheets('curriculum')->load($path, function($reader) {

                $curriculum_year = $reader->getActiveSheet()->getCell('B3')->getValue();

                session()->flash('curriculum_year', $curriculum_year);
            });

            //$courses = session('courses');
            $program_codes = session('program_codes');
            $program_names = session('program_name');
            $curriculum_years = session('curriculum_year');

            return view('registrar_college.curriculum_management.upload', compact('program_codes', 'program_names', 'curriculum_years'));
//            return view('registrar.grades.upload_grade', compact('grades', 'course', 'prof', 'request'));
        }
    }
}
