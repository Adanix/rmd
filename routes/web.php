<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Ramadhan\{
   DashboardController,
   DaySettController,
   GuestController,
   JadwalTakjilController,
   JamaahController,
   TakjilController as RamadhanTakjilController,
   MakananController,
   MinumanController,
   RamadhanSettController
};
use App\Http\Controllers\Ramadhan\Owner\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Authentication Routes
Route::middleware(['guest'])->group(
   function () {
      Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
      Route::post('/auth-login', [AuthController::class, 'login'])->name('login.submit');
   }
);

Route::middleware(['auth'])->group(
   function () {
      // Dashboard
      Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

      // Makanan
      Route::resource('makanans', MakananController::class)->names('makanans');

      // Minuman
      Route::resource('minumans', MinumanController::class)->names('minumans');

      // Jama'ah
      Route::resource('jamaahs', JamaahController::class)->names('jamaahs');
      // web.php
      Route::get('/jamaahs', [JamaahController::class, 'index'])->name('jamaahs.index');
      Route::get('/jamaahs/create', [JamaahController::class, 'create'])->name('jamaahs.create');
      Route::post('/jamaahs/import', [JamaahController::class, 'import'])->name('jamaahs.import');

      //TODO: Ramadhan Setting
      Route::resource('ramadhan-settings', RamadhanSettController::class)->names('ramadhan-settings');

      //* Day Setting
      Route::get( // CREATE — harus menerima ID juga!
         'day-settings/create/{ramadhan_setting_id}',
         [DaySettController::class, 'create']
      )->name('day-settings.create');
      Route::get( // CREATE — harus menerima ID juga!
         'day-settings/edit/{ramadhan_setting_id}',
         [DaySettController::class, 'edit']
      )->name('day-settings.edit');
      Route::post( // STORE — harus menerima ID juga!
         'day-settings/{ramadhan_setting_id}',
         [DaySettController::class, 'store']
      )->name('day-settings.store');
      Route::resource('day-settings', DaySettController::class)->except(['create', 'edit', 'store'])->names('day-settings');

      //! Takjil
      Route::get( // CREATE — harus menerima ID juga!
         'takjils/create/{id}',
         [RamadhanTakjilController::class, 'create']
      )->name('takjils.create');
      Route::resource('takjils', RamadhanTakjilController::class)->except(['create'])->names('takjils');

      // ! Jadwal Takjil
      Route::get('/jadwal-takjil', [JadwalTakjilController::class, 'index'])->name('jadwal-takjil.index');
      Route::get('/jadwal-takjil/{uuid}', [JadwalTakjilController::class, 'detailJadwal'])->name('jadwal-takjil-detail');
      Route::get('/jadwal-takjil/export/pdf', [JadwalTakjilController::class, 'exportPdf'])->name('jadwal-takjil.export.pdf');
      Route::get('/jadwal-takjil/export/excel', [JadwalTakjilController::class, 'exportExcel'])->name('jadwal-takjil.export.excel');

      // !!! Logout
      Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
   }
);

Route::middleware(['auth', 'role:owner'])->group(function () {
   Route::resource('users', UserController::class)->names('users');
});

// ! Jadwal Takjil - Guest View
Route::get('/', [GuestController::class, 'index'])->name('takjil-jamaah');
Route::get('/{uuid}', [GuestController::class, 'detailTakjil'])->name('takjil-detail');
