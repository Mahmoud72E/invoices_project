# Billing Website

This is a billing website project built with Laravel, JavaScript, and a Bootstrap theme. The website allows users to calculate invoices, track payment status (paid, not paid, or partially paid), and supports two languages: Arabic and English. The project also includes a role-based permission system.

## Features

- Create and manage invoices.
- Track payment status for each invoice (paid, not paid, partially paid).
- Multi-language support for Arabic and English.
- Role-based permission system to control user access.

## Technologies Used

- Laravel: a PHP web framework used for the backend development.
- JavaScript: used for client-side interactions and validations.
- Bootstrap: a popular CSS framework for building responsive and visually appealing websites.
- MySQL: a relational database management system used for storing data.

## Installation

1. Clone the repository:

```shell
    git clone [https://github.com/Mahmoud72E/invoices_project.git](https://github.com/Mahmoud72E/invoices_project.git)
```
2. Change to the project directory:
```shell
    cd invoices_project
```
3. Install the dependencies using Composer:
```shell
    composer install
```
4. Create a copy of the .env.example file and rename it to .env. Update the database configuration in the .env file to match your environment.

5. Generate a new application key:

```shell
    php artisan key:generate
```
6. Run the database migrations to create the required tables:
```shell
    php artisan migrate
```
7. Seed the database with the default data (including the admin user and permissions):
```shell
    php artisan db:seed --class:PermissionTableSeeder.php
    php artisan db:seed --class:CreateAdminUserSeeder.php
```
8. Serve the application:
```shell
    php artisan serve
```

9. Visit http://localhost:8000 in your web browser to access the billing website.

## Configuration
The following configuration options can be modified in the .env file:
```shell
    APP_NAME: The name of the application.
    APP_URL: The URL of the application.
    DB_HOST: The database host.
    DB_PORT: The database port.
    DB_DATABASE: The name of the database.
    DB_USERNAME: The username for the database connection.
    DB_PASSWORD: The password for the database connection.
    APP_LOCALE: The default language of the application (e.g., en for English, ar for Arabic).
```
## Usage
Log in using the admin credentials provided during the seed process.
Navigate to the invoices section to create, view, and manage invoices.
Update the payment status for each invoice (paid, not paid, or partially paid).
Use the language switcher to toggle between Arabic and English.
Users with appropriate roles and permissions can manage user access and settings.

## Contributing
Contributions are welcome! If you would like to contribute to this project, please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature or bug fix.
3. Make the necessary changes and commit your work.
4. Push your branch to your forked repository.
5. Submit a pull request detailing your changes.

## License
This project is licensed under the MIT License.

## Acknowledgements
This project was built using various open-source technologies and libraries. Special thanks to the Laravel community and contributors for their excellent work.

## Contact
For any inquiries or questions, please contact [mahmoud201411hotmailcom@gmail.com](mailto:mahmoud201411hotmailcom@gmail.com).
