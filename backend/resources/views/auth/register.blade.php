<x-guest-layout>
  <x-slot name="title">Create your school account</x-slot>

  <div class="register-shell">
    <header class="register-header">
      <div class="register-brand" aria-label="PMRS">
        <img src="{{ asset('logo.png') }}" alt="PMRS" width="132" height="33">
        <span>pmrsteam.official@gmail.com</span>
      </div>
      <div class="register-header-action">
        <span>Already have an account?</span>
        <a href="{{ route('login') }}">Sign in <span aria-hidden="true">↗</span></a>
      </div>
    </header>

    <main class="register-main">
      <section class="register-card" aria-labelledby="register-title">
        <div class="register-intro">
          <h1 id="register-title">Create your school account</h1>
          <p>Set up your institution and administrator profile. You can review your plan before any payment.</p>
        </div>

        @if ($errors->any())
          <div class="register-alert" role="alert">
            <strong>Please check your details.</strong>
            <span>Review the highlighted fields and try again.</span>
          </div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="register-form">
          @csrf

          <div class="register-section-heading">
            <span class="register-step">01</span>
            <div>
              <h2>Choose your plan</h2>
              <p>Select the starting point that fits your school.</p>
            </div>
          </div>

          <div class="register-plan-panel">
            <div class="register-field">
              <label for="selected-plan">School plan</label>
              <select id="selected-plan" name="plan" required aria-invalid="{{ $errors->has('plan') ? 'true' : 'false' }}">
                @foreach ($plans as $plan)
                  <option value="{{ $plan['slug'] }}"
                    data-price="₹{{ number_format($plan['annual_price_inr']) }}"
                    data-limit="{{ number_format($plan['student_limit']) }}"
                    data-features="{{ implode(', ', array_map(fn ($feature) => str_replace('_', ' ', $feature), array_slice($plan['features'], 6))) }}"
                    @selected(old('plan', $selectedPlan) === $plan['slug'])>{{ $plan['name'] }}</option>
                @endforeach
              </select>
              @error('plan')<span class="register-error">{{ $message }}</span>@enderror
            </div>
            <div class="register-plan-stats" aria-live="polite">
              <div><span>Annual plan</span><strong id="plan-price"></strong></div>
              <div><span>Student capacity</span><strong id="plan-limit"></strong></div>
            </div>
            <p class="register-plan-features" id="plan-features"></p>
            <p class="register-plan-note">You’ll see the final amount and any setup fee before checkout.</p>
          </div>

          <div class="register-section-heading register-details-heading">
            <span class="register-step">02</span>
            <div>
              <h2>School &amp; admin details</h2>
              <p>We’ll use these details to create your workspace.</p>
            </div>
          </div>

          <div class="register-fields">
            <div class="register-field">
              <label for="school-name">School or institution name</label>
              <input id="school-name" type="text" name="school_name" value="{{ old('school_name') }}" placeholder="e.g. Greenwood Public School" autocomplete="organization" required aria-invalid="{{ $errors->has('school_name') ? 'true' : 'false' }}">
              @error('school_name')<span class="register-error">{{ $message }}</span>@enderror
            </div>

            <div class="register-field-grid">
              <div class="register-field">
                <label for="admin-name">Your full name</label>
                <input id="admin-name" type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Ananya Sharma" autocomplete="name" required aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}">
                @error('name')<span class="register-error">{{ $message }}</span>@enderror
              </div>
              <div class="register-field">
                <label for="admin-email">Work email</label>
                <input id="admin-email" type="email" name="email" value="{{ old('email', request('email')) }}" placeholder="name@school.edu" autocomplete="email" inputmode="email" required aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}">
                @error('email')<span class="register-error">{{ $message }}</span>@enderror
              </div>
            </div>

            <div class="register-field-grid">
              <div class="register-field">
                <label for="password-input">Password</label>
                <div class="register-password-wrap">
                  <input id="password-input" type="password" name="password" placeholder="Create a password" autocomplete="new-password" required aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}">
                  <button type="button" class="register-password-toggle" data-toggle-password="password-input" aria-label="Show password" aria-pressed="false">Show</button>
                </div>
                @error('password')<span class="register-error">{{ $message }}</span>@enderror
              </div>
              <div class="register-field">
                <label for="password-confirmation">Confirm password</label>
                <div class="register-password-wrap">
                  <input id="password-confirmation" type="password" name="password_confirmation" placeholder="Repeat your password" autocomplete="new-password" required>
                  <button type="button" class="register-password-toggle" data-toggle-password="password-confirmation" aria-label="Show confirmed password" aria-pressed="false">Show</button>
                </div>
              </div>
            </div>
            <p class="register-password-hint">Use at least 8 characters with upper and lower case letters, a number, and a symbol.</p>
          </div>

          <button type="submit" class="register-submit">Create account <span aria-hidden="true">→</span></button>
          <p class="register-submit-note">No payment is taken on this page.</p>
        </form>
      </section>
      <aside class="register-visual" aria-label="Illustrative school analytics preview">
        <div class="register-visual-copy">
          <span class="register-visual-kicker">A clearer view of your school</span>
          <h2>Make every school day count.</h2>
          <p>Bring attendance and academic progress together in one calm, connected workspace.</p>
        </div>
        <div class="register-visual-stage">
          <div class="register-visual-ring" aria-hidden="true"></div>
          <img class="register-educator" src="{{ asset('images/register-educator.webp') }}" alt="Illustration of a school administrator seated on a sofa, working on a laptop" width="1536" height="1024" loading="lazy" decoding="async">

          <div class="register-metric register-metric-attendance">
            <span class="register-metric-label">Student attendance</span>
            <div class="register-metric-value">94.8% <span>↗ 4.2%</span></div>
            <svg class="register-bars" viewBox="0 0 136 36" fill="none" aria-hidden="true">
              <rect x="0" y="17" width="12" height="19" rx="4" fill="#F8D7B4"/><rect x="20" y="10" width="12" height="26" rx="4" fill="#F6BC83"/><rect x="40" y="14" width="12" height="22" rx="4" fill="#F8D7B4"/><rect x="60" y="5" width="12" height="31" rx="4" fill="#F3A96D"/><rect x="80" y="9" width="12" height="27" rx="4" fill="#F6BC83"/><rect x="100" y="2" width="12" height="34" rx="4" fill="#EE985E"/><rect x="120" y="0" width="12" height="36" rx="4" fill="#E98A50"/>
            </svg>
          </div>

          <div class="register-metric register-metric-performance">
            <span class="register-metric-label">Class performance</span>
            <div class="register-metric-value">88% <span>↗ 6.4%</span></div>
            <svg class="register-line" viewBox="0 0 150 42" fill="none" aria-hidden="true">
              <path d="M0 34C14 32 18 18 31 21C43 24 49 33 62 25C75 17 79 25 91 17C103 9 109 18 120 10C132 2 141 12 150 3" stroke="#54B989" stroke-width="3" stroke-linecap="round"/>
              <path d="M0 34C14 32 18 18 31 21C43 24 49 33 62 25C75 17 79 25 91 17C103 9 109 18 120 10C132 2 141 12 150 3V42H0V34Z" fill="url(#metricArea)"/>
              <defs><linearGradient id="metricArea" x1="75" y1="0" x2="75" y2="42" gradientUnits="userSpaceOnUse"><stop stop-color="#B9E8CF" stop-opacity=".48"/><stop offset="1" stop-color="#B9E8CF" stop-opacity="0"/></linearGradient></defs>
            </svg>
          </div>
        </div>
        <p class="register-visual-caption">Illustrative dashboard preview</p>
      </aside>
    </main>

    <footer class="register-footer">
      <span>© {{ date('Y') }} PMRS</span>
      <a href="mailto:pmrsteam.official@gmail.com">Need help? Contact us</a>
    </footer>
  </div>

  <style>
    .auth-page { display: block; padding: 0; overflow: visible; background: #fbf9f2; }
    .auth-page::before { display: none; }
    .register-shell {
      --register-ink: #151515;
      --register-muted: #68645f;
      --register-accent: #ffcc99;
      min-height: 100vh;
      min-height: 100dvh;
      overflow: hidden;
      background: #fbf9f2;
      color: var(--register-ink);
      font-family: 'Poppins', 'Inter', sans-serif;
    }
    .register-header, .register-footer {
      width: min(calc(100% - 72px), 1400px);
      margin-inline: auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 24px;
    }
    .register-header { min-height: 84px; }
    .register-brand { display: flex; align-items: center; gap: 18px; }
    .register-brand img { width: 118px; height: auto; }
    .register-brand span { padding-left: 18px; border-left: 1px solid #e4ddd3; color: var(--register-muted); font-size: 11px; font-weight: 500; }
    .register-header-action { display: flex; align-items: center; gap: 13px; color: var(--register-muted); font-size: 12px; }
    .register-header-action a {
      display: inline-flex; align-items: center; gap: 8px; min-height: 40px; padding: 8px 15px;
      border: 1px solid #e9dbc9; border-radius: 10px; background: #fff; color: var(--register-ink);
      font-weight: 700; transition: background .2s, transform .2s;
    }
    .register-header-action a:hover { background: #fff2e4; transform: translateY(-1px); }
    .register-main {
      width: min(calc(100% - 72px), 1400px);
      margin: 0 auto;
      padding: 24px 0 36px;
      display: grid;
      grid-template-columns: minmax(0, 1.02fr) minmax(0, .98fr);
      align-items: start;
      gap: clamp(30px, 4vw, 68px);
    }
    .register-card {
      width: 100%;
      padding: clamp(28px, 3vw, 44px);
      border: 1px solid #eee7df;
      border-radius: 22px;
      background: #fff;
      box-shadow: 0 18px 48px rgba(74, 57, 35, .09);
    }
    .register-intro { margin-bottom: 27px; }
    .register-eyebrow { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 12px; color: #9b622f; font-size: 10px; font-weight: 700; letter-spacing: .11em; text-transform: uppercase; }
    .register-eyebrow-dot { width: 7px; height: 7px; border-radius: 50%; background: #eca767; box-shadow: 0 0 0 4px #fff2e6; }
    .register-intro h1 { margin: 0 0 8px; color: #111; font-size: clamp(25px, 2.5vw, 31px); font-weight: 700; letter-spacing: -.04em; line-height: 1.25; }
    .register-intro p { max-width: 520px; color: var(--register-muted); font-size: 12px; line-height: 1.65; }
    .register-alert { display: flex; flex-direction: column; gap: 3px; margin-bottom: 24px; padding: 12px 15px; border: 1px solid #f2c6c6; border-radius: 10px; background: #fff6f4; color: #a2333b; font-size: 12px; }
    .register-section-heading { display: flex; align-items: flex-start; gap: 11px; margin-bottom: 13px; }
    .register-step { display: inline-grid; place-items: center; flex: 0 0 28px; height: 28px; border-radius: 8px; background: #fff1e3; color: #a9652e; font-size: 10px; font-weight: 700; }
    .register-section-heading h2 { margin: 0; font-size: 13px; font-weight: 700; line-height: 1.4; }
    .register-section-heading p { color: var(--register-muted); font-size: 11px; line-height: 1.5; }
    .register-plan-panel { padding: 17px; border: 1px solid #f0e5d9; border-radius: 13px; background: #fffcf8; }
    .register-field { min-width: 0; }
    .register-field label { display: block; margin-bottom: 6px; color: #292723; font-size: 11px; font-weight: 700; }
    .register-field input, .register-field select {
      width: 100%; height: 44px; padding: 0 12px; border: 1px solid #e5e1dc; border-radius: 9px;
      outline: none; background: #fff; color: var(--register-ink);
      font: 500 12px 'Poppins', 'Inter', sans-serif; transition: border-color .2s, box-shadow .2s;
    }
    .register-field input::placeholder { color: #a7a19a; font-weight: 400; }
    .register-field input:hover, .register-field select:hover { border-color: #c8b9a8; }
    .register-field input:focus, .register-field select:focus { border-color: #e8a76c; box-shadow: 0 0 0 3px rgba(255, 204, 153, .35); }
    .register-field input[aria-invalid="true"], .register-field select[aria-invalid="true"] { border-color: #d95151; }
    .register-error { display: block; margin-top: 6px; color: #b93939; font-size: 11px; line-height: 1.45; }
    .register-plan-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 13px; padding-top: 13px; border-top: 1px solid #efe8de; }
    .register-plan-stats div { display: flex; flex-direction: column; gap: 2px; }
    .register-plan-stats span { color: var(--register-muted); font-size: 10px; }
    .register-plan-stats strong { color: #2d2924; font-size: 13px; font-weight: 700; }
    .register-plan-features { margin-top: 10px; color: #58534d; font-size: 10px; line-height: 1.55; }
    .register-plan-note { margin-top: 7px; color: var(--register-muted); font-size: 10px; line-height: 1.5; }
    .register-details-heading { margin-top: 24px; }
    .register-fields { display: grid; gap: 14px; }
    .register-field-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
    .register-password-wrap { position: relative; }
    .register-password-wrap input { padding-right: 61px; }
    .register-password-toggle {
      position: absolute; top: 50%; right: 6px; transform: translateY(-50%);
      min-width: 48px; min-height: 32px; border: 0; border-radius: 6px; background: transparent;
      color: #895526; font: 700 10px 'Poppins', 'Inter', sans-serif; cursor: pointer;
    }
    .register-password-toggle:hover { background: #fff1e3; }
    .register-password-toggle:focus-visible, .register-submit:focus-visible,
    .register-header-action a:focus-visible, .register-footer a:focus-visible { outline: 3px solid #e9a764; outline-offset: 3px; }
    .register-password-hint { margin-top: -4px; color: var(--register-muted); font-size: 10px; line-height: 1.5; }
    .register-submit {
      display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; min-height: 49px;
      margin-top: 23px; border: 0; border-radius: 10px; background: var(--register-accent); color: #111;
      font: 700 13px 'Poppins', 'Inter', sans-serif; cursor: pointer;
      transition: background .2s, transform .2s, box-shadow .2s;
    }
    .register-submit:hover { background: #f6b878; box-shadow: 0 8px 18px rgba(209, 137, 65, .16); transform: translateY(-1px); }
    .register-submit-note { margin-top: 10px; text-align: center; color: var(--register-muted); font-size: 10px; }
    .register-visual { min-width: 0; align-self: start; position: sticky; top: 20px; padding-top: 18px; }
    .register-visual-copy { max-width: 470px; margin: 0 auto; text-align: center; }
    .register-visual-kicker { color: #aa6b35; font-size: 10px; font-weight: 700; letter-spacing: .11em; text-transform: uppercase; }
    .register-visual-copy h2 { margin: 8px 0 7px; font-size: clamp(24px, 2.5vw, 35px); line-height: 1.22; letter-spacing: -.04em; }
    .register-visual-copy p { color: var(--register-muted); font-size: 12px; line-height: 1.6; }
    .register-visual-stage { position: relative; width: 100%; height: clamp(360px, 35vw, 500px); margin-top: 8px; }
    .register-visual-stage::before {
      content: ""; position: absolute; right: 2%; bottom: 5%; width: 80px; height: 100px;
      background-image: radial-gradient(#e4b98c 1.6px, transparent 1.6px); background-size: 17px 17px; opacity: .5;
    }
    .register-visual-ring { position: absolute; z-index: 0; top: 9%; left: 50%; width: 68%; aspect-ratio: 1; border: 1px solid #e9ded2; border-radius: 50%; transform: translateX(-50%); }
    .register-visual-ring::after { content: ""; position: absolute; inset: 12%; border: 1px solid #f0e8df; border-radius: 50%; }
    .register-educator { position: absolute; z-index: 1; left: 50%; bottom: 0; width: min(108%, 730px); max-width: none; height: auto; transform: translateX(-50%); filter: drop-shadow(0 18px 18px rgba(80, 62, 47, .08)); }
    .register-metric {
      position: absolute; z-index: 2; width: 176px; padding: 15px 16px 11px;
      border: 1px solid #f2eee8; border-radius: 12px; background: #fff;
      box-shadow: 0 15px 38px rgba(68, 57, 44, .12);
    }
    .register-metric-attendance { top: 15%; left: 0; transform: rotate(-3deg); }
    .register-metric-performance { top: 30%; right: 0; transform: rotate(3deg); }
    .register-metric-label { display: block; color: #69645e; font-size: 10px; font-weight: 600; }
    .register-metric-value { display: flex; align-items: baseline; justify-content: space-between; gap: 5px; margin-top: 5px; color: #23211f; font-size: 22px; font-weight: 700; line-height: 1.2; }
    .register-metric-value span { color: #299364; font-family: 'Inter', sans-serif; font-size: 10px; font-weight: 700; white-space: nowrap; }
    .register-bars, .register-line { display: block; width: 100%; height: 39px; margin-top: 8px; }
    .register-visual-caption { margin-top: -4px; color: #8a8278; font-size: 10px; text-align: center; }
    .register-footer { padding: 8px 0 24px; color: #706b65; font-size: 10px; }
    .register-footer a { color: #51463c; font-weight: 600; }
    .register-footer a:hover { color: #a9652e; }
    @media (max-width: 980px) {
      .register-main { width: min(calc(100% - 36px), 640px); display: block; padding: 14px 0 30px; }
      .register-visual { display: none; }
      .register-header, .register-footer { width: min(calc(100% - 36px), 640px); }
      .register-card { padding: clamp(25px, 5vw, 38px); }
    }
    @media (max-width: 560px) {
      .register-header { min-height: 72px; }
      .register-brand img { width: 108px; }
      .register-brand span, .register-header-action > span { display: none; }
      .register-main { width: calc(100% - 24px); padding-top: 6px; }
      .register-card { padding: 26px 20px; border-radius: 17px; }
      .register-field-grid { grid-template-columns: 1fr; gap: 14px; }
      .register-field input, .register-field select { font-size: 16px; }
      .register-plan-panel { padding: 15px; }
    }
    @media (max-width: 380px) { .register-plan-stats { grid-template-columns: 1fr; } }
    @media (prefers-reduced-motion: reduce) { .register-shell * { scroll-behavior: auto; transition: none !important; } }
  </style>

  <script>
    (() => {
      const select = document.getElementById('selected-plan');
      const updatePlanSummary = () => {
        const plan = select.selectedOptions[0];
        if (!plan) return;
        document.getElementById('plan-price').textContent = plan.dataset.price + ' / year';
        document.getElementById('plan-limit').textContent = 'Up to ' + plan.dataset.limit + ' students';
        document.getElementById('plan-features').textContent = 'Plan highlights: ' + plan.dataset.features + '.';
      };
      select.addEventListener('change', updatePlanSummary);
      updatePlanSummary();

      document.querySelectorAll('[data-toggle-password]').forEach((button) => {
        button.addEventListener('click', () => {
          const input = document.getElementById(button.dataset.togglePassword);
          const showing = input.type === 'password';
          input.type = showing ? 'text' : 'password';
          button.textContent = showing ? 'Hide' : 'Show';
          button.setAttribute('aria-label', (showing ? 'Hide' : 'Show') + (input.id === 'password-confirmation' ? ' confirmed password' : ' password'));
          button.setAttribute('aria-pressed', String(showing));
        });
      });
    })();
  </script>
</x-guest-layout>
