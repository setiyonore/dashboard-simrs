<?php

namespace App\Http\Controllers\Pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use DataTables;
use Carbon;

class HutangVendorNonMedisController extends Controller
{
    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'hutang-vendor-non-medis';    
    }

    public function index()
    {
        $modul = $this->menu;
        return view('pendapatan.hutang_vendor_non_medis', compact('modul'));
    }

    public function getHutangVendorNonMedisTanggalDatang(Request $request)
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
            $sql = "SELECT ipsrspemesanan.kode_suplier,ipsrssuplier.nama_suplier,
            (SUM(ipsrspemesanan.tagihan)-(SELECT ifnull(SUM(besar_bayar),0) FROM bayar_pemesanan_non_medis where bayar_pemesanan_non_medis.no_faktur=ipsrspemesanan.no_faktur)) as sisahutang 
            from ipsrspemesanan inner join ipsrssuplier on ipsrspemesanan.kode_suplier=ipsrssuplier.kode_suplier 
            where ipsrspemesanan.tgl_pesan BETWEEN '".$start."' and '".$end."' 
            and (ipsrspemesanan.status='Belum Dibayar' or ipsrspemesanan.status='Belum Lunas')";
            
            $sql = $sql."group by ipsrspemesanan.kode_suplier order by ipsrspemesanan.kode_suplier";

            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);
        }            

        $modul = $this->menu;
        return view('pendapatan.hutang_vendor_non_medis_tgl_datang', compact('modul'));
        
    }

    public function getHutangVendorNonMedisTanggalTempo(Request $request)
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
            $sql = "SELECT ipsrspemesanan.kode_suplier,ipsrssuplier.nama_suplier,
            (SUM(ipsrspemesanan.tagihan)-(SELECT ifnull(SUM(besar_bayar),0) FROM bayar_pemesanan_non_medis where bayar_pemesanan_non_medis.no_faktur=ipsrspemesanan.no_faktur)) as sisahutang 
            from ipsrspemesanan inner join ipsrssuplier on ipsrspemesanan.kode_suplier=ipsrssuplier.kode_suplier 
            where ipsrspemesanan.tgl_tempo BETWEEN '".$start."' and '".$end."' 
            and (ipsrspemesanan.status='Belum Dibayar' or ipsrspemesanan.status='Belum Lunas')";
            
            $sql = $sql."group by ipsrspemesanan.kode_suplier order by ipsrspemesanan.kode_suplier";

            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);
        }            

        $modul = $this->menu;
        return view('pendapatan.hutang_vendor_non_medis_tgl_tempo', compact('modul'));
        
    }

}
