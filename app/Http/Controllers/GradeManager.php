<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Session;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
class GradeManager extends Controller
{
    public function allgrade(Request $request)
    {
    	$head_master_id=Auth::user()->id;
    	// dd($head_master_id);
    	$allgrade = DB::table('grade')
            ->join('block', 'grade.block_id', '=', 'block.block_id')
   			->where('block.head_master', '=', $head_master_id)
            ->get();
        // dd($allgrade);
    	return view('grademanager.all_grade', compact("allgrade"));
    }
    public function editgrade(Request $request)
    {
    	$id=$request->grade_id;
    	$specific_grade = DB::table('grade')
            ->join('block', 'grade.block_id', '=', 'block.block_id')
   			->where('grade.grade_id', '=', $id)
            ->first();
        if($specific_grade->leader)
        {
            $leader_name=DB::table('users')
            ->where('id', '=', $specific_grade->leader)
            ->first();
        }
        else
        {
            $leader_name="";
        }
        $leader=DB::table('users')
	    	->where('user_type', '=' , "user")
	    	->orderBy('id', 'DESC')
	    	->get();
    	return view('grademanager.edit_grade', compact("specific_grade", "leader_name" , "leader"));
    }
    public function update_grade(Request $request)
    {
        // dd($request);
        $this->validate($request,[
            'leader'=>'required'
        ]);
    	$data['leader']=$request->leader;
        $data['updated_at'] = date('Y-m-d H:i:s');
        DB::table('grade')
            ->where('grade_id', '=', $request->grade_id)
            ->update($data);
        //Update user table To set leader
        $data1['user_type']="leader";
        DB::table('users')
            ->where('id', '=', $request->leader)
            ->update($data1);
        // Update the leader bake To User
        $data2['user_type']="user";
        if($request->leader!=$request->already_leader)
        {
            DB::table('users')
                ->where('id', '=', $request->already_leader)
                ->update($data2);
        }
        Session::flash('key', 'Updated Successfully');
        Session::flash('message', 'Grade Updated Successfully.'); 
        return redirect('/allgrade');
    }
    public function hm_report(Request $request)
    {
        $current_user_id=Auth::user()->id;
        $allbranch = DB::table('block')
            ->where('head_master', '=', $current_user_id)
            ->get();
        return view('grademanager.hm_report', compact("allbranch"));
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
        $block_id=$request->block_id;
        $grade_id=$request->grade_id;
        if($grade_id=="-220")
        {
            $allclass = DB::table('classroom')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->where('grade.block_id', '=', $block_id)
                ->get();
        }
        else
        {
            $allclass = DB::table('classroom')
                ->where('grade_id', '=', $grade_id)
                ->get();
        }
        return $allclass;
    }
    public function get_subjects(Request $request)
    {
        $block_id=$request->block_id;

        $class_id=$request->class_id;
        $grade_id=$request->grade_id;
        if($class_id=="-120" && $grade_id=="-220")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->where('grade.block_id', '=', $block_id)
                ->get();
        }

        if($class_id!="-120" && $grade_id=="-220")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('grade.block_id', '=', $block_id)
                ->get();
        }

        if($class_id=="-120" && $grade_id!="-220")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('grade.block_id', '=', $block_id)
                ->get();
        }

        if($class_id!="-120" && $grade_id!="-220")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('grade.block_id', '=', $block_id)
                ->get();
        }
        return $allsubjects;
    }
    public function get_student(Request $request)
    {
        $subject_id=$request->subject_id;
        $class_id=$request->class_id;

        $block_id=$request->block_id;

        $grade_id=$request->grade_id;

        if($class_id=="-120" && $grade_id=="-220" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('grade.block_id', '=', $block_id)
                ->get();
        }

        // 1

        if($class_id!="-120" && $grade_id=="-220" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('grade.block_id', '=', $block_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }
        if($class_id!="-120" && $grade_id!="-220" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('grade.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }

        //2
        if($class_id=="-120" && $grade_id!="-220" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('grade.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->get();
        }
        if($class_id=="-120" && $grade_id!="-220" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('grade.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->get();
        }


        // 3
        if($class_id=="-120" && $grade_id=="-220" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('grade.block_id', '=', $block_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->get();
        }
        if($class_id!="-120" && $grade_id=="-220" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('grade.block_id', '=', $block_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }


        if($class_id!="-120" && $grade_id!="-220" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('grade.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }
        return $allstudents;
    }

    public function get_report(Request $request)
    {
        $block_id=$request->block_id;

        $grade_id=$request->grade_id;
        $class_id=$request->class_id;
        $subject_id=$request->subject_id;
        $student_id=$request->student_id;
        $grade_type=$request->grade_type;
        
        Session::put('block_id', $request->block_id);
        Session::put('grade_id', $request->grade_id);
        Session::put('class_id', $request->class_id);
        Session::put('subject_id', $request->subject_id);
        Session::put('student_id', $request->student_id);
        Session::put('grade_type', $request->grade_type);

        if($grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->get();
        }


        //1
        if($grade_id!="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->get();
        }
        if($grade_id!="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }
        if($grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->get();
        }



        //2
        if($grade_id=="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }
        if($grade_id=="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->get();
        }
        if($grade_id=="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }



        //3
        if($grade_id=="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->get();
        }
        if($grade_id=="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }
        if($grade_id!="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }



        //4
        if($grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }
        if($grade_id!="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }
        if($grade_id!="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }



        if($grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }
        return view('grademanager.save_report', compact("get_data"));
    }
    public function saveFileInDB(Request $request)
    {
        $block_id=Session::get('block_id');
        $grade_id=Session::get('grade_id');
        $class_id=Session::get('class_id');
        $subject_id=Session::get('subject_id');
        $student_id=Session::get('student_id');
        $grade_type=Session::get('grade_type');
        if($grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->get();
        }


        //1
        if($grade_id!="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->get();
        }
        if($grade_id!="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }
        if($grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->get();
        }



        //2
        if($grade_id=="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }
        if($grade_id=="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->get();
        }
        if($grade_id=="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }



        //3
        if($grade_id=="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->get();
        }
        if($grade_id=="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }
        if($grade_id!="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }



        //4
        if($grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }
        if($grade_id!="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }
        if($grade_id!="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }



        if($grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }
        $data['student_report_name']=$request->input('student_report_name');
        view()->share('grademanager.pdfview',$get_data);
        $pdf = PDF::loadView('grademanager.pdfview', compact('get_data'));
        $files=$pdf->download($data['student_report_name'].' '.'pdfview.pdf');
        
        $pdf = PDF::loadView('grademanager.pdfview',compact('get_data')); // <--- load your view into theDOM wrapper;
        $path = public_path('issues/'); // <--- folder to store the pdf documents into the server;
        $fileName =  $data['student_report_name'].'.'. 'pdf' ; // <--giving the random filename,
        $pdf->save($path . '/' . $fileName);
        $generated_pdf_link = url('pdf_docs/'.$fileName);
        $report=DB::table('studentreports')->insert([
            ['report_name' => $fileName,'current_user_id' =>Auth::user()->id,'created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],           
        ]);
        Session::flash('key', 'Save Successfully');
        Session::flash('message', 'File Save Successfully.'); 
    //destroy session
        Session::forget('branch_id');
        Session::forget('school_id');
        Session::forget('block_id');
        Session::forget('grade_id');
        Session::forget('class_id');
        Session::forget('subject_id');
        Session::forget('student_id');
        Session::forget('grade_type');
        return redirect('hm_report');
    }
}
