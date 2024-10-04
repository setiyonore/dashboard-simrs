<?php

namespace App\Http\Controllers\Pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class TindakanRanapController extends Controller
{

    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'tindakan-ranap';    
    }

    public function index()
    {
        $modul = $this->menu;
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab from penjab where status='1' order by png_jawab");
        $doctor = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter  from dokter order by nm_dokter");
        
        return view('pendapatan.honor_dokter_ranap', compact('modul','doctor', 'penjab'));
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
            $sql = "SELECT rawat_inap_drpr.no_rawat,reg_periksa.stts,reg_periksa.status_lanjut,reg_periksa.status_bayar,nota_inap.no_nota as no_nota,reg_periksa.no_rkm_medis,
            pasien.nm_pasien,dokter.nm_dokter,pegawai.nama AS petugas_input,rawat_inap_drpr.tgl_perawatan,
            rawat_inap_drpr.jam_rawat,penjab.png_jawab,
            ifnull((select bangsal.nm_bangsal from kamar_inap 
            inner join kamar 
            inner join bangsal on 
            kamar_inap.kd_kamar=kamar.kd_kamar 
            and kamar.kd_bangsal=bangsal.kd_bangsal 
            where kamar_inap.no_rawat=rawat_inap_drpr.no_rawat limit 1),'Ruang Terhapus' ) as ruang ,  
            jns_perawatan_inap.nm_perawatan,rawat_inap_drpr.tarif_tindakandr
            from pasien 
            left join reg_periksa on reg_periksa.no_rkm_medis=pasien.no_rkm_medis 
            left join rawat_inap_drpr on rawat_inap_drpr.no_rawat=reg_periksa.no_rawat 
            left join jns_perawatan_inap on rawat_inap_drpr.kd_jenis_prw=jns_perawatan_inap.kd_jenis_prw 
            inner join dokter on rawat_inap_drpr.kd_dokter=dokter.kd_dokter 
            inner join penjab on reg_periksa.kd_pj=penjab.kd_pj 
            LEFT JOIN nota_inap ON rawat_inap_drpr.no_rawat=nota_inap.no_rawat
            left join pegawai ON rawat_inap_drpr.nip=pegawai.nik
            where date(rawat_inap_drpr.tgl_perawatan) BETWEEN '".$start."' and '".$end."'
            AND NOT rawat_inap_drpr.tarif_tindakandr='0' ";
                        
            $sql = ($penjamin!="all") ? $sql."and reg_periksa.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = ($doctor!="all") ? $sql."and rawat_inap_drpr.kd_dokter= '".$doctor."' ": $sql." ";
            $sql = $sql."ORDER BY rawat_inap_drpr.tgl_perawatan,rawat_inap_drpr.jam_rawat,jns_perawatan_inap.nm_perawatan";

            
            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('pendapatan.honor_dokter_ranap', compact('modul'));
        
    }


}