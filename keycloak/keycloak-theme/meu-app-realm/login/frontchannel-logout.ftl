<!DOCTYPE html>
<html lang="${locale!'pt-BR'}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
      ${msg("logoutConfirmTitle",(realm.displayName!''))}
    </title>
    <link rel="stylesheet" href="${url.resourcesPath}/css/custom.css">
    <link rel="icon" type="image/x-icon" href="${url.resourcesPath}/favicon.ico" />
    <style>
      body {
      background:
      radial-gradient(circle at top left, rgba(99,102,241,.15), transparent 30%),
      radial-gradient(circle at bottom right, rgba(239,68,68,.10), transparent 30%),
      #f8fafc;
      }
      .logout-card {
      backdrop-filter: blur(10px);
      background: rgba(255,255,255,.85);
      border: 1px solid rgba(255,255,255,.4);
      box-shadow:
      0 10px 40px rgba(15,23,42,.08),
      0 2px 10px rgba(15,23,42,.04);
      animation: fadeIn .35s ease;
      }
      @keyframes fadeIn {
      from {
      opacity: 0;
      transform: translateY(10px);
      }
      to {
      opacity: 1;
      transform: translateY(0);
      }
      }
    </style>
  </head>
  <body class="min-h-screen flex items-center justify-center p-6 font-sans text-slate-800">
    <ul style="display: none;">
      <#if logout.clients??>
        <#list logout.clients as client>
          <li>
            <iframe src="${client.frontChannelLogoutUrl}" style="display:none;">
            </iframe>
          </li>
        </#list>
      </#if>
    </ul>
    <div class="w-full max-w-md">
      <div class="flex items-center justify-center gap-3 mb-8">
        <div class="bg-[#111827] text-white p-3 rounded-md shadow-lg shadow-slate-900/20">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4">
            </path>
          </svg>
        </div>
        <div>
          <h2 class="text-xl font-semibold tracking-tight text-slate-900">
            Larakey
          </h2>
          <p class="text-sm text-slate-500">
            Authentication Platform
          </p>
        </div>
      </div>
      <div class="logout-card rounded-md p-8 sm:p-10">
        <div class="flex justify-center mb-6">
          <div class="size-10 rounded-md bg-amber-50 flex items-center justify-center">
            <svg fill="hsl(39, 100%, 51%)" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <g>
                <rect x="11" y="1" width="2" height="5" opacity=".14"/>
                <rect x="11" y="1" width="2" height="5" transform="rotate(30 12 12)" opacity=".29"/>
                <rect x="11" y="1" width="2" height="5" transform="rotate(60 12 12)" opacity=".43"/>
                <rect x="11" y="1" width="2" height="5" transform="rotate(90 12 12)" opacity=".57"/>
                <rect x="11" y="1" width="2" height="5" transform="rotate(120 12 12)" opacity=".71"/>
                <rect x="11" y="1" width="2" height="5" transform="rotate(150 12 12)" opacity=".86"/>
                <rect x="11" y="1" width="2" height="5" transform="rotate(180 12 12)"/>
                <animateTransform attributeName="transform" type="rotate" calcMode="discrete" dur="0.75s" values="0 12 12;30 12 12;60 12 12;90 12 12;120 12 12;150 12 12;180 12 12;210 12 12;240 12 12;270 12 12;300 12 12;330 12 12;360 12 12" repeatCount="indefinite"/>
              </g>
            </svg>
          </div>
        </div>
        <div class="text-center">
          <h1 class="text-3xl font-bold text-slate-900 mb-3 tracking-tight">
            Encerrando sessão
          </h1>
          <p class="text-slate-500 text-sm leading-relaxed mb-6">
            Sua sessão está sendo finalizada com segurança. Você será redirecionado em breve.
          </p>
          <div class="bg-amber-50 border border-amber-100 rounded-md px-5 py-4">
            <p class="text-sm text-amber-700 font-medium">
              Por favor, aguarde...
            </p>
          </div>
        </div>
      </div>
      <div class="text-center mt-6">
        <p class="text-xs text-slate-400">
          © ${.now?string("yyyy")} Larakey. Todos os direitos reservados.
        </p>
      </div>
    </div>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
      var redirectUrl =
      <#if logout.logoutRedirectUri?has_content>
        "${logout.logoutRedirectUri}"
      <#else>
        "${url.loginUrl}"
      </#if>
      ;
      setTimeout(function() {
      window.location.replace(redirectUrl);
      }, 2000);
      });
    </script>
  </body>
</html>
