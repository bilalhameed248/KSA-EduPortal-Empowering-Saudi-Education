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
                <form method="POST" action="{{ route('update_issue_student') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body row">
                        <div class="col-lg-12 p-t-20">
                            <div class="mdl-textfield mdl-js-textfield txt-full-width">
                                <textarea class="mdl-textfield__input" rows="4" name="issue_text" id="text7" value="{{$specific_issues->issue_text}}"
                                    readonly>{{ $specific_issues->issue_text }}</textarea>
                                <label class="mdl-textfield__label" for="text7">Issue Description</label>
                                <input type="hidden" value="{{ $specific_issues->issue_id }}" name="specific_issues_id">
                            </div>
                        </div>
                        <div class="col-lg-6 p-t-20">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
                                <input class="mdl-textfield__input" name="issue_type" type="text" id="txtCourseCode"
                                    value="{{ $specific_issues->issue_type }}" readonly>
                                <label class="mdl-textfield__label">Issue Type</label>
                            </div>
                        </div>
                        <div class="col-lg-6 p-t-20">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
                                <input class="mdl-textfield__input"  type="text" id="txtCourseCode"
                                    value="{{ $specific_issues->issue_date }}" readonly>
                                <label class="mdl-textfield__label">Issue Date</label>
                            </div>
                        </div>
                        <div class="col-lg-6 p-t-20">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
                                <input class="mdl-textfield__input" type="text" id="txtCourseCode"
                                    value="{{ $specific_issues->first_name . ' ' . $specific_issues->last_name }}"
                                    readonly>
                                <label class="mdl-textfield__label">Creater</label>
                            </div>
                        </div>
                        <div class="col-lg-3 p-t-20">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
                                 <i class="fa fa-file-pdf-o" style="font-size:20px;color:red"></i>
                                <b> <a href="{{ url('/viewPdfFile/'.$specific_issues->issue_file) }}">{{$specific_issues->issue_file}}</a> </b>
                            </div>
                        </div>
                        <div class="col-lg-3 p-t-20">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
                                <i class="fa fa-file-pdf-o" style="font-size:20px;color:red"></i>
                                <b> <a href="{{ url('/viewPdfFile/'.$specific_issues->report_name) }}">{{$specific_issues->report_name}}</a> </b>
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
                                    <li class="mdl-menu__item" value="1" id="accepted">Accepted</li>
                                    <li class="mdl-menu__item" value="2" id="rejected">Rejected</li>
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
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 p-t-20" id="allusers_show">
                            <div
                                class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
                                <select name="user_id" id="allusers" class="form-control">

                                </select>
                                
                            </div>
                        </div>

                        <div class="col-lg-12 p-t-20 text-center">
                            <button type="submit"
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
        $("#accepted").click(function() {
            $("#issueStatus").val("accepted");
        });
        $("#rejected").click(function() {
            $("#issueStatus").val("rejected");
        });

    </script>
    <script type="text/javascript">
        $('#issuerelated').on('change', function() {
            var usertype = $(this).val();
            $.ajax({
                type: 'POST',
                url: '{{ route('get_principle') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    usertype: usertype
                },
                success: function(response) {
                    var len = response.length;
                    $("#allusers").empty();
                    var output = '<option selected disbaled>Select Person</option>';
                    $("#allusers_show").show();                  

                    for (var i = 0; i < len; i++) {
                        var first_name = response[i]['first_name'];
                        var last_name = response[i]['last_name'];
                        var user_id = response[i]['id'];
                        output += '<option value="' + user_id + '">' + first_name + ' ' +
                            last_name +
                            '</option>';
                        $("#allusers").html(output);

                    }
                }
            });
        });

    </script>
@endsection
