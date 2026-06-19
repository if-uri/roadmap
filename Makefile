.PHONY: deploy help
help:
	@grep -E "^[a-zA-Z_-]+:.*?## .*$$" $(MAKEFILE_LIST) | awk "BEGIN{FS=\":.*?## \"}{printf \"  %-10s %s\\n\",\$$1,\$$2}"
deploy: ## Publish to roadmap.ifuri.com (Plesk)
	bash scripts/deploy-plesk.sh
