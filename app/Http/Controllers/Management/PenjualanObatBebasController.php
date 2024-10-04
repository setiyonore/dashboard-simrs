<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class PenjualanObatBebasController extends Controller
{

    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'penjualan-obat-bebas';  
    }

    public function index(Request $request)
    {
        $modul = $this->menu;
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj,png_jawab from penjab where status='1' order by png_jawab ");

        return view('pendapatan.penjualan_obat_bebas', compact('modul','penjab'));
    }

    public function getPenjualanObatBebas(Request $request)
    {
        if (!empty($request->start)) {
            $start = $request->start;
        } else {
            $start = Carbon\Carbon::now()->isoFormat('YYYY-MM-DD');
        }

        if (!empty($request->end)) {
            $end = $request->end;
        } else {
            $end = Carbon\Carbon::now()->isoFormat('YYYY-MM-DD');
        }

        // $penjamin = !empty($request->penjamin) ?  $request->penjamin : "all";

        if (request()->ajax()) {
            $sql = " SELECT
        penjualan.nota_jual,
        penjualan.tgl_jual,
        penjualan.no_rkm_medis,
        penjualan.nm_pasien,
        detailjual.kode_brng,
        databarang.nama_brng,
        databarang.kode_sat,
        detailjual.h_beli,
        detailjual.h_jual,
        detailjual.jumlah,
        (detailjual.h_beli * detailjual.jumlah) AS harga_beli_x_jml,
        detailjual.total AS 'harga_jual_x_jml',
        -- ((detailjual.h_beli * detailjual.jumlah) + ((detailjual.h_beli * detailjual.jumlah) * 0.11)) AS 'harga_beli_x_jml+ppn',
        (detailjual.total + (detailjual.total * 0.11)) AS 'harga_jual_x_jml+ppn',
        penjualan.jns_jual,
        petugas.nama AS petugas,
        bangsal.nm_bangsal AS depo,
        penjualan.status
        FROM
        penjualan
        LEFT JOIN bangsal ON penjualan.kd_bangsal = bangsal.kd_bangsal
        LEFT JOIN petugas ON penjualan.nip = petugas.nip
        LEFT JOIN detailjual ON penjualan.nota_jual = detailjual.nota_jual
        LEFT JOIN databarang ON detailjual.kode_brng = databarang.kode_brng
        WHERE date (penjualan.tgl_jual) BETWEEN '" . $start . "' and '" . $end . "'
        ORDER BY penjualan.nota_jual";

            $tindakan = DB::connection('mysql_khanza')->select($sql);
            // dd($tindakan);

            return Datatables::of($tindakan)
                ->addIndexColumn()
                ->make(true);
        }

        $modul = $this->menu;
        return view('pendapatan.penjualan_obat_bebas', compact('modul'));
    }
   
}