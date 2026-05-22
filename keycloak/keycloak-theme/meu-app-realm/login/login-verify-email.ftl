<#import "template.ftl" as layout>
<@layout.registrationLayout displayMessage=true headerText="Verifique seu e-mail" subHeaderText="Precisamos confirmar seu endereço de e-mail para continuar.">
  <div class="text-sm text-slate-600 mb-6 leading-relaxed">
    <p>Um e-mail com instruções para verificação foi enviado para o endereço cadastrado.</p>
  </div>

  <div class="border-t border-slate-200 pt-6">
    <p class="text-sm text-slate-500 mb-3">Não recebeu o código de verificação?</p>
    <a href="${url.loginAction}" class="block text-center w-full bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium py-3 rounded-md transition-all duration-200 shadow-sm">
      Reenviar e-mail
    </a>
  </div>
</@layout.registrationLayout>
