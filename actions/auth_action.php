<?php
session_start();
require_once '../db.php';

header('Content-Type: application/json');

if(['action'] == 'register') {
     = ->real_escape_string(['name']);
     = ->real_escape_string(['email']);
     = password_hash(['password'], PASSWORD_BCRYPT);

     = ->query("SELECT id FROM users WHERE email = ''");
    if(->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already exists']);
    } else {
        ->query("INSERT INTO users (name, email, password) VALUES ('', '', '')");
        echo json_encode(['success' => true]);
    }
}

if(['action'] == 'login') {
     = ->real_escape_string(['email']);
     = ['password'];

     = ->query("SELECT * FROM users WHERE email = ''");
    if(->num_rows > 0) {
         = ->fetch_assoc();
        if(password_verify(, ['password'])) {
            ['user_id'] = ['id'];
            ['user_name'] = ['name'];
            ['user_role'] = ['role'];
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid password']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
}
?>
