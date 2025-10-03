# Laravel Classroom Clone

A **Laravel-based classroom management system** inspired by Google Classroom.  
This project was developed as part of a weekly coding challenge and preparation for the **USK (Uji Sertifikasi Kompetensi)** exam.  
It also serves as a portfolio project to demonstrate skills in **full-stack web development, database design, and software engineering principles**.

---

## 🚀 Features

- **Authentication**
  - User registration & login (Teacher & Student roles).
- **Class Management**
  - Teachers can create, edit, and delete classes.
  - Students can join classes using a unique class code.
- **Material Management**
  - Upload and manage learning resources (PDF, PPT, etc.).
- **Assignment Management**
  - Teachers create assignments with deadlines.
  - Students submit answers via file upload or text.
- **Submission & Grading**
  - Teachers review, grade, and comment on student submissions.
- **Dashboard**
  - Personalized dashboard for teachers and students.
- **Extra (Creativity)**
  - Discussion forum for each material/assignment.
  - Export grades to Excel.

---

## 🛠️ Tech Stack

- **Backend:** Laravel 12  
- **Frontend:** Blade, TailwindCSS/Bootstrap  
- **Database:** MySQL/MariaDB  
- **Server:** Apache/Nginx  
- **Tools:** Composer, NPM, Git  

---

## 🗄️ Database Structure

Key tables:  
- `users` → Authentication & roles (Teacher, Student)  
- `kelas` → Classroom management  
- `materi` → Learning materials  
- `tugas` → Assignments  
- `pengumpulan` → Submissions (with grades & comments)  
- `kelas_member` → Pivot table for students joining classes  

---

## ⚡ Installation

1. Clone the repository  
   ```bash
   git clone https://github.com/your-username/laravel-classroom-clone.git
   cd laravel-classroom-clone
   ```

2. **Install dependencies**

   ```bash
   composer install
   pnpm install && pnpm run dev
   ```

3. **Configure environment file**

   ```bash
   cp .env.example .env
   ```

   Update the `.env` file with your database credentials. Example:

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=digikelas21
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate application key**

   ```bash
   php artisan key:generate
   ```

5. **Run migrations**

   ```bash
   php artisan migrate --seed
   ```

6. **Serve the application**

   ```bash
   php artisan serve
   ```

    ```bash
   pnpm artisan serve
   ```
   The app will be available at [http://localhost:8000](http://localhost:8000).

## 🔑 Demo Accounts

    | Role    | Email                                                   | Password    |
    | ------- | ------------------------------------------------------- | ----------- |
    | Teacher | [guru.demo@smk21.ac.id](mailto:guru.demo@smk21.ac.id)   | password123 |
    | Student | [siswa.demo@smk21.ac.id](mailto:siswa.demo@smk21.ac.id) | password123 |

## 📄 License

This project is licensed under the [MIT License](./LICENSE).

---

✨ Created as part of **USK Weekly Coding Challenge And Preparation**.
