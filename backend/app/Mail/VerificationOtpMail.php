<?php
/**
 * ============================================================================
 * VerificationOtpMail — Email for OTP Verification
 * ============================================================================
 *
 * PURPOSE:
 *   Sends a time-sensitive One-Time Password (OTP) to users for email verification
 *   during registration or password reset.
 *
 * HOW IT WORKS:
 *   1. Constructor: Initializes with OTP and user name
 *   2. envelope(): Sets the email subject
 *   3. content(): Renders the verify-otp blade template
 *   4. attachments(): Returns empty array (no attachments)
 *
 * USAGE IN CONTROLLERS:
 *   // Send OTP to student
 *   $otp = rand(100000, 999999);
 *   Mail::to($student->email)->send(new VerificationOtpMail($otp, $student->name));
 *
 * RELATED FILES:
 *   - View:       resources/views/emails/verify-otp.blade.php
 *   - Controller: Registration & password reset controllers
 *   - Mail config: config/mail.php
 * ============================================================================
 */
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $name;

    /**
     * Create a new message instance.
     */
    public function __construct($otp, $name)
    {
        $this->otp = $otp;
        $this->name = $name;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify Your Email Address - PMRS',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.verify-otp',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
