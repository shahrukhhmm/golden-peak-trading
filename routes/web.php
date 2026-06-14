<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\icons\Boxicons;
use App\Http\Controllers\dashboard\Analytics;

use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\backend\SaleController;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\backend\ExpenseController;
use App\Http\Controllers\backend\InvoiceController;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\backend\CustomerController;
use App\Http\Controllers\backend\StockItemController;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\tables\Basic as TablesBasic;
use App\Http\Controllers\backend\CategoriesController;
use App\Http\Controllers\backend\DepartmentController;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\backend\StockMovementController;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;

Route::get('/optimize', function () {
  Artisan::call('optimize');

  return response()->json([
    'status' => 'optimized',
    'output' => Artisan::output(),
  ]);
});

// authentication login register routes
Route::middleware('guest')->group(function () {
  Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
  Route::post('/auth/login-basic', [LoginBasic::class, 'login'])->name('login');


  Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
  Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');
});

// Logout route
Route::get('/logout', [LoginBasic::class, 'logout'])->name('logout')->middleware('auth');


Route::middleware('auth')->group(function () {
  // Main Page Route
  Route::get('/', [Analytics::class, 'index'])->name('dashboard.analytics');
  Route::get('/dashboard/analytics/pdf', [Analytics::class, 'pdf'])->name('dashboard.analytics.pdf');


  // DEPARTMENTS ROUTE
  Route::prefix('departments')->name('departments.')->group(function () {
    Route::get('/', [DepartmentController::class, 'index'])->name('index');
    Route::get('/data', [DepartmentController::class, 'data'])->name('data');
    Route::post('/store', [DepartmentController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [DepartmentController::class, 'edit'])->name('edit');
    Route::delete('/delete/{id}', [DepartmentController::class, 'destroy'])->name('delete');
  });



  Route::prefix('departments/{department}')->name('departments.')->group(function () {

    // Route::get('/', function () {
    //   dd('Please select a valid sub-module.');
    // });

    Route::get('/categories', [CategoriesController::class, 'index'])
      ->name('categories.index');

    Route::get('/categories/data', [CategoriesController::class, 'data'])
      ->name('categories.data');
    Route::post('/categories', [CategoriesController::class, 'store'])
      ->name('categories.store');

    Route::get('/categories/{category}/edit', [CategoriesController::class, 'edit'])
      ->name('categories.edit');

    Route::delete('/categories/{category}', [CategoriesController::class, 'destroy'])
      ->name('categories.destroy');


    // expense routes
    Route::get('/expenses', [ExpenseController::class, 'index'])
      ->name('expenses.index');

    Route::get('/expenses/data', [ExpenseController::class, 'data'])
      ->name('expenses.data');

    Route::post('/expenses', [ExpenseController::class, 'store'])
      ->name('expenses.store');

    Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])
      ->name('expenses.edit');

    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])
      ->name('expenses.destroy');



    // SALE ROUTES
    Route::get('/sales', [SaleController::class, 'index'])
      ->name('sales.index');

    Route::get('/sales/data', [SaleController::class, 'data'])
      ->name('sales.data');

    Route::post('/sales', [SaleController::class, 'store'])
      ->name('sales.store');

    Route::get('/sales/{sale}/edit', [SaleController::class, 'edit'])
      ->name('sales.edit');

    Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])
      ->name('sales.destroy');




    // INVOICE ROUTES
    Route::get('/invoices', [InvoiceController::class, 'index'])
      ->name('invoices.index');

    Route::get('/invoices/data', [InvoiceController::class, 'data'])
      ->name('invoices.data');

    Route::post('/invoices', [InvoiceController::class, 'store'])
      ->name('invoices.store');

    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])
      ->name('invoices.show');

    // Route::get('/invoices/{invoice}/edit-data', [InvoiceController::class, 'edit'])
    // ->name('invoices.edit-data');

    Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])
      ->name('invoices.destroy');

    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])
      ->name('invoices.pdf');

    Route::get('/invoices/{invoice}/data', [InvoiceController::class, 'edit'])
      ->name('invoices.edit-data');



    // CUSTOMER ROUTES
    Route::get('/customers', [CustomerController::class, 'index'])
      ->name('customers.index');

    Route::get('/customers/data', [CustomerController::class, 'data'])
      ->name('customers.data');

    Route::post('/customers', [CustomerController::class, 'store'])
      ->name('customers.store');

    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])
      ->name('customers.edit');

    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])
      ->name('customers.destroy');



    Route::get('/stock', [StockItemController::class, 'index'])->name('stock.index');
    Route::get('/stock/data', [StockItemController::class, 'data'])->name('stock.data');
    Route::post('/stock/store', [StockItemController::class, 'store'])->name('stock.store');
    // pdf
    Route::get('/stock/pdf', [StockItemController::class, 'pdf'])->name('stock.pdf');


    // Add stock (IN)
    Route::post('/stock/{stockItem}/in', [StockMovementController::class, 'storeIn'])->name('stock.in.store');

    // Issue stock (OUT)
    Route::get('/stock/{stockItem}/issue', [StockMovementController::class, 'issue'])->name('stock.issue');
    Route::post('/stock/{stockItem}/out', [StockMovementController::class, 'storeOut'])->name('stock.out.store');

    Route::get('/stock/{stockItem}/history', [StockMovementController::class, 'history'])->name('stock.history');
  });













































  // ====================HELPER PAGES & UI ROUTES==================== //

  // User Interface
  Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
  Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
  Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
  Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
  Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
  Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
  Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
  Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
  Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
  Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
  Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
  Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
  Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
  Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
  Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');
  Route::get('/ui/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
  Route::get('/ui/toasts', [Toasts::class, 'index'])->name('ui-toasts');
  Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
  Route::get('/ui/typography', [Typography::class, 'index'])->name('ui-typography');

  // extended ui
  Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
  Route::get('/extended/ui-text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');

  // icons
  Route::get('/icons/boxicons', [Boxicons::class, 'index'])->name('icons-boxicons');

  // form elements
  Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
  Route::get('/forms/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');

  // form layouts
  Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
  Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');

  // tables
  Route::get('/tables/basic', [TablesBasic::class, 'index'])->name('tables-basic');
});
