/**
 * ============================================================================
 * components/ui/ContentSkeleton.jsx — Content-Area Progressive Skeleton
 * ============================================================================
 * Rendered inside <AppLayout /> while a lazy route chunk or page content is loading.
 * Keeps Navbar, Sidebar, and background UI intact while showing a content placeholder.
 * ============================================================================
 */

import React from 'react';

export default function ContentSkeleton() {
  return (
    <div className="w-full px-6 py-8 space-y-6 animate-pulse">
      {/* Header Skeleton */}
      <div className="bg-white/80 border border-slate-200/80 rounded-2xl p-6 flex justify-between items-center shadow-sm">
        <div className="space-y-2">
          <div className="h-7 w-48 bg-slate-200 rounded-lg"></div>
          <div className="h-4 w-72 bg-slate-100 rounded-md"></div>
        </div>
        <div className="h-10 w-32 bg-slate-200 rounded-xl"></div>
      </div>

      {/* Toolbar / Filters Skeleton */}
      <div className="bg-white/80 border border-slate-200/80 rounded-2xl p-5 flex flex-wrap gap-4 items-center shadow-sm">
        <div className="h-9 w-44 bg-slate-100 rounded-lg"></div>
        <div className="h-9 w-44 bg-slate-100 rounded-lg"></div>
        <div className="h-9 w-28 bg-slate-200 rounded-lg ml-auto"></div>
      </div>

      {/* Content Table / Data Card Skeleton */}
      <div className="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden p-6 space-y-4">
        <div className="h-8 w-full bg-slate-100 rounded-lg"></div>
        <div className="h-12 w-full bg-slate-50 rounded-lg"></div>
        <div className="h-12 w-full bg-slate-50 rounded-lg"></div>
        <div className="h-12 w-full bg-slate-50 rounded-lg"></div>
        <div className="h-12 w-full bg-slate-50 rounded-lg"></div>
      </div>
    </div>
  );
}
