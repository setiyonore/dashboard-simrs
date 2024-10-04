<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class NonKronisController extends Controller
{

    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'obat-non-kronis';  
    }

    public function index(Request $request)
    {
        $modul = $this->menu;
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj,png_jawab from penjab where status='1' order by png_jawab ");

        return view('pendapatan.obat_non_kronis', compact('modul','penjab'));
    }

    public function getNonKronis(Request $request)
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
        
        if(request()->ajax()){
            //Get Tindakan Obat Non Kronis
            $sql = "SELECT
            detail_pemberian_obat.tgl_perawatan,
            detail_pemberian_obat.no_rawat,
            ( SELECT no_nota FROM nota_jalan WHERE no_rawat = detail_pemberian_obat.no_rawat ) AS nota_jalan,
            ( SELECT no_nota FROM nota_inap WHERE no_rawat = detail_pemberian_obat.no_rawat ) AS nota_inap,
            ( SELECT no_sep FROM bridging_sep WHERE no_rawat = detail_pemberian_obat.no_rawat AND jnspelayanan = '2' LIMIT 1 ) AS sep_jalan,
            ( SELECT no_sep FROM bridging_sep WHERE no_rawat = detail_pemberian_obat.no_rawat AND jnspelayanan = '1' LIMIT 1 ) AS sep_inap,
            reg_periksa.no_rkm_medis,
            pasien.nm_pasien,
            (
            SELECT
                dokter.nm_dokter 
            FROM
                resep_obat
                INNER JOIN dokter ON resep_obat.kd_dokter = dokter.kd_dokter 
            WHERE
                resep_obat.no_rawat = detail_pemberian_obat.no_rawat 
                LIMIT 1 
            ) AS dokter_peresep,
            penjab.png_jawab,
            databarang.nama_brng,
            detail_pemberian_obat.h_beli,
            detail_pemberian_obat.biaya_obat,
            detail_pemberian_obat.jml,
            detail_pemberian_obat.total AS total_sebelum_ppn,
            (( detail_pemberian_obat.total * 0.11 )+ detail_pemberian_obat.total ) AS total_setelah_ppn,
            detail_pemberian_obat.STATUS as status_rawat,
            IF 
            ( 
                detail_pemberian_obat.STATUS = 'Ralan', 
                (                     
                SELECT 
                    poliklinik.devisi_poli 
                FROM 
                    reg_periksa 
                    LEFT JOIN poliklinik ON poliklinik.kd_poli = reg_periksa.kd_poli                                        
                WHERE 
                    no_rawat = detail_pemberian_obat.no_rawat  
                    LIMIT 1  
                    ),( 
                SELECT 
                    bangsal.devisi  
                FROM 
                    kamar_inap 
                    inner join kamar on kamar_inap.kd_kamar=kamar.kd_kamar  
                    inner join bangsal on kamar.kd_bangsal=bangsal.kd_bangsal  
                WHERE 
                    no_rawat = detail_pemberian_obat.no_rawat  
                    LIMIT 1  
                )) AS devisi,
            reg_periksa.stts as status_periksa
        FROM
            detail_pemberian_obat
            LEFT JOIN reg_periksa ON detail_pemberian_obat.no_rawat = reg_periksa.no_rawat
            LEFT JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis
            LEFT JOIN databarang ON detail_pemberian_obat.kode_brng = databarang.kode_brng
            LEFT JOIN penjab ON reg_periksa.kd_pj = penjab.kd_pj 
            WHERE date(detail_pemberian_obat.tgl_perawatan) BETWEEN '".$start."' and '".$end."' ";    

            $sql = ($penjamin!="all") ? $sql."and reg_periksa.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = $sql."order by detail_pemberian_obat.no_rawat";
            
            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('pendapatan.obat_non_kronis', compact('modul'));
        
    }    
}