.PHONY: test deploy help
help:
	@grep -E "^[a-zA-Z_-]+:.*?## .*$$" $(MAKEFILE_LIST) | awk "BEGIN{FS=\":.*?## \"}{printf \"  %-10s %s\\n\",\$$1,\$$2}"
test: ## Lint PHP and validate manifest, docs and internal links
	php -l index.php >/dev/null
	php -l config.php >/dev/null
	php -l lib/roadmap.php >/dev/null
	php scripts/check_site.php
deploy: ## Publish to roadmap.ifuri.com (Plesk)
	bash scripts/deploy-plesk.sh
