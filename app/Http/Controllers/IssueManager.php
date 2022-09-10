<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use Session;
use Notification;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use App\Notifications\NewNotification;
use DB;
class IssueManager extends Controller
{
    public function addissue(Request $request)
    {
        $my_save_reports=DB::table('studentreports')
            ->where('current_user_id','=',Auth::user()->id)
            ->get();
        // dd($my_save_reports);
    	return view('issuemanager.add_issue', compact("my_save_reports"));
    }
    public function allissue(Request $request)
    {
    	$myissues=DB::table('issues')
    		->join('users', 'issues.issue_creator', '=', 'users.id')
    		->where('issue_related', '=', Auth::user()->id)
            ->orWhere('issue_creator', '=', Auth::user()->id)
    		->orderBy('issue_id', 'DESC')
    		->get();
    	return view('issuemanager.all_issue', compact("myissues"));
    }
    public function add_issue(Request $request)
    {
        // dd($request);
        $student_id=$request->student_id;
        $classroom_id=$request->classroom_id;
        $user_id=$request->user_id;
        $report_id=$request->report_id;
    	$currentuseremail=Auth::user()->email;


        //Data
        $data['issue_text']=$request->issue_desc;
    	$data['issue_type']=$request->issueType;
    	$data['issue_date']=date('Y-m-d');
    	$data['issue_creator']=Auth::user()->id;
    	$data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['issue_status'] = "Pending";
        $data['student_report_id'] = $report_id;
        $file=$request->file('issuefile');
        $new_name = $currentuseremail . '.' . $file->getClientOriginalExtension();
        $data['issue_file']=$new_name;
        $file->move(public_path('issues'), $new_name);

        if($user_id!=null && $student_id==null)
        {
            $data['issue_related']=$user_id;
            DB::table('issues')->insert($data);
            // notification part
            $letter=collect([
                'title'=> 'New Notification!',
                'body'=> Auth()->user()->first_name.' '.Auth()->user()->last_name,
                ]);
            $user=User::where('id','=',$user_id)
                ->first();
            Notification::send($user, new NewNotification($letter));
        }
        else if($user_id==null && $student_id!="-15")
        {
            $student_d_id=DB::table('students')
    		->where('student_id', '=', $student_id)
    		->first();
            $data['issue_related']=$student_d_id->student_detail_id;
            DB::table('issues')->insert($data);
            // notification part
            $letter=collect([
                'title'=> 'New Notification!',
                'body'=> Auth()->user()->first_name.' '.Auth()->user()->last_name,
                ]);
            $user=User::where('id','=',$student_d_id->student_detail_id)
                ->first();
            Notification::send($user, new NewNotification($letter));
        }
        else if($user_id==null && $student_id=="-15")
        {
            $student_d_id=DB::table('students')
            ->join('classroom', 'classroom.classroom_id', '=', 'students.student_classroom_id')
    		->where('student_classroom_id','=',$classroom_id)
    		->get();
            foreach($student_d_id as $student)
            {
                $data['issue_related']=$student->student_detail_id;
                DB::table('issues')->insert($data);
                // notification part
                $letter=collect([
                    'title'=> 'New Notification!',
                    'body'=> Auth()->user()->first_name.' '.Auth()->user()->last_name,
                    ]);
                $user=User::where('id','=',$student->student_detail_id)
                    ->first();
                Notification::send($user, new NewNotification($letter));
            }
        }
        Session::flash('key', 'Added Successfully');
        Session::flash('message', 'Issue Added Successfully.'); 
        return redirect('/allissue');
    }
    public function get_principle(Request $request)
    {
        $usertype=$request->usertype;
        
        if($usertype=="student")
        {
            $allclassroom= DB::table('classroom')           
            ->get();
            return $allclassroom;
        }
        else
        {
            $usertype = DB::table('users')
            ->where('user_type', '=', $usertype)
            ->get();
            return $usertype;
        }
        
    }
    public function get_principle_specific(Request $request)
    {
        // dd($request);
        $usertype=$request->usertype;
        if($usertype=="student")
        {
            $allclassroom= DB::table('classroom')           
                ->get();
            return $allclassroom;
        }
        else
        {
            $usertype = DB::table('users')
            ->where('user_type', '=', $usertype)
            ->get();
            return $usertype;
        }
    }
    public function get_student_issue_specific(Request $request)
    {
        $classroom_id=$request->classroom_id;
        $all_students= DB::table('students')
            ->join('users', 'students.student_detail_id', '=', 'users.id')
            ->where('students.student_classroom_id','=',$classroom_id)
            ->get();
        return $all_students;
    }
    public function get_student(Request $request)
    {
        $classroom_id=$request->classroom_id;
        $all_students= DB::table('students')
            ->join('users', 'students.student_detail_id', '=', 'users.id')
            ->where('students.student_classroom_id','=',$classroom_id)
            ->get();
        return $all_students;
    }
    public function markAsRead()
    {
        $notification =auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }
    public function editIssue(Request $request)
    {
        $check_type=Auth::user()->user_type;
        $id=$request->id;
    	$specific_issues=DB::table('issues')
    		->join('users', 'issues.issue_creator', '=', 'users.id')
            ->join('studentreports', 'issues.student_report_id', '=', 'studentreports.student_report_id')
    		->where('issue_id', '=', $id)
    		->first();
        if($specific_issues==null)
        {
            $specific_issues=DB::table('issues')
                ->join('users', 'issues.issue_creator', '=', 'users.id')
                ->where('issue_id', '=', $id)
                ->first();
        }
        // dd($specific_issues);
        if($check_type=="student")
        {
            return view('issuemanager.view_issue_student', compact("specific_issues"));
        }
        else
        {
            $record=DB::table('studentreports')
            ->where('current_user_id','=',Auth::user()->id)
            ->get();
            return view('issuemanager.view_issue', compact("specific_issues","record"));
        }
    }
    public function viewPdfFile(Request $request)
    {
        $filename=$request->file_name;
        $filename = "/public/issues/$filename";
        $path = base_path($filename);
        $contentType = mime_content_type($path);
        return response()->file($path);
    }
    public function update_issue(Request $request)
    {
        // dd($request);
    	$this->validate($request,[
            'issueStatus'=>'required',
        ]);
        $student_id=$request->student_id;
        $classroom_id=$request->classroom_id;
        $user_id=$request->user_id;         //Person id other then student
        $usertype=$request->usertype;
        $student_report_id=$request->student_report_id;
    	$currentuseremail=Auth::user()->email;
        $file=$request->file('issuefile');
        $new_name = $currentuseremail . '.' . $file->getClientOriginalExtension();
        
        if($usertype && $usertype!="student" && $user_id && $student_report_id!=null)
        {
            $data1['issue_remarks']=$request->remarks;
            $data1['issue_status'] = $request->issueStatus;
            $data1['issue_detail_file']=$new_name;
            DB::table('issues')
                ->where('issue_id', '=',$request->specific_issues_id)
                ->update($data1);


            $data['issue_text']=$request->issue_text;
            $data['issue_type']=$request->issue_type;
            $data['issue_related']=$user_id;
            $data['issue_file']=$new_name;
            $data['issue_date']=date("Y/m/d");
            $data['issue_creator']=Auth::user()->id;
            $data['issue_status'] = "Pending";
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['student_report_id'] = $student_report_id;

            DB::table('issues')->insert($data);
            $file->move(public_path('issues'), $new_name);
            // Creating Notification
            $letter=collect([
                'title'=> 'New Notification!',
                'body'=> Auth()->user()->first_name.' '.Auth()->user()->last_name,
                ]);
            $user=User::where('id','=',$user_id)->first();
            Notification::send($user, new NewNotification($letter));

            $issue_creator=DB::table('issues')
                ->where('issue_id', '=',$request->specific_issues_id)
                ->first();
            $issue_creator=$issue_creator->issue_creator;
            $user=User::where('id','=',$issue_creator)->first();
            Notification::send($user, new NewNotification($letter));
            // End Notification
        }
        else if($usertype && $usertype=="student" && $student_id && $student_report_id!=null)
        {
            $data1['issue_remarks']=$request->remarks;
            $data1['issue_status'] = $request->issueStatus;
            $data1['issue_detail_file']=$new_name;
            DB::table('issues')
                ->where('issue_id', '=',$request->specific_issues_id)
                ->update($data1);

            if($student_id!="-15") 
            {
                $data['issue_text']=$request->issue_text;
                $data['issue_type']=$request->issue_type;
                $data['issue_related']=$student_id;
                $data['issue_file']=$new_name;
                $data['issue_date']=date("Y/m/d");
                $data['issue_creator']=Auth::user()->id;
                $data['issue_status'] = "Pending";
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $data['student_report_id'] = $student_report_id;

                DB::table('issues')->insert($data);
                $file->move(public_path('issues'), $new_name);
                // Creating Notification
                $letter=collect([
                    'title'=> 'New Notification!',
                    'body'=> Auth()->user()->first_name.' '.Auth()->user()->last_name,
                    ]);
                $user=User::where('id','=',$student_id)->first();
                Notification::send($user, new NewNotification($letter));

                $issue_creator=DB::table('issues')
                    ->where('issue_id', '=',$request->specific_issues_id)
                    ->first();
                $issue_creator=$issue_creator->issue_creator;
                $user=User::where('id','=',$issue_creator)->first();
                Notification::send($user, new NewNotification($letter));
                // End Notification
            }
            else
            {
                $student_d_id=DB::table('students')
                    ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                    ->where('student_classroom_id','=',$classroom_id)
                    ->get();
                $data['issue_text']=$request->issue_text;
                $data['issue_type']=$request->issue_type;
                $data['issue_file']=$new_name;
                $data['issue_date']=date("Y/m/d");
                $data['issue_creator']=Auth::user()->id;
                $data['issue_status'] = "Pending";
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $data['student_report_id'] = $student_report_id;

                foreach ($student_d_id as $student) 
                {
                    $data['issue_related']=$student->student_detail_id;
                    DB::table('issues')->insert($data);
                    // Creating Notification
                    $letter=collect([
                        'title'=> 'New Notification!',
                        'body'=> Auth()->user()->first_name.' '.Auth()->user()->last_name,
                        ]);
                    $user=User::where('id','=',$student->student_detail_id)->first();
                    Notification::send($user, new NewNotification($letter));

                    $issue_creator=DB::table('issues')
                        ->where('issue_id', '=',$request->specific_issues_id)
                        ->first();
                    $issue_creator=$issue_creator->issue_creator;
                    $user=User::where('id','=',$issue_creator)->first();
                    Notification::send($user, new NewNotification($letter));
                    // End Notification
                }
                $file->move(public_path('issues'), $new_name); 
            }
        }
        else if($usertype==null)
        {
            $data['issue_remarks']=$request->remarks;
            $data['issue_status'] = $request->issueStatus;
            $data['issue_detail_file']=$new_name;
            $file->move(public_path('issues'), $new_name);
            DB::table('issues')
                ->where('issue_id', '=',$request->specific_issues_id)
                ->update($data);
            // Creating Notification
            $letter=collect([
                'title'=> 'New Notification!',
                'body'=> Auth()->user()->first_name.' '.Auth()->user()->last_name,
                ]);
            $issue_creator=DB::table('issues')
                ->where('issue_id', '=',$request->specific_issues_id)
                ->first();
            $issue_creator=$issue_creator->issue_creator;
            $user=User::where('id','=',$issue_creator)->first();
            Notification::send($user, new NewNotification($letter));
            // End Notification
        }
        else
        {
            return redirect()->back();
        }
        // dd("done");
        Session::flash('key', 'Updated Successfully');
        Session::flash('message', 'Issue Updated Successfully.'); 
        return redirect('/allissue');
    }
    public function update_issue_student(Request $request)
    {
        $this->validate($request,[
            'issueStatus'=>'required',
        ]);
        $currentuseremail=Auth::user()->email;
        $user_id=$request->user_id;
        $issueRelated=$request->issueRelated;
        $issueStatus=$request->issueStatus;

        if($issueRelated!=null && $user_id!=null)
        {
            $data1['issue_status']=$issueStatus;
            DB::table('issues')
                ->where('issue_id', '=',$request->specific_issues_id)
                ->update($data1);
            $data['issue_text']=$request->issue_text;
            $data['issue_type']=$request->issue_type;
            $data['issue_related']=$user_id;
            $data['issue_date']=date("Y/m/d");
            $data['issue_creator']=Auth::user()->id;
            $data['issue_status'] = "Pending";
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');

            DB::table('issues')->insert($data);
            // Creating Notification
            $letter=collect([
                'title'=> 'New Notification!',
                'body'=> Auth()->user()->first_name.' '.Auth()->user()->last_name,
                ]);
            $user=User::where('id','=',$user_id)->first();
            Notification::send($user, new NewNotification($letter));
        }
        else
        {
            $data['issue_status'] = $request->issueStatus;
            DB::table('issues')
                ->where('issue_id', '=',$request->specific_issues_id)
                ->update($data);
            // Creating Notification
            $letter=collect([
                'title'=> 'New Notification!',
                'body'=> Auth()->user()->first_name.' '.Auth()->user()->last_name,
                ]);
            $issue_creator=DB::table('issues')
                ->where('issue_id', '=',$request->specific_issues_id)
                ->first();
            $issue_creator=$issue_creator->issue_creator;
            $user=User::where('id','=',$issue_creator)->first();
            Notification::send($user, new NewNotification($letter));
            // End Notification
        }
        // dd("done");
        Session::flash('key', 'Updated Successfully');
        Session::flash('message', 'Issue Updated Successfully.'); 
        return redirect('/allissue');
    }
}
