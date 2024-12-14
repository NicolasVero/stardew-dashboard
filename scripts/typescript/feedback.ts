function activate_feedback_ajax_trigger():void {
    const triggers = document.querySelectorAll(".feedback-opener");

    triggers.forEach(trigger => {
        trigger.addEventListener("click", () => {
            const existing_window = document.querySelector(".feedback-panel");
            hide_all_sections();
            
            if(existing_window) {
                toggle_visibility(existing_window as HTMLElement, true);
            } else {
                feedback_form_creation();
            }
        });
    });
}

// Create feedback form
function feedback_form_creation():void {
    const xml_upload = document.querySelector("body");

    fetch("./includes/functions/display_panels_functions.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            "action": "display_feedback_panel"
        })
    })
    .then(response => response.text())
    .then(data => {
        const temp_container = document.createElement("div");
        current_section = document.querySelector(".feedback-panel");
        temp_container.innerHTML = data;

        while(temp_container.firstChild) {
            xml_upload?.appendChild(temp_container.firstChild);
        }


        feedback_custom_radio();
        activate_feedback_form();
        activate_close_buttons(".exit-feedback", ".feedback-panel");
    })
    .catch(error => console.error("Error:", error));
}

function activate_feedback_form():void {
    const form = document.getElementById("feedback_form") as HTMLFormElement;
    form?.addEventListener("submit", (event) => {
        event.preventDefault();
        const formData = new FormData(form);

        fetch("./includes/sendmail.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then((data: FeedbackResponse) => {
            const alert_message = data.success ? data.message : "Error submitting form: " + data.message;
            alert(alert_message);
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred while submitting the form.");
        });
    });
}

function feedback_custom_radio():void {
    const feedback_fake_radios = document.querySelectorAll(".feedback_custom_radio");
    const feedback_real_radios = document.querySelectorAll(".feedback_real_radio");

    feedback_fake_radios.forEach(fake_radio => {
        const span_topic = fake_radio.parentElement!;
        
        span_topic.addEventListener("click", () => {
            const real_radio = fake_radio.previousElementSibling as HTMLInputElement;

            if(real_radio && real_radio.type === "radio") {
                real_radio.checked = true;
                real_radio.dispatchEvent(new Event("change"));
            }
        });
    });

    feedback_real_radios.forEach(real_radio => {
        real_radio.addEventListener("change", () => {
            feedback_fake_radios.forEach(fake_radio => {
                fake_radio.classList.add("topic_not_selected");
            });

            const fake_radio = real_radio.nextElementSibling as HTMLElement;
            
            if(fake_radio && fake_radio.tagName === "IMG") {
                if((real_radio as HTMLInputElement).checked) {
                    fake_radio.classList.remove("topic_not_selected");
                } else {
                    fake_radio.classList.add("topic_not_selected");
                }
            }
        });
    });
}