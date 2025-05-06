<?php

namespace CrescentPurchasing\FilamentAuditing\Contracts;

interface AuditSchemaContract
{
    public static function invoke(array $keys, array $values): array;
}
