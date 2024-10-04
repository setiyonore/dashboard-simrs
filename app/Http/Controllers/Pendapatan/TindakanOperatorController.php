<?php

namespace App\Http\Controllers\Pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class TindakanOperatorController extends Controller
{

    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'tindakan-operator';    
    }

    public function index()
    {
        $modul = $this->menu;
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab  from penjab where status='1' order by png_jawab ");
        $doctor = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter  from dokter order by nm_dokter");
        
        return view('pendapatan.operator', compact('modul','doctor', 'penjab'));
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
            //Get Tindakan Operarator
            $sql = "SELECT operasi.no_rawat,reg_periksa.stts,reg_periksa.status_lanjut,reg_periksa.status_bayar,nota_jalan.no_nota AS nota_jalan,nota_inap.no_nota AS nota_inap,poliklinik.nm_poli,reg_periksa.no_rkm_medis,pasien.nm_pasien, 
            operasi.kode_paket,paket_operasi.nm_perawatan,operasi.tgl_operasi, 
            penjab.png_jawab,if(operasi.status='Ralan',(select nm_poli from poliklinik where poliklinik.kd_poli=reg_periksa.kd_poli),
                                       (select bangsal.nm_bangsal from kamar_inap inner join kamar inner join bangsal on kamar_inap.kd_kamar=kamar.kd_kamar 
            and kamar.kd_bangsal=bangsal.kd_bangsal where kamar_inap.no_rawat=operasi.no_rawat limit 1 )) as ruangan,
            operator1.nm_dokter as operator1,operasi.biayaoperator1, 
            operator2.nm_dokter as operator2,operasi.biayaoperator2, 
            operator3.nm_dokter as operator3,operasi.biayaoperator3,
            asisten_operator1.nama as asisten_operator1,operasi.biayaasisten_operator1, 
            asisten_operator2.nama as asisten_operator2,operasi.biayaasisten_operator2, 
            asisten_operator3.nama as asisten_operator3,operasi.biayaasisten_operator3, 
            instrumen.nama as instrumen,operasi.biayainstrumen, 
            dokter_anak.nm_dokter as dokter_anak,operasi.biayadokter_anak, 
            perawaat_resusitas.nama as perawaat_resusitas,operasi.biayaperawaat_resusitas, 
            dokter_anestesi.nm_dokter as dokter_anestesi,operasi.biayadokter_anestesi, 
            asisten_anestesi.nama as asisten_anestesi,operasi.biayaasisten_anestesi, 
            (select nama from petugas where petugas.nip=operasi.asisten_anestesi2) as asisten_anestesi2,operasi.biayaasisten_anestesi2,bidan.nama as bidan,operasi.biayabidan, 
            (select nama from petugas where petugas.nip=operasi.bidan2) as bidan2,operasi.biayabidan2, 
            (select nama from petugas where petugas.nip=operasi.bidan3) as bidan3,operasi.biayabidan3, 
            (select nama from petugas where petugas.nip=operasi.perawat_luar) as perawat_luar,operasi.biayaperawat_luar, 
            (select nama from petugas where petugas.nip=operasi.omloop) as omloop,operasi.biaya_omloop, 
            (select nama from petugas where petugas.nip=operasi.omloop2) as omloop2,operasi.biaya_omloop2, 
            (select nama from petugas where petugas.nip=operasi.omloop3) as omloop3,operasi.biaya_omloop3, 
            (select nama from petugas where petugas.nip=operasi.omloop4) as omloop4,operasi.biaya_omloop4, 
            (select nama from petugas where petugas.nip=operasi.omloop5) as omloop5,operasi.biaya_omloop5, 
            (select nm_dokter from dokter where dokter.kd_dokter=operasi.dokter_pjanak) as dokter_pjanak,operasi.biaya_dokter_pjanak, 
            (select nm_dokter from dokter where dokter.kd_dokter=operasi.dokter_umum) as dokter_umum,operasi.biaya_dokter_umum, 
            operasi.biayaalat,operasi.biayasewaok,operasi.akomodasi,operasi.bagian_rs,operasi.biayasarpras,
            (
            operasi.biayaoperator1+operasi.biayaoperator2+operasi.biayaoperator3+operasi.biayaasisten_operator1+operasi.biayaasisten_operator2+operasi.biayaasisten_operator3+operasi.biayainstrumen+operasi.biayadokter_anak+operasi.biayaperawaat_resusitas+operasi.biayadokter_anestesi+operasi.biayaasisten_anestesi+operasi.biayaasisten_anestesi2+operasi.biayabidan+operasi.biayabidan2+operasi.biayabidan3+operasi.biayaperawat_luar+operasi.biayaalat+operasi.biaya_dokter_pjanak+operasi.biaya_dokter_umum+operasi.biayasewaok+operasi.akomodasi+operasi.bagian_rs+operasi.biaya_omloop+operasi.biaya_omloop2+operasi.biaya_omloop3+operasi.biaya_omloop4+operasi.biaya_omloop5+operasi.biayasarpras
            ) AS total_operasi
            from operasi 
            inner join reg_periksa on operasi.no_rawat=reg_periksa.no_rawat 
            inner join pasien on reg_periksa.no_rkm_medis=pasien.no_rkm_medis 
            inner join paket_operasi on operasi.kode_paket=paket_operasi.kode_paket 
            inner join penjab on reg_periksa.kd_pj=penjab.kd_pj 
            inner join dokter as operator1 on operator1.kd_dokter=operasi.operator1 
            inner join dokter as operator2 on operator2.kd_dokter=operasi.operator2 
            inner join dokter as operator3 on operator3.kd_dokter=operasi.operator3 
            inner join dokter as dokter_anak on dokter_anak.kd_dokter=operasi.dokter_anak 
            inner join dokter as dokter_anestesi on dokter_anestesi.kd_dokter=operasi.dokter_anestesi 
            inner join petugas as asisten_operator1 on asisten_operator1.nip=operasi.asisten_operator1 
            inner join petugas as asisten_operator2 on asisten_operator2.nip=operasi.asisten_operator2 
            inner join petugas as asisten_operator3 on asisten_operator3.nip=operasi.asisten_operator3 
            inner join petugas as asisten_anestesi on asisten_anestesi.nip=operasi.asisten_anestesi 
            inner join petugas as bidan on bidan.nip=operasi.bidan 
            inner join petugas as instrumen on instrumen.nip=operasi.instrumen 
            inner join petugas as perawaat_resusitas on perawaat_resusitas.nip=operasi.perawaat_resusitas
            INNER JOIN poliklinik ON reg_periksa.kd_poli=poliklinik.kd_poli
            LEFT JOIN nota_jalan ON operasi.no_rawat=nota_jalan.no_rawat
            LEFT JOIN nota_inap ON operasi.no_rawat=nota_inap.no_rawat
            where date(operasi.tgl_operasi) between '".$start."' and '".$end."' ";
                    
            $sql = ($penjamin!="all") ? $sql."and reg_periksa.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = ($doctor!="all") ? $sql."and operasi.operator1= '".$doctor."' ": $sql." ";
            $sql = $sql." order by operasi.tgl_operasi,paket_operasi.nm_perawatan";
            
            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);
        }            
        $modul = $this->menu;
        return view('pendapatan.operator', compact('modul'));
    }
}