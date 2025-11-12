<?php

return [
    'page_title' => 'Purchase Orders',
    'heading' => 'Purchase Orders',
    'back' => 'Back',
    'add_auto' => 'Add Automatically (via Image or File)',
    'reference' => 'Reference',
    'supplier' => 'Supplier',
    'date' => 'Date',
    'amount_ttc' => 'Total Amount (TTC)',
    'actions' => 'Actions',
    'view' => 'View',
    'edit' => 'Edit',
    'delete' => 'Delete',
    'delete_confirm' => 'Delete this order?',
    'no_invoice' => 'No purchase orders found.',

    'messages' => [
        'created' => 'Purchase order saved successfully!',
        'updated' => 'Purchase order updated successfully!',
        'deleted' => 'Purchase order deleted successfully!',
    ],

    'errors' => [
        'duplicate_reference' => 'This order reference already exists.',
        'not_found' => 'Purchase order not found.',
    ],

    'validation' => [
        'reference_commande_required' => 'Order reference is required.',
        'date_commande_required' => 'Order date is required.',
        'nom_fournisseur_required' => 'Supplier name is required.',
        'file_required' => 'A file is required.',
        'file_mimes' => 'The file must be one of: jpg, jpeg, png, bmp, tiff, pdf.',
        'file_max' => 'The file size must not exceed 5 MB.',
    ],

    'invoice_create' => [
        'title' => 'Create Purchase Order',
        'page_title' => 'New Purchase Order',

        'user_label' => 'User',
        'choose_user' => '-- Choose a User --',

        'status_label' => 'Status',
        'status' => [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ],

        'upload_label' => 'Upload File (Image or PDF)',
        'cancel' => 'Cancel',
        'save' => 'Save',
    ],

    'editt' => [
        'page_title' => 'Edit Purchase Order',
        'heading' => 'Edit Purchase Order',
        'user' => 'User',
        'reference' => 'Order Reference',
        'date' => 'Order Date',
        'supplier' => 'Supplier Name',
        'supplier_code' => 'Supplier Code',
        'ordered_by' => 'Ordered By',
        'ordered_to' => 'Ordered To',
        'amount_ht' => 'Amount (Excl. Tax)',
        'amount_tva' => 'VAT Amount',
        'amount_ttc' => 'Total Amount (Incl. Tax)',
        'status' => 'Status',
        'cancel' => 'Cancel',
        'update' => 'Update',
    ],

    'status' => [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ],

    'index' => [
        'title' => 'Purchase Orders',
        'page_title' => 'Purchase Orders Dashboard',
        'heading' => '📄 Purchase Orders',
        'back' => 'Back',
        'add_auto' => 'Add Automatically (via Image or File)',
        'reference' => 'Reference',
        'supplier' => 'Supplier',
        'date' => 'Date',
        'amount' => 'Total Amount',
        'status' => 'Status',
        'actions' => 'Actions',
        'view' => 'View',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'confirm_delete' => 'Are you sure you want to delete this purchase order?',
        'empty' => 'No purchase orders found.',
        'total' => 'Total',
    ],

    'show' => [
        'title' => 'Purchase Order #:id',
        'page_title' => 'Purchase Order #:id',
        'heading' => 'Purchase Order Details #:id',
        'details_title' => 'Purchase Order Details',
        'view_file' => 'View File',

        'fields' => [
            'user' => 'User',
            'email' => 'Email',
            'reference' => 'Order Reference',
            'date' => 'Order Date',
            'supplier_name' => 'Supplier Name',
            'supplier_code' => 'Supplier Code',
            'ordered_by' => 'Ordered By',
            'ordered_to' => 'Ordered To',
            'amount_ht' => 'Amount (Excl. Tax)',
            'amount_tva' => 'VAT Amount',
            'amount_ttc' => 'Total Amount (Incl. Tax)',
            'status' => 'Status',
            'created_at' => 'Created At',
            'file' => 'Attached File',
        ],

        'status' => [
            'approuvé' => 'Approved',
            'rejeté' => 'Rejected',
            'en_attente' => 'Pending',
        ],

        'actions' => [
            'approve' => 'Approve',
            'reject' => 'Reject',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'back' => 'Back',
            'confirm_delete' => 'Are you sure you want to delete this purchase order?',
        ],
    ],

    'confirm' => [
        'page_title' => 'Confirm Purchase Order Data',
        'heading' => 'Please Verify All Information Before Saving',
        'reference' => 'Order Reference',
        'date' => 'Order Date',
        'supplier' => 'Supplier Name',
        'supplier_code' => 'Supplier Code',
        'ordered_by' => 'Ordered By',
        'ordered_to' => 'Ordered To',
        'amount_ht' => 'Amount (Excl. Tax)',
        'amount_tva' => 'VAT Amount',
        'amount_ttc' => 'Total Amount (Incl. Tax)',
        'file' => 'Attached File',
        'user' => 'User',
        'status' => 'Status',
        'back' => 'Back',
        'confirm_save' => 'Confirm & Save',
        'check_before_save' => 'Please review all fields before confirming.',

        'original_file' => 'Original File',

        'errors' => [
            'reference_missing' => 'Order reference is missing.',
            'date_missing' => 'Order date is missing.',
            'supplier_missing' => 'Supplier name is missing.',
            'supplier_code_missing' => 'Supplier code is missing.',
            'commanded_by_missing' => 'Ordered By field is missing.',
            'commanded_to_missing' => 'Ordered To field is missing.',
            'montant_ht_missing' => 'Amount (Excl. Tax) is missing.',
            'montant_tva_missing' => 'VAT Amount is missing.',
            'montant_ttc_missing' => 'Total Amount (Incl. Tax) is missing.',
        ],

        'buttons' => [
            'back' => 'Back',
            'confirm_save' => 'Confirm & Save',
        ],
    ],
];
