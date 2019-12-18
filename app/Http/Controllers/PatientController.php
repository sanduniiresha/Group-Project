<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Patients;
use Illuminate\Support\Facades\Storage;
use File;
use App\Appointment;
use App\Medicine;
use App\Prescription;
use App\Prescription_Medicine;
use DB;
use stdClass;
use Carbon\Carbon;
class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        return view('patient.register_patient', ['title' => $user->name]);
    }

    public function searchPatient(Request $request){
        return view('patient.search_patient_view',['title'=>"Search Patient","search_result"=>""]);
    }

    public function patientData(Request $request){
        if($request->cat=="name"){
            $result=Patients::where('name','LIKE','%'.$request->keyword.'%')->get();
        }
        if($request->cat=="nic"){
            $result=Patients::where('nic','LIKE','%'.$request->keyword.'%')->get();

        }
        if($request->cat=="telephone"){
            $result=Patients::where('telephone','LIKE','%'.$request->keyword.'%')->get();
        }
        return view('patient.search_patient_view',["title"=>"Search Results","search_result"=>$result]);
    }

    public function register_patient(Request $request)
    {
        //dd($request->all());


        try {
            $patient = new Patients;
            $today_regs = (int) Patients::whereDate('created_at', date("Y-m-d"))->count();

            $number = $today_regs + 1;
            $year = date('Y') % 100;
            $month = date('m');
            $day = date('d');

            $reg_num = $year . $month . $day . $number;

            $date=date_create($request->reg_pbd);

            $patient->id = $reg_num;
            $patient->name = $request->reg_pname;
            $patient->address = $request->reg_paddress;
            $patient->occupation = $request->reg_poccupation;
            $patient->sex = $request->reg_psex;
            $patient->bod = date_format($date,"Y-m-d");
            $patient->telephone = $request->reg_ptel;
            $patient->nic = $request->reg_pnic;
            $patient->image = $reg_num.".png";
            $patient->save();
            session()->flash('regpsuccess', 'Patient ' . $request->reg_pname . ' Registered Successfully !');
            session()->flash('pid', "$reg_num");

            $image = $request->regp_photo;  // your base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            \Storage::disk('local')->put("public/".$reg_num.".png", base64_decode($image));

            // Log Activity
            activity()->performedOn($patient)->withProperties(['Patient ID' => $reg_num])->log('Patient Registration Success');

            return redirect()->back();
        } catch (\Exception $e) {
            // do task when error
            $error = $e->getCode();
            // log activity
            activity()->performedOn($patient)->withProperties(['Error Code' => $error, 'Error Message' => $e->getMessage()])->log('Patient Registration Failed');

            if ($error == '23000') {
                session()->flash('regpfail', 'Patient ' . $request->reg_pname . ' Is Already Registered..');
                return redirect()->back();
            }
        }
    }

    public function validateAppNum(Request $request)
    {
        $num = $request->number;
        $numlength = strlen((string) $num);
        if ($numlength < 5) {
            $rec = DB::table('appointments')->join('patients', 'appointments.patient_id', '=', 'patients.id')->select('patients.name as name', 'appointments.number as num', 'appointments.patient_id as pnum')->whereRaw(DB::Raw("Date(appointments.created_at)=CURDATE() and appointments.number='$num'"))->first();
            if ($rec) {
                return response()->json([
                    "exist" => true,
                    "name" => $rec->name,
                    "appNum" => $rec->num,
                    "pNum"=>$rec->pnum
                ]);
            } else {
                return response()->json([
                    "exist" => false,
                ]);
            }
        } else {
            $rec = DB::table('appointments')->join('patients', 'appointments.patient_id', '=', 'patients.id')->select('patients.name as name', 'appointments.number as num', 'appointments.patient_id as pnum')->whereRaw(DB::Raw("Date(appointments.created_at)=CURDATE() and appointments.patient_id='$num'"))->first();
            if ($rec) {
                return response()->json([
                    "exist" => true,
                    "name" => $rec->name,
                    "appNum" => $rec->num,
                    "pNum"=>$rec->pnum
                ]);
            } else {
                return response()->json([
                    "exist" => false,
                ]);
            }
        }
    }

    public function check_patient_view()
    {
        $user = Auth::user();
        return view('patient.check_patient_intro', ['title' => $user->name]);
    }

    public function checkPatient(Request $request)
    {
        $appointment=Appointment::where('number',$request->appNum)->where('created_at','>=', date('Y-m-d').' 00:00:00')->where('patient_id',$request->pid)->first();
        $patient=Patients::find($appointment->patient_id);

        $user = Auth::user();


        $pBloodPressure = new stdClass;
        $pBloodPressure->sys = 120;
        $pBloodPressure->dia = 80;
        $pBloodPressure->date = '2019-02-28';

        $pBloodSugar = new stdClass;
        $pBloodSugar->value = 100;
        $pBloodSugar->date = '2019-02-28';

        $pCholestrol = new stdClass;
        $pCholestrol->value = 100;
        $pCholestrol->date = '2019-02-28';

        $pHistory = new stdClass;


        return view('patient.check_patient_view', [
            'title' => ucWords($user->name),
            'appNum' => $request->appNum,
            'pName' => $appointment->patient->name,
            'pSex' => $appointment->patient->sex,
            'pAge' => $patient->getAge(),
            'pCholestrol' => $pCholestrol,
            'pBloodSugar' => $pBloodSugar,
            'pBloodPressure' => $pBloodPressure,
            'pHistory' => $pHistory,
            'inpatient'=>$appointment->admit,
            'pid'=>$appointment->patient->id,
            'medicines'=>Medicine::all(),
        ]);
    }
    public function markInPatient(Request $request){
        $pid=$request->pid;
        $app_num=$request->app_num;
        $user = Auth::user();
        $appointment=Appointment::where('number',$app_num)->where('created_at','>=', date('Y-m-d').' 00:00:00')->where('patient_id',$pid)->first();
        if($appointment->admit=="NO"){
            $appointment->admit="YES";
            $appointment->doctor=$user->id;
            $appointment->save();
            return response()->json([
                'success' => true,
                'appid'=>$appointment->id,
                'pid' => $pid,
                'app_num' => $app_num,
            ]);
        }
    }

    public function checkPatientSave(Request $request){
        $user = Auth::user();
        $presc=new Prescription;
        $presc->doctor_id=$user->id;
        $presc->patient_id=$request->patient_id;
        $presc->diagnosis=$request->diagnosis;
        $presc->cholestrol=$request->cholestrol;
        $presc->bp=$request->pressure;
        $presc->blood_sugar=$request->glucose;
        $presc->medicines=json_encode($request->medicines);
        $presc->save();

        foreach ($request->medicines as $medicine) {
            $med=Medicine::where('name_english',strtolower($medicine['name']))->first();
            $pres_med=new Prescription_Medicine;
            $pres_med->medicine_id=$med->id;
            $pres_med->prescription_id=$presc->id;
            $pres_med->save();
        }
    }

    public function create_channel_view()
    {
        $user = Auth::user();
        $appointments = DB::table('appointments')->join('patients', 'appointments.patient_id', '=', 'patients.id')->select('patients.name', 'appointments.number', 'appointments.patient_id')->whereRaw(DB::Raw('Date(appointments.created_at)=CURDATE()'))->orderBy('appointments.created_at', 'desc')->get();

        return view('patient.create_channel_view', ['title' => $user->name, 'appointments' => $appointments]);
    }

    public function regcard($id)
    {
        $patient = Patients::find($id);
        $url = Storage::url($id.'.png');
        $data = [
            'name' => $patient->name,
            'sex' => $patient->sex,
            'id' => $patient->id,
            'reg' => explode(" ", $patient->created_at)[0],
            'dob'=>'1997-06-25',
            'url'=>$url,
        ];
        return view('patient.patient_reg_card', $data);
    }

    public function register_in_patient_view()
    {
        $user = Auth::user();
        return view('patient.register_in_patient_view', ['title' => "Register Inpatient"]);
    }

    public function makeChannel(Request $request)
    {
        $regNum = $request->regNum;
        $patient = Patients::find($regNum);
        if ($patient) {

            $num = DB::table('appointments')->select('id')->whereRaw(DB::raw("date(created_at)=CURDATE()"))->count() + 1;

            return response()->json([
                'exist' => true,
                'name' => $patient->name,
                'sex' => $patient->sex,
                'address' => $patient->address,
                'occupation' => $patient->occupation,
                'telephone' => $patient->telephone,
                'nic' => $patient->nic,
                'age' => $patient->age,
                'id' => $patient->id,
                'appNum' => $num
            ]);
        } else {
            return response()->json([
                'exist' => false
            ]);
        }
    }

    public function addChannel(Request $request)
    {
        $app = new Appointment;
        $num = DB::table('appointments')->select('id')->whereRaw(DB::raw("date(created_at)=CURDATE()"))->count() + 1;
        $pid = $request->id;
        $patient = Patients::find($pid);

        $app->number = $num;
        $app->patient_id = $pid;
        $app->save();
        try {
            $app->save();
            return response()->json([
                'exist' => true,
                'name' => $patient->name,
                'id' => $patient->id,
                'appID' => $app->id,
                'appNum' => $num
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'exist' => false,
            ]);
        }
    }
    
    public function issueMedicineView()
    {
        $user = Auth::user();
        return view('patient.issueMedicineView', ['title' => "Issue Medicine"]);
    }

}
