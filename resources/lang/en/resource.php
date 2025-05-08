<?php

return [
    'title' => 'Audit History',
    'record_title' => 'Snapshot - :record (:id) at :timestamp',
    'actions' => [
        'restore_audit' => [
            'label' => 'Restore',
            'restore_to_old' => 'Restore to old values?',
            'restore_from_values' => 'Restoring from Values',
            'restore_to_values' => 'Restoring to Values',
        ],
        'view' => [
            'auditable' => 'View record',
            'owner' => 'View owner',
            'title' => 'View :title',
        ],
    ],
    'relation' => [
        'title' => 'Audit History',
    ],
    'table' => [
        'columns' => [
            'created_at' => 'Recorded at',
            'created_at_since' => 'Recorded from',
        ],
    ],
];
