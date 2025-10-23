# A dynamic role and permission system designed to reduce development time.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/almas/laravel-permission.svg?style=flat-square)](https://packagist.org/packages/almas/laravel-permission)
[![Total Downloads](https://img.shields.io/packagist/dt/almas/laravel-permission.svg?style=flat-square)](https://packagist.org/packages/almas/laravel-permission)
<!--delete-->

## Installation
You can install the package via Composer:
```bash
composer require almas/laravel-permission
```

Set Super Admin
Set the Super Admin email in your .env file:
```php
SUPER_ADMIN_EMAIL=superadmin@gmail.com
```
- If not defined, the default is superadmin@gmail.com.
- A random password is automatically generated for the Super Admin.
- You can reset the password via the "Forgot Password" option

After installation, run the following Artisan command to set up everything:
```bash
php artisan permission
```

This command will:
- Run necessary database migrations
- Seed default roles and permissions automatically

No additional setup is required — you're ready to go!

### Publish Configuration File
To publish the configuration file for this package, run the following command:
```bash
php artisan vendor:publish --tag=permission-config
```

---
## Super Admin Features

- Only the Super Admin can create and delete permissions.
- For every new permission added to the system, the Super Admin is automatically granted that permission.

---

## Route Registration
Add the following line to your `routes/web.php` file:
```php
use Illuminate\Support\Facades\Route;

Route::permission();
```
- By default, all routes will be protected using the auth middleware.
- This will automatically register all necessary permission management routes.

### Route Prefix Behavior
- When using Route::permission();, the base route will be:
```php
{your-app-url}/permissions
```

- You can customize the base route prefix by passing a parameter to the Route::permission() method:
```php
Route::permission('custom-prefix');
```

- This will change the base route to:
```php
{your-app-url}/custom-prefix
```

### Blade Helper to Get Base Permission URL

```php
@PermissionLink
```

---

## Usage

- Get All Permissions (as array):
```php
user_permission()
```

- Check a Single Permission: (eg. true/false):
```php
user_permission('show-users')
```

- Blade Directives:
```blade
@user_permission('show-users')
    <a>Show Users</a>
@end_user_permission
```

---
## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
