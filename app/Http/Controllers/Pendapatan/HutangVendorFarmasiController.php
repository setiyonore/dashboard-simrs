<?php

namespace App\Http\Controllers\Pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use DataTables;
use Carbon;

class HutangVendorFarmasiController extends Controller
{
    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'hutang-vendor-farmasi';    
    }

    public function index()
    {
        $modul = $this->menu;
        return view('pendapatan.hutang_vendor_farmasi', compact('modul'));
    }

    public function getHutangVendorFarmasiTanggalDatang(Request $request)
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
            $sql = "SELECT pemesanan.kode_suplier,datasuplier.nama_suplier,
            (SUM(pemesanan.tagihan)-(SELECT ifnull(SUM(besar_bayar),0) FROM bayar_pemesanan where bayar_pemesanan.no_faktur=pemesanan.no_faktur)) as sisahutang 
            from pemesanan 
            inner join datasuplier on pemesanan.kode_suplier=datasuplier.kode_suplier 
            where pemesanan.tgl_pesan BETWEEN '".$start."' and '".$end."' 
            AND (pemesanan.status='Belum Dibayar' or pemesanan.status='Belum Lunas') ";
            
            $sql = $sql."group by pemesanan.kode_suplier order by pemesanan.kode_suplier ";

            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);
        }            

        $modul = $this->menu;
        return view('pendapatan.hutang_vendor_farmasi_tgl_datang', compact('modul'));
        
    }

    public function getHutangVendorFarmasiTanggalTempo(Request $request)
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
            $sql = "SELECT pemesanan.kode_suplier,datasuplier.nama_suplier,
            (SUM(pemesanan.tagihan)-(SELECT ifnull(SUM(besar_bayar),0) FROM bayar_pemesanan where bayar_pemesanan.no_faktur=pemesanan.no_faktur)) as sisahutang 
            from pemesanan 
            inner join datasuplier on pemesanan.kode_suplier=datasuplier.kode_suplier 
            where pemesanan.tgl_tempo BETWEEN '".$start."' and '".$end."' 
            AND (pemesanan.status='Belum Dibayar' or pemesanan.status='Belum Lunas') ";
            
            $sql = $sql."group by pemesanan.kode_suplier order by pemesanan.kode_suplier ";

            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);
        }            

        $modul = $this->menu;
        return view('pendapatan.hutang_vendor_farmasi_tgl_tempo', compact('modul'));
        
    }

}
