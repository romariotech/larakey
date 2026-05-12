<!DOCTYPE html>
<html lang="${locale!'pt-BR'}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
      ${msg("errorTitle",(realm.displayName!''))}
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
      .error-card {
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
      <div class="error-card rounded-md p-8 sm:p-10">
        <div class="flex justify-center mb-6">
          <div class="w-16 h-16 rounded-md bg-red-100 flex items-center justify-center">
            <svg
            class="w-8 h-8 text-red-600"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24">
              <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 9v4m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z">
              </path>
            </svg>
          </div>
        </div>
        <div class="text-center">
          <h1 class="text-3xl font-bold text-slate-900 mb-3 tracking-tight">
            Algo deu errado
          </h1>
          <p class="text-slate-500 text-sm leading-relaxed mb-6">
            Ocorreu um problema durante a autenticação ou processamento da solicitação.
          </p>
          <div class="bg-red-50 border border-red-100 rounded-md px-5 py-4 mb-8">
            <p class="text-sm text-red-700 font-medium leading-relaxed">
              ${kcSanitize(message.summary)?no_esc}
            </p>
          </div>
          <div class="space-y-3">
            <#if client?? && client.baseUrl?has_content>
              <a
              href="${client.baseUrl}"
              class="w-full inline-flex justify-center items-center gap-2 bg-[#111827] hover:bg-[#1f2937] text-white font-medium py-3.5 px-5 rounded-md transition-all duration-200 shadow-lg shadow-slate-900/10 hover:scale-[1.01] active:scale-[0.99]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 7l5 5m0 0l-5 5m5-5H6">
                  </path>
                </svg>
                ${kcSanitize(msg("backToApplication"))?no_esc}
              </a>
            <#else>
              <button
              onclick="window.history.back()"
              class="w-full inline-flex justify-center items-center gap-2 bg-[#111827] hover:bg-[#1f2937] text-white font-medium py-3.5 px-5 rounded-md transition-all duration-200 shadow-lg shadow-slate-900/10 hover:scale-[1.01] active:scale-[0.99]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10 19l-7-7m0 0l7-7m-7 7h18">
                  </path>
                </svg>
                Voltar
              </button>
            </#if>
          </div>
        </div>
      </div>
      <div class="text-center mt-6">
        <p class="text-xs text-slate-400">
          © ${.now?string("yyyy")} Larakey. Todos os direitos reservados.
        </p>
      </div>
    </div>
  </body>
</html>
