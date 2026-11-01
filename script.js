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
        const phone = $('input[name="phone"]').val().replace(/[+\-\s]/g, "");
        if (!/^[0-9]{10,15}$/.test(phone)) {
            event.preventDefault();
            alert("Please enter a valid phone number (10 to 15 digits).");
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