<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Drop and create database
    $conn->exec("DROP DATABASE IF EXISTS playarena_db");
    $conn->exec("CREATE DATABASE playarena_db");
    $conn->exec("USE playarena_db");

    // Create tables
    $conn->exec("CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('user', 'admin') DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $conn->exec("CREATE TABLE sports (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        description TEXT,
        image VARCHAR(255)
    )");

    $conn->exec("CREATE TABLE locations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE
    )");

    $conn->exec("CREATE TABLE grounds (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sport_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        location_id INT NOT NULL,
        price_per_hour DECIMAL(10, 2) NOT NULL,
        image VARCHAR(255),
        description TEXT,
        FOREIGN KEY (sport_id) REFERENCES sports(id) ON DELETE CASCADE,
        FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE CASCADE
    )");

    $conn->exec("CREATE TABLE bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        ground_id INT NOT NULL,
        booking_date DATE NOT NULL,
        time_slot VARCHAR(50) NOT NULL,
        total_price DECIMAL(10, 2) NOT NULL,
        status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'confirmed',
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (ground_id) REFERENCES grounds(id) ON DELETE CASCADE
    )");

    $conn->exec("CREATE TABLE reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        ground_id INT NOT NULL,
        rating INT NOT NULL CHECK(rating >= 1 AND rating <= 5),
        comment TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (ground_id) REFERENCES grounds(id) ON DELETE CASCADE
    )");

    $conn->exec("CREATE TABLE coaches (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        experience INT NOT NULL,
        location VARCHAR(100) NOT NULL,
        sport VARCHAR(100) NOT NULL,
        course_fees DECIMAL(10, 2) NOT NULL,
        course_duration VARCHAR(100) NOT NULL
    )");

    $conn->exec("CREATE TABLE coaching_enrollments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        coach_id INT NOT NULL,
        status ENUM('active', 'completed') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (coach_id) REFERENCES coaches(id) ON DELETE CASCADE
    )");

    // Insert Admin
    $admin_pw = password_hash('admin123', PASSWORD_BCRYPT);
    $conn->exec("INSERT INTO users (name, email, password, role) VALUES ('Admin User', 'admin@playarena.com', '$admin_pw', 'admin')");

    // Insert user
    $user_pw = password_hash('user123', PASSWORD_BCRYPT);
    $conn->exec("INSERT INTO users (name, email, password, role) VALUES ('Test User', 'user@playarena.com', '$user_pw', 'user')");

    // Define mock data
    $sports = [
        ['Cricket', 'Experience the thrill of the gentleman\'s game.', 'https://images.unsplash.com/photo-1531415074968-036ba1b575da?w=800&q=80'],
        ['Football', 'Play the beautiful game on premium turfs.', 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=800&q=80'],
        ['Badminton', 'Smash it on world-class indoor courts.', 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=800&q=80'],
        ['Tennis', 'Perfect your serve on professional clay and hard courts.', 'https://images.unsplash.com/photo-1622279457486-62dcc4a431d6?w=800&q=80'],
        ['Basketball', 'Shoot hoops in top-tier indoor and outdoor courts.', 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=800&q=80'],
        ['Swimming', 'Take a dive in olympic size swimming pools.', 'https://images.unsplash.com/photo-1519315901367-f34f826359eb?w=800&q=80'],
        ['Table Tennis', 'Fast-paced action on professional tables.', 'https://images.unsplash.com/photo-1609710228159-0fa9bd7c0827?w=800&q=80'],
        ['Volleyball', 'Bump, set, spike on premium sand and indoor courts.', 'https://images.unsplash.com/photo-1592656094267-764a45160876?w=800&q=80'],
        ['Squash', 'High intensity squash courts for power matches.', 'https://images.unsplash.com/photo-1595011666735-e10398dc4254?w=800&q=80'],
        ['Boxing', 'State of the art boxing rings and training bags.', 'https://images.unsplash.com/photo-1549719386-74dfcbf7dbed?w=800&q=80'],
    ];

    $locations = ['Bandra', 'Andheri', 'Juhu', 'Goregaon', 'Malad', 'Powai', 'Worli', 'Colaba', 'Dadar', 'Kurla'];

    $insert_sport = $conn->prepare("INSERT INTO sports (name, description, image) VALUES (?, ?, ?)");
    $insert_location = $conn->prepare("INSERT INTO locations (name) VALUES (?)");

    foreach ($sports as $sport) {
        $insert_sport->execute($sport);
    }
    
    foreach ($locations as $loc) {
        $insert_location->execute([$loc]);
    }

    // Generate 5+ grounds per sport
    $insert_ground = $conn->prepare("INSERT INTO grounds (sport_id, name, location_id, price_per_hour, image, description) VALUES (?, ?, ?, ?, ?, ?)");
    
    for ($s_id = 1; $s_id <= 10; $s_id++) {
        $sport_name = $sports[$s_id-1][0];
        $sport_image = $sports[$s_id-1][2]; // reuse base image logic
        for ($g = 1; $g <= 6; $g++) {
            $loc_id = rand(1, 10);
            $loc_name = $locations[$loc_id - 1];
            $ground_name = "Premium " . $sport_name . " Arena " . $g;
            $price = rand(500, 2500);
            $desc = "A premium quality $sport_name ground located in the heart of $loc_name. Perfect for professionals and amateurs.";
            $image = "https://source.unsplash.com/800x600/?" . urlencode($sport_name); // dynamic random images based on sport
            $insert_ground->execute([$s_id, $ground_name, $loc_id, $price, $sport_image, $desc]);
        }
    }

    // Insert some mock coaches
    $insert_coach = $conn->prepare("INSERT INTO coaches (name, experience, location, sport, course_fees, course_duration) VALUES (?, ?, ?, ?, ?, ?)");
    $mock_coaches = [
        ['Ravi Shastri', 15, 'Bandra', 'Cricket', 5000, '3 Months'],
        ['Sunil Chhetri', 12, 'Andheri', 'Football', 4500, '3 Months'],
        ['P.V. Sindhu', 10, 'Juhu', 'Badminton', 6000, '3 Months'],
        ['Leander Paes', 20, 'Worli', 'Tennis', 7000, '6 Months'],
        ['Kavita Devi', 8, 'Dadar', 'Basketball', 3000, '2 Months'],
    ];

    foreach ($mock_coaches as $coach) {
        $insert_coach->execute($coach);
    }

    echo "Database playarena_db created and populated successfully!";

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
