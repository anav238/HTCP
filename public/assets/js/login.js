let developerLink = document.querySelector("#developer");
developerLink.addEventListener("click", showPopup);

function showPopup() {
    developerLink.removeEventListener("click", showPopup);
    document.body.insertAdjacentHTML("afterbegin", "<div class=\"popup\"><div class=\"popup-container\"><div class=\"popup-container-header\">Application Login</div><div class=\"popup-container-text\"></div><div class=\"popup-container-footer\"><a class=\"button button-blue\" id=\"close\">Close</a></div></div></div>");

    let popup = document.querySelector(".popup");
    let popupContent = popup.querySelector(".popup-container .popup-container-text");
    let buttons = popup.querySelector(".popup-container .popup-container-footer");
    let closeButton = buttons.querySelector("#close");

    popup.style.display = "flex";
    closeButton.addEventListener("click", closeButtonEvent);

    function closeButtonEvent() {
        popup.style.display = "none";
        popup.remove();
        developerLink.addEventListener("click", showPopup);
    }

    popupContent.insertAdjacentHTML("afterbegin", "\
    Fill in the form below to get your access token.<br />\
    <a href=\"https://app.swaggerhub.com/apis/htc53/HTCP/1.0.0\" target=\"_blank\" rel=\"noopener noreferrer\">Go to API documentation.</a>\
    <form id=\"token\">\
        <label for=\"email\">Email address</label>\
        <input name=\"email\" id=\"email\" type=\"email\" required>\
        \
        <label for=\"name\">Name</label>\
        <input name=\"name\" id=\"name\" type=\"text\" required>\
    </form>\
    ");

    let form = popupContent.querySelector("form");
    buttons.insertAdjacentHTML("afterbegin", "<button type=\"submit\" form=\"token\" class=\"button button-green\">Get Token</button>");
    submitButton = buttons.querySelector("button[type=submit]");
    form.querySelector("#email").focus();

    form.addEventListener("submit", (e) => {
        e.preventDefault();
        let email = form.querySelector("#email").value;
        let name = form.querySelector("#name").value;
        popup.classList.add("popup-loading");
        form.remove();
        submitButton.remove();
        popupContent.innerHTML = "Retrieving access token...";
        fetch("/api/applications?email=" + email + "&name=" + name, {
            method: "POST"
        })
        .then(response => response.json())
        .then(data => {
            popup.classList.remove("popup-loading");
            popupContent.innerHTML = "Your access token is:<div class=\"token\">" + data["Access Token"] + "</div><div class=\"form-data\">" + email + "<br />" + name + "</div><a href=\"https://app.swaggerhub.com/apis/htc53/HTCP/1.0.0\" target=\"_blank\" rel=\"noopener noreferrer\">Go to API documentation.</a>";
        });
    });
}