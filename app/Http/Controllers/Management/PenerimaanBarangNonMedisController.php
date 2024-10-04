<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class PenerimaanBarangNonMedisController extends Controller
{
    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'penerimaan-barang-non-medis';    
    }

    public function index()
    {
        $modul = $this->menu;
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab  from penjab where status='1' order by png_jawab ");

        return view('management.penerimaan_barang.non_medis', compact('modul', 'penjab'));
    }

    public function getPenerimaanBarangNonMedis(Request $request)
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
            $sql = "SELECT
            e.nama_suplier,
            b.no_order,
            b.tgl_faktur,
            a.no_faktur,
            b.tgl_pesan,
            b.tgl_tempo,
            c.nama_brng,
            a.harga,
            a.jumlah,
            d.satuan,
            a.subtotal,
            a.besardis AS diskon,
            if(b.ppn=0,0,(a.subtotal - a.besardis)*0.11) as ppn,
            ((a.subtotal - a.besardis) + if(b.ppn=0,0,(a.subtotal - a.besardis)*0.11)) as total,
            b.status,f.tgl_bayar,f.nama_bayar
        FROM
            ipsrsdetailpesan a
            LEFT JOIN ipsrspemesanan b ON a.no_faktur = b.no_faktur
            LEFT JOIN ipsrsbarang c ON a.kode_brng = c.kode_brng
            LEFT JOIN kodesatuan d ON a.kode_sat = d.kode_sat
            LEFT JOIN ipsrssuplier e ON b.kode_suplier = e.kode_suplier
            LEFT JOIN bayar_pemesanan_non_medis f ON b.no_faktur = f.no_faktur
            WHERE b.tgl_faktur BETWEEN '".$start."' and '".$end."'";

            $sql = $sql."ORDER BY a.no_faktur ";
            
            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('management.penerimaan_barang.non_medis', compact('modul'));
        
    }    
}
