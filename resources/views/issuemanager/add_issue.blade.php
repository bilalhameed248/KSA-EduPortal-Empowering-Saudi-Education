@extends('superadmin.app')
@section('content')
    <div class="page-bar">
        <div class="page-title-breadcrumb">
            <div class=" pull-left">
                <div class="page-title">Add Issue</div>
            </div>
            <ol class="breadcrumb page-breadcrumb pull-right">
                <li><i class="fa fa-home"></i>&nbsp;<a class="parent-item" href="{{ url('allissue') }}">Home</a>&nbsp;<i
                        class="fa fa-angle-right"></i>
                </li>
                <li><a class="parent-item" href="">Issues Management</a>&nbsp;<i class="fa fa-angle-right"></i>
                </li>
                <li class="active">Add Issue</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="card-head">
                    <header>Issue Details</header>
                </div>
                <form method="POST" action="{{ route('add_issue') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body row">
                        <div class="col-lg-12 p-t-20">
                            <div class="mdl-textfield mdl-js-textfield txt-full-width">
                                <textarea required class="mdl-textfield__input" rows="4" name="issue_desc"
                                    id="text7"></textarea>
                                <label class="mdl-textfield__label" for="text7">Issue Description</label>
                            </div>
                        </div>
                        <div class="col-lg-6 p-t-20">
                            <div
                                class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
                                <input class="mdl-textfield__input" type="text" id="list2" value="" readonly tabIndex="-1">
                                <label for="list2" class="pull-right margin-0">
                                    <i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
                                </label>
                                <label for="list2" class="mdl-textfield__label">Issue Type</label>
                                <ul data-mdl-for="list2" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
                                    <li class="mdl-menu__item" value="1" id="ptm">PTM</li>
                                    <li class="mdl-menu__item" value="2" id="student_grade">Student Grades</li>
                                    <input type="hidden" id="issueType" name="issueType">
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6 p-t-20">
                            <div
                                class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
                                <select required id="issuerelated" name="issueRelated" class="form-control" style="height: auto;">
                                    <option>Select issue related</option>
                                    <option value="principle">Principle</option>
                                    <option value="viceprinciple">Vice Principle</option>
                                    <option value="headmaster">Head Master</option>
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
                                <select  name="user_id" id="allusers" class="form-control">

                                </select>
                                <input type="hidden" id="to_principle_id" name="principle_id">
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
                                {{-- <input type="hidden" id="to_student_id" name="student_detail_id"> --}}
                            </div>
                        </div>
                        <div class="col-lg-12 p-t-20">
                            <label class="control-label col-md-3">Attachment
                            </label>
                            <div class="col-md-12">
                                <div class="dropzone">
                                    <style>
                                        .dropzone {
                                            min-height: 150px;
                                            border: 2px dashed rgba(0, 0, 0, 0.3);
                                            background: white;
                                            padding: 41px 20px;
                                        }

                                    </style>
                                    {{-- <input required type="file" id="file" name="issuefile" accept=".docx, .pdf"> --}}
                                    <div class="col text-center" id="dropContainer">
                                        {{-- <style>
                                            input#file {
                                                z-index: -1 !important;
                                                position: absolute;
                                                top: 5px;
                                                right: 328px;
                                            }

                                            div#dropContainer {
                                                z-index: 13 !important;

                                                position: relative;
                                            }

                                            label.form-label {
                                                background: #ffffff;
                                            }

                                        </style> --}}
                                        <label class="form-label " style="font-size: 28px" for="customFile">Drop files here
                                            to upload&nbsp &nbsp </label>
                                        <input required class="" type="file" accept=".doc, .docx, .pdf" class="form-control"
                                            id="file" style="font-size: 17px" name="issuefile" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- //Select Save File -->
                        <div class="col-lg-6 p-t-20">
                            <div
                                class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
                                <select required id="report_id" name="report_id" class="form-control">
                                    <option selected disbaled>Select Reports</option>
                                    @foreach($my_save_reports as $report)
                                    <option value="{{$report->student_report_id}}">{{$report->report_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- //Select Save File END -->
                        <div class="col-lg-12 p-t-20 text-center">
                            <button type="submit"
                                class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 m-r-20 btn-pink">Submit</button>
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
        $('#issuerelated').on('change', function() {
            var usertype = $(this).val();
            
            $("#to_issueRelated_id").val(usertype);
            $.ajax({
                type: 'POST',
                url: '{{ route('get_principle') }}',
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
                        var output = '<option selected disbaled>Select Person</option>';
                        $("#allusers_show").show();
                        $("#classes_show").hide();
                        $("#allstudent_show").hide();

                        for (var i = 0; i < len; i++) 
                        {
                            var first_name = response[i]['first_name'];
                            var last_name = response[i]['last_name'];
                            var user_id = response[i]['id'];
                            output += '<option value="' + user_id + '">' + first_name + ' ' +
                                last_name +
                                '</option>';
                                $("#allusers").html(output);
                        }
                    }    
                }
            });
        });
        $('#allclass').on('change', function() {
            var classroom_id = $(this).val();
            // alert(classroom_id);
            $("#to_class_id").val(classroom_id);
            $.ajax({
                type: 'POST',
                url: '{{ route('get_student_issue') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    classroom_id: classroom_id
                },
                success: function(response) 
                {
                    
                    var len = response.length;
                    $("#allstudent").empty();
                    var output = '<option selected disbaled>Select Student</option><option value="-15">All</option>';
                    for (var i = 0; i < len; i++)
                        {
                            var first_name = response[i]['first_name'];
                            var last_name = response[i]['last_name'];
                            var student_id = response[i]['student_id'];
                            output += '<option value="' + student_id + '">'  + first_name + ' ' +
                                last_name +
                                '</option>';
                                $("#allstudent").html(output);
                        }
                }
            });
        });

    </script>
    <script type="text/javascript">
        $("#ptm").click(function() {
            $("#issueType").val("PTM");
        });
        $("#student_grade").click(function() {
            $("#issueType").val("Student Grades");
        });
        $("#principle").click(function() {
            $("#issueRelated").val("principle");
        });
        $("#viceprinciple").click(function() {
            $("#issueRelated").val("viceprinciple");
        });
        $("#headmaster").click(function() {
            $("#issueRelated").val("headMaster");
        });
        $("#leader").click(function() {
            $("#issueRelated").val("leader");
        });
        $("#supervisor").click(function() {
            $("#issueRelated").val("supervisor");
        });
        $("#teacher").click(function() {
            $("#issueRelated").val("teacher");
        });

    </script>
    <script type="text/javascript">
        // dragover and dragenter events need to have 'preventDefault' called
        // in order for the 'drop' event to register. 
        // See: https://developer.mozilla.org/en-US/docs/Web/Guide/HTML/Drag_operations#droptargets
        dropContainer.ondragover = dropContainer.ondragenter = function(evt) {
            evt.preventDefault();
        };

        dropContainer.ondrop = function(evt) {
            // pretty simple -- but not for IE :(

            fileInput.files = evt.dataTransfer.files;

            // If you want to use some of the dropped files
            const dT = new DataTransfer();
            dT.items.add(evt.dataTransfer.files[0]);
            dT.items.add(evt.dataTransfer.files[3]);
            fileInput.files = dT.files;

            evt.preventDefault();
        };

    </script>
@endsection
