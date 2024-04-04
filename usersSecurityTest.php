<?php

use PHPUnit\Framework\TestCase;

class UsersSecurityTest extends TestCase
{
    // Test for Cross-Site Request Forgery (CSRF) vulnerability
    public function testCsrfVulnerability()
    {
        // Simulate a POST request without CSRF token
        $_SERVER["REQUEST_METHOD"] = "POST";

        ob_start();
        include 'users.php';
        $output = ob_get_clean();

        // Assert that the page does not contain any form actions
        $this->assertStringNotContainsString('<form action=', $output);
    }

    // Test for Insecure Direct Object References (IDOR) vulnerability
    public function testIdorVulnerability()
    {
        // Simulate accessing another user's profile by setting the session ID
        $_SESSION['id'] = 1; // Assuming this is another user's ID

        ob_start();
        include 'users.php';
        $output = ob_get_clean();

        // Assert that the page does not display sensitive user information
        $this->assertStringNotContainsString('Name', $output);
    }
}
