<div
    class="w-[1224px] max-w-[1224px] max-xl:w-full max-xl:px-6 max-lg:px-4 max-md:px-3 max-sm:px-3 mx-auto flex flex-row max-lg:flex-col gap-5 max-lg:gap-8 max-md:gap-6 max-sm:gap-5 py-9 max-lg:py-7 max-md:py-6 max-sm:py-5">
    <div class="w-full max-lg:order-1">
        <div
            class="flex flex-col gap-8 max-lg:gap-6 max-md:gap-5 max-sm:gap-4 pt-16 max-lg:pt-8 max-md:pt-6 max-sm:pt-4 max-lg:text-center">
            <h2
                class="font-semibold text-[32px] max-lg:text-[28px] max-md:text-[24px] max-sm:text-[20px] font-custom! max-w-[450px] max-lg:max-w-full max-lg:mx-auto break-words leading-tight">
                {{request('lang') == 'en' ? $homeInfo->title_seccion_tres_en : $homeInfo->title_seccion_tres_es}}
            </h2>
            <p
                class="text-[20px] max-lg:text-[18px] max-md:text-[16px] max-sm:text-[15px] max-w-[528px] max-lg:max-w-full max-lg:mx-auto leading-relaxed text-gray-700">
                {!!request('lang') == 'en' ? $homeInfo->text_seccion_tres_en : $homeInfo->text_seccion_tres_es !!}
            </p>

        </div>
    </div>
    <div class="w-full max-lg:order-2">
        <!-- Contenedor del formulario con padding responsive -->
        <div
            class="h-full min-h-[400px] max-lg:min-h-[350px] max-md:min-h-[300px] flex items-center justify-center bg-gray-50 max-lg:bg-transparent rounded-lg max-lg:rounded-none p-6 max-lg:p-4 max-md:p-3 max-sm:p-2">
            <div class="w-full max-w-[500px] max-lg:max-w-full">
                <script data-b24-form="inline/1/c7h29z" data-skip-moving="true">
                    (function (w, d, u) {
                        var s = d.createElement('script'); s.async = true; s.src = u + '?' + (Date.now() / 180000 | 0);
                        var h = d.getElementsByTagName('script')[0]; h.parentNode.insertBefore(s, h);
                    })(window, document, 'https://cdn.bitrix24.es/b7493823/crm/form/loader_1.js');
                </script>
            </div>
        </div>
    </div>
</div>

<!-- Estilos adicionales para el formulario Bitrix24 responsive -->
<style>
    /* Estilos para hacer responsive el formulario de Bitrix24 */
    @media (max-width: 1024px) {
        .b24-form-wrapper {
            max-width: 100% !important;
            margin: 0 !important;
        }

        .b24-form {
            max-width: 100% !important;
            padding: 1rem !important;
        }

        .b24-form-field {
            margin-bottom: 1rem !important;
        }

        .b24-form-control {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            padding: 0.75rem !important;
            font-size: 14px !important;
        }

        .b24-form-btn {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.875rem 1rem !important;
            font-size: 14px !important;
        }
    }

    @media (max-width: 768px) {
        .b24-form {
            padding: 0.75rem !important;
        }

        .b24-form-control {
            padding: 0.625rem !important;
            font-size: 13px !important;
        }

        .b24-form-btn {
            padding: 0.75rem 1rem !important;
            font-size: 13px !important;
        }

        .b24-form-field-container {
            margin-bottom: 0.75rem !important;
        }
    }

    @media (max-width: 640px) {
        .b24-form {
            padding: 0.5rem !important;
        }

        .b24-form-control {
            padding: 0.5rem !important;
            font-size: 12px !important;
        }

        .b24-form-btn {
            padding: 0.75rem !important;
            font-size: 12px !important;
        }

        .b24-form-field-label {
            font-size: 12px !important;
        }
    }

    /* Asegurar que el formulario no se desborde */
    .b24-form-wrapper,
    .b24-form,
    .b24-form * {
        box-sizing: border-box !important;
    }

    /* Ajustar altura mínima del formulario en diferentes breakpoints */
    .b24-form-wrapper {
        min-height: auto !important;
    }

    /* Mejorar la apariencia visual del formulario */
    @media (min-width: 1024px) {
        .b24-form-wrapper {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    }
</style>