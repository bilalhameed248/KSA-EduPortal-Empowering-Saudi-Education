<!DOCTYPE html>
<html lang="en">
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta name="description" content="Responsive Admin Template" />
    <meta name="author" content="SmartUniversity" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Smart School</title>
    <!-- google font -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet" type="text/css" />
    <!-- icons -->
    <link href="{{ asset('public/fonts/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('public/fonts/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/fonts/material-design-icons/material-icon.css') }}" rel="stylesheet"
        type="text/css" />
    <!--bootstrap -->
    <link href="{{ asset('public/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- data tables -->
    <link href="{{ asset('public/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <!-- Material Design Lite CSS -->
    <link rel="stylesheet" href="{{ asset('public/plugins/material/material.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/material_style.css') }}">
    <!-- Theme Styles -->
    <link href="{{ asset('public/css/theme/light/theme_style.css') }}" rel="stylesheet" id="rt_style_components"
        type="text/css" />
    <link href="{{ asset('public/css/theme/light/style.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/css/responsive.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/css/theme/light/theme-color.css') }}" rel="stylesheet" type="text/css" />
    <!-- dropzone -->
    <link href="{{ asset('public/plugins/dropzone/dropzone.css') }}" rel="stylesheet" media="screen">
    <!-- Date Time item CSS -->
    <link rel="stylesheet"
        href="{{ asset('public/plugins/material-datetimepicker/bootstrap-material-datetimepicker.css') }}" />
    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('public/img/favicon.ico') }}" />
</head>
<!-- END HEAD -->

<body
    class="page-header-fixed sidemenu-closed-hidelogo page-content-white page-md header-white white-sidebar-color logo-indigo">
    <div class="page-wrapper">
        <!-- start header -->
        <div class="page-header navbar navbar-fixed-top">
            <div class="page-header-inner ">
                <!-- logo start -->
                <div class="page-logo">
                    <a href="{{ url('/home') }}">
                        <span class="logo-icon material-icons fa-rotate-45">school</span>
                        <span class="logo-default">Smart</span> </a>
                </div>
                <!-- logo end -->
                <ul class="nav navbar-nav navbar-left in">
                    <li><a href="#" class="menu-toggler sidebar-toggler"><i class="icon-menu"></i></a></li>
                </ul>
                <form class="search-form-opened" action="#" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search..." name="query">
                        <span class="input-group-btn">
                            <a href="javascript:;" class="btn submit">
                                <i class="icon-magnifier"></i>
                            </a>
                        </span>
                    </div>
                </form>
                <!-- start mobile menu -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
                    data-target=".navbar-collapse">
                    <span></span>
                </a>
                <!-- end mobile menu -->
                <!-- start header menu -->
                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">


                        <!-- start notification dropdown -->
                        <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">

                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                                data-close-others="true">
                                <i class="fa fa-bell-o"></i>
                                <span class="badge headerBadgeColor1">
                                    {{ Auth::user()->unreadNotifications->count() }}
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="external">
                                    <h3><span class="bold">Notifications</span></h3>
                                    <span class="notification-label purple-bgcolor">New
                                        {{ Auth::user()->unreadNotifications->count() }}</span>
                                        
                                </li>
                                <li>
                                    <ul class="dropdown-menu-list small-slimscroll-style" data-handle-color="#637283">
                                        <a href="{{route('markAsRead')}}" class="float-right text-light-dark">Mark All As Read</a>
                                        @foreach (Auth::user()->notifications as $notification)
                                            <div class="col-lg-3 col-sm-3 col-3 text-center">
                                                <img src="{{ url('https://lh3.googleusercontent.com/-Crkh9ZylQRE/Xm5hfRGJJMI/AAAAAAAABGs/yt8O84Z8_DIJRTkMsLAvOFh3BtOuCQSXgCK8BGAsYHg/s0/2020-03-15.png') }}"
                                                    alt="User" class="w-50 rounded-circle">
                                            </div>
                                            <div class="col-lg-8 col-sm-8 col-8">
                                                <strong class="text-info">
                                                    
                                                    <a href="/allissue" data-notif-id="{{$notification->id}}">
                                                        {{ $notification->data['letter']['title'] }}</a>
                                                        <!-- <a href="/deleteNotification" target="_self" class="badge badge-danger" style="margin-left: 5%;">Delete
                                                        </a> -->
                                                </strong>
                                                </a>
                                                <div>
                                                    {{ $notification->data['letter']['body'] }}
                                                </div>
                                                <small style="color: blue;">{{ $notification->updated_at }}</small>
                                            </div>
                                        @endforeach
                                        {{-- @php
                                            $notify = DB::table('issues')
                                                ->where('issue_related', '=', 'principle')
                                                ->orderBy('issue_id', 'DESC')
                                                ->get();
                                        @endphp
                                        @if (Auth::user()->usertype == 'principle')
                                            <li>
                                                @foreach ($notify as $key => $value)
                                                    <a href="/allissue">
                                                        <span class="time">{{ $value->created_at }}</span>
                                                        <span class="details">
                                                            <span class="notification-icon circle deepPink-bgcolor"><i
                                                                    class="fa fa-check"></i></span>
                                                            {{ $value->issue_text }}!. </span>
                                                    </a>
                                                @endforeach
                                            </li>
                                        @endif --}}

                                        {{-- <li>
                                            <a href="javascript:;">
                                                <span class="time">just now</span>
                                                <span class="details">
                                                    <span class="notification-icon circle deepPink-bgcolor"><i
                                                            class="fa fa-check"></i></span>
                                                    Congratulations!. </span>
                                            </a>
                                        </li> --}}
                                        {{-- <li>
                                            <a href="javascript:;">
                                                <span class="time">3 mins</span>
                                                <span class="details">
                                                    <span class="notification-icon circle purple-bgcolor"><i
                                                            class="fa fa-user o"></i></span>
                                                    <b>John Micle </b>is now following you. </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">7 mins</span>
                                                <span class="details">
                                                    <span class="notification-icon circle blue-bgcolor"><i
                                                            class="fa fa-comments-o"></i></span>
                                                    <b>Sneha Jogi </b>sent you a message. </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">12 mins</span>
                                                <span class="details">
                                                    <span class="notification-icon circle pink"><i
                                                            class="fa fa-heart"></i></span>
                                                    <b>Ravi Patel </b>like your photo. </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">15 mins</span>
                                                <span class="details">
                                                    <span class="notification-icon circle yellow"><i
                                                            class="fa fa-warning"></i></span> Warning! </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">10 hrs</span>
                                                <span class="details">
                                                    <span class="notification-icon circle red"><i
                                                            class="fa fa-times"></i></span> Application error. </span>
                                            </a>
                                        </li> --}}
                                    </ul>
                                    <div class="dropdown-menu-footer">
                                        <a href="javascript:void(0)"> All notifications </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- end notification dropdown -->

                        <!-- start manage user dropdown -->
                        <li class="dropdown dropdown-user">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                                data-close-others="true">
                                <img alt="" class="img-circle" src="public/img/dp.jpg" />
                                <span class="username username-hide-on-mobile">
                                    {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }} </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
                                    <a href="{{ url('editProfile') }}">
                                        <i class="icon-user"></i> Profile </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="icon-settings"></i> Settings
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="icon-logout"></i> Log Out </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                        <!-- end manage user dropdown -->

                    </ul>
                </div>
            </div>
        </div>
        <!-- end header -->
        <!-- start page container -->
        <div class="page-container">
            <!-- start sidebar menu -->
            <div class="sidebar-container">
                <div class="sidemenu-container navbar-collapse collapse fixed-menu">
                    <div id="remove-scroll" class="left-sidemenu">
                        <ul class="sidemenu  page-header-fixed slimscroll-style" data-keep-expanded="false"
                            data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                            <li class="sidebar-toggler-wrapper hide">
                                <div class="sidebar-toggler">
                                    <span></span>
                                </div>
                            </li>
                            <li class="sidebar-user-panel">
                                <div class="user-panel">
                                    <div class="pull-left image">
                                        <img src="public/img/dp.jpg" class="img-circle user-img-circle"
                                            alt="User Image" />
                                    </div>
                                    <div class="pull-left info">
                                        <p> {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }} </p>

                                    </div>
                                </div>
                            </li>

                            @if (Auth::user()->user_type == 'superadmin')
                                <li class="nav-item active open">
                                    <a href="#" class="nav-link nav-toggle"> <i class="material-icons">person</i>
                                        <span class="title">User Management</span><span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="nav-item">
                                            <a href="{{ url('alluser') }}" class="nav-link "> <span class="title">All
                                                    Users</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ url('adduser') }}" class="nav-link "> <span class="title">Add
                                                    User</span>
                                            </a>
                                        </li>
                                        <!-- <li class="nav-item">
                                        <a href="{{ url('edituser') }}" class="nav-link "> <span class="title">Edit
                                                User</span>
                                        </a>
                                    </li> -->
                                        <li class="nav-item">
                                            <a href="{{ url('definesmc') }}" class="nav-link "> <span
                                                    class="title">Define
                                                    SMC</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @elseif(Auth::user()->user_type=="SMC")
                                <li class="nav-item active open">
                                    <a href="#" class="nav-link nav-toggle"><i class="material-icons">business</i>
                                        <span class="title">Branch Management</span><span class="arrow"></span></a>
                                    <ul class="sub-menu">
                                        <li class="nav-item">
                                            <a href="{{ url('addbranch') }}" class="nav-link "> <span
                                                    class="title">Add Branch</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ url('allbranch') }}" class="nav-link "> <span
                                                    class="title">All
                                                    Branches</span>
                                            </a>
                                        </li>

                                        <!-- <li class="nav-item">
                                        <a href="{{ url('editbranch') }}" class="nav-link "> <span class="title">Edit
                                                Brach</span>
                                        </a>
                                    </li> -->

                                    </ul>
                                </li>
                            @elseif(Auth::user()->user_type=="principle")
                                <li class="nav-item active open">
                                    <a href="#" class="nav-link nav-toggle"><i class="material-icons">store</i>
                                        <span class="title">School Management</span><span class="arrow"></span></a>
                                    <ul class="sub-menu">
                                        <li class="nav-item">
                                            <a href="{{ url('addschool') }}" class="nav-link "> <span
                                                    class="title">Add School</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ url('allschool') }}" class="nav-link "> <span
                                                    class="title">All
                                                    Schools</span>
                                            </a>
                                        </li>

                                        <!-- <li class="nav-item">
                                        <a href="edit_school.html" class="nav-link "> <span class="title">Edit
                                                School</span>
                                        </a>
                                    </li> -->
                                    </ul>
                                </li>
                            @elseif(Auth::user()->user_type=="viceprinciple")
                                <li class="nav-item active open">
                                    <a href="#" class="nav-link nav-toggle"><i class="material-icons">subtitles</i>
                                        <span class="title">Blocks Management</span><span class="selected"></span><span
                                            class="arrow"></span></a>
                                    <ul class="sub-menu">
                                        <!-- <li class="nav-item active open">
                                        <a href="{{ url('addblock') }}" class="nav-link "> <span class="title">Add Block</span><span class="selected"></span>
                                        </a>
                                    </li> -->
                                        <li class="nav-item">
                                            <a href="{{ url('allblock') }}" class="nav-link "> <span
                                                    class="title">All
                                                    Blocks</span>
                                            </a>
                                        </li>

                                        <!-- <li class="nav-item">
                                        <a href="edit_block.html" class="nav-link "> <span class="title">Edit
                                                Block</span>
                                        </a>
                                    </li> -->
                                    </ul>
                                </li>
                                <li class="nav-item active show">
                                    <a href="#" class="nav-link nav-toggle"><i class="material-icons">school</i>
                                        <span class="title">Teachers Management</span><span class="arrow"></span></a>
                                    <ul class="sub-menu">
                                        <li class="nav-item">
                                            <a href="{{ url('addteacher') }}" class="nav-link "> <span
                                                    class="title">Add Teachers</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ url('allteachers') }}" class="nav-link "> <span
                                                    class="title">All
                                                    Teachers</span>
                                            </a>
                                        </li>
                                        <!-- <li class="nav-item">
                                        <a href="{{ url('editteacher') }}" class="nav-link "> <span class="title">Edit
                                                Teacher</span>
                                        </a>
                                    </li> -->
                                        <li class="nav-item">
                                            <a href="{{ url('assignteacher') }}" class="nav-link "> <span
                                                    class="title">Assign
                                                    Teacher</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ url('allassignedteachers') }}" class="nav-link "> <span
                                                    class="title">All Assigned
                                                    Teachers</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @elseif(Auth::user()->user_type=="headmaster")
                                <li class="nav-item active open">
                                    <a href="#" class="nav-link nav-toggle"><i class="material-icons">description</i>
                                        <span class="title">Grades Management</span><span class="arrow"></span></a>
                                    <ul class="sub-menu">
                                        <!-- <li class="nav-item">
                                        <a href="add_grade.html" class="nav-link "> <span class="title">Add Grade</span>
                                        </a>
                                    </li> -->
                                        <li class="nav-item">
                                            <a href="{{ url('allgrade') }}" class="nav-link "> <span
                                                    class="title">All
                                                    Grades</span>
                                            </a>
                                        </li>
                                        <!-- <li class="nav-item">
                                        <a href="edit_grade.html" class="nav-link "> <span class="title">Edit
                                                Grade</span>
                                        </a>
                                    </li> -->
                                    </ul>
                                </li>
                            @elseif(Auth::user()->user_type=="leader")
                                <li class="nav-item active open">
                                    <a href="#" class="nav-link nav-toggle"><i class="material-icons">dvr</i>
                                        <span class="title">Classes Management</span><span class="arrow"></span></a>
                                    <ul class="sub-menu">
                                        <li class="nav-item">
                                            <a href="{{ url('addclassroom') }}" class="nav-link "> <span
                                                    class="title">Add ClassRoom</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ url('allclassroom') }}" class="nav-link "> <span
                                                    class="title">All
                                                    ClassRooms</span>
                                            </a>
                                        </li>

                                        <!-- <li class="nav-item">
                                        <a href="{{ url('editclassroom') }}" class="nav-link "> <span class="title">Edit
                                                ClassRoom</span>
                                        </a>
                                    </li> -->
                                    </ul>
                                </li>
                            @elseif(Auth::user()->user_type=="teacher")
                                <li class="nav-item active show">
                                    <a href="#" class="nav-link nav-toggle"><i class="material-icons">face</i>
                                        <span class="title">Students Management</span><span class="arrow"></span></a>
                                    <ul class="sub-menu">
                                        <li class="nav-item">
                                            <a href="{{ url('addgrade') }}" class="nav-link "> <span
                                                    class="title">Add
                                                    Grades</span>
                                            </a>
                                        </li>
                                        <!-- <li class="nav-item">
                                        <a href="{{ url('viewgrades') }}" class="nav-link "> <span class="title">View 
                                                Grades</span>
                                        </a>
                                    </li> -->
                                    </ul>
                                </li>
                            @endif
                            @if (Auth::user()->user_type != 'user' && Auth::user()->user_type != 'superadmin')
                                <li class="nav-item active show">
                                    <a href="#" class="nav-link nav-toggle"><i class="material-icons">group</i>
                                        <span class="title">Issues Management</span><span class="arrow"></span></a>
                                    <ul class="sub-menu">
                                        <li class="nav-item">
                                            <a href="{{ url('addissue') }}" class="nav-link "> <span
                                                    class="title">Add
                                                    Issue</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ url('allissue') }}" class="nav-link "> <span
                                                    class="title">All
                                                    Issues</span>
                                            </a>
                                        </li>
                                        <!-- <li class="nav-item">
                                        <a href="view_issue.html" class="nav-link "> <span class="title">View 
                                                Issues</span>
                                        </a>
                                    </li> -->
                                    </ul>
                                </li>
                            @endif
                            @if (Auth::user()->user_type != 'SMC' && Auth::user()->user_type != 'student' && Auth::user()->user_type != 'superadmin')
                                <li class="nav-item active show">
                                    <a href="#" class="nav-link nav-toggle"><i class="material-icons">widgets</i>
                                        <span class="title">Reports</span><span class="arrow"></span></a>
                                    <ul class="sub-menu">
                                        <li class="nav-item">
                                            @if (Auth::user()->user_type == 'principle')
                                                <a href="{{ url('teacherreport') }}" class="nav-link "> <span
                                                        class="title">Students Report</span>
                                                </a>
                                            @elseif(Auth::user()->user_type=="viceprinciple")
                                                <a href="{{ url('vp_report') }}" class="nav-link "> <span
                                                        class="title">Students Report</span>
                                                </a>
                                            @elseif(Auth::user()->user_type=="headmaster")
                                                <a href="{{ url('hm_report') }}" class="nav-link "> <span
                                                        class="title">Students Report</span>
                                                </a>
                                            @elseif(Auth::user()->user_type=="supervisor")
                                                <a href="{{ url('supervisor_report') }}" class="nav-link "> <span
                                                        class="title">Students Report</span>
                                                </a>
                                            @elseif(Auth::user()->user_type=="leader")
                                                <a href="{{ url('l_report') }}" class="nav-link "> <span
                                                        class="title">Students Report</span>
                                                </a>
                                            @elseif(Auth::user()->user_type=="teacher")
                                                <a href="{{ url('t_report') }}" class="nav-link "> <span
                                                        class="title">Students Report</span>
                                                </a>
                                            @endif
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <!-- end sidebar menu -->
            <!-- start page content -->
            <div class="page-content-wrapper">
                <div class="page-content">
                    <!-- End header And Side Menu -->
                    @yield('content')
                    <!-- Start Footer -->
                </div>
            </div>
            <!-- end page content -->
        </div>
    </div>
    <!-- end page container -->
    <!-- start footer -->
    <div class="page-footer">
        <div class="page-footer-inner"> 2021 &copy; Smart School
            <a href="mailto:abcd@gmail.com" target="_top" class="makerCss">Smart School</a>
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>
    <!-- end footer -->
    </div>
    <!-- start js include path -->
    <script src="{{ asset('public/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('public/plugins/popper/popper.js') }}"></script>
    <script src="{{ asset('public/plugins/jquery-blockui/jquery.blockui.min.js') }}"></script>
    <script src="{{ asset('public/plugins/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
    <!-- bootstrap -->
    <script src="{{ asset('public/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <!-- Common js-->
    <script src="{{ asset('public/js/app.js') }}"></script>
    <script src="{{ asset('public/js/layout.js') }}"></script>
    <script src="{{ asset('public/js/theme-color.js') }}"></script>
    <!-- data tables -->
    <script src="{{ asset('public/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/js/pages/table/table_data.js') }}"></script>
    <!-- Material -->
    <script src="{{ asset('public/plugins/material/material.min.js') }}"></script>
    <script src="{{ asset('public/js/pages/material-select/getmdl-select.js') }}"></script>
    <script src="{{ asset('public/plugins/material-datetimepicker/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('public/plugins/material-datetimepicker/bootstrap-material-datetimepicker.js') }}">
    </script>
    <script src="{{ asset('public/plugins/material-datetimepicker/datetimepicker.js') }}"></script>
    <!-- dropzone -->
    <script src="{{ asset('public/plugins/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('public/plugins/dropzone/dropzone-call.js') }}"></script>


<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script type="text/javascript">
    @if(Session::has('message'))
        show_alert();
    @endif
function show_alert()
{
    Swal.fire(
      '{{Session::get('key')}}',
      '{{ Session::get('message') }}',
      'success'
        );
}
</script>
   
    <!-- end js include path -->
</body>

</html>
