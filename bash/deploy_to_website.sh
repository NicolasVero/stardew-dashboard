#!/bin/bash

# Mode strict, arrête le script en cas d'erreur
set -euo pipefail

CYAN="\033[1;36m"
RED="\033[1;31m"
ORANGE="\033[1;33m"
GREEN="\033[1;32m"
CARRIAGE_RETURN="\033[0m"

handle_error() {
  echo -e "\n${RED}❌ An error occured : $1 (╯°□°)╯︵ ┻━┻ ${CARRIAGE_RETURN} \n"
  exit 1
}

info_message() {
  echo -e "\n${CYAN}$1${CARRIAGE_RETURN}\n"
}

success_message() {
  echo -e "\n${GREEN}$1 *\(^o^)/* ${CARRIAGE_RETURN}\n"
}

warning_message() {
  echo -e "\n${ORANGE}$1 ¯\_(ツ)_/¯ ${CARRIAGE_RETURN}\n"
}

info_message "Fetching the latest changes from origin..."
git fetch origin deploy || handle_error "Can't fetch 'deploy' branch from origin."

info_message "Switching to branch 'master'..."
git checkout master || handle_error "Can't switch to 'master'."

info_message "Pulling the latest changes for 'master'..."
git pull origin master || handle_error "Can't pull 'master'."

info_message "Switching to branch 'deploy'..."
git checkout deploy || handle_error "Can't switch to 'deploy'."

info_message "Merging 'master' into 'deploy'..."
git merge master || handle_error "Merge failed. Fix conflicts manually, then reload the script."

info_message "Pushing 'deploy' branch to origin..."
push_output=$(git push origin deploy 2>&1) || handle_error "Can't push 'deploy' to github."

if echo "$push_output" | grep -q "Everything up-to-date"; then
  warning_message "The repository is already up-to-date."
else
  success_message "✅ Changes have been pushed successfully!"
  success_message "Deployment launched!"
fi

read -p "Press Enter to continue..."