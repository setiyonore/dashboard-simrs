<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [AuthController::class, 'index'])->name('auth.index');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::group(['middleware' => ['web','auth']  ], function () {

    Route::group(['namespace' => 'App\Http\Controllers\Management'], function () {

        Route::get('/management/home', 'HomeController@index')->name('manager.home');

        Route::get('/management/services', 'ServicesController@index')->name('manager.services');

        //Radiologi
        Route::group(['prefix' => 'management/radiology'], function () {
            Route::get('', 'RadiologyController@index')->name('manager.radiology.home');
            Route::get('tindakan', 'RadiologyController@getTindakanRadiologi')->name('manager.radiology.list');
        });    
        
        //Laborat
        Route::group(['prefix' => 'management/laborat'], function () {
            Route::get('', 'LaboratController@index')->name('manager.laborat.home');
            Route::get('tindakan', 'LaboratController@getTindakanLaborat')->name('manager.laborat.list');
        });    
        
        //Obat Kronis
        Route::group(['prefix' => 'management/obatkronis'], function () {
            Route::get('', 'ObatKronisController@index')->name('manager.obatkronis.home');
            Route::get('obatkronis', 'ObatKronisController@getObatKronis')->name('manager.obatkronis.list');
        });    
        
        //Obat Non Kronis
        Route::group(['prefix' => 'management/nonkronis'], function () {
            Route::get('', 'NonKronisController@index')->name('manager.nonkronis.home');
            Route::get('nonkronis', 'NonKronisController@getNonKronis')->name('manager.nonkronis.list');
        }); 

        //Penjualan Obat Bebas
        Route::group(['prefix' => 'management/penjualanobatbebas'], function () {
            Route::get('', 'PenjualanObatBebasController@index')->name('manager.penjualanobatbebas.home');
            Route::get('penjualanobatbebas', 'PenjualanObatBebasController@getPenjualanObatBebas')->name('manager.penjualanobatbebas.list');
        }); 

        //Stok Keluar Medis
          Route::group(['prefix' => 'management/stokkeluarmedis'], function () {
            Route::get('', 'StokKeluarMedisController@index')->name('manager.stokkeluarmedis.home');
            Route::get('stokkeluarmedis', 'StokKeluarMedisController@getStokKeluarMedis')->name('manager.stokkeluarmedis.list');
        }); 

         //RKO
         Route::group(['prefix' => 'management/rko'], function () {
            Route::get('', 'RKOController@index')->name('manager.rko.home');
            Route::get('rko', 'RKOController@getRKO')->name('manager.rko.list');
        }); 

        Route::group(['prefix' => 'management/PenerimaanBarangNonMedis'], function () {
            Route::get('', 'PenerimaanBarangNonMedisController@index')->name('penerimaan_barang.non_medis.home');
            Route::get('non_medis', 'PenerimaanBarangNonMedisController@getPenerimaanBarangNonMedis')->name('penerimaan_barang.non_medis.list');
        });

        Route::group(['prefix' => 'management/PenerimaanObat'], function () {
            Route::get('', 'PenerimaanObatController@index')->name('penerimaan_obat.home');
            Route::get('obat', 'PenerimaanObatController@getPenerimaanObat')->name('penerimaan_obat.list');
        });
        
        
        //Medical Record
        // Route::group(['prefix' => 'management/medrec'], function () {
        //     Route::get('', 'MedicalRecordController@index')->name('manager.medrec');
        //     Route::get('getalos/{start}/{end}', 'MedicalRecordController@getAlos')->name('manager.medrec.alos');
        //     Route::get('getbor/{start}/{end}', 'MedicalRecordController@getBor')->name('manager.medrec.bor');
        //     Route::get('getdoctor', 'MedicalRecordController@getDoctor')->name('manager.medrec.doctor');
        //     Route::get('getpatient/{start}/{end}', 'MedicalRecordController@getPatient')->name('manager.medrec.patient');
        //     Route::get('getReadmittedRate/{start}/{end}', 'MedicalRecordController@getReadmittedRate')->name('manager.medrec.readmitted');
        //     Route::get('getAdmittedRateRalan/{start}/{end}', 'MedicalRecordController@getAdmittedRateRalan')->name('manager.medrec.admittedrateralan');
        //     Route::get('getBestDiagnose/{start}/{end}', 'MedicalRecordController@getBestDiagnose')->name('manager.medrec.bestdiagnose');
        //     Route::get('getInOut/{start}/{end}', 'MedicalRecordController@getInOut')->name('manager.medrec.inout');
        //     Route::get('getAppointments', 'MedicalRecordController@getAppointments')->name('manager.medrec.appointments');

        // });


    });


    Route::group(['namespace' => 'App\Http\Controllers\Pendapatan'], function () {

            //Hutang Vendor Farmasi
            Route::group(['prefix' => 'pendapatan/HutangVendorFarmasi'], function () {
                Route::get('', 'HutangVendorFarmasiController@index')->name('hutang_vendor_farmasi.home');
                Route::get('farmasi_tanggal_datang', 'HutangVendorFarmasiController@getHutangVendorFarmasiTanggalDatang')->name('hutang_vendor_farmasi.list_tgl_datang');
                Route::get('farmasi_tanggal_tempo', 'HutangVendorFarmasiController@getHutangVendorFarmasiTanggalTempo')->name('hutang_vendor_farmasi.list_tgl_tempo');
            });

            //Hutang Vendor Non Medis
            Route::group(['prefix' => 'pendapatan/HutangVendorNonMedis'], function () {
                Route::get('', 'HutangVendorNonMedisController@index')->name('hutang_vendor_non_medis.home');
                Route::get('non_medis_tanggal_datang', 'HutangVendorNonMedisController@getHutangVendorNonMedisTanggalDatang')->name('hutang_vendor_non_medis.list_tgl_datang');
                Route::get('non_medis_tanggal_tempo', 'HutangVendorNonMedisController@getHutangVendorNonMedisTanggalTempo')->name('hutang_vendor_non_medis.list_tgl_tempo');
            });

            //Piutang Ralan
            Route::group(['prefix' => 'pendapatan/PiutangRalan'], function () {
                Route::get('', 'PiutangRalanController@index')->name('piutang_ralan.home');
                Route::get('piutang_ralan', 'PiutangRalanController@getPiutangRalan')->name('piutang_ralan.list');
            });

            //Piutang Ranap
            Route::group(['prefix' => 'pendapatan/PiutangRanap'], function () {
                Route::get('', 'PiutangRanapController@index')->name('piutang_ranap.home');
                Route::get('piutang_ranap', 'PiutangRanapController@getPiutangRanap')->name('piutang_ranap.list');
            });

            //Honor Dokter Ralan
            Route::group(['prefix' => 'pendapatan/ralan'], function () {
                Route::get('', 'TindakanRalanController@index')->name('pendapatan.ralan.home');
                Route::get('ralan', 'TindakanRalanController@getTindakanRalan')->name('pendapatan.ralan.list');
            });

            //Honor Dokter Ralan (Format Baru)
            Route::group(['prefix' => 'pendapatan/ralan_v2'], function () {
                Route::get('', 'TindakanRalanController_v2@index')->name('pendapatan_v2.ralan.home');
                Route::get('ralan_v2', 'TindakanRalanController_v2@getTindakanRalan')->name('pendapatan_v2.ralan.list');
            });

            //Honor Dokter Ranap
            Route::group(['prefix' => 'pendapatan/ranap'], function () {
                Route::get('', 'TindakanRanapController@index')->name('pendapatan.ranap.home');
                Route::get('ranap', 'TindakanRanapController@getTindakanRanap')->name('pendapatan.ranap.list');
            });

            //Honor Dokter Ranap (Format Baru)
            Route::group(['prefix' => 'pendapatan/ranap_v2'], function () {
                Route::get('', 'TindakanRanapController_v2@index')->name('pendapatan_v2.ranap.home');
                Route::get('ranap_v2', 'TindakanRanapController_v2@getTindakanRanap')->name('pendapatan_v2.ranap.list');
            });

            //Operator 1
            Route::group(['prefix' => 'pendapatan/operator'], function () {
                Route::get('', 'TindakanOperatorController@index')->name('pendapatan.operator.home');
                Route::get('operator', 'TindakanOperatorController@getTindakanOperator')->name('pendapatan.operator.list');
            });

            //Operator 1 (Format Baru)
            Route::group(['prefix' => 'pendapatan/operator_v2'], function () {
                Route::get('', 'TindakanOperatorController_v2@index')->name('pendapatan_v2.operator.home');
                Route::get('operator_v2', 'TindakanOperatorController_v2@getTindakanOperator')->name('pendapatan_v2.operator.list');
            });

            //Anestesi
            Route::group(['prefix' => 'pendapatan/anestesi'], function () {
                Route::get('', 'TindakanAnestesiController@index')->name('pendapatan.anestesi.home');
                Route::get('anestesi', 'TindakanAnestesiController@getTindakanAnestesi')->name('pendapatan.anestesi.list');
            });

            //Anestesi (Format Baru)
            Route::group(['prefix' => 'pendapatan/anestesi_v2'], function () {
                Route::get('', 'TindakanAnestesiController_v2@index')->name('pendapatan_v2.anestesi.home');
                Route::get('anestesi_v2', 'TindakanAnestesiController_v2@getTindakanAnestesi')->name('pendapatan_v2.anestesi.list');
            });
            
            //Grouper Ralan
            Route::group(['prefix' => 'pendapatan/grouperralan'], function () {
                Route::get('', 'GrouperRalanController@index')->name('pendapatan.grouperralan.home');
                Route::get('grouperralan', 'GrouperRalanController@getGrouperRalan')->name('pendapatan.grouperralan.list');
            });

            //Grouper Ranap
            Route::group(['prefix' => 'pendapatan/grouperranap'], function () {
                Route::get('', 'GrouperRanapController@index')->name('pendapatan.grouperranap.home');
                Route::get('grouperranap', 'GrouperRanapController@getGrouperRanap')->name('pendapatan.grouperranap.list');
            });

            //Pendapatan Ralan 
            Route::group(['prefix' => 'pendapatan/pendapatan_ralan'], function () {
                Route::get('', 'PendapatanRalanController@index')->name('pendapatan.pendapatan_ralan.home');
                Route::get('pendapatan_ralan', 'PendapatanRalanController@getPendapatanRalan')->name('pendapatan.pendapatan_ralan.list');
            });

            //Pendapatan Ranap 
            Route::group(['prefix' => 'pendapatan/pendapatan_ranap'], function () {
                Route::get('', 'PendapatanRanapController@index')->name('pendapatan.pendapatan_ranap.home');
                Route::get('pendapatan_ranap', 'PendapatanRanapController@getPendapatanRanap')->name('pendapatan.pendapatan_ranap.list');
            });

        });
});
