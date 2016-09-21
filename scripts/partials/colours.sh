#!/bin/sh

# http://stackoverflow.com/a/5947802/571230

# Black        0;30     Dark Gray     1;30
# Red          0;31     Light Red     1;31
# Green        0;32     Light Green   1;32
# Brown/Orange 0;33     Yellow        1;33
# Blue         0;34     Light Blue    1;34
# Purple       0;35     Light Purple  1;35
# Cyan         0;36     Light Cyan    1;36
# Light Gray   0;37     White         1;37
#
# printf "Colour examples:\n"
# printf "\033[0;30mBlack\n"
# printf "\033[0;31mRed\n"
# printf "\033[0;32mGreen\n"
# printf "\033[0;33mOrange\n"
# printf "\033[0;34mBlue\n"
# printf "\033[0;35mPurple\n"
# printf "\033[0;36mCyan\n"
# printf "\033[0;37mLight Gray\n"
# printf "\033[1;30mDark Gray\n"
# printf "\033[1;31mLight Red\n"
# printf "\033[1;32mLight Green\n"
# printf "\033[1;33mYellow\n"
# printf "\033[1;34mLight Blue\n"
# printf "\033[1;35mLight Purple\n"
# printf "\033[1;36mLight Cyan\n"
# printf "\033[1;37mWhite\n"
# printf "\033[0mNo Colour\n"

# used to depict that something broke
COLOUR_RED='\033[0;31m'

# used to depict something was successful
COLOUR_GREEN='\033[0;32m'

# used for general informations
COLOUR_BLUE='\033[0;34m'

# used to prompt to action
COLOUR_ORANGE='\033[0;33m'

# default output colour
COLOUR_NC='\033[0m'

# printf "I ${COLOUR_RED}love${COLOUR_NC} Stack Overflow\n"
