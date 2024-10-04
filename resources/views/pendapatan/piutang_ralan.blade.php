@extends('layouts.management')

@section('title')
<title>Piutang Ralan</title>
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
            <h1>Piutang Ralan</h1> 
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('manager.home') }}">Home</a></li>
              <li class="breadcrumb-item active">Piutang Ralan</li>
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
                  <h3 class="card-title">Data Piutang Ralan<div id ="filter_period_id"></div></h3>
                  <div class="margin float-right">
                    <a class="btn btn-info btn-sm float-right"  href="javascript:void(0)"
                      data-toggle="tooltip" data-placement="top" title="Filter data" id="btn-filter">
                      <i class="fas fa-filter"></i> Filter
                    </a>
                </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover table-striped" id="data-piutang-ralan" style="width: 150% !important">
                        <thead>
                            <tr>
                              <th>Tanggal Register</th>
                              <th>No. Nota</th>
                              <th>No. RM</th>
                              <th>Nama Pasien</th>
                              <th>jenis Bayar</th>
                              <th>Perujuk</th>
                              <th>Registrasi</th>
                              <th>Obat</th>
                              <th>Paket Tindakan</th>
                              <th>Operasi</th>
                              <th>Laborat</th>
                              <th>Radiologi</th>
                              <th>Tambahan</th>
                              <th>Potongan</th>
                              <th>Total</th>
                              <th>Ekses</th>
                              <th>Sudah Bayar</th>
                              <th>Diskon Bayar</th>
                              <th>Tidak Terbayar</th>
                              <th>Sisa</th>
                              <th>Status Rawat</th>
                              <th>Status Pelunasan</th>
                              <th>Pelunasan</th>
                              <th>Tgl Pelunasan</th>
                              <th>Catatan</th>
                              <th>Dokter</th>
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
                        <h4 class="create-title">@Lang('common.filter')</h4>
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

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="filter_penjab">@Lang('common.penjamin'):</label>
                            <select name="filter_penjab" id="filter_penjab" class="form-control">
                              <option value="all">Semua</option>
                              @foreach($penjab ?? '' as $p )
                              <option value="{{ $p->kd_pj }}">{{ $p->png_jawab }}</option>
                              @endforeach  
                            </select>
                          </div>  
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="filter_poli">@Lang('common.poly'):</label>
                            <select name="filter_poli" id="filter_poli" class="form-control">
                              <option value="all">Semua</option>
                              @foreach($poli ?? '' as $p )
                              <option value="{{ $p->kd_poli }}">{{ $p->nm_poli }}</option>
                              @endforeach  
                            </select>
                          </div>  
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="filter_pelunasan">@Lang('common.status_pelunasan'):</label>
                            <select name="filter_pelunasan" id="filter_pelunasan" class="form-control">
                              <option value="all">Semua</option>
                              <option value="Lunas">Lunas</option>
                              <option value="Belum Lunas">Belum Lunas</option>
                            </select>
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
    var datatableRalan;
    var selectedPenjamin;
    var selectedPoli;
    var statusPelunasan;
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

    $('#filter_doctor').change(function(){
      selectedDoctor = $(this).val();
    });

    $('#filter_penjab').change(function(){
      selectedPenjamin = $(this).val();
    });

    $('#filter_poli').change(function(){
      selectedPoli = $(this).val();
    });

    $('#filter_pelunasan').change(function(){
      statusPelunasan = $(this).val();
    });

    $('#btn-filter').click(function () {
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
        
        if ( $.fn.dataTable.isDataTable('#data-piutang-ralan')) {
          datatableRalan = $('#data-piutang-ralan').DataTable().destroy();
        }

        datatableRalan = $('#data-piutang-ralan').DataTable({
            responsive: true, 
            lengthChange: false, 
            autoWidth: false,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
            ],
            ajax:{
                url: "{{ route('piutang_ralan.list') }}",
                type: 'GET',
                data:{
                  start: dateStart, 
                  end: dateEnd, 
                  penjamin: selectedPenjamin,
                  poli: selectedPoli,
                  status_pelunasan: statusPelunasan,
                }
            },
            columns:[
              { data: 'tgl_registrasi', name: 'tgl_registrasi',
                    render: data => {
                        return moment(data, "YYYY-MM-DD").format("DD-MMM-YYYY");
                    }
                },
                { data: 'no_nota', name: 'no_nota'},
                { data: 'no_rkm_medis', name: 'no_rkm_medis'},
                { data: 'nm_pasien', name: 'nm_pasien'},
                { data: 'png_jawab', name: 'png_jawab'},
                { data: 'perujuk', name: 'perujuk'},
                { data: 'registrasi', name: 'registrasi'},
                { data: 'obat_plus_ppn', name: 'obat_plus_ppn'},
                { data: 'paket_tindakan', name: 'paket_tindakan'},
                { data: 'total_operasi', name: 'total_operasi'},
                { data: 'laborat', name: 'laborat'},
                { data: 'radiologi', name: 'radiologi'},
                { data: 'tambahan', name: 'tambahan'},
                { data: 'potongan', name: 'potongan'},
                { data: 'total_rs', name: 'total_rs'},
                { data: 'ekses', name: 'ekses'},
                { data: 'sudah_dibayar', name: 'sudah_dibayar'},
                { data: 'diskon_bayar', name: 'diskon_bayar'},
                { data: 'tidak_terbayar', name: 'tidak_terbayar'},
                { data: 'sisa', name: 'sisa'},
                { data: 'status_rawat', name: 'status_rawat'},
                { data: 'status_pelunasan', name: 'status_pelunasan'},
                { data: 'pelunasan', name: 'pelunasan'},
                { data: 'tgl_bayar_pelunasan', name: 'tgl_bayar_pelunasan',
                  render: data => {
                        return moment(data, "YYYY-MM-DD").format("DD-MMM-YYYY");
                    }
                },
                { data: 'catatan', name: 'catatan'},
                { data: 'nm_dokter', name: 'nm_dokter'},
            ],  
          });
    });


});

</script>

@endsection