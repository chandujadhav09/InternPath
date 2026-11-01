INTERNPATH — ONLINE INTERNSHIP APPLICATION PORTAL
=================================================

PROJECT OVERVIEW
A responsive glassmorphism-style internship application website built with
HTML, CSS, JavaScript, jQuery and PHP.

FEATURES
- Personal and academic information form
- Internship domain and duration selection
- Technical skills text area with live character counter
- Resume upload (PDF, DOC, DOCX; maximum 2 MB)
- Client-side validation with JavaScript/jQuery
- Server-side validation with PHP
- Formatted application success/summary page
- Animated gradient ambience and glass-effect panels
- Responsive layout for desktop and mobile

FILES
index.php   Form, PHP validation, upload handling and success output
style.css   Animated glassmorphism design and responsive layout
script.js   jQuery interactions and client-side validation
uploads/    Uploaded resumes are stored here

PART A — RUN LOCALLY USING XAMPP
1. Download the ZIP and extract it.
2. Open XAMPP Control Panel.
3. Start Apache. MySQL is not needed for this version.
4. Copy the complete folder named online_internship_portal into:
   C:\xampp\htdocs\
5. Check that the uploads folder exists inside online_internship_portal.
   The folder includes an .htaccess protection file and an index.html placeholder.
6. Open a browser and visit:
   http://localhost/online_internship_portal/
7. Complete the form using sample information.
8. Upload a small sample PDF/DOC/DOCX file (under 2 MB).
9. Tick the confirmation checkbox and click Submit application.
10. Check that the formatted application summary appears.

IF RESUME UPLOAD DOES NOT WORK
- Confirm Apache is running.
- Confirm the uploads folder exists and is writable by the web server.
- Ensure the PHP Fileinfo extension is enabled (it is normally enabled in XAMPP).
- Check PHP upload_max_filesize and post_max_size settings in php.ini.
  Set both to at least 3M, save, and restart Apache.
- Use a PDF/DOC/DOCX file smaller than 2 MB.

PART B — UNDERSTAND THE CODE
1. HTML in index.php creates the form fields and page structure.
2. CSS in style.css creates the glass panels, gradients, animations and layout.
3. JavaScript checks the phone number and resume before submission.
4. jQuery updates the skills character counter and handles form interaction.
5. PHP receives POST data, validates it, checks the resume type/size,
   stores the uploaded file and displays the submitted values safely.

PART C — CLOUD HOSTING
Use a PHP-compatible hosting provider. Example: https://www.infinityfree.com/
1. Create an account and verify your email.
2. Create a hosting account and choose a free subdomain if offered.
3. Open the hosting control panel and its File Manager.
4. Open the web root folder, commonly named htdocs.
5. Upload index.php, style.css and script.js directly into htdocs.
6. Create an uploads folder inside htdocs.
7. Ensure PHP is permitted to write uploaded files to uploads.
8. Visit your assigned subdomain and test the form with a sample resume.
9. Include the working website URL in your assignment submission.

IMPORTANT LIMITATIONS / SECURITY
This academic demo displays the submitted application and stores the resume file,
but does not store application records in a database or send confirmation emails.
For a real public recruitment portal, add database storage, access controls,
CSRF protection, malware scanning, stricter upload validation and a privacy policy.
Do not upload real sensitive personal documents for a classroom demonstration.

ASSIGNMENT OBJECTIVE
To develop and host a web application using HTML, CSS, JavaScript, jQuery and PHP
that accepts internship applicant information, validates the data, and displays
a formatted confirmation page after successful submission.

FINAL PRESENTATION POLISH
-------------------------
- A unique application reference ID is shown on successful submission.
- The applicant can print the confirmation summary using the Print confirmation button.
- The selected resume filename and size are previewed before submission.
- The print stylesheet formats the confirmation for paper/PDF printing.
