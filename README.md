# SciGen.Report

<a href="https://scigen.report">SciGen.Report</a> is a website that allows users easily to share their results of complete or partial reproduction of scientific papers, either for positive or negative resutls. You can submit a short comment together with a summary of your replication results of any published research, through a simple light form, so everyone can know about any extra care needed to reproduce results! And you may also use it to appeal to what you have done and show your expertise with more than just your publications!

SciGen.Report is **not**:
- A journal
- Peer-reviewed
- A discussion forum

SciGen.Report *is*:
- Post peer-review
- Open Access to submit and read submissions
- Objective - You must state your result (success or not and to what degree) with optional comments (currently limited to 500 characters)
- Flexible - You can edit or delete your comments if you want/need in the future
- Non-restrictive - Just make your review clear, and every field is welcome!

SciGen.Report has its core values in **diversity**, **integrity**, and **transparency**. We welcome various contributions from various researchers, from students who are reproducing protocols as part of their studies to long-career PIs delving inot a complex project. Feel free to reach us if you have any need you think we could help.

### Dependencies

The following PHP dependencies are required and we recomend to introduce with composer:
- PHPMailer ^6.0
- Hashids ^4.0 

We also use Clamp.js 0.5.1 (included with this code as minimal version, provided under WTFPL), available at https://github.com/josephschmitt/Clamp.js

### Observations

- The config.php file defines the constants used in the code. The database constants need to be adjustsed to the values used.
- **NOTICE**: The base source code has been edited to hide potentially sensitive information that may compromise the security of The Service. Data that has been hidden include, but might not be limited to: database structure, hashing algorithms and hashing salt, email addresses, and access keys. If commiting new code, do not change these values.

## SciGen.Report Open Innitiative Contributors

SciGen.Report is an open source project maintained by CJS Inc. under Mozilla Public License 2.0. Contributors that make or have made it possible are listed below, with major contributions acknowledged in paranthesis:

- Cassio Amorim (Creator)
- Veethika Mishra (UX support)
- Ryan Davies (UX support, marketing advice)
- Helena Pérez Valle (UX support, scoring advice)
- Giuliano Maciocci (UX support, vizualization advice)
