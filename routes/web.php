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
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
	if(Auth::check()!=null)
	{
		return redirect('/home');
	}
	else
	{
		return view('superadmin.login');
	} 
});
//Forget password
Route::get('/forgetpasswordSA', 'SuperAdmin@forgetpasswordSA')->name('forgetpasswordSA');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');













//Super Admin get Route
Route::get('/alluser', 'SuperAdmin@alluser')->name('alluser')->middleware('SuperAdmin');
Route::get('/adduser', 'SuperAdmin@adduser')->name('adduser')->middleware('SuperAdmin');
Route::post('/edituser', 'SuperAdmin@edituser')->name('edituser')->middleware('SuperAdmin');
Route::get('/definesmc', 'SuperAdmin@definesmc')->name('definesmc')->middleware('SuperAdmin');
//Super Admin post Route
Route::post('/add_user', 'SuperAdmin@add_user')->name('add_user')->middleware('SuperAdmin');
Route::post('/edit_user_save', 'SuperAdmin@edit_user_save')->name('edit_user_save')->middleware('SuperAdmin');
Route::post('/add_smc_member', 'SuperAdmin@add_smc_member')->name('add_smc_member')->middleware('SuperAdmin');
//Check Email
Route::post('/check_email', 'SuperAdmin@check_email')->name('check_email')->middleware('SuperAdmin');
//Delete SMC
Route::get('/deleteSmc/{id}', 'SuperAdmin@deleteSmc')->name('deleteSmc')->middleware('SuperAdmin');

Route::get('/editProfile', 'SuperAdmin@editProfile')->name('editProfile');
Route::post('/edit_profile_save', 'SuperAdmin@edit_profile_save')->name('edit_profile_save');














// Branch manager Routes*****************************************
Route::get('/addbranch', 'BranchManager@addbranch')->name('addbranch')->middleware('branchmanager');
Route::get('/allbranch', 'BranchManager@allbranch')->name('allbranch')->middleware('branchmanager');
Route::post('/editbranch', 'BranchManager@editbranch')->name('editbranch')->middleware('branchmanager');
// Branch manager POST Routes
Route::post('/add_branch', 'BranchManager@add_branch')->name('add_branch')->middleware('branchmanager');
Route::post('/update_branch', 'BranchManager@update_branch')->name('update_branch')->middleware('branchmanager');














// School Manager Routes*****************************************
Route::get('/addschool', 'SchoolManager@addschool')->name('addschool')->middleware('SchoolManager');
Route::get('/allschool', 'SchoolManager@allschool')->name('allschool')->middleware('SchoolManager');
Route::post('/editschool', 'SchoolManager@editschool')->name('editschool')->middleware('SchoolManager');
// School manager POST Routes
Route::post('/add_school', 'SchoolManager@add_school')->name('add_school')->middleware('SchoolManager');
Route::post('/update_school', 'SchoolManager@update_school')->name('update_school')->middleware('SchoolManager');

Route::get('/teacherreport', 'SchoolManager@teacherreport')->name('teacherreport')->middleware('SchoolManager');

Route::post('/get_schools_school', 'SchoolManager@get_schools')->name('get_schools_school')->middleware('SchoolManager');
Route::post('/get_block_school', 'SchoolManager@get_block')->name('get_block_school')->middleware('SchoolManager');
Route::post('/get_grade_school', 'SchoolManager@get_grade')->name('get_grade_school')->middleware('SchoolManager');
Route::post('/get_class_school', 'SchoolManager@get_class')->name('get_class_school')->middleware('SchoolManager');
Route::post('/get_subjects_school', 'SchoolManager@get_subjects')->name('get_subjects_school')->middleware('SchoolManager');
Route::post('/get_student_school', 'SchoolManager@get_student')->name('get_student_school')->middleware('SchoolManager');



Route::post('/get_report_school', 'SchoolManager@get_report')->name('get_report_school')->middleware('SchoolManager');
Route::post('saveFileInDB', 'SchoolManager@saveFileInDB')->name('saveFileInDB')->middleware('SchoolManager');
// Route::get('/downloadPDF/{id}','SchoolManager@pdfview')->name('downloadPDF')->middleware('SchoolManager');










// Block Manager Routes*****************************************
Route::get('/allblock', 'BlockManager@allblock')->name('allblock')->middleware('BlockManager');
Route::post('/editblock', 'BlockManager@editblock')->name('editblock')->middleware('BlockManager');
// Block manager POST Routes
Route::post('/update_block', 'BlockManager@update_block')->name('update_block')->middleware('BlockManager');


Route::get('/vp_report', 'BlockManager@vp_report')->name('vp_report')->middleware('BlockManager');
Route::post('/get_schools_vp', 'BlockManager@get_schools')->name('get_schools_vp')->middleware('BlockManager');
Route::post('/get_block_vp', 'BlockManager@get_block')->name('get_block_vp')->middleware('BlockManager');
Route::post('/get_grade_vp', 'BlockManager@get_grade')->name('get_grade_vp')->middleware('BlockManager');
Route::post('/get_class_vp', 'BlockManager@get_class')->name('get_class_vp')->middleware('BlockManager');
Route::post('/get_subjects_vp', 'BlockManager@get_subjects')->name('get_subjects_vp')->middleware('BlockManager');
Route::post('/get_student_vp', 'BlockManager@get_student')->name('get_student_vp')->middleware('BlockManager');
Route::post('/get_report_vp', 'BlockManager@get_report')->name('get_report_vp')->middleware('BlockManager');
Route::post('saveFileInDB_vp', 'BlockManager@saveFileInDB')->name('saveFileInDB_vp')->middleware('BlockManager');









//Teacher manager Route******************************************
Route::get('/addteacher', 'TeacherManager@addteacher')->name('addteacher')->middleware('BlockManager');
Route::get('/allteachers', 'TeacherManager@allteachers')->name('allteachers')->middleware('BlockManager');
Route::post('/editteacher', 'TeacherManager@editteacher')->name('editteacher')->middleware('BlockManager');
Route::get('/assignteacher', 'TeacherManager@assignteacher')->name('assignteacher')->middleware('BlockManager');
Route::get('/allassignedteachers', 'TeacherManager@allassignedteachers')->name('allassignedteachers')->middleware('BlockManager');
Route::post('/add_teacher', 'TeacherManager@add_teacher')->name('add_teacher')->middleware('BlockManager');
Route::post('/update_teacher', 'TeacherManager@update_teacher')->name('update_teacher')->middleware('BlockManager');
Route::post('/assigned_teacher', 'TeacherManager@assigned_teacher')->name('assigned_teacher')->middleware('BlockManager');
Route::post('/edit_assigned_teacher', 'TeacherManager@edit_assigned_teacher')->name('edit_assigned_teacher')->middleware('BlockManager');
Route::post('/update_assigned_teacher', 'TeacherManager@update_assigned_teacher')->name('update_assigned_teacher')->middleware('BlockManager');


//
Route::get('/supervisor_report', 'TeacherManager@supervisor_report')->name('supervisor_report')->middleware('TeacherManager');
Route::post('/get_subjects_s', 'TeacherManager@get_subjects')->name('get_subjects_s')->middleware('TeacherManager');
Route::post('/get_student_s', 'TeacherManager@get_student')->name('get_student_s')->middleware('TeacherManager');
Route::post('/get_report_s', 'TeacherManager@get_report')->name('get_report_s')->middleware('TeacherManager');
Route::post('saveFileInDB_s', 'TeacherManager@saveFileInDB')->name('saveFileInDB_s')->middleware('TeacherManager');











// Grade Manager Routes*****************************************
Route::get('/allgrade', 'GradeManager@allgrade')->name('allgrade')->middleware('GradeManager');
Route::post('/editgrade', 'GradeManager@editgrade')->name('editgrade')->middleware('GradeManager');
// Grade manager POST Routes
Route::post('/update_grade', 'GradeManager@update_grade')->name('update_grade')->middleware('GradeManager');

// Student Reports 
Route::get('/hm_report', 'GradeManager@hm_report')->name('hm_report')->middleware('GradeManager');
Route::post('/get_schools_hm', 'GradeManager@get_schools')->name('get_schools_hm')->middleware('GradeManager');
Route::post('/get_block_hm', 'GradeManager@get_block')->name('get_block_hm')->middleware('GradeManager');
Route::post('/get_grade_hm', 'GradeManager@get_grade')->name('get_grade_hm')->middleware('GradeManager');
Route::post('/get_class_hm', 'GradeManager@get_class')->name('get_class_hm')->middleware('GradeManager');
Route::post('/get_subjects_hm', 'GradeManager@get_subjects')->name('get_subjects_hm')->middleware('GradeManager');
Route::post('/get_student_hm', 'GradeManager@get_student')->name('get_student_hm')->middleware('GradeManager');
Route::post('/get_report_hm', 'GradeManager@get_report')->name('get_report_hm')->middleware('GradeManager');
Route::post('saveFileInDB_hm', 'GradeManager@saveFileInDB')->name('saveFileInDB_hm')->middleware('GradeManager');













// Class Management Route***************************************
Route::get('/addclassroom', 'ClassManager@addclassroom')->name('addclassroom')->middleware('ClassManager');
Route::get('/allclassroom', 'ClassManager@allclassroom')->name('addclassroom')->middleware('ClassManager');
Route::post('/editclassroom', 'ClassManager@editclassroom')->name('editclassroom')->middleware('ClassManager');
// Class POST route
Route::post('/add_classroom', 'ClassManager@add_classroom')->name('add_classroom')->middleware('ClassManager');
Route::post('/update_classroom', 'ClassManager@update_classroom')->name('update_classroom')->middleware('ClassManager');
Route::post('/add_student_to_class', 'ClassManager@add_student_to_class')->name('add_student_to_class')->middleware('ClassManager');
Route::get('/delete_student/{id}', 'ClassManager@delete_student')->name('delete_student')->middleware('ClassManager');
Route::post('/get_remaning_students', 'ClassManager@get_remaning_students')->name('get_remaning_students')->middleware('ClassManager');



Route::get('/l_report', 'ClassManager@l_report')->name('l_report')->middleware('ClassManager');
Route::post('/get_schools_l', 'ClassManager@get_schools')->name('get_schools_l')->middleware('ClassManager');
Route::post('/get_block_l', 'ClassManager@get_block')->name('get_block_l')->middleware('ClassManager');
Route::post('/get_grade_l', 'ClassManager@get_grade')->name('get_grade_l')->middleware('ClassManager');
Route::post('/get_class_l', 'ClassManager@get_class')->name('get_class_l')->middleware('ClassManager');
Route::post('/get_subjects_l', 'ClassManager@get_subjects')->name('get_subjects_l')->middleware('ClassManager');
Route::post('/get_student_l', 'ClassManager@get_student')->name('get_student_l')->middleware('ClassManager');
Route::post('/get_report_l', 'ClassManager@get_report')->name('get_report_l')->middleware('ClassManager');
Route::post('saveFileInDB_l', 'ClassManager@saveFileInDB')->name('saveFileInDB_l')->middleware('ClassManager');














//Student manager Route******************************************
Route::get('/addgrade', 'StudentManager@addgrade')->name('addgrade')->middleware('StudentManager');
//Student manager POST  Route
Route::post('/filterdata', 'StudentManager@filterdata')->name('filterdata')->middleware('StudentManager');
Route::post('/update_marks', 'StudentManager@update_marks')->name('update_marks')->middleware('StudentManager');
Route::get('/editstdgrade/{student_id}/{subject_teacher_id}', 'StudentManager@editstdgrade')->name('editstdgrade')->middleware('StudentManager');
Route::post('/update_std_marks', 'StudentManager@update_std_marks')->name('update_std_marks')->middleware('StudentManager');

Route::get('/t_report', 'StudentManager@t_report')->name('t_report')->middleware('StudentManager');
Route::post('/get_schools_t', 'StudentManager@get_schools')->name('get_schools_t')->middleware('StudentManager');
Route::post('/get_block_t', 'StudentManager@get_block')->name('get_block_t')->middleware('StudentManager');
Route::post('/get_grade_t', 'StudentManager@get_grade')->name('get_grade_t')->middleware('StudentManager');
Route::post('/get_class_t', 'StudentManager@get_class')->name('get_class_t')->middleware('StudentManager');
Route::post('/get_subjects_t', 'StudentManager@get_subjects')->name('get_subjects_t')->middleware('StudentManager');
Route::post('/get_student_t', 'StudentManager@get_student')->name('get_student_t')->middleware('StudentManager');
Route::post('/get_report_t', 'StudentManager@get_report')->name('get_report_t')->middleware('StudentManager');
Route::post('saveFileInDB_t', 'StudentManager@saveFileInDB')->name('saveFileInDB_t')->middleware('StudentManager');










//Issue manager Route******************************************
Route::get('/addissue', 'IssueManager@addissue')->name('addissue')->middleware('IssueManager');
Route::get('/allissue', 'IssueManager@allissue')->name('allissue')->middleware('IssueManager');
Route::get('/markAsRead', 'IssueManager@markAsRead')->name('markAsRead')->middleware('IssueManager');
Route::get('/editIssue/{id}', 'IssueManager@editIssue')->name('editIssue')->middleware('IssueManager');
Route::get('/editIssue_student/{id}', 'IssueManager@editIssue_student')->name('editIssue_student')->middleware('IssueManager');
Route::post('/add_issue', 'IssueManager@add_issue')->name('add_issue')->middleware('IssueManager');
Route::post('/update_issue', 'IssueManager@update_issue')->name('update_issue')->middleware('IssueManager');
Route::post('/update_issue_student', 'IssueManager@update_issue_student')->name('update_issue_student')->middleware('IssueManager');

Route::post('get_principle','IssueManager@get_principle')->name('get_principle')->middleware('IssueManager');
Route::post('get_student_issue','IssueManager@get_student')->name('get_student_issue')->middleware('IssueManager');
Route::post('get_principle_specific','IssueManager@get_principle_specific')->name('get_principle_specific')->middleware('IssueManager');
Route::post('get_student_issue_specific','IssueManager@get_student_issue_specific')->name('get_student_issue_specific')->middleware('IssueManager');

Route::get('/viewPdfFile/{file_name}', 'IssueManager@viewPdfFile')->name('viewPdfFile')->middleware('IssueManager');







//Issue manager Route******************************************
Route::get('/studentreport', 'ReportManager@studentreport')->name('studentreport')->middleware('ReportManager');
Route::post('/get_report', 'ReportManager@get_report')->name('get_report')->middleware('ReportManager');
Route::post('/get_schools', 'ReportManager@get_schools')->name('get_schools')->middleware('ReportManager');
Route::post('/get_block', 'ReportManager@get_block')->name('get_block')->middleware('ReportManager');
Route::post('/get_grade', 'ReportManager@get_grade')->name('get_grade')->middleware('ReportManager');
Route::post('/get_class', 'ReportManager@get_class')->name('get_class')->middleware('ReportManager');
Route::post('/get_subjects', 'ReportManager@get_subjects')->name('get_subjects')->middleware('ReportManager');
Route::post('/get_student', 'ReportManager@get_student')->name('get_student')->middleware('ReportManager');