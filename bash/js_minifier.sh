#!/bin/bash

# This script minifies JavaScript files using UglifyJS.
# It is primarily intended for automatic post-build use, executed after compiling TypeScript,
# but can also be used to minify any JavaScript file.

# Minification is the process of reducing file size by removing unnecessary characters,
# which in turn improves website performance by decreasing loading times.
# This is especially useful for optimizing production files.

# How to use:
# To integrate this script into your build process, add it as a post-build step in your package.json file.
# Here's how:
# 	"scripts": {
# 		"build": "tsc",
# 		"postbuild": "bash ./path/to/script/postbuild_minifier.sh"
# 	}
# Once you've added this, the script will run automatically after executing npm run build,
# ensuring that your JavaScript files are minified without additional manual steps.

# Any needed module (NodeJS, UglifyJS) will be installed if needed.

# If you need to minify multiple files at once, consider using the Standalone version of the script,
# which allows for bulk processing of JavaScript files.

# Strict mode, stop the script if an error occurs
set -euo pipefail

# Project root dir
ROOT_DIR="$(cd "$(dirname "$0")" && cd .. && pwd)"

# Variables and functions import
source ${ROOT_DIR}/bash/functions.sh

#!--- Your path to the JS file to minify ---!#
FILE_NAME="${ROOT_DIR}/scripts/javascript/scripts.js"
FILE_BASE=$(basename "$FILE_NAME")

# File status check
info_message "Checking script"
if [ ! -f "$FILE_NAME" ]; then
	handle_error "Error: $FILE_BASE doesn't exist."
else
	useless_action_message "$FILE_BASE exists!"
fi
carriage_return_message

# NodeJS status check, install if not installed
info_message "Checking Node.js status..."
if ! command -v node &> /dev/null; then
	warning_message "Node.js isn't installed. Installing..."
	if [[ "$OSTYPE" == "linux-gnu"* ]]; then
		curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
		sudo apt-get install -y nodejs
	elif [[ "$OSTYPE" == "darwin"* ]]; then
		brew install node
	else
		handle_error "Operating system not supported for automatic Node.js installation.${CARRIAGE_RETURN}Please install Node.js manually from https://nodejs.org/"
	fi

	if ! command -v node &> /dev/null; then
		handle_error "Node.js installation failed. Please install it manually."
	fi

	info_message "Node.js installed successfully."
else
	useless_action_message "Node.js is already installed!"
fi
carriage_return_message

# UglifyJS status check, install if not installed
info_message "Checking UglifyJS status..."
if ! command -v uglifyjs &> /dev/null; then
	warning_message "UglifyJS is not installed. Installing..."
	npm install -g uglify-js
	if [ $? -ne 0 ]; then
		handle_error "UglifyJS installation failed. Please check your npm configuration."
	fi
	info_message "UglifyJS installed successfully."
else
	useless_action_message "UglifyJS is already installed!"
fi
carriage_return_message

# Minification process
info_message "Starting minification..."
uglifyjs "$FILE_NAME" -o "$FILE_NAME" --compress --mangle

if [ $? -eq 0 ]; then
	success_message "$FILE_BASE has been minified successfully."
else
	handle_error "Error during file minification."
fi
carriage_return_message

read -p "Press any key to continue..."
