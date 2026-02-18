# ⚡ SPARKS: Professional Email Queue System

**SPARKS** (formerly Hermes) is a professional-grade Email Queue System (EQS) built on CodeIgniter 4. It provides a robust, high-performance solution for managing email delivery through intelligent SMTP rotation and queueing.

![SPARKS Control Panel](public/assets/img/olympus_bg.png)

## 🏛️ Project Identity
*   **Designer**: Efesto Tech di Marco Spinelli
*   **Theme**: Modern & Professional Admin Interface
*   **Architecture**: CodeIgniter 4 + PHPMailer + MySQL

## 🔥 Key Features
*   **Intelligent SMTP Rotation**: Automatically cycles through multiple SMTP servers to bypass provider limits and ensure high deliverability.
*   **High-Volume Queueing**: Manages thousands of messages with priority support and automatic retries.
*   **Secure Storage**: AES-256 encryption for SMTP credentials and secure API-key based access.
*   **Multilingual Support**: Full native support for **Italian** and **English** across the entire admin interface.
*   **Performance Monitoring**: Real-time dashboard to track **Queued**, **Sent**, and **Failed** emails.

## 🛠️ Quick Installation

### The Setup Wizard (Recommended)
1.  Upload all files to your server.
2.  Navigate to `http://your-domain.com/setup.php`.
3.  Follow the setup instructions to configure your database, environment, and initial Admin account.
4.  The wizard will automatically initialize the system and self-destruct for security.

### Server Requirements
*   PHP 8.2+
*   MySQL 5.7+ / MariaDB 10.3+
*   Extensions: `intl`, `mbstring`, `mysqli`, `openssl`, `curl`.

## 📖 Complete Documentation
We provide a comprehensive, professional manual in both English and Italian.

*   **Location**: `docs/build/html/index.html`
*   **Contents**: Detailed Requirements, Advanced Installation, Feature Guide, and full REST API Specifications.

## ⚡ The Worker
To process the queue automatically, set up a cron job:
```bash
* * * * * curl -s "http://your-domain.com/worker.php?key=YOUR_SECRET_KEY" > /dev/null 2>&1
```

---
*Developed with pride by Efesto Tech.*
