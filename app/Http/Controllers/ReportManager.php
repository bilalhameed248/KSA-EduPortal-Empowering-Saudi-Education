<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Session;
class ReportManager extends Controller
{
    public function studentreport(Request $request)
    {
    	$allbranch = DB::table('branch')
            ->orderBy('branch.branch_id', 'DESC')
            ->get();
    	return view('studentreports.student_reports', compact("allbranch"));
    }
    public function get_report(Request $request)
    {
        $branch_id=$request->branch_id;
        $school_id=$request->school_id;
        $block_id=$request->block_id;
        $grade_id=$request->grade_id;
        $class_id=$request->class_id;
        $subject_id=$request->subject_id;
        $student_id=$request->student_id;
        $grade_type=$request->grade_type;

        $get_data = DB::table('student_grades')
            ->join('subjectteacher','student_grades.subject_teacher_id','=','subjectteacher.subject_teacher_id')
            ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
            ->join('students', 'student_grades.student_id', '=', 'students.student_id')
            ->join('users', 'students.student_detail_id', '=', 'users.id')
            ->where('student_grades.student_id', '=', $student_id)
            ->where('subjects.subject_id', '=', $subject_id)
            ->where('subjectteacher.subject_id', '=', $subject_id)
            ->first();
        // dd($get_data);
        return view('studentreports.save_report', compact("get_data"));
    }
    public function get_schools(Request $request)
    {
    	$branch_id=$request->branch_id;
    	$allschool = DB::table('school')
            ->where('branch_id', '=', $branch_id)
            ->get();
    	return $allschool;
    }
    public function get_block(Request $request)
    {
    	$school_id=$request->school_id;
    	$allblock = DB::table('block')
            ->where('school_id', '=', $school_id)
            ->get();
    	return $allblock;
    }
    public function get_grade(Request $request)
    {
    	$block_id=$request->block_id;
    	$allgrade = DB::table('grade')
            ->where('block_id', '=', $block_id)
            ->get();
    	return $allgrade;
    }
    public function get_class(Request $request)
    {
    	$grade_id=$request->grade_id;
    	$allclass = DB::table('classroom')
            ->where('grade_id', '=', $grade_id)
            ->get();
    	return $allclass;
    }
    public function get_subjects(Request $request)
    {
    	$class_id=$request->class_id;
    	$allsubjects = DB::table('teacher_classroom')
    		->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
    		->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
            ->where('teacher_classroom.classroom_id', '=', $class_id)
            ->get();
    	return $allsubjects;
    }
    public function get_student(Request $request)
    {
    	$subject_id=$request->subject_id;
    	$allstudents = DB::table('subjectteacher')
    		->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
    		->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
    		->join('users', 'students.student_detail_id', '=', 'users.id')
            ->where('subjectteacher.subject_id', '=', $subject_id)
            ->get();
    	return $allstudents;
    }
}
