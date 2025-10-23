-- ===============================
-- PERMISSIONS
-- ===============================
INSERT INTO `permissions` (`name`, `slug`, `created_by`, `created_at`, `updated_at`) VALUES
('Show User List', 'show-user-list', 1, NOW(), NOW()),
('Can Assign User Role', 'can-assign-user-role', 1, NOW(), NOW()),
('Show Permission List', 'show-permission-list', 1, NOW(), NOW()),
('Add Permission', 'add-permission', 1, NOW(), NOW()),
('Delete Permission', 'delete-permission', 1, NOW(), NOW()),
('Show Role List', 'show-role-list', 1, NOW(), NOW()),
('Create Role', 'create-role', 1, NOW(), NOW()),
('Edit Role', 'edit-role', 1, NOW(), NOW()),
('Delete Role', 'delete-role', 1, NOW(), NOW()),
('Can Assign Role Permission', 'can-assign-role-permission', 1, NOW(), NOW());

-- ===============================
-- ROLES
-- ===============================
INSERT INTO roles (name, slug, created_by, created_at, updated_at) VALUES
('Super Admin', 'super-admin', 1, NOW(), NOW()),
('Admin', 'admin', 1, NOW(), NOW());

-- ===============================
-- Role Permission
-- ===============================
INSERT INTO role_permission (id, role_id, permission_id, attached_by) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1),
(3, 1, 3, 1),
(4, 1, 4, 1),
(5, 1, 5, 1),
(6, 1, 6, 1),
(7, 1, 7, 1),
(8, 1, 8, 1),
(9, 1, 9, 1),
(10, 1, 10, 1);
