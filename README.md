

## Project Setup

### Step 1: Create Environment File
```bash
cp .env.example .env
```

### Step 2: Update Composer
```bash
composer update
```

### Step 3: Generate Application Key
```bash
php artisan key:generate
```

### Step 4: Run Migrations
```bash
php artisan migrate
```
If prompted to create the database, click "Yes".

### Step 5: Seed the Database
```bash
php artisan db:seed
```

### Step 6: Admin Credentials
Use the following credentials to log in as an admin:
- **Email**: admin@gmail.com
- **Password**: 123456

### Step 7: Run the Server
```bash
php artisan serve
```
Now you can access the API documentation at [http://127.0.0.1:8000/api/documentation](http://127.0.0.1:8000/api/documentation).

