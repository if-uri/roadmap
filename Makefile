.PHONY: planfile test deploy serve help
PORT ?= 8200
help:
	@grep -E "^[a-zA-Z_-]+:.*?## .*$$" $(MAKEFILE_LIST) | awk "BEGIN{FS=\":.*?## \"}{printf \"  %-10s %s\\n\",\$$1,\$$2}"
serve: ## Serve roadmap.ifuri.com locally (PHP) on http://127.0.0.1:$(PORT)
	php -S 127.0.0.1:$(PORT) index.php
planfile: ## Regenerate 90-planfile.md from the shared execution backlog
	python3 scripts/build_planfile_page.py
test: ## Lint PHP and validate manifest, docs and internal links
	php -l index.php >/dev/null
	php -l config.php >/dev/null
	php -l lib/roadmap.php >/dev/null
	php scripts/check_site.php
deploy: ## Publish to roadmap.ifuri.com (Plesk)
	bash scripts/deploy-plesk.sh
