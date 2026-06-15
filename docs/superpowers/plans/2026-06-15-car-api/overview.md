# Car API Implementation Plan

> **For agentic workers:** Execute in dependency order. Keep commits small and use messages like `feat: add docker yii scaffold`.

**Goal:** Build and verify the full Dockerized Yii2/PostgreSQL car API from the saved spec.
**Architecture:** Yii2 basic-style app with explicit car controller, forms, DTOs, service, repository, entities, mapper, and assembler.
**Tech Stack:** PHP 8.3, Yii2, PostgreSQL, PHPUnit, Docker Compose.
**Spec:** `docs/superpowers/specs/2026-06-15-car-api-design.md`
**Context Pack:** `docs/superpowers/plans/2026-06-15-car-api/context-pack.md`
**Review Mode:** targeted per milestone

## Task Index

| Task | File | Risk | Depends on | Summary |
|------|------|------|------------|---------|
| 1 | `task-001-scaffold-docker-yii.md` | medium | none | Add Docker, Composer, Yii bootstrap, config, and git ignores |
| 2 | `task-002-car-schema-domain.md` | high | 1 | Add migrations, entities, mapper, repository contract/implementation |
| 3 | `task-003-forms-service-assembler.md` | high | 2 | Add tests and implement validation, command DTO, service, assembler |
| 4 | `task-004-http-readme-smoke.md` | high | 3 | Add controller/routes/error handling, README, and smoke verification |

## Verification Policy

- Before each commit, run the narrowest relevant Docker command that proves the milestone.
- Before final response, run full tests and HTTP smoke checks through Docker.
- Do not claim success without fresh command evidence.
