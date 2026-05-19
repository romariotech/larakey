<!DOCTYPE html>
<html lang="${locale!'pt-BR'}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
      Recuperar Senha - ${(realm.displayName!'Larakey')}
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
      .login-card {
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
  <body class="h-screen flex items-center justify-center font-sans p-4 lg:p-8 relative overflow-hidden">
    <div class="w-full max-w-[980px] login-card rounded-md overflow-hidden">
      <div class="flex flex-col lg:flex-row h-full min-h-[580px]">
        <div class="hidden lg:flex lg:w-[44%] bg-[#111827] relative overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-[#111827] via-[#1f2937] to-[#0f172a]">
          </div>
          <div class="absolute -top-20 -right-20 w-72 h-72 bg-indigo-500/10 rounded-full blur-3xl">
          </div>
          <div class="absolute bottom-0 left-0 w-72 h-72 bg-cyan-500/10 rounded-full blur-3xl">
          </div>
          <img
          src="${url.resourcesPath}/svg/background-login.svg"
          alt="Background"
          class="absolute inset-0 w-full h-full object-cover opacity-20 mix-blend-screen" />
          <div class="relative z-10 flex flex-col justify-between p-8 text-white w-full">
            <div class="flex items-center gap-3">
              <div class="bg-white/10 backdrop-blur-md border border-white/10 text-white p-2 rounded-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4">
                  </path>
                </svg>
              </div>
              <div>
                <h2 class="text-lg font-semibold tracking-tight">
                  Larakey
                </h2>
                <p class="text-xs text-slate-300">
                  Authentication Platform
                </p>
              </div>
            </div>
            <div>
              <span class="inline-flex items-center px-3 py-1 rounded-lg bg-white/10 border border-white/10 text-xs text-slate-200 mb-3">
                Account Recovery
              </span>
              <h1 class="text-3xl font-bold leading-tight mb-4">
                Recupere seu acesso
              </h1>
              <p class="text-slate-300 leading-relaxed text-sm">
                Esqueceu sua senha? Não se preocupe. Siga os passos para redefinir suas credenciais de forma segura.
              </p>
            </div>
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 p-5 rounded-md shadow-2xl">
              <p class="text-slate-200 font-light text-sm leading-relaxed">
                “Uma experiência moderna de autenticação construída com Keycloak, Tailwind CSS e FreeMarker.”
              </p>
              <div class="mt-5">
                <h4 class="text-white font-medium text-sm">
                  Romário Oliveira
                </h4>
                <p class="text-slate-400 text-xs mt-1">
                  Full Stack Developer
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="flex-1 bg-white flex items-center justify-center p-6 sm:p-8 lg:p-10">
          <div class="w-full max-w-sm">
            <div class="flex lg:hidden items-center gap-2 mb-8">
              <div class="bg-[#111827] text-white p-3 rounded-md shadow-lg shadow-slate-900/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4">
                  </path>
                </svg>
              </div>
              <div>
                <h2 class="text-lg font-semibold tracking-tight text-slate-900">
                  Larakey
                </h2>
                <p class="text-xs text-slate-500">
                  Authentication Platform
                </p>
              </div>
            </div>
            <div class="mb-6">
              <h1 class="text-3xl font-bold text-slate-900 tracking-tight mb-3">
                Esqueceu a senha?
              </h1>
              <p class="text-sm text-slate-500 leading-relaxed">
                Informe o seu e-mail ou nome de usuário. Enviaremos um link seguro para você criar uma nova senha.
              </p>
            </div>
            <#if message?has_content && (message.type != 'warning' || !isAppInitiatedAction??)>
              <div class="mb-5 px-4 py-3 rounded-md border text-sm font-medium tracking-wide
              ${((message.type = 'error')?then(
                'bg-red-50 border-red-100 text-red-700',
                'bg-slate-50 border-slate-200 text-slate-700'
              ))}">
                ${kcSanitize(message.summary)?no_esc}
              </div>
            </#if>
            <form action="${url.loginAction}" method="post" class="space-y-5">
              <div>
                <label for="username" class="block text-sm font-medium text-slate-700 mb-2">
                  E-mail ou Usuário
                </label>
                <input
                  type="text"
                  id="username"
                  name="username"
                  value="${(auth.attemptedUsername!'')}"
                  autofocus
                  autocomplete="username"
                  placeholder="seu@email.com"
                  class="w-full bg-white border border-slate-200 rounded-md px-4 py-3 text-slate-900 text-sm transition-all focus:outline-none focus:ring-4 focus:ring-[#111827]/5 focus:border-[#111827] placeholder:text-slate-400" />
              </div>
              <button
                type="submit"
                class="w-full bg-[#111827] hover:bg-[#1f2937] text-white font-medium py-3 rounded-md transition-all duration-200 shadow-lg shadow-slate-900/10 hover:scale-[1.01] active:scale-[0.99]">
                Enviar e-mail de recuperação
              </button>
              <div class="text-center pt-2">
                <a href="${url.loginUrl}" class="text-sm font-medium text-slate-500 hover:text-[#111827] transition-colors inline-flex items-center gap-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                  </svg>
                  Voltar para o login
                </a>
              </div>
            </form>
            <div class="lg:hidden text-center mt-8">
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
