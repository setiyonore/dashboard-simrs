<?php

namespace App\Http\Controllers\Pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class TindakanRalanController extends Controller
{

    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'tindakan-ralan';    
    }

    public function index()
    {
        $modul = $this->menu;
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab  from penjab where status='1' order by png_jawab ");
        $doctor = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter  from dokter order by nm_dokter");
        
        return view('pendapatan.honor_dokter_ralan', compact('modul','doctor', 'penjab'));
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
            $sql = "SELECT rawat_jl_drpr.no_rawat,reg_periksa.stts,reg_periksa.status_lanjut,reg_periksa.status_bayar,nota_jalan.no_nota AS no_nota,reg_periksa.tgl_registrasi,reg_periksa.no_rkm_medis,
            pasien.nm_pasien,poliklinik.nm_poli,dokter.nm_dokter,
            (
                SELECT poliklinik.nm_poli FROM poliklinik 
                LEFT JOIN rujukan_internal_poli ON poliklinik.kd_poli=rujukan_internal_poli.kd_poli 
                WHERE reg_periksa.no_rawat=rujukan_internal_poli.no_rawat limit 1
            ) as poli_rujukan,
            (
                SELECT dokter.nm_dokter FROM dokter
                LEFT JOIN rujukan_internal_poli ON dokter.kd_dokter=rujukan_internal_poli.kd_dokter
                WHERE reg_periksa.no_rawat=rujukan_internal_poli.no_rawat limit 1
            ) AS dokter_rujukan,
            jns_perawatan.nm_perawatan,petugas.nama as petugas,rawat_jl_drpr.tgl_perawatan,
            rawat_jl_drpr.jam_rawat,penjab.png_jawab,  
            rawat_jl_drpr.tarif_tindakandr
            from pasien 
            inner join reg_periksa on reg_periksa.no_rkm_medis=pasien.no_rkm_medis 
            left join rawat_jl_drpr on rawat_jl_drpr.no_rawat=reg_periksa.no_rawat 
            inner join jns_perawatan on rawat_jl_drpr.kd_jenis_prw=jns_perawatan.kd_jenis_prw 
            inner join dokter on rawat_jl_drpr.kd_dokter=dokter.kd_dokter 
            inner join poliklinik on reg_periksa.kd_poli=poliklinik.kd_poli 
            inner join penjab on reg_periksa.kd_pj=penjab.kd_pj
            inner join petugas on rawat_jl_drpr.nip=petugas.nip
            LEFT JOIN nota_jalan ON rawat_jl_drpr.no_rawat=nota_jalan.no_rawat
            where date(reg_periksa.tgl_registrasi) BETWEEN '".$start."' and '".$end."'
            AND reg_periksa.status_lanjut='Ralan'
            AND NOT jns_perawatan.tarif_tindakandr='0' ";
            
            $sql = ($penjamin!="all") ? $sql."and reg_periksa.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = ($doctor!="all") ? $sql."and rawat_jl_drpr.kd_dokter= '".$doctor."' ": $sql." ";
            $sql = $sql."order by reg_periksa.tgl_registrasi,reg_periksa.no_rawat,jns_perawatan.nm_perawatan";

            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('pendapatan.honor_dokter_ralan', compact('modul'));
        
    }

}