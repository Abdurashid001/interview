Loyiha haqida
Bu Laravel asosida yaratilgan O'qituvchi-Student Intervyu Tizimi. Tizim orqali o'qituvchilar studentlar bilan intervyu tashkil qilishi, studentlar esa intervyularda qatnashishi mumkin. Admin tomonidan barcha jarayonlar boshqariladi.

Texnologiyalar
Laravel 12 - PHP framework

MySQL - Ma'lumotlar bazasi

Bootstrap 5 - Frontend

Chart.js - Statistik diagrammalar

Asosiy imkoniyatlar
Admin paneli
Barcha foydalanuvchilarni boshqarish

O'qituvchi so'rovlarini tasdiqlash/rad etish

Foydalanuvchi ma'lumotlarini tahrirlash

Parollarni o'zgartirish

Statistika ko'rish

O'qituvchi paneli
Yangi intervyu yaratish

Intervyular ro'yxatini ko'rish

Studentlarni intervyuga taklif qilish

Savollar qo'shish

Intervyu natijalarini kiritish

Shaxsiy profilni boshqarish

Student paneli
Intervyu takliflarini qabul qilish/rad etish

Intervyularda qatnashish

Natijalarni ko'rish

Shaxsiy profilni boshqarish

O'rnatish
Talablar
PHP ≥ 8.1

Composer

MySQL

Node.js & NPM (ixtiyoriy)

O'rnatish bosqichlari
bash
# 1. Loyihani klonlash
git clone <repository-url>
cd project-name

# 2. Composer paketlarini o'rnatish
composer install

# 3. Environment faylini yaratish
cp .env.example .env

# 4. Ma'lumotlar bazasini sozlash
# .env faylini oching va ma'lumotlar bazasi sozlamalarini kiriting:
# DB_DATABASE=your_database
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# 5. Application kalitini generatsiya qilish
php artisan key:generate

# 6. Migrationlarni ishga tushirish
php artisan migrate

# 7. Storage link yaratish (rasm va hujjatlar uchun)
php artisan storage:link

# 8. Development serverni ishga tushirish
php artisan serve
Ma'lumotlar bazasi strukturasi
Asosiy jadvallar
users - Foydalanuvchilar (admin, teacher, student)

teacher_requests - O'qituvchi bo'lish so'rovlari

interviews - Intervyular

interview_questions - Intervyu savollari

interview_invitations - Intervyu taklifnomalari

notifications - Bildirishnomalar

Users jadvali qo'shimcha maydonlari
role - Foydalanuvchi roli (admin, teacher, student)

phone - Telefon raqami

experience_years - Ish staji (o'qituvchilar uchun)

document_path - Tasdiqlovchi hujjat (o'qituvchilar uchun)

is_approved - Tasdiqlanganlik holati

approved_at - Tasdiqlangan sana

avatar - Profil rasmi

Route'lar
Asosiy route'lar
php
GET  /                     // Bosh sahifa
GET  /login                // Kirish
POST /login                // Kirish
GET  /register             // Ro'yxatdan o'tish
POST /register             // Ro'yxatdan o'tish
POST /logout               // Chiqish
GET  /dashboard            // Dashboard
Admin route'lari
php
GET    /admin/users                    // Foydalanuvchilar
GET    /admin/users/create              // Yangi foydalanuvchi
POST   /admin/users                     // Saqlash
GET    /admin/users/{user}              // Ko'rish
GET    /admin/users/{user}/edit         // Tahrirlash
PUT    /admin/users/{user}              // Yangilash
GET    /admin/users/{user}/change-password // Parol o'zgartirish
PUT    /admin/users/{user}/password     // Parolni yangilash
DELETE /admin/users/{user}              // O'chirish
POST   /admin/users/{user}/approve      // O'qituvchini tasdiqlash
GET    /admin/statistics                 // Statistika
O'qituvchi route'lari
php
GET    /teacher/dashboard                // Dashboard
GET    /teacher/interviews                // Intervyular
GET    /teacher/interviews/create         // Yangi intervyu
POST   /teacher/interviews                // Saqlash
GET    /teacher/interviews/{interview}    // Ko'rish
GET    /teacher/interviews/{interview}/questions // Savollar
POST   /teacher/interviews/{interview}/questions // Savollarni saqlash
POST   /teacher/interviews/{interview}/invite     // Studentlarni taklif qilish
GET    /teacher/interviews/{interview}/results    // Natijalarni ko'rish
POST   /teacher/interviews/{interview}/results    // Natijalarni saqlash
GET    /teacher/students                   // Studentlar
GET    /teacher/profile                     // Profil
Student route'lari
php
GET    /student/dashboard                // Dashboard
GET    /student/interviews                // Intervyular
GET    /student/interviews/{interview}    // Ko'rish
POST   /student/interviews/{interview}/answers // Javoblar
POST   /student/invitations/{invitation}/accept // Taklifni qabul qilish
POST   /student/invitations/{invitation}/decline // Taklifni rad etish
GET    /student/profile                     // Profil
GET    /student/courses                      // Kurslar
Muhim funksiyalar
O'qituvchi bo'lish jarayoni
Student O'qituvchi bo'lish tugmasini bosadi

Maxsus forma to'ldiriladi va PDF hujjat yuklanadi

Admin so'rovni ko'rib chiqadi

Admin tasdiqlagach, foydalanuvchi o'qituvchi sifatida tizimga kira oladi

Intervyu yaratish jarayoni
O'qituvchi intervyu yaratadi (mavzu, vaqt, davomiylik)

Savollar qo'shadi

Studentlarni taklif qiladi

Student taklifni qabul qiladi

Intervyu vaqtida student savollarga javob beradi

O'qituvchi natijalarni kiritadi

Xatoliklarni bartaraf qilish
Keng tarqalgan xatolar
Xatolik	Sabab	Yechim
Route not found	Route mavjud emas	php artisan route:list bilan tekshiring
View not found	View fayli yo'q	View faylini yarating
Class not found	Model import qilinmagan	use App\Models\ModelName qo'shing
Column not found	Jadvalda ustun yo'q	Migration qiling
Call to a member function format() on null	Null qiymat	$value?->format() yoki isset() tekshiring
Cacheni tozalash
bash
php artisan optimize:clear
php artisan route:clear
php artisan view:clear
php artisan config:clear
Ma'lumotlar bazasini to'ldirish (Seeder)
bash
# Barcha seederlarni ishga tushirish
php artisan db:seed

# Alohida seederni ishga tushirish
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=TeacherRequestSeeder
Test foydalanuvchilar
bash
# Admin yaratish
php artisan tinker
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@example.com';
$user->password = Hash::make('password');
$user->role = 'admin';
$user->save();
Loyiha tuzilishi
text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── UserController.php
│   │   │   └── TeacherRequestController.php
│   │   ├── Teacher/
│   │   │   ├── InterviewController.php
│   │   │   └── TeacherController.php
│   │   ├── Student/
│   │   │   ├── InterviewController.php
│   │   │   └── StudentController.php
│   │   └── Auth/
│   └── Middleware/
├── Models/
│   ├── User.php
│   ├── Interview.php
│   ├── InterviewQuestion.php
│   ├── InterviewInvitation.php
│   ├── TeacherRequest.php
│   └── Notification.php
resources/
├── views/
│   ├── layouts/
│   ├── admin/
│   ├── teacher/
│   ├── student/
│   └── auth/
routes/
└── web.php
Qo'shimcha imkoniyatlar (kelajakda)
Email orqali bildirishnomalar

Video intervyu (Zoom/Google Meet)

Chat tizimi

Kalendar integratsiyasi

Sertifikat generatsiya qilish

Export/Import (Excel, PDF)

API

Litsenziya
MIT

Muallif
Sizning ismingiz

© 2026 Barcha huquqlar himoyalangan
