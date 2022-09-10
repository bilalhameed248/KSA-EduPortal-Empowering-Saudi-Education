<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Auth;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Redirect;
class StudentManager extends Controller
{
    public function addgrade(Request $require)
    {
    	$current_user=Auth::user()->id;
    	$mystudents=null;
    	$classroom_and_sub = DB::table('teacher_classroom')
            ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
            ->join('classroom', 'classroom.classroom_id', '=', 'teacher_classroom.classroom_id')
            ->join('subjects', 'subjects.subject_id', '=', 'subjectteacher.subject_id')
            ->where('teacher_classroom.teacher_id', '=', $current_user)
            ->get();

    	return view('studentmanager.add_grade', compact("classroom_and_sub", "mystudents"));
    }
    public function filterdata(Request $request)
    {
    	$current_user=Auth::user()->id;
    	$classroom=$request->classroom;
    	$subject=$request->subject;
        // dd($classroom." ".$subject. " ".$current_user);
    	$classroom_and_sub = DB::table('teacher_classroom')
            ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
            ->join('classroom', 'classroom.classroom_id', '=', 'teacher_classroom.classroom_id')
            ->join('subjects', 'subjects.subject_id', '=', 'subjectteacher.subject_id')
            ->where('teacher_classroom.teacher_id', '=', $current_user)
            ->get();

    	$mystudents = DB::table('teacher_classroom')
            ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
            ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
            ->join('student_grades','students.student_id','=','student_grades.student_id')
            ->join('users', 'students.student_detail_id', '=', 'users.id')
            ->where('teacher_classroom.teacher_id', '=', $current_user)
            ->where('teacher_classroom.classroom_id', '=', $classroom)
            ->where('subjectteacher.subject_id', '=', $subject)
            ->get();
        if ($mystudents->isEmpty())
        { 
            $mystudents = DB::table('teacher_classroom')
            ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
            ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
            ->join('users', 'students.student_detail_id', '=', 'users.id')
            ->where('teacher_classroom.teacher_id', '=', $current_user)
            ->where('teacher_classroom.classroom_id', '=', $classroom)
            ->where('subjectteacher.subject_id', '=', $subject)
            ->get();
        }
        // dd($mystudents);
        return view('studentmanager.add_grade', compact("classroom_and_sub","mystudents"));
    }
    public function update_marks(Request $request)
    {
        // dd($request);
        $students=$request->student_id;
        $subject_teacher_id=$request->subject_teacher_id;


        $participation1=$request->participation1;
        $midterm=$request->midterm;
        $finalterm=$request->finalterm;


        $i=0;
        foreach ($students as $student1) 
        {
            $data['student_id']=$student1;

            $data['student_participation']=$participation1[$i];
            $data['student_midterm']=$midterm[$i];
            $data['student_final']=$finalterm[$i];

            $sub_total=$participation1[$i]+$midterm[$i]+$finalterm[$i];

            if($sub_total>=0 && $sub_total<=50)
            {
                $data['grade']="Low";
            }
            else if($sub_total>=51 && $sub_total<=80)
            {
                $data['grade']="Medium";
            }
            else if($sub_total>=81 && $sub_total<=100)
            {
                $data['grade']="High";
            }

            $data['student_term']=$sub_total;
            $data['subject_teacher_id']=$subject_teacher_id[$i];

            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');


            $Specific_std_rec = DB::table('student_grades')
                ->where('student_id', '=', $student1)
                ->where('subject_teacher_id', '=', $subject_teacher_id)
                ->first();

            if($Specific_std_rec !=null && $Specific_std_rec->student_participation!=null&&$Specific_std_rec->student_midterm!=null&&$Specific_std_rec->student_final!=null)
            {
                $i++;
                continue;
            }
            if($Specific_std_rec !=null && ($Specific_std_rec->student_participation!=null||$Specific_std_rec->student_midterm!=null||$Specific_std_rec->student_final!=null))
            {
                $i++;
                continue;
            }
            else if($Specific_std_rec !=null && $Specific_std_rec->student_participation==null&&$Specific_std_rec->student_midterm==null&&$Specific_std_rec->student_final==null)
            {
                DB::table('student_grades')
                    ->where('student_id', '=', $student1)
                    ->update($data);
                    $i++;
                continue;
            }
            
            DB::table('student_grades')->insert($data);
            $i++;
        }
        Session::flash('key', 'Added Successfully');
        Session::flash('message', 'Record Save Successfully.'); 
        return redirect('/addgrade');
    }
    public function editstdgrade(Request $request)
    {
        $student_id=$request->student_id;
        $subject_teacher_id=$request->subject_teacher_id;
        $Specific_std_rec = DB::table('student_grades')
            ->where('student_id', '=', $student_id)
            ->where('subject_teacher_id', '=', $subject_teacher_id)
            ->first();
        if($Specific_std_rec==null)
        {
            Session::flash('error_key', 'No Record Found');
            Session::flash('error_message', 'No Record Found To Edit...');
            return redirect('/addgrade');
        }
        return view('studentmanager.edit_grade', compact("Specific_std_rec"));
    }
    public function update_std_marks(Request $request)
    {
        $student_grades_id=$request->student_grades_id;
        $data['student_participation']=$request->participationmarks;
        $data['student_midterm']=$request->midtermmarks;
        $data['student_final']=$request->finaltermmarks;
        $sub_total=$data['student_participation']+$data['student_midterm']+$data['student_final'];

        if($sub_total>=0 && $sub_total<=50)
        {
            $data['grade']="Low";
        }
        else if($sub_total>=51 && $sub_total<=80)
        {
            $data['grade']="Medium";
        }
        else if($sub_total>=81 && $sub_total<=100)
        {
            $data['grade']="High";
        }

        $data['student_term']=$sub_total;
        DB::table('student_grades')
            ->where('student_grades_id', '=', $student_grades_id)
            ->update($data);
        Session::flash('key', 'Updated Successfully');
        Session::flash('message', 'Record Updated Successfully.'); 
        return redirect('/addgrade');
    }
    public function t_report(Request $request)
    {
        $current_user_id=Auth::user()->id;
        $allbranch = DB::table('teacher_classroom')
            ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
            ->where('teacher_classroom.teacher_id', '=', $current_user_id)
            ->get();
        // dd($allbranch);
        return view('studentmanager.t_report', compact("allbranch"));
    }
    public function get_subjects(Request $request)
    {
        $current_user_id=Auth::user()->id;
        $class_id=$request->class_id;
        $allsubjects = DB::table('teacher_classroom')
            ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
            ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
            ->where('teacher_classroom.classroom_id', '=', $class_id)
            ->where('teacher_classroom.teacher_id', '=', $current_user_id)
            ->get();
        return $allsubjects;
    }
    public function get_student(Request $request)
    {
        $current_user_id=Auth::user()->id;
        $class_id=$request->class_id;
        $subject_id=$request->subject_id;
        if($subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('teacher_classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.teacher_id', '=', $current_user_id)
                ->where('teacher_classroom.teacher_id', '=', $current_user_id)
                ->get();
        }
        else
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('teacher_classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.teacher_id', '=', $current_user_id)
                ->where('teacher_classroom.teacher_id', '=', $current_user_id)
                ->get();
        }
        return $allstudents;
    }
    public function get_report(Request $request)
    {
        // dd($request);
        $current_user_id=Auth::user()->id;
        $class_id=$request->class_id;

        $subject_id=$request->subject_id;
        $student_id=$request->student_id;
        $grade_type=$request->grade_type;
        Session::put('current_user_id', $request->subject_id);
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
                ->where('subjectteacher.teacher_id', '=', $current_user_id)
                ->get();
        }

        else if($subject_id=="-20" && $student_id!="-15")
        {

            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('student_grades.student_id', '=', $student_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.teacher_id', '=', $current_user_id)
                ->get();
        }

        else if($subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.teacher_id', '=', $current_user_id)
                ->get();
        }

        else if($subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('subjectteacher.teacher_id', '=', $current_user_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }
        return view('studentmanager.save_report', compact("get_data"));
    }
    public function saveFileInDB(Request $request)
    {
        $current_user_id=Session::get('current_user_id');
        $subject_id=Session::get('subject_id');
        $student_id=Session::get('student_id');
        $class_id=Session::get('class_id');
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
                ->where('subjectteacher.teacher_id', '=', $current_user_id)
                ->get();
        }

        else if($subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('student_grades.student_id', '=', $student_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.teacher_id', '=', $current_user_id)
                ->get();
        }

        else if($subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.teacher_id', '=', $current_user_id)
                ->get();
        }

        else if($subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('subjectteacher.teacher_id', '=', $current_user_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->get();
        }
        $data['student_report_name']=$request->input('student_report_name');
        view()->share('studentmanager.pdfview',$get_data);
        $pdf = PDF::loadView('studentmanager.pdfview', compact('get_data'));
        $files=$pdf->download($data['student_report_name'].' '.'pdfview.pdf');
        
        $pdf = PDF::loadView('studentmanager.pdfview',compact('get_data')); // <--- load your view into theDOM wrapper;
        $path = public_path('issues/'); // <--- folder to store the pdf documents into the server;
        $fileName =  $data['student_report_name'].'.'. 'pdf' ; // <--giving the random filename,
        $pdf->save($path . '/' . $fileName);
        $generated_pdf_link = url('pdf_docs/'.$fileName);
        
        $report=DB::table('studentreports')->insert([
            ['report_name' => $fileName,'current_user_id' =>Auth::user()->id,'created_at'=>Carbon::now()->toDateTimeString(),'updated_at'=>Carbon::now()->toDateTimeString()],           
        ]);
        Session::flash('key', 'Save Successfully');
        Session::flash('message', 'File Save Successfully.'); 
        
        Session::forget('subject_id');
        Session::forget('student_id');
        Session::forget('grade_type');
        return redirect('t_report');
    }
}
