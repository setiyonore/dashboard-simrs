<?php

namespace App\Http\Controllers\Pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class PiutangRanapController extends Controller
{
    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'piutang-ranap';    
    }

    public function index()
    {
        $modul = $this->menu;
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab  from penjab where status='1' order by png_jawab ");

        return view('pendapatan.piutang_ranap', compact('modul', 'penjab'));
    }

    public function getPiutangRanap(Request $request)
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
        $status_pelunasan = !empty($request->status_pelunasan) ?  $request->status_pelunasan : "all";

        if(request()->ajax()){
            $sql = "SELECT kamar_inap.tgl_keluar,nota_inap.no_nota,reg_periksa.no_rkm_medis,pasien.nm_pasien,penjab.png_jawab,
            rujuk_masuk.perujuk,
            (select billing.totalbiaya FROM billing 
            WHERE billing.no_rawat=reg_periksa.no_rawat
            AND billing.status='Registrasi')as registrasi,
            (
                (SELECT SUM(totalbiaya) 
                FROM billing 
                WHERE reg_periksa.no_rawat=billing.no_rawat
                AND `status` IN ('Ralan Dokter','Ralan Paramedis','Ralan Dokter Paramedis','Ranap Dokter','Ranap Paramedis', 'Ranap Dokter Paramedis')
                )	
            )AS tindakan,
            (SELECT SUM(totalbiaya) 
            FROM billing 
            WHERE reg_periksa.no_rawat=billing.no_rawat 
            AND `status`='Obat') as obat_plus_ppn,
            (SELECT SUM(totalbiaya) 
            FROM billing 
            WHERE reg_periksa.no_rawat=billing.no_rawat 
            AND `status`='Retur Obat') as retur_obat_plus_ppn,
            (SELECT SUM(totalbiaya) 
            FROM billing 
            WHERE reg_periksa.no_rawat=billing.no_rawat 
            AND `status`='Resep Pulang') as resep_pulang,
                (
                    SELECT SUM(totalbiaya) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat AND `status`='Laborat'
            ) as laborat,
            (
                    SELECT SUM(totalbiaya) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat AND `status`='Radiologi'
            ) as radiologi,
            (
                    SELECT SUM(totalbiaya) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat AND `status`='Potongan'
            ) as potongan,
            (
                    SELECT SUM(totalbiaya) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat AND `status`='Tambahan'
            ) as tambahan,
            (SELECT SUM(totalbiaya) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat AND `status`='Kamar') as biaya_kamar,
            ( SELECT SUM(totalbiaya) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat AND `status`='Operasi' ) AS total_operasi,
            (SELECT SUM(totalbiaya) 
            FROM billing 
            WHERE reg_periksa.no_rawat=billing.no_rawat 
            AND `status`='Harian') as harian,
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
            )AS sisa,bangsal.nm_bangsal,
            piutang_pasien.status as status_pelunasan,
            (
                    SELECT nama_bayar 
                    FROM akun_bayar 
                    LEFT JOIN bayar_piutang ON akun_bayar.kd_rek=bayar_piutang.kd_rek
                    WHERE bayar_piutang.no_rawat=reg_periksa.no_rawat
                    LIMIT 1
            ) as pelunasan,
            (SELECT catatan from bayar_piutang where bayar_piutang.no_rawat=piutang_pasien.no_rawat) as catatan,
            (SELECT tgl_bayar from bayar_piutang where bayar_piutang.no_rawat=piutang_pasien.no_rawat) as tgl_bayar_pelunasan,
            bangsal.nm_bangsal
            from kamar_inap 
            inner join reg_periksa on kamar_inap.no_rawat=reg_periksa.no_rawat 
            inner join pasien on reg_periksa.no_rkm_medis=pasien.no_rkm_medis 
            inner join penjab on reg_periksa.kd_pj=penjab.kd_pj 
            inner join kamar on kamar_inap.kd_kamar=kamar.kd_kamar 
            inner join bangsal on kamar.kd_bangsal=bangsal.kd_bangsal 
            inner join piutang_pasien on piutang_pasien.no_rawat=reg_periksa.no_rawat 
            LEFT JOIN nota_inap on kamar_inap.no_rawat=nota_inap.no_rawat
            LEFT JOIN rujuk_masuk ON reg_periksa.no_rawat=rujuk_masuk.no_rawat
            where kamar_inap.tgl_keluar between '".$start."' and '".$end."'
            AND NOT kamar_inap.stts_pulang='Pindah Kamar' 
            AND reg_periksa.status_lanjut='Ranap' ";
            
            $sql = ($penjamin!="all") ? $sql."and reg_periksa.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = ($status_pelunasan!="all") ? $sql."and piutang_pasien.status= '".$status_pelunasan."' ": $sql." ";
            $sql = $sql."order by kamar_inap.tgl_keluar,kamar_inap.jam_keluar";

            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);
        }            

        $modul = $this->menu;
        return view('pendapatan.piutang_ranap', compact('modul'));
        
    }
}