<#import "template.ftl" as layout>
<@layout.registrationLayout displayMessage=true headerText="Sair da Conta" subHeaderText="Você tem certeza que deseja encerrar sua sessão?">
  <form action="${url.logoutConfirmAction}" method="POST" class="space-y-4">
    <input type="hidden" name="session_code" value="${logoutConfirm.code}">

    <div class="flex gap-3">
      <button type="submit" name="confirmLogout" id="confirmLogout" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-3 rounded-md transition-all duration-200 shadow-lg shadow-red-600/20">
        Sim, quero sair
      </button>
      <button type="submit" name="cancelLogout" id="cancelLogout" class="flex-1 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium py-3 rounded-md transition-all duration-200">
        Cancelar
      </button>
    </div>
  </form>
</@layout.registrationLayout>
