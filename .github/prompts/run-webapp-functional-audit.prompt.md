---
name: Run Webapp Functional Audit
description: "Launch the Webapp Functional Auditor agent to test a URL, crawl major pages, capture screenshots, generate a standardized report folder, and run approved database checks."
argument-hint: "Provide URL, login credentials/roles, priority flows, and DB check approval level."
agent: webapp-functional-auditor
---
Run a full webapp functional audit using the configured auditor agent.

Input from user:
- Target URL and environment
- Credentials and role accounts if available
- Priority flows or pages to validate
- Database checks scope (read-only by default)

Execution requirements:
- Follow the workflow in [../skills/webapp-functional-audit/SKILL.md](../skills/webapp-functional-audit/SKILL.md)
- Create a new timestamped report folder under `reports/webapp-functional-audit/`
- Use standardized templates from [../skills/webapp-functional-audit/assets/report-template.md](../skills/webapp-functional-audit/assets/report-template.md) and [../skills/webapp-functional-audit/assets/database-checks-template.md](../skills/webapp-functional-audit/assets/database-checks-template.md)
- Capture screenshots for failures and key checkpoints
- Run only approved read-only DB checks

Chat response must include:
- Coverage summary
- High-severity findings
- Blocked areas
- Output folder path
- DB-check completion status
