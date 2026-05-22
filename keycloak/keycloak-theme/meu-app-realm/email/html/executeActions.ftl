<!DOCTYPE html>
<html lang="${locale!'pt-BR'}">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
      ${msg("emailExecuteActionsTitle")}
    </title>
     <style>
      * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      }
      body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
      line-height: 1.6;
      color: #1e293b;
      background-color: #f1f5f9;
      }
      .container {
      max-width: 600px;
      margin: 0 auto;
      background: #ffffff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 10px 40px rgba(15, 23, 42, 0.1);
      }
      .header {
      background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
      padding: 40px 30px;
      text-align: center;
      position: relative;
      overflow: hidden;
      }
      .header::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 300px;
      height: 300px;
      background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
      border-radius: 50%;
      }
      .header::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 300px;
      height: 300px;
      background: radial-gradient(circle, rgba(34, 197, 94, 0.08) 0%, transparent 70%);
      border-radius: 50%;
      }
      .logo {
      position: relative;
      z-index: 1;
      display: inline-flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 20px;
      }
      .logo-icon {
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      padding: 8px;
      border-radius: 8px;
      }
      .logo-icon svg {
      width: 24px;
      height: 24px;
      color: #ffffff;
      }
      .logo-text h2 {
      font-size: 18px;
      font-weight: 600;
      color: #ffffff;
      letter-spacing: -0.3px;
      }
      .logo-text p {
      font-size: 12px;
      color: #cbd5e1;
      margin-top: 2px;
      }
      .header h1 {
      position: relative;
      z-index: 1;
      font-size: 28px;
      font-weight: 700;
      color: #ffffff;
      letter-spacing: -0.5px;
      margin-bottom: 10px;
      }
      .header-subtitle {
      position: relative;
      z-index: 1;
      font-size: 14px;
      color: #cbd5e1;
      }
      .body {
      padding: 40px 30px;
      }
      .greeting {
      font-size: 16px;
      color: #1e293b;
      margin-bottom: 24px;
      font-weight: 500;
      }
      .message {
      font-size: 14px;
      color: #475569;
      line-height: 1.8;
      margin-bottom: 32px;
      }
      .cta-section {
      text-align: center;
      margin-bottom: 32px;
      }
      .cta-button {
      display: inline-block;
      background: linear-gradient(to right, #111827, #1f2937);
      color: #ffffff;
      text-decoration: none;
      padding: 14px 40px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 14px;
      letter-spacing: 0.5px;
      box-shadow: 0 10px 25px rgba(17, 24, 39, 0.2);
      transition: all 0.3s ease;
      display: inline-block;
      }
      .cta-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 15px 35px rgba(17, 24, 39, 0.3);
      }
      .alternative-link {
      text-align: center;
      margin-top: 16px;
      font-size: 12px;
      color: #64748b;
      }
      .alternative-link a {
      color: #4f46e5;
      text-decoration: none;
      font-weight: 600;
      word-break: break-all;
      }
      .alternative-link a:hover {
      text-decoration: underline;
      }
      .warning-box {
      background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
      border-left: 4px solid #f59e0b;
      padding: 16px;
      border-radius: 6px;
      margin-bottom: 24px;
      font-size: 13px;
      color: #92400e;
      }
      .warning-box strong {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
      }
      .security-info {
      background: #f0f9ff;
      border-left: 4px solid #0284c7;
      padding: 16px;
      border-radius: 6px;
      margin-bottom: 24px;
      font-size: 13px;
      color: #0c4a6e;
      }
      .security-info strong {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
      }
      .footer {
      background: #f8fafc;
      padding: 24px 30px;
      text-align: center;
      border-top: 1px solid #e2e8f0;
      }
      .footer-text {
      font-size: 12px;
      color: #64748b;
      margin-bottom: 12px;
      }
      .footer-divider {
      width: 40px;
      height: 1px;
      background: #cbd5e1;
      margin: 12px auto;
      }
      .footer-legal {
      font-size: 11px;
      color: #94a3b8;
      }
      .divider {
      height: 1px;
      background: #e2e8f0;
      margin: 24px 0;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <!-- Header -->
      <div class="header">
        <div class="logo">
          <div class="logo-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4">
              </path>
            </svg>
          </div>
          <div class="logo-text">
            <h2>
              Larakey
            </h2>
            <p>
              Authentication Platform
            </p>
          </div>
        </div>
        <h1>
          ${msg("emailExecuteActionsTitle")}
        </h1>
        <p class="header-subtitle">
          ${msg("emailExecuteActionsMsg")}
        </p>
      </div>
      <!-- Body -->
      <div class="body">
        <p class="greeting">
          Olá,
        </p>
        <p class="message">
          ${msg("emailExecuteActionsBody", user.firstName, actions)}
        </p>
        <!-- Security Info -->
        <div class="security-info">
          <strong>
            🔒 Segurança em primeiro lugar
          </strong>
          Este link expira em ${linkExpirationHours!'24'} horas por segurança. Se você não solicitou isso, pode ignorar com segurança este e-mail.
        </div>
        <!-- CTA Button -->
        <div class="cta-section">
          <a href="${link}" class="cta-button">
            Executar ações
          </a>
        </div>
        <!-- Alternative Link -->
        <div class="alternative-link">
          <p>
            Ou copie este link em seu navegador:
          </p>
          <a href="${link}">
            ${link}
          </a>
        </div>
        <div class="divider">
        </div>
        <!-- Warning Box -->
        <div class="warning-box">
          <strong>
            ⚠️ Aviso importante
          </strong>
          Nunca compartilhe este link com ninguém. A Larakey nunca pedirá sua senha por e-mail.
        </div>
        <!-- Additional Info -->
        <p class="message">
          Se você não solicitou esta ação, pode ignorar este e-mail com segurança. Sua conta permanecerá protegida.
        </p>
      </div>
      <!-- Footer -->
      <div class="footer">
        <p class="footer-text">
          <strong>
            Larakey
          </strong>
          - Plataforma de Autenticação Segura
        </p>
        <div class="footer-divider">
        </div>
        <p class="footer-legal">
          © ${.now?string("yyyy")} Larakey. Todos os direitos reservados.
          <br>
          Este é um e-mail automático, por favor não responda.
        </p>
      </div>
    </div>
  </body>
</html>
