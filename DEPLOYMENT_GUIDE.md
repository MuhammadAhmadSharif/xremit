# XRemit Pro - Render Deployment Guide

This guide will help you deploy the XRemit Pro Laravel application to Render using Docker.

## Prerequisites

- A Render account (free tier available)
- Your Laravel application code
- AlwaysData MySQL database (already configured)

## Deployment Steps

### 1. Database Setup (Already Completed)

✅ **Database Connection Verified**
- Host: `mysql-xremit.alwaysdata.net`
- Database: `xremit_db`
- Username: `xremit`
- Password: `5@XmR5vD7TGCaRK`

✅ **Migrations and Seeders Executed**
- All database tables created
- Sample data populated

### 2. Prepare Your Repository

1. **Commit all changes to your Git repository:**
   ```bash
   git add .
   git commit -m "Prepare for Render deployment"
   git push origin main
   ```

2. **Ensure these files are in your repository:**
   - `Dockerfile`
   - `docker/nginx.conf`
   - `docker/supervisord.conf`
   - `.dockerignore`
   - `render.yaml` (optional, for automatic deployment)

### 3. Deploy to Render

#### Option A: Using Render Dashboard (Recommended)

1. **Go to [Render Dashboard](https://dashboard.render.com/)**
2. **Click "New +" → "Web Service"**
3. **Connect your Git repository**
4. **Configure the service:**
   - **Name:** `xremit-web`
   - **Environment:** `Docker`
   - **Region:** Choose closest to your users
   - **Branch:** `main`
   - **Build Command:** `docker build -t xremit-web .`
   - **Start Command:** `docker run -p $PORT:80 xremit-web`

5. **Add Environment Variables:**
   ```
   APP_NAME=XRemit Pro
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-app-name.onrender.com
   
   DB_CONNECTION=mysql
   DB_HOST=mysql-xremit.alwaysdata.net
   DB_PORT=3306
   DB_DATABASE=xremit_db
   DB_USERNAME=xremit
   DB_PASSWORD=5@XmR5vD7TGCaRK
   
   LOG_CHANNEL=stack
   CACHE_DRIVER=file
   SESSION_DRIVER=file
   QUEUE_CONNECTION=sync
   ```

6. **Click "Create Web Service"**

#### Option B: Using render.yaml (Blue-Green Deployment)

1. **Push your code with render.yaml**
2. **Render will automatically detect and deploy**

### 4. Post-Deployment Steps

1. **Wait for the build to complete** (usually 5-10 minutes)
2. **Check the logs** for any errors
3. **Test your application** at the provided URL
4. **Set up custom domain** (optional)

### 5. Environment Variables Reference

| Variable | Value | Description |
|----------|-------|-------------|
| `APP_ENV` | `production` | Application environment |
| `APP_DEBUG` | `false` | Disable debug mode |
| `APP_URL` | Your Render URL | Application URL |
| `DB_HOST` | `mysql-xremit.alwaysdata.net` | Database host |
| `DB_DATABASE` | `xremit_db` | Database name |
| `DB_USERNAME` | `xremit` | Database username |
| `DB_PASSWORD` | `5@XmR5vD7TGCaRK` | Database password |

### 6. Troubleshooting

#### Common Issues:

1. **Build Fails:**
   - Check Dockerfile syntax
   - Ensure all required files are present
   - Check composer dependencies

2. **Application Errors:**
   - Check application logs
   - Verify environment variables
   - Ensure database connection

3. **Performance Issues:**
   - Enable caching: `php artisan config:cache`
   - Optimize autoloader: `composer install --optimize-autoloader --no-dev`

### 7. Monitoring and Maintenance

1. **Set up alerts** for downtime
2. **Monitor logs** regularly
3. **Update dependencies** periodically
4. **Backup database** regularly

### 8. Scaling (Optional)

- **Upgrade plan** for more resources
- **Add Redis** for caching
- **Set up CDN** for static assets
- **Configure load balancing**

## Support

If you encounter issues:
1. Check Render logs
2. Verify environment variables
3. Test database connection
4. Check Laravel logs: `storage/logs/laravel.log`

## Security Notes

- ✅ Database credentials are secure
- ✅ HTTPS is enabled by default
- ✅ Security headers configured
- ✅ Sensitive files are protected
- ⚠️ Update mail configuration for production
- ⚠️ Configure proper session security

---

**Deployment Status:** ✅ Ready for Render deployment
**Last Updated:** $(date)
**Version:** XRemit Pro v2.6.0
