<?php

namespace CrescentPurchasing\FilamentAuditing\Actions;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Str;

readonly class GetAuditSchema
{
    /**
     * @return Component[]
     */
    public function __invoke(array $values, ?array $keys = null): array
    {
        $fields = [];

        $keys ??= array_keys($values);

        foreach ($keys as $key) {

            $field = null;
            $content = $values[$key] ?? '';

            if (Str::isJson($content)) {
                $content = json_decode($content, true);
            }

            if (is_scalar($content)) {
                $field = Placeholder::make($key);
                $field->translateLabel();
                $field->inlineLabel();
                $field->content($content);
            } else {
                $field = Textarea::make($key);
                $field->translateLabel();
                $field->rows(16);
                $field->readOnly();
                $field->dehydrated(false);
                $field->formatStateUsing(fn (): string => json_encode($content, JSON_PRETTY_PRINT));
                $field->extraInputAttributes(['class' => 'font-mono']);
            }

            $fields[] = $field;
        }

        return $fields;
    }
}
