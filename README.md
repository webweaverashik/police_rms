# Police Report Management System (PRMS)

A **secure, role-based web application** for managing political program reports, designed for police and administrative authorities.
Built with **Laravel (Backend)** and **Blade + Vanilla JavaScript (Frontend)**, with support for **Android WebView deployment**.



## 📌 Project Overview

The **Police Report Management System (PRMS)** allows authorized police officials to:

* Enter and manage political program reports
* Monitor activities by thana, party, program type, and parliamentary seat
* Maintain accountability through login activity tracking
* Enforce strict role-based access control

This system is designed for **government and law-enforcement environments**, focusing on **data integrity, auditability, and simplicity**.



## 🧱 Technology Stack

| Layer          | Technology                           |
| -- |  |
| Backend        | Laravel                              |
| Frontend       | Blade Templates + Vanilla JavaScript |
| Database       | MySQL                                |
| Authentication | Laravel Auth                         |
| Mobile App     | Android WebView (Capacitor/Cordova)  |
| Charts         | Chart.js                             |
| Styling        | Bootstrap / Custom CSS               |



## 👥 User Roles

| Role            | Description                                    |
|  | - |
| **Super Admin** | Full system control                            |
| **Data Viewer** | View-only access (SP, OC, UNO)                 |
| **Data Entry**  | Create reports (Inspector, SI, ASI, Constable) |



## 🗂️ Core Modules

### 🔐 User Management

* Role-based access
* Designation-based identity
* Multi-thana assignment
* Account activation/deactivation

### 🧾 Report Management

* Political program reporting
* Status tracking (Done / Ongoing / Upcoming)
* Attendee count (tentative & final)
* Linked to thana, party, seat, and program type

### 📊 Dashboard

* Program statistics
* Reports by status, party, and thana
* Visual summaries using charts

### 🧠 Master Data

* Roles
* Designations
* Thanas
* Upazillas
* Parliamentary Seats
* Political Parties
* Program Types

### 🕵️ Login Activity Tracking

* IP address
* Device info
* User agent
* Timestamp



## 🗄️ Database Schema (Summary)

### Main Tables

* `users`
* `roles`
* `designations`
* `thanas`
* `upazillas`
* `parliament_seats`
* `political_parties`
* `program_types`
* `reports`
* `user_thana` (pivot)
* `login_activities`

All tables use **foreign key constraints** to maintain data integrity.



## 🔗 Relationships (High Level)

* A **User** belongs to a **Role** and a **Designation**
* A **User** can be assigned to multiple **Thanas**
* A **Report** belongs to:

  * Thana
  * Political Party
  * Parliamentary Seat
  * Program Type
* A **User** can have many **Login Activities**



## 🚀 Installation & Setup

### 1️⃣ Clone Repository

```bash
git clone https://github.com/your-org/prms.git
cd prms
```

### 2️⃣ Install Dependencies

```bash
composer install
npm install
```

### 3️⃣ Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Configure database credentials in `.env`.

### 4️⃣ Database Migration & Seeding

```bash
php artisan migrate --seed
```

This will create:

* Default roles
* Designations
* Master data
* Super Admin user

### 5️⃣ Run Application

```bash
php artisan serve
```

Access via:
`http://127.0.0.1:8000`



## 🔑 Default Admin Login

```
Email: admin@prms.gov
Password: password
```

⚠️ Change this password immediately in production.



## 🔐 Security Features

* CSRF Protection
* Password hashing
* Role-based route protection
* Foreign key enforcement
* Login activity tracking
* Account activation control



## 📱 Android WebView Support

The system can be packaged as an Android app using **WebView**.

### Features

* Secure domain loading
* No external navigation
* Centralized server control
* No offline sync (intentional)

Recommended tools:

* Capacitor
* Cordova



## 📁 Project Structure (Simplified)

```
app/
 ├── Models/
 ├── Http/Controllers/
 ├── Http/Middleware/
 └── Helpers/

database/
 ├── migrations/
 ├── seeders/
 └── factories/

resources/
 ├── views/
 └── js/
```



## 🧪 Testing

* Manual testing recommended for workflows
* Database seeders provide realistic test data
* Validation enforced at controller and database level



## 📜 License

This project is intended for **government / institutional use**.
Distribution or modification should follow organizational policies.



## 🤝 Contribution Guidelines

* Follow Laravel coding standards
* Use meaningful commit messages
* Never commit `.env` files
* All schema changes must be done via migrations



## 📞 Support

For technical support or enhancements, contact the system administrator or development team.



## ✅ Status

* **Project Status:** Active Development
* **Version:** 1.0
* **Deployment:** Web + Android WebView
