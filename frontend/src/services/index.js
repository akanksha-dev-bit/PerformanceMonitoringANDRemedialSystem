/**
 * ============================================================================
 * services/index.js — Service Registry
 * ============================================================================
 * Re-exports all services for clean, single-import access.
 *
 * Usage:
 *   import { studentService, quizService } from '@/services';
 * ============================================================================
 */

export { default as authService       } from './authService';
export { default as studentService    } from './studentService';
export { default as teacherService    } from './teacherService';
export { default as subjectService    } from './subjectService';
export { default as attendanceService } from './attendanceService';
export { default as markService       } from './markService';
export { default as performanceService} from './performanceService';
export { default as remedialService   } from './remedialService';
export { default as quizService       } from './quizService';
export { default as reportService     } from './reportService';
export { default as api               } from './api';
