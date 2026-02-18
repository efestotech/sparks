System Features
===============

SPARKS is a professional-grade email sending system designed for high performance and reliability.

Multiple SMTP Management
------------------------

* **Intelligent Rotation**: The system rotates through different SMTP servers to maximize delivery and respect limits.
* **Hourly and Daily Limits**: Each SMTP server has its own configurable limits to avoid bans and overloads.
* **Connection Pooling**: The system keeps connections active to speed up the sending of large quantities of sparks.
* **Personal Sender Identity**: Each server can have its own sender name and email.

Email Queue Management
----------------------

* **Priority Management**: Sends critical messages before standard notifications.
* **Scheduled Sending**: Ability to specify a future date and time for automatic delivery.
* **Automatic Retries**: If a send fails, the system retries according to the configuration (default: 3 retries).
* **Detailed Logging**: Every event is recorded with the original SMTP error codes for rapid debugging.

Administrative Interface
------------------------

* **Professional Design**: A clean and intuitive interface, elegant and modern.
* **Native Multilingual**: Full support for Italian and English, with automatic language detection.
* **Real-time Dashboard**: View the status of embers and anvils at a glance.
* **User Management**: Manage different administrative accounts with individual API keys.

Security and Control
--------------------

* **Password Encryption**: SMTP passwords are encrypted in the database using AES-256.
* **Worker Security**: Worker execution is protected by a configurable Secret Key.
* **API Isolation**: External access is only allowed through regenerable API keys.
迫于
