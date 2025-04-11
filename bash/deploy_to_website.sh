#!/bin/bash

# Mode strict, arrête le script en cas d'erreur
set -euo pipefail

# Project root dir
ROOT_DIR="$(cd "$(dirname "$0")" && cd .. && pwd)"

# Variables and functions import
source ${ROOT_DIR}/bash/functions.sh

MASTER_BRANCH="master"
DEPLOY_BRANCH="deploy"

info_message "Fetching the latest changes from origin..."
git fetch origin "$DEPLOY_BRANCH" || handle_error "Can't fetch '$DEPLOY_BRANCH' branch from origin."
carriage_return_message

info_message "Switching to branch '$MASTER_BRANCH'..."
git checkout "$MASTER_BRANCH" || handle_error "Can't switch to '$MASTER_BRANCH'."
carriage_return_message

info_message "Pulling the latest changes for '$MASTER_BRANCH'..."
git pull origin "$MASTER_BRANCH" || handle_error "Can't pull '$MASTER_BRANCH'."
carriage_return_message

info_message "Switching to branch '$DEPLOY_BRANCH'..."
git checkout "$DEPLOY_BRANCH" || handle_error "Can't switch to '$DEPLOY_BRANCH'."
carriage_return_message

info_message "Pulling the latest changes for '$DEPLOY_BRANCH'..."
git pull origin "$DEPLOY_BRANCH" || handle_error "Can't pull '$DEPLOY_BRANCH'."
carriage_return_message

info_message "Merging '$MASTER_BRANCH' into '$DEPLOY_BRANCH'..."
git merge "$MASTER_BRANCH" || handle_error "Merge failed. Fix conflicts manually, then reload the script."
carriage_return_message

info_message "Pushing '$DEPLOY_BRANCH' branch to origin..."
push_output=$(git push origin "$DEPLOY_BRANCH" 2>&1) || handle_error "Can't push '$DEPLOY_BRANCH' to github."
carriage_return_message

if echo "$push_output" | grep -q "Everything up-to-date"; then
  	warning_message "The repository is already up-to-date."
else
  	success_message "✅ Changes have been pushed successfully!"
  	success_message "Deployment launched!"
fi
carriage_return_message

info_message "Switching to branch '$MASTER_BRANCH'..."
git checkout "$MASTER_BRANCH" || handle_error "Can't switch to '$MASTER_BRANCH'."
carriage_return_message

read -p "Press any key to continue..."
