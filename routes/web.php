<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArchiveInvoicesController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\InvoiceDetailsController;
use App\Http\Controllers\Invoices_ReportController;
use App\Http\Controllers\Customers_ReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard')->middleware('auth');

require __DIR__.'/auth.php';

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);

    // Start Main Pages
        Route::resource('invoices', InvoicesController::class);
        Route::resource('sections', SectionController::class);
        Route::resource('products', ProductController::class);
        Route::resource('archive', ArchiveInvoicesController::class);
        Route::get('section/{id}', [InvoicesController::class, 'getproducts']);
    // End Main Pages

    // Start Invoices Details Page
        Route::get('InvoicesDetails/{id}', [InvoiceDetailsController::class, 'edit']);
        Route::resource('InvoiceAttachments', InvoiceAttachmentsController::class);
        Route::post('delete_file', [InvoiceDetailsController::class, 'destroy'])->name('delete_file');
        Route::get('View_file/{invoice_number}/{file_name}',[InvoiceDetailsController::class, 'open_file']);
        Route::get('download/{invoice_number}/{file_name}',[InvoiceDetailsController::class, 'get_file']);
    // End Invoices Details Page

    // Start Change Payment
        Route::get('Status_show/{id}', [InvoicesController::class, 'show'])->name('Status_show');
        Route::post('Status_Update/{id}',  [InvoicesController::class, 'Status_Update'])->name('Status_Update');
    // End Change Payment

    Route::get('Print_invoice/{id}',  [InvoicesController::class, 'Print_invoice']);

    // Start Invoices Pages
        Route::get('edit_invoice/{id}', [InvoicesController::class, 'edit']);
        Route::get('Invoice_Paid', [InvoicesController::class, 'Invoice_Paid']);
        Route::get('Invoice_UnPaid', [InvoicesController::class, 'Invoice_UnPaid']);
        Route::get('Invoice_Partial', [InvoicesController::class, 'Invoice_Partial']);
    // End Invoices Pages


    // Start Reports Pages
        Route::get('invoices_report', [Invoices_ReportController::class, 'index']);
        Route::post('Search_invoices', [Invoices_ReportController::class ,'Search_invoices'])->name("Search_invoices");
        Route::get('customers_report', [Customers_ReportController::class ,'index'])->name("customers_report");
        Route::post('Search_customers', [Customers_ReportController::class ,'Search_customers'])->name('Search_customers');
    // End Reports Pages

    // Start Notification Read
        Route::get('read_notifi/{id}', [InvoicesController::class, 'Print_invoice']);
        Route::get('read_all', function () {
            $user = \App\Models\User::find(Auth()->user()->id);
            foreach ($user->unreadNotifications as $notifi) {
                $notifi->markAsRead();
            }
            return back();
        })->name('read_all');
    // End Notification Read

    //Start Them Page
    Route::get('/{page}', [AdminController::class, 'index']);
    //End Them Page
});

