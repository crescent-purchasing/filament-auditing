<?php

namespace CrescentPurchasing\FilamentAuditing\Actions;

use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use Illuminate\Support\Str;

readonly class FormatEvent
{
    private FilamentAuditingPlugin $plugin;

    public function __construct()
    {
        $this->plugin = FilamentAuditingPlugin::get();
    }

    public function __invoke(string $type): string
    {
        if ($this->plugin->formatsEvent()) {
            return $this->plugin->formatEvent($type);
        }

        return Str::of($type)
            ->headline()
            ->toString();
    }
}
