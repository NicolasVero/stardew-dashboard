enum OS {
	mac = "mac",
	linux = "linux",
	windows = "windows"
}

const os_paths: Map<OS, string> = new Map([
	[OS.mac, "(~/.config/StardewValley/Saves/)."],
	[OS.linux, "(~/.steam/debian-installation/steamapps/compatdata/413150/pfx/drive_c/users/steamuser/AppData/Roaming/StardewValley/Saves/)."],
	[OS.windows, "(%AppData%/StardewValley/Saves/SaveName)."]
]);

function detect_os(): OS {
	const user_agent: string = window.navigator.userAgent.toLowerCase();

	if (user_agent.includes("mac")) {   
        return OS.mac;
    }

	if (user_agent.includes("linux")) {   
        return OS.linux;
    }

	return OS.windows;
}

function get_os_path(os: OS = OS.windows): string {
	return os_paths.get(os) || "";
}
