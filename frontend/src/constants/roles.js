/**
 * ============================================================================
 * constants/roles.js — Role Constants & Guards
 * ============================================================================
 * Centralizes all role strings and provides guard helpers.
 * These match the roles defined in the Laravel User model.
 * ============================================================================
 */

/** Role string constants — must match backend exactly */
export const ROLES = {
  ADMIN:   'admin',
  TEACHER: 'teacher',
  STUDENT: 'student',
};

/**
 * Returns true if the user object has the given role.
 * @param {object|null} user
 * @param {string} role
 * @returns {boolean}
 */
export function hasRole(user, role) {
  return user?.role === role;
}

export const isAdmin   = (user) => hasRole(user, ROLES.ADMIN);
export const isTeacher = (user) => hasRole(user, ROLES.TEACHER);
export const isStudent = (user) => hasRole(user, ROLES.STUDENT);
