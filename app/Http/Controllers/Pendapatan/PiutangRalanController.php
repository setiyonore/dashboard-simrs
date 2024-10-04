<?php

namespace App\Http\Controllers\Pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use DataTables;
use Carbon;

class PiutangRalanController extends Controller
{
    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'piutang-ralan';    
    }

    public function index()
    {
        $modul = $this->menu;
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab  from penjab where status='1' order by png_jawab ");
        $poli = DB::connection('mysql_khanza')->select("select kd_poli, nm_poli  from poliklinik where status='1' order by kd_poli ");

        return view('pendapatan.piutang_ralan', compact('modul', 'penjab', 'poli'));
    }

    public function getPiutangRalan(Request $request)
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
        $poli = !empty($request->poli) ?  $request->poli : "all";
        $status_pelunasan = !empty($request->status_pelunasan) ?  $request->status_pelunasan : "all";

        if(request()->ajax()){
            $sql = "SELECT reg_periksa.tgl_registrasi as tgl_registrasi, nota_jalan.no_nota,
            reg_periksa.no_rkm_medis,pasien.nm_pasien,penjab.png_jawab,rujuk_masuk.perujuk,
            (select billing.totalbiaya FROM billing 
            WHERE billing.no_rawat=reg_periksa.no_rawat
            AND billing.status='Registrasi')as registrasi,
            (SELECT SUM(totalbiaya) 
            FROM billing 
            WHERE reg_periksa.no_rawat=billing.no_rawat 
            AND `status`='Obat') as obat_plus_ppn,
            (
                (SELECT SUM(totalbiaya) 
                FROM billing 
                WHERE reg_periksa.no_rawat=billing.no_rawat
                AND `status` IN ('Ralan Dokter','Ralan Paramedis','Ralan Dokter Paramedis')
                )	
            )AS paket_tindakan,
                ( SELECT SUM(totalbiaya) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat AND `status`='Operasi' ) AS total_operasi,
                (
                    SELECT SUM(totalbiaya) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat AND `status`='Laborat'
            ) as laborat,
            (
                    SELECT SUM(totalbiaya) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat AND `status`='Radiologi'
            ) as radiologi,
            (
                    SELECT SUM(totalbiaya) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat AND `status`='Tambahan'
            ) as tambahan,
            (
                    SELECT SUM(totalbiaya) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat AND `status`='Potongan'
            ) as potongan,
            (
                SELECT SUM(totalbiaya) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat
            ) AS total_rs,
            (
                SELECT uangmuka FROM piutang_pasien WHERE reg_periksa.no_rawat=piutang_pasien.no_rawat
            ) AS ekses,
            (
                select sum(bayar_piutang.besar_cicilan) from bayar_piutang where bayar_piutang.no_rawat=reg_periksa.no_rawat
            ) AS sudah_dibayar,
            (
                select sum(bayar_piutang.diskon_piutang) from bayar_piutang where bayar_piutang.no_rawat=reg_periksa.no_rawat
            ) AS diskon_bayar,
            (
                select sum(bayar_piutang.tidak_terbayar) from bayar_piutang where bayar_piutang.no_rawat=reg_periksa.no_rawat
            ) AS tidak_terbayar,
            (
                (SELECT SUM(totalbiaya) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat) 
                - 
                (SELECT uangmuka FROM piutang_pasien WHERE reg_periksa.no_rawat=piutang_pasien.no_rawat)
                -
                (SELECT SUM(bayar_piutang.besar_cicilan) from bayar_piutang where bayar_piutang.no_rawat=reg_periksa.no_rawat)
                -
                (SELECT SUM(bayar_piutang.diskon_piutang) from bayar_piutang where bayar_piutang.no_rawat=reg_periksa.no_rawat)
                -
                (SELECT SUM(bayar_piutang.tidak_terbayar) from bayar_piutang where bayar_piutang.no_rawat=reg_periksa.no_rawat)
            )AS sisa,
            reg_periksa.stts as status_rawat,piutang_pasien.status as status_pelunasan,
            (SELECT tgl_bayar from bayar_piutang where bayar_piutang.no_rawat=piutang_pasien.no_rawat) as tgl_bayar_pelunasan,
            (
                    SELECT nama_bayar 
                    FROM akun_bayar 
                    LEFT JOIN bayar_piutang ON akun_bayar.kd_rek=bayar_piutang.kd_rek
                    WHERE bayar_piutang.no_rawat=reg_periksa.no_rawat
                    LIMIT 1
            ) as pelunasan,
            (SELECT catatan from bayar_piutang where bayar_piutang.no_rawat=piutang_pasien.no_rawat) as catatan,
            dokter.nm_dokter
            from reg_periksa 
            inner join pasien on reg_periksa.no_rkm_medis=pasien.no_rkm_medis 
            inner join penjab on reg_periksa.kd_pj=penjab.kd_pj 
            inner join dokter on reg_periksa.kd_dokter=dokter.kd_dokter 
            inner join poliklinik on reg_periksa.kd_poli=poliklinik.kd_poli 
            inner join piutang_pasien on piutang_pasien.no_rawat=reg_periksa.no_rawat
            LEFT JOIN nota_jalan on reg_periksa.no_rawat=nota_jalan.no_rawat
            LEFT JOIN rujuk_masuk ON reg_periksa.no_rawat=rujuk_masuk.no_rawat
            WHERE reg_periksa.status_lanjut='Ralan'
            AND DATE(reg_periksa.tgl_registrasi) BETWEEN '".$start."' and '".$end."' ";
            
            $sql = ($penjamin!="all") ? $sql."and reg_periksa.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = ($poli!="all") ? $sql."and reg_periksa.kd_poli= '".$poli."' ": $sql." ";
            $sql = ($status_pelunasan!="all") ? $sql."and piutang_pasien.status= '".$status_pelunasan."' ": $sql." ";
            $sql = $sql."order by reg_periksa.tgl_registrasi";

            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);
        }            

        $modul = $this->menu;
        return view('pendapatan.piutang_ralan', compact('modul'));
        
    }
}
