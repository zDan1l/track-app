# Docker Deployment Guide

## Files Created

| File | Purpose |
|------|---------|
| `Dockerfile` | Builds the Laravel app container |
| `docker-compose.yml` | Local development & orchestration |
| `.dockerignore` | Excludes unnecessary files from image |
| `render.yaml` | Render.com Docker deployment config |

---

## Option 1: Deploy to Render.com with Docker

### Step 1: Push to GitHub

```bash
git init
git add .
git commit -m "Add Docker support"
git remote add origin https://github.com/YOUR_USERNAME/track-app.git
git push -u origin main
```

### Step 2: Deploy on Render

1. Go to https://dashboard.render.com
2. Click **New** → **Web Service**
3. Connect GitHub → Select `track-app` repo
4. Settings:
   - **Environment**: Docker
   - **Plan**: Free
   - **Region**: Singapore

5. Render automatically detects `Dockerfile` and `render.yaml`
6. Click **Create Web Service**

### Step 3: Run Migration

After deployment, open **SSH** tab:
```bash
php artisan migrate --force
```

### Step 4: Create Admin

```bash
php artisan tinker --execute="User::create(['name'=>'Admin','email'=>'admin@yourdomain.com','password'=>bcrypt('yourpassword')])"
```

---

## Option 2: Local Development with Docker

### Start the Application

```bash
docker-compose up -d
```

### Run Migration

```bash
docker-compose exec app php artisan migrate
```

### Create Admin User

```bash
docker-compose exec app php artisan tinker --execute="User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>bcrypt('password')])"
```

### Access the App

- **URL**: http://localhost:8000
- **Default Login**: admin@example.com / password

### Useful Commands

```bash
# View logs
docker-compose logs -f

# Stop containers
docker-compose down

# Rebuild after code changes
docker-compose up -d --build

# Run artisan command
docker-compose exec app php artisan <command>

# Access container shell
docker-compose exec app bash

# Clear cache
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

---

## Option 3: Deploy to Any Docker Host

### Build Image

```bash
docker build -t track-app .
```

### Run Container

```bash
docker run -d \
  --name track-app \
  -p 80:80 \
  -e DB_CONNECTION=pgsql \
  -e DB_HOST=your-db-host \
  -e DB_PORT=5432 \
  -e DB_DATABASE=track_app \
  -e DB_USERNAME=track_user \
  -e DB_PASSWORD=your_password \
  -e APP_ENV=production \
  -e APP_DEBUG=false \
  -v track-app-storage:/var/www/html/storage \
  track-app
```

### Run Migration

```bash
docker exec track-app php artisan migrate --force
```

---

## Dockerfile Details

The Dockerfile:
- Uses **PHP 8.3 + Apache**
- Installs required extensions (pdo, mbstring, gd, zip, etc.)
- Installs Composer dependencies
- Sets proper permissions
- Caches configs for performance

---

## Environment Variables

| Variable | Default | Description |
|----------|---------|-------------|
| APP_ENV | production | Application environment |
| APP_DEBUG | false | Debug mode |
| APP_URL | - | Application URL |
| APP_TIMEZONE | Asia/Jakarta | Timezone |
| DB_CONNECTION | pgsql | Database type |
| DB_HOST | db | Database host |
| DB_PORT | 5432 | Database port |
| DB_DATABASE | track_app | Database name |
| DB_USERNAME | track_user | Database user |
| DB_PASSWORD | secret | Database password |

---

## Troubleshooting

### Container won't start
```bash
docker-compose logs
```

### Database connection error
- Ensure `db` container is running: `docker-compose ps`
- Check environment variables in docker-compose.yml

### Permission issues
```bash
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
```

### View not updating
```bash
docker-compose exec app php artisan view:clear
docker-compose exec app php artisan config:clear
```
