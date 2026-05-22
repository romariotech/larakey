<#import "template.ftl" as layout>
<@layout.registrationLayout displayMessage=false headerText="Ops, algo deu errado!" subHeaderText="Ocorreu um erro durante o processo.">

    <div class="bg-red-50 border border-red-100 text-red-700 px-4 py-3 rounded-md text-sm font-medium tracking-wide mb-6">
        ${kcSanitize(message.summary)?no_esc}
    </div>

    <#if client?? && client.baseUrl?has_content>
        <a href="${client.baseUrl}" class="flex items-center justify-center w-full bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium py-3 rounded-md transition-all duration-200 shadow-sm">
            Voltar para o aplicativo
        </a>
    </#if>
</@layout.registrationLayout>
