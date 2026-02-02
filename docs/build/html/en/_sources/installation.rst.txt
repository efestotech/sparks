Installation and Configuration
==============================

Follow these steps to erect your SPARKS Forge.

1. File Upload
--------------

Copy all the files from the project folder to the main directory of your web server (e.g., `public_html`, `www`, or a dedicated subfolder).

2. The Setup Wizard (Recommended)
---------------------------------

SPARKS comes with an intuitive setup wizard to automate the configuration. 

1. Navigate to: `http://your-domain.com/setup.php`
2. **The Citadel Boundary**: Enter your Base URL (e.g., `http://sparks.it/`).
3. **The Vault of Hephaestus**: Provide your MySQL/MariaDB database credentials (Hostname, Name, Username, Password).
4. **The Master Blacksmith**: Create your first Guardian (Admin) account.
5. **Forge Protection**: A Worker Sigil (Secret Key) is automatically generated. Keep this safe!
6. Click **Strike the Anvil & Begin**. 

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
   database.default.username = master_blacksmith
   database.default.password = secret_sigil
   database.default.DBDriver = MySQLi

   # Worker Key
   worker.secret_key = AVeryLongAndSecureKey

4. Cron Job Configuration (The Automatic Bellows)
--------------------------------------------------

To ensure emails are sent automatically, you must set up a Cron Job on your server that calls the `worker.php` file periodically.

Example (Execution every minute):

.. code-block:: bash

   * * * * * curl -s "http://your-domain.com/worker.php?key=YourSecretKey" > /dev/null 2>&1

Alternatively, you can ignite the bellows manually from the Admin Dashboard.

5. Accessing the Citadel
--------------------------

After the setup is complete, access the administrative area at:

`http://your-domain.com/admin`

The default credentials are those you provided during the Setup Wizard.
