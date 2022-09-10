<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Session;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Redirect;
class ClassManager extends Controller
{
    public function addclassroom(Request $request)
    {
    	$leader_id=Auth::user()->id;
    	$leader_grade=DB::table('grade')
    		->where('leader', '=' , $leader_id)->first();
    	$class_supervisor=DB::table('users')->where('user_type', '=' , "user")->orderBy('id', 'DESC')->get();
        $alluser=DB::table('users')->where('user_type', '=' , "user")->orderBy('id', 'DESC')->get();
    	return view('classmanager.add_classroom', compact("class_supervisor", "leader_grade", "alluser"));
    }
    public function get_remaning_students(Request $request)
    {
        $classsupervisor_id=$request->classsupervisor_id;
        $all_students=DB::table('users')
            ->where('user_type', '=' , "user")
            ->where('id', '!=' , $classsupervisor_id)
            ->orderBy('id', 'DESC')
            ->get();
        return $all_students;
    }
    public function allclassroom(Request $request)
    {
        $leader_id=Auth::user()->id;
        $allclassroom = DB::table('classroom')
            ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
            ->where('grade.leader', '=', $leader_id)
            ->get();
        // dd($allclassroom);
    	return view('classmanager.all_classroom', compact("allclassroom"));
    }
    public function editclassroom(Request $request)
    {
        $id=$request->classroom_id;
        $specific_classroom = DB::table('classroom')
            ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
            ->join('users', 'classroom.supervisor', '=', 'users.id')
            ->where('classroom.classroom_id', '=', $id)
            ->first();
        $alluser=DB::table('users')->where('user_type', '=' , "user")->orderBy('id', 'DESC')->get();
        $student=DB::table('users')->where('user_type', '=' , "user")->orderBy('id', 'DESC')->get();
        $student_list = DB::table('students')
            ->join('users', 'students.student_detail_id', '=', 'users.id')
            ->where('students.student_classroom_id', '=', $id)
            ->orderBy('students.student_id', 'DESC')
            ->get();
        // dd($student_list);
    	return view('classmanager.edit_classroom', compact("specific_classroom", "alluser", "student", "student_list"));
    }
    //Post Function
    public function add_classroom(Request $request)
    {
        // dd($request);
        $this->validate($request,[
            'classsupervisor'=>'required'
        ]);
        $data['grade_id']=$request->grade_id;
        $data['classroom_name']=$request->classroom_name;
        $data['supervisor']=$request->classsupervisor;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $classroom_id=DB::table('classroom')->insertGetId($data);

        $data1['user_type']="supervisor";
        DB::table('users')
            ->where('id', '=', $request->classsupervisor)
            ->update($data1);

        $users=$request->users;
        foreach ($users as $user1)
        {
            $data2['student_classroom_id']=$classroom_id;
            $data2['student_detail_id']=$user1;
            $data2['created_at'] = date('Y-m-d H:i:s');
            $data2['updated_at'] = date('Y-m-d H:i:s');
            DB::table('students')->insert($data2);
            $data3['user_type']="student";
            DB::table('users')
            ->where('id', '=', $user1)
            ->update($data3);
        }
        Session::flash('key', 'Added Successfully');
        Session::flash('message', 'Classroom Added Successfully.'); 
        return redirect('/allclassroom');
    }
    public function update_classroom(Request $request)
    {
        $classroom_id=$request->classroom_id;
        if($request->classsupervisor==null)
        {
            $cs=$request->already_supervisor;
        }
        else
        {
            $cs=$request->classsupervisor;
        }
        $data['classroom_name']=$request->classroom_name;
        $data['supervisor']=$cs;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $classroom_id=DB::table('classroom')
            ->where('classroom_id', '=', $classroom_id)
            ->update($data);

        //Update user Table To set user_type To branch manager
        $data1['user_type']="supervisor";
        DB::table('users')
            ->where('id', '=', $cs)
            ->update($data1);
        //
        $data2['user_type']="user";
        if($cs!=$request->already_supervisor)
        {
            DB::table('users')
                ->where('id', '=', $request->already_supervisor)
                ->update($data2);
        }
        Session::flash('key', 'Updated Successfully');
        Session::flash('message', 'Classroom Updated Successfully.'); 
        return redirect('/allclassroom');
    }
    public function add_student_to_class(Request $request)
    {
        $classroom_id=$request->classroom_id;
        $users=$request->users;
        foreach ($users as $user1)
        {
            $data2['student_classroom_id']=$classroom_id;
            $data2['student_detail_id']=$user1;
            $data2['created_at'] = date('Y-m-d H:i:s');
            $data2['updated_at'] = date('Y-m-d H:i:s');
            DB::table('students')->insert($data2);
            $data3['user_type']="student";
            DB::table('users')
            ->where('id', '=', $user1)
            ->update($data3);
        }
        Session::flash('key', 'Added Successfully');
        Session::flash('message', 'Students Added Successfully.'); 
        return redirect('/allclassroom');
    }
    public function delete_student(Request $request)
    {
        $id=$request->id;
        DB::table('students')->where('student_detail_id', '=', $id)->delete();
        $data['user_type']="user";
            DB::table('users')
            ->where('id', '=', $id)
            ->update($data);
        Session::flash('key', 'Deleted Successfully');
        Session::flash('message', 'Students Deleted Successfully.'); 
        return redirect('/allclassroom');
    }
    public function l_report(Request $request)
    {
        $current_user_id=Auth::user()->id;
        $allbranch = DB::table('grade')
            ->where('leader', '=', $current_user_id)
            ->get();
        return view('classmanager.l_report', compact("allbranch"));
    }
    public function get_class(Request $request)
    {
        $grade_id=$request->grade_id;
        $allclassroom = DB::table('classroom')
            ->where('grade_id', '=', $grade_id)
            ->get();
        return $allclassroom;
    }
    public function get_subjects(Request $request)
    {
        $class_id=$request->class_id;
        $grade_id=$request->grade_id;
        if($class_id=="-120")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->where('classroom.grade_id', '=', $grade_id)
                ->get();
        }
        else
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->where('classroom.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }
        return $allsubjects;
    }
    public function get_student(Request $request)
    {
        $subject_id=$request->subject_id;
        $class_id=$request->class_id;

        $grade_id=$request->grade_id;

        if($class_id=="-120" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('classroom.grade_id', '=', $grade_id)
                ->get();
        }
        if($class_id!="-120" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('classroom.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }
        if($class_id=="-120" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('classroom.grade_id', '=', $grade_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->get();
        }
        if($class_id!="-120" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('classroom.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->get();
        }
        return $allstudents;
    }
    public function get_report(Request $request)
    {
        $grade_id=$request->grade_id;

        $class_id=$request->class_id;
        $subject_id=$request->subject_id;
        $student_id=$request->student_id;
        $grade_type=$request->grade_type;
        Session::put('grade_id', $request->grade_id);
        Session::put('class_id', $request->class_id);
        Session::put('subject_id', $request->subject_id);
        Session::put('student_id', $request->student_id);
        Session::put('grade_type', $request->grade_type);
        if($class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->get();
        }

        //1
        if($class_id!="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }
        if($class_id!="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->get();
        }

        // 2
        if($class_id=="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->get();
        }
        if($class_id=="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }

        if($class_id=="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }
        if($class_id!="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }




        if($class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }
        return view('classmanager.save_report', compact("get_data"));
    }
    public function saveFileInDB(Request $request)
    {
        $grade_id=Session::get('grade_id');
        $class_id=Session::get('class_id');
        $subject_id=Session::get('subject_id');
        $student_id=Session::get('student_id');
        $grade_type=Session::get('grade_type');
        if($class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->get();
        }

        //1
        if($class_id!="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }
        if($class_id!="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->get();
        }

        // 2
        if($class_id=="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->get();
        }
        if($class_id=="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }

        if($class_id=="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }
        if($class_id!="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }




        if($class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->get();
        }
        $data['student_report_name']=$request->input('student_report_name');
        view()->share('classmanager.pdfview',$get_data);
        $pdf = PDF::loadView('classmanager.pdfview', compact('get_data'));
        $files=$pdf->download($data['student_report_name'].' '.'pdfview.pdf');
        
        $pdf = PDF::loadView('classmanager.pdfview',compact('get_data')); // <--- load your view into theDOM wrapper;
        $path = public_path('issues/'); // <--- folder to store the pdf documents into the server;
        $fileName =  $data['student_report_name'].'.'. 'pdf' ; // <--giving the random filename,
        $pdf->save($path . '/' . $fileName);
        $generated_pdf_link = url('pdf_docs/'.$fileName);
        $report=DB::table('studentreports')->insert([
            ['report_name' => $fileName,'current_user_id' =>Auth::user()->id,'created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],           
        ]);
        Session::flash('key', 'Save Successfully');
        Session::flash('message', 'File Save Successfully.'); 
        Session::forget('grade_id');
        Session::forget('class_id');
        Session::forget('subject_id');
        Session::forget('student_id');
        Session::forget('grade_type');
        return redirect('l_report');
    }
}
