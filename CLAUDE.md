# CLAUDE.md

Guidance for Claude Code and other AI agents working in this repository.

## Project overview

`mailzeet-php` is the official PHP SDK for the MailZeet transactional email API. It is a standalone Composer package (PSR-4 autoloaded, no framework dependency) distributed via Packagist under `mailzeet/mailzeet-php`. PHP applications that need to send emails via MailZeet include this library and interact with it through the `MailZeet` entry-point class.

## Tech stack

- PHP 7.4, 8.0, 8.1, 8.2 (all supported)
- GuzzleHTTP 7.7+ (HTTP transport)
- PHPUnit 9.6 (testing)
- axazara/php-cs (code-style, wraps PHP-CS-Fixer)
- PHPStan level 4 (static analysis)
- insolita/unused-scanner (dead-dependency detection)

## Getting started

```bash
composer install
```

No `.env` file or runtime server is needed; this is a pure library. For development against a custom API endpoint, pass `devMode: true` and a custom `baseUrl` when constructing `MailZeet`.

## Common commands

| Task | Command |
|---|---|
| Test | `composer test` |
| Lint / format | `composer format` |
| Code-style dry-run | `composer sniff` |
| Static analysis | `composer analyse` |
| Unused dependency scan | `composer unused` |

## Architecture

The package has a flat, intentionally simple structure following the KISS principle:

- `src/MailZeet.php` — the single public entry point; accepts an API key, constructs the Guzzle wrapper, and exposes `send(Mail $mail): object`.
- `src/Objects/Mail.php` — fluent builder for an email payload (sender, recipients, cc, bcc, subject, html/text body, template ID, params, attachments, priority, language). Create via `Mail::make()`.
- `src/Objects/Address.php` — value object representing an email address with an optional name.
- `src/Configs/Config.php` — constants: `BASE_URL` (`https://api.mailzeet.com/v1`), `VERSION`, priority constants, `TIMEOUT`.
- `src/Utils/GuzzleWrapper.php` — thin wrapper around GuzzleHttpClient that adds the base URL and sends POST requests.
- `src/Helpers/RequestHelper.php` — trait consumed by `MailZeet`; maps HTTP status codes to typed exceptions.
- `src/Exceptions/` — one exception class per HTTP error scenario (400, 401, 403, 404, 406, 422, 503, 5xx).
- `tests/` — PHPUnit test suite; mirrors `src/` structure. Config uses `phpunit.xml.dist`; coverage output goes to `build/`.

## Conventions

- All code must pass `composer format` (php-cs-fixer via `axazara/php-cs`) before committing.
- Static analysis runs at PHPStan level 4 (`composer analyse`); do not lower this level.
- Every change requires new or updated PHPUnit test cases in `tests/`.
- The `Mail` object uses a fluent setter pattern — all setters return `self` (or `Mail`).
- `Address` objects must be passed to `Mail` setters; raw strings are not accepted (they throw `\InvalidArgumentException`).
- Dev mode is enabled by passing `devMode: true` to the `MailZeet` constructor; only in dev mode is a custom `baseUrl` honoured.
- CI runs tests against PHP 7.4 – 8.2, both `prefer-lowest` and `prefer-stable` dependencies, on Ubuntu and Windows.

## Git Conventions

### 1. Branch names

Enforced regex (`branch_name_pattern`):
```
^(feature|fix|hotfix|chore|docs|refactor|test|ci|perf|build|style)/[a-z0-9._-]+$
```

- Lowercase only, kebab-case after the prefix, **max 50 characters** total.
- Use the full word `feature/` — **never** `feat/` (the short `feat` form is only for commit message types).
- Include the ticket id when relevant: `feature/AXA-123-add-stripe` (the ticket id is lowercased to satisfy the pattern — e.g. `feature/axa-123-add-stripe`).
- **Never** use a `claude/` prefix or any prefix outside the allowed set.
- `main`, `release`, `staging` are permanent protected branches — never push to them directly.
- If a branch is misnamed, rename it before pushing: `git branch -m <old> <new>`.

### 2. Commit messages
Enforced regex (`commit_message_pattern`), applied to **every** commit:
```
^(feat|fix|docs|style|refactor|perf|test|build|ci|chore|revert)(\([^)]+\))?!?: .+
```
- Lowercase type, optional scope in parens, optional `!` for breaking changes, subject after `: `.
- Subject starts with a lowercase letter and has no trailing period.
- Examples: `feat(checkout): add Apple Pay support`, `fix(api): handle expired tokens`, `chore(deps): bump axios from 1.7.2 to 1.15.2`, `refactor!: drop Node 18 support`.
- Do not rewrite Dependabot commits — `chore(deps): bump X from a to b` is already enforced via `.github/dependabot.yml`.

### 3. Files that are always rejected
Never stage or commit:
- `.env`, `.env.*` (only `.env.example` and `.env.sample` are allowed), `**/.env`, `**/.env.*`
- Private keys: `**/id_rsa{,.pub}`, `**/id_dsa`, `**/id_ecdsa`, `**/id_ed25519`, `**/.ssh/id_*`
- Credentials: `**/.aws/credentials`, `**/credentials.json`, `**/service-account.json`, `**/firebase-adminsdk-*.json`, `**/secrets.{yml,yaml}`
- Extensions: `*.pem`, `*.key`, `*.p12`, `*.pfx`, `*.jks`, `*.keystore`, `*.ppk`, `*.asc`, `*.gpg`
- Any file larger than 100 MB (use git LFS)
If a secret is needed, use `.env.example` for env vars and an external secret manager for credentials.

### Pull requests targeting `main`, `release`, `staging`
All three are protected — a PR is required (direct push blocked):
- 1 approval, all conversations resolved, **squash or rebase merge only** (linear history enforced — no merge commits).
- Commits must be GPG- or SSH-signed. Signing is required for `main` (`required-signatures-main` ruleset).
- The PR **title** becomes the squash commit message and must match the commit-message regex above (enforced on all three branches).

**Required workflows run on PRs whose base is `main` only** (not `release`/`staging`): `Branch naming convention`, `PR title — Conventional Commits`, and `PR size labeler`.
If a check shows `Waiting for workflow to run` for over a minute, the third-party action is likely missing from the enterprise allowlist.

When the branch-naming or PR-title check fails, the baseline bot auto-posts rename/title suggestions, following the enforced regex patterns.
If the bot's suggestions are incorrect, edit the PR title or branch name to match the required format.

### Pre-push checklist
Before running `git push`:
1. Branch name matches the regex.
2. Every commit in `origin/main..HEAD` matches the commit pattern (`git log --format=%s origin/main..HEAD`).
3. No staged file is in the blocked paths/extensions list.
4. Commits are signed if the target is `main`.

If any check fails, fix it locally rather than letting the server reject the push.
