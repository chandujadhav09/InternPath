<?php

function h($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

$errors = [];
$success = false;
$data = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fields = [
        "full_name", "email", "phone", "usn",
        "college", "degree", "graduation_year",
        "domain", "duration", "skills"
    ];

    foreach ($fields as $field) {
        $data[$field] = trim($_POST[$field] ?? "");
    }

    // Name validation
    if ($data["full_name"] === "") {
        $errors[] = "Full name is required.";
    } elseif (!preg_match("/^[a-zA-Z\s.'-]+$/", $data["full_name"])) {
        $errors[] = "Name must contain letters only.";
    }

   // Email validation
$data["email"] = trim($data["email"]);

if ($data["email"] === "") {
    $errors[] = "Email address is required.";
} elseif (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address (example: name@gmail.com).";
}

    // Phone validation
    $phoneDigits = preg_replace('/[+\-\s]/', '', $data["phone"]);

    if (!preg_match('/^[0-9]{10,15}$/', $phoneDigits)) {
        $errors[] = "Enter a valid phone number containing 10 to 15 digits.";
    }

    // USN validation
    if (!preg_match('/^[A-Za-z0-9]{10}$/', $data["usn"])) {
        $errors[] = "USN must contain exactly 10 letters and numbers.";
    }

    // Required fields
    foreach ([
        "college", "degree", "graduation_year",
        "domain", "duration", "skills"
    ] as $field) {

        if ($data[$field] === "") {
            $errors[] = ucfirst(str_replace("_", " ", $field))
                      . " is required.";
        }
    }

    // Allowed values
    $allowedDegrees = [
        "B.E. / B.Tech", "B.Sc.", "BCA",
        "MCA", "M.Tech", "MBA", "Other"
    ];

    $allowedYears = array_map('strval', range(2026, 2032));

    $allowedDomains = [
        "Web Development",
        "Artificial Intelligence / Machine Learning",
        "Data Science",
        "Cybersecurity",
        "Cloud Computing",
        "Java Development",
        "Python Development",
        "UI / UX Design"
    ];

    $allowedDurations = [
        "1 month", "2 months", "3 months", "6 months"
    ];

    if (!in_array($data["degree"], $allowedDegrees, true)) {
        $errors[] = "Select a valid degree.";
    }

    if (!in_array($data["graduation_year"], $allowedYears, true)) {
        $errors[] = "Select a valid graduation year.";
    }

    if (!in_array($data["domain"], $allowedDomains, true)) {
        $errors[] = "Select a valid internship domain.";
    }

    if (!in_array($data["duration"], $allowedDurations, true)) {
        $errors[] = "Select a valid internship duration.";
    }

    if (strlen($data["skills"]) > 400) {
        $errors[] = "Skills must be 400 characters or fewer.";
    }

    if (($_POST["confirm"] ?? "") !== "yes") {
        $errors[] = "Please confirm that the information is correct.";
    }

    // Resume upload validation
    $resumeName = "";

    if (!isset($_FILES["resume"]) ||
        $_FILES["resume"]["error"] !== UPLOAD_ERR_OK) {

        $errors[] = "Please upload your resume (maximum 2 MB).";

    } else {

        $file = $_FILES["resume"];
        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

        if (!in_array($ext, ["pdf", "doc", "docx"], true)) {
            $errors[] = "Resume must be a PDF, DOC or DOCX file.";
        }

        if ($file["size"] <= 0 ||
            $file["size"] > 2 * 1024 * 1024) {

            $errors[] = "Resume must be larger than 0 bytes and smaller than 2 MB.";
        }

        if (!$errors) {

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($file["tmp_name"]);

            $validMimes = [
                "pdf" => ["application/pdf"],
                "doc" => [
                    "application/msword",
                    "application/CDFV2",
                    "application/x-ole-storage",
                    "application/octet-stream"
                ],
                "docx" => [
                    "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                    "application/zip",
                    "application/octet-stream"
                ]
            ];

            if (!in_array($mime, $validMimes[$ext] ?? [], true)) {
                $errors[] = "The uploaded file content does not match its file extension.";
            }
        }

        if (!$errors) {

            $uploadDir = __DIR__ . "/uploads/";

            if (!is_dir($uploadDir) &&
                !mkdir($uploadDir, 0755, true)) {

                $errors[] = "Upload folder could not be created.";
            }

            if (!$errors && !is_writable($uploadDir)) {
                $errors[] = "Upload folder is not writable by PHP.";
            }

            if (!$errors) {

                $resumeName = "resume_" .
                    bin2hex(random_bytes(8)) . "." . $ext;

                if (!move_uploaded_file(
                    $file["tmp_name"],
                    $uploadDir . $resumeName
                )) {

                    $errors[] = "Resume upload failed. Please try again.";
                }
            }
        }
    }

    $success = !$errors;

    if ($success) {
        $applicationId = "IP-" . date("Ymd") . "-"
                       . strtoupper(bin2hex(random_bytes(3)));
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>InternPath | Internship Application</title>

<link rel="stylesheet" href="style.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

</head>

<body>

<div class="ambient"></div>

<nav class="navbar">
    <a class="logo" href="index.php">
        <span class="logo-mark">i</span>
        intern<span>path</span>
    </a>

    <span class="nav-note">CAREER STARTS HERE</span>
</nav>

<main class="wrap">

<?php if ($success): ?>

<section class="success-card">

    <div class="check">✓</div>

    <p class="kicker">APPLICATION RECEIVED</p>

    <h1>You're one step closer!</h1>

    <p class="sub">
        Your internship application has been submitted successfully.
    </p>

    <div class="reference">
        <span>
            APPLICATION ID
            <strong><?= h($applicationId) ?></strong>
        </span>

        <span>
            STATUS <strong>Submitted</strong>
        </span>
    </div>

    <h2>Application summary</h2>

    <div class="summary">

        <div>
            <small>Applicant name</small>
            <b><?= h($data["full_name"]) ?></b>
        </div>

        <div>
            <small>Email address</small>
            <b><?= h($data["email"]) ?></b>
        </div>

        <div>
            <small>Phone number</small>
            <b><?= h($data["phone"]) ?></b>
        </div>

        <div>
            <small>University Seat Number (USN)</small>
            <b><?= h($data["usn"]) ?></b>
        </div>

        <div>
            <small>College / University</small>
            <b><?= h($data["college"]) ?></b>
        </div>

        <div>
            <small>Degree</small>
            <b><?= h($data["degree"]) ?></b>
        </div>

        <div>
            <small>Graduation year</small>
            <b><?= h($data["graduation_year"]) ?></b>
        </div>

        <div>
            <small>Internship domain</small>
            <b><?= h($data["domain"]) ?></b>
        </div>

        <div>
            <small>Preferred duration</small>
            <b><?= h($data["duration"]) ?></b>
        </div>

        <div class="wide">
            <small>Technical skills</small>
            <b><?= h($data["skills"]) ?></b>
        </div>

        <div class="wide">
            <small>Resume file</small>
            <b><?= h($_FILES["resume"]["name"]) ?></b>
        </div>

    </div>

    <div class="success-actions">

        <button class="btn print-btn"
                type="button"
                onclick="window.print()">
            Print confirmation ↗
        </button>

        <a class="btn secondary-btn" href="index.php">
            Submit another application →
        </a>

    </div>

</section>

<?php else: ?>

<section class="hero">

    <div>

        <p class="kicker">YOUR FUTURE, YOUR MOVE</p>

        <h1>
            Find your place.<br>
            <span>Build your future.</span>
        </h1>

        <p class="hero-copy">
            Take the first step toward your career.
            Complete the application form to express your interest
            in an internship opportunity.
        </p>

        <div class="hero-points">
            <span>✓ Simple application</span>
            <span>✓ Multiple domains</span>
            <span>✓ Student-friendly</span>
        </div>

    </div>

    <div class="hero-art">

        <div class="art-circle">✦</div>

        <div class="art-card">
            <span>INTERNSHIP APPLICATION</span>
            <strong>Learn.<br>Build.<br>Grow.</strong>
            <small>Turn your potential into experience.</small>
        </div>

    </div>

</section>

<section class="form-card">

    <div class="form-title">

        <div>
            <p class="kicker">APPLICATION FORM</p>
            <h2>Internship application</h2>
            <p class="sub">
                Fields marked <em>*</em> are mandatory.
            </p>
        </div>

        <div class="step">
            01 <small>/ 01</small>
        </div>

    </div>

    <?php if ($errors): ?>

    <div class="errors">

        <b>Please check these details:</b>

        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= h($e) ?></li>
            <?php endforeach; ?>
        </ul>

    </div>

    <?php endif; ?>

    <form id="applicationForm"
          method="POST"
          enctype="multipart/form-data">

        <h3>Personal information</h3>

        <div class="grid">

            <label>
                Full name <em>*</em>

                <input
                    type="text"
                    name="full_name"
                    placeholder="e.g. Chandrika Jadhav"
                    pattern="[A-Za-z\s.'-]+"
                    title="Name must contain letters only"
                    required
                    value="<?= h($data["full_name"] ?? "") ?>"
                >
            </label>
<label>
    Email Address <em>*</em>

    <input
        type="email"
        id="email"
        name="email"
        placeholder="example@gmail.com"
        maxlength="254"
        autocomplete="email"
        required
        value="<?= h($data["email"] ?? "") ?>"
    >

    <small class="hint">
        Enter a valid email address.
    </small>
</label>

            <label>
                Phone number <em>*</em>

                <input
                    type="tel"
                    name="phone"
                    placeholder="Enter mobile number"
                    required
                    value="<?= h($data["phone"] ?? "") ?>"
                >
            </label>

            <label>
                University Seat Number (USN) <em>*</em>

                <input
                    type="text"
                    name="usn"
                    id="usn"
                    placeholder="Enter your 10-character USN"
                    minlength="10"
                    maxlength="10"
                    pattern="[A-Za-z0-9]{10}"
                    title="Enter exactly 10 letters and numbers"
                    required
                    value="<?= h($data["usn"] ?? "") ?>"
                >

                <small class="hint">
                    Enter your 10-character university USN.
                </small>
            </label>

            <label>
                College / University <em>*</em>

                <input
                    name="college"
                    placeholder="Your institution name"
                    required
                    value="<?= h($data["college"] ?? "") ?>"
                >
            </label>

        </div>

        <h3 class="section-gap">Academic details</h3>

        <div class="grid">

            <label>
                Degree / Course <em>*</em>

                <select name="degree" required>
                    <option value="">Select your degree</option>

                    <?php foreach (
                        ["B.E. / B.Tech","B.Sc.","BCA","MCA",
                         "M.Tech","MBA","Other"] as $v
                    ): ?>

                    <option
                        <?= (($data["degree"] ?? "") === $v)
                            ? "selected" : "" ?>
                    >
                        <?= h($v) ?>
                    </option>

                    <?php endforeach; ?>

                </select>
            </label>

            <label>
                Expected graduation year <em>*</em>

                <select name="graduation_year" required>
                    <option value="">Select year</option>

                    <?php foreach (range(2026,2032) as $y): ?>

                    <option
                        <?= (($data["graduation_year"] ?? "") === (string)$y)
                            ? "selected" : "" ?>
                    >
                        <?= $y ?>
                    </option>

                    <?php endforeach; ?>

                </select>
            </label>

        </div>

        <h3 class="section-gap">Internship preferences</h3>

        <div class="grid">

            <label>
                Preferred domain <em>*</em>

                <select name="domain" required>
                    <option value="">Choose a domain</option>

                    <?php foreach (
                        ["Web Development",
                         "Artificial Intelligence / Machine Learning",
                         "Data Science",
                         "Cybersecurity",
                         "Cloud Computing",
                         "Java Development",
                         "Python Development",
                         "UI / UX Design"] as $v
                    ): ?>

                    <option
                        <?= (($data["domain"] ?? "") === $v)
                            ? "selected" : "" ?>
                    >
                        <?= h($v) ?>
                    </option>

                    <?php endforeach; ?>

                </select>
            </label>

            <label>
                Preferred duration <em>*</em>

                <select name="duration" required>
                    <option value="">Select duration</option>

                    <?php foreach (
                        ["1 month","2 months","3 months","6 months"] as $v
                    ): ?>

                    <option
                        <?= (($data["duration"] ?? "") === $v)
                            ? "selected" : "" ?>
                    >
                        <?= $v ?>
                    </option>

                    <?php endforeach; ?>

                </select>
            </label>

            <label class="wide">
                Technical skills <em>*</em>

                <textarea
                    name="skills"
                    rows="3"
                    maxlength="400"
                    placeholder="e.g. Python, Java, HTML, CSS, SQL"
                    required
                ><?= h($data["skills"] ?? "") ?></textarea>

                <small class="counter">0 / 400 characters</small>
            </label>

            <label class="wide">
                Upload resume <em>*</em>

                <input
                    type="file"
                    name="resume"
                    id="resume"
                    accept=".pdf,.doc,.docx"
                    required
                >

                <small class="hint">
                    Accepted formats: PDF, DOC, DOCX · Maximum size: 2 MB
                </small>

                <small class="file-preview"
                       id="filePreview"
                       aria-live="polite">
                    No resume selected yet.
                </small>

            </label>

        </div>

        <label class="consent">

            <input
                type="checkbox"
                id="confirm"
                name="confirm"
                value="yes"
                required
            >

            I confirm that the information provided is correct.

        </label>

        <button class="btn" type="submit">
            Submit application <span>→</span>
        </button>

        <p class="privacy">
            Demo application portal · Please do not upload sensitive personal documents.
        </p>

    </form>

</section>

<?php endif; ?>

</main>

<footer>
    © 2026 InternPath · Internship Application Portal
</footer>

<script src="script.js"></script>

</body>
</html>
