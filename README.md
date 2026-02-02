# ⚡ SPARKS: The Forge of Hephaestus

**SPARKS** (formerly Hermes) is a professional-grade Email Queue System (EQS) built on CodeIgniter 4. Inspired by the divine forge of Hephaestus, it provides a robust, high-performance solution for managing email delivery through intelligent SMTP rotation and queueing.

![The Forge](https://www.efestotech.it/wp-content/uploads/2026/02/Logo-Efesto-scaled.jpg)

## 🏛️ Project Identity
*   **Designer**: Efesto Tech di Marco Spinelli
*   **Theme**: Ancient Greek Mythology / Premium Forge Aesthetic
*   **Architecture**: CodeIgniter 4 + PHPMailer + MySQL

## 🔥 Key Features
*   **Intelligent SMTP Rotation**: Automatically cycles through multiple "Anvils" (SMTP servers) to bypass provider limits and ensure high deliverability.
*   **High-Volume Queueing**: Manages thousands of messages with priority support and automatic retries.
*   **Aegis Security**: AES-256 encryption for SMTP credentials and secure API-key based access.
*   **Multilingual Citadel**: Full native support for **Italian** and **English** across the entire admin interface and public home.
*   **Performance Monitoring**: Real-time dashboard to track "Embers" (Queued), "Ignited" (Sent), and "Extinguished" (Failed) sparks.

## 🛠️ Quick Installation

### The Setup Wizard (Recommended)
1.  Upload all files to your server.
2.  Navigate to `http://your-domain.com/setup.php`.
3.  Follow the **Ancient Scroll** instructions to configure your database, environment, and initial Admin (Guardian) account.
4.  The wizard will automatically initialize the forge and self-destruct for security.

### Server Requirements
*   PHP 8.2+
*   MySQL 5.7+ / MariaDB 10.3+
*   Extensions: `intl`, `mbstring`, `mysqli`, `openssl`, `curl`.

## 📖 Complete Documentation
We provide a comprehensive, professional Sphinx-based manual in both English and Italian.

*   **Location**: `docs/build/html/index.html`
*   **Contents**: Detailed Requirements, Advanced Installation, Feature Guide, and full REST API Specifications.

## ⚡ The Mantice (Worker)
To process the queue automatically, set up a cron job:
```bash
* * * * * curl -s "http://your-domain.com/worker.php?key=YOUR_SECRET_SIGIL" > /dev/null 2>&1
```

---
*Forged with pride in the fires of Efesto Tech.*
