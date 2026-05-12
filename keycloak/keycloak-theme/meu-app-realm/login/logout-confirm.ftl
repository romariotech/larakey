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
      radial-gradient(circle at top left, rgba(99,102,241,.08), transparent 30%),
      radial-gradient(circle at bottom right, rgba(239,68,68,.06), transparent 30%),
      #f1f5f9;
      }
      .logout-card {
      backdrop-filter: blur(10px);
      background: rgba(255,255,255,.92);
      border: 1px solid rgba(255,255,255,.5);
      box-shadow:
      0 20px 60px rgba(15,23,42,.08),
      0 6px 20px rgba(15,23,42,.04);
      animation: fadeIn .35s ease;
      }
      @keyframes fadeIn {
      from {
      opacity: 0;
      transform: translateY(12px);
      }
      to {
      opacity: 1;
      transform: translateY(0);
      }
      }
    </style>
  </head>
  <body class="h-screen flex items-center justify-center font-sans p-4 sm:p-8 relative overflow-hidden">
    <div class="w-full max-w-[1000px] logout-card rounded-md overflow-hidden">
      <div class="flex flex-col lg:flex-row h-full min-h-[580px]">
        <!-- Left Side -->
        <div class="hidden lg:flex lg:w-[42%] bg-[#111827] relative overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-[#111827] via-[#1f2937] to-[#0f172a]">
          </div>
          <div class="absolute -top-20 -right-20 w-72 h-72 bg-indigo-500/10 rounded-md blur-3xl">
          </div>
          <div class="absolute bottom-0 left-0 w-72 h-72 bg-red-500/10 rounded-md blur-3xl">
          </div>
          <img
          src="${url.resourcesPath}/svg/background-login.svg"
          alt="Background"
          class="absolute inset-0 w-full h-full object-cover opacity-20 mix-blend-screen" />
          <div class="relative z-10 flex flex-col justify-between p-10 text-white w-full">
            <div class="flex items-center gap-3">
              <div class="bg-white/10 backdrop-blur-md border border-white/10 text-white p-3 rounded-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4">
                  </path>
                </svg>
              </div>
              <div>
                <h2 class="text-xl font-semibold tracking-tight">
                  Larakey
                </h2>
                <p class="text-sm text-slate-300">
                  Authentication Platform
                </p>
              </div>
            </div>
            <div>
              <h1 class="text-4xl font-bold leading-tight mb-5">
                Encerrando sua sessão com segurança
              </h1>
              <p class="text-slate-300 leading-relaxed text-sm">
                Sua sessão será finalizada em todos os dispositivos conectados a esta aplicação.
              </p>
            </div>
            <div class="text-xs text-slate-400">
              © ${.now?string("yyyy")} Larakey
            </div>
          </div>
        </div>
        <!-- Right Side -->
        <div class="flex-1 bg-white flex items-center justify-center p-8 sm:p-12 lg:p-16">
          <div class="w-full max-w-sm">
            <!-- Mobile Logo -->
            <div class="flex lg:hidden items-center gap-3 mb-10">
              <div class="bg-[#111827] text-white p-3 rounded-md shadow-lg shadow-slate-900/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <!-- Content -->
            <div class="mb-8">
              <div class="w-16 h-16 rounded-md bg-red-100 flex items-center justify-center mb-6">
                <svg
                class="w-8 h-8 text-red-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                  <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V7">
                  </path>
                </svg>
              </div>
              <h1 class="text-3xl font-bold text-slate-900 tracking-tight mb-4">
                Sair da conta
              </h1>
              <p class="text-sm text-slate-500 leading-relaxed">
                Você está prestes a encerrar sua sessão atual.
                Será necessário autenticar novamente para acessar a plataforma.
              </p>
            </div>
            <!-- Message -->
            <#if message?has_content>
              <div class="mb-6 px-4 py-3 rounded-md border text-sm font-medium tracking-wide
              ${((message.type = 'error')?then(
                'bg-red-50 border-red-100 text-red-700',
                'bg-slate-50 border-slate-200 text-slate-700'
              ))}">
                ${kcSanitize(message.summary)?no_esc}
              </div>
            </#if>
            <!-- Form -->
            <form
            action="${url.logoutConfirmAction}"
            method="POST"
            class="space-y-4">
              <input
              type="hidden"
              name="session_code"
              value="${logoutConfirm.code}">
              <button
              type="submit"
              name="confirmLogout"
              id="kc-logout"
              class="w-full bg-[#111827] hover:bg-red-600 text-white font-medium py-3.5 rounded-md transition-all duration-200 shadow-lg shadow-slate-900/10 hover:scale-[1.01] active:scale-[0.99]">
                Sim, encerrar minha sessão
              </button>
              <a
              href="${url.loginUrl}"
              class="w-full inline-flex justify-center items-center py-3.5 rounded-md border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-all text-sm font-medium">
                Não, voltar para a aplicação
              </a>
            </form>
            <!-- Mobile Footer -->
            <div class="lg:hidden text-center mt-10">
              <div class="w-24 h-px bg-slate-200 mx-auto mb-4">
              </div>
              <p class="text-xs text-slate-400 tracking-wide">
                © ${.now?string("yyyy")} Larakey. Todos os direitos reservados.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
