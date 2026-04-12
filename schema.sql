DROP DATABASE IF EXISTS playarena;
CREATE DATABASE playarena;
USE playarena;

-- ================= USERS =================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin','user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================= SPORTS =================
CREATE TABLE sports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    icon VARCHAR(50)
);

-- ================= GROUNDS =================
CREATE TABLE grounds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sport_id INT,
    name VARCHAR(100),
    location VARCHAR(255),
    price_per_hour DECIMAL(10,2),
    image_url TEXT,
    FOREIGN KEY (sport_id) REFERENCES sports(id) ON DELETE CASCADE
);

-- ================= COACHES =================
CREATE TABLE coaches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sport_id INT,
    ground_id INT,
    name VARCHAR(100),
    experience_years INT,
    hourly_rate DECIMAL(10,2),
    image_url TEXT,
    bio TEXT,
    FOREIGN KEY (sport_id) REFERENCES sports(id) ON DELETE CASCADE,
    FOREIGN KEY (ground_id) REFERENCES grounds(id) ON DELETE SET NULL
);

-- ================= BOOKINGS =================
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    ground_id INT NULL,
    coach_id INT NULL,
    booking_date DATE,
    slot_time VARCHAR(50),
    total_amount DECIMAL(10,2),
    status ENUM('confirmed','cancelled') DEFAULT 'confirmed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (ground_id) REFERENCES grounds(id) ON DELETE CASCADE,
    FOREIGN KEY (coach_id) REFERENCES coaches(id) ON DELETE CASCADE
);

-- ================= USERS DATA =================
INSERT INTO users (name,email,password,role) VALUES
('Admin','admin@playarena.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin'),
('User','user@playarena.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user');

-- ================= SPORTS =================
INSERT INTO sports (name,icon) VALUES
('Cricket','bi-picket-fence'),
('Football','bi-soccer-ball'),
('Badminton','bi-lightning'),
('Tennis','bi-circle'),
('Basketball','bi-basket'),
('Volleyball','bi-circle'),
('Table Tennis','bi-circle'),
('Swimming','bi-circle');

-- ================= GROUNDS (3 EACH SPORT) =================
INSERT INTO grounds (sport_id,name,location,price_per_hour,image_url) VALUES

-- CRICKET
(1,'Andheri Cricket Ground','Andheri West',1200,'https://images.unsplash.com/photo-1674986778924-7a33c1531443'),
(1,'Bandra Cricket Turf','Bandra East',1500,'https://images.unsplash.com/photo-1624193634221-33b652971323'),
(1,'Powai Cricket Arena','Powai',1000,'https://images.unsplash.com/photo-1761757106344-441482b56693'),

-- FOOTBALL
(2,'Andheri Football Turf','Andheri East',1800,'https://plus.unsplash.com/premium_photo-1671489203034-fc619a2de3bf'),
(2,'Bandra Football Field','Bandra West',2200,'https://plus.unsplash.com/premium_photo-1663948061665-34c2b6d42381'),
(2,'Powai Football Arena','Powai',1600,'https://plus.unsplash.com/premium_photo-1685089027812-6885c06b0fbf'),

-- BADMINTON
(3,'Andheri Badminton Court','Andheri West',500,'https://images.unsplash.com/photo-1723633236252-eb7badabb34c'),
(3,'Bandra Badminton Club','Bandra East',600,'https://images.unsplash.com/photo-1599391398131-cd12dfc6c24e'),
(3,'Powai Badminton Hub','Powai',450,'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8YmFkbWludG9uJTIwY291cnR8ZW58MHx8MHx8fDA%3D'),

-- TENNIS
(4,'Andheri Tennis Court','Andheri West',800,'https://images.unsplash.com/flagged/photo-1576972405668-2d020a01cbfa?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTB8fHRlbm5pcyUyMGNvdXJ0fGVufDB8fDB8fHww'),
(4,'Bandra Tennis Lawn','Bandra West',1000,'https://plus.unsplash.com/premium_photo-1663039984787-b11d7240f592'),
(4,'Powai Tennis Arena','Powai',750,'https://plus.unsplash.com/premium_photo-1664303119944-4cf5302bb701'),

-- BASKETBALL
(5,'Andheri Basketball Court','Andheri East',900,'https://images.unsplash.com/photo-1600534220378-df36338afc40'),
(5,'Bandra Basketball Arena','Bandra West',1100,'https://plus.unsplash.com/premium_photo-1675364966937-c2bdf5bce9b5'),
(5,'Powai Basketball Hub','Powai',850,'https://images.unsplash.com/photo-1590227632180-80a3bf110871'),

-- VOLLEYBALL
(6,'Andheri Volleyball Court','Andheri West',700,'https://plus.unsplash.com/premium_photo-1708696216242-a432e73ecd72'),
(6,'Bandra Volleyball Arena','Bandra West',900,'https://plus.unsplash.com/premium_photo-1708696216310-5abfafa9aec9'),
(6,'Powai Volleyball Hub','Powai',750,'https://images.unsplash.com/photo-1503152977911-f125b5741a6d'),

-- TABLE TENNIS
(7,'Andheri TT Club','Andheri East',400,'https://images.unsplash.com/photo-1708268418738-4863baa9cf72'),
(7,'Bandra TT Arena','Bandra West',500,'https://images.unsplash.com/photo-1746052379113-a2ab829e161b'),
(7,'Powai TT Hub','Powai',350,'https://images.unsplash.com/photo-1617473515500-05a0b4a2306e'),

-- SWIMMING
(8,'Andheri Swimming Pool','Andheri West',600,'https://plus.unsplash.com/premium_photo-1663040082818-b25debfd997f'),
(8,'Bandra Swimming Pool','Bandra West',700,'https://images.unsplash.com/photo-1652911367218-36fef4d8e60d'),
(8,'Powai Aqua Center','Powai',650,'https://images.unsplash.com/photo-1541689186060-3b08be2fd22f?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8N3x8c3dpbWluZyUyMGNvYWNofGVufDB8fDB8fHww');

-- ================= COACHES (3 EACH SPORT) =================
INSERT INTO coaches (sport_id, ground_id, name, experience_years, hourly_rate, image_url, bio) VALUES

-- CRICKET
(1,1,'Rahul Sharma',10,500,'https://plus.unsplash.com/premium_photo-1661861204104-6a6dbf55ff6e?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8Y3JpY2tldCUyMGNvYWNofGVufDB8fDB8fHww','Batting expert'),
(1,2,'Amit Verma',12,600,'https://images.unsplash.com/photo-1599982973590-2de8279c6114?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTJ8fGNyaWNrZXQlMjBjb2FjaHxlbnwwfHwwfHx8MA%3D%3D','Bowling specialist'),
(1,3,'Suresh Iyer',8,400,'https://media.istockphoto.com/id/1489166683/photo/coach-discussing-strategy-with-his-team-during-the-innings-break-of-the-cricket-match.webp?a=1&b=1&s=612x612&w=0&k=20&c=p3rWFagrITWM3pmaUfn9cz4Om_GM-eOnlMK-_MmT40w=','All-round trainer'),

-- FOOTBALL
(2,4,'Karan Mehta',15,700,'https://images.unsplash.com/photo-1585757318177-0570a997dc3a?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8Zm9vdGJhbGwlMjBjb2FjaHxlbnwwfHwwfHx8MA%3D%3D','Tactical coach'),
(2,5,'Vikas Yadav',10,600,'https://images.unsplash.com/photo-1526232761682-d26e03ac148e?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Nnx8Zm9vdGJhbGwlMjBjb2FjaHxlbnwwfHwwfHx8MA%3D%3D','Defense expert'),
(2,6,'Rohit Singh',9,550,'https://plus.unsplash.com/premium_photo-1664304619594-1b2bffeff5f2?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8Zm9vdGJhbGwlMjBjb2FjaHxlbnwwfHwwfHx8MA%3D%3D','Fitness trainer'),

-- BADMINTON
(3,7,'Neha Kapoor',20,1200,'https://images.unsplash.com/photo-1733141732193-9f05009c931a?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8YmFkbWludG9uJTIwY29hY2h8ZW58MHx8MHx8fDA%3D','National level coach'),
(3,8,'Pooja Shah',12,800,'https://plus.unsplash.com/premium_photo-1747939676323-a653fb286025?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8YmFkbWludG9uJTIwY29hY2h8ZW58MHx8MHx8fDA%3D','Footwork specialist'),
(3,9,'Ankit Jain',10,700,'https://images.unsplash.com/photo-1771854399835-f413978006cb?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTV8fGJhZG1pbnRvbiUyMGNvYWNofGVufDB8fDB8fHww','Smash training'),

-- TENNIS
(4,10,'Rohan Desai',18,1500,'https://plus.unsplash.com/premium_photo-1661860690785-ab78cec2a21d?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8dGVubmlzY29hY2h8ZW58MHx8MHx8fDA%3D','Serve expert'),
(4,11,'Aditya Nair',20,2000,'https://plus.unsplash.com/premium_photo-1661816354638-d575287bf82d?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTN8fHRlbm5pc2NvYWNofGVufDB8fDB8fHww','Professional coach'),
(4,12,'Manisha Patel',14,1200,'https://images.unsplash.com/photo-1595435742656-5272d0b3fa82?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Nnx8dGVubmlzY29hY2h8ZW58MHx8MHx8fDA%3D','Beginner friendly'),

-- BASKETBALL
(5,13,'Sahil Khan',10,500,'https://images.unsplash.com/photo-1548311344-5324fa0dbad6?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8YmFza2V0YmFsbCUyMGNvYWNofGVufDB8fDB8fHww','Shooting expert'),
(5,14,'Arjun Malhotra',8,450,'https://images.unsplash.com/photo-1520399636535-24741e71b160?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8YmFza2V0YmFsbCUyMGNvYWNofGVufDB8fDB8fHww','Defense coach'),
(5,15,'Dev Chauhan',12,600,'https://images.unsplash.com/photo-1588411105268-51be2fd9722e?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fGJhc2tldGJhbGwlMjBjb2FjaHxlbnwwfHwwfHx8MA%3D%3D','Advanced trainer'),

-- VOLLEYBALL
(6,16,'Kunal Bansal',9,400,'https://plus.unsplash.com/premium_photo-1661963404614-74802f16a7a0?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8dm9sbHliYWxsfGVufDB8fDB8fHww','Spike specialist'),
(6,17,'Nikhil Arora',7,350,'https://images.unsplash.com/photo-1773639692148-41583a735a67?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OHx8dm9sbHliYWxsfGVufDB8fDB8fHww','Defense expert'),
(6,18,'Harsha Gupta',11,500,'https://plus.unsplash.com/premium_photo-1664300257528-fc310ffef734?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8dm9sbHliYWxsfGVufDB8fDB8fHww','All-round trainer'),

-- TABLE TENNIS
(7,19,'Ritik Mehra',12,500,'https://images.unsplash.com/photo-1659303388076-de1535159d6c?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8dGFibGUlMjB0ZW5uaXMlMjBjb2FjaHxlbnwwfHwwfHx8MA%3D%3D','Spin master'),
(7,20,'Sneha Kulkarni',10,450,'https://images.unsplash.com/photo-1615177981645-51107b202210?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Nnx8dGFibGUlMjB0ZW5uaXMlMjBjb2FjaHxlbnwwfHwwfHx8MA%3D%3D','Reflex trainer'),
(7,21,'Yash Thakur',8,400,'https://plus.unsplash.com/premium_photo-1664304787554-8191fb0832fb?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8dGFibGUlMjB0ZW5uaXMlMjBjb2FjaHxlbnwwfHwwfHx8MA%3D%3D','Beginner coach'),

-- SWIMMING
(8,22,'Priyank Nair',15,600,'https://plus.unsplash.com/premium_photo-1664476533965-dc3cf47c0957?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8c3dpbWluZyUyMGNvYWNofGVufDB8fDB8fHww','Freestyle expert'),
(8,23,'Ananya Iyer',10,550,'https://plus.unsplash.com/premium_photo-1661855484909-b76b427d0f9b?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8c3dpbW1pbmclMjBjb2FjaHxlbnwwfHwwfHx8MA%3D%3D','Endurance coach'),
(8,24,'Riya Sharma',9,500,'https://plus.unsplash.com/premium_photo-1661871676823-21f3d20b52fd?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8c3dpbW1pbmd8ZW58MHx8MHx8fDA%3D','Beginner trainer');