<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Session;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
class TeacherManager extends Controller
{
    public function addteacher(Request $request)
    {
    	$allsubjects=DB::table('subjects')->get();
    	$allteachers=DB::table('users')->where('user_type', '=' , "user")->orderBy('id', 'DESC')->get();
    	return view('teachermanager.add_teacher', compact("allsubjects", "allteachers"));
    }
    public function allteachers(Request $request)
    {
    	$allteachers = DB::table('subjectteacher')
            ->join('users', 'subjectteacher.teacher_id', '=', 'users.id')
            ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
            ->orderBy('subjectteacher.subject_teacher_id', 'DESC')
            ->get();
    	return view('teachermanager.all_teachers', compact("allteachers"));
    }
    public function editteacher(Request $request)
    {
    	$subject_teacher_id=$request->subject_teacher_id;
    	$specific_teachers = DB::table('subjectteacher')
            ->join('users', 'subjectteacher.teacher_id', '=', 'users.id')
            ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
            ->where('subjectteacher.subject_teacher_id', '=', $subject_teacher_id)
            ->first();
    	// dd($specific_teachers);
        $allsubjects=DB::table('subjects')->get();
        $allteachers=DB::table('users')->where('user_type', '=' , "user")->orderBy('id', 'DESC')->get();
    	return view('teachermanager.edit_teacher', compact("specific_teachers", "allteachers", "allsubjects"));
    }
    public function assignteacher(Request $request)
    {
    	$allteachers=DB::table('users')->where('user_type', '=' , "teacher")->orderBy('id', 'DESC')->get();
    	$all_classroom = DB::table('classroom')->get();
    	return view('teachermanager.assigned_teacher', compact("allteachers", "all_classroom"));
    }
    public function allassignedteachers(Request $request)
    {
    	$all_assigned_teachers = DB::table('teacher_classroom')
    		->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
    		->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
            ->join('users', 'teacher_classroom.teacher_id', '=', 'users.id')
            ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
            ->orderBy('teacher_classroom.teacher_classroom_id', 'DESC')
            ->get();
        // dd($all_assigned_teachers);
    	return view('teachermanager.all_assigned_teacher', compact("all_assigned_teachers"));
    }
    public function add_teacher(Request $request)
    {
    	$this->validate($request,[
            'subject'=>'required',
            'teacher'=>'required'
        ]);
    	$data['subject_id']=$request->subject;
        $data['teacher_id']=$request->teacher;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        DB::table('subjectteacher')->insert($data);
        //Update user Table To set user_type To branch manager
        $data1['user_type']="teacher";
        DB::table('users')
            ->where('id', '=', $request->teacher)
            ->update($data1);
        Session::flash('key', 'Added Successfully');
        Session::flash('message', 'Teacher Added Successfully.'); 
        return redirect('/allteachers');
    }
    public function update_teacher(Request $request)
    {
    	$subject_teacher_id=$request->subject_teacher_id;
    	if($request->subject==null)
    	{
    		$sub=$request->already_subject_id;
    	}
    	else
    	{
    		$sub=$request->subject;
    	}
    	if($request->teacher==null)
    	{
    		$teacher=$request->already_teacher_id;
    	}
    	else
    	{
    		$teacher=$request->teacher;
    	}
    	$data['subject_id']=$sub;
        $data['teacher_id']=$teacher;
        $data['updated_at'] = date('Y-m-d H:i:s');
        DB::table('subjectteacher')
            ->where('subject_teacher_id', '=', $subject_teacher_id)
            ->update($data);
        $data1['user_type']="teacher";
        DB::table('users')
            ->where('id', '=', $teacher)
            ->update($data1);
        // Update user table back to user
        $data2['user_type']="user";
        if($teacher!=$request->already_teacher_id)
        {
        	DB::table('users')
                ->where('id', '=', $request->already_teacher_id)
                ->update($data2);
        }
        Session::flash('key', 'Updated Successfully');
        Session::flash('message', 'Teacher Updated Successfully.'); 
        return redirect('/allteachers');
    }
    public function assigned_teacher(Request $request)
    {
    	$allteachers=DB::table('teacher_classroom')
    		->where('teacher_id', '=' , $request->teacher)
    		->where('classroom_id', '=' , $request->classroom)
    		->first();
    	if($allteachers!=null)
    	{
    		Session::flash('key', 'Already Assigned');
	        Session::flash('message', 'Teacher Already Assigned.'); 
	        return redirect('/assignteacher');
    	}
    	$this->validate($request,[
            'teacher'=>'required',
            'classroom'=>'required'
        ]);
    	$data['teacher_id']=$request->teacher;
        $data['classroom_id']=$request->classroom;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        DB::table('teacher_classroom')->insert($data);
        Session::flash('key', 'Assigned Successfully');
        Session::flash('message', 'Teacher Assigned Successfully.'); 
        return redirect('/allassignedteachers');
    }
    public function edit_assigned_teacher(Request $request)
    {
    	$teacher_classroom_id=$request->teacher_classroom_id;
    	$specific_teacher_classroom=DB::table('teacher_classroom')
    		->join('users', 'teacher_classroom.teacher_id', '=', 'users.id')
            ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
    		->where('teacher_classroom_id', '=' , $teacher_classroom_id)
    		->first();
    	// dd($specific_teacher_classroom);
    	$allteachers=DB::table('users')->where('user_type', '=' , "teacher")->orderBy('id', 'DESC')->get();
    	$all_classroom = DB::table('classroom')->get();
    	return view('teachermanager.edit_assigned_teacher', compact("specific_teacher_classroom", "allteachers", "all_classroom"));
    }
    public function update_assigned_teacher(Request $request)
    {
    	
    	$teacher_classroom_id=$request->teacher_classroom_id;
    	if($request->teacher==null)
    	{
    		$teacher=$request->already_teacher_id;
    	}
    	else
    	{
    		$teacher=$request->teacher;
    	}
    	if($request->classroom==null)
    	{
    		$classroom=$request->already_classroom_id;
    	}
    	else
    	{
    		$classroom=$request->classroom;
    	}
        $data['teacher_id']=$teacher;
        $data['classroom_id']=$classroom;
        DB::table('teacher_classroom')
            ->where('teacher_classroom_id', '=', $teacher_classroom_id)
            ->update($data);
        Session::flash('key', 'Assigned Successfully');
        Session::flash('message', 'Teacher Assigned Successfully.'); 
        return redirect('/allassignedteachers');
    }
    public function supervisor_report(Request $request)
    {
        $current_user_id=Auth::user()->id;
        $allbranch = DB::table('classroom')
            ->where('supervisor', '=', $current_user_id)
            ->get();
        return view('teachermanager.supervisor_report', compact("allbranch"));
    }
    public function get_subjects(Request $request)
    {
        $class_id=$request->class_id;
        $allsubjects = DB::table('teacher_classroom')
            ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
            ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
            ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
            ->where('classroom.classroom_id', '=', $class_id)
            ->get();
        return $allsubjects;
    }
    public function get_student(Request $request)
    {
        $class_id=$request->class_id;
        $subject_id=$request->subject_id;
        if($subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }
        else
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->get();
        }
        return $allstudents;
    }
    public function get_report(Request $request)
    {
        $class_id=$request->class_id;

        $subject_id=$request->subject_id;
        $student_id=$request->student_id;
        $grade_type=$request->grade_type;

        Session::put('class_id', $request->class_id);
        Session::put('subject_id', $request->subject_id);
        Session::put('student_id', $request->student_id);
        Session::put('grade_type', $request->grade_type);
        if($subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }

        if($subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }
        if($subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('student_grades.student_id', '=', $student_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }


        if($subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }
        return view('teachermanager.save_report', compact("get_data"));
    }
    public function saveFileInDB(Request $request)
    {
        $class_id=Session::get('class_id');
        $subject_id=Session::get('subject_id');
        $student_id=Session::get('student_id');
        $grade_type=Session::get('grade_type');
        if($subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }

        if($subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }
        if($subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('student_grades.student_id', '=', $student_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }


        if($subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }
        $data['student_report_name']=$request->input('student_report_name');
        view()->share('teachermanager.pdfview',$get_data);
        $pdf = PDF::loadView('teachermanager.pdfview', compact('get_data'));
        $files=$pdf->download($data['student_report_name'].' '.'pdfview.pdf');
        
        $pdf = PDF::loadView('teachermanager.pdfview',compact('get_data')); // <--- load your view into theDOM wrapper;
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
        
        Session::forget('class_id');
        Session::forget('subject_id');
        Session::forget('student_id');
        Session::forget('grade_type');
        return redirect('supervisor_report');
    }
}

