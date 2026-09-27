<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class NoteSummarizer implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'You summarize one note for its author. '
            .'Write a one-sentence summary, list the key points, '
            .'and suggest up to three short, lowercase tags.';
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'summary' => $schema->string()->required(),
            'key_points' => $schema->array()->items($schema->string())->required(),
            'suggested_tags' => $schema->array()->items($schema->string())->required(),
        ];
    }
}
