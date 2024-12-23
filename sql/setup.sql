CREATE DATABASE IF NOT EXISTS flight_booking_system;

USE flight_booking_system;

CREATE TABLE IF NOT EXISTS Customers (
    passport_id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255),
    dob DATE,
    nationality VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS Cabs (
    reg_no VARCHAR(50) PRIMARY KEY,
    capacity INT,
    driver_name VARCHAR(255),
    phone_no VARCHAR(20),
    model VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS Flights (
    flight_no VARCHAR(50) PRIMARY KEY,
    flight_name VARCHAR(255),
    no_of_seat INT,
    engine VARCHAR(50),
    capacity INT
);

CREATE TABLE IF NOT EXISTS Transactions (
    id INT PRIMARY KEY,
    customer_id VARCHAR(255),
    no_of_seat INT,
    seat_type VARCHAR(50),
    price DECIMAL(10, 2),
    FOREIGN KEY (customer_id) REFERENCES Customers(passport_id)
);

CREATE TABLE IF NOT EXISTS Airports (
    id VARCHAR(255) PRIMARY KEY,
    airport_name VARCHAR(255),
    city VARCHAR(100),
    country VARCHAR(100),
    postal_code VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS Airlines (
    airline_id VARCHAR(50) PRIMARY KEY,
    airline_name VARCHAR(255),
    headquarter VARCHAR(255),
    contact VARCHAR(100),
    website VARCHAR(255),
    services TEXT,
    operating_regions TEXT
);

CREATE TABLE IF NOT EXISTS Flight_Schedule (
    id VARCHAR(50) PRIMARY KEY,
    flight_no VARCHAR(50),
    departure_date DATE,
    departure_time TIME,
    from_location VARCHAR(100),
    to_location VARCHAR(100),
    FOREIGN KEY (flight_no) REFERENCES Flights(flight_no)
);

-- Relationship Tables
CREATE TABLE IF NOT EXISTS FlightBooking (
    bill NUMERIC(10,2),
    airport_id VARCHAR(255),
    customer_id VARCHAR(255),
    flight_no VARCHAR(50),
    FOREIGN KEY (airport_id) REFERENCES Airports(id),
    FOREIGN KEY (customer_id) REFERENCES Customers(passport_id),
    FOREIGN KEY (flight_no) REFERENCES Flights(flight_no)
);

CREATE TABLE IF NOT EXISTS CabBooking (
    cab_reg_no VARCHAR(50),
    customer_id VARCHAR(255),
    price DECIMAL(10, 2) NOT NULL,
    pickup_location VARCHAR(255) NOT NULL,
    dropoff_location VARCHAR(255) NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES Customers(passport_id),
    FOREIGN KEY (cab_reg_no) REFERENCES Cabs(reg_no)
);

CREATE TABLE IF NOT EXISTS ManageFlight (
    airline_id VARCHAR(50),
    flight_no VARCHAR(50),
    FOREIGN KEY (airline_id) REFERENCES Airlines(airline_id),
    FOREIGN KEY (flight_no) REFERENCES Flights(flight_no)
);

CREATE TABLE IF NOT EXISTS BuyTicket (
    customer_id VARCHAR(255),
    transaction_id INT,
    FOREIGN KEY (customer_id) REFERENCES Customers(passport_id),
    FOREIGN KEY (transaction_id) REFERENCES Transactions(id)
);

CREATE TABLE IF NOT EXISTS Travels (
    flight_no VARCHAR(50),
    airport_id VARCHAR(255),
    schedule_id VARCHAR(50),
    FOREIGN KEY (airport_id) REFERENCES Airports(id),
    FOREIGN KEY (flight_no) REFERENCES Flights(flight_no),
    FOREIGN KEY (schedule_id) REFERENCES Flight_Schedule(id)
);
