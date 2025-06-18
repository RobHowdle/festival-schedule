# Festival Schedule

A Laravel 12 + Vue 3 application for managing festival schedules.

---

## 🚀 Getting Started

### 1. **Clone the Repository**

```sh
git clone https://github.com/your-username/festival-schedule.git
cd festival-schedule
```

---

### 2. **Copy and Configure Environment File**

```sh
cp .env.example .env
```
- Edit `.env` to set your app key, database credentials, and ports as needed.

---

### 3. **Install Dependencies**

```sh
composer install
npm install
```

---

### 4. **Build Frontend Assets**

```sh
npm run build
```
or for development:
```sh
npm run dev
```

---

### 5. **Docker Setup (Recommended)**

#### **Start Docker Containers**

```sh
docker-compose up -d
```
or, if using Laravel Sail:
```sh
./vendor/bin/sail up -d
```

#### **Run Migrations**

```sh
docker-compose exec laravel.test php artisan migrate
```
or with Sail:
```sh
./vendor/bin/sail artisan migrate
```

---

### 6. **Access the Application**

- Visit [http://localhost](http://localhost) (or your configured `APP_PORT`).

---

## 🐳 Docker Notes

- The default setup uses PHP 8.3 and MySQL 8.0.
- Update `.env` for database credentials to match `docker-compose.yml`.
- Vite dev server runs on port 5173 by default.

---

## 🛠️ Useful Commands

- **Stop containers:**  
  `docker-compose down`  
  or  
  `./vendor/bin/sail down`

- **Run artisan commands:**  
  `docker-compose exec laravel.test php artisan <command>`  
  or  
  `./vendor/bin/sail artisan <command>`

---

## 📦 Project Structure

- `resources/js/components` — Vue components
- `resources/views` — Blade templates
- `public` — Static assets (logo, favicon, etc.)

---

## 🤝 Contributing

Pull requests are welcome!

---

## 📄 License