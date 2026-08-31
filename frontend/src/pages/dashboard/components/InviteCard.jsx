/**
 * ============================================================================
 * pages/dashboard/components/InviteCard.jsx
 * ============================================================================
 * Student invite link card with real functional QR code, copy, and share actions.
 * ============================================================================
 */

import React, { useState } from 'react';

export default function InviteCard({ school }) {
  const [copied, setCopied] = useState(false);

  const schoolName = school?.name || 'Your School';
  const inviteLink = school?.invite_link || window.location.origin;
  const schoolCode = school?.school_code || 'PMRS-CODE';

  // Functional real QR code encoding the exact inviteLink
  const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?size=140x140&color=4c3d9e&data=${encodeURIComponent(inviteLink)}`;

  const handleCopy = async () => {
    try {
      await navigator.clipboard.writeText(inviteLink);
      setCopied(true);
      setTimeout(() => setCopied(false), 3000);
    } catch (err) {
      console.error('Failed to copy invite link:', err);
    }
  };

  const handleShare = async () => {
    if (navigator.share) {
      try {
        await navigator.share({
          title: `Join ${schoolName}`,
          text: `Use this link to register as a student at ${schoolName}:`,
          url: inviteLink,
        });
      } catch (err) {
        if (err.name !== 'AbortError') console.error('Share failed:', err);
      }
    } else {
      handleCopy();
    }
  };

  return (
    <div className="inv-card">
      {/* Left Stripe */}
      <div className="inv-stripe">
        <div className="inv-stripe-circles"></div>
        <div className="inv-pill">
          <span className="inv-dot"></span>
          <span className="inv-pill-text">Live</span>
        </div>
        <h3 className="inv-stripe-title">Invite<br />Students</h3>
        <p className="inv-stripe-school">{schoolName}</p>
      </div>

      {/* Middle Content - Fills available width naturally */}
      <div className="inv-mid">
        <p className="inv-desc">
          Share this link and students will automatically join{' '}
          <strong>{schoolName}</strong> — no manual setup needed.
        </p>

        <div className="inv-actions">
          <div className="inv-link-box">
            <svg className="inv-link-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
              <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
            </svg>
            <span className="inv-link-val">{inviteLink}</span>
          </div>

          <div style={{ display: 'flex', gap: '10px', flexShrink: 0 }}>
            <button type="button" className="inv-btn-primary" onClick={handleCopy} id="inv-copy-btn">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
                <rect x="9" y="9" width="13" height="13" rx="2" ry="2" />
                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
              </svg>
              <span>{copied ? 'Copied!' : 'Copy Link'}</span>
            </button>

            <button type="button" className="inv-btn-secondary" onClick={handleShare} id="inv-share-btn">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
                <circle cx="18" cy="5" r="3" />
                <circle cx="6" cy="12" r="3" />
                <circle cx="18" cy="19" r="3" />
                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
              </svg>
              Share
            </button>
          </div>
        </div>
      </div>

      {/* Right QR Section with Functional Image QR Code */}
      <div className="inv-qr-panel">
        <div className="inv-qr-frame">
          <img
            src={qrCodeUrl}
            alt={`QR Code for ${inviteLink}`}
            style={{ width: '120px', height: '120px', display: 'block', borderRadius: '6px' }}
          />
        </div>
        <span className="inv-scan-label">Scan to join</span>
        <span className="inv-scan-code">{schoolCode}</span>
      </div>
    </div>
  );
}
