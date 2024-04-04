<?php

use PHPUnit\Framework\TestCase;

class RegisterSecurityTest extends TestCase
{
    // Test for SQL Injection vulnerability
    public function testSqlInjectionVulnerability()
    {
        // Simulate a POST request with SQL Injection payload
        $_POST["username"] = "'; DROP TABLE users; --";
        $_POST["password"] = "password";
        $_POST["confirm_password"] = "password";

        ob_start();
        include 'register.php';
        $output = ob_get_clean();

        // Assert that the database table is not dropped due to SQL Injection
        $this->assertDatabaseTableExists('users');
    }

    // Test for Password Length vulnerability
    public function testPasswordLengthVulnerability()
    {
        // Simulate a POST request with a password less than 6 characters
        $_POST["username"] = "testuser";
        $_POST["password"] = "pass";
        $_POST["confirm_password"] = "pass";

        ob_start();
        include 'register.php';
        $output = ob_get_clean();

        // Assert that the page contains the password length error message
        $this->assertStringContainsString('Password must have at least 6 characters.', $output);
    }
}
