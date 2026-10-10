<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Visitor\VisitorHotelController;
use App\Http\Controllers\Visitor\VisitorFerryController;
use App\Http\Controllers\Visitor\VisitorEventController;
use App\Http\Controllers\Visitor\VisitorDashboardController;

// Hotel Manager Controllers
use App\Http\Controllers\HotelManager\HotelManagerDashboardController;
use App\Http\Controllers\HotelManager\HotelManagerBookingsController;
use App\Http\Controllers\HotelManager\HotelManagerHotelProfileController;
use App\Http\Controllers\HotelManager\HotelManagerRoomsController;

// Ferry Operator Controllers
use App\Http\Controllers\FerryOperator\FerryOperatorDashboardController;
use App\Http\Controllers\FerryOperator\FerryOperatorScheduleController;
use App\Http\Controllers\FerryOperator\FerryOperatorTicketController;
use App\Http\Controllers\FerryOperator\FerryOperatorValidationController;
use App\Http\Controllers\FerryOperator\FerryOperatorPassController;
use App\Http\Controllers\FerryOperator\FerryOperatorAvailabilityController;
use App\Http\Controllers\FerryOperator\FerryOperatorReportController;

// Theme Park Staff Controllers
use App\Http\Controllers\ThemeParkStaff\EventController;
use App\Http\Controllers\ThemeParkStaff\PromotionAdvertisementController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminAdsController;
use App\Http\Controllers\Admin\AdminReportsController;
use App\Http\Controllers\Admin\AdminSystemController;
use App\Http\Controllers\Admin\IslandLocationController;

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.save');

// Public Pages
Route::get('/hotels', [VisitorHotelController::class, 'index'])->name('hotels.index');
Route::get('/hotels/{hotel}', [VisitorHotelController::class, 'show'])->name('hotels.show');

Route::get('/ferries', [VisitorFerryController::class, 'index'])->name('ferries.index');
Route::get('/ferries/{ferry}', [VisitorFerryController::class, 'show'])->name('ferries.show');

Route::get('/events', [VisitorEventController::class, 'index'])->name('events.index');
Route::get('/events/type/{type}', [VisitorEventController::class, 'byType'])->name('events.byType');
Route::get('/events/{id}', [VisitorEventController::class, 'show'])->name('events.show');

// Visitor Routes
Route::prefix('visitor')
    ->middleware(['auth', 'is_visitor'])
    ->name('visitor.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [VisitorDashboardController::class, 'index'])->name('dashboard');

        // Hotel Bookings
        Route::post('/hotels/room/{room}/book', [VisitorHotelController::class, 'bookRoom'])->name('hotels.book.room');
        Route::get('/booking/{id}/confirmation', [VisitorHotelController::class, 'bookingConfirmation'])->name('booking.confirmation');
        Route::delete('/booking/{id}/cancel', [VisitorHotelController::class, 'cancelBooking'])->name('booking.cancel');

        // Ferry Booking
        Route::post('/ferries/book/{schedule}', [VisitorFerryController::class, 'bookTicket'])->name('ferry.book');
        Route::get('/ferry/request-confirmation/{request}', [VisitorFerryController::class, 'confirmation'])->name('ferry.request.confirmation');
        Route::get('/ferry/my-requests', [VisitorFerryController::class, 'myRequests'])->name('ferry.my-requests');
        Route::delete('/ferry/cancel-request/{request}', [VisitorFerryController::class, 'cancelRequest'])->name('ferry.cancel-request');
        Route::get('/ferry-ticket/{ticket}/confirmation', [VisitorFerryController::class, 'showTicketConfirmation'])->name('ferry.ticket.confirmation');

        // Event Bookings
        Route::post('/events/book/{slot}', [VisitorEventController::class, 'bookTicket'])->name('events.book');
        Route::get('/events/ticket/{id}/confirmation', [VisitorEventController::class, 'confirmation'])->name('event.ticket.confirmation');
        Route::delete('/events/ticket/{ticket}/cancel', [VisitorEventController::class, 'cancel'])->name('event.ticket.cancel');
    });

// Hotel Manager Routes - Using IDs for consistency
Route::prefix('hotel_manager')
    ->middleware(['auth', 'is_hotel_manager'])
    ->name('hotel_manager.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [HotelManagerDashboardController::class, 'dashboard'])->name('dashboard');
        
        // Rooms Management
        Route::get('rooms', [HotelManagerRoomsController::class, 'index'])->name('rooms.index');
        Route::get('rooms/create', [HotelManagerRoomsController::class, 'create'])->name('rooms.create');
        Route::post('rooms', [HotelManagerRoomsController::class, 'store'])->name('rooms.store');
        Route::get('rooms/{id}', [HotelManagerRoomsController::class, 'show'])->name('rooms.show');
        Route::get('rooms/{id}/edit', [HotelManagerRoomsController::class, 'edit'])->name('rooms.edit');
        Route::put('rooms/{id}', [HotelManagerRoomsController::class, 'update'])->name('rooms.update');
        Route::delete('rooms/{id}', [HotelManagerRoomsController::class, 'destroy'])->name('rooms.destroy');

        // Bookings Management
        Route::get('bookings', [HotelManagerBookingsController::class, 'index'])->name('bookings.index');
        Route::get('bookings/create', [HotelManagerBookingsController::class, 'create'])->name('bookings.create');
        Route::post('bookings', [HotelManagerBookingsController::class, 'store'])->name('bookings.store');
        Route::get('bookings/{booking}', [HotelManagerBookingsController::class, 'show'])->name('bookings.show');
        Route::get('bookings/{booking}/edit', [HotelManagerBookingsController::class, 'edit'])->name('bookings.edit');
        Route::put('bookings/{booking}', [HotelManagerBookingsController::class, 'update'])->name('bookings.update');
        Route::delete('bookings/{booking}', [HotelManagerBookingsController::class, 'destroy'])->name('bookings.destroy');

        // Promotions
        Route::get('/promotions', function() {
            return 'Promotions - Placeholder';
        })->name('promotions.index');

        // Hotel Profile
        Route::get('/hotel_profile', [HotelManagerHotelProfileController::class, 'hotelProfile'])->name('hotel_profile');
    });

// Ferry Operator Routes - UPDATED FOR ROUTE MODEL BINDING
Route::prefix('ferry-operator')
    ->middleware(['auth', 'is_ferry_operator'])
    ->name('ferry_operator.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [FerryOperatorDashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard/stats', [FerryOperatorDashboardController::class, 'stats'])->name('dashboard.stats');

        // Schedule Management (CRUD) - Keep as {id} since not using route model binding here
        Route::prefix('schedules')->name('schedules.')->group(function () {
            Route::get('/', [FerryOperatorScheduleController::class, 'index'])->name('index');
            Route::get('/create', [FerryOperatorScheduleController::class, 'create'])->name('create');
            Route::post('/', [FerryOperatorScheduleController::class, 'store'])->name('store');
            Route::get('/{id}', [FerryOperatorScheduleController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [FerryOperatorScheduleController::class, 'edit'])->name('edit');
            Route::put('/{id}', [FerryOperatorScheduleController::class, 'update'])->name('update');
            Route::delete('/{id}', [FerryOperatorScheduleController::class, 'destroy'])->name('destroy');
            
            // Status management routes
            Route::post('/{id}/update-status', [FerryOperatorScheduleController::class, 'updateStatus'])->name('update-status');
            Route::post('/{id}/cancel', [FerryOperatorScheduleController::class, 'cancel'])->name('cancel');
            Route::post('/{id}/activate', [FerryOperatorScheduleController::class, 'activate'])->name('activate');
            Route::post('/{id}/mark-boarding', [FerryOperatorScheduleController::class, 'markAsBoarding'])->name('mark-boarding');
            Route::post('/{id}/mark-departed', [FerryOperatorScheduleController::class, 'markAsDeparted'])->name('mark-departed');
            Route::post('/{id}/mark-arrived', [FerryOperatorScheduleController::class, 'markAsArrived'])->name('mark-arrived');
            
            // Availability check
            Route::get('/availability/{date}', [FerryOperatorScheduleController::class, 'getAvailability'])->name('availability');
        });

        // Ticket Requests Management - USE {id} INSTEAD
        Route::prefix('tickets')->name('tickets.')->group(function () {
            Route::get('/', [FerryOperatorTicketController::class, 'index'])->name('index');
            Route::get('/{ferryTicketRequest}', [FerryOperatorTicketController::class, 'show'])->name('show');  // Changed to model binding
            Route::get('/{ferryTicketRequest}/validate', [FerryOperatorTicketController::class, 'validateForm'])->name('validate.form');
            Route::post('/{ferryTicketRequest}/validate', [FerryOperatorTicketController::class, 'validateRequest'])->name('validate');
            Route::post('/{ferryTicketRequest}/approve', [FerryOperatorTicketController::class, 'approve'])->name('approve');
            Route::post('/{ferryTicketRequest}/reject', [FerryOperatorTicketController::class, 'reject'])->name('reject');
            Route::post('/{ferryTicketRequest}/assign-seats', [FerryOperatorTicketController::class, 'assignSeats'])->name('assign.seats');
        });

        // Validations (History of validated tickets) - Keep as {id}
        Route::prefix('validations')->name('validations.')->group(function () {
            Route::get('/', [FerryOperatorValidationController::class, 'index'])->name('index');
            Route::get('/{id}', [FerryOperatorValidationController::class, 'show'])->name('show');
            Route::get('/{id}/details', [FerryOperatorValidationController::class, 'details'])->name('details');
            Route::get('/ticket/{id}', [FerryOperatorValidationController::class, 'showTicket'])->name('ticket.show');
        });

        // Ferry Passes Management - Keep as {id} unless you update those controllers too
        Route::prefix('passes')->name('passes.')->group(function () {
            // CRUD operations
            Route::get('/', [FerryOperatorPassController::class, 'index'])->name('index');
            Route::get('/create', [FerryOperatorPassController::class, 'create'])->name('create');
            Route::post('/', [FerryOperatorPassController::class, 'store'])->name('store');
            Route::get('/{id}', [FerryOperatorPassController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [FerryOperatorPassController::class, 'edit'])->name('edit');
            Route::put('/{id}', [FerryOperatorPassController::class, 'update'])->name('update');
            Route::delete('/{id}', [FerryOperatorPassController::class, 'destroy'])->name('destroy');
            
            // Status management routes
            Route::post('/{id}/update-status', [FerryOperatorPassController::class, 'updateStatus'])->name('update-status');
            Route::post('/{id}/issue', [FerryOperatorPassController::class, 'issue'])->name('issue');
            Route::post('/{id}/cancel', [FerryOperatorPassController::class, 'cancel'])->name('cancel');
            Route::post('/{id}/activate', [FerryOperatorPassController::class, 'activate'])->name('activate');
            Route::post('/{id}/renew', [FerryOperatorPassController::class, 'renew'])->name('renew');
            Route::get('/{id}/validate', [FerryOperatorPassController::class, 'validatePass'])->name('validate.pass');
            // Special routes
            Route::get('/generate/{ticketRequestId}', [FerryOperatorPassController::class, 'generateFromRequest'])->name('generate.from.request');
        });

        // Availability Management - Keep as {id}
        Route::prefix('availability')->name('availability.')->group(function () {
            // Main availability pages
            Route::get('/', [FerryOperatorAvailabilityController::class, 'index'])->name('index');
            Route::get('/today', function() {
                return redirect()->route('ferry_operator.availability.index');
            })->name('today');
            Route::get('/date/{date}', [FerryOperatorAvailabilityController::class, 'index'])->name('date');
            
            // Update operations
            Route::post('/update', [FerryOperatorAvailabilityController::class, 'update'])->name('update');
            
            // Calendar views
            Route::get('/calendar', [FerryOperatorAvailabilityController::class, 'calendar'])->name('calendar');
            Route::get('/month/{year}/{month}', [FerryOperatorAvailabilityController::class, 'monthView'])->name('month.view');
            Route::get('/week/{date?}', [FerryOperatorAvailabilityController::class, 'weekView'])->name('week.view');
            
            // Bulk operations
            Route::get('/bulk', [FerryOperatorAvailabilityController::class, 'bulkForm'])->name('bulk.form');
            Route::post('/bulk-update', [FerryOperatorAvailabilityController::class, 'bulkUpdate'])->name('bulk.update');
            
            // Maintenance
            Route::get('/maintenance', [FerryOperatorAvailabilityController::class, 'maintenanceForm'])->name('maintenance.form');
            Route::post('/set-maintenance', [FerryOperatorAvailabilityController::class, 'setMaintenance'])->name('set.maintenance');
            
            // Export/Print
            Route::get('/export/{type?}', [FerryOperatorAvailabilityController::class, 'export'])->name('export');
            Route::get('/print/{date?}', [FerryOperatorAvailabilityController::class, 'print'])->name('print');
            Route::get('/download/{type}/{date?}', [FerryOperatorAvailabilityController::class, 'download'])->name('download');
            
            // API endpoints for AJAX
            Route::prefix('api')->name('api.')->group(function() {
                Route::get('/daily-stats/{date}', [FerryOperatorAvailabilityController::class, 'dailyStats'])->name('daily.stats');
                Route::get('/monthly-stats/{year}/{month}', [FerryOperatorAvailabilityController::class, 'monthlyStats'])->name('monthly.stats');
                Route::post('/quick-update/{id}', [FerryOperatorAvailabilityController::class, 'quickUpdate'])->name('quick.update');
            });
        });

        // Reports & Analytics - Keep as {id}
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [FerryOperatorReportController::class, 'index'])->name('index');
            Route::get('/passenger-summary', [FerryOperatorReportController::class, 'passengerSummary'])->name('passenger-summary');
            Route::get('/revenue', [FerryOperatorReportController::class, 'revenueReport'])->name('revenue');
            Route::get('/validation-stats', [FerryOperatorReportController::class, 'validationStats'])->name('validation-stats');
            Route::get('/schedule-utilization', [FerryOperatorReportController::class, 'scheduleUtilization'])->name('schedule-utilization');
            Route::get('/hotel-customer-analysis', [FerryOperatorReportController::class, 'hotelCustomerAnalysis'])->name('hotel-customer-analysis');
            Route::post('/generate', [FerryOperatorReportController::class, 'generateReport'])->name('generate');
            Route::get('/export/{type}', [FerryOperatorReportController::class, 'exportReport'])->name('export');
            Route::get('/download/{report}', [FerryOperatorReportController::class, 'download'])->name('download');
            Route::get('/print', [FerryOperatorReportController::class, 'printReport'])->name('print');
        });

        // Quick Actions - Keep as {id}
        Route::prefix('quick-actions')->name('quick.')->group(function () {
            Route::post('/validate-booking/{hotelBookingId}', [FerryOperatorValidationController::class, 'validateBooking'])->name('validate.booking');
            Route::post('/issue-pass/{visitorId}', [FerryOperatorPassController::class, 'quickIssuePass'])->name('issue.pass');
        });
    });

// theme park staff
Route::prefix('theme-park-staff')
    ->middleware(['auth', 'theme_park_staff'])
    ->name('theme-park-staff.')
    ->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [EventController::class, 'dashboard'])->name('dashboard');
        
        // Events Management
        Route::prefix('events')->name('events.')->group(function () {
            Route::get('/', [EventController::class, 'index'])->name('index');
            Route::get('/create', [EventController::class, 'create'])->name('create');
            Route::post('/', [EventController::class, 'store'])->name('store');
            
            // PUT SCHEDULE ROUTES FIRST
            Route::get('/{eventId}/schedule', [EventController::class, 'schedule'])->name('schedule');
            Route::post('/{eventId}/schedule/slots', [EventController::class, 'createScheduleSlots'])->name('schedule.slots');
            
            // Event slots data for AJAX
            Route::get('/{eventId}/slots/available', [EventController::class, 'getAvailableSlots'])->name('slots.available');
            
            // Event Slots Management
            Route::prefix('{eventId}/slots')->name('slots.')->group(function () {
                Route::get('/', [EventController::class, 'slotsIndex'])->name('index');
                Route::get('/create', [EventController::class, 'createSlot'])->name('create');
                Route::post('/', [EventController::class, 'storeSlot'])->name('store');
                Route::get('/{slotId}', [EventController::class, 'showSlot'])->name('show');
                Route::get('/{slotId}/edit', [EventController::class, 'editSlot'])->name('edit');
                Route::put('/{slotId}', [EventController::class, 'updateSlot'])->name('update');
                Route::delete('/{slotId}', [EventController::class, 'destroySlot'])->name('destroy');
                Route::post('/{slotId}/activate', [EventController::class, 'activateSlot'])->name('activate');
                Route::post('/{slotId}/deactivate', [EventController::class, 'deactivateSlot'])->name('deactivate');
            });
            
            // INDIVIDUAL EVENT ROUTES - MUST COME AFTER MORE SPECIFIC ROUTES
            Route::get('/{id}', [EventController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [EventController::class, 'edit'])->name('edit');
            Route::put('/{id}', [EventController::class, 'update'])->name('update');
            Route::delete('/{id}', [EventController::class, 'destroy'])->name('destroy');
            
            // Event activation routes
            Route::post('/{id}/activate', [EventController::class, 'activate'])->name('activate');
            Route::post('/{id}/deactivate', [EventController::class, 'deactivate'])->name('deactivate');
        });

        // Tickets Management
        Route::prefix('tickets')->name('tickets.')->group(function () {
            Route::get('/', [EventController::class, 'ticketsIndex'])->name('index');
            
            // Ticket Validation
            Route::get('/validate', [EventController::class, 'validateTicketPage'])->name('validate.page');
            Route::post('/validate', [EventController::class, 'validateTicket'])->name('validate');
            
            // On-site Sales
            Route::get('/onsite', [EventController::class, 'onsiteSales'])->name('onsite');
            Route::post('/onsite/create', [EventController::class, 'createOnsiteSale'])->name('onsite.create');
            
            // Export tickets
            Route::post('/export', [EventController::class, 'exportTickets'])->name('export');
            
            // Update expired tickets
            Route::get('/update-expired', [EventController::class, 'updateExpiredTickets'])->name('update.expired');
            
            // Individual ticket routes
            Route::get('/{id}', [EventController::class, 'showTicket'])->name('show');
            Route::put('/{id}', [EventController::class, 'updateTicket'])->name('update');
            Route::get('/{id}/print', [EventController::class, 'printTicket'])->name('print');
            
            // Ticket operations (POST methods)
            Route::post('/{id}/check-in', [EventController::class, 'checkInTicket'])->name('check.in');
            Route::post('/{id}/cancel', [EventController::class, 'cancelTicket'])->name('cancel');
            Route::delete('/{id}/delete', [EventController::class, 'deleteTicket'])->name('delete');
            Route::post('/{id}/update-status', [EventController::class, 'updateTicketStatus'])->name('update.status');
        });

        // Promotions Management
        Route::prefix('promotions')->name('promotions.')->group(function () {
            Route::get('/', [EventController::class, 'promotionsIndex'])->name('index');
            Route::get('/{id}', [EventController::class, 'showPromotion'])->name('show');
            Route::get('/{id}/edit', [EventController::class, 'editPromotion'])->name('edit');
            Route::put('/{id}', [EventController::class, 'updatePromotion'])->name('update');
            
            // Promotion activation
            Route::post('/{id}/activate', [EventController::class, 'activatePromotion'])->name('activate');
            Route::post('/{id}/deactivate', [EventController::class, 'deactivatePromotion'])->name('deactivate');
        });

        // Promotions & Advertisements (main section)
        Route::prefix('promotions-ads')->name('promotions-ads.')->group(function () {
            Route::get('/', [PromotionAdvertisementController::class, 'index'])->name('index');
            Route::get('/create', [PromotionAdvertisementController::class, 'create'])->name('create');
            Route::post('/', [PromotionAdvertisementController::class, 'store'])->name('store');
            Route::get('/{id}', [PromotionAdvertisementController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [PromotionAdvertisementController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PromotionAdvertisementController::class, 'update'])->name('update');
            Route::delete('/{id}', [PromotionAdvertisementController::class, 'destroy'])->name('destroy');
            
            // Additional actions
            Route::post('/{id}/quick-toggle', [PromotionAdvertisementController::class, 'quickToggle'])->name('quick-toggle');
            Route::get('/{id}/add-advertisement', [PromotionAdvertisementController::class, 'addAdvertisementToPromotion'])->name('add-advertisement');
            
            // ANALYTICS ROUTE
            Route::get('/analytics', [PromotionAdvertisementController::class, 'analytics'])->name('analytics');
            
            // Batch operations
            Route::post('/batch-toggle', [PromotionAdvertisementController::class, 'batchToggle'])->name('batch-toggle');
            Route::post('/batch-delete', [PromotionAdvertisementController::class, 'batchDelete'])->name('batch-delete');
        });

        // Reports Management
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [EventController::class, 'reportsIndex'])->name('index');
            Route::get('/dashboard', [EventController::class, 'reportsDashboard'])->name('dashboard');
            Route::get('/generate', [EventController::class, 'generateReport'])->name('generate'); // <-- CHANGED FROM POST TO GET
            Route::get('/sales', [EventController::class, 'salesReport'])->name('sales');
            Route::get('/capacity', [EventController::class, 'capacityReport'])->name('capacity');
            Route::get('/attendance', [EventController::class, 'attendanceReport'])->name('attendance');
            Route::get('/download/{type}', [EventController::class, 'downloadReport'])->name('download');
            Route::get('/print/{type}', [EventController::class, 'printReport'])->name('print');
        });
        // Quick Actions
        Route::prefix('quick-actions')->name('quick.')->group(function () {
            Route::post('/validate-ticket/{ticketId}', [EventController::class, 'quickValidateTicket'])->name('validate.ticket');
            Route::post('/check-in/{ticketId}', [EventController::class, 'quickCheckIn'])->name('check.in');
            Route::post('/create-onsite', [EventController::class, 'quickOnsiteSale'])->name('create.onsite');
        });
    });

// Admin Routes - Using IDs for consistency
Route::prefix('admin')
    ->middleware(['auth', 'is_admin'])
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');

        // Users Management
        Route::get('/users', [AdminUserController::class, 'users'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{id}', [AdminUserController::class, 'showUser'])->name('users.show');
        Route::get('/users/{id}/edit', [AdminUserController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{id}', [AdminUserController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{id}', [AdminUserController::class, 'deleteUser'])->name('users.destroy');
        
        // Advertisement Management
        Route::get('/advertisements', [AdminAdsController::class, 'advertisements'])->name('advertisements.index');
        Route::get('/advertisements/create', [AdminAdsController::class, 'createAdvertisement'])->name('advertisements.create');
        Route::post('/advertisements', [AdminAdsController::class, 'storeAdvertisement'])->name('advertisements.store');
        Route::get('/advertisements/{id}/edit', [AdminAdsController::class, 'editAdvertisement'])->name('advertisements.edit');
        Route::put('/advertisements/{id}', [AdminAdsController::class, 'updateAdvertisement'])->name('advertisements.update');
        Route::delete('/advertisements/{id}', [AdminAdsController::class, 'deleteAdvertisement'])->name('advertisements.destroy');
        Route::post('/advertisements/{id}/toggle-status', [AdminAdsController::class, 'toggleStatus'])->name('advertisements.toggle-status');
        Route::get('/advertisements/{id}', [AdminAdsController::class, 'showAdvertisement'])->name('advertisements.show');
        
        // Island Locations Management
        Route::resource('island-locations', IslandLocationController::class);
        
        // Reports Management
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [AdminReportsController::class, 'reports'])->name('index');
            Route::get('/generate', [AdminReportsController::class, 'generateReportPage'])->name('generate-page');
            Route::get('/advertisement-analytics', [AdminReportsController::class, 'advertisementAnalytics'])->name('advertisement-analytics');
            Route::get('/sales-summary', [AdminReportsController::class, 'salesSummary'])->name('sales-summary');
            Route::get('/occupancy', [AdminReportsController::class, 'occupancyReport'])->name('occupancy');
            Route::get('/visitor-activity', [AdminReportsController::class, 'visitorActivity'])->name('visitor-activity');
            Route::post('/generate', [AdminReportsController::class, 'generateReport'])->name('generate');
            Route::post('/generate-sales', [AdminReportsController::class, 'generateSalesReport'])->name('generate-sales');
            Route::post('/generate-occupancy', [AdminReportsController::class, 'generateOccupancyReport'])->name('generate-occupancy');
            Route::post('/generate-visitor-activity', [AdminReportsController::class, 'generateVisitorActivityReport'])->name('generate-visitor-activity');
            Route::get('download/{id}', [AdminReportsController::class, 'downloadReport'])->name('download');
            Route::delete('delete/{id}', [AdminReportsController::class, 'deleteReport'])->name('delete');
            Route::get('analytics-data', [AdminReportsController::class, 'getAnalyticsData'])->name('analytics-data');
        });
        
        // System Settings
        Route::get('/settings', [AdminSystemController::class, 'systemSettings'])->name('settings');
        
        // Ferry Operators Management (Admin can manage ferry operators)
        Route::prefix('ferry-operators')->name('ferry_operators.')->group(function () {
            Route::get('/', [AdminUserController::class, 'ferryOperators'])->name('index');
            Route::get('/create', [AdminUserController::class, 'createFerryOperator'])->name('create');
            Route::post('/', [AdminUserController::class, 'storeFerryOperator'])->name('store');
            Route::get('/{id}/edit', [AdminUserController::class, 'editFerryOperator'])->name('edit');
            Route::put('/{id}', [AdminUserController::class, 'updateFerryOperator'])->name('update');
            Route::delete('/{id}', [AdminUserController::class, 'deleteFerryOperator'])->name('destroy');
        });
    });

// Public Routes (no authentication required)
Route::prefix('public')->name('public.')->group(function () {
    // Advertisement click tracking
    Route::post('/advertisement/{id}/click', [AdminAdsController::class, 'trackClick'])->name('advertisement.click');
    
    // Ferry schedule viewing (public)
    Route::get('/ferry-schedules', [VisitorFerryController::class, 'schedules'])->name('ferry.schedules');
    Route::get('/ferry-schedules/{ferry}', [VisitorFerryController::class, 'ferrySchedules'])->name('ferry.schedules.details');
    
    // Island locations
    Route::get('/island-locations', [IslandLocationController::class, 'publicIndex'])->name('island.locations');
});

// API Routes (for AJAX calls)
Route::prefix('api')->name('api.')->group(function () {
    // Ferry Operator API
    Route::prefix('ferry-operator')->middleware(['auth', 'is_ferry_operator'])->group(function () {
        Route::get('/dashboard-stats', [FerryOperatorDashboardController::class, 'getStats'])->name('ferry.dashboard.stats');
        Route::get('/schedule-availability/{date}', [FerryOperatorScheduleController::class, 'getAvailability'])->name('ferry.schedule.availability');
        Route::get('/pending-requests-count', [FerryOperatorTicketController::class, 'pendingCount'])->name('ferry.pending.requests.count');
        Route::post('/quick-validate/{ticketRequestId}', [FerryOperatorTicketController::class, 'quickValidate'])->name('ferry.quick.validate');
    });
    
    // Visitor API
    Route::prefix('visitor')->middleware(['auth', 'is_visitor'])->group(function () {
        Route::post('/ferry-request', [VisitorFerryController::class, 'apiRequestTicket'])->name('visitor.ferry.request');
    });
    
    // Public API
    Route::get('/ferries/available', [VisitorFerryController::class, 'availableFerries'])->name('ferries.available');
    Route::get('/ferry/{ferry}/schedules/{date}', [VisitorFerryController::class, 'ferrySchedulesByDate'])->name('ferry.schedules.by.date');
});

// Catch-all route for 404 - should be at the bottom
Route::fallback(function () {
    return response('Page not found - 404', 404);
});

Route::get('/test-analytics-auth', function() {
    if (!auth()->check()) {
        return "You are NOT logged in. Please login first.";
    }
    
    $user = auth()->user();
    $isThemeParkStaff = $user->user_type === 'theme_park_staff';
    
    return response()->json([
        'logged_in' => true,
        'user_id' => $user->id,
        'user_name' => $user->name,
        'user_type' => $user->user_type,
        'is_theme_park_staff' => $isThemeParkStaff,
        'can_access_analytics' => $isThemeParkStaff,
        'middleware_groups' => auth()->user()->getMiddlewareGroups() ?? 'N/A'
    ]);
});

// Force register the analytics route
if (!Route::has('theme-park-staff.promotions-ads.analytics')) {
    Route::get('/theme-park-staff/promotions-ads/analytics-fixed', 
        [\App\Http\Controllers\ThemeParkStaff\PromotionAdvertisementController::class, 'analytics']
    )->name('theme-park-staff.promotions-ads.analytics-fixed');
}