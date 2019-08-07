# iRooms
Simple room booking system created using Laravel 5.5, is a web based booking software to manage rooms and resources, a very easy to use interface.

## How does it work?

 - Meetings booking: Book all your meetings in your own customized platform. Select rooms, resources and rooms layout. Easy start meetings with your team and clients.
 - Your time is valuable: Visualize your meetings by month, week or day and administrate your time to be in time on everything that matters to you.
 - Available meetings finder: Find meetings across the platform and verify the availability of the rooms and time to create your schedule.
 - Edit your book: Need to move your meeting? Update and re-schedule it, also you can change the room layout or the resources that you need to use.

## Features
- Manage your meetings: Every meeting that you create can be modified easily. Change meeting time, renamed a meeting, room, resources and the room layout for the meeting.
- Custom resources: You can create all the resources that you have able to use in your organization. These resources can be added in every meeting that you create in the platform.
- Build your rooms: The platform allows you to build your custom rooms with amazing settings such as color tag, room capacity, open and closing the time of the meeting and the multiple selection of room layouts.
- User roles: Each user that you create can be classified as user, manager and administrator; each of them gives you the opportunity to define an specific process to manage every created meeting.

## Technologies

- HTML 5
- CSS 3
- PHP 7
- Laravel 5.5
- MySQL

### Requirements

 - PHP >= 7.1.3
 - OpenSSL PHP Extension
 - PDO PHP Extension
 - Mbstring PHP Extension
 - Tokenizer PHP Extension
 - XML PHP Extension
 - Ctype PHP Extension
 - JSON PHP Extension

## Install

To complete the installation process you need to follow the following steps:

 1. Download this repository.
 2. Install dependencies with the command: `composer install`
 3. Put the project in the root folder of your server or the folder where do you want to publish the platform.
 4. Create a database and its user with all the permissions.
 5. Acceed via URL to the platform.

**Additional note:**

 - In case if necessary, verify that the root and public directory have all necessary permissions to create, modify or delete files. You can do this with the command: `sudo chmod -R 775 [directory]`

At the configuration panel you need to configure these tabs:

- General: In this tab you will be able to establish all the general settings for your project, base URL, App name, logo, favicon and description.
- Database: Add the information and user access of the pre-created database for your project.
- User: Here you can create your first user with his general information, this user will have the administrator role.
- Email: In the last tab you have to setup the SMTP credentials and the required information, this will allow you to send emails from the platform.

Finally, when you complete the information of all tabs, save the configuration and you will be redirected to the login screen of the platform.