# Deploy to Render.com Guide

Render.com connects directly to GitHub - just push your code and Render handles the rest!

---

## Step 1: Push to GitHub

1. **Create a new repository on GitHub** (e.g., `track-app`)

2. **Initialize git and push:**
   ```bash
   cd D:\.project\track\track-app
   git init
   git add .
   git commit -m "Initial commit"
   git branch -M main
   git remote add origin https://github.com/YOUR_USERNAME/track-app.git
   git push -u origin main
   ```

---

## Step 2: Create Database on Render

1. Go to https://dashboard.render.com
2. Click **New** → **PostgreSQL**
3. Settings:
   - Name: `track-app-db`
   - Database: `track_app`
   - User: `track_user`
   - Region: Singapore (closest to Indonesia)
   - Plan: Free
4. Click **Create Database**
5. **Save the connection details** (Internal Database URL)

---

## Step 3: Create Web Service

1. Click **New** → **Web Service**
2. **Connect your GitHub** and select `track-app` repository
3. Settings:

   | Field | Value |
   |-------|-------|
   | Name | track-app |
   | Region | Singapore |
   | Branch | main |
   | Runtime | PHP 8.2+ |
   | Build Command | `composer install --no-dev --optimize-autoloader && php artisan key:generate --force && php artisan storage:link && php artisan config:cache && php artisan route:cache && php artisan view:cache` |
   | Start Command | `php artisan serve --host=0.0.0.0 --port=10000` |

4. **Environment Variables** (click Advanced → Add Environment Variable):

   | Key | Value |
   |-----|-------|
   | APP_ENV | production |
   | APP_DEBUG | false |
   | APP_KEY | (generate with: `php artisan key:generate`) |
   | APP_URL | (your Render URL, e.g., `https://track-app.onrender.com`) |
   | APP_TIMEZONE | Asia/Jakarta |
   | LOG_CHANNEL | errorlog |
   | DB_CONNECTION | pgsql |
   | DB_HOST | (from PostgreSQL dashboard) |
   | DB_PORT | 5432 |
   | DB_DATABASE | track_app |
   | DB_USERNAME | (from PostgreSQL dashboard) |
   | DB_PASSWORD | (from PostgreSQL dashboard) |

5. **Disk Storage** (click Advanced → Add Disk):
   - Name: `storage`
   - Mount Path: `/opt/render/project/storage`
   - Size: 1 GB

6. Click **Create Web Service**

---

## Step 4: Run Migration

After deployment, open your service's **SSH** tab and run:

```bash
php artisan migrate --force
```

---

## Step 5: Create Admin User

In the SSH terminal:

```bash
php artisan tinker --execute="User::create(['name'=>'Admin','email'=>'admin@yourdomain.com','password'=>bcrypt('yourpassword')])"
```

---

## Step 6: Access Your App

Your app will be available at: `https://track-app.onrender.com`

---

## Automatic Deployments

Every time you push to GitHub main branch, Render automatically:
1. Pulls latest code
2. Runs build commands
3. Restarts the service

---

## Troubleshooting

### Build Failed
- Check GitHub has all required files
- Verify `composer.json` is committed

### Migration Issues
- Use SSH tab to run `php artisan migrate:status`
- Check database connection in environment variables

### File Upload Not Working
- Ensure Disk storage is configured
- Mount path: `/opt/render/project/storage`

### 500 Error
- Check logs in Render dashboard
- Verify `APP_KEY` is set correctly
- Ensure `DB_CONNECTION=pgsql` for PostgreSQL

---

## Free Tier Limits

| Resource | Free Tier |
|----------|-----------|
| Web Service | 750 hours/month |
| PostgreSQL | 90 days (then sleeps) |
| Disk Storage | 1 GB |

⚠️ **Note:** Free database sleeps after inactivity. First request may take 30+ seconds to wake up.
