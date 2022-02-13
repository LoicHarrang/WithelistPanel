<?php

return [
    // -- permissions
    // acl/permissions/list.blade.php
    'permissions.list.title' => 'Liste des permissions',
    'permissions.list.heading' => 'Permissions',
    'permissions.list.subtitle' => 'Liste de toute les permissions. Aucun édition ou suppression possible.',
    'permissions.list.table.id' => 'ID',
    'permissions.list.table.name' => 'Identité',
    'permissions.list.table.description' => 'Description',
    'permissions.list.empty' => 'Aucune permissions.',
    // -- roles
    // acl/roles/edit.blade.php
    'roles.edit.heading' => 'Editer le groupe ":name"',
    'roles.edit.form.info.heading' => 'Informations',
    'roles.edit.form.info.id' => 'Identifiant',
    'roles.edit.form.info.id.description' => 'Une phrase courte, pour identifier le groupe.',
    'roles.edit.form.info.displayname' => 'Nom public',
    'roles.edit.form.info.displayname.description' => 'Nom du groupe montré aux utilisateurs.',
    'roles.edit.form.info.description' => 'Description',
    'roles.edit.form.info.description.description' => 'Description du groupe, afin de mieu l\'identifier.',
    'roles.edit.form.permissions.heading' => 'Permissions',
    'roles.edit.form.submit' => 'Editer le groupe',
    'roles.edit.danger.heading' => 'Attention:',
    'roles.edit.danger.delete.button' => 'Supprimer le groupe',
    'roles.edit.danger.delete.confirm' => 'Voulez vous vraiment supprimer le groupe ? Aucun retour en arrière possible.',
    'roles.edit.users.heading' => 'Utilisateur',
    'roles.edit.users.empty' => 'Aucun utilisateur dans le groupe.',
    // acl/roles/list.blade.php
    'roles.list.title' => 'Groupe',
    'roles.list.heading' => 'Groupe',
    'roles.list.add.button' => 'Creer un groupe',
    'roles.list.table.heading.name' => 'Identité',
    'roles.list.table.heading.members' => 'Membres',
    'roles.list.table.heading.actions' => 'Action',
    'roles.list.table.empty' => '<p><b>Aucun groupe.</b> Vous pouvez en créer un via le boutton.</p>
                            <p>Comme il n\'y a aucun groupe, les utilisateurs ont les permissions pas défaut.</p>',
    // acl/roles/new.blade.php
    'roles.add.title' => 'Creer un groupe',
    'roles.add.heading' => 'Creer un groupe',
    'roles.add.form.info.heading' => 'Informations',
    'roles.add.form.info.id' => 'Identifiant',
    'roles.add.form.info.id.description' => 'Une phrase courte, pour identifier le groupe.',
    'roles.add.form.info.displayname' => 'Nom public',
    'roles.add.form.info.displayname.description' => 'Nom du groupe montré aux utilisateurs.',
    'roles.add.form.info.description' => 'Description',
    'roles.add.form.info.description.description' => 'Description du groupe, afin de mieu l\'identifier.',
    'roles.add.form.permissions.heading' => 'Permissions',
    'roles.add.form.submit' => 'Créer le groupe',
    // -- Users
    // acl/users/edit.blade.php
    'users.edit.data.heading' => 'Informations et permissions',
    'users.edit.data.id' => 'Identifiant',
    'users.edit.data.steamid' => 'SteamID',
    'users.edit.data.email' => 'Adresse Email',
    'users.edit.data.disabled' => 'Compte désactivé',
    'users.edit.groups.heading' => 'Groupe',
    'users.edit.permissions.heading' => 'Permissions individuelles de l\'utilisateur',
    'users.edit.submit' => 'Editer l\'utilisateur',

];
