@extends('superadmin.app')
@section('content')
    <div class="page-bar">
        <div class="page-title-breadcrumb">
            <div class=" pull-left">
                <div class="page-title">View Issue</div>
            </div>
            <ol class="breadcrumb page-breadcrumb pull-right">
                <li><i class="fa fa-home"></i>&nbsp;<a class="parent-item" href="{{ url('allissue') }}">Home</a>&nbsp;<i
                        class="fa fa-angle-right"></i>
                </li>
                <li><a class="parent-item" href="">Issues Management</a>&nbsp;<i class="fa fa-angle-right"></i>
                </li>
                <li class="active">View Issue</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="card-head">
                    <header>Issue Details</header>
                </div>
                <form method="POST" action="{{ route('update_issue') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body row">
                        <div class="col-lg-12 p-t-20">
                            <div class="mdl-textfield mdl-js-textfield txt-full-width">
                                <textarea class="mdl-textfield__input" rows="4" id="text7" value="" name="issue_text"
                                readonly>{{ $specific_issues->issue_text }}</textarea>
                                <label class="mdl-textfield__label" for="text7">Issue Description</label>
                                <input type="hidden" value="{{ $specific_issues->issue_id }}" name="specific_issues_id">
                            </div>
                        </div>
                        <div class="col-lg-6 p-t-20">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
                                <input class="mdl-textfield__input" type="text" id="txtCourseCode"
                                    value="{{ $specific_issues->issue_type }}" readonly name="issue_type">
                                <label class="mdl-textfield__label">Issue Type</label>
                            </div>
                        </div>
                        <div class="col-lg-6 p-t-20">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
                                <input class="mdl-textfield__input" type="text" id="txtCourseCode"
                                    value="{{ $specific_issues->issue_date }}" readonly name="issue_date">
                                <label class="mdl-textfield__label">Issue Date</label>
                            </div>
                        </div>
                        <div class="col-lg-6 p-t-20">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
                                <input class="mdl-textfield__input" type="text" id="txtCourseCode"
                                    value="{{ $specific_issues->first_name . ' ' . $specific_issues->last_name }}"
                                    readonly >
                                <label class="mdl-textfield__label">Creater</label>
                            </div>
                        </div>
                        @if($specific_issues->issue_file)
                        <div class="col-lg-3 p-t-20">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
                                 <i class="fa fa-file-pdf-o" style="font-size:20px;color:red"></i>
                                <b> <a href="{{ url('/viewPdfFile/'.$specific_issues->issue_file) }}">{{$specific_issues->issue_file}}</a> </b>
                            </div>
                        </div>
                        @endif
                        @if($specific_issues->report_name ?? '')
                        <div class="col-lg-3 p-t-20">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
                                <i class="fa fa-file-pdf-o" style="font-size:20px;color:red"></i>
                                <b> <a href="{{ url('/viewPdfFile/'.$specific_issues->report_name) }}">{{$specific_issues->report_name}}</a> </b>
                            </div>
                        </div>
                        @endif
                        <div class="col-lg-6 p-t-20">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
                                <input required class="mdl-textfield__input" type="text" id="txtCourseCode" name="remarks">
                                <label class="mdl-textfield__label">Remarks</label>
                            </div>
                        </div>
                        <div class="col-lg-6 p-t-20">
                            <div
                                class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
                                <input class="mdl-textfield__input" type="text" id="list2" value="" readonly tabIndex="-1">
                                <label for="list2" class="pull-right margin-0">
                                    <i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
                                </label>
                                <label for="list2" class="mdl-textfield__label">Status</label>
                                <ul data-mdl-for="list2" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
                                    <li class="mdl-menu__item" value="1" id="solved">Solved</li>
                                    <li class="mdl-menu__item" value="2" id="approved">Approved</li>
                                    <input type="hidden" id="issueStatus" name="issueStatus">
                                </ul>
                            </div>
                            @if ($errors->has('issueStatus'))
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{{ $errors->first('issueStatus') }}</li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-6 p-t-20">
                            <div
                                class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
                                <select id="issuerelated" name="issueRelated" class="form-control"
                                    style="height: auto;">
                                    <option selected disabled>Select issue related</option>
                                    <option value="principle">Principle</option>
                                    <option value="viceprinciple">Viceprinciple</option>
                                    <option value="headmaster">Headmaster</option>
                                    <option value="leader">Leader</option>
                                    <option value="supervisor">Supervisor</option>
                                    <option value="teacher">Teacher</option>
                                    <option value="student">Student</option>
                                </select>
                                <input type="hidden" id="to_issueRelated_id" name="usertype">
                            </div>
                        </div>
                        <div class="col-lg-6 p-t-20" id="allusers_show">
                            <div
                                class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
                                <select name="user_id" id="allusers" class="form-control">

                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6 p-t-20" id="classes_show" style="display: none;">
                            <div
                                class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
                                <select id="allclass" class="form-control" name="classroom_id">

                                </select>
                                <input type="hidden" id="to_class_id" name="classroom_id">
                            </div>
                        </div>
                        <div class="col-lg-6 p-t-20" id="allstudent_show" style="display: none;">
                            <div
                                class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
                                <select id="allstudent" class="form-control" name="student_id">

                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6 p-t-20" id="allusers_show2">
                            <div
                                class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
                                <select name="student_report_id" id="student_report_id" class="form-control">
                                    <option label="Select Student Report" value=""></option>
                                    @foreach ($record as $item)
                                    <option value="{{$item->student_report_id}}">{{$item->report_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-12 p-t-20">
                            <label class="control-label col-md-3">Attachment
                            </label>
                            <div class="col-md-12">
                                <div class="dropzone">
                                    <!-- <input required type="file" id="file" name="issueDetailFile" accept=".docx, .pdf"> -->
                                    <div class="col text-center" id="dropContainer"
                                        style="background-color: #fafafa; border-style: dotted; border-color: #cbcbcb; border-width: 2px; height=102px">
                                        <label class="form-label " style="font-size: 32px" for="customFile">DRAG & DROP YOUR
                                            FILES &nbsp OR &nbsp </label>
                                        <input required class="" type="file" accept=".doc, .docx, .pdf" class="form-control" id="file" style="font-size: 21px" name="issuefile" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 p-t-20 text-center">
                            <button type="submit" id="replyOrSubmit"
                                class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 m-r-20 btn-pink">Reply</button>
                            <button type="button"
                                class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-default"
                                onclick="location.href='{{ url('allissue') }}'">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript">
        $("#solved").click(function() {
            $("#issueStatus").val("Solved");
        });
        $("#approved").click(function() {
            $("#issueStatus").val("Approved");
        });



        $('#issuerelated').on('change', function() {
            var usertype = $(this).val();
            // alert(usertype);
            $("#to_issueRelated_id").val(usertype);
            $.ajax({
                type: 'POST',
                url: '{{ route('get_principle_specific') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    usertype: usertype
                },
                success: function(response) 
                {
                    var len = response.length;
                    $("#allusers").empty();
                    if (usertype == "student") 
                    {
                        $("#allusers_show").hide();
                        $("#classes_show").show();
                        $("#allstudent_show").show();
                        $("#allclass").empty();
                        // alert(response);
                        var output2 = '<option selected disbaled>Select Class</option>';
                        for (var i = 0; i < len; i++) 
                        {
                            var classroom_name = response[i]['classroom_name'];
                            var classroom_id = response[i]['classroom_id'];
                            output2 += '<option value="' + classroom_id + '">' + classroom_name 
                                '</option>';
                            $("#allclass").html(output2);
                        }
                    } 
                    else 
                    {
                        var output1 = '<option label="Select Person" value=""></option>';
                        $("#allusers_show").show();
                        $("#classes_show").hide();
                        $("#allstudent_show").hide();

                        for (var i = 0; i < len; i++) 
                        {
                            var first_name = response[i]['first_name'];
                            var last_name = response[i]['last_name'];
                            var user_id = response[i]['id'];
                            output1 += '<option value="' + user_id + '">' + first_name + ' ' +
                                last_name +
                                '</option>';
                            $("#allusers").html(output1);
                        }
                    }
                    $("#replyOrSubmit").text("SUBMIT");
                }
            });
        });


        $('#allclass').on('change', function() 
        {
            var classroom_id = $(this).val();
            // alert(classroom_id);
            $("#to_class_id").val(classroom_id);
            $.ajax({
                type: 'POST',
                url: '{{ route('get_student_issue_specific') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    classroom_id: classroom_id
                },
                success: function(response) 
                {
                    var len = response.length;
                    $("#allstudent").empty();
                    var output =
                        '<option selected disbaled>Select Student</option><option value="-15">All</option>';
                    for (var i = 0; i < len; i++) 
                    {
                        var first_name = response[i]['first_name'];
                        var last_name = response[i]['last_name'];
                        var student_id = response[i]['student_detail_id'];
                        output += '<option value="' + student_id + '">' + first_name + ' ' +
                            last_name +
                            '</option>';
                        $("#allstudent").html(output);
                    }
                }
            });
        });

    </script>
@endsection
