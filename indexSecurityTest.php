<?php

use PHPUnit\Framework\TestCase;

class IndexSecurityTest extends TestCase
{
    // Test for unauthorized access to message threads
    public function testUnauthorizedAccess()
    {
        // Simulate a request to the index page without logging in
        $_SESSION["loggedin"] = false;

        ob_start();
        include 'index.php';
        $output = ob_get_clean();

        // Assert that the index page redirects unauthorized users to the login page
        $this->assertStringContainsString('Location: login.php', implode(';', $http_response_header));
    }
}
