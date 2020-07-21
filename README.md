# SciGen.Report

<a href="https://scigen.report">SciGen.Report</a> is a website that allows users easily to share their results of complete or partial reproduction of scientific papers, either for positive or negative resutls.

### Dependencies:

The following PHP dependencies are required and we recomend to introduce with composer:
- PHPMailer ^6.0
- Hashids ^4.0 

We also use Clamp.js 0.5.1 (included with this code as minimal version, provided under WTFPL), available at https://github.com/josephschmitt/Clamp.js

### Observations:

- The config.php file defines the constants used in the code. The database constants need to be adjustsed to the values used.
- **NOTICE**: The base source code has been edited to hide potentially sensitive information that may compromise the security of The Service. Data that has been hidden include, but might not be limited to: database structure, hashing algorithms and hashing salt, email addresses, and access keys. If commiting new code, do not change these values.
