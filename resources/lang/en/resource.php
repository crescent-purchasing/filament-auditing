<?php

return [
    'actions' => [
        'view_user' => 'View User',
        'view_user_title' => 'View :title',
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
