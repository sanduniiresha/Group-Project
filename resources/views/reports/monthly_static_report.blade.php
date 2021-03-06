@extends('template.main')

@section('title', $title)

@section('content_title',"REPORTS")
@section('content_description',"Personalize Your Account")
@section('breadcrumbs')

    <ol class="breadcrumb">
        <li><a href="{{route('dash')}}"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
        <li class="active">Here</li>
    </ol>
@endsection

@section('main_content')
    <?php $user = Auth::user();
    $name = $user->name;
    $user_type = $user->user_type;
    $image_path = $user->img_path;
    $outlet = 'Rural Ayruvedic Hospital Kesbawa'?>

    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Monthly Statics Reports</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form class="form-horizontal">
                        <div class="box-body">

                            <center><h3>Outpatient Department</h3></center>
                            <br>

                            <div class="form-group">
                                <label  class="col-sm-3 control-label">OPD opened dates</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control"  placeholder="enter number of dates">
                                </div>
                            </div>

                            <div class="form-group">
                                <label  class="col-sm-3 control-label">Employees Total Atendance</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control"  placeholder="total attendance">
                                </div>
                            </div>

                            <div class="form-group">
                                <label  class="col-sm-3 control-label">AVG of daily OPD patients</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="avg of opd patients">
                                </div>
                            </div>

                            <div class="form-group">
                                <label  class="col-sm-3 control-label">Value of Issued medicine</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="medicine value">
                                </div>
                            </div>

                            <div class="form-group">
                                <label  class="col-sm-3 control-label">AVG price for one patient</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="avg for one patient">
                                </div>
                            </div>
                                <br>
                                <br>

                            <div class="form-group">
                                <label  class="col-sm-4 control-label">Issuing medicines according to OPD dates</label>
                                <br>
                                <br>
                                <br>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th scope="col">Description</th>
                                                <th scope="col">Day 03</th>
                                                <th scope="col">Day 05</th>
                                                <th scope="col">Day 07</th>
                                                <th scope="col">Day 06</th>
                                                <th scope="col">Day</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            <th>No. of patients from OPD</th>
                                            <td><input type="text" name="no_patient_day3"></td>
                                            <td><input type="text" name="no_patient_day5"></td>
                                            <td><input type="text" name="no_patient_day7"></td>
                                            <td><input type="text" name="no_patient_day6"></td>
                                            <td><input type="text" name="no_patient_day"></td>
                                            <td><input type="text" name="no_patient_total"></td>
                                            </tr>
                                            <tr>
                                            <th>No. of OPD days</th>
                                            <td><input type="text" name="no_opd_day3"></td>
                                            <td><input type="text" name="no_opd_day5"></td>
                                            <td><input type="text" name="no_opd_day7"></td>
                                            <td><input type="text" name="no_opd_day6"></td>
                                            <td><input type="text" name="no_opd_day"></td>
                                            <td><input type="text" name="no_opd_day_total"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                                <br>
                                <br>

                            <center><h3>Inpatient Department</h3></center>
                            <br>

                            <div class="form-group">
                                <label  class="col-sm-3 control-label">No of wards</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="enter no of wards">
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-3 control-label">No of beds in wards</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="enter no of beds">
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-3 control-label">No of in-patients in this month</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="enter no of in-patients">
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-3 control-label">No of in-patients discharged within this month</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="enter no of patients">
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-3 control-label">No of in-patient dates</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="enter no of in-patients">
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-3 control-label">AVG no of days that in-patient spent in the hospital</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="enter no of days">
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-3 control-label">Monthly revenue for the medicines issued for the in-patients</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="enter monthly revenue for medicines">
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-3 control-label">Revenue for the medicines issued for one in-patient day</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="revenue for medicines for one in-patients day">
                                </div>
                            </div>
                            <br>
                            <br>

                            <div class="row">
                                <div class="col-sm-12">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th scope="col">The type of drug produced</th>
                                                <th scope="col">Drugs produced in the institute</th>
                                                <th scope="col">Drugs received from othe institutes</th>
                                                <th scope="col">Drugs received from Pharmaceutical Corporation</th>
                                                <th scope="col">Total Medicines Available</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            <td><input type="text" name="no_patient_day3"></td>
                                            <td><input type="text" name="no_patient_day5"></td>
                                            <td><input type="text" name="no_patient_day7"></td>
                                            <td><input type="text" name="no_patient_day6"></td>
                                            <td><input type="text" name="no_patient_day"></td>
                                            </tr>
                                            <tr>
                                            <td><input type="text" name="no_opd_day3"></td>
                                            <td><input type="text" name="no_opd_day5"></td>
                                            <td><input type="text" name="no_opd_day7"></td>
                                            <td><input type="text" name="no_opd_day6"></td>
                                            <td><input type="text" name="no_opd_day"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <br><br><br>
                            <center><h3>Dry Medicines Provision</h3></center>
                            <br><br>
                            <div class="form-group">
                                <label  class="col-sm-3 control-label">Total value for the medicines which bought this month</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="total value for medicines">
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-3 control-label">Total value for the medicines which got as donations</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="total value for medicines as donations">
                                </div>
                            </div>
                            <br>
                            <br>
                            <center><h3>Approval Board</h3></center>
                            <br>
                            <br>
                            <div class="form-group">
                                <label  class="col-sm-3 control-label">Total number of employees approved to the hospital(in all grades)</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="number of employees">
                                </div>
                            </div>

                            <div class="form-group">
                                <label  class="col-sm-3 control-label">Number of employees served for this month</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Number of employees">
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-3 control-label">Number of vacancies available at the end of the month(in all grades)</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Number of vacancies available">
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-3 control-label">Excess number of workers(Exceed the approved number of employees)</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Excess number of workers">
                                </div>
                            </div>
                            <label>Number of days of duty in the field within the month :-</label>
                            <br><br>
                            <div class="form-group">
                                <label  class="col-sm-3 control-label">Head of the institute</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Number of days of duty">
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-3 control-label">Other medical officers</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Number of days of duty">
                                </div>
                            </div>


                        </div>
                        <br>
                        <br>


                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button action="refresh()" type="submit" class="btn btn-default">Cancel</button>
                            <button type="submit" class="btn btn-info pull-right">Create report</button>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
                <!-- /.box -->
            </div>

        </div>
        <!-- /.row -->

    </section>

@endsection
