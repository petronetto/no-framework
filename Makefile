# COLORS
GREEN  := $(shell tput -Txterm setaf 2)
YELLOW := $(shell tput -Txterm setaf 3)
WHITE  := $(shell tput -Txterm setaf 7)
RESET  := $(shell tput -Txterm sgr0)

help:
	@echo ''
	@echo '==================================================='
	@echo '|${GREEN}     _____     _ _     _____             _       ${RESET}|'
	@echo '|${GREEN}    |  |  |___| | |___|   __|___ ___ ___| |_     ${RESET}|'
	@echo '|${GREEN}    |     | -_| | | . |   __|  _| -_|_ -|   |    ${RESET}|'
	@echo '|${GREEN}    |__|__|___|_|_|___|__|  |_| |___|___|_|_|    ${RESET}|'
	@echo '|${GREEN}                                                 ${RESET}|'
	@echo '==================================================='
	@echo '|   Available options                             |'
	@echo '==================================================='
	@echo '|${GREEN}    1) docker [params]                           ${RESET}|'
	@echo '|${GREEN}    2) artisan [params]                          ${RESET}|'
	@echo '|${GREEN}    3) composer [params]                         ${RESET}|'
	@echo '|${GREEN}    4) run-tests [optional params]               ${RESET}|'
	@echo '|                                                 |'
	@echo '==================================================='
	@echo '|   Available endpoints                           |'
	@echo '==================================================='
	@echo '|${GREEN}    API            -> http://localhost:8080      ${RESET}|'
	@echo '|${GREEN}    API Docs       -> http://localhost:3000      ${RESET}|'
	@echo '|${GREEN}    Sonar Qube     -> http://localhost:7000      ${RESET}|'
	@echo '==================================================='
	@echo ''

test1:
	@echo teste1
test2:
	@echo teste2