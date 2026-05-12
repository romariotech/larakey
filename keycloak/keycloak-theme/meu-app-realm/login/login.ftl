<!DOCTYPE html>
<html lang="${locale!'pt-BR'}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
      ${msg("loginTitle",(realm.displayName!''))}
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
        <!-- LEFT -->
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
            <!-- Logo -->
            <div class="flex items-center gap-3">
              <div class="bg-white/10 backdrop-blur-md border border-white/10 text-white p-2 rounded-md">
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
                <h2 class="text-lg font-semibold tracking-tight">
                  Larakey
                </h2>
                <p class="text-xs text-slate-300">
                  Authentication Platform
                </p>
              </div>
            </div>
            <!-- Center -->
            <div>
              <span class="inline-flex items-center px-3 py-1 rounded-lg bg-white/10 border border-white/10 text-xs text-slate-200 mb-3">
                Secure Authentication
              </span>
              <h1 class="text-3xl font-bold leading-tight mb-4">
                Acesse sua plataforma com segurança
              </h1>
              <p class="text-slate-300 leading-relaxed text-sm">
                Gerencie autenticação, sessões e acesso de usuários em um ambiente moderno e seguro.
              </p>
            </div>
            <!-- Footer -->
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
        <!-- RIGHT -->
        <div class="flex-1 bg-white flex items-center justify-center p-6 sm:p-8 lg:p-10">
          <div class="w-full max-w-sm">
            <!-- Mobile Logo -->
            <div class="flex lg:hidden items-center gap-2 mb-8">
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
                <h2 class="text-lg font-semibold tracking-tight text-slate-900">
                  Larakey
                </h2>
                <p class="text-xs text-slate-500">
                  Authentication Platform
                </p>
              </div>
            </div>
            <!-- Header -->
            <div class="mb-6">
              <h1 class="text-3xl font-bold text-slate-900 tracking-tight mb-3">
                Bem-vindo!
              </h1>
              <p class="text-sm text-slate-500 leading-relaxed">
                Entre com suas credenciais para acessar sua conta.
              </p>
            </div>
            <!-- Alerts -->
            <#if message?has_content && (message.type != 'warning' || !isAppInitiatedAction??)>
              <div class="mb-5 px-4 py-3 rounded-md border text-sm font-medium tracking-wide
              ${((message.type = 'error')?then(
                'bg-red-50 border-red-100 text-red-700',
                'bg-slate-50 border-slate-200 text-slate-700'
              ))}">
                ${kcSanitize(message.summary)?no_esc}
              </div>
            </#if>
            <!-- FORM -->
            <form
            action="${url.loginAction}"
            method="post"
            class="space-y-4">
              <!-- Email -->
              <div>
                <label
                for="username"
                class="block text-sm font-medium text-slate-700 mb-2">
                  E-mail
                </label>
                <input
                type="text"
                id="username"
                name="username"
                value="${(login.username!'')}"
                autofocus
                autocomplete="off"
                placeholder="seu@email.com"
                class="w-full bg-white border border-slate-200 rounded-md px-4 py-3 text-slate-900 text-sm transition-all focus:outline-none focus:ring-4 focus:ring-[#111827]/5 focus:border-[#111827] placeholder:text-slate-400" />
              </div>
              <!-- Password -->
              <div>
                <div class="flex items-center justify-between mb-2">
                  <label
                  for="password"
                  class="block text-sm font-medium text-slate-700">
                    Senha
                  </label>
                  <a
                  href="#"
                  class="text-xs font-medium text-slate-500 hover:text-[#111827] transition-colors">
                    Esqueceu a senha?
                  </a>
                </div>
                <div class="relative">
                  <input
                  type="password"
                  id="password"
                  name="password"
                  autocomplete="off"
                  placeholder="Digite sua senha"
                  class="w-full bg-white border border-slate-200 rounded-md px-4 py-3 pr-12 text-slate-900 text-sm transition-all focus:outline-none focus:ring-4 focus:ring-[#111827]/5 focus:border-[#111827] placeholder:text-slate-400" />
                  <button
                  type="button"
                  class="absolute inset-y-0 right-4 flex items-center text-slate-400 hover:text-slate-700 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.8"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.8"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </button>
                </div>
              </div>
              <!-- Remember -->
              <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-3 cursor-pointer">
                  <input
                  type="checkbox"
                  class="w-4 h-4 rounded border-slate-300 text-[#111827] focus:ring-[#111827]/20">
                  <span class="text-sm text-slate-600">
                    Lembrar sessão
                  </span>
                </label>
              </div>
              <!-- Submit -->
              <button
              type="submit"
              class="w-full bg-[#111827] hover:bg-[#1f2937] text-white font-medium py-3 rounded-md transition-all duration-200 shadow-lg shadow-slate-900/10 hover:scale-[1.01] active:scale-[0.99]">
                Entrar
              </button>
            </form>
            <!-- Mobile Footer -->
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
