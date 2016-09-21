default: build
build:
	@./scripts/install.sh
# npm install -g less
# compile
compile:
# lessc public/less/style.less public/css/style.css
clean:
	@./scripts/uninstall.sh
