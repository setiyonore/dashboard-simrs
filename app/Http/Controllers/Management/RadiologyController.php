<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class RadiologyController extends Controller
{

    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'radiology';  
    }

    public function index(Request $request)
    {
        $modul = $this->menu;
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab  from penjab where status='1' order by png_jawab ");
        $doctor = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter  from dokter order by nm_dokter");
   
        return view('management.radiology.index', compact('modul','doctor','penjab'));
    }

    public function getTindakanRadiologi(Request $request)
    {   
        if(!empty($request->start)){
            $start = $request->start;
        }else{
            $start=Carbon\Carbon::now()->isoFormat('YYYY-MM-DD');
        }

        if(!empty($request->end)){
            $end = $request->end;
        }else{
            $end=Carbon\Carbon::now()->isoFormat('YYYY-MM-DD');
        }

        $penjamin = !empty($request->penjamin) ?  $request->penjamin : "all";
        $doctor = !empty($request->doctor) ?  $request->doctor : "all";

        if(request()->ajax()){
            //Get Tindakan Radiologi
            $sql = "SELECT
            periksa_radiologi.no_rawat,
            periksa_radiologi.STATUS AS status_rawat,
            reg_periksa.stts AS status_periksa,
            reg_periksa.status_bayar,
            nota_jalan.no_nota AS nota_jalan,
            nota_inap.no_nota AS nota_inap,
            bridging_sep.no_sep,
            periksa_radiologi.tgl_periksa,
            reg_periksa.tgl_registrasi,
            reg_periksa.no_rkm_medis,
            pasien.nm_pasien,
            penjab.png_jawab,
            poliklinik.devisi_poli,
            dokter.nm_dokter as dokter_perujuk,

            if(periksa_radiologi.status='Ralan',(select devisi_poli from poliklinik where poliklinik.kd_poli=reg_periksa.kd_poli),
            (SELECT bangsal.devisi FROM  kamar_inap 
                inner join kamar on kamar_inap.kd_kamar=kamar.kd_kamar  
                inner join bangsal on kamar.kd_bangsal=bangsal.kd_bangsal
                WHERE reg_periksa.no_rawat=kamar_inap.no_rawat LIMIT 1)) as devisi,

            jns_perawatan_radiologi.nm_perawatan,
            (SELECT SUM(totalbiaya) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat AND `status`='Radiologi') as biaya
        FROM
            periksa_radiologi
            INNER JOIN reg_periksa ON periksa_radiologi.no_rawat = reg_periksa.no_rawat
            INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis
            LEFT JOIN jns_perawatan_radiologi ON periksa_radiologi.kd_jenis_prw = jns_perawatan_radiologi.kd_jenis_prw
            INNER JOIN dokter ON periksa_radiologi.dokter_perujuk = dokter.kd_dokter
            INNER JOIN penjab ON reg_periksa.kd_pj = penjab.kd_pj
            LEFT JOIN nota_jalan ON periksa_radiologi.no_rawat = nota_jalan.no_rawat
            LEFT JOIN nota_inap ON periksa_radiologi.no_rawat = nota_inap.no_rawat
            LEFT JOIN poliklinik ON reg_periksa.kd_poli = poliklinik.kd_poli
            LEFT JOIN bridging_sep ON periksa_radiologi.no_rawat=bridging_sep.no_rawat
            WHERE DATE(periksa_radiologi.tgl_periksa) BETWEEN '".$start."' and '".$end."'";

            $sql = ($penjamin!="all") ? $sql."and reg_periksa.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = ($doctor!="all") ? $sql."and periksa_radiologi.dokter_perujuk= '".$doctor."' ": $sql." ";
            $sql = $sql."group by periksa_radiologi.no_rawat order by periksa_radiologi.no_rawat";
            
            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('management.radiology.index', compact('modul'));
        
    }    
}