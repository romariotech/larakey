<#import "template.ftl" as layout>
<@layout.registrationLayout displayMessage=true headerText="Bem-vindo!" subHeaderText="Entre com suas credenciais para acessar sua conta.">
  <form action="${url.loginAction}" method="post" class="space-y-4">
    <div>
      <label for="username" class="block text-sm font-medium text-slate-700 mb-2">
        <#if !realm.loginWithEmailAllowed>Username<#elseif !realm.registrationEmailAsUsername>E-mail ou Username<#else>E-mail</#if>
      </label>
      <input type="text" id="username" name="username" value="${(login.username!'')}" autofocus autocomplete="off" placeholder="seu@email.com" class="w-full bg-white border border-slate-200 rounded-md px-4 py-3 text-slate-900 text-sm transition-all focus:outline-none focus:ring-4 focus:ring-[#111827]/5 focus:border-[#111827] placeholder:text-slate-400" />
    </div>

    <div>
      <div class="flex items-center justify-between mb-2">
        <label for="password" class="block text-sm font-medium text-slate-700">Senha</label>
        <#if realm.resetPasswordAllowed>
          <a href="${url.loginResetCredentialsUrl}" class="text-xs font-medium text-slate-500 hover:text-[#111827] transition-colors">
            Esqueceu a senha?
          </a>
        </#if>
      </div>
      <div class="relative">
        <input type="password" id="password" name="password" autocomplete="off" placeholder="Digite sua senha" class="w-full bg-white border border-slate-200 rounded-md px-4 py-3 text-slate-900 text-sm transition-all focus:outline-none focus:ring-4 focus:ring-[#111827]/5 focus:border-[#111827] placeholder:text-slate-400" />
      </div>
    </div>

    <#if realm.rememberMe && !usernameHidden??>
      <div class="flex items-center justify-between pt-1">
        <label class="flex items-center gap-3 cursor-pointer">
          <input type="checkbox" name="rememberMe" id="rememberMe" <#if login.rememberMe??>checked</#if> class="w-4 h-4 rounded border-slate-300 text-[#111827] focus:ring-[#111827]/20">
          <span class="text-sm text-slate-600">Lembrar sessão</span>
        </label>
      </div>
    </#if>

    <button type="submit" class="w-full bg-[#111827] hover:bg-[#1f2937] text-white font-medium py-3 rounded-md transition-all duration-200 shadow-lg shadow-slate-900/10 hover:scale-[1.01] active:scale-[0.99]">
      Entrar
    </button>
  </form>
</@layout.registrationLayout>
