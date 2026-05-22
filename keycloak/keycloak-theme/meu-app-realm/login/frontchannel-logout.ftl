<#import "template.ftl" as layout>
<@layout.registrationLayout displayMessage=true headerText="Saindo..." subHeaderText="Encerrando suas sessões ativas. Por favor, aguarde.">
  <div class="flex justify-center py-8">
    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-[#111827]"></div>
  </div>
  <#if logout.clients??>
    <div class="hidden">
      <#list logout.clients as client>
        <iframe src="${client.frontChannelLogoutUrl}"></iframe>
      </#list>
    </div>
  </#if>
  <script>
    setTimeout(function() {
      window.location.replace("${logout.logoutRedirectUri}");
    }, 2000);
  </script>
</@layout.registrationLayout>
