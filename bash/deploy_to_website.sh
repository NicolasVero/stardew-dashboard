#!/bin/bash

# Mode strict, arrête le script en cas d'erreur
set -euo pipefail

CYAN="\033[1;36m"
RED="\033[1;31m"
ORANGE="\033[1;33m"
GREEN="\033[1;32m"
CARRIAGE_RETURN="\033[0m"

MASTER_BRANCH="master"
DEPLOY_BRANCH="deploy"

handle_error() {
  echo -e "\n${RED}❌ An error occured : $1 (╯°□°)╯︵ ┻━┻ ${CARRIAGE_RETURN} \n"
  exit 1
}

warning_message() {
  echo -e "\n${ORANGE}$1 ¯\_(ツ)_/¯ ${CARRIAGE_RETURN}\n"
}

info_message() {
  echo -e "\n${CYAN}$1${CARRIAGE_RETURN}\n"
}

success_message() {
  echo -e "\n${GREEN}$1 *\(^o^)/* ${CARRIAGE_RETURN}\n"
}

info_message "Fetching the latest changes from origin..."
git fetch origin "$DEPLOY_BRANCH" || handle_error "Can't fetch '$DEPLOY_BRANCH' branch from origin."

info_message "Switching to branch '$MASTER_BRANCH'..."
git checkout "$MASTER_BRANCH" || handle_error "Can't switch to '$MASTER_BRANCH'."

info_message "Pulling the latest changes for '$MASTER_BRANCH'..."
git pull origin "$MASTER_BRANCH" || handle_error "Can't pull '$MASTER_BRANCH'."

info_message "Switching to branch '$DEPLOY_BRANCH'..."
git checkout "$DEPLOY_BRANCH" || handle_error "Can't switch to '$DEPLOY_BRANCH'."

info_message "Pulling the latest changes for '$DEPLOY_BRANCH'..."
git pull origin "$DEPLOY_BRANCH" || handle_error "Can't pull '$DEPLOY_BRANCH'."

info_message "Merging '$MASTER_BRANCH' into '$DEPLOY_BRANCH'..."
git merge "$MASTER_BRANCH" || handle_error "Merge failed. Fix conflicts manually, then reload the script."

info_message "Pushing '$DEPLOY_BRANCH' branch to origin..."
push_output=$(git push origin "$DEPLOY_BRANCH" 2>&1) || handle_error "Can't push '$DEPLOY_BRANCH' to github."

if echo "$push_output" | grep -q "Everything up-to-date"; then
  warning_message "The repository is already up-to-date."
else
  success_message "✅ Changes have been pushed successfully!"
  success_message "Deployment launched!"
fi

info_message "Switching to branch '$MASTER_BRANCH'..."
git checkout "$MASTER_BRANCH" || handle_error "Can't switch to '$MASTER_BRANCH'."

read -p "Press Enter to continue..."
