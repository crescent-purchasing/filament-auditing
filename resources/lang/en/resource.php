<?php

return [
    'record_title' => 'Snapshot - :record (:id) at :timestamp',
    'actions' => [
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
