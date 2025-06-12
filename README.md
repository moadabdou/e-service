
# Application de Gestion des Affectations des Enseignements

## Overview

This project, developed as part of the "Web 1: Technologies du Web et PHP5" module at ENSA Al Hoceima, is an application designed to manage the assignment of teaching units within academic departments. The primary goal is to optimize and automate the process of assigning teaching responsibilities to professors and temporary staff (vacataires), ensuring a balanced and transparent distribution of teaching loads.

The application aims to:

* Centralize the management of teaching units and teaching staff per department.
* Simplify the expression of teaching preferences for the upcoming academic year.
* Ensure an equitable distribution of teaching hours.
* Allow department heads and coordinators to manage and validate teaching assignments.
* Generate detailed reports for monitoring and analysis.
* Ensure traceability of decisions and maintain a history of assignments.

### Key Actors and Their Roles:

* **Administrators:** Manage user accounts and access permissions, including creating accounts for professors and assigning responsibilities.

* **Chefs de Département (Department Heads):**
  * Manage modules and professors within their department.
  * Assign teaching units to professors.
  * Validate or decline professors' choices.
  * Generate professors' hourly loads (with visual indication for those below minimum).
  * Consult and validate vacant teaching units.
  * Access historical data and generate reports.
  * Import/Export data via Excel files.

* **Coordonnateurs de Filière (Program Coordinators):**
  * Manage modules specific to their program.
  * Create module descriptions (volume, specialty, responsible).
  * Define TD/TP group numbers for the next semester.
  * Consult assignment validations by department heads.
  * Assign teaching units to temporary staff (vacataires).
  * Create accounts for temporary staff.
  * Access historical data.
  * Import/Export data via Excel files.
  * Upload semester timetables and assign them to relevant teachers.

* **Enseignants (Professors):**
  * View available teaching units for the next year.
  * Select and express preferences for teaching units.
  * Automatically calculate total selected hourly load.
  * Receive notifications for non-compliance with minimum load.
  * Consult a list of modules they are responsible for.
  * Upload grades for regular and make-up sessions.
  * Consult historical data.

* **Vacataires (Temporary Staff):**
  * Consult the list of teaching units they are assigned.
  * Upload grades for regular and make-up sessions.

### Security and Data Management:

* All users (admin, department heads, program coordinators, professors, and temporary staff) must authenticate with secure credentials.
* Access to certain functionalities is restricted based on user roles.
* Professors and teaching units are oriented towards a department based on their specialization.

## Installation Guide

This project is built using vanilla PHP with some Composer-managed libraries and is designed to run on a XAMPP environment.

### Prerequisites

* **XAMPP:** Download and install XAMPP from <https://www.apachefriends.org/index.html>. This package includes Apache, MySQL (MariaDB), PHP, and phpMyAdmin, which are all necessary for running the application.
* **Composer:** Ensure Composer is installed globally on your system. If not, follow the instructions on the official Composer website: <https://getcomposer.org/doc/00-intro.md>

### Step-by-Step Installation

1. **Clone the Repository :**

   ```
   git clone https://github.com/moadabdou/e-service.git C:\xampp\htdocs\eservice
   ```

2. **Start XAMPP Services:**

   * Open the XAMPP Control Panel.
   * Start **Apache** and **MySQL** services.

3. **Create Database and Import Schema:**

   * Open your web browser and navigate to `http://localhost/phpmyadmin`.
   * In phpMyAdmin, click on the "Databases" tab.
   * Enter `eservice` as the database name and click "Create".
   * Select the newly created `eservice` database from the left sidebar.
   * Click on the "Import" tab.
   * Click "Choose File" and select the `schema.sql` file located in your project's `database/` folder (e.g., `C:\xampp\htdocs\eservice\database\schema.sql`).
   * Scroll down and click "Go" to import the database schema.

4. **Install Composer Dependencies:**

   * Open your terminal or command prompt.
   * Navigate to your project's root directory (e.g., `cd C:\xampp\htdocs\eservice`).
   * Run the Composer install command:

   ```
   composer install
   ```

   This will install all necessary PHP libraries defined in your `composer.json` file.

5. **Configure Database Connection (`.env` file):**

   * In the root directory of your project, create a new file named `.env` (if it doesn't already exist).
   * Add the following lines to the `.env` file, which define your database connection parameters:

   ```
   host=localhost
   username=root
   password=
   dbname=eservice
   port=3306
   ```

   * Save the `.env` file.

6. **Access the Application:**

   * Open your web browser and navigate to the URL of your project. If you placed it directly in `htdocs` under `eservice`, the URL will typically be:

   ```
   http://localhost/eservice/
   ```

   * You should now see the application's login page or home page.
