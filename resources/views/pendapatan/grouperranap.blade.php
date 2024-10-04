@extends('layouts.management')

@section('title')
<title>Grouper Rawat Inap</title>
@endsection

@section('head')
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{ asset('ionicons-v2.0.1/css/ionicons.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<!-- Toastr -->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">
<!-- icheck bootstrap -->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
<!-- bootstrap datetime picker-->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">

@endsection


@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Grouper Rawat Inap BPJS Kesehatan</h1> 
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('manager.home') }}">Home</a></li>
              <li class="breadcrumb-item active">Grouper Rawat Inap</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section><!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
              <div class="card">
                <div class="card-header border-0">
                  <h3 class="card-title">Data Grouper Rawat Inap BPJS Kesehatan <div id ="filter_period_id"></div></h3>
                  <div class="margin float-right">
                    <a class="btn btn-info btn-sm float-right"  href="javascript:void(0)"
                      data-toggle="tooltip" data-placement="top" title="Filter data" id="btn-filter">
                      <i class="fas fa-filter"></i> Filter
                    </a>
                </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover table-striped" id="data-grouper-ranap" style="width: 200% !important">
                        <thead>
                            <tr>
                              <th>Tgl Register</th>
                              <th>No. Rawat</th>
                              <th>Status Periksa</th>
                              <th>Status Lanjut</th>
                              <th>Status Bayar</th>
                              <th>Nota Inap</th>
                              <th>Poli</th>
                              <th>Dokter Register</th> 
                              <th>Dokter DPJP</th>
                              <th>Dokter DPJP 2</th>
                              <th>Tgl. Masuk</th>
                              <th>Tgl. Keluar</th>
                              <th>RM</th>
                              <th>Pasien</th> 
                              <th>Kamar 1</th>
                              <th>Status Pulang Kamar 1</th>
                              <th>Kamar 2</th>
                              <th>Status Pulang Kamar 2</th>
                              <th>Kelas Rawat</th>
                              <th>Lama Inap</th>
                              <th>Penjamin</th>
                              <th>SEP</th>
                              <th>Code CBG</th>
                              <th>Deskripsi</th>
                              <th>Total Rs</th>
                              <th>INACBG</th>
                              <th>Selisih</th>
                              <th>Biaya Register</th>
                              <th>Pemeriksaan</th>
                              <th>Tindakan Ralan Dr</th>
                              <th>Tindakan Ralan Pr</th>
                              <th>Tindakan Ralan DrPr</th>
                              <th>Tindakan Ranap Dr</th>
                              <th>Tindakan Ranap Pr</th>
                              <th>Tindakan Ranap DrPr</th>
                              <th>Obat Ralan</th>
                              <th>Obat Ranap</th>
                              <th>Total Obat Belum PPN</th>
                              <th>Retur Obat</th>
                              <th>Total Obat Setelah Retur + PPN</th>
                              <th>Biaya Kamar</th>
                              <th>Laborat</th>
                              <th>Radiologi</th>
                              <th>Tambahan Lain</th>
                              <th>Potongan</th>
                              <th>Ekses</th>
                              <th>Operasi</th>
                              <th>Biaya Operator 1</th>
                              <th>Biaya Operator 2</th>
                              <th>Biaya Operator 3</th>
                              <th>Biaya Asisten Operator 1</th>
                              <th>Biaya Asisten Operator 2</th>
                              <th>Biaya Asisten Operator 3 </th>
                              <th>Biaya Instrumen</th>
                              <th>Biaya Dokter Anak</th>
                              <th>Biaya Perawat Resusitas</th>
                              <th>Biaya Dokter Anestesi</th>
                              <th>Biaya Asisten Anestesi</th>
                              <th>Biaya Asisten Anestesi 2</th>
                              <th>Biaya Bidan</th>
                              <th>Biaya Bidan 2</th>
                              <th>Biaya Bidan 3</th>
                              <th>Biaya Perawat Luar</th>
                              <th>Biaya Alat</th>
                              <th>Biaya Sewa OK</th>
                              <th>Akomodasi</th>
                              <th>Bagian RS</th>
                              <th>Biaya Omloop</th>
                              <th>Biaya Omloop 2</th>
                              <th>Biaya Omloop 3</th>
                              <th>Biaya Omloop 4</th>
                              <th>Biaya Omloop 5</th>
                              <th>Biaya Sarpras</th>
                              <th>Biaya Dokter PJ Anak</th>
                              <th>Biaya Dokter Umum</th>
                              <th>Total operasi</th>
                              <th>Devisi</th>
                            </tr>
                        </thead>
                    </table>
                    <!-- /.table -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.card -->

            
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

      </div>
      <!--/. container-fluid -->
    </section>
    <!-- /.content -->

    <!-- Filter data -->
    <div class="modal fade" id="filter-item-modal" role="dialog">
        <div class="modal-dialog">
            <form id="form-filter" name="form-filter" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="create-title">Filter Sesuai Tgl Keluar<br></h4>
                        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="dos">@Lang('common.date_start'):</label>
                                <div class="input-group date" id="date_start" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#date_start"/>
                                    <div class="input-group-append" data-target="#date_start" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="dos">@Lang('common.date_end'):</label>
                                <div class="input-group date" id="date_end" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#date_end"/>
                                    <div class="input-group-append" data-target="#date_end" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary float-right" id="submit-filter">@Lang('common.filter')</button>
                        <button type="button" class="btn btn-default float-right" id="submit-filter-cancel">@Lang('common.cancel')</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </form>
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

  </div>
  <!-- /.content-wrapper -->

@endsection

@section('includejs')
<!-- jQuery -->
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Momen -->
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/moment/moment-with-locales.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Toastr -->
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>
<!-- DataTables -->
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<!-- overlayScrollbars -->
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

@endsection


@section('javascript')
<script type="text/javascript">

$(document).ready(function () {
    var dateEnd;
    var dateStart;
    var datatableGrouperRanap;
    var selectedDoctor;
    var selectedPenjamin;
    var total = 0;

    $('[data-toggle="tooltip"]').tooltip();

    $('#date_start').datetimepicker({
        locale: 'id',
        viewMode: 'days',
        format:'L',
        startDate: new Date(),
    });

    $('#date_end').datetimepicker({
        locale: 'id',
        viewMode: 'days',
        format:'L',
    });

    $("#date_start").on("change.datetimepicker", function(e) {
      dateStart= moment(e.date).format("YYYY-MM-DD");
    });


    $("#date_end").on("change.datetimepicker", function (e) {
      dateEnd = moment(e.date).format("YYYY-MM-DD");
    });

  
    //Doctor
    $('#filter_doctor').change(function(){
      selectedDoctor = $(this).val();
    });


    $('#filter_penjab').change(function(){
      selectedPenjamin = $(this).val();
    });

    $('#btn-filter').click(function () {
        $('#dateStart').trigger("reset");
        $('#endStart').trigger('reset');
        $('#filter_doctor').trigger('reset');
        $('#filter_penjab').trigger('reset');
        $('#form-filter').trigger("reset");
        $('#filter-item-modal').modal('show');
    });

    $('#submit-filter-cancel').click(function () {
        $('#form-filter').trigger("reset");
        $('#filter-item-modal').modal('hide');
    });

    $('#submit-filter').click(function(e) {
        var period = "("+ moment(dateStart, "YYYY-MM-DD").format("DD MMM YYYY") + " - "+ moment(dateEnd, "YYYY-MM-DD").format("DD MMM YYYY") + ")"; 
        $('#filter_period_id').text(period); 
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#filter-item-modal').modal('hide');
        
        if ( $.fn.dataTable.isDataTable('#data-grouper-ranap')) {
          datatableGrouperRanap = $('#data-grouper-ranap').DataTable().destroy();
        }

        datatableGrouperRanap = $('#data-grouper-ranap').DataTable({
            responsive: true, 
            lengthChange: false, 
            autoWidth: false,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
            ],
            ajax:{
                url: "{{ route('pendapatan.grouperranap.list') }}",
                type: 'GET',
                data:{
                  start: dateStart, 
                  end: dateEnd, 
                  doctor: selectedDoctor,
                  penjamin: selectedPenjamin
                }
            },
            columns:[
                { data: 'tgl_registrasi', name: 'tgl_registrasi',
                  render: data => {
                        return moment(data, "YYYY-MM-DD").format("DD-MMM-YYYY");
                    }
                },
                { data: 'no_rawat', name: 'no_rawat'},
                { data: 'stts', name: 'stts' },
                { data: 'status_lanjut', name: 'status_lanjut' },
                { data: 'status_bayar', name: 'status_bayar'},
                { data: 'no_nota', name: 'no_nota'},
                { data: 'devisi_poli', name: 'devisi_poli'},
                { data: 'dokter_register', name: 'dokter_register'},
                { data: 'dokter_dpjp_ranap', name: 'dokter_dpjp_ranap'},
                { data: 'dokter_dpjp_2', name: 'dokter_dpjp_2' },
                { data: 'tgl_masuk', name: 'tgl_masuk',
                    render: data => {
                        return moment(data, "YYYY-MM-DD").format("DD-MM-YYYY");
                    }
                },
                { data: 'tgl_keluar', name: 'tgl_keluar',
                    render: data => {
                        return moment(data, "YYYY-MM-DD").format("DD-MM-YYYY");
                    }
                },
                { data: 'no_rkm_medis', name: 'no_rkm_medis'},
                { data: 'nm_pasien', name: 'nm_pasien'},
                { data: 'kamar_1', name: 'kamar_1' },
                { data: 'status_plg_kmr_1', name: 'status_plg_kmr_1' },
                { data: 'kamar_2', name: 'kamar_2' },
                { data: 'status_plg_kmr_2', name: 'status_plg_kmr_2' },
                { data: 'klsrawat', name: 'klsrawat'},
                { data: 'lama', name: 'lama'},
                { data: 'png_jawab', name: 'png_jawab'},
                { data: 'no_sep', name: 'no_sep'},
                { data: 'code_cbg', name: 'code_cbg'},
                { data: 'deskripsi', name: 'deskripsi'},
                { data: 'total_real_rs', name: 'total_real_rs'},
                { data: 'inacbg', name: 'inacbg'},
                { data: 'selisih', name: 'selisih'},
                { data: 'biaya_reg', name: 'biaya_reg'},
                { data: 'pemeriksaan', name: 'pemeriksaan'},
                { data: 'tindakan_ralan_dr', name: 'tindakan_ralan_dr'},
                { data: 'tindakan_ralan_pr', name: 'tindakan_ralan_pr'},
                { data: 'tindakan_ralan_dr_pr', name: 'tindakan_ralan_dr_pr'},
                { data: 'tindakan_ranap_dr', name: 'tindakan_ranap_dr'},
                { data: 'tindakan_ranap_pr', name: 'tindakan_ranap_pr'},
                { data: 'tindakan_ranap_dr_pr', name: 'tindakan_ranap_dr_pr'},
                { data: 'obat_ralan', name: 'obat_ralan'},
                { data: 'obat_ranap', name: 'obat_ranap'},
                { data: 'total_obat_belum_ppn', name: 'total_obat_belum_ppn'},
                { data: 'retur_obat', name: 'retur_obat'},
                { data: 'total_obat_setelah_retur_plus_ppn', name: 'total_obat_setelah_retur_plus_ppn'},
                { data: 'biaya_kamar', name: 'biaya_kamar'},
                { data: 'laborat', name: 'laborat'},
                { data: 'radiologi', name: 'radiologi'},
                { data: 'tambahan_lain_lain', name: 'tambahan_lain_lain'},
                { data: 'potongan', name: 'potongan'},
                { data: 'ekses', name: 'ekses'},
                { data: 'nm_perawatan', name: 'nm_perawatan' },
                { data: 'biayaoperator1', name: 'biayaoperator1'},
                { data: 'biayaoperator2', name: 'biayaoperator2'},
                { data: 'biayaoperator3', name: 'biayaoperator3'},
                { data: 'biayaasisten_operator1', name: 'biayaasisten_operator1'},
                { data: 'biayaasisten_operator2', name: 'biayaasisten_operator2'},
                { data: 'biayaasisten_operator3', name: 'biayaasisten_operator3'},
                { data: 'biayainstrumen', name: 'biayainstrumen'},
                { data: 'biayadokter_anak', name: 'biayadokter_anak'},
                { data: 'biayaperawaat_resusitas', name: 'biayaperawaat_resusitas'},
                { data: 'biayadokter_anestesi', name: 'biayadokter_anestesi'},
                { data: 'biayaasisten_anestesi', name: 'biayaasisten_anestesi'},
                { data: 'biayaasisten_anestesi2', name: 'biayaasisten_anestesi2'},
                { data: 'biayabidan', name: 'biayabidan'},
                { data: 'biayabidan2', name: 'biayabidan2'},
                { data: 'biayabidan3', name: 'biayabidan3'},
                { data: 'biayaperawat_luar', name: 'biayaperawat_luar'},
                { data: 'biayaalat', name: 'biayaalat'},
                { data: 'biayasewaok', name: 'biayasewaok'},
                { data: 'akomodasi', name: 'akomodasi'},
                { data: 'bagian_rs', name: 'bagian_rs'},
                { data: 'biaya_omloop', name: 'biaya_omloop'},
                { data: 'biaya_omloop2', name: 'biaya_omloop2'},
                { data: 'biaya_omloop3', name: 'biaya_omloop3'},
                { data: 'biaya_omloop4', name: 'biaya_omloop4'},
                { data: 'biaya_omloop5', name: 'biaya_omloop5'},
                { data: 'biayasarpras', name: 'biayasarpras'},
                { data: 'biaya_dokter_pjanak', name: 'biaya_dokter_pjanak'},
                { data: 'biaya_dokter_umum', name: 'biaya_dokter_umum'},
                { data: 'total_operasi', name: 'total_operasi'},
                { data: 'devisi', name: 'devisi'}
            ],
            
          });

          
    });
    
    // reset data request tanggal
    $('#filter-item-modal').on('hide.bs.modal', function(){
        $('#dateStart').trigger("reset");
        $('#endStart').trigger('reset');
        $('#filter_doctor').trigger('reset');
        $('#filter_poli').trigger('reset');
        $('#filter_penjab').trigger('reset');
        $('#form-filter').trigger("reset");
      
      
    });



});

</script>

@endsection
