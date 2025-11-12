<?php
return [
    'currency' => 'DH',
    'title' => 'Panneau d’administration',
    'logout' => 'Déconnexion',

    'sidebar' => [
        'title' => 'Admin',
        'licenses' => 'Licences',
        'dashboard' => 'Tableau de bord',
        'invoices' => 'Bons de commande',
        'users' => 'Utilisateurs',
        'settings' => 'Paramètres',
    ],

    'dashboard' => [
        'page_title' => 'Tableau de bord',
        'welcome_title' => 'Bienvenue dans l’espace administrateur 👋',
        'welcome_text' => 'Ici, vous pouvez gérer vos bons de commande, utilisateurs et bien plus encore.',
        'purchase_orders' => 'Bons de commande',
        'users' => 'Utilisateurs',
    ],

    'dashboard_title' => 'Tableau de bord',
    'change_password_title' => 'Changer le mot de passe',
    'new_password' => 'Nouveau mot de passe',
    'confirm_password' => 'Confirmer le mot de passe',
    'change_button' => 'Changer',

    'profile' => [
        'title' => 'Profil Administrateur',
        'page_title' => 'Mon Profil',
        'account_info' => 'Informations du compte',
        'name' => 'Nom',
        'email' => 'Email',
        'role' => 'Rôle',
        'created_at' => 'Date de création',
        'updated_at' => 'Dernière mise à jour',
        'back' => 'Retour',
        'edit' => 'Modifier le profil',
    ],

    'profile_edit' => [
        'title' => 'Modifier le profil',
        'page_title' => 'Profil Administrateur',
        'subtitle' => 'Modifier mes informations',
        'name' => 'Nom',
        'email' => 'Email',
        'new_password' => 'Nouveau mot de passe (optionnel)',
        'confirm_password' => 'Confirmer le mot de passe',
        'back' => 'Retour',
        'save' => 'Sauvegarder',
    ],

    'auth' => [
        'invalid_credentials' => 'Identifiants invalides.',
        'access_denied' => 'Accès refusé. Administrateur uniquement.',
        'password_changed' => 'Mot de passe changé avec succès.',
        'logout_success' => 'Déconnexion réussie.',

        'validation' => [
            'email_required' => 'L’adresse e-mail est obligatoire.',
            'email_invalid' => 'L’adresse e-mail n’est pas valide.',
            'password_required' => 'Le mot de passe est obligatoire.',
            'password_min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ],
    ],

    'invoice' => [
        'reference_exists' => 'Cette référence de commande existe déjà.',
        'created_success' => 'Bon de commande enregistré avec succès.',
        'updated_success' => 'Bon de commande mis à jour avec succès.',
        'deleted_success' => 'Bon de commande supprimé avec succès.',
        'approved_success' => 'Bon de commande approuvé avec succès.',
        'rejected_success' => 'Bon de commande rejeté avec succès.',

        'validation' => [
            'user_required' => "L'utilisateur est obligatoire.",
            'user_exists' => "L'utilisateur sélectionné n'existe pas.",
            'file_required' => 'Le fichier est obligatoire.',
            'file_type' => 'Le fichier doit être valide.',
            'file_mimes' => 'Le fichier doit être une image ou un PDF (jpg, jpeg, png, bmp, tiff, pdf).',
            'file_max' => 'Le fichier ne doit pas dépasser 5 Mo.',
            'status_required' => 'Le statut est obligatoire.',
            'status_invalid' => 'Le statut sélectionné est invalide.',
            'reference_commande_required' => 'La référence de commande est obligatoire.',
            'date_commande_required' => 'La date de commande est obligatoire.',
            'nom_fournisseur_required' => 'Le nom du fournisseur est obligatoire.',
            'commande_par_required' => 'Le champ "Commandé par" est obligatoire.',
            'commande_a_required' => 'Le champ "Commandé à" est obligatoire.',
            'montant_ht_required' => 'Le montant HT est obligatoire.',
            'montant_tva_required' => 'Le montant TVA est obligatoire.',
            'montant_ttc_required' => 'Le montant TTC est obligatoire.',
            'statut_required' => 'Le statut est obligatoire.',
            'user_id_required' => 'L’utilisateur est obligatoire.',
            'user_id_exists' => 'L’utilisateur sélectionné n’existe pas.',
        ],
    ],

    'user' => [
        'access_denied' => 'Accès refusé.',
        'name_required' => 'Le nom est obligatoire.',
        'email_required' => "L'email est obligatoire.",
        'email_invalid' => "L'email n'est pas valide.",
        'email_unique' => "Cet email est déjà utilisé.",
        'password_min' => 'Le mot de passe doit contenir au moins 6 caractères.',
        'password_confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        'profile_updated' => 'Profil mis à jour avec succès.',

        'validation' => [
            'name_required' => 'Le nom est obligatoire.',
            'email_required' => 'L’adresse e-mail est obligatoire.',
            'email_email' => 'Veuillez entrer une adresse e-mail valide.',
            'email_unique' => 'Cette adresse e-mail est déjà utilisée.',
            'password_required' => 'Le mot de passe est obligatoire.',
            'password_confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password_min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'role_required' => 'Le rôle est obligatoire.',
            'role_in' => 'Le rôle sélectionné est invalide.',
        ],

        'errors' => [
            'not_authorized' => 'Vous n’êtes pas autorisé à attribuer ce rôle.',
            'no_license_attached' => '❌ Aucune licence trouvée pour votre compte.',
            'admin_limit_reached' => '❌ Nombre maximal d’administrateurs (:max) atteint pour votre licence.',
            'user_limit_reached' => '❌ Nombre maximal d’utilisateurs (:max) atteint pour votre licence.',
            'total_limit_reached' => '❌ Vous ne pouvez pas créer plus de :max comptes avec votre licence actuelle.',
        ],

        'success' => [
            'created' => 'Utilisateur créé avec succès.',
            'updated' => 'Utilisateur modifié avec succès.',
            'deleted' => 'Utilisateur supprimé avec succès.',
            'permissions_updated' => 'Permissions mises à jour avec succès.',
        ],
    ],
];
