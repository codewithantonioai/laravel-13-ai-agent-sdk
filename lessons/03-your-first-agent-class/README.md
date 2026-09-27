# Lesson 3 · Your first agent class

▶ [Watch this lesson](https://youtu.be/DYQ7VNI3ZzA?t=386) (starts at 6:26)

## Goal

Lesson three. Our first agent class: the notes assistant.

## Commands and files (as shown in the video)

### Terminal · make agent

```bash
```

### `app/Ai/Agents/NotesAssistant.php` · agent stub

```php
<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Laravel\Ai\Providers\Tools\ProviderTool;
use Stringable;

class NotesAssistant implements Agent, Conversational, HasTools
{
    use Promptable;

    public function instructions(): Stringable|string
    {
        return 'You are a helpful assistant.';
    }

    public function messages(): iterable
    {
        return [];
    }

    public function tools(): iterable
    {
        return [];
    }
}
```

### `app/Ai/Agents/NotesAssistant.php` · agent instructions

```php
<?php

namespace App\Ai\Agents;

use App\Models\User;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Laravel\Ai\Providers\Tools\ProviderTool;
use Stringable;

class NotesAssistant implements Agent, Conversational, HasTools
{
    use Promptable;

    public function __construct(public User $user) {}

    public function instructions(): Stringable|string
    {
        return "You are a helpful assistant for the Notes app. "
            ."You help {$this->user->name} find and understand their own notes. "
            ."Answer only from their notes. If the notes don't say, say so. "
            ."Keep answers short.";
    }

    public function messages(): iterable
    {
        return [];
    }

    public function tools(): iterable
    {
        return [];
    }
}
```

### Terminal · tinker no tools

```bash
```

## Recap

So: make agent creates the class, with instructions, messages and tools. The user goes in the constructor. And without tools, the model knows nothing about your notes. Let's give it some.
