<?php

use PHPUnit\Framework\TestCase;

class new_messageSecurityTest extends TestCase
{
    // Test for Cross-Site Scripting (XSS) vulnerability
    public function testXssVulnerability()
    {
        // Simulate a POST request with malicious XSS payload
        $_POST["to"] = "<script>alert('XSS Attack!');</script>";
        $_POST["body"] = "<script>alert('XSS Attack!');</script>";

        ob_start();
        include 'new_message.php';
        $output = ob_get_clean();

        // Assert that the output does not contain the XSS payload
        $this->assertStringNotContainsString('<script>alert(\'XSS Attack!\');</script>', $output);
    }

    // Test for Injection Attack vulnerability
    public function testInjectionVulnerability()
    {
        // Simulate a POST request with SQL Injection payload
        $_POST["to"] = "1; DROP TABLE messages;";
        $_POST["body"] = "1; DROP TABLE messages;";

        ob_start();
        include 'new_message.php';
        $output = ob_get_clean();

        // Assert that the database table is not dropped due to SQL Injection
        $this->assertDatabaseTableExists('messages');
    }
}
