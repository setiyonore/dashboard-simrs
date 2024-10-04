<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class StokKeluarMedisController extends Controller
{

    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'stok-keluar-medis';  
    }

    public function index(Request $request)
    {
        $modul = $this->menu;
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj,png_jawab from penjab where status='1' order by png_jawab ");

        return view('pendapatan.stok_keluar_medis', compact('modul','penjab'));
    }

    public function getStokKeluarMedis(Request $request)
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
        
     
        if (request()->ajax()) {
        $sql = " SELECT
        pengeluaran_obat_bhp.no_keluar,
        pengeluaran_obat_bhp.tanggal,
        pengeluaran_obat_bhp.keterangan,
        detail_pengeluaran_obat_bhp.kode_brng,
        databarang.nama_brng,
        detail_pengeluaran_obat_bhp.kode_sat,
        detail_pengeluaran_obat_bhp.harga_beli,
        detail_pengeluaran_obat_bhp.jumlah,
        detail_pengeluaran_obat_bhp.total,        
        petugas.nama AS petugas,
        bangsal.nm_bangsal AS depo
        FROM
        pengeluaran_obat_bhp
        LEFT JOIN bangsal ON pengeluaran_obat_bhp.kd_bangsal = bangsal.kd_bangsal
        LEFT JOIN petugas ON pengeluaran_obat_bhp.nip = petugas.nip
        LEFT JOIN detail_pengeluaran_obat_bhp ON pengeluaran_obat_bhp.no_keluar = detail_pengeluaran_obat_bhp.no_keluar
        LEFT JOIN databarang ON detail_pengeluaran_obat_bhp.kode_brng = databarang.kode_brng
        WHERE date (pengeluaran_obat_bhp.tanggal) BETWEEN '" . $start . "' and '" . $end . "'
        ORDER BY pengeluaran_obat_bhp.no_keluar";

        $tindakan = DB::connection('mysql_khanza')->select($sql);
        // dd($tindakan);

        return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('pendapatan.stok_keluar_medis', compact('modul'));
        
    }    
}