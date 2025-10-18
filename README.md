# 🛍️ Papelería Andy — Online Store

**Papelería Andy** is a local stationery shop offering school supplies, office materials, and gifts.
This project is an **online store** that allows local customers in **Uriangato, Gto. (Mexico)** to place orders for **home delivery** with **cash on delivery** payment.

---

## 🚀 Main Technologies

**Backend**

* [Laravel 12](https://laravel.com/)
* [Filament Admin Panel](https://filamentphp.com/)
* [Pest](https://pestphp.com/) — testing
* [Spatie Packages](https://spatie.be/open-source) — SEO and sitemap generation

**Frontend**

* [Vue 3](https://vuejs.org/)
* [Inertia.js](https://inertiajs.com/)
* [Tailwind CSS](https://tailwindcss.com/)
* [SweetAlert2](https://sweetalert2.github.io/)
* [Vite](https://vitejs.dev/)

**DevOps / CI-CD**

* [GitHub Actions](https://github.com/features/actions) — automated deployment via FTP
* Hosting on **Neubox (cPanel)**

---

## 📦 Requirements

* PHP >= 8.2
* Composer
* Node.js >= 18
* MySQL / MariaDB
* PHP Extensions:

  * `fileinfo`, `pdo`, `mbstring`, `openssl`, `xml`, `ctype`, `json`, `tokenizer`

---

## 🧭 Local Installation

Follow these steps to set up the project locally.

### 1️⃣ Clone the repository

```bash
git clone https://github.com/<your-username>/papeleria-andy.git
cd papeleria-andy
```

### 2️⃣ Install dependencies

```bash
composer install
npm install
```

### 3️⃣ Set up the environment

Copy the `.env` example file and edit the configuration values:

```bash
cp .env.example .env
```

Update the following:

```env
APP_NAME="Papelería Andy"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=papeleria_andy
DB_USERNAME=root
DB_PASSWORD=

# Additional configuration
FILESYSTEM_DISK=public
PRODUCTS_PATH="/path/to/your/project/public/products"
```

### 4️⃣ Generate the application key

```bash
php artisan key:generate
```

### 5️⃣ Run migrations and seeders

```bash
php artisan migrate --seed
```

### 6️⃣ Build the frontend

For development:

```bash
npm run dev
```

For production:

```bash
npm run build
```

### 7️⃣ Start the local server

```bash
php artisan serve
```

Access at [http://localhost:8000](http://localhost:8000)

---

## ⚙️ Admin Panel

The project includes a **Filament Admin Panel** to manage products, categories, coupons, and general settings.

Default admin route:

```
/admin
```

To create an admin user:

```bash
php artisan tinker
>>> \App\Models\User::factory()->create(['email' => 'admin@example.com', 'is_admin' => true]);
```

---

## 🌐 SEO & Optimization

This project includes:

* Dynamic **title/meta tags** (Vue SEO helper)
* **Automatic sitemap** generation
* **robots.txt** configuration
* **Open Graph** and **Twitter Card** tags

---

## 🧰 Useful Commands

| Command                            | Description                    |
| ---------------------------------- | ------------------------------ |
| `php artisan migrate:fresh --seed` | Reset and seed the database    |
| `php artisan optimize:clear`       | Clear cached files             |
| `npm run lint`                     | Check for JS/Vue syntax issues |
| `php artisan serve`                | Start local Laravel server     |

---

## 🔄 GitHub Actions Deployment

The repository includes a **CI/CD pipeline** (`.github/workflows/deploy.yml`) that:

1. Installs dependencies
2. Builds the frontend (`npm run build`)
3. Publishes Livewire assets (`php artisan vendor:publish --tag=livewire:assets`)
4. Uploads all required files to the server via FTP using `SamKirkland/FTP-Deploy-Action`

---

## 📁 Project Structure

```
papeleria-andy/
├── app/
├── bootstrap/
├── config/
├── public/
│   ├── build/
│   ├── products/
├── resources/
│   ├── js/
│   │   ├── Pages/
│   │   ├── Components/
│   │   ├── Composables/
│   └── views/
├── routes/
│   └── web.php
└── .github/
    └── workflows/deploy.yml
```

---

## 👨‍💻 Author

**Néstor Ruiz**
Independent Web Developer
📧 [n.raulrg@gmail.com](mailto:n.raulrg@gmail.com)

---

## 📝 License

This is a **private project** owned by **Papelería Andy**.
Reproduction or distribution without explicit permission is not allowed.


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
