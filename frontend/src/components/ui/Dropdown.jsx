/**
 * ============================================================================
 * components/ui/Dropdown.jsx — Dropdown Menu Component
 * ============================================================================
 * Generic dropdown used for profile menu, action menus, etc.
 * Props:
 *   trigger:  ReactNode — element that opens the dropdown
 *   items:    [{ label, icon?, onClick?, href?, danger? }]
 *   align:    'left'|'right'
 * ============================================================================
 */

import { useState, useRef, useEffect } from 'react';
import { AnimatePresence, motion }     from 'motion/react';
import { cn } from '@/utils/cn';

function Dropdown({ trigger, items = [], align = 'right', className }) {
  const [open, setOpen] = useState(false);
  const ref = useRef(null);

  // Close when clicking outside
  useEffect(() => {
    if (!open) return;
    const handler = (e) => {
      if (ref.current && !ref.current.contains(e.target)) setOpen(false);
    };
    document.addEventListener('mousedown', handler);
    return () => document.removeEventListener('mousedown', handler);
  }, [open]);

  return (
    <div ref={ref} className={cn('relative', className)}>
      {/* Trigger */}
      <div onClick={() => setOpen((o) => !o)} className="cursor-pointer">
        {trigger}
      </div>

      {/* Menu */}
      <AnimatePresence>
        {open && (
          <motion.div
            initial={{ opacity: 0, y: -8, scale: 0.97 }}
            animate={{ opacity: 1, y:  0, scale: 1    }}
            exit={{   opacity: 0, y: -8, scale: 0.97  }}
            transition={{ duration: 0.18, ease: [0.4, 0, 0.2, 1] }}
            className={cn(
              'absolute top-[calc(100%+10px)] z-[300] min-w-[180px]',
              'bg-white/95 backdrop-blur-xl',
              'border border-white/60 rounded-2xl',
              'shadow-[0_18px_40px_rgba(0,0,0,0.10),0_2px_8px_rgba(0,0,0,0.04)]',
              'overflow-hidden py-1.5',
              align === 'right' ? 'right-0' : 'left-0'
            )}
          >
            {items.map((item, i) => {
              if (item.separator) {
                return <hr key={i} className="my-1 border-black/[0.05]" />;
              }

              const content = (
                <>
                  {item.icon && (
                    <span className="w-7 h-7 rounded-lg bg-gray-50 flex items-center justify-center flex-shrink-0 text-sm">
                      {item.icon}
                    </span>
                  )}
                  <span className="flex-1 text-left">{item.label}</span>
                </>
              );

              const baseClass = cn(
                'w-full flex items-center gap-2.5 px-3 py-2 mx-1 rounded-lg text-sm',
                'transition-all duration-150',
                item.danger
                  ? 'text-red-600 hover:bg-red-50'
                  : 'text-[var(--c-text-2)] hover:bg-[rgba(108,92,231,0.06)] hover:text-[var(--c-text)]'
              );

              return item.href ? (
                <a key={i} href={item.href} className={baseClass} onClick={() => setOpen(false)}>
                  {content}
                </a>
              ) : (
                <button
                  key={i}
                  type="button"
                  className={baseClass}
                  onClick={() => { item.onClick?.(); setOpen(false); }}
                >
                  {content}
                </button>
              );
            })}
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
}

export default Dropdown;
