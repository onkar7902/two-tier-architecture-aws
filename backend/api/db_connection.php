<?php
// api/db_connection.php

require __DIR__ . '/../vendor/autoload.php';

use Aws\SecretsManager\SecretsManagerClient;
use Aws\Exception\AwsException;

/**
 * Get secure database connection with SSL
 *
 * @return PDO
 */
function getDatabaseConnection() {
    $region = 'ap-south-1';
    $secretName = 'dev-rds-creds';

    $client = new SecretsManagerClient([
        'version' => 'latest',
        'region' => $region
    ]);

    try {
        $result = $client->getSecretValue(['SecretId' => $secretName]);
        if (isset($result['SecretString'])) {
            $secret = json_decode($result['SecretString'], true);
            $host = $secret['host'];
            $db_name = $secret['dbname'] ?? 'hello_world';
            $username = $secret['username'];
            $password = $secret['password'];
        } else {
            throw new Exception("SecretString not found in secret value.");
        }
    } catch (AwsException $e) {
        echo "❌ Failed to retrieve secret: " . $e->getMessage();
        exit;
    }

    $sslCertPath = '/etc/pki/tls/certs/bundle.pem';

    if (!file_exists($sslCertPath)) {
        die("❌ SSL certificate not found at $sslCertPath\n");
    }

    $dsn = "mysql:host=$host;port=3306;dbname=$db_name;charset=utf8mb4";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_SSL_CA => $sslCertPath,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true
    ];

    return new PDO($dsn, $username, $password, $options);
}
?>