<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
        <img src="{{ asset('images/logo_rsbkd.png') }}" alt="RS Bunda Surabaya" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">RS Bunda Surabaya</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

                <!-- Ringkasan Hutang Vendor Farmasi -->
                <li class="nav-item ">
                    @if($modul=='hutang-vendor-farmasi')
                    <a href="{{ route('hutang_vendor_farmasi.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('hutang_vendor_farmasi.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-solid fa-pen-nib"></i>
                            <p>Vendor Farmasi</p>
                        </a>
                </li>

                <!-- Ringkasan Hutang Non Medis -->
                <li class="nav-item ">
                    @if($modul=='hutang-vendor-non-medis')
                    <a href="{{ route('hutang_vendor_non_medis.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('hutang_vendor_non_medis.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-solid fa-pen"></i>
                            <p>Vendor Non Medis</p>
                        </a>
                </li>

                <!-- Tindakan Rawat Jalan -->
                <li class="nav-item ">
                    @if($modul=='tindakan-ralan')
                    <a href="{{ route('pendapatan.ralan.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('pendapatan.ralan.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-solid fa-stethoscope"></i>
                            <p>Honor Dokter Ralan</p>
                        </a>
                </li>

                <!-- Tindakan Rawat Jalan v2 -->
                <li class="nav-item ">
                    @if($modul=='tindakan-ralan-v2')
                    <a href="{{ route('pendapatan_v2.ralan.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('pendapatan_v2.ralan.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-solid fa-shekel-sign"></i>
                            <p>Honor Dokter Ralan v2</p>
                        </a>
                </li>

                <!-- Tindakan Rawat Inap -->
                <li class="nav-item">
                    @if($modul=='tindakan-ranap')
                    <a href="{{ route('pendapatan.ranap.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('pendapatan.ranap.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-sharp fa-solid fa-bed"></i>
                            <p>Honor Dokter Ranap</p>
                        </a>
                </li>

                <!-- Tindakan Rawat Inap v2-->
                <li class="nav-item">
                    @if($modul=='tindakan-ranap-v2')
                    <a href="{{ route('pendapatan_v2.ranap.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('pendapatan_v2.ranap.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-solid fa-shekel-sign"></i>
                            <p>Honor Dokter Ranap v2</p>
                        </a>
                </li>

                <!-- Tindakan Operator -->
                <li class="nav-item">
                    @if($modul=='tindakan-operator')
                    <a href="{{ route('pendapatan.operator.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('pendapatan.operator.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-user-md"></i>
                            <p>Honor Operator Operasi</p>
                        </a>
                </li>

                <!-- Tindakan Operator (Format Baru)-->
                <li class="nav-item">
                    @if($modul=='tindakan-operator-v2')
                    <a href="{{ route('pendapatan_v2.operator.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('pendapatan_v2.operator.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-solid fa-shekel-sign"></i>
                            <p>Honor Operator Operasi v2</p>
                        </a>
                </li>

                <!-- Tindakan Anestesi -->
                <li class="nav-item">
                    @if($modul=='tindakan-anestesi')
                    <a href="{{ route('pendapatan.anestesi.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('pendapatan.anestesi.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-users"></i>
                            <p>Honor Anestesi Operasi</p>
                        </a>
                </li>

                <!-- Tindakan Anestesi (Format Baru) -->
                <li class="nav-item">
                    @if($modul=='tindakan-anestesi-v2')
                    <a href="{{ route('pendapatan_v2.anestesi.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('pendapatan_v2.anestesi.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-solid fa-shekel-sign"></i>
                            <p>Honor Anestesi Operasi v2</p>
                        </a>
                </li>

                <!--Grouper Ralan-->
                <li class="nav-item">
                    @if($modul=='grouper-ralan')
                    <a href="{{ route('pendapatan.grouperralan.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('pendapatan.grouperralan.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>Grouper Ralan</p>
                        </a>
                </li>

                <!--Grouper Ranap-->
                <li class="nav-item">
                    @if($modul=='grouper-ranap')
                    <a href="{{ route('pendapatan.grouperranap.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('pendapatan.grouperranap.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-chart-pie"></i>
                            <p>Grouper Ranap</p>
                        </a>
                </li>

                <!-- Pendapatan Ralan  -->
                <li class="nav-item">
                    @if($modul=='pendapatan_ralan')
                    <a href="{{ route('pendapatan.pendapatan_ralan.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('pendapatan.pendapatan_ralan.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-money-bill-alt"></i>
                            <p>Pendapatan Ralan</p>
                        </a>
                </li>

                <!-- Pendapatan Ranap  -->
                <li class="nav-item">
                    @if($modul=='pendapatan_ranap')
                    <a href="{{ route('pendapatan.pendapatan_ranap.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('pendapatan.pendapatan_ranap.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-dollar-sign"></i>
                            <p>Pendapatan Ranap</p>
                        </a>
                </li>

                <!-- Laporan Radiologi -->
                <li class="nav-item">
                    @if($modul=='radiology')
                    <a href="{{ route('manager.radiology.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('manager.radiology.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-columns"></i>
                            <p>
                                Laporan Radiologi
                            </p>
                        </a>
                </li>

                <!-- Laporan Laborat -->
                <li class="nav-item">
                    @if($modul=='laborat')
                    <a href="{{ route('manager.laborat.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('manager.laborat.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon far fa-image"></i>
                            <p>
                                Laporan Laborat
                            </p>
                        </a>
                </li>

                <li class="nav-item">
                    @if($modul=='obat-kronis')
                    <a href="{{ route('manager.obatkronis.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('manager.obatkronis.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-reguler fa-capsules"></i>
                            <p>
                                Obat Kronis
                            </p>
                        </a>
                </li>

                <li class="nav-item">
                    @if($modul=='obat-non-kronis')
                    <a href="{{ route('manager.nonkronis.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('manager.nonkronis.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-sharp fa-regular fa-pills"></i>
                            <p>
                                Obat Non Kronis
                            </p>
                        </a>
                </li>

                <li class="nav-item">
                    @if($modul=='penerimaan-barang-non-medis')
                    <a href="{{ route('penerimaan_barang.non_medis.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('penerimaan_barang.non_medis.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-sharp fa-solid fa-parachute-box"></i>
                            <p>
                                Penerimaan Non Medis
                            </p>
                        </a>
                </li>

                <li class="nav-item">
                    @if($modul=='penerimaan-obat')
                    <a href="{{ route('penerimaan_obat.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('penerimaan_obat.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-sharp fa-solid fa-tablets"></i>
                            <p>
                                Penerimaan Obat
                            </p>
                        </a>
                </li>

                <li class="nav-item">
                    @if($modul=='piutang-ralan')
                    <a href="{{ route('piutang_ralan.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('piutang_ralan.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-sharp fa-solid fa-tag"></i>
                            <p>
                                Piutang Ralan
                            </p>
                        </a>
                </li>

                <li class="nav-item">
                    @if($modul=='piutang-ranap')
                    <a href="{{ route('piutang_ranap.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('piutang_ranap.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-sharp fa-solid fa-tags"></i>
                            <p>
                                Piutang Ranap
                            </p>
                        </a>
                </li>

                <li class="nav-item">
                    @if($modul=='penjualan-obat-bebas')
                    <a href="{{ route('manager.penjualanobatbebas.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('manager.penjualanobatbebas.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-sharp fa-regular fa-pills"></i>
                            <p>
                                Penjualan Obat Bebas
                            </p>
                        </a>
                </li>

                <li class="nav-item">
                    @if($modul=='stok-keluar-medis')
                    <a href="{{ route('manager.stokkeluarmedis.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('manager.stokkeluarmedis.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-sharp fa-solid fa-tablets"></i>
                            <p>
                                Stok Keluar Medis
                            </p>
                        </a>
                </li>

                <li class="nav-item">
                    @if($modul=='RKO')
                    <a href="{{ route('manager.rko.home') }}" class="nav-link active">
                        @else
                        <a href="{{ route('manager.rko.home') }}" class="nav-link">
                            @endif
                            <i class="nav-icon fas fa-money-bill-alt"></i>
                            <p>
                                RKO Keluar Medis
                            </p>
                        </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>