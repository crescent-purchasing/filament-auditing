<?php

return [
    'title' => 'Audit History',
    'audit_relation_title' => 'History',
    'owner_relation_title' => 'Auditing',
    'record_title' => 'Audit - :record (:id) @ :timestamp',
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
        'auditable_type' => 'Record type',
        'audited_fields' => 'Modified fields',
        'created_at' => 'Recorded at',
        'created_at_since' => 'Recorded',
        'event' => 'Event',
        'ip_address' => 'IP address',
        'query' => 'Advanced',
        'tags' => 'Tags',
        'url' => 'URL',
        'user_agent' => 'User agent',
        'user' => [
            'label' => 'Owner',
            'type_label' => 'Type',
            'id' => 'ID',
            'email' => 'Email',
            'summary' => [
                'type_direct' => ':relationship is :type',
                'type_inverse' => ':relationship is not :type',
                'value_direct' => ':relationship (:type) is :value',
                'value_inverse' => ':relationship (:type) is not :value',
            ],
        ],
        'id' => 'ID',
    ],
    'tabs' => [
        'label' => 'Audit data',
        'meta' => 'Metadata',
        'new' => 'New (After)',
        'old' => 'Old (Before)',
        'user' => 'User data',
    ],
];
