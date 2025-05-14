<?php

namespace CrescentPurchasing\FilamentAuditing\Actions;

use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use Illuminate\Support\Str;

readonly class FormatAuditableType
{
    private FilamentAuditingPlugin $plugin;

    public function __construct()
    {
        $this->plugin = FilamentAuditingPlugin::get();
    }

    public function __invoke(string $type): string
    {
        if ($this->plugin->formatsAuditableType()) {
            return $this->plugin->formatAuditableType($type);
        }

        return Str::of($type)
            ->classBasename()
            ->headline()
            ->toString();
    }
}
