# نظام إدارة عيادات طبية مع حجز مواعيد وتصوير أشعة
==========================

### Overview & Project Purpose

نظام إدارة عيادات طبية مع حجز مواعيد وتصوير أشعة هو مشروع تطبيقي يهدف إلى تسهيل إدارة العيادات الطبية من خلال نظام إدارة متكامل. يتيح للمستخدمين حجز مواعيد، إدارة المرضى، وتحليل نتائج الأشعة. هذا النظام مصمم لتحسين كفاءة إدارة العيادات الطبية وتحسين تجربة المرضى.

### Project Structure Mapping


project/
├── docker/
│   ├── docker-compose.yml
│   ├── dockerfile
│   └── ...
├── src/
│   ├── app/
│   │   ├── models/
│   │   │   ├── Patient.php
│   │   │   ├── Appointment.php
│   │   │   └── ...
│   │   ├── controllers/
│   │   │   ├── AppointmentController.php
│   │   │   └── ...
│   │   ├── repositories/
│   │   │   ├── AppointmentRepository.php
│   │   │   └── ...
│   │   └── ...
│   ├── config/
│   │   ├── database.php
│   │   └── ...
│   ├── public/
│   │   ├── index.php
│   │   └── ...
│   └── ...
├── tests/
│   ├── UnitTests/
│   │   ├── AppointmentTest.php
│   │   └── ...
│   └── ...
├── vendor/
│   └── ...
└── ...


### Step-by-Step Instructions for Running the Environment using Docker-Compose

1. **Install Docker and Docker-Compose**: قم بتحميل وتثبيت Docker و Docker-Compose على جهازك.
2. **تثبيت dependencies**: قم بتشغيل الأمر `docker-compose up --build` في مجلد المشروع.
3. **تشغيل النظام**: قم بتشغيل الأمر `docker-compose up` لتشغيل النظام.
4. **تجربة النظام**: قم بزيارة `http://localhost:8080` في متصفحك لفتح النظام.

### Modules, Tables, and Roles

#### Modules

* **حجز مواعيد**: يتيح للمستخدمين حجز مواعيد مع الأطباء.
* **إدارة المرضى**: يتيح للمستخدمين إدارة بيانات المرضى.
* **تصوير أشعة**: يتيح للمستخدمين تحليل نتائج الأشعة.

#### Tables

* **patients**: يحتوي على بيانات المرضى.
* **appointments**: يحتوي على بيانات الحجوزات.
* **radiology_results**: يحتوي على نتائج الأشعة.

#### Roles

* **admin**: يمتلك صلاحيات إدارة النظام.
* **doctor**: يمتلك صلاحيات إدارة المرضى وحجز مواعيد.
* **patient**: يمتلك صلاحيات الحجز وحجز مواعيد.

### Contact Developer Details

* **اسم المطور**: [اسمك]
* **بريد الإلكتروني**: [بريدك]
* **رابط GitHub**: [رابط GitHub]
* **رابط LinkedIn**: [رابط LinkedIn]

### License

نظام إدارة عيادات طبية مع حجز مواعيد وتصوير أشعة هو مشروع مفتوح المصدر. يمكنك استخدام هذا المشروع وفقًا لشروط الترخيص المذكورة في الملف `LICENSE`.

---

## 📧 للتواصل (Contact)
almednyakrm@gmail.com
