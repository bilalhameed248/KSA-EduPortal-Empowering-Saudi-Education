@extends('superadmin.app')
@section('content')
<div class="page-bar">
	<div class="page-title-breadcrumb">
		<div class=" pull-left">
			<div class="page-title">Add Grades</div>
		</div>
		<ol class="breadcrumb page-breadcrumb pull-right">
			<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
					href="index.html">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li><a class="parent-item" href="">Student Management</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li class="active">Add Grades</li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="card-box">
			<div class="card-head">
				<header>Students Details</header>
			</div>
			<div class="card-body row">
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<input class="mdl-textfield__input" type="text" id="listBranch" value="" readonly
							tabIndex="-1">
						<label for="listBranch" class="pull-right margin-0">
							<i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
						</label>
						<label for="listBranch" class="mdl-textfield__label">Select ClassRoom</label>
						<ul data-mdl-for="listBranch" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
							<li class="mdl-menu__item" data-val="DE">4th Grade-Section1</li>
							<li class="mdl-menu__item" data-val="BY">5th Grade-Section2</li>
						</ul>
					</div>
				</div>
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<input class="mdl-textfield__input" type="text" id="listSubject" value="" readonly
							tabIndex="-1">
						<label for="listSubject" class="pull-right margin-0">
							<i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
						</label>
						<label for="listSubject" class="mdl-textfield__label">Select ClassRoom</label>
						<ul data-mdl-for="listSubject" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
							<li class="mdl-menu__item" data-val="DE">Mathematics</li>
						</ul>
					</div>
				</div>
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<input class="mdl-textfield__input" type="text" id="listVP" value="" readonly
							tabIndex="-1">
						<label for="listVP" class="pull-right margin-0">
							<i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
						</label>
						<label for="listVP" class="mdl-textfield__label">Select Grades Type</label>
						<ul data-mdl-for="listVP" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
							<li class="mdl-menu__item" data-val="DE">Mid Term</li>
							<li class="mdl-menu__item" data-val="BY">Final Term</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="tabbable-line">
			<div class="tab-content">
				<div class="tab-pane active fontawesome-demo" id="tab1">
					<div class="row">
						<div class="col-md-12">
							<div class="card card-box">
								<div class="card-head">
									<header>All Students</header>
									<div class="tools">
										<a class="fa fa-repeat btn-color box-refresh"
											href="javascript:;"></a>
										<a class="t-collapse btn-color fa fa-chevron-down"
											href="javascript:;"></a>
									</div>
								</div>
								<div class="card-body ">
									<div class="table-scrollable">
										<table
											class="table table-striped table-bordered table-hover table-checkable order-column valign-middle"
											id="example4">
											<thead>
												<tr>
													<th></th>
													<th> First Name </th>
													<th> Last Name </th>
													<th> Marks </th>
												</tr>
											</thead>
											<tbody>
												<tr class="odd gradeX">
													<td class="patient-img">
														<img src="public/img/prof/prof1.jpg"
															alt="">
													</td>
													<td>ABC</td>
													<td class="left">XYZ</td>
													<td class="left"></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="col-lg-12 p-t-20 text-center">
								<button type="button"
									class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 m-r-20 btn-pink">Export</button>
								<button type="button"
									class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-default">Cancel</button>
							</div>
						</div>
					</div>
				</div>						
			</div>
		</div>
	</div>
</div>
@endsection