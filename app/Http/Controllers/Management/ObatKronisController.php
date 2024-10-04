<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class ObatKronisController extends Controller
{

    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'obat-kronis';  
    }

    public function index(Request $request)
    {
        $modul = $this->menu;
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab from penjab where status='1' order by png_jawab");

        return view('pendapatan.obat_kronis', compact('modul', 'penjab'));
    }

    public function getObatKronis(Request $request)
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
            piutang.nota_piutang,LEFT(piutang.catatan,17) as no_rawat,
            (
                SELECT no_sep from bridging_sep where no_rawat = LEFT(piutang.catatan,17)
            ) as sep,
            piutang.tgl_piutang,
            piutang.no_rkm_medis,
            piutang.nm_pasien,
            detailpiutang.kode_brng,
            databarang.nama_brng,
            detailpiutang.h_beli,
            detailpiutang.h_jual,
            detailpiutang.jumlah,
            (detailpiutang.h_beli * detailpiutang.jumlah) AS harga_beli_x_jml,
            detailpiutang.total AS 'harga_jual_x_jml',
            penjab.png_jawab,
            poliklinik.devisi_poli,
            petugas.nama AS petugas,
            piutang.tgltempo,
            bangsal.nm_bangsal AS depo,
            reg_periksa.status_lanjut
        FROM
            piutang
            LEFT JOIN reg_periksa ON LEFT(piutang.catatan,17) = reg_periksa.no_rawat
            LEFT JOIN penjab ON reg_periksa.kd_pj = penjab.kd_pj
            LEFT JOIN poliklinik ON reg_periksa.kd_poli = poliklinik.kd_poli
            LEFT JOIN bangsal ON piutang.kd_bangsal = bangsal.kd_bangsal
            LEFT JOIN petugas ON piutang.nip = petugas.nip
            LEFT JOIN detailpiutang ON piutang.nota_piutang = detailpiutang.nota_piutang
            LEFT JOIN databarang ON detailpiutang.kode_brng = databarang.kode_brng
            WHERE date(piutang.tgl_piutang) BETWEEN '".$start."' and '".$end."'";

            $sql = $sql."ORDER BY piutang.nota_piutang";
            
            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('pendapatan.obat_kronis', compact('modul'));
        
    }    
}