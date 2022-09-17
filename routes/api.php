<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\Me\ProfileController;
use App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\Country\CountryController;
use App\Http\Controllers\API\Sport\SportController;
use App\Http\Controllers\API\Currency\CurrenciesController;
use App\Http\Controllers\API\Event\EventController;
use App\Http\Controllers\API\Team\TeamBlockController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\API\Upload\UploadController;
use App\Http\Controllers\API\Team\TeamController;
use App\Http\Controllers\API\Post\PostController;
use App\Http\Controllers\API\Announcement\AnnouncementController;
use App\Http\Controllers\API\Auth\Me\PasswordController;
use App\Http\Controllers\API\BookMark\BookMarkController;
use App\Http\Controllers\API\Venue\VenueTypeController;
use App\Http\Controllers\API\Venue\VenueController;
use App\Http\Controllers\API\Comment\CommentController;

Route::get('user/change-email', [
    ProfileController::class,
    'updateEmail',
])->name('change-email');

Route::group(['as' => '.api'], function () {
    Route::post('login', [Auth\LoginController::class, 'login'])->name('login');
    Route::post('login-social', [
        Auth\LoginController::class,
        'socialLogin',
    ])->name('socialLogin');

    // Reset Password
    Route::group(['prefix' => 'password', 'as' => 'password.'], function () {
        Route::put('change', [PasswordController::class, 'change'])
            ->name('change')
            ->middleware(['auth:sanctum']);
        Route::post('email', [
            Auth\ForgotPasswordController::class,
            'sendResetLinkEmail',
        ])->name('sendResetLinkEmail');
        Route::post('check-reset-link', [
            Auth\ResetPasswordController::class,
            'checkResetLink',
        ])->name('check-reset-link');
        Route::post('reset', [
            Auth\ResetPasswordController::class,
            'reset',
        ])->name('reset');
        Route::post('forgot', [
            Auth\ForgotPasswordController::class,
            'sendResetLinkEmail',
        ])->name('sendResetLinkEmail');
        Route::post('check-reset-link', [
            Auth\ResetPasswordController::class,
            'checkResetLink',
        ])->name('check-reset-link');
        Route::post('reset', [
            Auth\ResetPasswordController::class,
            'reset',
        ])->name('reset');
    });

    Route::post('register/check-existing', [
        Auth\RegisterController::class,
        'checkExisting',
    ])->name('check-existing');
    Route::post('register-social', [
        Auth\RegisterController::class,
        'registerSocial',
    ])->name('registerSocial');
    Route::post('register', [Auth\RegisterController::class, 'register'])->name(
        'register'
    );
    Route::post('upload', [UploadController::class, 'upload'])->name('upload');
});

Route::group(['as' => 'api.', 'middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [Auth\LogoutController::class, 'logout'])->name(
        'logout'
    );
    // API USER MANAGEMENT
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::post('check-password', [
            ProfileController::class,
            'checkValidPassword',
        ])->name('check-password');
        Route::delete('{id}', [ProfileController::class, 'destroy'])->name(
            'destroy'
        );
        Route::patch('{id}', [ProfileController::class, 'update'])->name(
            'update'
        );
        Route::get('{user}/profile-content', [
            UserController::class,
            'infoContent',
        ])->name('user.content');
        Route::get('{user}/profile', [UserController::class, 'info'])->name(
            'user.profile'
        );
    });
    Route::group(['prefix' => 'me', 'as' => 'profile.'], function () {
        Route::get('profile', [ProfileController::class, 'info'])->name(
            'profile'
        );
        Route::get('profile-content', [
            ProfileController::class,
            'infoContent',
        ])->name('profile.content');
    });
});

// REQUIRED AUTH
Route::middleware('auth:sanctum')->group(function () {
    // Comment
    Route::post('comment', [CommentController::class, 'createComment'])->name(
        'createComment'
    );

    Route::patch('comment/{id}', [
        CommentController::class,
        'updateComment',
    ])->name('updateComment');

    Route::delete('comment/{id}', [
        CommentController::class,
        'removeComment',
    ])->name('removeComment');
    #Post
    Route::post('post', [PostController::class, 'createPost'])->name(
        'createPost'
    );
    Route::get('post/{id}', [PostController::class, 'getDetailPost'])->name(
        'getDetailPost'
    );
    Route::patch('post/{id}', [PostController::class, 'updatePost'])->name(
        'updatePost'
    );
    Route::delete('post/{id}', [PostController::class, 'removePost'])->name(
        'removePost'
    );
    // EVENT
    Route::get('event', [EventController::class, 'index'])->name('event');
    Route::get('event/schedule', [EventController::class, 'schedule'])->name(
        'event.schedule'
    );
    Route::get('schedule-calendar', [
        EventController::class,
        'scheduleCalendar',
    ])->name('event.scheduleCalendar');

    Route::post('event/{event_id}/join', [
        EventController::class,
        'join',
    ])->name('event.join');
    Route::get('event/event-code', [EventController::class, 'eventCode'])->name(
        'event.code'
    );
    Route::post('event/{event}/leave', [EventController::class, 'leave'])->name(
        'event.leave'
    );
    Route::get('event/{id}', [EventController::class, 'getDetailEvent'])->name(
        'event.getDetailEvent'
    );
    Route::get('event-booking-info/{id}', [
        EventController::class,
        'eventBookingInfo',
    ])->name('event.eventBookingInfo');
    Route::post('event', [EventController::class, 'store'])->name(
        'event.store'
    );
    Route::put('event/{id}', [EventController::class, 'updateEvent'])->name(
        'event.updateEvent'
    );
    Route::delete('event/{id}', [EventController::class, 'destroy'])->name(
        'event.updateEvent'
    );
    Route::get('schedule-bydate', [
        EventController::class,
        'scheduleByDate',
    ])->name('event.scheduleByDate');
    Route::get('is-overlap-schedule', [
        EventController::class,
        'isOverlapSchedule',
    ])->name('event.isOverlapSchedule');
    Route::get('event/code/{private_code}', [
        EventController::class,
        'show',
    ])->name('show');

    // Route::post('event/{event_id}/join', [
    //     EventController::class,
    //     'joinEvent',
    // ])->name('event.joinEvent');

    // USER
    Route::get('user/{id}', [UserController::class, 'getDetailUser'])->name(
        'user.getDetailUser'
    );
    Route::get('user-search', [UserController::class, 'searchUser'])->name(
        'user.searchUser'
    );
    Route::get('user', [UserController::class, 'index'])->name('user');

    Route::put('user/{id}', [UserController::class, 'updateUser'])->name(
        'user.updateUser'
    );
    // TEAM

    Route::get('team/search', [
        TeamController::class,
        'searchTeamByName',
    ])->name('searchTeamByName');
    Route::get('team', [TeamController::class, 'getAllTeam'])->name(
        'getAllTeam'
    );
    Route::get('team/{team}/profile-content', [
        TeamController::class,
        'getProfileContent',
    ])->name('team.getProfileContent');

    Route::get('team/verify', [TeamController::class, 'teamVerify'])->name(
        'team.teamVerify'
    );

    Route::get('team/{id}', [TeamController::class, 'getDetailTeam'])->name(
        'getDetailTeam'
    );
    Route::post('team', [TeamController::class, 'createTeam'])->name(
        'createTeam'
    );
    Route::put('team/{id}', [TeamController::class, 'updateTeamProfile'])->name(
        'updateTeamProfile'
    );
    Route::delete('team/{id}', [TeamController::class, 'removeTeam'])->name(
        'removeTeam'
    );
    Route::get('team/{team_id}/profile', [
        TeamController::class,
        'getTeamProfile',
    ])->name('getTeamProfile');

    Route::get('team-request/{team_id}', [
        TeamController::class,
        'getAllTeamRequest',
    ])->name('getAllTeamRequest');
    Route::post('team-request', [
        TeamController::class,
        'requestJoinTeam',
    ])->name('requestJoinTeam');

    Route::delete('team-request/{team_id}', [
        TeamController::class,
        'cancelRequestTeam',
    ])->name('cancelRequestTeam');

    Route::delete('team-leave/{team_id}', [
        TeamController::class,
        'userLeaveTeam',
    ])->name('userLeaveTeam');

    Route::get('skill-level', [TeamController::class, 'getAllTeamLevel'])->name(
        'skillLevel'
    );
    Route::get('skill-level/{id}', [
        TeamController::class,
        'getDetailTeamLevel',
    ])->name('getDetailTeamLevel');

    Route::get('team-member/{team_id}', [
        TeamController::class,
        'getListTeamById',
    ])->name('getListTeamById');

    Route::get('team-role/{team_id}', [
        TeamController::class,
        'getRoleAndPermission',
    ])->name('getRoleAndPermission');

    Route::get('team-member/{team_id}/{member_id}', [
        TeamController::class,
        'getDetailTeamMember',
    ])->name('getDetailTeamMember');

    Route::get('team-role', [TeamController::class, 'getAllRoleTeam'])->name(
        'getAllRoleTeam'
    );

    Route::delete('team-member/{team_id}/{member_id}', [
        TeamController::class,
        'removeTeamMember',
    ])->name('removeTeamMember');

    Route::put('team-member/{team_id}/{member_id}', [
        TeamController::class,
        'updateTeamMember',
    ])->name('updateTeamMember');

    Route::post('team-invite/{team_id}', [
        TeamController::class,
        'inviteUserToTeam',
    ])->name('inviteUserToTeam');

    Route::get('/team-block/{team_id}', [
        TeamBlockController::class,
        'index',
    ])->name('index');
    Route::post('/team-block/{team_id}', [
        TeamBlockController::class,
        'create',
    ])->name('create');
    Route::delete('/team-block/{team_id}/{user_id}', [
        TeamBlockController::class,
        'destroy',
    ])->name('destroy');

    Route::get('team-roster/{team_id}', [
        TeamController::class,
        'getTeamRoster',
    ])->name('getTeamRoster');

    Route::patch('team-roster/{team_id}', [
        TeamController::class,
        'updateTeamRoster',
    ])->name('updateTeamRoster');

    Route::post('team/{team_id}/change-ownership', [
        TeamController::class,
        'changeOwnerShip',
    ])->name('changeOwnerShip');

    Route::get('my-team', [TeamController::class, 'getMyTeam'])->name(
        'getMyTeam'
    );

    Route::get('team-managed', [TeamController::class, 'getTeamManager'])->name(
        'getTeamManager'
    );

    Route::get('team-owned', [TeamController::class, 'getTeamOwned'])->name(
        'getTeamOwned'
    );
    Route::put('team-request/{team_id}/{team_request_id}', [
        TeamController::class,
        'teamRequestAccess',
    ])->name('teamRequestAccess');

    // END TEAM
    // Organization role
    Route::get('organization-role', [
        TeamController::class,
        'getAllOrganizationRole',
    ])->name('getAllOrganizationRole');
    Route::get('organization-role/{id}', [
        TeamController::class,
        'getDetailOrganizationRole',
    ])->name('getDetailOrganizationRole');
    // Contact Relationship
    Route::get('contact-relationship', [
        TeamController::class,
        'getAllContactRelationship',
    ])->name('getAllContactRelationship');
    Route::get('contact-relationship/{id}', [
        TeamController::class,
        'getDetailContactRelationship',
    ])->name('getDetailContactRelationship');

    // announcement
    Route::get('announcement/{id}', [
        AnnouncementController::class,
        'getDetailAnnouncement',
    ])->name('getDetailAnnouncement');

    // Venue
    Route::get('venue', [VenueController::class, 'getAllVenue'])->name(
        'getAllVenue'
    );
    Route::get('venue/{id}', [VenueController::class, 'getDetailVenue'])->name(
        'getDetailVenue'
    );
    Route::get('venue-type', [VenueTypeController::class, 'index'])->name(
        'index'
    );
    Route::get('venue-type/{id}', [VenueTypeController::class, 'show'])->name(
        'show'
    );
   //Bookmark
   Route::get('bookmark/{type}',[ BookmarkController::class, 'getBookmark'])->name('getBookmark');
   Route::get('bookmark/{type}/{status}',[ BookmarkController::class, 'getBookmark'])->name('getEventBookmark');
   
    // BOOKMARK
    Route::post('bookmark', [BookMarkController::class, 'addBookMark'])->name(
        'addBookMark'
    );

    Route::delete('bookmark/{target_id}', [
        BookMarkController::class,
        'deleteBookMark',
    ])->name('deleteBookMark');
});

// API COUNTRY
Route::controller(CountryController::class)->group(function () {
    Route::get('country', 'getListAllCountry')->name(
        'country.getListAllCountry'
    );
    Route::get('country/{id}', 'getDetailCountry')->name(
        'country.getDetailCountry'
    );
});

// API CURRENCY
Route::controller(CurrenciesController::class)->group(function () {
    Route::get('currency', 'index');
    Route::get('currency/{id}', 'show');
});
// API SPORT
Route::controller(SportController::class)->group(function () {
    Route::get('sport', 'getListAllSport')->name('sport.getListAllSport');
    Route::get('sport/{id}', 'getDetailSport')->name('sport.getDetailSport');
});
