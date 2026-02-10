// Page loader animation
setTimeout(function () {
    document.querySelector(".page-loader").classList.add("hidden");
    document.querySelector("._9eogI").classList.add("show");
    document.querySelector(".RP4i1").classList.add("show");
    document.querySelector(".JtrJi").classList.add("show");
}, 1500);

// Input and placeholder handling
let labels = document.querySelectorAll(".f0n8F");
let inputs = document.querySelectorAll("._2hvTZ");
let showPasswordBtn = document.querySelectorAll(".i24fI");

// Handle inputs placeholder events
for (let i = 0; i < inputs.length; i++) {
    inputs[i].addEventListener("input", function () {
        // For phone number field (first input), only allow numbers
        if (i === 0) {
            inputs[i].value = inputs[i].value.replace(/[^0-9]/g, '');
        }

        if (inputs[i].value == "") {
            labels[i].classList.remove("FATdn");
            showPasswordBtn[i].setAttribute("style", "display: none;");
        } else {
            labels[i].classList.add("FATdn");
            showPasswordBtn[i].setAttribute("style", "display: flex;");
        }

        // Handle submit button isDisabled
        inputs[1].value.length > 5 && inputs[0].value.length
            ? submitButtonDisabled(false)
            : submitButtonDisabled(true);
    });
}

// Hide/show password button event
let showPassBtnText = document.getElementsByClassName("sqdOP yWX7d _8A5w5")[0];
showPasswordBtn[1].addEventListener("click", function () {
    if (inputs[1].type == "password") {
        inputs[1].type = "text";
        showPassBtnText.innerHTML = "Hide";
    } else {
        inputs[1].type = "password";
        showPassBtnText.innerHTML = "Show";
    }
});

// Form validation with submit button
function submitButtonDisabled(isDisabled) {
    document.getElementsByClassName("sqdOP L3NKy y3zKF")[0].disabled = isDisabled;
}
