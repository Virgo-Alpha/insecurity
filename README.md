# Insecurity Web Application

## Introduction

This is the README for the Insecurity web application. Insecurity is a web application developed to demonstrate various security vulnerabilities and the importance of implementing proper security measures.

## Problem Statement

The Insecurity web application was developed with several security vulnerabilities that needed to be addressed. These vulnerabilities included but were not limited to:

- Brute Force Attacks
- Cross-Site Scripting (XSS)
- SQL Injection
- Cross-Site Request Forgery (CSRF)
- Privilege Escalation
- Insecure Direct Object References (IDOR)

## Vulnerability Management Pipeline

To address the vulnerabilities in the Insecurity web application, a vulnerability management pipeline was established on the safe-solution branch of this repository. This pipeline consists of the following stages:

1. **Discovery**: Identifying vulnerabilities through code security reviews, automated testing, and responsible disclosure programs.
2. **Reproduction**: Reproducing vulnerabilities in a staging or System Integration Testing (SIT) environment to understand their impact.
3. **Ranking and Triage**: Ranking vulnerabilities using the Common Vulnerability Scoring System (CVSS) to prioritize fixes.
4. **Fix Management**: Implementing fixes for identified vulnerabilities, considering business-centric metrics.
5. **Deployment and Release**: Deploying fixed versions of the application and monitoring for any new vulnerabilities.

## Implemented Solutions

### Discovery

- Conducted code security reviews and automated testing to identify vulnerabilities.
- Utilized responsible disclosure programs such as bug bounty programs and internal red/blue teams.

### Reproduction

- Established a staging environment mimicking the production environment for efficient vulnerability reproduction.
- Implemented automated testing suites to validate fixes and prevent regression.

### Ranking and Triage

- Utilized the Common Vulnerability Scoring System (CVSS) to rank vulnerabilities based on severity.
- Developed custom scoring algorithms to include risks specific to the application's architecture.

### Fix Management

- Implemented permanent, application-wide solutions for identified vulnerabilities.
- Ensured each closed security bug had a regression test shipped with it to prevent future regressions.

### Deployment and Release

- Hosted the application on GitHub and secured it with automated tests using GitHub Actions.
- Utilized AI-powered testing tools to automate security testing and analyze results.

## Running Unit Tests

To run the unit tests for the Insecurity web application, follow these steps:

1. Navigate to the project directory.
2. Run the PHPUnit command:

```bash
./vendor/bin/phpunit [SecurityTestName].php
```

Feel free to further customize or expand this README as needed. If you have any questions or need additional information, please let me know!

## How to Use
To access the secure version of the application, switch to the safe-solution branch and deploy the updated code. Users can download the original "Insecurity" web application using the following command:
```bash
wget https://edshare.gcu.ac.uk/id/document/59386 -O insecurity.sh && bash insecurity.sh
```
When prompted, please enter your password.

You will be presented with the Insecurity web application in Firefox/any other browser(Direct URL: http://targets.local/insecurity/login.php).

## Additional Notes
Regular security audits and code reviews are recommended to identify and address any potential vulnerabilities in the application. It's crucial to stay updated with the latest security best practices and patches to protect against emerging threats.