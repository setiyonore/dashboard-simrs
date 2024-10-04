@extends('layouts.management')

@section('title')
<title>RKO</title>
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
                    <h1>RKO</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('manager.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">RKO</li>
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
                            <h3 class="card-title">Data RKO<div id="filter_period_id"></div>
                            </h3>
                            <div class="margin float-right">
                                <a class="btn btn-info btn-sm float-right" href="javascript:void(0)"
                                    data-toggle="tooltip" data-placement="top" title="Filter data" id="btn-filter">
                                    <i class="fas fa-filter"></i> Filter
                                </a>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover table-striped" id="data-rko" style="width: 100% !important">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                        <th id="persentase">Persentase</th>
                                        <th>Kode Barang</th>
                                        <th>Bulan</th>
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
            <form id="form-filter" name="form-filter">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="create-title">@Lang('common.filter')</h4>
                        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="dos">@Lang('common.month'):</label>
                                    <div class="input-group date" id="date_start" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#date_start" />
                                        <div class="input-group-append" data-target="#date_start" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="dos">@Lang('common.percent'):</label>
                                    <div class="input-group date" id="date_start" data-target-input="nearest">
                                        <select class="form-control" name="Persen" id="percent">
                                            <option value="50">50%</option>
                                            <option value="30">30%</option>
                                            <option value="20">20%</option>
                                        </select>
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
    $(document).ready(function() {
        var dateEnd;
        var dateStart;
        var datatableRKO;
        var selectedPenjamin;
        var total = 0;

        $('[data-toggle="tooltip"]').tooltip();

        $('#date_start').datetimepicker({
            locale: 'id', // Set locale to Indonesian
            viewMode: 'months', // Show month and year picker
            format: 'MM/YYYY', // Display month and year only
            startDate: new Date(), // Set the start date to the current date
        });


        $('#date_end').datetimepicker({
            locale: 'id',
            viewMode: 'days',
            format: 'L',
        });

        $("#date_start").on("change.datetimepicker", function(e) {
            dateStart = moment(e.date).format("YYYY-MM-DD");
        });


        $("#date_end").on("change.datetimepicker", function(e) {
            dateEnd = moment(e.date).format("YYYY-MM-DD");
        });


        $('#filter_penjab').change(function() {
            selectedPenjamin = $(this).val();
        });

        $('#btn-filter').click(function() {
            $('#dateStart').trigger("reset");
            $('#endStart').trigger('reset');
            $('#filter_penjab').trigger('reset');
            $('#form-filter').trigger("reset");
            $('#filter-item-modal').modal('show');
        });

        $('#submit-filter-cancel').click(function() {
            $('#form-filter').trigger("reset");
            $('#filter-item-modal').modal('hide');
        });

        $('#submit-filter').click(function(e) {
            // console.log(moment(dateStart, "YYYY-MM").format("YYYY-MM"));
            let month = moment(dateStart, "YYYY-MM").format("YYYY-MM");
            let percent = $('#percent').val();
            $('#filter_period_id').text(month);

            $('#persentase').text('persentase' + ' ' + percent + '%')
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#filter-item-modal').modal('hide');

            if ($.fn.dataTable.isDataTable('#data-rko')) {
                datatableRKO = $('#data-rko').DataTable().destroy();
            }

            datatableRKO = $('#data-rko').DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
                ],
                ajax: {
                    url: "{{ route('manager.rko.list') }}",
                    type: 'GET',
                    data: {
                        month: month,
                        percent: percent,
                        penjamin: selectedPenjamin
                    }
                },

                columns: [{
                        data: 'nama_brng',
                        name: 'nama_brng'
                    }, {
                        data: 'jml',
                        name: 'jml'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'kode_brng',
                        name: 'kode_brng'
                    },
                    {
                        data: 'month_name',
                        data: 'month_name'
                    }
                ],
            });
        });

        //reset Filter
        $('#filter-item-modal').on('hide.bs.modal', function() {
            $('#dateStart').trigger("reset");
            $('#endStart').trigger('reset');
            $('#filter_penjab').trigger('reset');
            $('#form-filter').trigger("reset");

        });


    });
</script>

@endsection