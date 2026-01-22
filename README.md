# 🗳️ সাতক্ষীরা-২ ভোটার তালিকা (Satkhira-2 Voter List)

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind">
  <img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

<p align="center">
  <strong>বাংলাদেশ নির্বাচন কমিশনের সাতক্ষীরা-২ আসনের ভোটার তথ্য ব্যবস্থাপনা সিস্টেম</strong><br>
  A comprehensive voter information management system for Satkhira-2 constituency, Bangladesh
</p>

---

## ✨ বৈশিষ্ট্যসমূহ (Features)

### 🌐 পাবলিক ওয়েবসাইট
- 🔍 **স্মার্ট সার্চ** - ভোটার আইডি, নাম, পিতা/মাতার নাম দিয়ে সার্চ
- 🔢 **ইংরেজি/বাংলা নম্বর সাপোর্ট** - স্বয়ংক্রিয় নম্বর রূপান্তর
- 📱 **রেসপন্সিভ ডিজাইন** - মোবাইল ও ডেস্কটপ ফ্রেন্ডলি
- 🎨 **প্রিমিয়াম UI/UX** - আধুনিক ও আকর্ষণীয় ডিজাইন
- 📢 **ব্রেকিং নিউজ** - স্ক্রলিং নিউজ টিকার
- 🖼️ **ডায়নামিক ব্যানার** - স্লাইডার ব্যানার সিস্টেম

### 🔐 অ্যাডমিন প্যানেল
- 📊 **ড্যাশবোর্ড** - পরিসংখ্যান ও দ্রুত অ্যাকশন
- 👥 **ভোটার ব্যবস্থাপনা** - সকল ভোটার দেখুন ও খুঁজুন
- 📤 **এক্সেল আপলোড** - বাল্ক ডেটা ইম্পোর্ট
- 🖼️ **ব্যানার ম্যানেজমেন্ট** - ব্যানার যোগ/সম্পাদনা/মুছুন
- 📰 **ব্রেকিং নিউজ** - নিউজ ম্যানেজমেন্ট
- 👤 **ইউজার ম্যানেজমেন্ট** - রোল ভিত্তিক অ্যাক্সেস (Admin/Moderator/User)
- 🎨 **কলাপসিবল সাইডবার** - সুন্দর অ্যাডমিন ইন্টারফেস

---

## 🛠️ প্রযুক্তি (Tech Stack)

| Technology | Version | Purpose |
|------------|---------|---------|
| Laravel | 11.x | Backend Framework |
| PHP | 8.2+ | Server Language |
| MySQL | 8.0+ | Production Database |
| SQLite | 3.x | Development Database |
| Tailwind CSS | 3.x | Styling |
| Alpine.js | 3.x | Frontend Interactivity |
| Maatwebsite Excel | 3.x | Excel Import/Export |

---

## 📦 ইনস্টলেশন (Installation)

### প্রয়োজনীয়তা (Requirements)
- PHP >= 8.2
- Composer
- MySQL 8.0+ (Production) / SQLite (Development)
- Node.js & NPM (optional for assets)

### ধাপসমূহ (Steps)

```bash
# ১. প্রজেক্ট ক্লোন করুন
git clone https://github.com/your-repo/satkhira-voter-list.git
cd satkhira-voter-list

# ২. ডিপেন্ডেন্সি ইনস্টল করুন
composer install

# ৩. এনভায়রনমেন্ট সেটআপ
cp .env.example .env
php artisan key:generate

# ৪. ডাটাবেস মাইগ্রেশন
php artisan migrate

# ৫. অ্যাডমিন ইউজার তৈরি
php artisan db:seed

# ৬. সার্ভার চালু করুন
php artisan serve
```

---

## ⚙️ প্রোডাকশন ডেপ্লয়মেন্ট (Production Deployment)

### cPanel Hosting

1. **ফাইল আপলোড** - সব ফাইল `public_html` এ আপলোড করুন
2. **.env আপডেট** করুন:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-domain.com
   
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```
3. **পারমিশন সেট** করুন:
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```
4. **ক্যাশ অপ্টিমাইজ** করুন:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---

## 📊 ডাটাবেস স্ট্রাকচার (Database Structure)

### voters টেবিল
| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT | Primary Key |
| serial_no | VARCHAR | ক্রমিক নং |
| upazila | VARCHAR | উপজেলা |
| union | VARCHAR | ইউনিয়ন |
| ward | VARCHAR | ওয়ার্ড |
| area_code | VARCHAR | এলাকা কোড |
| area_name | VARCHAR | এলাকার নাম |
| gender | VARCHAR | লিঙ্গ |
| center_no | VARCHAR | কেন্দ্র নং |
| center_name | VARCHAR | কেন্দ্রের নাম |
| name | VARCHAR | ভোটারের নাম |
| voter_id | VARCHAR | ভোটার আইডি |
| father_name | VARCHAR | পিতার নাম |
| mother_name | VARCHAR | মাতার নাম |
| occupation | VARCHAR | পেশা |
| date_of_birth | VARCHAR | জন্ম তারিখ |
| address | TEXT | ঠিকানা |

---

## 🔑 ডিফল্ট লগইন (Default Login)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@admin.com | admin123 |

⚠️ **গুরুত্বপূর্ণ:** প্রোডাকশনে অবশ্যই পাসওয়ার্ড পরিবর্তন করুন!

---

## 📸 স্ক্রিনশট (Screenshots)

### পাবলিক ওয়েবসাইট
- হোম পেজ সার্চ ইন্টারফেস
- ভোটার তথ্য কার্ড
- মোবাইল রেসপন্সিভ ভিউ

### অ্যাডমিন প্যানেল
- ড্যাশবোর্ড পরিসংখ্যান
- ভোটার লিস্ট টেবিল
- ব্যানার ম্যানেজমেন্ট

---

## 📝 লাইসেন্স (License)

This project is proprietary software. All rights reserved.

---

## 👨‍💻 ডেভেলপার (Developer)

<p align="center">
  <strong>Developed with ❤️ by</strong><br><br>
  <a href="https://github.com/mirjavedjeetu">
    <img src="https://img.shields.io/badge/Mir%20Javed%20Jeetu-Developer-purple?style=for-the-badge&logo=github" alt="Developer">
  </a>
</p>

<p align="center">
  <a href="mailto:contact@metasoftinfo.com">📧 contact@metasoftinfo.com</a><br>
  <a href="https://metasoftinfo.com">🌐 metasoftinfo.com</a>
</p>

---

<p align="center">
  <sub>© 2026 Meta Soft Info. All Rights Reserved.</sub>
</p>
