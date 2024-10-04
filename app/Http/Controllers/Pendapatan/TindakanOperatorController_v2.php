<?php

namespace App\Http\Controllers\pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class TindakanOperatorController_v2 extends Controller
{
    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'tindakan-operator-v2';    
    }

    public function index()
    {
        $modul = $this->menu;
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab  from penjab where status='1' order by png_jawab ");
        $doctor = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter  from dokter order by nm_dokter");
        
        return view('pendapatan.operator_v2', compact('modul','doctor', 'penjab'));
    }

    public function getTindakanOperator(Request $request)
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
            //Get Tindakan Operator
            $sql = "SELECT operator1.nm_dokter as operator1,penjab.png_jawab,reg_periksa.stts,reg_periksa.status_lanjut,DATE(operasi.tgl_operasi) AS tgl_operasi,operasi.no_rawat,
            CASE WHEN reg_periksa.status_lanjut = 'Ralan' 
            THEN (
                SELECT nota_jalan.no_nota
                FROM nota_jalan
                WHERE reg_periksa.no_rawat=nota_jalan.no_rawat 
                )
            WHEN reg_periksa.status_lanjut = 'Ranap'
            THEN (
                SELECT nota_inap.no_nota
                FROM nota_inap
                WHERE reg_periksa.no_rawat=nota_inap.no_rawat
            )
            END AS nota,
            pasien.no_rkm_medis,pasien.nm_pasien,paket_operasi.nm_perawatan,
            if(operasi.status='Ralan',(select devisi_poli from poliklinik where poliklinik.kd_poli=reg_periksa.kd_poli),
            (select bangsal.nm_bangsal from kamar_inap inner join kamar inner join bangsal on kamar_inap.kd_kamar=kamar.kd_kamar 
            and kamar.kd_bangsal=bangsal.kd_bangsal where kamar_inap.no_rawat=operasi.no_rawat limit 1 )) as kamar,
            
            if(operasi.status='Ralan',(select devisi_poli from poliklinik where poliklinik.kd_poli=reg_periksa.kd_poli),
            (SELECT bangsal.devisi FROM  kamar_inap 
                    inner join kamar on kamar_inap.kd_kamar=kamar.kd_kamar  
                    inner join bangsal on kamar.kd_bangsal=bangsal.kd_bangsal
                    WHERE reg_periksa.no_rawat=kamar_inap.no_rawat LIMIT 1)) as devisi,
            
            NULL AS kolom_kosong_1,NULL AS kolom_kosong_2,
            ( 
                SELECT operasi.biayaoperator1
                FROM operasi
                WHERE operasi.kode_paket=paket_operasi.kode_paket
                AND reg_periksa.kd_pj='127'
                AND reg_periksa.no_rawat=operasi.no_rawat
                LIMIT 1
            ) AS operator_umum,
            
            NULL AS kolom_kosong_3,NULL AS kolom_kosong_4,
            ( 
                SELECT operasi.biayaoperator1
                FROM operasi
                WHERE operasi.kode_paket=paket_operasi.kode_paket
                AND reg_periksa.kd_pj IN ('BPJ', '63')
                AND reg_periksa.no_rawat=operasi.no_rawat
                LIMIT 1
            ) AS operator_bpjs,
            
            NULL AS kolom_kosong_5,NULL AS kolom_kosong_6,
            ( 
                SELECT operasi.biayaoperator1
                FROM operasi
                WHERE operasi.kode_paket=paket_operasi.kode_paket
                AND reg_periksa.kd_pj NOT IN ('127','BPJ', '63')
                AND reg_periksa.no_rawat=operasi.no_rawat
                LIMIT 1
            ) AS operator_corporate
            
            from operasi 
            inner join reg_periksa on operasi.no_rawat=reg_periksa.no_rawat 
            inner join pasien on reg_periksa.no_rkm_medis=pasien.no_rkm_medis 
            inner join paket_operasi on operasi.kode_paket=paket_operasi.kode_paket 
            inner join penjab on reg_periksa.kd_pj=penjab.kd_pj
            inner join dokter as operator1 on operator1.kd_dokter=operasi.operator1 
            where date(operasi.tgl_operasi) between '".$start."' and '".$end."'
            and NOT operasi.biayaoperator1='0' ";
                    
            $sql = ($penjamin!="all") ? $sql."and reg_periksa.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = ($doctor!="all") ? $sql."and operasi.operator1= '".$doctor."' ": $sql." ";
            $sql = $sql."order by operasi.tgl_operasi,paket_operasi.nm_perawatan ";
            
            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);
        }            
        $modul = $this->menu;
        return view('pendapatan.operator_v2', compact('modul'));
    }
}
