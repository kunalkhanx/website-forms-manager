# Website Forms Manager
A portal built with Laravel 11, MySQL, TailwindCSS, and Flowbite, allowing users to create and manage forms, fields, and submissions. It also provides a REST API for collecting form data, making it an ideal solution for building custom form-based applications or data collection platforms.

## Features
- **Form Creation & Management:** Users can create multiple forms with custom fields.
- **Form Submissions:** Store and view form submission data in the portal.
- **REST API:** Collect form data through a REST API for external integrations.
- **Field Customization:** Create and manage different types of fields for your forms (text, number, files, etc.).
- **Responsive Design:** Built with TailwindCSS and Flowbite for modern, responsive UI components.

## Technologies
- **Laravel 11:** PHP framework used to build scalable, secure web applications.
- **MySQL:** Relational database used to store user, form, and submission data.
- **TailwindCSS:** Utility-first CSS framework for creating fast, responsive user interfaces.
- **Flowbite:** Library of responsive UI components built with TailwindCSS.

## Installation
1. Clone the repository:
    ```bash
    git clone https://github.com/kunalkhanx/website-forms-manager.git
    cd website-forms-manager
    ```
2. Install dependencies:
    ```bash
    composer install
    npm install
    ```
3. Set up environment variables by copying .env.example to .env and updating the following values:
    ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your-database
    DB_USERNAME=your-username
    DB_PASSWORD=your-password
    ```
4. Run the migrations:
    ```bash
    php artisan migrate
    ```
5. Generate APP_KEY
    ```bash
    php artisan key:generate
    ```
6. Build the frontend assets:
    ```bash
    npm run dev
    ```
7. Start the development server:
    ```bash
    php artisan serve
    ```
8. Access the application at http://localhost:8000

## Usage
- Create forms through the dashboard and add fields such as text, numbers, dates, etc.
- View and manage submissions from users who fill out your forms.
- Use the REST API to submit form data programmatically.

## License
This project is licensed under the MIT License.