$(function () {
    const $form = $("#applicationForm");
    const $skills = $('textarea[name="skills"]');

    function updateCounter() {
        $(".counter").text($skills.val().length + " / 400 characters");
    }
    updateCounter();
    $skills.on("input", updateCounter);

    $("#resume").on("change", function () {
        const file = this.files[0];
        if (!file) {
            $("#filePreview").text("No resume selected yet.");
            return;
        }
        const sizeKB = Math.max(1, Math.round(file.size / 1024));
        $("#filePreview").text("Selected: " + file.name + " · " + sizeKB + " KB");
    });

    $form.on("submit", function (event) {
        const fullName = $('input[name="full_name"]').val().trim();
        if (!/^[\\p{L}]+(?:[ \'-][\\p{L}]+)*$/u.test(fullName)) {
            event.preventDefault();
            alert("Please enter a name using letters only. Spaces, hyphens and apostrophes are allowed.");
            $('input[name="full_name"]').focus();
            return;
        }

        const phone = $('input[name="phone"]').val().trim();
        if (!/^[6-9][0-9]{9}$/.test(phone)) {
            event.preventDefault();
            alert("Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8 or 9.");
            $('input[name="phone"]').focus();
            return;
        }
        const fileInput = $("#resume")[0];
        if (fileInput.files.length) {
            const file = fileInput.files[0];
            const allowed = ["pdf", "doc", "docx"];
            const ext = file.name.split(".").pop().toLowerCase();
            if (!allowed.includes(ext) || file.size > 2 * 1024 * 1024) {
                event.preventDefault();
                alert("Upload a PDF, DOC or DOCX resume smaller than 2 MB.");
                return;
            }
        }
        if (!$("#confirm").is(":checked")) {
            event.preventDefault();
            alert("Please confirm your information before submitting.");
            return;
        }
        $(".btn").prop("disabled", true).text("Submitting application...");
    });
});
