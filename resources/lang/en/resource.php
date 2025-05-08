<?php

return [
    'record_title' => 'Snapshot - :record (:id) at :timestamp',
    'actions' => [
        'restore_audit' => [
            'label' => 'Restore',
            'restore_to_old' => 'Restore to old values?',
            'restore_from_values' => 'Restoring from Values',
            'restore_to_values' => 'Restoring to Values',
        ],
        'view_user' => 'View User',
        'view_user_title' => 'View :title',
        'view_auditable' => 'View Record',
        'view_auditable_title' => 'View :title',
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
