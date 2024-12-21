// Types explicites pour les réponses
interface AjaxResponse {
    code: string;
    html: Record<string, string>;
    players: { length: number }[];
}

interface FeedbackResponse {
    success: boolean;
    message: string;
}

function file_choice(event: Event): void {
    const input = event.target as HTMLInputElement;
    const new_filename: string = input.files ? input.files[0].name.substring(0, 12) : "";
    const filename_element: HTMLElement = document.getElementById("new-filename");

    if(filename_element !== null) {
        filename_element.innerHTML = new_filename;
    }

    toggle_loading(true);
    AJAX_send();
}

// Upload File AJAX
async function AJAX_send(): Promise<void> {
    const xml_upload = document.getElementById("save-upload") as HTMLInputElement;
    const file: File = xml_upload?.files?.[0];

    if(file === null) {
        alert("An error occurred while uploading the file. Please try again.");
        return;
    }

    const max_upload_size = await get_max_upload_size();
    const is_file_too_big: boolean = file.size > max_upload_size;
    const page_display: HTMLElement = document.getElementById("display");
    const landing_menu: HTMLElement = document.getElementById("landing_menu");
    const landing_page: string = document.getElementById("landing_page")?.outerHTML ?? "";

    if(landing_menu !== null) {
        landing_menu.outerHTML = "";
    }

    page_display.innerHTML = "";

    const form_data: FormData = new FormData();
    const xhr: XMLHttpRequest = new XMLHttpRequest();
    const url: string = get_site_root() + "/includes/get_xml_data.php";

    if(is_file_too_big) {
        form_data.append("save-upload", new File(["SizeException"], "Error_SizeException.xml"));
    } else {
        form_data.append("save-upload", file);
    }

    xhr.open("POST", url, true);

    xhr.onreadystatechange = function () {
        if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            const data: AjaxResponse = JSON.parse(xhr.responseText);
            const html: Record<string, string> = data.html;

            page_display.innerHTML = html["topbar"];
            
            if(data.code === "success") {
                page_display.innerHTML += landing_page;
                const players_count: number = data.players.length;

                for(let i = 0; i < players_count; i++) {
                    page_display.innerHTML += html["player_" + i];
                }

                initialize_player_swapper(players_count);
                initialize_settings();
                load_elements();
            } else {
                page_display.innerHTML += html["error_message"];
                load_error_page_items();
            }

            activate_feedback_ajax_trigger();
            toggle_visibility(current_section, false);
            toggle_loading(false);
        }
    };

    xhr.send(form_data);
}