# Insecurity Web Application Security

## Problem Statement:
The "Insecurity" web application contains multiple vulnerabilities that could potentially compromise the security and integrity of user data. These vulnerabilities include SQL injection, Cross-Site Scripting (XSS), and lack of input validation, which could allow attackers to execute malicious code, manipulate the database, and access sensitive information.

## Vulnerabilities Found
1. **SQL Injection**: The application is vulnerable to SQL injection attacks due to the direct insertion of user input into SQL queries, making it possible for attackers to execute arbitrary SQL commands.
2. **Cross-Site Scripting (XSS)**: The application lacks proper output encoding, allowing attackers to inject malicious scripts into web pages viewed by other users, leading to session hijacking, cookie theft, and other attacks.
3. **Lack of Input Validation**: The application fails to validate user input properly, allowing malicious users to submit arbitrary data and potentially bypass authentication or manipulate application behavior.

## Solution Implemented
To address these vulnerabilities and enhance the security of the application, the following solutions were implemented in the safe-solution branch:

1. **Prepared Statements for SQL Queries**: All SQL queries were refactored to use prepared statements with parameterized queries, preventing SQL injection by separating data from commands.
2. **Output Sanitization**: User input and dynamic content displayed on web pages were properly sanitized using `htmlspecialchars()` function to prevent XSS attacks by escaping special characters.
3. **Input Validation**: Proper input validation was implemented to ensure that user-submitted data meets expected criteria, such as length, format, and type, thereby preventing injection attacks and ensuring data integrity.

## How to Use
To access the secure version of the application, switch to the safe-solution branch and deploy the updated code. Users can download the original "Insecurity" web application using the following command:
```bash
wget https://edshare.gcu.ac.uk/id/document/59386 -O insecurity.sh && bash insecurity.sh
```

## Additional Notes
Regular security audits and code reviews are recommended to identify and address any potential vulnerabilities in the application. It's crucial to stay updated with the latest security best practices and patches to protect against emerging threats.