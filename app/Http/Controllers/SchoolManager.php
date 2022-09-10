<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Session;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class SchoolManager extends Controller
{
    public function addschool(Request $request)
    {
        $current_user_id=Auth::user()->id;

    	$allbranch=DB::table('branch')
            ->where('branch_principle', '=' , $current_user_id)
            ->get();
    	$viceprinciple=DB::table('users')->where('user_type', '=' , "user")->orderBy('id', 'DESC')->get();
    	return view('schoolmanager.addschool', compact("allbranch", "viceprinciple"));
    }
    public function allschool(Request $request)
    {
        $current_user_id=Auth::user()->id;
    	$allschool = DB::table('school')
            ->join('users', 'school.vice_principle', '=', 'users.id')
            ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
            ->where('branch.branch_principle', '=' , $current_user_id)
            ->orderBy('school.school_id', 'DESC')
            ->get();
    	return view('schoolmanager.allschool', compact("allschool"));
    }
    public function editschool(Request $request)
    {
        $current_user_id=Auth::user()->id;
    	$id=$request->school_id;
    	$specific_school = DB::table('school')
    		->join('users', 'school.vice_principle', '=', 'users.id')
    		->join('branch', 'school.branch_id', '=', 'branch.branch_id')
	    	->where('school.school_id', '=', $id)
	    	->first();
	    $allbranch=DB::table('branch')
            ->where('branch_principle', '=' , $current_user_id)
            ->get();
	    $viceprinciple=DB::table('users')
	    	->where('user_type', '=' , "user")
	    	->orderBy('id', 'DESC')
	    	->get();
    	return view('schoolmanager.editschool', compact("specific_school", "allbranch" ,"viceprinciple"));
    }
    public function add_school(Request $request)
    {
    	$this->validate($request,[
            'viceprinciple'=>'required',
            'branchid'=>'required',
            'schoolfor'=>'required'
        ]);
        $data['school_name']=$request->schoolname;
        $data['vice_principle']=$request->viceprinciple;
        $data['school_for']=$request->schoolfor;
        $data['branch_id']=$request->branchid;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $last_id=DB::table('school')->insertGetId($data);
        //Update user Table To set user_type To branch manager
        $data1['user_type']="viceprinciple";
        DB::table('users')
            ->where('id', '=', $request->viceprinciple)
            ->update($data1);

        //Add Block Data here
        $block_1="Block-1 ";
        $block_2="Block-2";
        $block_3="Block-3";
        $block_4="Block-4";

        $b1=DB::table('block')->insertGetId(['block_name' => $block_1, 'school_id' => $last_id, 'head_master' => null,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')]);

        $b2=DB::table('block')->insertGetId(['block_name' => $block_2, 'school_id' => $last_id, 'head_master' => null,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')]);

        $b3=DB::table('block')->insertGetId(['block_name' => $block_3, 'school_id' => $last_id, 'head_master' => null,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')]);

        $b4=DB::table('block')->insertGetId(['block_name' => $block_4, 'school_id' => $last_id, 'head_master' => null,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')]);

        //Insert 12 Grade
        for($i=1; $i<=12; $i++)
        {
            if($i>=1 && $i<=2)
            {
                if($i==1)
                {
                    $grade_n=$i."st Grade";
                    DB::table('grade')->insert(['grade_name' => $grade_n, 'block_id' => $b1, 'leader' => null,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')]);
                }
                else if($i==2)
                {
                    $grade_n=$i."nd Grade";
                    DB::table('grade')->insert(['grade_name' => $grade_n, 'block_id' => $b1, 'leader' => null,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')]);
                }
            }
            else if($i>=3 && $i<=5)
            {
                if($i==3)
                {
                    $grade_n=$i."rd Grade";
                    DB::table('grade')->insert(['grade_name' => $grade_n, 'block_id' => $b2, 'leader' => null,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')]);
                }
                else
                {
                    $grade_n=$i."th Grade";
                    DB::table('grade')->insert(['grade_name' => $grade_n, 'block_id' => $b2, 'leader' => null,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')]);
                }
            }
            else if($i>=6 && $i<=8)
            {
                $grade_n=$i."th Grade";
                DB::table('grade')->insert(['grade_name' => $grade_n, 'block_id' => $b3, 'leader' => null,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')]);
            }
            else if($i>=9 && $i<=12)
            {
                $grade_n=$i."th Grade";
                DB::table('grade')->insert(['grade_name' => $grade_n, 'block_id' => $b4, 'leader' => null,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')]);
            }
        }
    	Session::flash('key', 'Added Successfully');
        Session::flash('message', 'School Added Successfully.'); 
        return redirect('/allschool');
    }
    public function update_school(Request $request)
    {
    	$school_id=$request->school_id;
    	if($request->branchid==null)
    	{
    		$bid=$request->alreadybranchid;
    	}
    	else
    	{
    		$bid=$request->branchid;
    	}
    	if($request->viceprinciple==null)
    	{
    		$vpid=$request->alreadyviceprinciple;
    	}
    	else
    	{
    		$vpid=$request->viceprinciple;
    	}
    	if($request->schoolfor==null)
    	{
    		$sf=$request->alreadyschoolfor;
    	}
    	else
    	{
    		$sf=$request->schoolfor;
    	}
    	$data['school_name']=$request->schoolname;
        $data['vice_principle']=$vpid;
        $data['school_for']=$sf;
        $data['branch_id']=$bid;
        $data['updated_at'] = date('Y-m-d H:i:s');
    	DB::table('school')
            ->where('school_id', '=', $school_id)
            ->update($data);
        $data1['user_type']="viceprinciple";
        DB::table('users')
            ->where('id', '=', $vpid)
            ->update($data1);
        $data2['user_type']="user";
        if($vpid!=$request->alreadyviceprinciple)
        {
        	DB::table('users')
                ->where('id', '=', $request->alreadyviceprinciple)
                ->update($data2);
        }
        Session::flash('key', 'Updated Successfully');
        Session::flash('message', 'Branch Updated Successfully.'); 
        return redirect('/allschool');
    }
    public function teacherreport(Request $request)
    {
        $current_user_id=Auth::user()->id;
        $allbranch = DB::table('branch')
            ->where('branch_principle', '=', $current_user_id)
            ->orderBy('branch.branch_id', 'DESC')
            ->get();
        return view('schoolmanager.teacher_report', compact("allbranch"));
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
        if($school_id=="-322")
        {
            $branch_id=$request->branch_id;
            $allblock = DB::table('block')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->where('school.branch_id','=', $branch_id)
                ->get();
        }
        else
        {
            $allblock = DB::table('block')
                ->where('school_id', '=', $school_id)
                ->get();
        }
        return $allblock;
    }
    public function get_grade(Request $request)
    {
        $block_id=$request->block_id;
        $branch_id=$request->branch_id;
        $school_id=$request->school_id;
        if($school_id=="-322" && $block_id=="-420")
        {
            $allgrade = DB::table('grade')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->where('school.branch_id', '=', $branch_id)
                ->get();
        }
        else if($school_id=="-322" && $block_id!="-420")
        {
            $allgrade = DB::table('grade')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->where('block.block_id', '=', $block_id)
                ->where('school.branch_id', '=', $branch_id)
                ->get();
            // return "All School Not All Block";
        }
        else if($school_id!="-322" && $block_id=="-420")
        {
            $allgrade = DB::table('grade')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->where('school.school_id', '=', $school_id)
                ->where('school.branch_id', '=', $branch_id)
                ->get();
            // return "Not All School But All Block";
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
        $branch_id=$request->branch_id;
        $school_id=$request->school_id;
        $block_id=$request->block_id;
        $grade_id=$request->grade_id;

        if($school_id!="-322" && $block_id!="-420" && $grade_id!="-220")
        {
            $allclass = DB::table('classroom')
                ->where('grade_id', '=', $grade_id)
                ->get();
        }



        if($school_id=="-322" && $block_id=="-420" && $grade_id!="-220")
        {
            $allclass = DB::table('classroom')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->where('classroom.grade_id', '=', $grade_id)
                ->where('school.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id!="-420" && $grade_id!="-220")
        {
            $allclass = DB::table('classroom')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->where('classroom.grade_id', '=', $grade_id)
                ->where('block.block_id', '=', $block_id)
                ->where('school.branch_id', '=', $branch_id)
                ->get();
        }



        if($school_id=="-322" && $block_id!="-420" && $grade_id=="-220")
        {
            $allclass = DB::table('classroom')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->where('block.block_id', '=', $block_id)
                ->where('school.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id=="-220")
        {
            $allclass = DB::table('classroom')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('school.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id=="-420" && $grade_id=="-220")
        {
            $allclass = DB::table('classroom')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->where('school.school_id', '=', $school_id)
                ->where('school.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id=="-420" && $grade_id!="-220")
        {
            $allclass = DB::table('classroom')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->where('school.school_id', '=', $school_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('school.branch_id', '=', $branch_id)
                ->get();
        }

        if($school_id=="-322" && $block_id=="-420" && $grade_id=="-220")
        {
            $allclass = DB::table('classroom')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->where('school.branch_id', '=', $branch_id)
                ->get();
        }
        return $allclass;
    }
    public function get_subjects(Request $request)
    {
        $class_id=$request->class_id;

        $branch_id=$request->branch_id;

        $school_id=$request->school_id;
        $block_id=$request->block_id;
        $grade_id=$request->grade_id;

        if($school_id=="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id=="-120")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')

                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }





        if($school_id!="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id=="-120")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->where('branch.branch_id', '=', $branch_id)
                ->where('school.school_id', '=', $school_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id=="-220" && $class_id=="-120")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id=="-120")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }






        if($school_id=="-322" && $block_id!="-420" && $grade_id=="-220" && $class_id=="-120")
        {
             $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->where('block.block_id', '=', $block_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id=="-120")
        {
             $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id!="-120")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }





        if($school_id=="-322" && $block_id=="-420" && $grade_id!="-220" && $class_id=="-120")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id=="-420" && $grade_id!="-220" && $class_id!="-120")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id=="-420" && $grade_id!="-220" && $class_id!="-120")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('school.school_id', '=', $school_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }






        if($school_id=="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id!="-120")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id!="-120")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->where('school.school_id', '=', $school_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id=="-220" && $class_id!="-120")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'teacher_classroom.classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }

        



        if($school_id!="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id!="-120")
        {
            $allsubjects = DB::table('teacher_classroom')
                ->join('subjectteacher', 'teacher_classroom.teacher_id', '=', 'subjectteacher.teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->where('teacher_classroom.classroom_id', '=', $class_id)
                ->get();
        }
        return $allsubjects;
    }
    public function get_student(Request $request)
    {
        $subject_id=$request->subject_id;
        $class_id=$request->class_id;

        $branch_id=$request->branch_id;

        $school_id=$request->school_id;
        $block_id=$request->block_id;
        $grade_id=$request->grade_id;

        if($school_id=="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')

                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }


        if($school_id!="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')

                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('school.school_id', '=', $school_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')

                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')

                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')

                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }




        if($school_id=="-322" && $block_id!="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')

                ->join('users', 'students.student_detail_id', '=', 'users.id')

                ->where('block.block_id', '=', $block_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id=="-20")
        {
             $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')

                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('block.block_id', '=', $block_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')

                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('block.block_id', '=', $block_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')

                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('block.block_id', '=', $block_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }



        if($school_id=="-322" && $block_id=="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')

                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id=="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')

                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id=="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')

                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id=="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')

                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('school.school_id', '=', $school_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }



        if($school_id=="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id=="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')

                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')

                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')

                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('school.school_id', '=', $school_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')

                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }



        if($school_id=="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')

                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')

                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('school.school_id', '=', $school_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')

                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')

                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }




        else if($school_id!="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20")
        {
            $allstudents = DB::table('subjectteacher')
                ->join('teacher_classroom', 'subjectteacher.teacher_id', '=', 'teacher_classroom.teacher_id')
                ->join('students', 'teacher_classroom.classroom_id', '=', 'students.student_classroom_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('teacher_classroom.classroom_id', '=', $class_id)
                ->get();
        }
        return $allstudents;
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
        Session::put('branch_id', $request->branch_id);
        Session::put('school_id', $request->school_id);
        Session::put('block_id', $request->block_id);
        Session::put('grade_id', $request->grade_id);
        Session::put('class_id', $request->class_id);
        Session::put('subject_id', $request->subject_id);
        Session::put('student_id', $request->student_id);
        Session::put('grade_type', $request->grade_type);

     
        if($school_id=="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }





        if($school_id!="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }





        if($school_id=="-322" && $block_id!="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }




        if($school_id=="-322" && $block_id=="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id=="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id=="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id=="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id=="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }



        if($school_id=="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }





        if($school_id=="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }




        if($school_id=="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }



        if($school_id!="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        
        return view('schoolmanager.save_report', compact("get_data"));
    }

    public function saveFileInDB(Request $request)
    {
        $branch_id=Session::get('branch_id');
        $school_id=Session::get('school_id');
        $block_id=Session::get('block_id');
        $grade_id=Session::get('grade_id');
        $class_id=Session::get('class_id');
        $subject_id=Session::get('subject_id');
        $student_id=Session::get('student_id');
        $grade_type=Session::get('grade_type');
        // dd($school_id);
     // dd($request);
     if($school_id=="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        
        if($school_id!="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }





        if($school_id=="-322" && $block_id!="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }




        if($school_id=="-322" && $block_id=="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id=="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id=="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id=="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id=="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }



        if($school_id=="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id=="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }





        if($school_id=="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id=="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id=="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }




        if($school_id=="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id=="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id=="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id=="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        if($school_id!="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id=="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }



        if($school_id!="-322" && $block_id!="-420" && $grade_id!="-220" && $class_id!="-120" && $subject_id!="-20" && $student_id!="-15")
        {
            $get_data = DB::table('student_grades')
                ->join('students', 'student_grades.student_id', '=', 'students.student_id')
                ->join('subjectteacher', 'student_grades.subject_teacher_id', '=', 'subjectteacher.subject_teacher_id')
                ->join('subjects', 'subjectteacher.subject_id', '=', 'subjects.subject_id')
                ->join('classroom', 'students.student_classroom_id', '=', 'classroom.classroom_id')
                ->join('grade', 'classroom.grade_id', '=', 'grade.grade_id')
                ->join('block', 'grade.block_id', '=', 'block.block_id')
                ->join('school', 'block.school_id', '=', 'school.school_id')
                ->join('branch', 'school.branch_id', '=', 'branch.branch_id')
                ->join('users', 'students.student_detail_id', '=', 'users.id')
                ->where('school.school_id', '=', $school_id)
                ->where('block.block_id', '=', $block_id)
                ->where('grade.grade_id', '=', $grade_id)
                ->where('classroom.classroom_id', '=', $class_id)
                ->where('subjectteacher.subject_id', '=', $subject_id)
                ->where('student_grades.student_id', '=', $student_id)
                ->where('branch.branch_id', '=', $branch_id)
                ->get();
        }
        $data['student_report_name']=$request->input('student_report_name');
        view()->share('schoolmanager.pdfview',$get_data);
        $pdf = PDF::loadView('schoolmanager.pdfview', compact('get_data'));
        $files=$pdf->download($data['student_report_name'].' '.'pdfview.pdf');
        
        $pdf = PDF::loadView('schoolmanager.pdfview',compact('get_data')); // <--- load your view into theDOM wrapper;
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
        return redirect('teacherreport');
    }
}
