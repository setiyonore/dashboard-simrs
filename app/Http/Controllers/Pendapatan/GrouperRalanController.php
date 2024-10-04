<?php

namespace App\Http\Controllers\Pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class GrouperRalanController extends Controller {
    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'grouper-ralan';    
    }

    public function index()
    {
        $modul = $this->menu;
        $doctor = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter  from dokter order by nm_dokter");
        $poli = DB::connection('mysql_khanza')->select("select kd_poli, nm_poli  from poliklinik  order by nm_poli ");
        
        return view('pendapatan.grouperralan', compact('modul', 'doctor', 'poli'));
    }

    public function getGrouperRalan(Request $request)
    {   
        // dd($request->start, $request->end);
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

        $doctor = !empty($request->doctor) ?  $request->doctor : "all";
        $poli = !empty($request->poli) ?  $request->poli : "all";
        

        if(request()->ajax()){
            //Get Obat Ralan PerDokter
            $sql = "SELECT reg_periksa.no_rawat,reg_periksa.status_bayar,reg_periksa.stts,reg_periksa.status_lanjut,reg_periksa.biaya_reg,
            (
                SELECT no_nota FROM nota_jalan WHERE reg_periksa.no_rawat=nota_jalan.no_rawat
            ) as nota_jalan,
            reg_periksa.tgl_registrasi,reg_periksa.no_rkm_medis,pasien.nm_pasien,poliklinik.devisi_poli,dokter.nm_dokter,penjab.png_jawab,
            (
                SELECT poliklinik.devisi_poli FROM poliklinik LEFT JOIN rujukan_internal_poli ON poliklinik.kd_poli = rujukan_internal_poli.kd_poli WHERE reg_periksa.no_rawat = rujukan_internal_poli.no_rawat LIMIT 1 
            ) AS poli_rujukan,
            (  
                SELECT dokter.nm_dokter FROM dokter LEFT JOIN rujukan_internal_poli ON dokter.kd_dokter = rujukan_internal_poli.kd_dokter WHERE reg_periksa.no_rawat = rujukan_internal_poli.no_rawat LIMIT 1 
            ) AS dokter_rujukan,
            (
		        SELECT bridging_sep.no_sep FROM bridging_sep WHERE bridging_sep.no_rawat=reg_periksa.no_rawat AND jnspelayanan='2' LIMIT 1 
            ) as no_sep,
            (
                SELECT inacbg_grouping_stage12.code_cbg FROM inacbg_grouping_stage12 LEFT JOIN bridging_sep ON inacbg_grouping_stage12.no_sep=bridging_sep.no_sep WHERE bridging_sep.no_rawat=reg_periksa.no_rawat LIMIT 1
            ) as code_cbg,
            (
                SELECT inacbg_grouping_stage12.deskripsi FROM inacbg_grouping_stage12 LEFT JOIN bridging_sep ON inacbg_grouping_stage12.no_sep=bridging_sep.no_sep WHERE bridging_sep.no_rawat=reg_periksa.no_rawat LIMIT 1
            ) as deskripsi,
            (
                SELECT piutang_pasien.totalpiutang FROM piutang_pasien WHERE reg_periksa.no_rawat=piutang_pasien.no_rawat
            ) as total_real_rs,
            (
                SELECT inacbg_grouping_stage12.tarif FROM inacbg_grouping_stage12 LEFT JOIN bridging_sep ON inacbg_grouping_stage12.no_sep=bridging_sep.no_sep WHERE bridging_sep.no_rawat=reg_periksa.no_rawat LIMIT 1
            ) as inacbg,
            (
                SELECT (inacbg_grouping_stage12.tarif - piutang_pasien.sisapiutang) as selisih FROM inacbg_grouping_stage12 
                LEFT JOIN bridging_sep ON inacbg_grouping_stage12.no_sep=bridging_sep.no_sep
                LEFT JOIN piutang_pasien ON bridging_sep.no_rawat=piutang_pasien.no_rawat	
                WHERE bridging_sep.no_rawat=reg_periksa.no_rawat LIMIT 1
            ) as selisih,
            (
            SELECT SUM(totalbiaya) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat AND nm_perawatan IN ('PEMERIKSAAN/KONSUL DOKTER SPESIALIS (D)','PEMERIKSAAN/KONSULTASI DR SPESIALIS', 'PEMERIKSAAN/KONSULTASI DR UMUM/IGD', 'PEMERIKSAAN/ DOKTER SPESIALIS (D)', 'PEMERIKSAAN/KONSUL DOKTER IGD (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (DPJP 1) (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (KONSULAN) (D)', 'PEMERIKSAAN/KONSUL DOKTER UMUM (D)') LIMIT 1
            ) AS pemeriksaan,
            -- (
                -- SELECT SUM(totalbiaya) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat AND billing.status ='Ralan Dokter'
            -- )AS tindakan_dr,
            -- (
                -- SELECT SUM(totalbiaya) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat AND billing.status ='Ralan Paramedis'
            -- )AS tindakan_pr,
            (
	        (SELECT SUM(totalbiaya) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat AND billing.status ='Ralan Dokter Paramedis' AND nm_perawatan NOT IN ('PEMERIKSAAN/KONSUL DOKTER SPESIALIS (D)','PEMERIKSAAN/KONSULTASI DR SPESIALIS', 'PEMERIKSAAN/KONSULTASI DR UMUM/IGD', 'PEMERIKSAAN/ DOKTER SPESIALIS (D)', 'PEMERIKSAAN/KONSUL DOKTER IGD (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (DPJP 1) (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (KONSULAN) (D)', 'PEMERIKSAAN/KONSUL DOKTER UMUM (D)') LIMIT 1)
	        -
            (SELECT SUM(totalbiaya) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat AND billing.status ='Ralan Dokter Paramedis' AND nm_perawatan NOT IN ('PEMERIKSAAN/KONSUL DOKTER SPESIALIS (D)','PEMERIKSAAN/KONSULTASI DR SPESIALIS', 'PEMERIKSAAN/KONSULTASI DR UMUM/IGD', 'PEMERIKSAAN/ DOKTER SPESIALIS (D)', 'PEMERIKSAAN/KONSUL DOKTER IGD (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (DPJP 1) (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (KONSULAN) (D)', 'PEMERIKSAAN/KONSUL DOKTER UMUM (D)') LIMIT 1)
	        -- (SELECT SUM(totalbiaya) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat AND nm_perawatan IN ('PEMERIKSAAN/KONSULTASI DR SPESIALIS', 'PEMERIKSAAN/KONSULTASI DR UMUM/IGD', 'PEMERIKSAAN/ DOKTER SPESIALIS (D)', 'PEMERIKSAAN/KONSUL DOKTER IGD (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (DPJP 1) (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (KONSULAN) (D)', 'PEMERIKSAAN/KONSUL DOKTER UMUM (D)') LIMIT 1)
            )AS tindakan_dr_pr,
            (SELECT SUM( totalbiaya ) FROM billing WHERE reg_periksa.no_rawat = billing.no_rawat AND `status` = 'Obat' ) AS obat_plus_ppn,
            (SELECT SUM( totalbiaya ) FROM billing WHERE reg_periksa.no_rawat = billing.no_rawat AND `status` = 'Laborat' ) AS laborat,
            (SELECT SUM( totalbiaya ) FROM billing WHERE reg_periksa.no_rawat = billing.no_rawat AND `status` = 'Radiologi' ) AS radiologi,
            (SELECT SUM( totalbiaya ) FROM billing WHERE billing.no_rawat = reg_periksa.no_rawat AND `status` = 'Tambahan' ) AS tambahan_lain_lain,
            (SELECT SUM( totalbiaya ) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat AND `status`='Potongan') as potongan,
            (
                SELECT uangmuka FROM piutang_pasien WHERE reg_periksa.no_rawat=piutang_pasien.no_rawat
            ) AS ekses,
            (
            (
                SELECT COALESCE(SUM(totalbiaya),0) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat AND billing.status ='Ralan Dokter'AND nm_perawatan NOT IN ('PEMERIKSAAN/KONSUL DOKTER SPESIALIS (D)', 'PEMERIKSAAN/KONSULTASI DR SPESIALIS', 'PEMERIKSAAN/KONSULTASI DR UMUM/IGD', 'PEMERIKSAAN/ DOKTER SPESIALIS (D)', 'PEMERIKSAAN/KONSUL DOKTER IGD (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (DPJP 1) (D)', 'PEMERIKSAAN/KONSUL DOKTER SPESIALIS (KONSULAN) (D)', 'PEMERIKSAAN/KONSUL DOKTER UMUM (D)') LIMIT 1
            ) 
            +
            (
                SELECT COALESCE(SUM(tarif_tindakandr),0) 
                FROM rawat_jl_drpr 
                WHERE reg_periksa.no_rawat=rawat_jl_drpr.no_rawat
                AND rawat_jl_drpr.kd_jenis_prw NOT IN 
                -- PEMERIKSAAN/KONSULTASI DR SPESIALIS
                ('10744','11582','12001','12839','13677','14096','16191','17029','17448','1945','19923','20240','20557','20874','21191','21508','21825','22142','22459','22776','23093','23410','2364','23727','24044','24361','24678','24995','25312','25629','25946','262','26263','26580','26897','27214','27531','2783','27848','28165','28482','28799','29116','29433','29750','30067','30384','30701','31018','31335','31652','31969','3202','32286','32603','32920','33237','33554','33871','34188','34505','34822','35139','35455','35771','36075','3621','36350','36625','36900','37175','37450','37725','38000','38275','38550','38825','39100','39375','39650','39925','40200','4040','40475','40750','41025','41300','41575','41850','42125','42400','42675','42950','43225','43500','43775','44050','44325','4459','44600','44875','45150','45425','45700','45975','46250','46525','46800','47075','47350','47625','47900','48175','48450','48725','49000','49275','49550','49825','50100','50375','50650','50925','51200','51475','51750','52026','52302','52579','52856','5297','53133','53410','5716','6135','6554','6973','7392','8649','9068','9906', 
                -- PEMERIKSAAN/KONSULTASI DR UMUM/IGD
                '19924','20241','20558','20875','21192','21509','21826','22143','22460','22777','23094','23411','23728','24045','24362','24679','24996','25313','25630','25947','26264','264','26581','26898','27215','27532','27849','28166','28483','28800','29117','29434','29751','30068','30385','30702','31019','31336','31653','31970','32287','32604','32921','33238','33555','33872','34189','34506','34823','35140','35456','35772','36076','36351','36626','36901','37176','37451','37726','38001','38276','38551','38826','39101','39376','39651','39926','40201','40476','40751','41026','41301','41576','41851','42126','42401','42676','42951','43226','43501','43776','44051','44326','44601','44876','45151','45426','45701','45976','46251','46526','46801','47076','47351','47626','47901','48176','48451','48726','49001','49276','49551','49826','50101','50376','50651','50926','51201','51476','51751','52027','52303','52580','52857','53134','53411',
                -- PEMERIKSAAN/ DOKTER SPESIALIS (D)
                '2',
                -- PEMERIKSAAN/KONSUL DOKTER SPESIALIS (DPJP 1) (D)
                '91',
                -- PEMERIKSAAN/KONSUL DOKTER SPESIALIS (KONSULAN) (D)
                '92',
                -- PEMERIKSAAN/KONSUL DOKTER UMUM (D)
                '64',
                -- PEMERIKSAAN/KONSUL DOKTER IGD (D)
                '1')
            )
            )AS tindakan_dr,
            (
            (
                SELECT COALESCE(SUM(totalbiaya),0) FROM billing WHERE reg_periksa.no_rawat=billing.no_rawat AND billing.status ='Ralan Paramedis'
            )
            +               
                (SELECT COALESCE(SUM(tarif_tindakanpr) + SUM(material) + SUM(bhp) + SUM(kso) + SUM(menejemen),0) 
                    FROM rawat_jl_drpr 
                    WHERE reg_periksa.no_rawat=rawat_jl_drpr.no_rawat
                    AND rawat_jl_drpr.kd_jenis_prw NOT IN 
                    -- PEMERIKSAAN/KONSULTASI DR SPESIALIS
                    ('10744','11582','12001','12839','13677','14096','16191','17029','17448','1945','19923','20240','20557','20874','21191','21508','21825','22142','22459','22776','23093','23410','2364','23727','24044','24361','24678','24995','25312','25629','25946','262','26263','26580','26897','27214','27531','2783','27848','28165','28482','28799','29116','29433','29750','30067','30384','30701','31018','31335','31652','31969','3202','32286','32603','32920','33237','33554','33871','34188','34505','34822','35139','35455','35771','36075','3621','36350','36625','36900','37175','37450','37725','38000','38275','38550','38825','39100','39375','39650','39925','40200','4040','40475','40750','41025','41300','41575','41850','42125','42400','42675','42950','43225','43500','43775','44050','44325','4459','44600','44875','45150','45425','45700','45975','46250','46525','46800','47075','47350','47625','47900','48175','48450','48725','49000','49275','49550','49825','50100','50375','50650','50925','51200','51475','51750','52026','52302','52579','52856','5297','53133','53410','5716','6135','6554','6973','7392','8649','9068','9906', 
                    -- PEMERIKSAAN/KONSULTASI DR UMUM/IGD
                    '19924','20241','20558','20875','21192','21509','21826','22143','22460','22777','23094','23411','23728','24045','24362','24679','24996','25313','25630','25947','26264','264','26581','26898','27215','27532','27849','28166','28483','28800','29117','29434','29751','30068','30385','30702','31019','31336','31653','31970','32287','32604','32921','33238','33555','33872','34189','34506','34823','35140','35456','35772','36076','36351','36626','36901','37176','37451','37726','38001','38276','38551','38826','39101','39376','39651','39926','40201','40476','40751','41026','41301','41576','41851','42126','42401','42676','42951','43226','43501','43776','44051','44326','44601','44876','45151','45426','45701','45976','46251','46526','46801','47076','47351','47626','47901','48176','48451','48726','49001','49276','49551','49826','50101','50376','50651','50926','51201','51476','51751','52027','52303','52580','52857','53134','53411',
                    -- PEMERIKSAAN/ DOKTER SPESIALIS (D)
                    '2',
                    -- PEMERIKSAAN/KONSUL DOKTER SPESIALIS (DPJP 1) (D)
                    '91',
                    -- PEMERIKSAAN/KONSUL DOKTER SPESIALIS (KONSULAN) (D)
                    '92',
                    -- PEMERIKSAAN/KONSUL DOKTER UMUM (D)
                    '64',
                    -- PEMERIKSAAN/KONSUL DOKTER IGD (D)
                    '1')
            )
            )AS tindakan_pr,
            paket_operasi.nm_perawatan,operasi.biayaoperator1,operasi.biayaoperator2,operasi.biayaoperator3,
            operasi.biayaasisten_operator1,operasi.biayaasisten_operator2,operasi.biayaasisten_operator3,
            operasi.biayainstrumen,operasi.biayadokter_anak,operasi.biayaperawaat_resusitas,
            operasi.biayadokter_anestesi,operasi.biayaasisten_anestesi,operasi.biayaasisten_anestesi2,operasi.biayabidan,operasi.biayabidan2,operasi.biayabidan3,operasi.biayaperawat_luar,operasi.biayaalat,operasi.biayasewaok,operasi.akomodasi,operasi.bagian_rs,operasi.biaya_omloop,operasi.biaya_omloop2,operasi.biaya_omloop3,operasi.biaya_omloop4,operasi.biaya_omloop5,operasi.biayasarpras,operasi.biaya_dokter_pjanak,operasi.biaya_dokter_umum,
            (
            SELECT SUM(totalbiaya) FROM billing WHERE billing.no_rawat=reg_periksa.no_rawat AND billing.`status`='Operasi'
            ) AS total_operasi
            FROM reg_periksa
            INNER JOIN pasien ON reg_periksa.no_rkm_medis=pasien.no_rkm_medis
            INNER JOIN poliklinik ON reg_periksa.kd_poli=poliklinik.kd_poli
            INNER JOIN dokter ON reg_periksa.kd_dokter=dokter.kd_dokter
            INNER JOIN penjab ON reg_periksa.kd_pj=penjab.kd_pj
            LEFT JOIN operasi ON reg_periksa.no_rawat=operasi.no_rawat
            LEFT JOIN paket_operasi on operasi.kode_paket=paket_operasi.kode_paket
            WHERE reg_periksa.status_lanjut='Ralan'
            AND reg_periksa.kd_pj='BPJ'
            AND DATE(reg_periksa.tgl_registrasi) BETWEEN '".$start."' AND '".$end."' ";

            $sql = ($doctor!="all") ? $sql."and reg_periksa.kd_dokter= '".$doctor."' ": $sql." ";
            $sql = ($poli!="all") ? $sql."and reg_periksa.kd_poli= '".$poli."' ": $sql." ";
            $sql = $sql."ORDER BY reg_periksa.tgl_registrasi";
            
            
            $grouperralan = DB::connection('mysql_khanza')->select($sql);
            
            return Datatables::of($grouperralan)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('pendapatan.grouperralan', compact('modul'));
        
    }
}
?>