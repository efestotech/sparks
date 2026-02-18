Installation and Configuration
==============================

Follow these steps to set up your SPARKS installation.

1. File Upload
--------------

Copy all the files from the project folder to the main directory of your web server (e.g., `public_html`, `www`, or a dedicated subfolder).

2. The Setup Wizard (Recommended)
---------------------------------

SPARKS comes with an intuitive setup wizard to automate the configuration. 

1. Navigate to: `http://your-domain.com/setup.php`
2. **Base URL**: Enter your system's public address (e.g., `http://sparks.it/`).
3. **Database Connection**: Provide your MySQL/MariaDB credentials (Hostname, Name, Username, Password).
4. **Administrator Account**: Create your first Admin account.
5. **System Security**: A Secret Key is automatically generated for worker execution.
6. Click **Install and Initialize**.

The system will:
* Test the database connection.
* Generate a secure encryption key for SMTP passwords.
* Create the `.env` file automatically.
* Initialize the database schema and default settings.
* **Self-destruct**: The `setup.php` script is automatically deleted for security once finished.

3. Manual Configuration (Alternative)
-------------------------------------

If you prefer manual setup, rename the `.env.example` file to `.env` in the project root and configure the following parameters:

.. code-block:: ini

   # Environment (development/production)
   CI_ENVIRONMENT = production

   # Base URL
   app.baseURL = 'http://your-domain.com/'

   # Database Configuration
   database.default.hostname = localhost
   database.default.database = sparks
   database.default.username = db_user
   database.default.password = db_password
   database.default.DBDriver = MySQLi

   # Worker Key
   worker.secret_key = AVeryLongAndSecureKey

4. Cron Job Configuration (The Worker)
------------------------------------

To ensure emails are sent automatically, you must set up a Cron Job on your server that calls the `worker.php` file periodically.

Example (Execution every minute):

.. code-block:: bash

   * * * * * curl -s "http://your-domain.com/worker.php?key=YourSecretKey" > /dev/null 2>&1

Alternatively, you can trigger the worker manually from the Admin Dashboard.

5. Accessing the Admin Area
---------------------------

After the setup is complete, access the administrative area at:

`http://your-domain.com/admin`

The default credentials are those you provided during the Setup Wizard.
