<?php 

use App\Meetings; 

 
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

// Auth::routes();

// Authentication Routes...
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login')->name('login-post');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
// Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
// Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');


/**
|--------------------------------------------------------------------
|  Schedule
|--------------------------------------------------------------------
 */

Route::post("/searchajax","AutoCompleteController@autoComplete")->name("searchajax");
Route::post("/searchdata","AutoCompleteController@searchData")->name("searchdata");

Route::post('/savestate', 'DashboardController@saveState');


/*ADMIN*/
Route::prefix("/admin")->group(function () {

	Route::get("/", function () {
	  return redirect()->route("schedule-monthly");
	})->name("schedule");

	Route::prefix("/schedule")->group(function () {

		Route::get("/", function () {
		  return redirect()->route("schedule-monthly");
		})->name("schedule");

		#---- Methods ----
		Route::post("/create","MeetingsController@create")->name("meetings-create-post");
		Route::get("/meetingslayouts","MeetingsController@layouts")->name("layouts");

		Route::get("/advanced-booking","MeetingsController@advancedCreate")->name("advanced-booking");
		Route::post("/advanced-create","MeetingsController@advanced")->name("advanced-create");
		Route::get("/room-layouts","MeetingsController@roomLayouts")->name("room-layout-radio");
		Route::post("/validate-hours","MeetingsController@validateHours")->name("hours");

		/*Date range*/
		Route::post("/last-create-option","MeetingsController@lastOption")->name("last-create-option");
		Route::post("/same-options","MeetingsController@sameOptions")->name("same-options");
		Route::post("/first-create-option","MeetingsController@firstOption")->name("first-create-option");


		Route::post("/disabled-option","MeetingsController@disabledOption")->name("disabled-option");

		/*same date*/
		Route::post("/diferent-start","MeetingsController@diferentStartdate")->name("diferent-start");
		Route::post("/diferent-end","MeetingsController@diferentEnd")->name("diferent-end");
		Route::post("/only-validate-start","MeetingsController@onlyvalidateStart")->name("only-validate-start");
		Route::post("/same-date","MeetingsController@sameDate")->name("same-date");
		Route::post("/same-end","MeetingsController@sameEnd")->name("same-end");
		Route::post("/date-range","MeetingsController@dateRange")->name("date-range");

		/*diferent date*/
		Route::post("/first-val-dif","MeetingsController@firstvalDif")->name("first-val-dif");
		Route::post("/second-val-dif","MeetingsController@secondvalDif")->name("second-val-dif");


		Route::prefix("/booking")->group(function () {
			Route::get("/", function () {
			  return redirect()->route("schedule-monthly");
			});
			/*Edit*/
			Route::get('/{id}','MeetingsController@edit')->name("edit-booking");
			Route::post('/{id}','MeetingsController@update')->name("update-booking");
		});


		Route::prefix("/monthly-view")->group(function () {

			Route::get('/', 'HomeController@index')->name('schedule-monthly');
			Route::post("/meetings-month","HomeController@meetingsMonth")->name("total");
			Route::post("/meetings-detail","HomeController@meetingsDetail")->name("detail");

		});

		Route::prefix("/weekly-view")->group(function () {

			Route::get('/', 'WeekController@index')->name('schedule-weekly');

		});

		Route::prefix("/daily-view")->group(function () {

			Route::get('/', 'DayController@index')->name('schedule-daily');
			Route::post('/update-meeting-day','DayController@updateMeeting')->name("update-meeting-day");

		});

		
	});

	Route::prefix("/account")->group(function () {

		Route::get("/", function () {
		  return redirect()->route("schedule-monthly");
		})->name("schedule");

		Route::prefix("/my-account")->group(function () {

			Route::get('/', 'AccountController@myAccount')->name('profile');
			Route::post("/edit-profile","AccountController@editProfile")->name("edit-profile");
			Route::post('/changePassword','AccountController@changePassword')->name('changePassword');
		});

		Route::prefix("/my-meetings")->group(function () {

			Route::get('/', 'AccountController@myMeetings')->name('meetings');
			Route::delete('/deleteMeeting/{id}', 'AccountController@destroy')->name('deleteMeeting');
			Route::delete('/deleteAll', 'AccountController@deleteAll')->name('deleteAll');
		});

		Route::prefix("/meetings-approval")->group(function () {

			Route::get('/', 'AccountController@meetingsApproval')->name('meetings-approval');

			Route::post('/update-status','AccountController@updateStatus')->name("update-status");
		});

	});

	Route::prefix("/manage")->group(function () {

		Route::get("/", function () {
		  return redirect()->route("schedule-monthly");
		})->name("schedule");

		Route::prefix("/rooms")->group(function () {

			Route::get('/', 'RoomsController@roomList')->name('room-list');
			Route::post("/create","RoomsController@create")->name("create-room");
			Route::post('/update-room','RoomsController@updateRoom')->name("update-room");
			Route::delete('/deleteRoom/{id}', 'RoomsController@destroy')->name('deleteRoom');
			Route::delete('/deleteAllRoom', 'RoomsController@deleteAll')->name('deleteAllRoom');
		});

		Route::prefix("/resources")->group(function () {

			Route::get('/', 'ResourcesController@list')->name('resource-list');

			Route::post("/create","ResourcesController@create")->name("create-resource");
			Route::post('/update-resource','ResourcesController@updateResource')->name("update-resource");
			Route::delete('/deleteResource/{id}', 'ResourcesController@destroy')->name('deleteResource');
			Route::delete('/deleteAllRes', 'ResourcesController@deleteAll')->name('deleteAllRes');
		});

		Route::prefix("/users")->group(function () {

			Route::get('/', 'UsersController@list')->name('user-list');

			/*Update role*/
			Route::post('/update-role','UsersController@updateRole')->name("update-role");

			Route::post("/create","UsersController@create")->name("create-user");
			Route::post('/update-user','UsersController@updateUser')->name("update-user");
			Route::delete('/deleteUser/{id}', 'UsersController@destroy')->name('deleteUser');
			Route::delete('/deleteAllUser', 'UsersController@deleteAll')->name('deleteAllUser');

		});

		Route::prefix("/settings")->group(function () {

			Route::get('/', 'SettingsController@index')->name('settings');
			Route::post("/create","SettingsController@create")->name("create-settings");
			Route::post("/main-create","SettingsController@mainCreate")->name("main-create");
		});


	});

}); /*end admin*/


Route::prefix("/management")->group(function () {

	Route::get("/", function () {
	  return redirect()->route("manage-schedule-monthly");
	})->name("manage-schedule");

	Route::prefix("/schedule")->group(function () {

		Route::get("/", function () {
		  return redirect()->route("manage-schedule-monthly");
		})->name("manage-schedule");

		#---- Methods ----
		Route::post("/create","ManagementMeetingsController@create")->name("manage-meetings-create-post");
		Route::get("/meetingslayouts","ManagementMeetingsController@layouts")->name("manage-layouts");

		Route::get("/advanced-booking","ManagementMeetingsController@advancedCreate")->name("manage-advanced-booking");
		Route::post("/advanced-create","ManagementMeetingsController@advanced")->name("manage-advanced-create");
		Route::get("/room-layouts","ManagementMeetingsController@roomLayouts")->name("manage-room-layout-radio");
		Route::post("/validate-hours","ManagementMeetingsController@validateHours")->name("manage-hours");

		/*Date range*/
		Route::post("/last-create-option","ManagementMeetingsController@lastOption")->name("manage-last-create-option");
		Route::post("/same-options","ManagementMeetingsController@sameOptions")->name("manage-same-options");
		Route::post("/first-create-option","ManagementMeetingsController@firstOption")->name("manage-first-create-option");

		/*same date*/
		Route::post("/diferent-start","ManagementMeetingsController@diferentStartdate")->name("manage-diferent-start");
		Route::post("/diferent-end","ManagementMeetingsController@diferentEnd")->name("manage-diferent-end");
		Route::post("/only-validate-start","ManagementMeetingsController@onlyvalidateStart")->name("manage-only-validate-start");
		Route::post("/same-date","ManagementMeetingsController@sameDate")->name("manage-same-date");
		Route::post("/same-end","ManagementMeetingsController@sameEnd")->name("manage-same-end");
		Route::post("/date-range","ManagementMeetingsController@dateRange")->name("manage-date-range");

		/*diferent date*/
		Route::post("/first-val-dif","ManagementMeetingsController@firstvalDif")->name("manage-first-val-dif");
		Route::post("/second-val-dif","ManagementMeetingsController@secondvalDif")->name("manage-second-val-dif");


		Route::prefix("/booking")->group(function () {
			Route::get("/", function () {
			  return redirect()->route("manage-schedule-monthly");
			});
			/*Edit*/
			Route::get('/{id}','ManagementMeetingsController@edit')->name("manage-edit-booking");
			Route::post('/{id}','ManagementMeetingsController@update')->name("manage-update-booking");
		});

		Route::prefix("/monthly-view")->group(function () {

			Route::get('/', 'ManagementHomeController@index')->name('manage-schedule-monthly');
			Route::post("/meetings-month","ManagementHomeController@meetingsMonth")->name("manage-total");

		});

		Route::prefix("/weekly-view")->group(function () {

			Route::get('/', 'ManagementWeekController@index')->name('manage-schedule-weekly');
		});

		Route::prefix("/daily-view")->group(function () {

			Route::get('/', 'ManagementDayController@index')->name('manage-schedule-daily');
			Route::post('/update-meeting-day','ManagementDayController@updateMeeting')->name("manage-update-meeting-day");

		});

		
	});


	Route::prefix("/account")->group(function () {

		Route::get("/", function () {
		  return redirect()->route("manage-schedule-monthly");
		})->name("manage-schedule");

		Route::prefix("/my-account")->group(function () {

			Route::get('/', 'ManagementAccountController@myAccount')->name('manage-profile');
			Route::post("/edit-profile","ManagementAccountController@editProfile")->name("manage-edit-profile");
			Route::post('/changePassword','ManagementAccountController@changePassword')->name('manage-changePassword');
		});

		Route::prefix("/my-meetings")->group(function () {

			Route::get('/', 'ManagementAccountController@myMeetings')->name('manage-meetings');
			Route::delete('/deleteMeeting/{id}', 'ManagementAccountController@destroy')->name('manage-deleteMeeting');
			Route::delete('/deleteAll', 'ManagementAccountController@deleteAll')->name('manage-deleteAll');
		});

		Route::prefix("/meetings-approval")->group(function () {

			Route::get('/', 'ManagementAccountController@meetingsApproval')->name('manage-meetings-approval');

			Route::post('/update-status','ManagementAccountController@updateStatus')->name("manage-update-status");
		});

	});

}); /* end management */


Route::prefix("/user")->group(function () {

	Route::get("/", function () {
	  return redirect()->route("user-schedule-monthly");
	})->name("user-schedule");

	Route::prefix("/schedule")->group(function () {

		Route::get("/", function () {
		  return redirect()->route("user-schedule-monthly");
		})->name("user-schedule");

		#---- Methods ----
		Route::post("/create","UserMeetingsController@create")->name("user-meetings-create-post");
		Route::get("/meetingslayouts","UserMeetingsController@layouts")->name("user-layouts");

		Route::get("/advanced-booking","UserMeetingsController@advancedCreate")->name("user-advanced-booking");
		Route::post("/advanced-create","UserMeetingsController@advanced")->name("user-advanced-create");
		Route::get("/room-layouts","UserMeetingsController@roomLayouts")->name("user-room-layout-radio");
		Route::post("/validate-hours","UserMeetingsController@validateHours")->name("user-hours");

		/*Date range*/
		Route::post("/last-create-option","UserMeetingsController@lastOption")->name("user-last-create-option");
		Route::post("/same-options","UserMeetingsController@sameOptions")->name("user-same-options");
		Route::post("/first-create-option","UserMeetingsController@firstOption")->name("user-first-create-option");

		/*same date*/
		Route::post("/diferent-start","UserMeetingsController@diferentStartdate")->name("user-diferent-start");
		Route::post("/diferent-end","UserMeetingsController@diferentEnd")->name("user-diferent-end");
		Route::post("/only-validate-start","UserMeetingsController@onlyvalidateStart")->name("user-only-validate-start");
		Route::post("/same-date","UserMeetingsController@sameDate")->name("user-same-date");
		Route::post("/same-end","UserMeetingsController@sameEnd")->name("user-same-end");
		Route::post("/date-range","UserMeetingsController@dateRange")->name("user-date-range");

		/*diferent date*/
		Route::post("/first-val-dif","UserMeetingsController@firstvalDif")->name("user-first-val-dif");
		Route::post("/second-val-dif","UserMeetingsController@secondvalDif")->name("user-second-val-dif");


		Route::prefix("/booking")->group(function () {
			Route::get("/", function () {
			  return redirect()->route("user-schedule-monthly");
			});
			/*Edit*/
			Route::get('/{id}','UserMeetingsController@edit')->name("user-edit-booking");
			Route::post('/{id}','UserMeetingsController@update')->name("user-update-booking");
		});

		Route::prefix("/monthly-view")->group(function () {

			Route::get('/', 'UserHomeController@index')->name('user-schedule-monthly');
			Route::post("/meetings-month","UserHomeController@meetingsMonth")->name("user-total");

		});

		Route::prefix("/weekly-view")->group(function () {

			Route::get('/', 'UserWeekController@index')->name('user-schedule-weekly');
		});

		Route::prefix("/daily-view")->group(function () {

			Route::get('/', 'UserDayController@index')->name('user-schedule-daily');
			Route::post('/update-meeting-day','UserDayController@updateMeeting')->name("user-update-meeting-day");

		});

		
	});


	Route::prefix("/account")->group(function () {

		Route::get("/", function () {
		  return redirect()->route("user-schedule-monthly");
		})->name("user-schedule");

		Route::prefix("/my-account")->group(function () {

			Route::get('/', 'UserAccountController@myAccount')->name('user-profile');
			Route::post("/edit-profile","UserAccountController@editProfile")->name("user-edit-profile");
			Route::post('/changePassword','UserAccountController@changePassword')->name('user-changePassword');
		});

	});

}); /* end user */