<?php

namespace App\Http\Controllers\pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class TindakanRanapController_v2 extends Controller
{
    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'tindakan-ranap-v2';    
    }

    public function index()
    {
        $modul = $this->menu;
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab  from penjab where status='1' order by png_jawab ");
        $doctor = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter  from dokter order by nm_dokter");
        
        return view('pendapatan.honor_dokter_ranap_v2', compact('modul','doctor', 'penjab'));
    }

    public function getTindakanRanap(Request $request)
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
            $sql = "SELECT dokter.nm_dokter,penjab.png_jawab,reg_periksa.stts,reg_periksa.status_lanjut,rawat_inap_drpr.tgl_perawatan,reg_periksa.no_rawat,nota_inap.no_nota as nota_inap,
            reg_periksa.no_rkm_medis,pasien.nm_pasien,jns_perawatan_inap.nm_perawatan,
            ifnull((select bangsal.nm_bangsal from kamar_inap 
            inner join kamar 
            inner join bangsal on 
            kamar_inap.kd_kamar=kamar.kd_kamar 
            and kamar.kd_bangsal=bangsal.kd_bangsal 
            where kamar_inap.no_rawat=rawat_inap_drpr.no_rawat limit 1),'Ruang Terhapus' ) as kamar,
            NULL AS kolom_kosong_1,NULL AS kolom_kosong_2,
            (SELECT bangsal.devisi FROM  kamar_inap 
                    inner join kamar on kamar_inap.kd_kamar=kamar.kd_kamar  
                    inner join bangsal on kamar.kd_bangsal=bangsal.kd_bangsal
                    WHERE reg_periksa.no_rawat=kamar_inap.no_rawat LIMIT 1) as devisi,
            (
                SELECT tarif_tindakandr 
                FROM jns_perawatan_inap
                WHERE rawat_inap_drpr.kd_jenis_prw=jns_perawatan_inap.kd_jenis_prw
                AND reg_periksa.no_rawat=rawat_inap_drpr.no_rawat
                AND reg_periksa.kd_pj='127'
                AND jns_perawatan_inap.nm_perawatan NOT IN ('PEMERIKSAAN/KONSULTASI DR SPESIALIS', 'PEMERIKSAAN/KONSULTASI DR UMUM/IGD', 'PEMERIKSAAN/ DOKTER SPESIALIS (D)', 'PEMERIKSAAN/KONSUL DOKTER IGD (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (DPJP 1) (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (KONSULAN) (D)', 'PEMERIKSAAN/KONSUL DOKTER UMUM (D)') 
            ) AS tindakan_ranap_umum,
            
            NULL AS kolom_kosong_3,NULL AS kolom_kosong_4,
            (
                SELECT tarif_tindakandr 
                FROM jns_perawatan_inap
                WHERE rawat_inap_drpr.kd_jenis_prw=jns_perawatan_inap.kd_jenis_prw
                AND reg_periksa.no_rawat=rawat_inap_drpr.no_rawat
                AND reg_periksa.kd_pj IN('BPJ','63')
                AND jns_perawatan_inap.nm_perawatan NOT IN ('PEMERIKSAAN/KONSULTASI DR SPESIALIS', 'PEMERIKSAAN/KONSULTASI DR UMUM/IGD', 'PEMERIKSAAN/ DOKTER SPESIALIS (D)', 'PEMERIKSAAN/KONSUL DOKTER IGD (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (DPJP 1) (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (KONSULAN) (D)', 'PEMERIKSAAN/KONSUL DOKTER UMUM (D)') 
            ) AS tindakan_ranap_bpjs,
            
            NULL AS kolom_kosong_5,NULL AS kolom_kosong_6,
            (
                SELECT tarif_tindakandr 
                FROM jns_perawatan_inap
                WHERE rawat_inap_drpr.kd_jenis_prw=jns_perawatan_inap.kd_jenis_prw
                AND reg_periksa.no_rawat=rawat_inap_drpr.no_rawat
                AND reg_periksa.kd_pj NOT IN ('127','BPJ', '63')
                AND jns_perawatan_inap.nm_perawatan NOT IN ('PEMERIKSAAN/KONSULTASI DR SPESIALIS', 'PEMERIKSAAN/KONSULTASI DR UMUM/IGD','PEMERIKSAAN/ DOKTER SPESIALIS (D)', 'PEMERIKSAAN/KONSUL DOKTER IGD (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (DPJP 1) (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (KONSULAN) (D)', 'PEMERIKSAAN/KONSUL DOKTER UMUM (D)') 
            ) AS tindakan_ranap_corporat
            
            from pasien 
            inner join reg_periksa on reg_periksa.no_rkm_medis=pasien.no_rkm_medis 
            inner join rawat_inap_drpr on rawat_inap_drpr.no_rawat=reg_periksa.no_rawat 
            inner join jns_perawatan_inap on rawat_inap_drpr.kd_jenis_prw=jns_perawatan_inap.kd_jenis_prw 
            inner join penjab on reg_periksa.kd_pj=penjab.kd_pj
            INNER JOIN dokter ON rawat_inap_drpr.kd_dokter=dokter.kd_dokter
            LEFT JOIN nota_inap ON reg_periksa.no_rawat=nota_inap.no_rawat
            where date(rawat_inap_drpr.tgl_perawatan) BETWEEN '".$start."' and '".$end."' 
            and NOT rawat_inap_drpr.tarif_tindakandr='0' ";
            
            $sql = ($penjamin!="all") ? $sql."and reg_periksa.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = ($doctor!="all") ? $sql."and rawat_inap_drpr.kd_dokter= '".$doctor."' ": $sql." ";
            $sql = $sql."order by rawat_inap_drpr.no_rawat,rawat_inap_drpr.tgl_perawatan,rawat_inap_drpr.jam_rawat,jns_perawatan_inap.nm_perawatan ";

            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('pendapatan.honor_dokter_ranap_v2', compact('modul'));
        
    }
}
