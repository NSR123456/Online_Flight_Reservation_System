[![flight-reservation-system · GitHub ...](https://images.openai.com/thumbnails/1762f42f9ed66dfa2b704c2412f2abbd.jpeg)](https://github.com/topics/flight-reservation-system)


# Online Flight Reservation System

A web-based application that allows users to book flights online. Developed using PHP and MySQL, this system provides an intuitive interface for passengers to search, select, and reserve flights.

## 🛠️ Features

* **User Registration & Login**: Secure authentication for passengers.
* **Flight Search**: Find flights by source, destination, and date.
* **Booking Management**: Reserve, view, and cancel bookings.
* **Admin Panel**: Manage flights, view bookings, and update statuses.

## ⚙️ Tech Stack

* **Backend**: PHP
* **Database**: MySQL
* **Frontend**: HTML, CSS, JavaScript
* **Framework**: Bootstrap 4

## 📦 Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/NSR123456/Online_Flight_Reservation_System.git
   cd Online_Flight_Reservation_System
   ```

2. Import the database schema:

   * Access MySQL:

     ```bash
     mysql -u root -p
     ```

   * Create a new database:

     ```sql
     CREATE DATABASE flight_reservation;
     USE flight_reservation;
     ```

   * Import the schema:

     ```sql
     SOURCE path_to_schema.sql;
     ```

3. Configure database connection:

   * Edit `config.php` with your database credentials:

     ```php
     define('DB_SERVER', 'localhost');
     define('DB_USERNAME', 'root');
     define('DB_PASSWORD', '');
     define('DB_DATABASE', 'flight_reservation');
     ```

4. Start a local server:

   * If using XAMPP, place the project folder in the `htdocs` directory.
   * Access the application via `http://localhost/Online_Flight_Reservation_System`.

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.


