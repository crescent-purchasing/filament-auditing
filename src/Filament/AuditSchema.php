<?php

namespace CrescentPurchasing\FilamentAuditing\Filament;

use CrescentPurchasing\FilamentAuditing\Contracts\AuditSchemaContract;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Str;

class AuditSchema implements AuditSchemaContract
{
    public static function invoke(array $keys, array $values): array
    {
        $fields = [];

        foreach ($keys as $key) {

            $field = null;
            $content = $values[$key] ?? '';

            if (Str::isJson($content)) {
                $content = json_decode($content, true);
            }

            if (is_scalar($content)) {
                $field = Placeholder::make($key);
                $field->inlineLabel();
                $field->content($content);
            } else {
                $field = Textarea::make($key);
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
