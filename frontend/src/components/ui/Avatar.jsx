/**
 * ============================================================================
 * components/ui/Avatar.jsx — User Avatar Component
 * ============================================================================
 * Renders initials-based avatar with gradient background.
 * Matches the .nb-avatar style from app.blade.php.
 * ============================================================================
 */

import { cn } from '@/utils/cn';
import { getInitials } from '@/utils/format';

const sizes = {
  xs: 'w-6 h-6 text-[9px]',
  sm: 'w-8 h-8 text-xs',
  md: 'w-10 h-10 text-sm',
  lg: 'w-12 h-12 text-base',
  xl: 'w-16 h-16 text-lg',
};

function Avatar({ name, src, size = 'sm', className }) {
  if (src) {
    return (
      <img
        src={src}
        alt={name || 'Avatar'}
        className={cn('rounded-full object-cover flex-shrink-0', sizes[size], className)}
      />
    );
  }

  return (
    <div
      className={cn(
        'rounded-full flex items-center justify-center flex-shrink-0 font-bold',
        'bg-gradient-to-br from-[#6C5CE7] to-[#8B5CF6] text-white',
        'shadow-[0_2px_8px_rgba(108,92,231,0.3)]',
        sizes[size],
        className
      )}
    >
      {getInitials(name)}
    </div>
  );
}

export default Avatar;
