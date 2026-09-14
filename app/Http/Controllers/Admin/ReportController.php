<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
class ReportController extends Controller{
    public function index(Request $r){
        $date=$r->input('date', now()->toDateString());
        $attendances=Attendance::with('user')->whereDate('attendance_date',$date)->orderBy('scanned_at','desc')->paginate(20)->withQueryString();
        return view('admin.reports.index', compact('attendances','date'));
    }
    public function export(Request $r){
        $date=$r->input('date', now()->toDateString());
        $rows=Attendance::with('user')->whereDate('attendance_date',$date)->get();
        $csv="Nama,NISN,Kelas,Waktu,Hasil\n";
        foreach($rows as $a) $csv.="\"{$a->user->name}\",{$a->user->identifier},{$a->user->class_name},{$a->scanned_at},{$a->result->value}\n";
        return response($csv,200,['Content-Type'=>'text/csv','Content-Disposition'=>"attachment; filename=laporan-$date.csv"]);
    }
}
