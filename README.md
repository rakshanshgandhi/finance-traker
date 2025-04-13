# 💰 Finance Tracker App

A secure and user-friendly finance tracking application built using **PHP Symfony**.  
Users can register, log in, and perform full **CRUD operations** on their transactions, with data scoped to each individual user.

---

## 🛠️ Tech Stack

- **Backend:** PHP 8.x, Symfony 6.x  
- **Frontend:** Twig, HTML, CSS, Vanilla JavaScript  
- **Database:** MySQL / SQLite (configurable via `.env`)  
- **Authentication:** Symfony Security (session-based)  
- **ORM:** Doctrine

---

## 🚀 Features

✅ User Registration & Login  
✅ Session-based Authentication  
✅ Create / Read / Update / Delete (CRUD) for Transactions  
✅ User-specific data: each user sees only their own transactions  
✅ Clean and responsive UI

---

## 📦 Installation Steps

### ✅ Requirements
- PHP 8.1+
- Composer
- MySQL (or SQLite)
- Symfony CLI *(optional but recommended)*

### 📥 1. Clone the Repository
```bash
git clone https://github.com/rakshanshgandhi/finance-traker.git
cd finance-traker
```

### 📦 2. Install Dependencies
```bash
composer install
```

### 🔧 3. Configure the Environment
Create a `.env.local` file by copying the `.env` file:
```bash
cp .env .env.local
```

Edit `.env.local` and configure your database connection:
```
# For MySQL
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/finance_tracker?serverVersion=8.0"

# Or for SQLite
# DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
```

### 🗄️ 4. Create the Database
```bash
# Create the database
php bin/console doctrine:database:create

# Run migrations
php bin/console doctrine:migrations:migrate
```

### 🌱 5. (Optional) Load Fixtures
```bash
php bin/console doctrine:fixtures:load
```

### 🚀 6. Start the Development Server
Using Symfony CLI (recommended):
```bash
symfony server:start
```

Or with PHP's built-in server:
```bash
php -S localhost:8000 -t public/
```

### 🌐 7. Access the Application
Open your browser and navigate to:
```
http://localhost:8000
```

### 🔐 8. Default Test User (if fixtures were loaded)
- Email: user@example.com
- Password: password

### 🧪 9. Running Tests
```bash
php bin/phpunit
```

## 🛠️ Troubleshooting
- If you encounter permission issues with cache or log directories:
  ```bash
  chmod -R 777 var/cache var/log
  ```
- To clear the cache:
  ```bash
  php bin/console cache:clear
  ```
- If database migrations fail, try:
  ```bash
  php bin/console doctrine:schema:update --force
  ```
