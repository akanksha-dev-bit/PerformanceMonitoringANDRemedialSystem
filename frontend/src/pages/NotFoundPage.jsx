import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { motion } from 'framer-motion';
import { ArrowLeft, ArrowRight } from 'lucide-react';

export default function NotFoundPage() {
  const navigate = useNavigate();
  const [btnOffset, setBtnOffset] = useState({ x: 0, y: 0 });

  const handleMouseMove = (e) => {
    const rect = e.currentTarget.getBoundingClientRect();
    const x = e.clientX - (rect.left + rect.width / 2);
    const y = e.clientY - (rect.top + rect.height / 2);
    setBtnOffset({ x: x * 0.25, y: y * 0.25 });
  };

  const handleMouseLeave = () => {
    setBtnOffset({ x: 0, y: 0 });
  };

  const digitCards = [
    {
      digit: '4',
      rotateX: [0, 12, -6, 0],
      rotateY: [0, -14, 10, 0],
      translateY: [0, -18, 0],
      duration: 6.8,
      delay: 0
    },
    {
      digit: '0',
      rotateX: [0, -14, 12, 0],
      rotateY: [0, 16, -12, 0],
      translateY: [0, 15, 0],
      duration: 7.6,
      delay: 0.35
    },
    {
      digit: '4',
      rotateX: [0, 10, -14, 0],
      rotateY: [0, -12, 16, 0],
      translateY: [0, -22, 0],
      duration: 7.1,
      delay: 0.7
    }
  ];

  return (
    <div className="relative min-h-screen w-full bg-[#faf9f6] text-[#152238] overflow-hidden flex flex-col justify-between selection:bg-[#152238] selection:text-white font-sans">
      <div className="absolute inset-0 pointer-events-none flex items-center justify-center">
        <div
          className="w-[650px] h-[650px] md:w-[850px] md:h-[850px] rounded-full blur-[140px] opacity-80"
          style={{
            background: 'radial-gradient(circle at center, rgba(242, 169, 59, 0.22) 0%, rgba(21, 34, 56, 0.05) 50%, rgba(250, 249, 246, 0) 75%)'
          }}
        />
      </div>

      <div className="absolute inset-0 pointer-events-none bg-[radial-gradient(rgba(21,34,56,0.05)_1px,transparent_1px)] [background-size:24px_24px] opacity-60" />

      <header className="relative z-20 w-full max-w-7xl mx-auto px-6 py-8 flex items-center justify-between">
        <Link to="/" className="flex items-center gap-3 group">
          <img src="/logo.png" alt="PMRS Logo" className="h-9 w-auto object-contain transition-transform duration-300 group-hover:scale-105" />
        </Link>

        <div className="flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/80 border border-black/5 text-xs font-mono text-[#152238]/70 shadow-sm backdrop-blur-md">
          <span className="w-2 h-2 rounded-full bg-[#F2A93B] animate-pulse" />
          <span>HTTP 404 NOT_FOUND</span>
        </div>
      </header>

      <main className="relative z-10 w-full max-w-5xl mx-auto px-6 py-12 flex flex-col items-center text-center my-auto">
        <div className="relative flex items-center justify-center gap-4 sm:gap-6 md:gap-9 mb-14 [perspective:1200px]">
          {digitCards.map((item, idx) => (
            <motion.div
              key={idx}
              initial={{ opacity: 0, scale: 0.85, y: 30 }}
              animate={{
                opacity: 1,
                scale: 1,
                y: item.translateY,
                rotateX: item.rotateX,
                rotateY: item.rotateY
              }}
              transition={{
                opacity: { duration: 0.8, delay: idx * 0.15 },
                scale: { duration: 0.8, delay: idx * 0.15 },
                y: { duration: item.duration, repeat: Infinity, repeatType: 'mirror', ease: 'easeInOut', delay: item.delay },
                rotateX: { duration: item.duration * 1.2, repeat: Infinity, repeatType: 'mirror', ease: 'easeInOut', delay: item.delay },
                rotateY: { duration: item.duration * 1.1, repeat: Infinity, repeatType: 'mirror', ease: 'easeInOut', delay: item.delay }
              }}
              style={{ transformStyle: 'preserve-3d' }}
              className="relative w-28 h-40 sm:w-36 sm:h-52 md:w-44 md:h-64 rounded-3xl backdrop-blur-2xl bg-white/70 border border-[#152238]/10 flex items-center justify-center shadow-[0_20px_50px_rgba(21,34,56,0.08),inset_0_1px_2px_rgba(255,255,255,0.9)] group hover:border-[#152238]/20 transition-colors duration-500"
            >
              <div className="absolute inset-0 rounded-3xl bg-gradient-to-b from-white/60 to-transparent opacity-80 pointer-events-none" />
              <div className="absolute top-3.5 left-3.5 w-2 h-2 rounded-full bg-[#152238]/15" />
              <div className="absolute bottom-3.5 right-3.5 w-2 h-2 rounded-full bg-[#152238]/15" />
              
              <span className="text-7xl sm:text-8xl md:text-9xl font-bold bg-gradient-to-b from-[#152238] via-[#152238]/90 to-[#152238]/60 bg-clip-text text-transparent tracking-tighter select-none font-mono drop-shadow-[0_10px_20px_rgba(21,34,56,0.12)]">
                {item.digit}
              </span>
            </motion.div>
          ))}
        </div>

        <motion.div
          initial={{ opacity: 0, y: 22 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.8, delay: 0.45 }}
          className="max-w-2xl mx-auto flex flex-col items-center"
        >
          <h1
            className="text-4xl sm:text-5xl md:text-6xl font-normal tracking-tight text-[#152238] mb-6 leading-[1.1]"
            style={{ fontFamily: "'Instrument Serif', serif" }}
          >
            This page is marked absent.
          </h1>

          <p className="text-base sm:text-lg text-[#1a1a1a]/70 font-normal leading-relaxed max-w-xl mb-10">
            The curriculum route or academic record you are attempting to locate has been relocated, renamed, or never existed in the PMRS index.
          </p>

          <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
            <motion.div
              onMouseMove={handleMouseMove}
              onMouseLeave={handleMouseLeave}
              animate={{ x: btnOffset.x, y: btnOffset.y }}
              transition={{ type: 'spring', stiffness: 380, damping: 22 }}
            >
              <Link
                to="/dashboard"
                className="inline-flex items-center gap-3 px-8 py-4 rounded-full bg-[#152238] text-white font-semibold text-sm tracking-wide hover:bg-[#1a2b47] transition-colors shadow-[0_10px_30px_rgba(21,34,56,0.22)] group"
              >
                <span>Return to Dashboard</span>
                <ArrowRight className="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" />
              </Link>
            </motion.div>

            <button
              onClick={() => navigate(-1)}
              className="inline-flex items-center gap-2 px-6 py-4 rounded-full bg-white/80 hover:bg-white border border-black/10 text-[#152238] font-semibold text-sm transition-all shadow-sm"
            >
              <ArrowLeft className="w-4 h-4" />
              <span>Go back to class</span>
            </button>
          </div>
        </motion.div>
      </main>

      <footer className="relative z-20 w-full max-w-7xl mx-auto px-6 py-8 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-black/5 text-xs font-mono text-[#152238]/50">
        <div>
          PERFORMANCE MONITORING & REMEDIAL SYSTEM
        </div>
        <div className="flex items-center gap-6">
          <Link to="/" className="hover:text-[#152238] transition-colors">Landing</Link>
          <a href="https://pmrs.live" className="hover:text-[#152238] transition-colors">System Status</a>
          <span>© {new Date().getFullYear()} PMRS</span>
        </div>
      </footer>
    </div>
  );
}
