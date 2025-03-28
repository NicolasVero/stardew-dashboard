#!/bin/bash

RED="\033[1;31m"
ORANGE="\033[1;33m"
GREEN="\033[1;32m"
CYAN="\033[1;36m"
GREY="\033[1;30m"
BLANK_SPACE="\033[0m"
CARRIAGE_RETURN="\n"

handle_error() {
  echo -e "${RED}❌ An error occured: $1 (╯°□°)╯︵ ┻━┻ ${BLANK_SPACE}"
  exit 1
}

soft_error_message() {
  echo -e "${RED}$1${BLANK_SPACE}"
}

warning_message() {
  echo -e "${ORANGE}$1 ¯\_(ツ)_/¯ ${BLANK_SPACE}"
}

success_message() {
  echo -e "${GREEN}$1 *\(^o^)/* ${BLANK_SPACE}"
}

info_message() {
  echo -e "${CYAN}$1${BLANK_SPACE}"
}

useless_action_message() {
  echo -e "${GREY}$1${BLANK_SPACE}"
}

carriage_return_message() {
  echo -e ""
}
