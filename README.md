# Add an AI Agent to Your Laravel 13 App (AI SDK, Tools & Memory)

Companion repository for the **Code with Antonio** video course · [Watch on YouTube](https://youtu.be/DYQ7VNI3ZzA).

Add an AI agent to your Laravel 13 app with the official AI SDK: tools over Eloquent, structured output, conversation memory and tests with fake().

## What you'll build

Notes gets an AI assistant that searches, summarizes and remembers

## Lessons

| # | Lesson | Video | Folder |
|---|---|---|---|
| 1 | An assistant that knows your notes | [0:00](https://youtu.be/DYQ7VNI3ZzA?t=0) | [lessons/01-an-assistant-that-knows-your-notes](lessons/01-an-assistant-that-knows-your-notes) |
| 2 | Install the AI SDK | [3:32](https://youtu.be/DYQ7VNI3ZzA?t=212) | [lessons/02-install-the-ai-sdk](lessons/02-install-the-ai-sdk) |
| 3 | Your first agent class | [6:26](https://youtu.be/DYQ7VNI3ZzA?t=386) | [lessons/03-your-first-agent-class](lessons/03-your-first-agent-class) |
| 4 | Give it tools | [8:48](https://youtu.be/DYQ7VNI3ZzA?t=528) | [lessons/04-give-it-tools](lessons/04-give-it-tools) |
| 5 | Summaries with a shape | [12:19](https://youtu.be/DYQ7VNI3ZzA?t=739) | [lessons/05-summaries-with-a-shape](lessons/05-summaries-with-a-shape) |
| 6 | A chat that remembers | [15:17](https://youtu.be/DYQ7VNI3ZzA?t=917) | [lessons/06-a-chat-that-remembers](lessons/06-a-chat-that-remembers) |
| 7 | Test it without spending tokens | [19:20](https://youtu.be/DYQ7VNI3ZzA?t=1160) | [lessons/07-test-it-without-spending-tokens](lessons/07-test-it-without-spending-tokens) |

## Requirements

- Laravel 13.x (13.32.0) · laravel/ai 1.0.0 (2026-09-23) · OpenAI `gpt-6-sol` · PHP 8.4 · Pest 5.2.1 · SQLite
- PHP, Composer and the Laravel installer (one command, see lesson 2) or Laravel Herd
- Node.js + npm

## Run the final app

```bash
cd app
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm install && npm run build
composer run dev
```

## Repository structure

```
README.md          this file
lessons/           one folder per lesson: goal, timestamp, commands and files exactly as in the video
app/               the final working project
assets/            thumbnail
```

## License

MIT — use it for anything, credit appreciated.

---
Generated on 2026-09-27 from the course scripts. Code with Antonio · AI tutor.