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
CREATE TABLE Cab_Route_Price (
    id INT AUTO_INCREMENT PRIMARY KEY,

    dropoff_location VARCHAR(255),
    price DECIMAL(10, 2)
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
    bill NUMERIC(10, 2),
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
    id INT PRIMARY KEY AUTO_INCREMENT,
    flight_no VARCHAR(50),
    departure_date DATE,
    departure_time TIME,
    source VARCHAR(100),
    destination VARCHAR(100),
);
CREATE TABLE customer_support_info (
    id INT PRIMARY KEY,
    message TEXT NOT NULL,
    email VARCHAR(100) ,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
);

-- Relationship Tables
CREATE TABLE IF NOT EXISTS BookFlight (
    transaction_id INT AUTO INCREMENT,
    customer_id VARCHAR(255),
    flight_no VARCHAR(50),
    airport_id VARCHAR(255),
    schedule_id VARCHAR(50),
    FOREIGN KEY (transaction_id) REFERENCES Transactions(id),
    FOREIGN KEY (customer_id) REFERENCES Customers(passport_id),
    FOREIGN KEY (flight_no) REFERENCES Flights(flight_no),
    FOREIGN KEY (airport_id) REFERENCES Airports(id),
    FOREIGN KEY (schedule_id) REFERENCES Flight_Schedule(id)
);


CREATE TABLE IF NOT EXISTS BookCab (
    route_id INT,
    cab_reg_no VARCHAR(50),
    customer_id VARCHAR(255),
    from_airport_id VARCHAR(255),
    booking_date DATE,
    FOREIGN KEY (route_id) REFERENCES Cab_Route_Price(id),
    FOREIGN KEY (cab_reg_no) REFERENCES Cabs(cab_reg_no),
    FOREIGN KEY (customer_id) REFERENCES Customers(customer_id)
    FOREIGN KEY (from_airport_id) REFERENCES Airports(id)
);


    CREATE TABLE IF NOT EXISTS AllocateSeat (
    schedule_id INT ,
    flight_no VARCHAR(50),
    available_seats INT,
    status ENUM('Available', 'Booked') DEFAULT 'Available',
    FOREIGN KEY (flight_no) REFERENCES Flights(flight_no),
    FOREIGN KEY (schedule_id) REFERENCES Flight_Schedule(id)
);

CREATE TABLE IF NOT EXISTS SupportRequests (
    customer_id VARCHAR(255),
    airline_id VARCHAR(50),
    msg_id INT,
    FOREIGN KEY (msg_id) REFERENCES customer_support_info(id),
    FOREIGN KEY (customer_id) REFERENCES Customers(passport_id),
    FOREIGN KEY (airline_id) REFERENCES Airlines(airline_id)
);
CREATE TABLE BelongTo (
    flight_no VARCHAR(50),
    airline_id VARCHAR(50),
    FOREIGN KEY (flight_no) REFERENCES Flights(flight_no),
    FOREIGN KEY (airline_id) REFERENCES Airlines(airline_id)
);
