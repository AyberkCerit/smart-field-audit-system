<div align="right">
  🇬🇧 <b>English</b> | <a href="README.tr.md">🇹🇷 Türkçe</a>
</div>

# Smart Field Audit System

The Smart Field Audit System is a comprehensive Laravel web application that allows field personnel, regional managers, and system administrators to manage field operations, task distributions, and audit processes smoothly and efficiently in a digital environment.

## 📸 Screenshots

The following screenshots give an idea about the general look and operation of the system:

**1. Welcome Screen**
![Welcome Screen](screenshots/welcome.png)

**2. Features and Presentation**
![Features](screenshots/features.png)

**3. Login Page**
![Login Screen](screenshots/login.png)

**4. Dashboard**
![Dashboard](screenshots/dashboard.png)

## 🚀 Key Features

- **Advanced Role and Permission Management (RBAC)**
  - **Admin:** Manages the entire system, users, and settings.
  - **Manager:** Assigns tasks to field personnel, manages audit points, and tracks processes.
  - **Field Personnel:** Claims assigned tasks, completes them, and can attach files/photos as evidence.

- **Task and Operations Management**
  - Task creation, assignment, and status updates (pending, in progress, completed, etc.).
  - Uploading multiple files and media to tasks as evidence (powered by Spatie Media Library).

- **Audit Points and Map Integration**
  - Geolocation (latitude/longitude) supported audit points.
  - **Leaflet.js** integration to select and view locations directly on the map.

- **Traceability and Security**
  - Automatic background logging of all critical operations (CRUD) using Spatie Activitylog.
  - Activity Logs screen to track who did what and when.

- **Feedback and Notification System**
  - Communication and feedback history between personnel and managers.
  - Real-time in-system notifications for new events.

- **Export and Reporting**
  - Rich report generation in Excel (Maatwebsite Excel) and PDF (DomPDF) formats.
  - QR Code (Simple Qrcode) generation for audit points.

## 🛠️ Technologies and Packages Used

**Backend**
- PHP 8.3+
- Laravel 11.x
- MySQL / SQLite

**Frontend**
- Tailwind CSS (Styling)
- Alpine.js (Interactivity)
- Vite (Asset Management)
- Leaflet.js (Maps)
- Anime.js (Animations)

**Highlighted Laravel Packages**
- `spatie/laravel-permission`: Role and permission management
- `spatie/laravel-medialibrary`: Media management
- `spatie/laravel-activitylog`: Activity logging
- `barryvdh/laravel-dompdf`: PDF generation
- `maatwebsite/excel`: Excel operations
- `simplesoftwareio/simple-qrcode`: QR code generation

## ⚙️ Installation and Setup

Follow these steps to run the project on your local environment:

1. **Clone the Repository**
   ```bash
   git clone https://github.com/AyberkCerit/smart-field-audit-system.git
   cd file_management_service
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Set Up Environment Variables**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Open your `.env` file and configure your database settings (DB_*) according to your local server.

4. **Database Migrations and Dummy Data (Seeder)**
   To ensure the system works properly (creating roles and sample data), you need to migrate and seed the database:
   ```bash
   php artisan migrate --seed
   ```
   *Note: After the seed process, an `admin@sahadenetim.com` (password: password) account and various test data will be created automatically.*

5. **Start the Application**
   To run the server and frontend assets concurrently:
   ```bash
   npm run dev
   php artisan serve
   ```
   *Optional: Run `php artisan queue:listen` for email and asynchronous notifications.*

## 🔒 Security Notes

- CSRF protection is active on APIs and public forms.
- Role-based authorizations are strictly enforced across all Route structures, Policies, and Controllers.
- Before deploying to a live environment, make sure the `APP_DEBUG` value in your `.env` file is set to `false`.
