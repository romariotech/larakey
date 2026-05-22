<#import "template.ftl" as layout>
<@layout.registrationLayout displayMessage=true headerText="Esqueceu a senha?" subHeaderText="Digite seu e-mail e enviaremos um link para redefinir sua senha.">
  <form action="${url.loginAction}" method="post" class="space-y-4">
    <div>
      <label for="username" class="block text-sm font-medium text-slate-700 mb-2">E-mail</label>
      <input type="text" id="username" name="username" autofocus autocomplete="email" placeholder="seu@email.com" class="w-full bg-white border border-slate-200 rounded-md px-4 py-3 text-slate-900 text-sm transition-all focus:outline-none focus:ring-4 focus:ring-[#111827]/5 focus:border-[#111827] placeholder:text-slate-400" />
    </div>

    <button type="submit" class="w-full bg-[#111827] hover:bg-[#1f2937] text-white font-medium py-3 rounded-md transition-all duration-200 shadow-lg shadow-slate-900/10 hover:scale-[1.01] active:scale-[0.99]">
      Enviar link de recuperação
    </button>

    <div class="text-center pt-2">
      <a href="${url.loginUrl}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">
        Voltar para o login
      </a>
    </div>
  </form>
</@layout.registrationLayout>
