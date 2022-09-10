<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Session;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
class BlockManager extends Controller
{
    public function allblock(Request $request)
    {
    	$vice_principle_id=Auth::user()->id;
    	$allblock = DB::table('block')
            ->join('school', 'block.school_id', '=', 'school.school_id')
   			->where('school.vice_principle', '=', $vice_principle_id)
            ->get();
    	return view('blockmanager.all_block', compact("allblock"));
    }
    public function editblock(Request $request)
    {
    	$id=$request->block_id;
    	$specific_block = DB::table('block')
            ->join('school', 'block.school_id', '=', 'school.school_id')
   			->where('block.block_id', '=', $id)
            ->first();
        if($specific_block->head_master)
        {
            $head_master_name=DB::table('users')
                ->where('id', '=', $specific_block->head_master)
                ->first();
        }
        else
        {
            $head_master_name="";
        }
    	// dd($head_master_name);
        $head_master=DB::table('users')
	    	->where('user_type', '=' , "user")
	    	->orderBy('id', 'DESC')
	    	->get();
	    // dd($head_master);
    	return view('blockmanager.edit_block', compact("specific_block", "head_master_name", "head_master"));
    }
    public function update_block(Request $request)
    {
    	$this->validate($request,[
            'headmaster'=>'required'
        ]);
    	$data['head_master']=$request->headmaster;
        $data['updated_at'] = date('Y-m-d H:i:s');
        DB::table('block')
            ->where('block_id', '=', $request->block_id)
            ->update($data);
        //Update user table To set headmaster
        $data1['user_type']="headmaster";
        DB::table('users')
            ->where('id', '=', $request->headmaster)
            ->update($data1);
        $data2['user_type']="user";
        if($request->headmaster!=$request->already_head_master)
        {
        	DB::table('users')
	            ->where('id', '=', $request->already_head_master)
	            ->update($data2);
        }
        Session::flash('key', 'Updated Successfully');
        Session::flash('message', 'Block Updated Successfully.'); 
        return redirect('/allblock');
    }
    public function vp_report(Request $request)
    {
        $current_user_id=Auth::user()->id;
        $allbranch = DB::table('school')
            ->where('vice_principle', '=', $current_user_id)
            ->orderBy('school.school_id', 'DESC')
            ->get();
        return view('blockmanager.vp_report', compact("allbranch"));
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
        $school_id=$request->school_id;
        if($block_id=="-420")
        {
            $allgrade = DB::table('grade')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->where('block.school_id','=', $school_id)
                ->get();
        }
        else
        {
            $allgrade = DB::table('grade')
                ->where('block_id', '=', $block_id)
                ->get();
        }
        return $allgrade;
    }
    public function get_class(Request $request)
    {
        $school_id=$request->school_id;
        $block_id=$request->block_id;
        $grade_id=$request->grade_id;

        if($block_id!="-420" && $grade_id!="-220")
        {
            $allclass = DB::table('classroom')
                ->where('grade_id', '=', $grade_id)
                ->get();
        }
        else if($block_id!="-420" && $grade_id=="-220")
        {
            $allclass = DB::table('classroom')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->where('block.block_id', '=', $block_id)
                ->where('block.school_id', '=', $school_id)
                ->get();
        }
        else if($block_id=="-420" && $grade_id!="-220")
        {
            $allclass = DB::table('classroom')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('block.school_id', '=', $school_id)
                ->get();
        }
        else if($block_id=="-420" && $grade_id=="-220")
        {
            $allclass = DB::table('classroom')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->where('block.school_id', '=', $school_id)
                ->get();
        }
        return $allclass;
    }
    public function get_subjects(Request $request)
    {
        $class_id=$request->class_id;

        $school_id=$request->school_id;

        $block_id=$request->block_id;
        $grade_id=$request->grade_id;

        if($block_id=="-420" && $grade_id=="-220" && $class_id=="-120")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->where('block.school_id', '=', $school_id)
                ->get();
        }

        if($block_id!="-420" && $grade_id=="-220" && $class_id=="-120")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->where('block.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id!="-220" && $class_id=="-120")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->where('block.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->get();
        }

        if($block_id=="-420" && $grade_id!="-220" && $class_id=="-120")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->where('block.school_id', '=', $school_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->get();
        }
        if($block_id=="-420" && $grade_id!="-220" && $class_id!="-120")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->where('block.school_id', '=', $school_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }

        if($block_id=="-420" && $grade_id=="-220" && $class_id!="-120")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->where('block.school_id', '=', $school_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id=="-220" && $class_id!="-120")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->where('block.school_id', '=', $school_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('block.block_id', '=', $block_id)
                ->get();
        }
        else if($block_id!="-420" && $grade_id!="-220" && $class_id!="-120")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('block.school_id', '=', $school_id)
                ->get();
        }
        return $allsubjects;
    }
    public function get_student(Request $request)
    {
        $subject_id=$request->subject_id;
        $class_id=$request->class_id;

        $school_id=$request->school_id;

        $block_id=$request->block_id;
        $grade_id=$request->grade_id;

        if($block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('school.school_id', '=', $school_id)
                ->get();
        }



        if($block_id!="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('block.block_id', '=', $block_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('grade.grade_id', '=', $grade_id)
                ->where('block.block_id', '=', $block_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('grade.grade_id', '=', $grade_id)
                ->where('block.block_id', '=', $block_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }


        if($block_id=="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('grade.grade_id', '=', $grade_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id=="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id=="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('grade.grade_id', '=', $grade_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }

        if($block_id=="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('classroom.classroom_id', '=', $class_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id=="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('block.block_id', '=', $block_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }



        if($block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('block.block_id', '=', $block_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('grade.grade_id', '=', $grade_id)
                ->where('block.block_id', '=', $block_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }

        //one more
        if($block_id!="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('block.block_id', '=', $block_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }




        if($block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('grade.grade_id', '=', $grade_id)
                ->where('block.block_id', '=', $block_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        return $allstudents;
    }
    public function get_report(Request $request)
    {
        $school_id=$request->school_id;

        $block_id=$request->block_id;
        $grade_id=$request->grade_id;
        $class_id=$request->class_id;
        $subject_id=$request->subject_id;
        $student_id=$request->student_id;
        $grade_type=$request->grade_type;
        
        Session::put('school_id', $request->school_id);
        Session::put('block_id', $request->block_id);
        Session::put('grade_id', $request->grade_id);
        Session::put('class_id', $request->class_id);
        Session::put('subject_id', $request->subject_id);
        Session::put('student_id', $request->student_id);
        Session::put('grade_type', $request->grade_type);

        // dd("branch_id=".$branch_id."   school_id=".$school_id."   block_id=".$block_id
        //     ."   grade_id=".$grade_id."    class_id=".$class_id."    subject_id".$subject_id.
        //     "    student_id=".$student_id."   grade_type=".$grade_type);

        if($block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->get();
        }

        //1

        if($block_id!="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->get();
        }


        //2


        if($block_id=="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id=="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id=="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id=="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('student_grades.student_id', '=', $student_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }


        //3



        if($block_id=="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id=="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id=="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('student_grades.student_id', '=', $student_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }



        //4



        if($block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('student_grades.student_id', '=', $student_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }


        //5




        if($block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('student_grades.student_id', '=', $student_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }

        //one More
        if($block_id!="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }



        if($block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }

        return view('blockmanager.save_report', compact("get_data"));
    }
    public function saveFileInDB(Request $request)
    {
        
        $school_id=Session::get('school_id');
        $block_id=Session::get('block_id');
        $grade_id=Session::get('grade_id');
        $class_id=Session::get('class_id');
        $subject_id=Session::get('subject_id');
        $student_id=Session::get('student_id');
        $grade_type=Session::get('grade_type');
        // dd($block_id);
        if($block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->get();
        }

        //1

        if($block_id!="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->get();
        }


        //2


        if($block_id=="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id=="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id=="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id=="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('student_grades.student_id', '=', $student_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }


        //3



        if($block_id=="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id=="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id=="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('student_grades.student_id', '=', $student_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }



        //4



        if($block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('student_grades.student_id', '=', $student_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }


        //5




        if($block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('student_grades.student_id', '=', $student_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }
        if($block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }

        //one More
        if($block_id!="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }



        if($block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }
        $data['student_report_name']=$request->input('student_report_name');
        // view()->share('blockmanager.pdfview',$get_data);
        // $pdf = PDF::loadView('blockmanager.pdfview', compact('get_data'));
        // $files=$pdf->download($data['student_report_name'].' '.'pdfview.pdf');
        
        $pdf = PDF::loadView('blockmanager.pdfview',compact('get_data')); // <--- load your view into theDOM wrapper;
        $path = public_path('issues/'); // <--- folder to store the pdf documents into the server;
        $fileName =  $data['student_report_name'].'.'. 'pdf' ; // <--giving the random filename,
        $pdf->save($path . '/' . $fileName);
        $generated_pdf_link = url('pdf_docs/'.$fileName);
        $report=DB::table('studentreports')->insert([
            ['report_name' => $fileName,'current_user_id' =>Auth::user()->id,'created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],           
        ]);
        Session::flash('key', 'Save Successfully');
        Session::flash('message', 'File Save Successfully.'); 
        // dd('done');
        
        Session::forget('school_id');
        Session::forget('block_id');
        Session::forget('grade_id');
        Session::forget('class_id');
        Session::forget('subject_id');
        Session::forget('student_id');
        Session::forget('grade_type');
        return redirect('vp_report');
    }
}
