# Lesson 2 · Install the AI SDK

▶ [Watch this lesson](https://youtu.be/DYQ7VNI3ZzA?t=212) (starts at 3:32)

## Goal

Lesson two. Install the AI SDK, add an API key, and send our very first prompt.

## Commands and files (as shown in the video)

### Terminal · composer require

```bash
composer require laravel/ai
```

### Terminal · vendor publish

```bash
php artisan vendor:publish --provider="Laravel\Ai\AiServiceProvider"
```

### Terminal · migrate

```bash
php artisan migrate
```

### `.env.example` · env config

```ini
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="\${APP_NAME}"

OPENAI_API_KEY=sk-proj-••••••••
```

### Terminal · tinker first prompt

```bash
use function Laravel\Ai\agent;
$agent = agent(instructions: 'You are a helpful assistant for a notes app.');
$response = $agent->prompt('Suggest a title for a pop filter note.'); $response->text
$response->meta->model
```

## Recap

So: require, publish and migrate install the SDK. The API key lives in the env file, and never in the repository. And the agent function gives you a quick prompt from Tinker. Now, a real agent class.
