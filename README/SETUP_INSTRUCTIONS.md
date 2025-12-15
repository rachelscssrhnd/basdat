# E-Clinic Lab Application Setup Instructions

## 🚀 Quick Start

### 1. Database Setup
```bash
# Run migrations to create database tables
php artisan migrate

# Seed the database with sample data
php artisan db:seed
```

### 2. Start the Application
```bash
# Start the Laravel development server
php artisan serve
```

The application will be available at: `http://127.0.0.1:8000`

## 🔑 Default Login Credentials

### Admin Access
- **Username**: admin
- **Password**: admin123
- **Role**: Administrator

### User Access  
- **Username**: user
- **Password**: user123
- **Role**: User

## 📱 Application Features

### Public Features (No Login Required)
- ✅ **Homepage** - Browse featured lab tests
- ✅ **Lab Test Catalog** - View all available tests with pricing
- ✅ **Booking System** - Complete booking workflow
- ✅ **Order History** - View booking status and details
- ✅ **Test Results** - View and download test results

### Admin Features (Login Required)
- ✅ **Admin Dashboard** - Overview of bookings and statistics
- ✅ **Booking Management** - View, edit, and delete bookings
- ✅ **Test Management** - Add, edit, and delete lab tests
- ✅ **Real-time Data** - Live updates with AJAX

## 🗄️ Database Structure

The application uses the following main entities:
- **Users & Roles** - Authentication and authorization
- **Patients** - Customer information
- **Bookings** - Appointment scheduling
- **Lab Tests** - Available test types and pricing
- **Test Results** - Laboratory results and parameters
- **Payments** - Transaction tracking
- **Branches** - Clinic locations

## 🛠️ Technical Details

### Controllers Created
- `HomeController` - Homepage functionality
- `LabTestController` - Lab test catalog
- `BookingController` - Booking management
- `MyOrderController` - Order history
- `ResultController` - Test results
- `AdminController` - Admin dashboard
- `AuthController` - Authentication (existing)

### Key Features
- ✅ **RESTful API** design
- ✅ **Eloquent ORM** integration
- ✅ **Form Validation** with error handling
- ✅ **CSRF Protection** on all forms
- ✅ **Responsive Design** with Tailwind CSS
- ✅ **AJAX Integration** for admin features
- ✅ **Database Relationships** properly mapped

## 🎯 User Workflow

1. **Browse Tests** → Visit lab test catalog
2. **Book Appointment** → Fill booking form with patient details
3. **View Orders** → Check booking status and history
4. **View Results** → Access test results when available

## 🔧 Admin Workflow

1. **Login** → Access admin panel with admin credentials
2. **Manage Bookings** → View and update booking status
3. **Manage Tests** → Add new lab tests and parameters
4. **View Statistics** → Monitor application usage

## 📋 Sample Data Included

The seeder creates:
- 2 user roles (Admin, User)
- 2 user accounts (admin, user)
- 1 clinic branch
- 1 sample patient
- 3 lab tests with parameters
- 1 sample booking with payment
- 1 sample test result

## 🚨 Troubleshooting

### Common Issues
1. **Controller not found** - Make sure all controllers are in the correct namespace
2. **Database connection** - Check your `.env` file database configuration
3. **Permission errors** - Ensure proper file permissions on storage and bootstrap/cache directories

### Reset Database
```bash
# Drop all tables and re-run migrations
php artisan migrate:fresh --seed
```

## 📞 Support

For technical support or questions about the application, please refer to the Laravel documentation or contact the development team.

---
**E-Clinic Lab Application** - Modern diagnostic center management system
