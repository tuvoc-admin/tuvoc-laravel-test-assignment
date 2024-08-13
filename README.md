
## Project Version
- laravel framework ^11.9
- php ^8.2

## Prerequisites
- Composer
- PHP (V >= 8.2)
- MySQL
- Node (V >=20)
- Npm

## Installation Steps
- Install project: git clone 
- Go to project directory
- Install PHP dependencies using Composer: ```composer install```
- Install npm dependencies: ```npm install```

## Configuration
- Create env file: ```cp .env.example .env``` or make a copy of `.env.example` to `.env` in the same directory
- Configure any additional environment variables required by your project in the `.env` file.
- Generate application key: ```php artisan key:generate```

## Database Setup
- Create a new database for your project in MySQL or your preferred database system.
- Update the .env file with your database credentials and with Email credential.
- Migrate the database tables:	```php artisan migrate --seed```

## Development Server
- Make Build for fronend: ```npm run build```
- Start the development server: ```php artisan serve```
- Start a queue worker to process the email notification jobs: ```php artisan queue:work```
- Your application should now be run at your localhost
- If your application running via `127.0.0.1:8000` then change the `.env`'s `APP_URL` varible with `127.0.0.1:8000` instead of `localhost:8000`

## Enable the zip extension
-  in `php.ini` file, Search for the line `;extension=zip` Remove the semicolon to enable the extension

## About Project
- Program to track student sessions

### User authentication Module 
	- user can authenticate using below details
	- email: test@example.com, password: 123456

### Students Module
	- Create student
	- List Student

### Parse Docx File
	- Upload Docs to parse data

### Below Done API's 
	- Find Postman collection attached as Assignment_tuvoc.postman_collection
	- login - Default login with email:test@example.com, password:123456
	- addStudent
	- listStudent
	- updateAvailability
	- scheduleSession
	- rateSession
	- parseTargetDocx

