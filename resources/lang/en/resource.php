<?php

return [
    'title' => 'Audit History',
    'relation_title' => 'Audit History',
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
    'fields' => [
        'auditable_type' => 'Record Type',
        'audited_fields' => 'Audited fields',
        'created_at' => 'Recorded at',
        'created_at_since' => 'Recorded from',
        'event' => 'Event',
        'ip_address' => 'IP address',
        'query' => 'Advanced',
        'tags' => 'Tags',
        'url' => 'URL',
        'user_agent' => 'User agent',
        'user' => [
            'label' => 'Owner',
            'id' => 'ID',
            'email' => 'Email',
        ],
        'id' => 'id',
    ],
    'tabs' => [
        'label' => 'Audit data',
        'meta' => 'Meta data',
        'new' => 'New (After)',
        'old' => 'Old (Before)',
        'user' => 'User data',
    ],
];
