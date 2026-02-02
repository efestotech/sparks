System Requirements
===================

To run the Forge of SPARKS, your environment must meet the following divine criteria:

Server Environment
------------------

* **PHP**: Version 8.1 or higher.
* **Database**: MySQL 5.7+ or MariaDB 10.3+.
* **Web Server**: Apache with `mod_rewrite` enabled or Nginx.
* **Composer**: For managing dependencies (optional for manual installation).

Required PHP Extensions
-----------------------

The system requires the following extensions to be enabled in your `php.ini`:

* `intl`: For localization and multi-language messages.
* `mbstring`: For safe multibyte text handling.
* `mysqli`: For database communication.
* `curl`: For external notifications (if used).
* `openssl`: For encrypting Secret Sigils (SMTP passwords).

Resource Limits
---------------

* **Memory**: At least 128MB of RAM dedicated to PHP.
* **Permissions**: The `writable/` folder must be writable by the web server.
