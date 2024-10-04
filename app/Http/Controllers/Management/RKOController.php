<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon\Carbon;

class RKOController extends Controller
{

    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'rko';
    }

    public function index(Request $request)
    {
        $modul = $this->menu;


        // $sql = "SELECT sum(jumlah) as jml,sum(jumlah) * 50 / 100 AS jumlah,
        //             dpob.kode_brng,
        //             d.nama_brng,
        //             MONTHNAME(pob.tanggal) AS month_name
        //         FROM pengeluaran_obat_bhp pob
        //         LEFT JOIN detail_pengeluaran_obat_bhp dpob
        //             ON dpob.no_keluar = pob.no_keluar
        //         LEFT JOIN databarang d
        //             ON d.kode_brng = dpob.kode_brng
        //         WHERE tanggal BETWEEN '2024-07-01' AND '2024-07-31'
        //         GROUP BY dpob.kode_brng, d.nama_brng, MONTHNAME(pob.tanggal),dpob.jumlah ;
        //         ";
        // $penjab = DB::connection('mysql_khanza')
        //     ->select($sql);

        return view('pendapatan.rko', compact('modul'));
    }

    public function getRKO(Request $request)
    {

        // if (!empty($request->start)) {
        //     $start = $request->start;
        // } else {
        //     $start = Carbon\Carbon::now()->isoFormat('YYYY-MM-DD');
        // }

        // if (!empty($request->end)) {
        //     $end = $request->end;
        // } else {
        //     $end = Carbon\Carbon::now()->isoFormat('YYYY-MM-DD');
        // }

        // $penjamin = !empty($request->penjamin) ?  $request->penjamin : "all";

        if (request()->ajax()) {
            $sql = "SELECT
            SUM(dpob.jumlah) as jml,
            SUM(dpob.jumlah) * " . $request->percent . " / 100 AS jumlah,
            dpob.kode_brng,
            d.nama_brng,
            MONTHNAME(pob.tanggal) AS month_name
        FROM pengeluaran_obat_bhp pob
        LEFT JOIN detail_pengeluaran_obat_bhp dpob
            ON dpob.no_keluar = pob.no_keluar
        LEFT JOIN databarang d
            ON d.kode_brng = dpob.kode_brng
        WHERE DATE_FORMAT(pob.tanggal, '%Y-%m') = '" . $request->month . "'
        GROUP BY dpob.kode_brng, d.nama_brng, month_name;";


            $tindakan = DB::connection('mysql_khanza')->select($sql);
            // dd($tindakan);

            return Datatables::of($tindakan)
                ->addIndexColumn()
                ->make(true);
        }

        $modul = $this->menu;
        return view('pendapatan.rko', compact('modul'));
    }
}
