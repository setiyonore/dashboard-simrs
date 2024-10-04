<?php

namespace App\Http\Controllers\management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class PenerimaanObatController extends Controller
{
    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'penerimaan-obat';    
    }

    public function index()
    {
        $modul = $this->menu;
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab  from penjab where status='1' order by png_jawab ");

        return view('management.penerimaan_barang.obat', compact('modul', 'penjab'));
    }

    public function getPenerimaanObat(Request $request)
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

        // $penjamin = !empty($request->penjamin) ?  $request->penjamin : "all";
        

        if(request()->ajax()){
            $sql = "select e.nama_suplier,
            b.no_order,
            b.tgl_faktur,
            a.no_faktur,
            b.tgl_pesan, 
            b.tgl_tempo, 
            c.nama_brng, 
            a.h_pesan, 
            a.jumlah, 
            d.satuan, 
            a.subtotal, 
            a.besardis as diskon,
            if(b.ppn=0,0,(a.subtotal - a.besardis)*0.11) as ppn,
            ((a.subtotal - a.besardis) + if(b.ppn=0,0,(a.subtotal - a.besardis)*0.11)) as total,
            b.status,f.tgl_bayar,f.nama_bayar
            from detailpesan a
            LEFT JOIN pemesanan b on a.no_faktur = b.no_faktur
            LEFT JOIN databarang c on a.kode_brng = c.kode_brng
            LEFT JOIN kodesatuan d on a.kode_sat = d.kode_sat
            LEFT JOIN datasuplier e on b.kode_suplier = e.kode_suplier
            LEFT JOIN bayar_pemesanan f on b.no_faktur = f.no_faktur
            WHERE b.tgl_faktur BETWEEN '".$start."' and '".$end."'";

            $sql = $sql."ORDER BY a.no_faktur ";
            
            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('management.penerimaan_barang.obat', compact('modul'));
        
    }    

}
