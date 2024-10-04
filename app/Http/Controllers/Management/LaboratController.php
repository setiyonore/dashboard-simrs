<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class LaboratController extends Controller
{

    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'laborat';  
    }

    public function index(Request $request)
    {
        $modul = $this->menu;
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab  from penjab where status='1' order by png_jawab ");
        $doctor = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter  from dokter order by nm_dokter");

        return view('management.laborat.index', compact('modul','doctor', 'penjab'));
    }

    public function getTindakanLaborat(Request $request)
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
            //Get Tindakan Laborat
            $sql = "SELECT periksa_lab.no_rawat, nota_jalan.no_nota as nota_jalan,nota_inap.no_nota AS nota_inap,
            bridging_sep.no_sep,periksa_lab.tgl_periksa AS tgl_periksa_lab, reg_periksa.stts,reg_periksa.status_bayar,
            reg_periksa.status_lanjut,reg_periksa.no_rkm_medis,pasien.nm_pasien,penjab.png_jawab,
            poliklinik.devisi_poli,dokter.nm_dokter AS dokter_perujuk,
            
            if(periksa_lab.status='Ralan',(select devisi_poli from poliklinik where poliklinik.kd_poli=reg_periksa.kd_poli),
            (SELECT bangsal.devisi FROM  kamar_inap 
                    inner join kamar on kamar_inap.kd_kamar=kamar.kd_kamar  
                    inner join bangsal on kamar.kd_bangsal=bangsal.kd_bangsal
                    WHERE reg_periksa.no_rawat=kamar_inap.no_rawat LIMIT 1)) as devisi,

            (
            SELECT GROUP_CONCAT(template_laboratorium.Pemeriksaan) FROM template_laboratorium 
            LEFT JOIN detail_periksa_lab ON template_laboratorium.id_template=detail_periksa_lab.id_template
            WHERE detail_periksa_lab.no_rawat=reg_periksa.no_rawat
            GROUP BY detail_periksa_lab.tgl_periksa 
            LIMIT 1
            ) AS tindakan_lab_PK,
            (
            SELECT GROUP_CONCAT(jns_perawatan_lab.nm_perawatan) FROM jns_perawatan_lab 
            LEFT JOIN periksa_lab ON jns_perawatan_lab.kd_jenis_prw=periksa_lab.kd_jenis_prw
            WHERE periksa_lab.kategori='PA'
            AND periksa_lab.no_rawat=reg_periksa.no_rawat
            GROUP BY periksa_lab.tgl_periksa 
            LIMIT 1
            ) AS tindakan_lab_PA,
            (SELECT SUM(totalbiaya) FROM billing WHERE periksa_lab.no_rawat=billing.no_rawat AND `status`='Laborat') AS billing
            FROM periksa_lab
            LEFT JOIN nota_jalan ON periksa_lab.no_rawat=nota_jalan.no_rawat
            LEFT JOIN nota_inap ON periksa_lab.no_rawat=nota_inap.no_rawat
            LEFT JOIN reg_periksa ON periksa_lab.no_rawat=reg_periksa.no_rawat
            INNER JOIN pasien ON reg_periksa.no_rkm_medis=pasien.no_rkm_medis
            INNER JOIN penjab ON reg_periksa.kd_pj=penjab.kd_pj
            INNER JOIN dokter ON periksa_lab.dokter_perujuk=dokter.kd_dokter
            INNER JOIN poliklinik ON reg_periksa.kd_poli=poliklinik.kd_poli
            LEFT JOIN bridging_sep ON periksa_lab.no_rawat=bridging_sep.no_rawat
            WHERE DATE(periksa_lab.tgl_periksa) BETWEEN '".$start."' and '".$end."' ";
                    
            $sql = ($penjamin!="all") ? $sql."and reg_periksa.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = ($doctor!="all") ? $sql."and periksa_lab.dokter_perujuk= '".$doctor."' ": $sql." ";
            $sql = $sql."group by periksa_lab.no_rawat order by periksa_lab.no_rawat";

            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('management.laborat.index', compact('modul'));
        
    }    
}