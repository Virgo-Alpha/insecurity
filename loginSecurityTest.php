<?php

use PHPUnit\Framework\TestCase;

class LoginSecurityTest extends TestCase
{
    // Test if the login form is protected against CSRF attacks
    public function testCsrfProtection()
    {
        // Simulate a CSRF attack by sending a POST request with malicious data
        $maliciousData = http_build_query([
            'username' => 'attacker',
            'password' => 'malicious_password',
            'csrf_token' => 'malicious_csrf_token',
        ]);

        // Create a stream context to send the POST request
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'content' => $maliciousData,
            ],
        ];
        $context = stream_context_create($options);
        $result = file_get_contents('http://localhost/login.php', false, $context);

        // Assert that the login page rejects the request due to CSRF protection
        $this->assertStringContainsString('403 Forbidden', $http_response_header[0]);
    }

    // Test if the login form is protected against injection attacks
    public function testInjectionProtection()
    {
        // Attempt a SQL injection attack by injecting a malicious SQL query into the username field
        $_POST['username'] = "attacker' OR '1'='1";
        $_POST['password'] = 'malicious_password';

        ob_start();
        include 'login.php';
        $output = ob_get_clean();

        // Assert that the login page rejects the login attempt due to injection protection
        $this->assertStringContainsString('That username and password don\'t match :(', $output);
    }
}
