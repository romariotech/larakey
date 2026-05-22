<#import "template.ftl" as layout>
<@layout.registrationLayout displayMessage=true headerText="Atualizar Senha" subHeaderText="Por favor, defina uma nova senha para sua conta.">
  <form action="${url.loginAction}" method="post" class="space-y-4">
    <input type="text" id="username" name="username" value="${username}" autocomplete="username" readonly="readonly" style="display:none;"/>
    <input type="password" id="password" name="password" autocomplete="current-password" style="display:none;"/>

    <div>
      <label for="password-new" class="block text-sm font-medium text-slate-700 mb-2">Nova Senha</label>
      <input type="password" id="password-new" name="password-new" autofocus autocomplete="new-password" class="w-full bg-white border border-slate-200 rounded-md px-4 py-3 text-slate-900 text-sm transition-all focus:outline-none focus:ring-4 focus:ring-[#111827]/5 focus:border-[#111827]" />
    </div>

    <div>
      <label for="password-confirm" class="block text-sm font-medium text-slate-700 mb-2">Confirmar Nova Senha</label>
      <input type="password" id="password-confirm" name="password-confirm" autocomplete="new-password" class="w-full bg-white border border-slate-200 rounded-md px-4 py-3 text-slate-900 text-sm transition-all focus:outline-none focus:ring-4 focus:ring-[#111827]/5 focus:border-[#111827]" />
    </div>

    <button type="submit" class="w-full bg-[#111827] hover:bg-[#1f2937] text-white font-medium py-3 rounded-md transition-all duration-200 shadow-lg shadow-slate-900/10 hover:scale-[1.01] active:scale-[0.99]">
      Salvar nova senha
    </button>
  </form>
</@layout.registrationLayout>
