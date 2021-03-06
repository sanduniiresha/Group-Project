<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;
use App\User;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function viewclinicreport(){
        $user = Auth::user();
        return view('reports/clinic_reports',['title' => $user->name]);
    }
    public function view_mobile_clinic_report(){
        $user = Auth::user();
        return view('reports/mobile_clinic_reports',['title' => $user->name]);
    }
    public function view_monthly_static_report(){
        $user = Auth::user();
        return view('reports/monthly_static_report',['title' => $user->name]);
    }
    public function view_out_patient_report(){
        $user = Auth::user();
        return view('reports/out_patient_report',['title' => $user->name]);
    }
    public function view_attendance_report(){
        $user = Auth::user();
        return view('reports/attendance_reports',['title' => $user->name]);
    }

    public function view_ward_report()
    {
        $user = Auth::user();
        return view('reports/ward_reports', ['title' => $user->name]);
    }
    public function gen_att_reports(Request $request){
        $user = Auth::user();
        // dd($request->start);

        $start_date=date_format(date_create($request->start),"Y/m/d");
        $end_date=date_format(date_create($request->end),"Y/m/d");
        // dd($request->start);


        if($request->type == "All"){
            $data = DB::table('attendances')
            ->join('users','attendances.user_id' , '=', 'users.id')
            ->select('users.id as id','attendances.start as start','attendances.end as end','users.name as name','users.user_type as type',
            DB::raw('count(CASE WHEN HOUR(TIMEDIFF(attendances.end, attendances.start )) > 7 THEN 1 ELSE NULL END) AS attended'),
            DB::raw('count(CASE WHEN HOUR(TIMEDIFF(attendances.end, attendances.start )) < 5 THEN 1 ELSE NULL END) AS shortleave'))
            ->whereBetween('attendances.start', [$start_date,$end_date])
            ->groupBy('name')
            ->get();
        }


        if($request->type == "My Attendance"){

            $data = DB::table('attendances')
            ->join('users','attendances.user_id' , '=', 'users.id')
            ->select('users.id as id','attendances.start as start','attendances.end as end','users.name as name','users.user_type as type',
            DB::raw('count(CASE WHEN HOUR(TIMEDIFF(attendances.end, attendances.start )) > 7 THEN 1 ELSE NULL END) AS attended'),
            DB::raw('count(CASE WHEN HOUR(TIMEDIFF(attendances.end, attendances.start )) < 5 THEN 1 ELSE NULL END) AS shortleave'))
            ->whereBetween('attendances.start', [$start_date,$end_date])
            ->where('attendances.user_id',$user->id)
            ->groupBy('name')
            ->get();
            // $data=User::find($user->id);

        }


        if($request->type == "Doctors"){
            $data = DB::table('attendances')
            ->join('users','attendances.user_id' , '=', 'users.id')
            ->select('users.id as id','attendances.start as start','attendances.end as end','users.name as name','users.user_type as type',
            DB::raw('count(CASE WHEN HOUR(TIMEDIFF(attendances.end, attendances.start )) > 7 THEN 1 ELSE NULL END) AS attended'),
            DB::raw('count(CASE WHEN HOUR(TIMEDIFF(attendances.end, attendances.start )) < 5 THEN 1 ELSE NULL END) AS shortleave'))
            ->whereBetween('attendances.start', [$start_date,$end_date])
            ->where('users.user_type','doctor')
            ->groupBy('name')
            ->get();
        }


        if($request->type == "General Staff"){
            $data = DB::table('attendances')
            ->join('users','attendances.user_id' , '=', 'users.id')
            ->select('users.id as id','attendances.start as start','attendances.end as end','users.name as name','users.user_type as type',
            DB::raw('count(CASE WHEN HOUR(TIMEDIFF(attendances.end, attendances.start )) > 7 THEN 1 ELSE NULL END) AS attended'),
            DB::raw('count(CASE WHEN HOUR(TIMEDIFF(attendances.end, attendances.start )) < 5 THEN 1 ELSE NULL END) AS shortleave'))
            ->whereBetween('attendances.start', [$start_date,$end_date])
            ->whereIn('users.user_type',['pharmacist','general'])
            ->groupBy('name')
            // ->where('users.user_type','pharmacist')
            ->get();
        }

        return view('reports/attendance-reports/all_attendance_report',['title' => $user->name,'details' => $data,'start'=>$request->start,'end'=>$request->end,'type'=>$request->type]);
    }

    public function all_print_preview(Request $request){

        $start_date=date_format(date_create($request->start),"Y/m/d");
        $end_date=date_format(date_create($request->end),"Y/m/d");

        $user = Auth::user();
        //get the attendance of all type
        if($request->type=="All"){
            $data = DB::table('attendances')
            ->join('users','attendances.user_id' , '=', 'users.id')
            ->select('users.id as id','attendances.start as start','users.name as name','users.user_type as type',
            DB::raw('count(CASE WHEN HOUR(TIMEDIFF(attendances.end, attendances.start )) > 7 THEN 1 ELSE NULL END) AS attended'),
            DB::raw('count(CASE WHEN HOUR(TIMEDIFF(attendances.end, attendances.start )) < 5 THEN 1 ELSE NULL END) AS shortleave'))
            ->whereBetween('attendances.start', [$start_date,$end_date])
            ->groupBy('name')
            ->orderBy('type')
            ->get();
        }

        //get the attendance of mine
        if($request->type == "My"){
            $data = DB::table('attendances')
            ->join('users','attendances.user_id' , '=', 'users.id')
            ->select('users.id as id','attendances.start as start','users.name as name','users.user_type as type',
            DB::raw('count(CASE WHEN HOUR(TIMEDIFF(attendances.end, attendances.start )) > 7 THEN 1 ELSE NULL END) AS attended'),
            DB::raw('count(CASE WHEN HOUR(TIMEDIFF(attendances.end, attendances.start )) < 5 THEN 1 ELSE NULL END) AS shortleave'))
            ->whereBetween('attendances.start', [$start_date,$end_date])
            ->where('attendances.user_id',$user->id)
            ->groupBy('name')
            ->orderBy('type')
            ->get();

        }

        //get the attendance of doctor
        if($request->type == "Doctors"){
            $data = DB::table('attendances')
            ->rightJoin('users','attendances.user_id' , '=', 'users.id')
            ->select('users.id as id','attendances.start as start','users.name as name','users.user_type as type',
            DB::raw('count(CASE WHEN HOUR(TIMEDIFF(attendances.end, attendances.start )) > 7 THEN 1 ELSE NULL END) AS attended'),
            DB::raw('count(CASE WHEN HOUR(TIMEDIFF(attendances.end, attendances.start )) < 5 THEN 1 ELSE NULL END) AS shortleave'))
            ->whereBetween('attendances.start', [$start_date,$end_date])
            ->where('users.user_type','doctor')
            ->groupBy('name')
            ->orderBy('type')
            ->get();

        }

        //get the attendance of staff
        if($request->type == "General"){
            $data = DB::table('attendances')
            ->join('users','attendances.user_id' , '=', 'users.id')
            ->select('users.id as id','attendances.start as start','users.name as name','users.user_type as type',
            DB::raw('count(CASE WHEN HOUR(TIMEDIFF(attendances.end, attendances.start )) > 7 THEN 1 ELSE NULL END) AS attended'),
            DB::raw('count(CASE WHEN HOUR(TIMEDIFF(attendances.end, attendances.start )) < 5 THEN 1 ELSE NULL END) AS shortleave'))
            ->whereBetween('attendances.start', [$start_date,$end_date])
            ->whereIn('users.user_type',['pharmacist','general'])
            ->groupBy('name')
            ->orderBy('type')
            // ->where('users.user_type','pharmacist')
            ->get();

        }

        //return to printing page view
        return view('reports/attendance-reports/all_print_preview',['title' => $user->name,'details' => $data]);
    }
}
