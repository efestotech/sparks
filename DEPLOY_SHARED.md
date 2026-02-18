# 🚀 Shared Hosting Deployment Guide: SPARKS

This document provides step-by-step instructions for deploying SPARKS on a shared hosting environment (e.g., SiteGround, Bluehost, Namecheap, Aruba, etc.).

## 1. Prepare the Files
1.  **Clean up**: Ensure your local `.env` is NOT uploaded or is renamed. The production environment will be configured via the Setup Wizard.
2.  **Zip the project**: Compress all files and folders in the project root into a single `.zip` file.
    *   *Include*: `app/`, `public/`, `writable/`, `vendor/`, `spark`, `composer.json`, `setup.php`.
    *   *Exclude*: `tests/`, `.git/`, `.env` (unless you want to do it manually).

## 2. Upload to Server
1.  Access your hosting File Manager or use FTP/SFTP.
2.  Upload the `.zip` file to your home directory or the folder where you want to host the app.
3.  **Extract** the archive.

## 3. Configure Public Access (Crucial)
Shared hosting usually points to `public_html`, but SPARKS starts in the `public/` folder. Choose **ONE** of these two methods:

### Method A: Use Root .htaccess (Recommended)
Create a file named `.htaccess` in your **root folder** (the one containing `app/`, `public/`, etc.) with the following content:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```
*(The `RewriteCond` prevents infinite redirection loops).*

### Method B: Move Public Contents
1.  Move everything INSIDE the `public/` folder to your `public_html` (or your domain root).
2.  Open the `index.php` you just moved and update the path to the system bootstrap:
    ```php
    $pathsPath = realpath(FCPATH . '../app/Config/Paths.php');
    ```
    Change it to (if your `app` folder is one level up):
    ```php
    $pathsPath = realpath(FCPATH . './app/Config/Paths.php');
    ```
    *(Note: Using the .htaccess method is generally cleaner and easier).*

## 4. Run the Setup Wizard
1.  Open your browser and navigate to: `http://your-domain.com/setup.php`
2.  Fill in the **Base URL** and **Database Connection** details.
3.  The wizard will create the database tables, generate the encryption keys, and create your Admin account.
4.  Once finished, the script will self-destruct for security.

## 5. Set Folder Permissions
Ensure the following directory is writable by the web server (usually CHMOD 755 or 775):
*   `writable/` (and all its subfolders: `cache`, `logs`, `session`, `uploads`).

## 6. Configure the Automatic Worker (Cron)
Shared hosting provides a "Cron Jobs" section in the control panel (cPanel/Plesk).
Add a new cron job to run every minute:

**Command**:
```bash
/usr/bin/curl -s "http://your-domain.com/worker.php?key=YOUR_SECRET_KEY" > /dev/null 2>&1
```
*(Replace `YOUR_SECRET_KEY` with the key generated during setup).*

## 7. PHP Version Check
Make sure your hosting is set to use **PHP 8.2 or higher**. Most panels allow you to select the version under "PHP Selector" or "MultiPHP Manager".

---

## 🛠️ Troubleshooting 500 Errors
If you experience a 500 error immediately after upload:

1.  **SymLinks Issue**: Open `public/.htaccess` and find `Options +FollowSymLinks`. Change it to `Options +SymLinksIfOwnerMatch`.
2.  **Redirection Loop**: Ensure your root `.htaccess` has the `RewriteCond %{REQUEST_URI} !^/public/` line.
3.  **Missing Logs**: check `writable/logs/` for PHP-related errors. If the folder is not writable, the app will crash before it can even log.
4.  **PHP Version**: Ensure you are using **PHP 8.2**. PHP 8.1 or lower might work but 8.2+ is recommended.
