<?php

namespace App\Http\Controllers\pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class TindakanRalanController_v2 extends Controller
{
    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'tindakan-ralan-v2';    
    }

    public function index()
    {
        $modul = $this->menu;
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab  from penjab where status='1' order by png_jawab ");
        $doctor = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter  from dokter order by nm_dokter");
        
        return view('pendapatan.honor_dokter_ralan_v2', compact('modul','doctor', 'penjab'));
    }

    public function getTindakanRalan(Request $request)
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
            $sql = "SELECT dokter.nm_dokter,penjab.png_jawab,reg_periksa.stts,reg_periksa.status_lanjut,reg_periksa.tgl_registrasi,reg_periksa.no_rawat,
            CASE WHEN reg_periksa.status_lanjut ='Ralan'
            THEN (
                SELECT nota_jalan.no_nota 
                FROM nota_jalan 
                WHERE reg_periksa.no_rawat=nota_jalan.no_rawat
                )
            WHEN reg_periksa.status_lanjut ='Ranap'
            THEN (
                SELECT nota_inap.no_nota 
                FROM nota_inap 
                WHERE reg_periksa.no_rawat=nota_inap.no_rawat
            )
            END AS nota,
            reg_periksa.no_rkm_medis,pasien.nm_pasien,jns_perawatan.nm_perawatan,poliklinik.devisi_poli,
            (
                SELECT tarif_tindakandr 
                FROM jns_perawatan 
                WHERE rawat_jl_drpr.kd_jenis_prw=jns_perawatan.kd_jenis_prw
                AND reg_periksa.no_rawat=rawat_jl_drpr.no_rawat
                AND reg_periksa.kd_pj='127'
                AND jns_perawatan.nm_perawatan IN ('PEMERIKSAAN/KONSULTASI DR SPESIALIS', 'PEMERIKSAAN/KONSULTASI DR UMUM/IGD', 'PEMERIKSAAN/ DOKTER SPESIALIS (D)', 'PEMERIKSAAN/KONSUL DOKTER IGD (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (DPJP 1) (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (KONSULAN) (D)', 'PEMERIKSAAN/KONSUL DOKTER UMUM (D)') 
            ) AS pemeriksaan_ralan_umum,
            (
                SELECT tarif_tindakandr 
                FROM jns_perawatan 
                WHERE rawat_jl_drpr.kd_jenis_prw=jns_perawatan.kd_jenis_prw
                AND reg_periksa.no_rawat=rawat_jl_drpr.no_rawat
                AND reg_periksa.kd_pj='127'
                AND jns_perawatan.nm_perawatan NOT IN ('PEMERIKSAAN/KONSULTASI DR SPESIALIS', 'PEMERIKSAAN/KONSULTASI DR UMUM/IGD','PEMERIKSAAN/ DOKTER SPESIALIS (D)', 'PEMERIKSAAN/KONSUL DOKTER IGD (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (DPJP 1) (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (KONSULAN) (D)', 'PEMERIKSAAN/KONSUL DOKTER UMUM (D)') 
            ) AS tindakan_ralan_umum,
            NULL AS kolom_kosong_1,
            
            (
                SELECT tarif_tindakandr 
                FROM jns_perawatan 
                WHERE rawat_jl_drpr.kd_jenis_prw=jns_perawatan.kd_jenis_prw
                AND reg_periksa.no_rawat=rawat_jl_drpr.no_rawat
                AND reg_periksa.kd_pj IN ('BPJ', '63')
                AND jns_perawatan.nm_perawatan IN ('PEMERIKSAAN/KONSULTASI DR SPESIALIS', 'PEMERIKSAAN/KONSULTASI DR UMUM/IGD', 'PEMERIKSAAN/ DOKTER SPESIALIS (D)', 'PEMERIKSAAN/KONSUL DOKTER IGD (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (DPJP 1) (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (KONSULAN) (D)', 'PEMERIKSAAN/KONSUL DOKTER UMUM (D)') 
            ) AS pemeriksaan_ralan_bpjs,
            
            (
                SELECT tarif_tindakandr 
                FROM jns_perawatan 
                WHERE rawat_jl_drpr.kd_jenis_prw=jns_perawatan.kd_jenis_prw
                AND reg_periksa.no_rawat=rawat_jl_drpr.no_rawat
                AND reg_periksa.kd_pj IN ('BPJ', '63')
                AND jns_perawatan.nm_perawatan NOT IN ('PEMERIKSAAN/KONSULTASI DR SPESIALIS', 'PEMERIKSAAN/KONSULTASI DR UMUM/IGD','PEMERIKSAAN/ DOKTER SPESIALIS (D)', 'PEMERIKSAAN/KONSUL DOKTER IGD (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (DPJP 1) (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (KONSULAN) (D)', 'PEMERIKSAAN/KONSUL DOKTER UMUM (D)') 
            ) AS tindakan_ralan_bpjs,
            NULL AS kolom_kosong_2,
            
            (
                SELECT tarif_tindakandr 
                FROM jns_perawatan 
                WHERE rawat_jl_drpr.kd_jenis_prw=jns_perawatan.kd_jenis_prw
                AND reg_periksa.no_rawat=rawat_jl_drpr.no_rawat
                AND reg_periksa.kd_pj NOT IN ('127','BPJ', '63')
                AND jns_perawatan.nm_perawatan IN ('PEMERIKSAAN/KONSULTASI DR SPESIALIS', 'PEMERIKSAAN/KONSULTASI DR UMUM/IGD', 'PEMERIKSAAN/ DOKTER SPESIALIS (D)', 'PEMERIKSAAN/KONSUL DOKTER IGD (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (DPJP 1) (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (KONSULAN) (D)', 'PEMERIKSAAN/KONSUL DOKTER UMUM (D)') 
            ) AS pemeriksaan_ralan_corporate,
            
            (
                SELECT tarif_tindakandr 
                FROM jns_perawatan 
                WHERE rawat_jl_drpr.kd_jenis_prw=jns_perawatan.kd_jenis_prw
                AND reg_periksa.no_rawat=rawat_jl_drpr.no_rawat
                AND reg_periksa.kd_pj NOT IN ('127','BPJ', '63')
                AND jns_perawatan.nm_perawatan NOT IN ('PEMERIKSAAN/KONSULTASI DR SPESIALIS', 'PEMERIKSAAN/KONSULTASI DR UMUM/IGD','PEMERIKSAAN/ DOKTER SPESIALIS (D)', 'PEMERIKSAAN/KONSUL DOKTER IGD (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (DPJP 1) (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (KONSULAN) (D)', 'PEMERIKSAAN/KONSUL DOKTER UMUM (D)') 
            ) AS tindakan_ralan_corporate,
            NULL AS kolom_kosong_3
            
            from pasien 
            inner join reg_periksa on reg_periksa.no_rkm_medis=pasien.no_rkm_medis 
            inner join rawat_jl_drpr on rawat_jl_drpr.no_rawat=reg_periksa.no_rawat 
            inner join jns_perawatan on rawat_jl_drpr.kd_jenis_prw=jns_perawatan.kd_jenis_prw 
            inner join penjab on reg_periksa.kd_pj=penjab.kd_pj 
            INNER JOIN dokter ON rawat_jl_drpr.kd_dokter=dokter.kd_dokter
            LEFT JOIN nota_inap ON reg_periksa.no_rawat=nota_inap.no_rawat
            INNER JOIN poliklinik ON reg_periksa.kd_poli=poliklinik.kd_poli
            where date(reg_periksa.tgl_registrasi) BETWEEN '".$start."' and '".$end."'
            and NOT rawat_jl_drpr.tarif_tindakandr='0' ";
            
            $sql = ($penjamin!="all") ? $sql."and reg_periksa.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = ($doctor!="all") ? $sql."and rawat_jl_drpr.kd_dokter= '".$doctor."' ": $sql." ";
            $sql = $sql."order by reg_periksa.tgl_registrasi,reg_periksa.no_rawat,jns_perawatan.nm_perawatan";

            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('pendapatan.honor_dokter_ralan_v2', compact('modul'));
        
    }
}
