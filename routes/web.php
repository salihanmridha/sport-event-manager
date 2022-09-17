<?php

use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SportController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ContactRelationshipController;
use App\Http\Controllers\Auth\UserProfileController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\LandingPage\LandingPageController;
use App\Http\Controllers\Admin\VenueController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\OrganizationRoleController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\TeamBlockController;
use App\Http\Controllers\Admin\VenueTypeController;

Route::redirect('/', '/login');

Auth::routes(['register' => false]);

Route::group(
    ['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']],
    function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');

        // Permissions
        Route::resource('permissions', PermissionController::class, [
            'except' => ['store', 'update', 'destroy'],
        ]);

        // Roles
        Route::resource('roles', RoleController::class, [
            'except' => ['store', 'update', 'destroy'],
        ]);

        // Users
        Route::post('users/media', [UserController::class, 'storeMedia'])->name(
            'users.storeMedia'
        );
        Route::resource('users', UserController::class, [
            'except' => ['store', 'update', 'destroy'],
        ]);

        // Sport
        Route::resource('sports', SportController::class, [
            'except' => ['store', 'update', 'destroy'],
        ]);
        Route::post('sports/media', [
            SportController::class,
            'storeMedia',
        ])->name('sports.storeMedia');

        Route::resource('teams.members', TeamMemberController::class);


        // Team
        Route::post('teams/media', [TeamController::class, 'storeMedia'])->name(
            'teams.storeMedia'
        );
        Route::resource('teams', TeamController::class, [
            'except' => ['store', 'update', 'destroy'],
        ]);

        // Country
        Route::resource('countries', CountryController::class, [
            'except' => ['store', 'update', 'destroy'],
        ]);

        // Event
        Route::post('events/media', [
            EventController::class,
            'storeMedia',
        ])->name('events.storeMedia');
        Route::resource('events', EventController::class, [
            'except' => ['store', 'update', 'destroy'],
        ]);

        Route::get('events/{event}/list-player/', [
            EventController::class,
            'getListPlayer',
        ])->name('events.listPlayer');

        // Currency
        Route::resource('currencies', CurrencyController::class, [
            'except' => ['store', 'update', 'destroy'],
        ]);

        // Contact Relationship
        Route::resource('relationship', ContactRelationshipController::class, [
            'except' => ['store', 'update', 'destroy'],
        ]);
        // Announcement
        Route::post('announcements/media', [
            AnnouncementController::class,
            'storeMedia',
        ])->name('announcements.storeMedia');
        Route::resource('announcements', AnnouncementController::class, [
            'except' => ['store', 'update', 'destroy'],
        ]);
        // skill
        Route::resource('skillLevelMngs', SkillController::class, [
            'except' => ['store', 'update', 'destroy'],
        ]);
        Route::resource('skills', SkillController::class, [
            'except' => ['store', 'update', 'destroy'],
        ]);

        //Organization role
        Route::resource(
            'organization-role',
            OrganizationRoleController::class,
            [
                'except' => ['store', 'update', 'destroy'],
            ]
        );

        // Venues
        Route::post('venues/media', [
            VenueController::class,
            'storeMedia',
        ])->name('venues.storeMedia');

        Route::get('venue/courts/{id}', [
            VenueController::class,
            'vanueCourts',
        ])->name('venues.courts');

        Route::resource('venues', VenueController::class, [
            'except' => ['store', 'update', 'destroy'],
        ]);

        // Venue type
        Route::resource('venue-types', VenueTypeController::class, [
            'except' => ['store', 'update', 'destroy'],
        ]);

        // Team Block
        Route::resource('teams.team_block', TeamBlockController::class, [
            'except' => ['store', 'update', 'destroy'],
        ]);

        // Reservation
        Route::resource('reservations', ReservationController::class, [
            'except' => ['store', 'update', 'destroy'],
        ]);

        // Team member request
        Route::get('teams/{team}/member-request', [
            TeamMemberController::class,
            'listMemberRequest'
        ])->name('team.member_request');

        // Invite Player
        Route::get('events/{event}/invite-player', [EventController::class, 'invitePlayer'])->name('events.invite_player');
    }
);

// landing page
Route::get('/landing-page', [LandingPageController::class, 'index'])->name(
    'landing-page'
);
Route::get('/landing-page-banner', [
    LandingPageController::class,
    'banner',
])->name('landing-page-banner');

Route::group(
    ['prefix' => 'profile', 'as' => 'profile.', 'middleware' => ['auth']],
    function () {
        if (
            file_exists(
                app_path('Http/Controllers/Auth/UserProfileController.php')
            )
        ) {
            Route::get('/', [UserProfileController::class, 'show'])->name(
                'show'
            );
        }
    }
);
Route::any('adminer', '\Aranyasen\LaravelAdminer\AdminerController@index');
